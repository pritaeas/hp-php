<?php
// this example is an addition to using-pdo.php
// the table structure used is in sql/using-pdo-binding.sql

// the obvious lines will not be commented, as they are
// already explained in the other code snippet.

try {
    $pdo = new PDO('mysql:dbname=mydatabase;host=localhost', 'myuser', 'mypassword');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // these are the variables we are going to insert
    $name = 'pritaeas';
    $email = 'pritaeas@example.com';
    $dob = '2013-08-30';
    $level = 0;

    // insert a record
    // to use binding, use a colon followed by an identifier where you would normally insert a value
    $query = 'INSERT INTO `mytable` (`name`, `email`, `dob`, `level`) VALUES (:name, :email, :dob, :level)';

    // prepare the query for binding
    $statement = $pdo->prepare($query);

    // now bind the actual values to variables (see it as replacing the identifiers)
    $statement->bindValue('name', $name, PDO::PARAM_STR);
    $statement->bindValue('email', $email, PDO::PARAM_STR);
    $statement->bindValue('dob', $dob, PDO::PARAM_STR);
    $statement->bindValue('level', $level, PDO::PARAM_INT);

    // execute the query
    $result = $statement->execute();
    if ($result)
    {
        $lastId = $pdo->lastInsertId();
        echo '<p>Rows inserted: ' . $statement->rowCount() . '</p>';
        echo '<p>Last ID: ' . $lastId . '</p>';
    }
    else
    {
        echo '<p>Insert failed</p>';
    }

    // update the last record.
    // the same method works for update queries too.

    $level++;

    $query = 'UPDATE `mytable` SET `level` = :level WHERE `id` = :id';

    $statement = $pdo->prepare($query);

    // binding two integers now,
    $statement->bindValue('level', $level, PDO::PARAM_INT);
    $statement->bindValue('id', $lastId, PDO::PARAM_INT);

    $result = $statement->execute();
    if ($result)
    {
        echo '<p>Rows updated: ' . $statement->rowCount() . '</p>';
    }
    else
    {
        echo '<p>Update failed</p>';
    }

    unset($pdo);
}
catch (PDOException $exception) {
    echo '<p>There was an error connecting to the database!</p>';
    echo $exception->getMessage();
}
?>