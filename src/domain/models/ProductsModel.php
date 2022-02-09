<?php

    require_once('./src/core/AbstractModel.php');
    require('./src/domain/models/queries.php');
    
    global $QUERY_insert_product_item;
    $QUERY_insert_product_item                              = $insert_product_item;
    
    global $QUERY_insert_product_incompletes;
    $QUERY_insert_product_incompletes                       = $insert_product_incompletes;
    
    global $QUERY_insert_product_materials;
    $QUERY_insert_product_materials                         = $insert_product_materials;
    
    global $QUERY_calculate_product_max_possible_entry;
    $QUERY_calculate_product_max_possible_entry             = $calculate_product_max_possible_entry;
    
    global $QUERY_increase_product_quantity;
    $QUERY_increase_product_quantity                        = $increase_product_quantity;
    
    global $QUERY_insert_products;
    $QUERY_insert_products                                  = $insert_products;
    
    global $QUERY_insert_factured_item;
    $QUERY_insert_factured_item                             = $insert_factured_item;
    
    global $QUERY_select_product_incompletes;
    $QUERY_select_product_incompletes                       = $select_product_incompletes;
    
    global $QUERY_select_product_materials;
    $QUERY_select_product_materials                         = $select_product_materials;
    
    global $QUERY_update_product_item;
    $QUERY_update_product_item                              = $update_product_item;

    class ProductsModel extends AbstractModel {

        public function findItems() {
            $query = 'SELECT p.* FROM product p ORDER BY p.code';
            $stmt = $this->db->query($query);
            $rows = $stmt->fetchAll();
            return $rows;
        }

        public function sumStorageMoney() {
            $query = 'SELECT sum(p.current_value) FROM product p';
            $statement = $this->db->query($query);
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function newDefinition($item_params) {
            global $QUERY_insert_product_item;
            $statement = $this->db->prepare($QUERY_insert_product_item);
            $item = [
                'code'              => $item_params->getString('code'),
                'name'              => $item_params->getString('name'),
                'note'              => $item_params->getString('note'),
                'work_price'        => $item_params->getFloatFromCurrency('work_price'),
                'incompletes_price' => $item_params->getFloatFromCurrency('incompletes_price'),
                'materials_price'   => $item_params->getFloatFromCurrency('materials_price'),
                'price'             => $item_params->getFloatFromCurrency('price'),
                'user_id'           => $this->userId
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
            
            $incomplete_id = $this->db->lastInsertId();
            
            global $QUERY_insert_product_incompletes;
            $statement = $this->db->prepare($QUERY_insert_product_incompletes);
            for ($i = 0; $item_params->has("inc-$i") && $item_params->getInt("inc-$i") != -1; $i++) {
                if (!$statement->execute([
                        $incomplete_id,
                        $item_params->getInt("inc-$i"),
                        $item_params->getFloat("inc-$i" . '_quantityUsed'),
                        $item_params->getFloatFromCurrency("inc-$i" . '_calculatedPrice')
                ])) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
            
            global $QUERY_insert_product_materials;
            $statement = $this->db->prepare($QUERY_insert_product_materials);
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
        
        public function calculateMaxPossibleEntry($product_id) {
            global $QUERY_calculate_product_max_possible_entry;
            $statement = $this->db->prepare($QUERY_calculate_product_max_possible_entry);
            $statement->execute([$product_id, $product_id]);
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function increaseQuantity($params) {
            $amount = $params->getInt('quantity_increase');
            if ($amount > 0) {
                $id = $params->getInt('id');
                
                global $QUERY_increase_product_quantity;
                $statement = $this->db->prepare($QUERY_increase_product_quantity);
                $data = [
                    'product_id'        => $id,
                    'amount'            => $amount
                ];
                if (!$statement->execute($data)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
                
                global $QUERY_insert_products;
                $statement = $this->db->prepare($QUERY_insert_products);
                $item = [
                    'product_id'            => $id,
                    'quantity'              => $amount,
                    'user_id'               => $_SESSION['user']['id']
                ];
                if (!$statement->execute($item)) {
                    throw new Exception($statement->errorInfo()[2]);
                }
                
                global $QUERY_insert_factured_item;
                $statement = $this->db->prepare($QUERY_insert_factured_item);
                if (!$statement->execute([$id, $id])) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
        }
        
        public function fetch($id) {
            $query = 'SELECT * FROM product i WHERE i.id = :id';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id);
            $statement->execute();
            $row = $statement->fetch();
            return $row;
        }
        
        public function fetchIncompletes($product_id) {
            global $QUERY_select_product_incompletes;
            $statement = $this->db->prepare($QUERY_select_product_incompletes);
            $statement->bindValue('product_id', $product_id);
            $statement->execute();
            $rows = $statement->fetchAll();
            return $rows;
        }
        
        public function fetchMaterials($product_id) {
            global $QUERY_select_product_materials;
            $statement = $this->db->prepare($QUERY_select_product_materials);
            $statement->bindValue('product_id', $product_id);
            $statement->execute();
            $rows = $statement->fetchAll();
            return $rows;
        }
        
        public function updateDefinition($item_params) {
            global $QUERY_update_product_item;
            $statement = $this->db->prepare($QUERY_update_product_item);
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
