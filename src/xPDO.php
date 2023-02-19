<?php
namespace imateapot;
use PDO;
use PDOStatement;

class xPDO extends PDO {
  protected $_xOptions;

  public function __construct(string $dsn, ?string $user = null, ?string $password = null, ?array $options = null, ?array $xOptions = null) {
    $this->_xOptions = $xOptions??[];
    parent::__construct($dsn, $user, $password, $options);
  }

  protected function _prefixTables($statement) {
    return preg_replace('/\<([\p{L}\p{N}@$#\-_]*[\p{L}@$#\-_]+[\p{L}\p{N}@$#\-_]*)\>/u', @$this->_xOptions['table_prefix'].'$1', $statement);
  }

  public function exec(string $statement): int|false {
    return parent::exec($this->_prefixTables($statement));
  }

  public function prepare(string $query, array $options = []): PDOStatement|false {
    return parent::prepare($this->_prefixTables($query), $options);
  }
  
  public function query(string $query, ?int $fetchMode = null, ...$fetchModeArgs): PDOStatement|false {
    return parent::query($this->_prefixTables($query), $fetchMode,...$fetchModeArgs);
  }

}