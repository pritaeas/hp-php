<?php
	// define a variable to switch on/off error messages
	$sqliteDebug = true;

	// connect to your database
    // both the file and the folder it is in has to be writable
	$dbLink = sqlite_open('sqlite2.db', 0666, $sqliteError);

	// sqlite_open will return false if the connect failed
	if (!$dbLink) {
		echo '<p>There was an error connecting to the database!</p>';
		if ($sqliteDebug) {
			// $sqliteError returns the latest error message,
			// hopefully clarifying the problem
			echo $sqliteError;
		}

		// since there is no database connection your queries will fail,
		// quit processing
		die();
	}

	// create a query that should return a single record
	$query = 'SELECT * FROM mytable ORDER BY mycolumn LIMIT 1';

	// execute the query
	// passing the connection link as a parameter is optional
	// but useful if you are accessing more than one database
	// sqlite_query returns false on error, and a resource on success
	$result = sqlite_query($dbLink, $query, SQLITE_BOTH, $sqliteError);
	if (!$result and $sqliteDebug) {
		// the query failed and debugging is enabled
		echo "<p>There was an error in query: $query</p>";
		echo $sqliteError;
	}
	if ($result) {
		// the query was successful
		// get the result (if any)
		// sqlite_fetch_array returns false if there is no record
		if ($record = sqlite_fetch_array($result)) {
			// we have a record so now we can use it
			// output the column by index
			echo $record[0];
			// output the column by name
			echo $record['mycolumn'];
		}
		else {
			echo '<p>No record found.</p>';
		}
	}

	// now get multiple records
	$query = 'SELECT * FROM mytable ORDER BY mycolumn LIMIT 10';

	$result = sqlite_query($dbLink, $query, SQLITE3_BOTH, $sqliteError);
	if (!$result and $sqliteDebug) {
		echo "<p>There was an error in query: $query</p>";
		echo $sqliteError;
	}
	if ($result) {
		// perhaps you want to check if there are any rows available
		$recordCount = sqlite_num_rows($result);
		if ($recordCount == 0) {
			echo '<p>No records found.</p>';
		}
		else {
			while ($record = sqlite_fetch_array($result)) {
				echo $record[0];
				echo $record['mycolumn'];
			}
		}
	}

	// close the database connection (preferred)
	sqlite_close($dbLink);
?>
