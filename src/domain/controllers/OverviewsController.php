<?php

    require_once('./src/domain/views/OverviewsView.php');
    require_once('./src/domain/models/OverviewsModel.php');
    require_once('./src/domain/controllers/MaterialsController.php');
    require_once('./src/domain/controllers/BiscuitsController.php');
    require_once('./src/domain/controllers/IncompletesController.php');
    require_once('./src/domain/controllers/ProductsController.php');
    require_once('./src/domain/controllers/FacturedsController.php');

    class OverviewsController extends AbstractController {
        const MODULE_NAME = 'overviews';
        
        private static $modules = null;
        
        public static function getModules() {
            if (self::$modules == null) {
                self::$modules = [
                    'storages' => [
                        MaterialsController::MODULE_NAME            => 'MATERIJALI',
                        BiscuitsController::MODULE_NAME             => 'BISKVITI',
                        IncompletesController::MODULE_NAME          => 'POLUPROIZVODI',
                        ProductsController::MODULE_NAME             => 'GOTOVI PROIZVODI'
                    ],
                    'factureds' => [
                        FacturedsController::MODULE_NAME            => 'FAKTURIRANO'
                    ]
                ];
            }
        
            return self::$modules;
        }
        

        public function __construct() {
            parent::__construct();

            $this->view = new OverviewsView(self::MODULE_NAME);
            $this->model = new OverviewsModel($this->db);
        }
        

        public function loadModule($params = null) {
            if ($params == null) {
                $date_range = [
                    date('Y-m-01'),
                    date('Y-m-d')
                ];
                return $this->_loadModule($date_range);
            }
            
            $date_range = [
                $params['POST_params']->getString('start_date'),
                $params['POST_params']->getString('finish_date')
            ];
            return $this->_loadModule($date_range);
        }
        
        private function _loadModule($date_range) {
            $data = [
                'start_date'            => $date_range[0],
                'finish_date'           => $date_range[1],
                'all_modules_data'      => $this->model->findAddedItemsForAllModules($date_range)
            ];
            
            $content = $this->view->listView($data);
            return $this->view->renderPage($content);
        }

    }

?>
