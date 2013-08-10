<?php
define ('DSN_REGEX', '/^(?P<user>\w+)(:(?P<password>\w+))?@(?P<host>[.\w]+)(:(?P<port>\d+))?\\\\(?P<database>\w+)$/im');

/**
 * Parse a DSN-string, user:password@host:port\database, and break it into it's components.
 * Password is optional.
 *
 * Many thanks to Vision.
 *
 * @param string $dsn DSN string to parse.
 * @return array|bool Array on success, false on error.
 */
function ParseDsn($dsn)
{
    $result = array
    (
        'user' => '',
        'password' => '',
        'host' => 'localhost',
        'port' => 3306,
        'database' => ''
    );

    if (strlen($dsn) == 0)
    {
        return false;
    }

    if (!preg_match(DSN_REGEX, $dsn, $matches))
    {
        return false;
    }

    if (count($matches) == 0)
    {
        return false;
    }

    foreach ($result as $key => $value)
    {
        if (array_key_exists($key, $matches) and !empty($matches[$key]))
        {
            $result[$key] = $matches[$key];
        }
    }

    return $result;
}

// Test.
print_r(ParseDsn('my_user:my_pass@example.com:3307\my_data'));
print_r(ParseDsn('user@example.com\database'));
?>