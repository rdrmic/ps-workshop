<?php

    require_once('./src/core/AbstractController.php');
    require_once('./src/core/user/UserView.php');
    require_once('./src/core/user/UserModel.php');

    class UserController extends AbstractController {
        const MODULE_NAME = 'user';
        
        private $loginData;
        
        public function __construct($login_data = null) {
            parent::__construct();
            
            if ($login_data != null) {
                $this->loginData = [
                    'username'  => $login_data->getString('username'),
                    'password'  => $login_data->getString('password')
                ];
            } else {
                $this->loginData = null;
            }

            $this->view = new UserView();
            $this->model = new UserModel($this->db);
        }
        
        public function login() {
            // GET: show log-in page
            if ($this->loginData == null) {
                $content = $this->view->loginView();
                return $this->view->renderPage($content);
            }

            // POST: log-in the user and go to starting page
            $session = Session::getInstance();
            $user_data = $this->model->login($this->loginData);
            if ($user_data == null) {
                // TODO unsuccessful login - msg to user?
                $urlBase = Config::getInstance()->getUrlBase();
                //echo "session->redirectRequest -> //<br /><br />";
                $session->redirectRequest($urlBase);
            } else {
                $session->setUser($user_data);
                
                $urlBase = Config::getInstance()->getUrlBase();
                $start_route = Config::getInstance()->get('start-route');
                //echo "session->redirectRequest -> $start_route<br /><br />";
                $session->redirectRequest($urlBase . '?' . $start_route);
            }

        }
        
        /*public function authenticate($user_data) {
            echo "_SESSION user[generated_code]: " . $user_data['generated_code'] . "<br /><br />";
            
            if (isset($_COOKIE['user_code'])) {
                echo "_COOKIE - user_code: " . $_COOKIE['user_code'] . "<br /><br />";
            } else {
                echo "_COOKIE - NOT SET<br /><br />";
            }
            
            //$is_authenticated = $this->model->authenticate($user_data);
            $is_authenticated = true;
            return $is_authenticated;
        }*/

    }

?>
