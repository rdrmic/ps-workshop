<?php

    require_once('./src/core/AbstractView.php');

    class UserView extends AbstractView {
        const TEMPLATE_LOGIN = 'login.tpl';
        
        public function loginView() {
            return $this->loadTemplate(self::TEMPLATE_LOGIN)->render();
        }
        
        public function renderPage($content) {
            $params = [
                'app_version' => AbstractView::assembleAppVersionLabel(),
                'content' => $content
            ];
            return $this->loadTemplate("layout.tpl")->render($params);
        }

    }

?>
