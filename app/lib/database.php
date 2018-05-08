<?php
namespace App\Lib;

use PDO;

class Database
{
    public static function StartUp()
    {
        $dsn = 'mysql:host=localhost;dbname=tetrisdb;charset=utf8';
        $user = 'root';
        $password = '';

        $pdo = new PDO($dsn, $user, $password);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        
        return $pdo;
    }

    public static $FETCH_COLUMN = PDO::FETCH_COLUMN;

}
