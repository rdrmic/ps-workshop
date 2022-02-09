<?php

    require_once('./config/Config.php');

    class DB {
        private static $instance;

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = self::connect();
            }
            return self::$instance;
        }

        private static function connect() {
            $db_config = Config::getInstance()->getDb();

            $pdo = new PDO(
                $db_config['dsn'],
                $db_config['user'],
                $db_config['pass']
            );
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // PDO::ERRMODE_SILENT  PDO::ERRMODE_WARNING
            $pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);     // TODO doesn't work?
            return $pdo;
        }

    }

?>
