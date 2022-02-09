<?php

    require_once('./src/core/AbstractModel.php');

    class UserModel extends AbstractModel {
        
        public function login($login_data) {
            $query = 'SELECT u.id, u.username, u.generated_code FROM user u WHERE u.username = :username AND u.password = :password AND u.active = 1';
            
            $statement = $this->db->prepare($query);
            $statement->bindValue('username', $login_data['username']);
            $statement->bindValue('password', $login_data['password']);
            $statement->execute();
            $user_data = $statement->fetch();
            return !$user_data ? null : $user_data;
        }
        
        /*public function authenticate($user_data) {
            // ...
            return true;
        }*/

    }

?>
