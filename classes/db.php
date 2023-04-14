<?php

    abstract class Db {

        private static $conn;

        private static function getConfig(){
            //vraag config file
            return parse_ini_file("config/config.ini");
        }
        

        public static function getInstance() {
            if(self::$conn != null) {
                // hergebruik connectie
                return self::$conn;
            }
            else {
                // nieuwe connectie creeëren en config data uit config file halen
                $config = self::getConfig();
                $database = $config['database'];
                $user = $config['user'];
                $password = $config['password'];
                $host = $config['host'];
                
                self::$conn = new PDO("mysql:host=$host;dbname=".$database, $user, $password);
                return self::$conn;
            }
        }
    }