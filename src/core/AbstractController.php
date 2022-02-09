<?php

    require_once('DB.php');

    abstract class AbstractController {
        protected $db;
        
        protected $view;
        protected $model;

        public function __construct() {
            $this->db = DB::getInstance();
        }
        
        public function loadModule() {
            $data = [
                'rows'                  => $this->model->findItems(),
                'storage_money_sum'     => $this->model->sumStorageMoney()
            ];
            $content = $this->view->listView($data);
            return $this->view->renderPage($content);
        }
        
        public function increaseQuantity($id_or_params) {
            // GET: return page for getting amount for increasing item's quantity
            if (!is_array($id_or_params)) {
                $max_possible_entry = $this->model->calculateMaxPossibleEntry($id_or_params);
                
                $data = [
                    'id'                    => $id_or_params,
                    'max_possible_entry'    => $max_possible_entry,
                    'quantity_increase'     => null
                ];
                $content = $this->view->increaseQuantityView($data);
                return $this->view->renderPage($content);
            }

            // POST: increase item's quantity
            $params = $id_or_params['POST_params'];
            $id = $params->getInt('id');
            $max_possible_entry = $this->model->calculateMaxPossibleEntry($id);
            $quantity_increase = $params->getInt('quantity_increase');
            if ($quantity_increase > $max_possible_entry) {
                $data = [
                    'id'                    => $id,
                    'max_possible_entry'    => $max_possible_entry,
                    'quantity_increase'     => $quantity_increase
                ];
                $content = $this->view->increaseQuantityView($data);
                return $this->view->renderPage($content);
            }
            
            $this->_increaseQuantity($params);
        }
        
        public function deleteItem($id) {
            // TODO complete usages list ?
            //$usages = $this->model->findItemUsages(static::MODULE_NAME, $id);
            //if (!empty($usages)) { ...
            
            $has_usages = $this->model->findItemUsages(static::MODULE_NAME, $id);
            if ($has_usages) {
                $content = $this->view->showUsagesView();
                return $this->view->renderPage($content);
            }
            $this->_deleteItem(static::MODULE_NAME, $id);
        }
        

        protected function redirectToList() {
            $urlBase = Config::getInstance()->getUrlBase();
            Session::redirectRequest("$urlBase?" . static::MODULE_NAME);
        }
        
        
        protected function _newDefinition($params) {
            $this->model->newDefinition($params);
            $this->redirectToList();
        }
        
        protected function _increaseQuantity($params) {
            $this->model->increaseQuantity($params);
            $this->redirectToList();
        }
        
        protected function _updateDefinition($params) {
            $this->model->updateDefinition($params);
            $this->redirectToList();
        }
        
        private function _deleteItem($item_type, $id) {
            $this->model->deleteItem($id, $item_type);
            $this->redirectToList();
        }

    }

?>
