<?php
	// define a variable to switch on/off error messages
	$sqliteDebug = true;

	try {
		// connect to your database
		$sqlite = new SQLite3('test.sqlite3.db');
	}
	catch (Exception $exception) {
		// unlike sqlite3 throws an exception when it is unable to connect
		echo '<p>There was an error connecting to the database!</p>';
		if ($sqliteDebug) {
			echo $exception->getMessage();
		}
	}

	// create a query that should return a single record
	$query = 'SELECT * FROM mytable ORDER BY mycolumn LIMIT 1';

	// execute the query
	// query returns FALSE on error, and a result object on success
	$sqliteResult = $sqlite->query($query);
	if (!$sqliteResult and $sqliteDebug) {
        // the query failed and debugging is enabled
        echo "<p>There was an error in query: $query</p>";
        echo $sqlite->lastErrorMsg();
    }

	if ($sqliteResult) {
		// the query was successful
		// get the result (if any)
		// fetchArray returns FALSE if there is no record
		if ($record = $sqliteResult->fetchArray()) {
			// we have a record so now we can use it
			echo $record['mycolumn'];
		}
		else {
			echo '<p>No record found.</p>';
		}

		// when you are done with the result, finalize it
		$sqliteResult->finalize();
	}

	// now get multiple records
	$query = 'SELECT * FROM mytable ORDER BY mycolumn LIMIT 10';

	$sqliteResult = $sqlite->query($query);
	if (!$sqliteResult and $sqliteDebug) {
		// the query failed and debugging is enabled
        echo "<p>There was an error in query: $query</p>";
        echo $sqlite->lastErrorMsg();
	}

	if ($sqliteResult) {
        while ($record = $sqliteResult->fetchArray()) {
            echo $record['mycolumn'];
        }

		$sqliteResult->finalize();
	}

	// clean up any objects
	$sqlite->close();
?>