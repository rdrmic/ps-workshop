<?php

    require_once('Template.php');

    abstract class AbstractView {
        const TEMPLATE_LIST_HEADER = "items_list_header.tpl";
        const TEMPLATE_LIST = "items_list.tpl";
        const TEMPLATE_INCREASE_QUANTITY = 'items_increaseQuantity.tpl';
        const TEMPLATE_SHOW_USAGES = 'items_showUsages.tpl';
        
        protected $module;
        
        protected $pieceUnit;

        public function __construct($module = null) {
            $this->module = $module;
            $this->pieceUnit = Config::getInstance()->get('piece-unit');
        }
        
        
        protected static function assembleAppVersionLabel() {
            $environment = Config::getInstance()->get('environment');
            $app_version_arr = Config::getInstance()->get('app-version');
            $app_version = 'v ' . $app_version_arr['version'];
            if ($environment != Config::ENV_PROD) {
                $app_version .= '_' . $environment;
            }
            return $app_version;
        }

        protected function formatToCurrency($amount) {              // TODO static ?
            return number_format($amount, 2, ',', '.');
        }
        
        // 1.235,38 -> 1235,38; 2,60 -> 2,6; 2,00 -> 2
        protected function formatToMinimalFloatNumber($amount) {         // TODO static ?
            $str = number_format($amount, 3, ',', '');
            $str = rtrim($str, '0');
            $str = rtrim($str, ',');
            return $str;
        }
        
        
        public function loadTemplate($template) {                   // TODO static ?
            return new Template("./templates/" . $template);
        }

        public function renderPage($content) {
            $params = [
                'active_'.$this->module     => 'active',
                'url_base'                  => Config::getInstance()->getUrlBase()
            ];
            $menu = $this->loadTemplate("menu.tpl")->render($params);

            $params = [
                'app_version'       => self::assembleAppVersionLabel(),
                'menu'              => $menu,
                'content'           => $content
            ];
            return $this->loadTemplate("layout.tpl")->render($params);
        }
        
        
        protected function renderItemList($rows) {
            $html = '';
            foreach ($rows as $row) {
                $html .= '<tr class="countable-rows">';
                $html .= '<td>' . $row['code'] . '</td>';
                $html .= '<td>' . $row['name'] . '</td>';
                $html .= '<td class="table_right_align">' . $this->formatToCurrency($row['price']) . ' Kn</td>';
                $html .= '<td>' . $row['note'] . '</td>';
                $html .= '<td class="table_right_align">' . $row['current_quantity'] . '</td>';
                $html .= '<td class="table_right_align">' . $this->formatToCurrency($row['current_value']) . ' Kn</td>';
                
                // buttons
                $storage_and_id = "$this->module/" . $row['id'];
                $html .= '
                    <td class="action-button-td">
                        <button type="button" class="button-orange" onclick="document.location=\'?' . $storage_and_id . '/add\';" title="Unos u skladište">
                            +
                        </button>
                    </td>
                ';
                $html .= '
                    <td class="action-button-td">
                        <button type="button" class="button-orange" onclick="document.location=\'?' . $storage_and_id . '/update\';" title="Pregled i ažuriranje detalja">
                            &gt;
                        </button>
                    </td>
                ';
                $html .= '
                    <td class="action-button-td">
                        <form method="post">
                            <input type="hidden" name="delete-id" value="' . $row['id'] . '" />
                            <input type="submit" class="form-style-1 button-orange submit-in-list" name="submit" value="x" title="Brisanje" onclick="return confirm(\'Potrebno potvrditi\');" />
                        </form>
                    </td>
                ';
                $html .= '</tr>';
            }
            return $html;
        }
        
        public function listView($data) {
            $params = [
                'storage' => $this->module,
                'new-item-definition-button' => static::NEW_ITEM_DEFINITON_BUTTON
            ];
            $item_list_header = $this->loadTemplate(self::TEMPLATE_LIST_HEADER)->render($params);
            
            $params = [
                'item_list_header' => $item_list_header,
                'item-list' => $this->renderItemList($data['rows']),
                'storage-money-sum' => $this->formatToCurrency($data['storage_money_sum'])
            ];
            return $this->loadTemplate(static::TEMPLATE_LIST)->render($params);
        }
        
        public function increaseQuantityView($data) {
            $id = $data['id'];
            $max_possible_entry = $data['max_possible_entry'];
            $quantity_increase = $data['quantity_increase'];
            
            $params = [
                'id'                    => $id,
                'href'                  => Config::getInstance()->getUrlBase() . "?$this->module/$id/update",
                'max_possible_entry'    => $max_possible_entry,
                'storage'               => $this->module,
                'quantity_increase'     => $quantity_increase,
                'msg'                   => $quantity_increase != null && $quantity_increase > $max_possible_entry ? 'Nedovoljno sirovina!' : null
            ];
            return $this->loadTemplate(self::TEMPLATE_INCREASE_QUANTITY)->render($params);
        }
        
        public function showUsagesView() {
            $params = [
                'storage'               => $this->module
            ];
            return $this->loadTemplate(self::TEMPLATE_SHOW_USAGES)->render($params);
        }
        
        
        protected function renderMaterialsList($materials) {
            $html = '<table>';
            foreach ($materials as $m) {
                $html .= '<tr>';
                // code, name, packaging
                $html .= '<td style="padding-right:50px;">' .
                    $m['code'] . ', ' . $m['name'] . ' ('  . $this->formatToCurrency($m['price']) . ' Kn / ' . $m['package_quantity'] . ' ' . $m['package_measure_unit'] . ')' .
                '</td>';
                
                $html .= '<td class="item-quantity-used">' .
                    $this->formatToMinimalFloatNumber($m['material_quantity_used']) . ' ' . $m['package_measure_unit'] .
                '</td>';
                if ($m['material_quantity_used'] > $m['current_quantity_in_measure_unit']) {
                    $available_class = 'item-quantity-not-available';
                } else {
                    $available_class = 'item-quantity-available';
                }
                $html .= '<td class="' . $available_class . '" style="padding-right:30px;">' .
                    '&nbsp;/ ' . $this->formatToMinimalFloatNumber($m['current_quantity_in_measure_unit']) . ' ' . $m['package_measure_unit'] .
                '</td>';
                $html .= '<td></td>';
                $html .= '<td style="font-style: italic; text-align: right;">' . $this->formatToCurrency($m['material_calculated_price']) . ' Kn</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
            return $html;
        }

    }

?>
