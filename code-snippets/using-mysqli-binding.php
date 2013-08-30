<?php
// this example is an addition to using-mysqli.php
// the table structure used is in sql/using-mysqli-binding.sql

// the obvious lines will not be commented, as they are
// already explained in the other code snippet.

$mysqliDebug = true;
$mysqli = new mysqli('localhost', 'myuser', 'mypassword', 'mydatabase');

if ($mysqli->connect_errno) {
    echo '<p>There was an error connecting to the database!</p>';
    if ($mysqliDebug) {
        echo $mysqli->connect_error;
    }
    die();
}

// insert a record
// to use binding, use a question mark where you would normally insert a value
$query = 'INSERT INTO `mytable` (`name`, `email`, `dob`, `level`) VALUES (?, ?, ?, ?)';

// prepare the query for binding
$statement = $mysqli->prepare($query);

// now bind the actual values to variables (see it as replacing the question marks)
// 'sssi' defines the type for each of the question marks in order
// options are s(string) i(integer) d(double) b(blob)
$statement->bind_param('sssi', $name, $email, $dob, $level);

// you can now give values to your variables
// whether this happens now, or before the query doesn't matter,
// as long as it happens before execute.
$name = 'pritaeas';
$email = 'pritaeas@example.com';
$dob = '2013-08-30';
$level = 0;

// execute the query
$result = $statement->execute();
if ($result)
{
    $lastId = $mysqli->insert_id;
    echo '<p>Rows inserted: ' . $mysqli->affected_rows . '</p>';
    echo '<p>Last ID: ' . $lastId . '</p>';
}
else
{
    echo '<p>Insert failed</p>';
    die($mysqli->error);
}

// update the last record.
// the same method works for update queries too.

$level++;

$query = 'UPDATE `mytable` SET `level` = ? WHERE `id` = ?';

$statement = $mysqli->prepare($query);

// binding two integers now,
// both already have a value
$statement->bind_param('ii', $level, $lastId);

$result = $statement->execute();
if ($result)
{
    $lastId = $mysqli->insert_id;
    echo '<p>Rows updated: ' . $mysqli->affected_rows . '</p>';
}
else
{
    echo '<p>Update failed</p>';
    die($mysqli->error);
}

$mysqli->close();
?>