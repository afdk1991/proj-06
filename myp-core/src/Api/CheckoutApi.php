<?php
/**
 * 结算与订单 API。
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MyP_Core_Api_CheckoutApi {

	public static function register_routes() {
		register_rest_route( MYP_REST_NAMESPACE, '/checkout', array(
			'methods' => 'POST',
			'callback' => array( __CLASS__, 'checkout' ),
			'permission_callback' => function () {
				return is_user_logged_in() ? true : new WP_Error( 'login', '请登录', array( 'status' => 401 ) );
			},
		) );
		register_rest_route( MYP_REST_NAMESPACE, '/orders', array(
			'methods' => 'GET',
			'callback' => array( __CLASS__, 'orders' ),
			'permission_callback' => function () { return is_user_logged_in(); },
		) );
	}

	public static function checkout( $req ) {
		$user_id = get_current_user_id();
		$items   = $req->get_param( 'items' );
		if ( ! is_array( $items ) || ! $items ) {
			return myp_response( 400, '购物车为空' );
		}
		$model = new MyP_Core_Database_OrderModel();
		$order_id = $model->create( $user_id, $items, array(
			'consignee' => $req->get_param( 'consignee' ),
			'mobile'    => $req->get_param( 'mobile' ),
			'address'   => $req->get_param( 'address' ),
			'remark'    => $req->get_param( 'remark' ),
		) );
		if ( is_wp_error( $order_id ) ) {
			return myp_response( 500, $order_id->get_error_message() );
		}
		return myp_response( 0, '下单成功', array( 'order_id' => $order_id ) );
	}

	public static function orders() {
		$list = ( new MyP_Core_Database_OrderModel() )->user_orders( get_current_user_id() );
		return myp_response( 0, 'ok', $list );
	}
}
