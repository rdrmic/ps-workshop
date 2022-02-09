<?php

    require_once('./src/core/AbstractModel.php');
    require('./src/domain/models/queries.php');
    
    global $QUERY_insert_biscuit_item;
    $QUERY_insert_biscuit_item                              = $insert_biscuit_item;
    
    global $QUERY_insert_biscuit_materials;
    $QUERY_insert_biscuit_materials                         = $insert_biscuit_materials;
    
    global $QUERY_calculate_biscuit_max_possible_entry;
    $QUERY_calculate_biscuit_max_possible_entry             = $calculate_biscuit_max_possible_entry;
    
    global $QUERY_increase_biscuit_quantity;
    $QUERY_increase_biscuit_quantity                        = $increase_biscuit_quantity;
    
    global $QUERY_insert_biscuits;
    $QUERY_insert_biscuits                                  = $insert_biscuits;
    
    global $QUERY_select_biscuit_materials;
    $QUERY_select_biscuit_materials                         = $select_biscuit_materials;    
    
    global $QUERY_update_biscuit_item;
    $QUERY_update_biscuit_item                              = $update_biscuit_item;

    class BiscuitsModel extends AbstractModel {

        public function findItems() {
            $query = 'SELECT b.* FROM biscuit b ORDER BY b.code';
            $statement = $this->db->query($query);
            $rows = $statement->fetchAll();
            return $rows;
        }

        public function sumStorageMoney() {
            $query = 'SELECT sum(b.current_value) FROM biscuit b';
            $statement = $this->db->query($query);
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function newDefinition($item_params) {
            global $QUERY_insert_biscuit_item;
            $statement = $this->db->prepare($QUERY_insert_biscuit_item);
            $item = [
                'code'              => $item_params->getString('code'),
                'name'              => $item_params->getString('name'),
                'note'              => $item_params->getString('note'),
                'work_price'        => $item_params->getFloatFromCurrency('work_price'),
                'materials_price'   => $item_params->getFloatFromCurrency('materials_price'),
                'price'             => $item_params->getFloatFromCurrency('price'),
                'user_id'           => $this->userId
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
            
            $biscuit_id = $this->db->lastInsertId();
            
            global $QUERY_insert_biscuit_materials;
            $statement = $this->db->prepare($QUERY_insert_biscuit_materials);
            for ($i = 0; $item_params->has("mat-$i") && $item_params->getInt("mat-$i") != -1; $i++) {
                if (!$statement->execute([
                        $biscuit_id,
                        $item_params->getInt("mat-$i"),
                        $item_params->getFloat("mat-$i" . '_quantityUsed'),
                        $item_params->getFloatFromCurrency("mat-$i" . '_calculatedPrice')
                ])) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
        }
        
        public function calculateMaxPossibleEntry($biscuit_id) {
            global $QUERY_calculate_biscuit_max_possible_entry;
            $statement = $this->db->prepare($QUERY_calculate_biscuit_max_possible_entry);
            $statement->bindValue('biscuit_id', $biscuit_id);
            $statement->execute();
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function increaseQuantity($params) {
            $amount = $params->getInt('quantity_increase');
            if ($amount > 0) {
                $id = $params->getInt('id');
                
                global $QUERY_increase_biscuit_quantity;
                $statement = $this->db->prepare($QUERY_increase_biscuit_quantity);
                $data = [
                    'biscuit_id'            => $id,
                    'amount'                => $amount
                ];
                if (!$statement->execute($data)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
                
                global $QUERY_insert_biscuits;
                $statement = $this->db->prepare($QUERY_insert_biscuits);
                $item = [
                    'biscuit_id'            => $id,
                    'quantity'              => $amount,
                    'user_id'               => $this->userId
                ];
                if (!$statement->execute($item)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
        }

        public function fetch($id) {
            $query = 'SELECT b.* FROM biscuit b WHERE b.id = :id';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id);
            $statement->execute();
            $row = $statement->fetch();
            return $row;
        }
        
        public function fetchMaterials($biscuit_id) {
            global $QUERY_select_biscuit_materials;
            $statement = $this->db->prepare($QUERY_select_biscuit_materials);
            $statement->bindValue('biscuit_id', $biscuit_id);
            $statement->execute();
            $rows = $statement->fetchAll();
            return $rows;
        }

        public function updateDefinition($item_params) {
            global $QUERY_update_biscuit_item;
            $statement = $this->db->prepare($QUERY_update_biscuit_item);
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
