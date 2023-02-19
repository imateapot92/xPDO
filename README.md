# xPDO
xPDO è una estensione di PHP. Al momento include esclusivamente un sistema per semplificare la gestione dei prefissi dei nomi delle tabelle.

### Installazione
Puoi installare xPDO tramite Composer:

`composer require imateapot/xpdo`

### Utilizzo
Per utilizzare xPDO, devi prima includere la classe:

    use imateapot\xPDO;

Quindi, puoi creare una nuova istanza di xPDO come segue:

    use imateapot\xPDO;
    $db = new xPDO($dsn, $user, $password, $options, $xOptions);

dove $dsn, $user, $password, $options sono rispettivamente i parametri standard per la creazione di un'istanza di PDO, il parametro opzionale $xOptions contiene le opzioni aggiuntive per xPDO.

Puoi utilizzare xPDO esattamente come utilizzeresti PDO, ma con la possibilità di specificare l'utilizzo del prefisso nei nomi delle tabella utilizzando la sintassi `<nome_tabella>`.

Ad esempio:

    ...
	$xOptions = [
		'table_prefix' => 'prefix_'
	];
	$db = new xPDO($dsn, $user, $password, $options, $xOptions);
	$stmt = $db->prepare('SELECT * FROM <my_table> WHERE id = :id');
    $stmt->execute(['id' => $id]);

In questo esempio, il prefisso della tabella specificato in $xOptions['table_prefix'] sarà aggiunto automaticamente al nome della tabella "my_table".
