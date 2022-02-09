<?php

    require_once('./src/domain/views/IncompletesView.php');
    require_once('./src/domain/models/IncompletesModel.php');
    require_once('./src/domain/models/BiscuitsModel.php');
    require_once('./src/domain/models/MaterialsModel.php');

    class IncompletesController extends AbstractController {
        const MODULE_NAME = 'incompletes';

        public function __construct() {
            parent::__construct();

            $this->view = new IncompletesView(self::MODULE_NAME);
            $this->model = new IncompletesModel($this->db);
        }
        
        
        public function newDefinition($params = null) {
            // GET: page for definition of new item
            if ($params == null) {
                $codesInUse = $this->model->findCodesInUse(self::MODULE_NAME);
                $biscuits = (new BiscuitsModel($this->db))->findItems();
                $materials = (new MaterialsModel($this->db))->findItems();
                
                $content = $this->view->newDefinitionView($codesInUse, $biscuits, $materials);
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
                $biscuits = $this->model->fetchBiscuits($id_or_params);
                $materials = $this->model->fetchMaterials($id_or_params);
                
                $content = $this->view->detailView($item, $biscuits, $materials);
                return $this->view->renderPage($content);
            }

            // POST: update item's definition
            $params = $id_or_params['POST_params'];
            $this->_updateDefinition($params);
        }

    }

?>
