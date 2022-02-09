<?php

    require_once('./src/core/AbstractView.php');

    class ProductsView extends AbstractView {
        const TEMPLATE_NEW_DEFINITION = 'products_newDefinition.tpl';
        const TEMPLATE_DETAILS = 'products_details.tpl';
    
        const NEW_ITEM_DEFINITON_BUTTON = 'Novi gotovi proizvod';
        
        
        public function newDefinitionView($codesInUse, $incompletes, $materials) {
            $data = [
                'codes-in-use'      => htmlspecialchars(json_encode($codesInUse)),
                'incompletes'       => htmlspecialchars(json_encode($incompletes)),
                'materials'         => htmlspecialchars(json_encode($materials))
            ];
            return $this->loadTemplate(self::TEMPLATE_NEW_DEFINITION)->render($data);
        }
        
        public function detailView($params, $incompletes, $materials) {
            $params['work_price']           = $this->formatToCurrency($params['work_price']);
            $params['incompletes_price']    = $this->formatToCurrency($params['incompletes_price']);
            $params['materials_price']      = $this->formatToCurrency($params['materials_price']);
            $params['price']                = $this->formatToCurrency($params['price']);
            
            $params['incompletes_list']     = $this->renderIncompletesList($incompletes);
            $params['materials_list']       = $this->renderMaterialsList($materials);
            return $this->loadTemplate(self::TEMPLATE_DETAILS)->render($params);
        }
        
        private function renderIncompletesList($incompletes) {
            $html = '<table>';
            foreach ($incompletes as $i) {
                $html .= '<tr>';
                // code, name, packaging
                $html .= '<td style="padding-right:50px;">' .
                    $i['code'] . ', ' . $i['name'] . ' ('  . $this->formatToCurrency($i['price']) . ' Kn)' .
                '</td>';
                
                $html .= '<td class="item-quantity-used">' . $i['incomplete_quantity_used'] . ' ' . $this->pieceUnit . '</td>';
                if ($i['incomplete_quantity_used'] > $i['current_quantity']) {
                    $available_class = 'item-quantity-not-available';
                } else {
                    $available_class = 'item-quantity-available';
                }
                $html .= '<td class="' . $available_class . '" style="padding-right:30px;">&nbsp;/ ' . $i['current_quantity'] . ' ' . $this->pieceUnit . '</td>';
                $html .= '<td></td>';
                $html .= '<td style="font-style: italic; text-align: right;">' . $this->formatToCurrency($i['incomplete_calculated_price']) . ' Kn</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            return $html;
        }

    }

?>
