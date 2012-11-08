<?php
	// define a variable to switch on/off error messages
	$mysqlDebug = true;

	// connect to your database
	$dbLink = mysql_connect('localhost', 'myuser', 'mypassword');

	// mysql_connect will return false if the connect failed
	if (!$dbLink) {
		echo '<p>There was an error connecting to the database!</p>';
		if ($mysqlDebug) {
			// mysql_error returns the latest error message,
			// hopefully clarifying the problem
			echo mysql_error();
		}

		// since there is no database connection your queries will fail,
		// quit processing
		die();
	}

	// if you use a single database, selecting it will simplify your queries
	// mysql_select_db will return false if the database could not be selected
	if (!mysql_select_db('mydatabase')) {
		echo '<p>There was an error selecting the database!</p>';
		if ($mysqlDebug) {
			echo mysql_error();
		}

		// since there is no database selected your queries will fail,
		// close the connection and quit processing
		mysql_close($dbLink);
		die();
	}

	// create a query that should return a single record
	// the backticks around the table and column names are optional
	// they are required only when a name matches a reserved word (e.g. `date`)
	$query = 'SELECT * FROM `mytable` ORDER BY `mycolumn` LIMIT 1';

	// execute the query
	// passing the connection link as a parameter is optional
	// but useful if you are accessing more than one database
	// mysql_query returns false on error, and a resource on success
	$result = mysql_query($query, $dbLink);
	if (!$result and $mysqlDebug) {
		// the query failed and debugging is enabled
		echo "<p>There was an error in query: $query</p>";
		echo mysql_error();
	}
	if ($result) {
		// the query was successful
		// get the result (if any)
		// mysql_fetch_array returns false if there is no record
		if ($record = mysql_fetch_array($result)) {
			// we have a record so now we can use it
			// output the column by index
			echo $record[0];
			// output the column by name
			echo $record['mycolumn'];
		}
		else {
			echo '<p>No record found.</p>';
		}

		// when you are done with the result, free it
		// it is optional, but preferred
		mysql_free_result($result);
	}

	// now get multiple records
	$query = 'SELECT * FROM `mytable` ORDER BY `mycolumn` LIMIT 10';

	$result = mysql_query($query, $dbLink);
	if (!$result and $mysqlDebug) {
		echo "<p>There was an error in query: $query</p>";
		echo mysql_error();
	}
	if ($result) {
		// perhaps you want to check if there are any rows available
		$recordCount = mysql_num_rows($result);
		if ($recordCount == 0) {
			echo '<p>No records found.</p>';
		}
		else {
			while ($record = mysql_fetch_array($result)) {
				echo $record[0];
				echo $record['mycolumn'];
			}
		}
		mysql_free_result($result);
	}

	// close the database connection (preferred)
	// closing is optional, but not closing the link
	// will keep the connection lingering until it times out
	mysql_close($dbLink);
?>
