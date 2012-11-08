<?php
	// define a variable to switch on/off error messages
	$mysqliDebug = true;

	// connect to your database
	// if you use a single database, passing it will simplify your queries
	$mysqli = @new mysqli('localhost', 'myuser', 'mypassword', 'mydatabase');

	// mysqli->connect_errno will return zero if successful
	if ($mysqli->connect_errno) {
		echo '<p>There was an error connecting to the database!</p>';
		if ($mysqliDebug) {
			// mysqli->connect_error returns the latest error message,
			// hopefully clarifying the problem
			// NOTE: supported as of PHP 5.2.9
			echo $mysqli->connect_error;
		}

		// since there is no database connection your queries will fail,
		// quit processing
		die();
	}

	// create a query that should return a single record
	// the backticks around the table and column names are optional
	// they are required only when a name matches a reserved word (e.g. `date`)
	$query = 'SELECT * FROM `mytable` ORDER BY `mycolumn` LIMIT 1';

	// execute the query
	// passing the connection link as a parameter is optional
	// but useful if you are accessing more than one database
	// query returns FALSE on error, and a result object or TRUE on success
	// the result (object or TRUE) depends on the query you execute
	$result = $mysqli->query($query);
	if (!$result and $mysqliDebug) {
		// the query failed and debugging is enabled
		echo "<p>There was an error in query: $query</p>";
		echo $mysqli->error;
	}
	if ($result) {
		// the query was successful
		// get the result (if any)
		// fetch_object returns NULL if there is no record
		if ($recordObj = $result->fetch_object()) {
			// we have a record so now we can use it
			// the columns are properties of the object
			echo $recordObj->mycolumn;
		}
		else {
			echo '<p>No record found.</p>';
		}

		// when you are done with the result, free it
		$result->close();
	}

	// now get multiple records
	$query = 'SELECT * FROM `mytable` ORDER BY `mycolumn` LIMIT 10';

	$result = $mysqli->query($query);
	if (!$result and $mysqliDebug) {
		echo "<p>There was an error in query: $query</p>";
		echo $mysqli->error;
	}
	if ($result) {
		// perhaps you want to check if there are any rows available
		if ($result->num_rows == 0) {
			echo '<p>No records found.</p>';
		}
		else {
			while ($recordObj = $result->fetch_object()) {
				echo $recordObj->mycolumn;
			}
		}
		$result->close();
	}

	// close the database connection (preferred)
	// closing is optional, but not closing the link
	// will keep the connection lingering until it times out
	$mysqli->close();
?>