## CONNECTION FORM IN PHP

### ADJIBADE Ahmed - Université Paris 8

To test this login form you can proceed in three steps.

Assuming that you already have PhpMyAdmin and a local server system (MAMP, XAMP...)

- Clone this repository on your server. If you are using MAMP on MacOS for exemple, you can clone it in the `/Applications/MAMP/htdocs/` folder.

- Import the `customers.sql` file in your database manager via PhpMyAdmin interface.

- Change the connection credentials in the `login.php` file to suit your localhost informations.

```php
    // connection to the database
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'customers';
```

You can now access to the php file via your server to use the form.

For exemple : `https://localhost/secu/login.php`

Enjoy 🍀
