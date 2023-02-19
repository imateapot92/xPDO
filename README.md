# xPDO
xPDO is a PHP extension that currently only includes a system to simplify the management of table name prefixes.

## Installation
You can install xPDO via Composer:

`composer require imateapot/xpdo`

## Usage
To use xPDO, you first need to include the class.
Then, you can create a new xPDO instance like this:

    use imateapot\xPDO;
    $db = new xPDO($dsn, $user, $password, $options, $xOptions);
where $dsn, $user, $password, $options are the standard parameters for creating a PDO instance, and the optional parameter $xOptions contains additional options for xPDO.

You can use xPDO exactly as you would use PDO, but with the ability to specify the use of table name prefixes using the syntax `<table_name>`.

For example:

    ...
    $xOptions = [
    	'table_prefix' => 'prefix_'
    ];
    $db = new xPDO($dsn, $user, $password, $options, $xOptions);
    $stmt = $db->prepare('SELECT * FROM <my_table> WHERE id = :id');
    $stmt->execute(['id' => $id]);
the table prefix specified in` $xOptions['table_prefix']` will be automatically added to the table name "my_table".
