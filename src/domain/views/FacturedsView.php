<?php

    class FacturedsView extends AbstractView {
        const TEMPLATE_LIST = "factureds_list.tpl";
        const TEMPLATE_INCREASE_QUANTITY = 'factureds_increaseQuantity.tpl';
        
        
        protected function renderItemList($rows) {
            $html = '';
            foreach ($rows as $row) {
                $html .= '<tr class="countable-rows">';
                $html .= '<td>' . $row['code'] . '</td>';
                $html .= '<td>' . $row['name'] . '</td>';
                $html .= '<td class="table_right_align">' . $this->formatToCurrency($row['price']) . ' Kn</td>';
                $html .= '<td class="table_right_align">' . $row['current_quantity'] . '</td>';
                $html .= '<td class="table_right_align">' . $this->formatToCurrency($row['current_value']) . ' Kn</td>';
                
                // buttons
                if ($row['is_deleted']) {
                    $html .= '
                        <td class="action-button-td">
                            <button type="button" class="button-gray" title="Proizvod je obrisan">
                                +
                            </button>
                        </td>
                    ';
                } else {
                    $html .= '
                        <td class="action-button-td">
                            <button type="button" class="button-orange" onclick="document.location=\'?fact/' . $row['id'] . '/add\';" title="Fakturiranje">
                                +
                            </button>
                        </td>
                    ';
                }
                $html .= '</tr>';
            }
            return $html;
        }
        
        public function listView($data) {
            $params = [
                'item-list' => $this->renderItemList($data['rows']),
                'storage-money-sum' => $this->formatToCurrency($data['storage_money_sum'])
            ];
            return $this->loadTemplate(static::TEMPLATE_LIST)->render($params);
        }
        
        public function increaseQuantityView($data) {
            $id = $data['id'];
            $href = $data['product_id'] == null ? '' : '?products/' . $data['product_id'] .'/add';
            $max_possible_entry = $data['max_possible_entry'];
            $quantity_increase = $data['quantity_increase'];
            
            $data = [
                'id'                    => $id,
                'href'                  => Config::getInstance()->getUrlBase() . $href,
                'max_possible_entry'    => $max_possible_entry,
                'storage'               => $this->module,
                'quantity_increase'     => $quantity_increase,
                'msg'                   => $quantity_increase != null && $quantity_increase > $max_possible_entry ? 'Nedovoljno gotovih proizvoda!' : null
            ];
            return $this->loadTemplate(self::TEMPLATE_INCREASE_QUANTITY)->render($data);
        }

    }

?>
