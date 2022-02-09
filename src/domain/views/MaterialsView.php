<?php

    require_once('./src/core/AbstractView.php');

    class MaterialsView extends AbstractView {
        const TEMPLATE_LIST = "materials_list.tpl";
        const TEMPLATE_INCREASE_QUANTITY = 'materials_increaseQuantity.tpl';
        const TEMPLATE_DETAILS = 'materials_details.tpl';
        
        const NEW_ITEM_DEFINITON_BUTTON = 'Novi materijal';
        

        protected function renderItemList($rows) {
            $html = '';
            foreach ($rows as $row) {
                $html .= '<tr class="countable-rows">';
                $html .= '<td>' . $row['code'] . '</td>';
                $html .= '<td>' . $row['name'] . '</td>';
                $html .= '<td class="table_right_align">' . $row['package_quantity'] . ' ' . $row['package_measure_unit'] . '</td>';
                $html .= '<td class="table_right_align">' . $this->formatToCurrency($row['price']) . ' Kn</td>';
                $html .= '<td>' . $row['note'] . '</td>';
                $html .= '
                    <td class="table_right_align">' .
                        '<span style="color: #555;">' . $this->formatToMinimalFloatNumber($row['current_quantity']) . ' &nbsp;&nbsp;</span>' .
                        '<span style="font-weight: bold; color: #333;">' .
                            $this->formatToMinimalFloatNumber($row['current_quantity_in_measure_unit']) . ' ' . $row['package_measure_unit'] .
                        '</span>' .
                    '</td>
                ';
                $html .= '<td class="table_right_align">' . $this->formatToCurrency($row['current_value']) . ' Kn</td>';
                
                // buttons
                $html .= '
                    <td class="action-button-td">
                        <button type="button" class="button-orange" onclick="document.location=\'?materials/' . $row['id'] . '/add\';" title="Unos u skladište">
                            +
                        </button>
                    </td>
                ';
                $html .= '
                    <td class="action-button-td">
                        <button type="button" class="button-orange" onclick="document.location=\'?materials/' . $row['id'] . '/update\';" title="Pregled i ažuriranje detalja">
                            &gt;
                        </button>
                    </td>
                ';
                $html .= '
                    <td class="action-button-td">
                        <form method="post">
                            <input type="hidden" name="delete-id" value="' . $row['id'] . '" />
                            <input type="submit" class="form-style-1 button-orange submit-in-list" name="submit" value="x" title="Brisanje"
                                onclick="return confirm(\'Potrebno potvrditi\');" />
                        </form>
                    </td>
                ';
                $html .= '</tr>';
            }
            return $html;
        }

        
        public function newDefinitionView($codesInUse) {
            $params = [
                'codes-in-use'      => htmlspecialchars(json_encode($codesInUse))
            ];
            return $this->loadTemplate(self::TEMPLATE_DETAILS)->render($params);    // TODO refactor?   self::TEMPLATE_NEW_DEFINITION ?
        }

        public function detailView($data) {
            $params = [
                'id'                        => $data['id'],
                'code'                      => $data['code'],
                'name'                      => $data['name'],
                'note'                      => $data['note'],
                'package_quantity'          => $data['package_quantity'],
                'package_measure_unit'      => $data['package_measure_unit'],
                'price'                     => $this->formatToCurrency($data['price'])
            ];
            return $this->loadTemplate(self::TEMPLATE_DETAILS)->render($params);
        }
        
        public function increaseQuantityView($data) {
            return $this->loadTemplate(self::TEMPLATE_INCREASE_QUANTITY)->render($data);
        }

    }

?>
