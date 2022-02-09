<?php

    require_once('./src/domain/models/queries.php');
    // TODO includes
    require_once('./src/domain/controllers/MaterialsController.php');
    require_once('./src/domain/controllers/BiscuitsController.php');
    require_once('./src/domain/controllers/IncompletesController.php');

    /*global $QUERY_insert_item_deleted;
    $QUERY_insert_item_deleted                      = $insert_item_deleted;*/
    
    global $QUERY_insert_item_copy_deleted;
    $QUERY_insert_item_copy_deleted                 = $insert_item_copy_deleted;
    
    global $QUERY_delete_item;
    $QUERY_delete_item                              = $delete_item;

    abstract class AbstractModel {
        protected $db;
        protected $userId;

        public function __construct($db) {
            $this->db = $db;
            $this->userId = Session::getInstance()->getUser()['id'];
        }
        
        
        public function findCodesInUse($type) {
            //__show("type: $type");
            $type = rtrim($type, 's');
            
            $query = "SELECT item.code FROM $type item";
            //__show("query: $query");
            $statement = $this->db->query($query);
            $rows = $statement->fetchAll();
            //__show($rows);
            return $rows;
        }
        
        public function findItemUsages($type, $id) {
            //__show("### DELETE $type $id");
            
            /*
                materials
                    biscuits
                    incompletes
                    products
                biscuits
                    incompletes
                incompletes
                    products
            */
            //$usages = [];       // TODO complete usages list ?
            if ($type == MaterialsController::MODULE_NAME) {
                $fetchedRows = $this->findItemConsumers('biscuit', 'material', $id);
                if (!empty($fetchedRows)) {
                    //$usages[] = 'biscuit';
                    return true;
                }
                $fetchedRows = $this->findItemConsumers('incomplete', 'material', $id);
                if (!empty($fetchedRows)) {
                    //$usages[] = 'incomplete';
                    return true;
                }
                $fetchedRows = $this->findItemConsumers('product', 'material', $id);
                if (!empty($fetchedRows)) {
                    //$usages[] = 'product';
                    return true;
                }
            } else if ($type == BiscuitsController::MODULE_NAME) {
                $fetchedRows = $this->findItemConsumers('incomplete', 'biscuit', $id);
                if (!empty($fetchedRows)) {
                    //$usages[] = 'incomplete';
                    return true;
                }
            } else if ($type == IncompletesController::MODULE_NAME) {
                $fetchedRows = $this->findItemConsumers('product', 'incomplete', $id);
                if (!empty($fetchedRows)) {
                    //$usages[] = 'product';
                    return true;
                }
            }
            //__show($usages);
            //return $usages;
            return false;
        }
        
        private function findItemConsumers($consumer, $item, $item_id) {
            /*
            SELECT b.code, b.name
            FROM biscuit_materials bm
            JOIN biscuit b
                ON b.id = bm.biscuit_id
            WHERE 1
                AND bm.material_id = :material_id
            */
            
            $usage_table = $consumer . '_' . $item . 's';
            $query = "SELECT c.code, c.name FROM $usage_table u JOIN $consumer c ON c.id = u.$consumer" . "_id WHERE u.$item" . "_id = $item_id";
            //__show($query);

            $statement = $this->db->query($query);
            $rows = $statement->fetchAll();
            //__show($rows);
            
            return $rows;
        }
        
        public function deleteItem($id, $type = null) {
            $type = rtrim($type, 's');
            
            /*global $QUERY_insert_item_deleted;
            $query = str_replace('{item_type}', $type, $QUERY_insert_item_deleted);
            $statement = $this->db->prepare($query);
            $item = [
                'id'                    => $id,
                'user_id'               => $this->userId
            ];
            __show($statement);
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }*/
            
            global $QUERY_insert_item_copy_deleted;
            $query = str_replace('{item_type}', $type, $QUERY_insert_item_copy_deleted);
            $statement = $this->db->prepare($query);
            $item = [
                'id'                    => $id,
                'user_id'               => $this->userId
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
            
            global $QUERY_delete_item;
            $query = str_replace('{item_type}', $type, $QUERY_delete_item);
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id);
            if (!$statement->execute()) {
                throw new Exception($statement->errorInfo()[2]);
            }
        }
    }

?>
