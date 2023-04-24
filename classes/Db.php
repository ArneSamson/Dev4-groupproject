<?php

class Db {
    private static $conn;

    public static function getInstance() {
        if (self::$conn == null) {
            $dsn = 'mysql:host=ID378949_dev4LLA.db.webhosting.be;dbname=ID378949_dev4LLA';
            $username = "ID378949_dev4LLA";
            $password = "TCAw75m7T7y3N6b70n59";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            self::$conn = new PDO($dsn, $username, $password, $options);
        }
        return self::$conn;
    }
}