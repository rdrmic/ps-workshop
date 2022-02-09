<?php

    require_once('./src/domain/views/SummaryView.php');
    require_once('./src/domain/models/SummaryModel.php');

    class SummaryController extends AbstractController {
        const MODULE_NAME = 'summary';

        public function __construct() {
            parent::__construct();

            $this->view = new SummaryView(self::MODULE_NAME);
            $this->model = new SummaryModel($this->db);
        }
        

        public function loadModule($params = null) {
            if ($params == null) {
                $data = $this->model->getSummaryData();
                $content = $this->view->listView($data);
                return $this->view->renderPage($content);
            }
            
            // TODO only for testing purposes - remove before production
            return $this->deleteAllData($params['POST_params']);
        }
        
        private function deleteAllData($params) {
            if ($params->has('delete_all_data')) {
                $this->model->deleteAllData();
            }
            
            return $this->loadModule();
        }

    }

?>
