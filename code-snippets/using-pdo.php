<?php
	// define a variable to switch on/off error messages
	$pdoDebug = true;

	try {
		// connect to your database
		$pdo = new PDO('mysql:dbname=mydatabase;host=localhost', 'myuser', 'mypassword');
	}
	catch (PDOException $exception) {
		// unlike mysql/mysqli, pdo throws an exception when it is unable to connect
		echo '<p>There was an error connecting to the database!</p>';
		if ($pdoDebug) {
			// pdo's exception provides more information than just a message
			// including getFile() and getLine()
			echo $exception->getMessage();
		}
	}

	if ($pdoDebug) {
		// $pdo->query() returns FALSE if there is an error
		// to get more information, the following will enable exceptions
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	// create a query that should return a single record
	// the backticks around the table and column names are optional
	// they are required only when a name matches a reserved word (e.g. `date`)
	$query = 'SELECT * FROM `mytable` ORDER BY `mycolumn` LIMIT 1';

	// execute the query
	// query returns FALSE on error, and a result object on success
	try {
		$pdoStatement = $pdo->query($query);
	}
	catch (PDOException $exception) {
		// the query failed and debugging is enabled
		echo "<p>There was an error in query: $query</p>";
		echo $exception->getMessage();
		$pdoStatement = false;
	}

	if ($pdoStatement) {
		// the query was successful
		// get the result (if any)
		// fetchObject returns FALSE if there is no record
		if ($recordObj = $pdoStatement->fetchObject()) {
			// we have a record so now we can use it
			// the columns are properties of the object
			echo $recordObj->mycolumn;
		}
		else {
			echo '<p>No record found.</p>';
		}

		// when you are done with the statement, close it
		$pdoStatement->closeCursor();
	}

	// now get multiple records
	$query = 'SELECT * FROM `mytable` ORDER BY `mycolumn` LIMIT 10';

	try {
		$pdoStatement = $pdo->query($query);
	}
	catch (PDOException $exception) {
		// the query failed and debugging is enabled
		echo "<p>There was an error in query: $query</p>";
		echo $exception->getMessage();
		$pdoStatement = false;
	}

	if ($pdoStatement) {
		// perhaps you want to check if there are any rows available
		if ($pdoStatement->rowCount() == 0) {
			echo '<p>No records found.</p>';
		}
		else {
			while ($recordObj = $pdoStatement->fetchObject()) {
				echo $recordObj->mycolumn;
			}
		}

		$pdoStatement->closeCursor();
	}

	// clean up any objects
	unset($pdoStatement);
	unset($pdo);
?>