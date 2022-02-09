<?php

    require_once('./src/domain/views/FacturedsView.php');
    require_once('./src/domain/models/FacturedsModel.php');

    class FacturedsController extends AbstractController {
        const MODULE_NAME = 'fact';

        public function __construct() {
            parent::__construct();

            $this->view = new FacturedsView(self::MODULE_NAME);
            $this->model = new FacturedsModel($this->db);
        }
        
        
        public function increaseQuantity($id_or_params) {
            // GET: return page for getting amount for increasing item's quantity
            if (!is_array($id_or_params)) {
                $product_id = $this->model->getProductId($id_or_params);
                $max_possible_entry = $this->model->calculateMaxPossibleEntry($id_or_params);   // FIXME if ($max_possible_entry == null) {$max_possible_entry = 0;}
                
                $data = [
                    'id'                    => $id_or_params,
                    'product_id'            => $product_id,
                    'max_possible_entry'    => $max_possible_entry,
                    'quantity_increase'     => null
                ];
                $content = $this->view->increaseQuantityView($data);
                return $this->view->renderPage($content);
            }

            // POST: increase item's quantity
            $params = $id_or_params['POST_params'];
            $id = $params->getInt('id');
            $max_possible_entry = $this->model->calculateMaxPossibleEntry($id);     // FIXME what here ?
            $quantity_increase = $params->getInt('quantity_increase');
            if ($quantity_increase > $max_possible_entry) {
                $product_id = $this->model->getProductId($id);

                $data = [
                    'id'                    => $id,
                    'product_id'            => $product_id,
                    'max_possible_entry'    => $max_possible_entry,
                    'quantity_increase'     => $quantity_increase
                ];
                $content = $this->view->increaseQuantityView($data);
                return $this->view->renderPage($content);
            }
            
            $this->_increaseQuantity($params);
        }

    }

?>
