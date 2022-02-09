<?php

    require('./src/domain/models/queries.php');
    
    global $QUERY_select_factured_items;
    $QUERY_select_factured_items                            = $select_factured_items;
    
    global $QUERY_select_product_id;
    $QUERY_select_product_id                                = $select_product_id;
    
    global $QUERY_calculate_factured_max_possible_entry;
    $QUERY_calculate_factured_max_possible_entry            = $calculate_factured_max_possible_entry;
    
    global $QUERY_increase_factured_quantity;
    $QUERY_increase_factured_quantity                       = $increase_factured_quantity;
    
    global $QUERY_insert_factureds;
    $QUERY_insert_factureds                                 = $insert_factureds;
    
    class FacturedsModel extends AbstractModel {

        public function findItems() {
            global $QUERY_select_factured_items;
            $stmt = $this->db->query($QUERY_select_factured_items);
            $rows = $stmt->fetchAll();
            return $rows;
        }

        public function sumStorageMoney() {
            $query = 'SELECT sum(f.current_value) FROM factured f';
            $statement = $this->db->query($query);
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function getProductId($factured_id) {
            global $QUERY_select_product_id;
            $statement = $this->db->prepare($QUERY_select_product_id);
            $statement->bindValue('factured_id', $factured_id); 
            $statement->execute();
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function calculateMaxPossibleEntry($factured_id) {
            global $QUERY_calculate_factured_max_possible_entry;
            $statement = $this->db->prepare($QUERY_calculate_factured_max_possible_entry);
            $statement->bindValue('factured_id', $factured_id);
            $statement->execute();
            $value = $statement->fetch(PDO::FETCH_NUM)[0];
            return $value;
        }
        
        public function increaseQuantity($params) {
            $id = $params->getInt('id');
            $amount = $params->getInt('quantity_increase');
            
            global $QUERY_increase_factured_quantity;
            $statement = $this->db->prepare($QUERY_increase_factured_quantity);
            $data = [
                'factured_id'       => $id,
                'amount'            => $amount
            ];
            if (!$statement->execute($data)) {
                throw new Exception($statement->errorInfo()[2]);
            }
            
            global $QUERY_insert_factureds;
            $statement = $this->db->prepare($QUERY_insert_factureds);
            $item = [
                'factured_id'           => $id,
                'quantity'              => $amount,
                'user_id'               => $_SESSION['user']['id']
            ];
            if (!$statement->execute($item)) {
                throw new Exception($statement->errorInfo()[2]);
            }
        }

    }

?>
