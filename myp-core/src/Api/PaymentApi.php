<?php
/**
 * 支付 API：发起支付 + 回调。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MyP_Core_Api_PaymentApi {

	public static function register_routes() {
		register_rest_route( MYP_REST_NAMESPACE, '/pay', array(
			'methods' => 'POST',
			'callback' => array( __CLASS__, 'pay' ),
			'permission_callback' => function () { return is_user_logged_in(); },
		) );
		register_rest_route( MYP_REST_NAMESPACE, '/pay/callback', array(
			'methods' => 'GET,POST',
			'callback' => array( __CLASS__, 'callback' ),
			'permission_callback' => '__return_true',
		) );
	}

	public static function pay( $req ) {
		$order_no = sanitize_text_field( $req->get_param( 'order_no' ) );
		$order = ( new MyP_Core_Database_OrderModel() )->get_by_no( $order_no );
		if ( ! $order ) {
			return myp_response( 404, '订单不存在' );
		}
		$gateway = MyP_Core_Payment_PaymentManager::gateway( 'demo' );
		$pay     = $gateway->pay( $order );
		return myp_response( 0, 'ok', $pay );
	}

	public static function callback( $req ) {
		$order_no = sanitize_text_field( $req->get_param( 'order_no' ) );
		$order = ( new MyP_Core_Database_OrderModel() )->get_by_no( $order_no );
		if ( $order && 'pending' === $order->status ) {
			( new MyP_Core_Database_OrderModel() )->mark_paid( $order->id, 'demo' );
		}
		return myp_response( 0, '支付成功' );
	}
}
