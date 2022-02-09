<?php

    require('./src/domain/models/queries.php');
    
    global $QUERY_select_storage_added_items;
    $QUERY_select_storage_added_items                       = $select_storage_added_items;
    
    global $QUERY_select_factureds_added_items;
    $QUERY_select_factureds_added_items                     = $select_factureds_added_items;
    
    class OverviewsModel extends AbstractModel {

        public function findAddedItemsForAllModules($date_range) {
            $data = [];
            
            // storages
            global $QUERY_select_storage_added_items;
            foreach (array_keys(OverviewsController::getModules()['storages']) as $storage_name) {
                $query = str_replace('{item_type}', rtrim($storage_name, 's'), $QUERY_select_storage_added_items);
                $data[$storage_name] = $this->findAddedItems($query, $date_range);
            }
            
            // factureds
            global $QUERY_select_factureds_added_items;
            $storage_name = array_keys(OverviewsController::getModules()['factureds'])[0];
            $query = $QUERY_select_factureds_added_items;
            $data[$storage_name] = $this->findAddedItems($query, $date_range);
            
            return $data;
        }
        
        private function findAddedItems($query, $date_range) {
            //__show($query);
            
            $statement = $this->db->prepare($query);
            //$statement->bindValue('items_table', MaterialsController::MODULE_NAME);
            $statement->execute($date_range);
            $rows = $statement->fetchAll();
            
            $total_value = .0;
            foreach ($rows AS $row) {
                $total_value += $row['value'];
            }
            
            $data = [
                'total_value'       => $total_value,
                'rows'              => $rows
            ];
            return $data;
        }

    }

?>
