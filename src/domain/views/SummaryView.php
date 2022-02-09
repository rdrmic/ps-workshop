<?php

    require_once('./src/core/AbstractView.php');

    class SummaryView extends AbstractView {
        const TEMPLATE_SUMMARY = "summary.tpl";

        public function listView($data) {
            $params = [
                'sum-materials'     => $this->formatToCurrency($data['sum_materials']),
                'sum-biscuits'      => $this->formatToCurrency($data['sum_biscuits']),
                'sum-incompletes'   => $this->formatToCurrency($data['sum_incompletes']),
                'sum-products'      => $this->formatToCurrency($data['sum_products']),
                'sum-all'           => $this->formatToCurrency($data['sum_all'])
            ];
            return $this->loadTemplate(self::TEMPLATE_SUMMARY)->render($params);
        }

    }

?>
