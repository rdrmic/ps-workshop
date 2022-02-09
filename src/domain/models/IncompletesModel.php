<?php

    require_once('./src/core/AbstractModel.php');
    require('./src/domain/models/queries.php');
    
    global $QUERY_insert_incomplete_item;
    $QUERY_insert_incomplete_item                           = $insert_incomplete_item;
    
    global $QUERY_insert_incomplete_biscuits;
    $QUERY_insert_incomplete_biscuits                       = $insert_incomplete_biscuits;
    
    global $QUERY_insert_incomplete_materials;
    $QUERY_insert_incomplete_materials                      = $insert_incomplete_materials;
    
    global $QUERY_calculate_incomplete_max_possible_entry;
    $QUERY_calculate_incomplete_max_possible_entry          = $calculate_incomplete_max_possible_entry;
    
    global $QUERY_increase_incomplete_quantity;
    $QUERY_increase_incomplete_quantity                     = $increase_incomplete_quantity;
    
    global $QUERY_insert_incompletes;
    $QUERY_insert_incompletes                               = $insert_incompletes;
    
    global $QUERY_select_incomplete_biscuits;
    $QUERY_select_incomplete_biscuits                       = $select_incomplete_biscuits;
    
    global $QUERY_select_incomplete_materials;
    $QUERY_select_incomplete_materials                      = $select_incomplete_materials;
    
    global $QUERY_update_incomplete_item;
    $QUERY_update_incomplete_item                           = $update_incomplete_item;
    
    class IncompletesModel extends AbstractModel {

        public function findItems() {
            $query = 'SELECT i.* FROM incomplete i ORDER BY i.code';
            $stmt = $this->db->query($query);
            $rows = $stmt->fetchAll();
            return $rows;
        }

        public function sumStorageMoney() {
            $query = 'SELECT sum(i.current_value) FROM incomplete i';
            $statement = $this->db->query($query);
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function newDefinition($item_params) {
            global $QUERY_insert_incomplete_item;
            $statement = $this->db->prepare($QUERY_insert_incomplete_item);
            $item = [
                'code'              => $item_params->getString('code'),
                'name'              => $item_params->getString('name'),
                'note'              => $item_params->getString('note'),
                'work_price'        => $item_params->getFloatFromCurrency('work_price'),
                'biscuits_price'    => $item_params->getFloatFromCurrency('biscuits_price'),
                'materials_price'   => $item_params->getFloatFromCurrency('materials_price'),
                'price'             => $item_params->getFloatFromCurrency('price'),
                'user_id'           => $this->userId
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
            
            $incomplete_id = $this->db->lastInsertId();
            
            global $QUERY_insert_incomplete_biscuits;
            $statement = $this->db->prepare($QUERY_insert_incomplete_biscuits);
            for ($i = 0; $item_params->has("bis-$i") && $item_params->getInt("bis-$i") != -1; $i++) {
                if (!$statement->execute([
                        $incomplete_id,
                        $item_params->getInt("bis-$i"),
                        $item_params->getFloat("bis-$i" . '_quantityUsed'),
                        $item_params->getFloatFromCurrency("bis-$i" . '_calculatedPrice')
                ])) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }

            global $QUERY_insert_incomplete_materials;            
            $statement = $this->db->prepare($QUERY_insert_incomplete_materials);
            for ($i = 0; $item_params->has("mat-$i") && $item_params->getInt("mat-$i") != -1; $i++) {
                if (!$statement->execute([
                        $incomplete_id,
                        $item_params->getInt("mat-$i"),
                        $item_params->getFloat("mat-$i" . '_quantityUsed'),
                        $item_params->getFloatFromCurrency("mat-$i" . '_calculatedPrice')
                ])) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
        }
        
        public function calculateMaxPossibleEntry($incomplete_id) {
            global $QUERY_calculate_incomplete_max_possible_entry;
            $statement = $this->db->prepare($QUERY_calculate_incomplete_max_possible_entry);
            $statement->execute([$incomplete_id, $incomplete_id]);
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function increaseQuantity($params) {
            $amount = $params->getInt('quantity_increase');
            if ($amount > 0) {
                $id = $params->getInt('id');
                
                global $QUERY_increase_incomplete_quantity;
                $statement = $this->db->prepare($QUERY_increase_incomplete_quantity);
                $data = [
                    'incomplete_id'     => $id,
                    'amount'            => $amount
                ];
                if (!$statement->execute($data)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
                
                global $QUERY_insert_incompletes;
                $statement = $this->db->prepare($QUERY_insert_incompletes);
                $item = [
                    'incomplete_id'         => $id,
                    'quantity'              => $amount,
                    'user_id'               => $_SESSION['user']['id']
                ];
                if (!$statement->execute($item)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
        }
        
        public function fetch($id) {
            $query = 'SELECT i.* FROM incomplete i WHERE i.id = :id';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id);
            $statement->execute();
            $row = $statement->fetch();
            return $row;
        }
        
        public function fetchBiscuits($incomplete_id) {
            global $QUERY_select_incomplete_biscuits;
            $statement = $this->db->prepare($QUERY_select_incomplete_biscuits);
            $statement->bindValue('incomplete_id', $incomplete_id);
            $statement->execute();
            $rows = $statement->fetchAll();
            return $rows;
        }
        
        public function fetchMaterials($incomplete_id) {
            global $QUERY_select_incomplete_materials;
            $statement = $this->db->prepare($QUERY_select_incomplete_materials);
            $statement->bindValue('incomplete_id', $incomplete_id);
            $statement->execute();
            $rows = $statement->fetchAll();
            return $rows;
        }
        
        public function updateDefinition($item_params) {        // TODO naming in all(?) models: '*_item' -> '*_definition'; $item -> $data
            global $QUERY_update_incomplete_item;
            $statement = $this->db->prepare($QUERY_update_incomplete_item);
            $item = [
                'id'                => $item_params->getInt('id'),
                'code'              => $item_params->getString('code'),
                'name'              => $item_params->getString('name'),
                'note'              => $item_params->getString('note')
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
        }

    }

?>
