<?php

    require_once('ParamsFilteringMap.php');

    class Request {
        private static $instance;
        
        const GET = 'GET';
        const POST = 'POST';

        //private $domain;
        private $path;
        private $method;
        private $params;
        private $postParams;
        //private $cookies;

        private function __construct() {
            //$this->domain = $_SERVER['HTTP_HOST'];
            
            //$this->path = $_SERVER['REQUEST_URI'];
            $this->path = '';
            if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
                $this->path = explode('?', $_SERVER['REQUEST_URI'])[1];
            }
            
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->params = new ParamsFilteringMap(array_merge($_GET, $_POST));
            $this->postParams = empty($_POST) ? null : new ParamsFilteringMap($_POST);
            //$this->cookies = new ParamsFilteringMap($_COOKIE);
        }
    
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new Request();
            }
            return self::$instance;
        }

        /*public function getUrl() {
            return $this->domain . $this->path;
        }*/

        /*public function getDomain() {
            return $this->domain;
        }*/

        public function getPath() {
            return $this->path;
        }

        public function getMethod() {
            return $this->method;
        }

        public function isGet() {
            return $this->method === self::GET;
        }

        public function isPost() {
            return $this->method === self::POST;
        }

        public function getParams() {
            return $this->params;
        }
        
        public function getPostParams() {
            return $this->postParams;
        }

        /*public function getCookies() {        // TODO
            return $this->cookies;
        }*/

    }

?>
