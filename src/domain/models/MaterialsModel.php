<?php

    require_once('./src/core/AbstractModel.php');
    require('./src/domain/models/queries.php');
    
    global $QUERY_insert_material_item;
    $QUERY_insert_material_item                     = $insert_material_item;
    
    global $QUERY_increase_quantity_material;
    $QUERY_increase_quantity_material               = $increase_quantity_material;
    
    global $QUERY_insert_materials;
    $QUERY_insert_materials                         = $insert_materials;
    
    global $QUERY_update_material_item;
    $QUERY_update_material_item                     = $update_material_item;
    
    /*global $QUERY_insert_material_deleted_item;
    $QUERY_insert_material_deleted_item             = $insert_material_deleted_item;*/
    
    global $QUERY_insert_material_copy_deleted;
    $QUERY_insert_material_copy_deleted             = $insert_material_copy_deleted;
    
    global $QUERY_delete_material_item;
    $QUERY_delete_material_item                     = $delete_material_item;

    
    class MaterialsModel extends AbstractModel {

        public function findItems() {
            $query = 'SELECT m.* FROM material m ORDER BY m.code';      // FIXME WHERE NOT m.deleted
            $statement = $this->db->query($query);
            $rows = $statement->fetchAll();
            return $rows;
        }

        public function sumStorageMoney() {
            $query = 'SELECT sum(m.current_value) FROM material m';     // FIXME WHERE NOT m.deleted
            $statement = $this->db->query($query);
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function newDefinition($item_params) {
            global $QUERY_insert_material_item;
            $statement = $this->db->prepare($QUERY_insert_material_item);
            $item = [
                'code'                  => $item_params->getString('code'),
                'name'                  => $item_params->getString('name'),
                'note'                  => $item_params->getString('note'),
                'package_quantity'      => $item_params->getInt('package_quantity'),
                'package_measure_unit'  => $item_params->getString('package_measure_unit'),
                'price'                 => $item_params->getFloatFromCurrency('price'),
                'user_id'               => $this->userId
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
        }

        public function fetch($id) {
            $query = 'SELECT m.* FROM material m WHERE m.id=:id';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id);
            $statement->execute();
            $row = $statement->fetch();
            return $row;
        }
        
        public function increaseQuantity($params) {
            $amount = $params->getInt('quantity_increase');
            if ($amount > 0) {
                $id = $params->getInt('id');
                
                global $QUERY_increase_quantity_material;
                $statement = $this->db->prepare($QUERY_increase_quantity_material);
                $data = [
                    'id'                    => $id,
                    'amount'                => $amount
                ];
                if (!$statement->execute($data)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
                
                global $QUERY_insert_materials;
                $statement = $this->db->prepare($QUERY_insert_materials);
                $data = [
                    'material_id'           => $id,
                    'quantity'              => $amount,
                    'user_id'               => $this->userId
                ];
                if (!$statement->execute($data)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
        }

        public function updateDefinition($item_params) {
            global $QUERY_update_material_item;
            $statement = $this->db->prepare($QUERY_update_material_item);
            $item = [
                'id'                    => $item_params->getInt('id'),
                'code'                  => $item_params->getString('code'),
                'name'                  => $item_params->getString('name'),
                'note'                  => $item_params->getString('note')
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
        }
        
        public function deleteItem($id, $type = null) {
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
            
            global $QUERY_insert_material_copy_deleted;
            $statement = $this->db->prepare($QUERY_insert_material_copy_deleted);
            $item = [
                'id'                    => $id,
                'user_id'               => $this->userId
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
            
            global $QUERY_delete_material_item;
            $statement = $this->db->prepare($QUERY_delete_material_item);
            $statement->bindValue('id', $id);
            if (!$statement->execute()) {
                throw new Exception($statement->errorInfo()[2]);
            }
        }

    }

?>
