<?php
/**
 * 模拟支付（测试用）：直接返回成功页。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MyP_Core_Payment_DemoPayment extends MyP_Core_Payment_BasePayment {
	protected $gateway = 'demo';

	public function pay( $order ) {
		return array(
			'type' => 'redirect',
			'url'  => add_query_arg( array(
				'myp_pay'   => 'demo',
				'order_no'  => $order->order_no,
				'sign'      => md5( $order->order_no . 'demo' ),
			), home_url( '/checkout/return' ) ),
		);
	}

	public function verify_callback( $request ) {
		return ( isset( $request['order_no'] ) && md5( $request['order_no'] . 'demo' ) === ( $request['sign'] ?? '' ) );
	}
}
