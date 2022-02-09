<?php

    require_once('./src/core/AbstractView.php');

    class IncompletesView extends AbstractView {
        const TEMPLATE_NEW_DEFINITION = 'incompletes_newDefinition.tpl';
        const TEMPLATE_DETAILS = 'incompletes_details.tpl';
        
        const NEW_ITEM_DEFINITON_BUTTON = 'Novi poluproizvod';
        
        
        public function newDefinitionView($codesInUse, $biscuits, $materials) {
            $data = [
                'codes-in-use'      => htmlspecialchars(json_encode($codesInUse)),
                'biscuits'          => htmlspecialchars(json_encode($biscuits)),
                'materials'         => htmlspecialchars(json_encode($materials))
            ];
            return $this->loadTemplate(self::TEMPLATE_NEW_DEFINITION)->render($data);
        }
        
        public function detailView($params, $biscuits, $materials) {
            $params['work_price']           = $this->formatToCurrency($params['work_price']);
            $params['biscuits_price']       = $this->formatToCurrency($params['biscuits_price']);
            $params['materials_price']      = $this->formatToCurrency($params['materials_price']);
            $params['price']                = $this->formatToCurrency($params['price']);
            
            $params['biscuits_list']        = $this->renderBiscuitsList($biscuits);
            $params['materials_list']       = $this->renderMaterialsList($materials);
            return $this->loadTemplate(self::TEMPLATE_DETAILS)->render($params);
        }
        
        private function renderBiscuitsList($biscuits) {
            $html = '<table>';
            foreach ($biscuits as $b) {
                $html .= '<tr>';
                // code, name, packaging
                $html .= '<td style="padding-right:50px;">' .
                    $b['code'] . ', ' . $b['name'] . ' ('  . $this->formatToCurrency($b['price']) . ' Kn)' .
                '</td>';
                
                $html .= '<td class="item-quantity-used">' . $b['biscuit_quantity_used'] . ' ' . $this->pieceUnit . '</td>';
                if ($b['biscuit_quantity_used'] > $b['current_quantity']) {
                    $available_class = 'item-quantity-not-available';
                } else {
                    $available_class = 'item-quantity-available';
                }
                $html .= '<td class="' . $available_class . '" style="padding-right:30px;">&nbsp;/ ' . $b['current_quantity'] . ' ' . $this->pieceUnit . '</td>';
                $html .= '<td></td>';
                $html .= '<td style="font-style: italic; text-align: right;">' . $this->formatToCurrency($b['biscuit_calculated_price']) . ' Kn</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            return $html;
        }

    }

?>
