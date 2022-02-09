<?php
    require_once('./src/domain/views/MaterialsView.php');
    require_once('./src/domain/models/MaterialsModel.php');

    class MaterialsController extends AbstractController {
        const MODULE_NAME = 'materials';

        public function __construct() {
            parent::__construct();

            $this->view = new MaterialsView(self::MODULE_NAME);
            $this->model = new MaterialsModel($this->db);
        }
        
        
        public function newDefinition($params = null) {
            // GET: page for definition of new item
            if ($params == null) {
                $codesInUse = $this->model->findCodesInUse(self::MODULE_NAME);
                
                $content = $this->view->newDefinitionView($codesInUse);
                return $this->view->renderPage($content);
            }

            // POST: create definition of new item
            $params = $params['POST_params'];
            $this->_newDefinition($params);
        }
        
        public function updateDefinition($id_or_params) {
            // GET: get id of item's definition and return form filled with definition's attributes
            if (!is_array($id_or_params)) {
                $item = $this->model->fetch($id_or_params);
                
                $content = $this->view->detailView($item);
                return $this->view->renderPage($content);
            }

            // POST: update item's definition
            $params = $id_or_params['POST_params'];
            $this->_updateDefinition($params);
        }
        
        public function increaseQuantity($id_or_params) {
            // GET: return page for getting amount for increasing item's quantity   // TODO renaming
            if (!is_array($id_or_params)) {
                $data = ['id' => $id_or_params];
                $content = $this->view->increaseQuantityView($data);
                return $this->view->renderPage($content);
            }

            // POST: increase item's quantity
            $params = $id_or_params['POST_params'];
            $this->_increaseQuantity($params);
        }

    }

?>
