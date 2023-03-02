<?php
namespace imateapot;
use PDO;
use PDOStatement;
use PDOException;


class xPDO extends PDO {
  protected $_xOptions;
  protected $_xTransactionDepth = 0;

  public function __construct(string $dsn, ?string $user = null, ?string $password = null, ?array $options = null, ?array $xOptions = null) {
    $this->_xOptions = $xOptions??[];
    parent::__construct($dsn, $user, $password, $options);
  }
  
  protected function _xHasSavepoint() {
    return in_array($this->getAttribute(PDO::ATTR_DRIVER_NAME),["pgsql", "mysql"]);
  }

  public function beginTransaction():bool {
    if($this->_xTransactionDepth == 0 || !$this->_xHasSavepoint()) {
      $this->_xTransactionDepth++;
      return parent::beginTransaction();
    }
    else {
      $this->exec("SAVEPOINT LEVEL{$this->_xTransactionDepth}");
      $this->_xTransactionDepth++;
      return true;
    }
  }

  public function commit():bool {
    $this->_xTransactionDepth--;
    if($this->_xTransactionDepth == 0 || !$this->_xHasSavepoint()) return parent::commit();
    else $this->exec("RELEASE SAVEPOINT LEVEL{$this->_xTransactionDepth}");
    return true;
  }

  public function rollBack():bool {
    if ($this->_xTransactionDepth == 0) throw new PDOException('Rollback error : There is no transaction started');
    $this->_xTransactionDepth--;
    if($this->_xTransactionDepth == 0 || !$this->_xHasSavepoint()) return parent::rollBack();
    else $this->exec("ROLLBACK TO SAVEPOINT LEVEL{$this->_xTransactionDepth}");
    return true;
  }

  protected function _xPrefixTables($statement) {
    return preg_replace('/\<([\p{L}\p{N}@$#\-_]*[\p{L}@$#\-_]+[\p{L}\p{N}@$#\-_]*)\>/u', @$this->_xOptions['table_prefix'].'$1', $statement);
  }

  public function exec(string $statement): int|false {
    return parent::exec($this->_xPrefixTables($statement));
  }

  public function prepare(string $query, array $options = []): PDOStatement|false {
    return parent::prepare($this->_xPrefixTables($query), $options);
  }
  
  public function query(string $query, ?int $fetchMode = null, ...$fetchModeArgs): PDOStatement|false {
    return parent::query($this->_xPrefixTables($query), $fetchMode,...$fetchModeArgs);
  }

}
