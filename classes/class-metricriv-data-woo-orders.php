<?php

defined('ABSPATH') || exit;

class MetricRiv_Data_Woo_Orders {
	public static function output($range, $start, $end) {
        global $metricRiv;

        if(!class_exists('WooCommerce')) {
            return $metricRiv->errorResponse();
        } elseif($range == 'all') {
            return self::all();
        } else {
            return self::filter($start, $end);
        }
	}

	public static function all() {
        // Uses total from the paginate response to get the actual number.
        $items = wc_get_orders([
            'type' => 'shop_order',
            'status' => ['wc-processing', 'wc-completed'],
            'limit' => 10,
            'return' => 'ids',
            'paginate' => true,
        ]);

        $output = [];
        $output['value'] = $items->total;
        $output['range'] = 'all';
        $output['start'] = '';
        $output['end'] = '';
        return $output;
    }

	public static function filter($start, $end) {
        global $metricRiv;

        $ts_start = $metricRiv->getTimestamp($start);
        $ts_end = $metricRiv->getTimestamp($end);

        // Uses total from the paginate response to get the actual number.
        $items = wc_get_orders([
            'type' => 'shop_order',
            'status' => ['wc-processing', 'wc-completed'],
            'limit' => 10,
            'return' => 'ids',
            'date_created' => $ts_start . '...' . $ts_end,
            'paginate' => true,
        ]);

        $output = [];
        $output['value'] = $items->total;
        $output['range'] = 'custom';
        $output['start'] = $start;
        $output['end'] = $end;
        return $output;
    }
}
