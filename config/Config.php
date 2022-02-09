<?php

    class Config {
        private static $instance;
        
        const ENV_DEV = 'dev';
        const ENV_PROD = 'prod';
        
        private $data;
        
        private $displayErrors;
        private $db;
        private $urlBase;

        private function __construct() {
            $app_config = file_get_contents(__DIR__ . '/app_config.json');
            $this->data = json_decode($app_config, true);
            
            $env = $this->data['environment'];
            
            $this->displayErrors = $this->get("display_errors-$env");
            $this->db = $this->get("db-$env");
            $this->urlBase = $this->get("urlbase-$env");
        }

        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new Config();
            }
            return self::$instance;
        }
        
        
        public function getDisplayErrors() {
            return $this->displayErrors;
        }
        
        public function getDb() {
            return $this->db;
        }
        
        public function getUrlBase() {
            return $this->urlBase;
        }
        
        
        public function get($key) {
            if (!isset($this->data[$key])) {
                throw new Exception("Key '$key' not in config.");     // TODO error handling
            }
            return $this->data[$key];
        }

    }

?>
