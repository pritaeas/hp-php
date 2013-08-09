<html>
<body>
<?php
// To be able to use this example, you have to import the following MySQL dump into your database:
// sql/tagging-related-articles.sql
// http://www.daniweb.com/web-development/php/threads/387961/php-code-to-show-related-posts-on-a-blog

try {
    $pdo = new PDO('mysql:dbname=test;host=localhost', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $exception) {
    die($exception->getMessage());
}

$id = isset($_GET['id']) ? $_GET['id'] : 0;
if ($id > 0)
{
    echo '<p><a href="?">Back to article list</a></p>';

    // Get single article.
    try
    {
        $query = 'SELECT a.*, GROUP_CONCAT(t.name SEPARATOR \',\') AS tags
                  FROM articles a
                  LEFT JOIN articles_tags at ON a.id = at.article_id
                  LEFT JOIN tags t ON at.tag_id = t.id
                  WHERE a.id = :id
                  GROUP BY a.id';

        $pdoStatement = $pdo->prepare($query);
        $pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $pdoStatement->execute();

        if ($recordObj = $pdoStatement->fetchObject())
        {
            echo "<h1>{$recordObj->title}</h1>";
            echo "<p><strong>Tags:</strong> {$recordObj->tags}</p>";

            // Get top 5 related articles.
            $tags = "'" . implode("','", explode(',', $recordObj->tags)) . "'";
            $query = "SELECT a.id As id, a.title AS title, COUNT(*) AS relevance
                      FROM articles_tags, tags t, articles a
                      WHERE article_id <> :id
                      AND tag_id = t.id
                      AND article_id = a.id
                      AND t.name IN ({$tags})
                      GROUP BY article_id
                      ORDER BY relevance DESC, a.date_modified DESC LIMIT 5";

            $pdoStatement2 = $pdo->prepare($query);
            $pdoStatement2->bindValue(':id', $id, PDO::PARAM_INT);
            $pdoStatement2->execute();

            echo '<p><strong>Related:</strong></p>';
            echo '<ul>';
            while ($recordObj2 = $pdoStatement2->fetchObject())
            {
                echo "<li><a href=\"?id={$recordObj2->id}</a>\">{$recordObj2->title}</a> ({$recordObj2->relevance})</li>";
            }
            echo '</ul>';

            $pdoStatement2->closeCursor();
            unset($pdoStatement2);
        }
        else
        {
            echo '<p>No such article.</p>';
        }

        $pdoStatement->closeCursor();
    }
    catch (PDOException $exception)
    {
        die($exception->getMessage());
    }
}
else
{
    echo '<h1>Article list</h1>';
    try
    {
        $pdoStatement = $pdo->prepare('SELECT * FROM `articles` ORDER BY `date_created` DESC');
        $pdoStatement->execute();

        if ($pdoStatement->rowCount() == 0)
        {
            echo '<p>No articles found.</p>';
        }
        else
        {
            echo '<ul>';
            while ($recordObj = $pdoStatement->fetchObject())
            {
                echo "<li><a href=\"?id={$recordObj->id}\">{$recordObj->title}</a></li>";
            }
            echo '</ul>';
        }

        $pdoStatement->closeCursor();
    }
    catch (PDOException $exception)
    {
        die($exception->getMessage());
    }
}

unset($pdoStatement);
unset($pdo);
?>
</body>
</html>