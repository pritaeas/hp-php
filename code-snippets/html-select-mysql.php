<?php
    $dbLink = mysql_connect('localhost', 'myuser', 'mypassword');
    if (!$dbLink)
    {
        echo '<p>There was an error connecting to the database!</p>';
        die();
    }

    if (!mysql_select_db('mydatabase'))
    {
        echo '<p>There was an error selecting the database!</p>';
        mysql_close($dbLink);
        die();
    }

    $query = 'SELECT `mycolumn` FROM `mytable` ORDER BY `mycolumn`';
    $result = mysql_query($query, $dbLink);
    if (!$result)
    {
        echo '<p>There was an error in your query!</p>';
    }
    else
    {
        $select = '<select>';
        while ($record = mysql_fetch_array($result)) {
            $select .= "<option value=\"{$record['mycolumn']}\">{$record['mycolumn']}</option>";
        }
        $select .= '</select>';
        mysql_free_result($result);
    }

    mysql_close($dbLink);
?>
