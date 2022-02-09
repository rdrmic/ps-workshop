<?php

    class OverviewsView extends AbstractView {
        const TEMPLATE_LIST = "overviews_list.tpl";
        

        protected function renderItemList($allModulesData) {
            $facturedsData = array_pop($allModulesData);
            
            $html = '';
            // storages
            foreach ($allModulesData as $module_name => $module_data) {
                $table_sub_header = OverviewsController::getModules()['storages'][$module_name];
                $html .= '<tr>
                    <td colspan="4" class="overviews-th-storage">' . $table_sub_header . '</td>
                    <td class="overviews-th-storage-value">'. $this->formatToCurrency($module_data['total_value']) . ' Kn</td>
                </tr>';
                foreach ($module_data['rows'] as $row) {
                    $html .= '<tr class="countable-rows-js">';
                    $html .= '<td class="overviews">' . $row['code'] . '</td>';
                    $html .= '<td class="overviews">' . $row['name'] . '</td>';
                    $html .= '<td class="table_right_align overviews">' . $this->formatToCurrency($row['price']) . ' Kn</td>';
                    $html .= '<td class="table_right_align overviews">' . $row['quantity'] . '</td>';
                    $html .= '<td class="table_right_align overviews">' . $this->formatToCurrency($row['value']) . ' Kn</td>';
                    $html .= '</tr>';
                }
            }
            // factureds
            $table_sub_header = OverviewsController::getModules()['factureds'][current(array_keys(OverviewsController::getModules()['factureds']))];
            $html .= '<tr>
                <td colspan="4" class="overviews-th-storage overviews-factureds-bold">' . $table_sub_header . '</td>
                <td class="overviews-th-storage-value overviews-factureds-bold">'. $this->formatToCurrency($facturedsData['total_value']) . ' Kn</td>
            </tr>';
            foreach ($facturedsData['rows'] as $row) {
                $html .= '<tr class="countable-rows-js">';
                $html .= '<td class="overviews overviews-factureds">' . $row['code'] . '</td>';
                $html .= '<td class="overviews overviews-factureds">' . $row['name'] . '</td>';
                $html .= '<td class="table_right_align overviews overviews-factureds">' . $this->formatToCurrency($row['price']) . ' Kn</td>';
                $html .= '<td class="table_right_align overviews overviews-factureds">' . $row['quantity'] . '</td>';
                $html .= '<td class="table_right_align overviews overviews-factureds">' . $this->formatToCurrency($row['value']) . ' Kn</td>';
                $html .= '</tr>';
            }
            return $html;
        }
        
        public function listView($data) {
            $params = [
                'start_date'    => $data['start_date'],
                'finish_date'   => $data['finish_date'],
                'item-list'     => $this->renderItemList($data['all_modules_data'])
            ];
            return $this->loadTemplate(static::TEMPLATE_LIST)->render($params);
        }

    }

?>
