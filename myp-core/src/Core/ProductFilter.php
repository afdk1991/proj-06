<?php
/**
 * 高级筛选：价格区间 / 会员价 / 库存。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MyP_Core_Core_ProductFilter {

	public function __construct() {
		add_action( 'myp_product_query', array( $this, 'apply' ), 10, 1 );
	}

	public function apply( $args ) {
		$args['meta_query'] = array();

		if ( ! empty( $_GET['min_price'] ) ) {
			$args['meta_query'][] = array(
				'key'     => '_myp_price',
				'value'   => floatval( $_GET['min_price'] ),
				'type'    => 'NUMERIC',
				'compare' => '>=',
			);
		}
		if ( ! empty( $_GET['max_price'] ) ) {
			$args['meta_query'][] = array(
				'key'     => '_myp_price',
				'value'   => floatval( $_GET['max_price'] ),
				'type'    => 'NUMERIC',
				'compare' => '<=',
			);
		}
		if ( ! empty( $_GET['in_stock'] ) ) {
			$args['meta_query'][] = array(
				'key'     => '_myp_stock',
				'value'   => 0,
				'type'    => 'NUMERIC',
				'compare' => '>',
			);
		}
		return $args;
	}
}
