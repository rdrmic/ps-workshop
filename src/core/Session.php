<?php

    require_once("./src/core/Request.php");
    require_once("./src/core/user/UserController.php");
    
    // TODO load all necessary files here?
    require_once("./src/core/Utils.php");

    class Session {
        private static $instance;
        
        private $request;
        private $user;
        
        private function __construct() {
            ini_set('error_reporting', E_ALL);
            ini_set('log_errors', 1);
            ini_set('display_errors', Config::getInstance()->getDisplayErrors());
            
            session_start();
            
            $this->request = Request::getInstance();
            $this->user = array_key_exists('user', $_SESSION) ? $_SESSION['user'] : null;
        }
        
        public static function getInstance() {
            if (self::$instance == null) {
                self::$instance = new Session();
            }
            return self::$instance;
        }
        
        
        public function setUser($user_data) {
            $_SESSION['user'] = $user_data;
            $this->user = $_SESSION['user'];
            
            //echo "---> _COOKIE user_code:" . $user_data['generated_code'] . " <br />";
            $cookie_set = setcookie('user_code', $this->user['generated_code']);         // TODO expiration - [mk]time() + (60 * 1)   // (sec * x)
            if (!$cookie_set) {
                // FIXME error handling
                echo
                    '<p style="color:yellow;background-color:red;padding:7px;font-family:courier;font-weight:bold;">' .
                        'ERROR:<br />' .
                        'Unable to set cookie!' .
                    '</p><br />';
                die();
            }
        }
        
        public function getUser() {
            return $this->user;
        }
        
        private function loginUser() {
            $login_data = $this->request->getPostParams();
            $user_controller = new UserController($login_data);
            $response = $user_controller->login();
            return $response;
        }
        
        /*private function authenticateUser() {
            $user_controller = new UserController();
            $user_ok = $user_controller->authenticate($this->user);         // TODO check for expiration
            return $user_ok;
        }*/
        
        public function handleRequest() {
            $response = null;
            if ($this->user == null) {
                $response = $this->loginUser();
            } else {
                /*$user_ok = $this->authenticateUser();
                if (!$user_ok) {
                    $response = $this->loginUser();
                } else {
                    $response = $this->dispatchRequest();
                }*/
                require_once("./src/core/RequestDispatcher.php");
                $request_dispatcher = new RequestDispatcher($this->request);
                $response = $request_dispatcher->dispatch();
            }
            return $response;
        }
        
        public function redirectRequest($url, $statusCode = 303) {
            header('Location: ' . $url, true, $statusCode);
            exit();
        }
        
        
        /*public function destroy() {
            $_SESSION = [];
            session_destroy();
        }*/
        
    }

?>
