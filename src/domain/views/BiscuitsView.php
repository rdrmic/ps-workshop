<?php

    require_once('./src/core/AbstractView.php');

    class BiscuitsView extends AbstractView {
        const TEMPLATE_NEW_DEFINITION = 'biscuits_newDefinition.tpl';
        const TEMPLATE_DETAILS = 'biscuits_details.tpl';
        
        const NEW_ITEM_DEFINITON_BUTTON = 'Novi biskvit';
        
        
        public function newDefinitionView($codesInUse, $materials) {
            $params = [
                'codes-in-use'      => htmlspecialchars(json_encode($codesInUse)),
                'materials'         => htmlspecialchars(json_encode($materials))
            ];
            return $this->loadTemplate(self::TEMPLATE_NEW_DEFINITION)->render($params);
        }
        
        public function detailView($params, $materials) {
            $params['work_price']           = $this->formatToCurrency($params['work_price']);
            $params['materials_price']      = $this->formatToCurrency($params['materials_price']);
            $params['price']                = $this->formatToCurrency($params['price']);
            
            $params['materials_list']       = $this->renderMaterialsList($materials);
            return $this->loadTemplate(self::TEMPLATE_DETAILS)->render($params);
        }

    }

?>
