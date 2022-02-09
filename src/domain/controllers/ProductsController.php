<?php

    require_once('./src/domain/views/ProductsView.php');
    require_once('./src/domain/models/ProductsModel.php');
    require_once('./src/domain/models/IncompletesModel.php');
    require_once('./src/domain/models/MaterialsModel.php');

    class ProductsController extends AbstractController {
        const MODULE_NAME = 'products';

        public function __construct() {
            parent::__construct();

            $this->view = new ProductsView(self::MODULE_NAME);
            $this->model = new ProductsModel($this->db);
        }
        
        
        public function newDefinition($params = null) {
            // GET: page for definition of new item
            if ($params == null) {
                $codesInUse = $this->model->findCodesInUse(self::MODULE_NAME);
                $incompletes = (new IncompletesModel($this->db))->findItems();
                $materials = (new MaterialsModel($this->db))->findItems();
                
                $content = $this->view->newDefinitionView($codesInUse, $incompletes, $materials);
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
                $incompletes = $this->model->fetchIncompletes($id_or_params);
                $materials = $this->model->fetchMaterials($id_or_params);
                
                $content = $this->view->detailView($item, $incompletes, $materials);
                return $this->view->renderPage($content);
            }

            // POST: update item's definition
            $params = $id_or_params['POST_params'];
            $this->_updateDefinition($params);
        }

    }

?>
