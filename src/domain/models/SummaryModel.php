<?php

    require_once('./src/core/AbstractModel.php');
    require('./src/domain/models/queries.php');
    
    global $QUERY_fetch_summary;
    $QUERY_fetch_summary = $fetch_summary;
    
    // only for testing purposes
    // TODO find programmatically
    global $tablesToDelete;
    $tablesToDelete = [
        'biscuit',
        'biscuits',
        'biscuit_deleted',
        'biscuit_materials',
        //'biscuit_version',
        'factured',
        'factureds',
        'incomplete',
        'incompletes',
        'incomplete_deleted',
        'incomplete_biscuits',
        'incomplete_materials',
        //'incomplete_version',
        'material',
        'materials',
        'material_deleted',
        //'material_version',
        'product',
        'products',
        'product_deleted',
        'product_incompletes',
        'product_materials'
        //'product_version',
    ];

    class SummaryModel extends AbstractModel {

        public function getSummaryData() {
            global $QUERY_fetch_summary;
            $statement = $this->db->query($QUERY_fetch_summary);
            $row = $statement->fetch();
            return $row;
        }
        
        /* only for testing purposes */
        public function deleteAllData() {
            global $tablesToDelete;
            foreach ($tablesToDelete as $table) {
                $query = "TRUNCATE TABLE $table";
                $statement = $this->db->prepare($query);
                if (!$statement->execute()) {
                    throw new Exception($statement->errorInfo()[2]);
                }
            }
        }

    }

?>
