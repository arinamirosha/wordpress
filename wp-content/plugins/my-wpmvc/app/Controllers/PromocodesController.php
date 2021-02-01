<?php

namespace MyWpmvc\Controllers;

use MyWpmvc\Models\Promocode;
use WPMVC\MVC\Controllers\ModelController as Controller;
use WPMVC\Request;
use WPMVC\Response;

/**
 * PromocodesController
 * WordPress MVC automated model controller.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class PromocodesController extends Controller {
	/**
	 * Property model.
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $model = 'MyWpmvc\\Models\\Promocode';

	public function delete() {
		$promids = Request::input( 'promids' );
		foreach ( $promids as $promid ) {
			Promocode::find( $promid )->delete();
		}
	}

	public function save() {
		$request_data = Request::all( 'sanitize_text_field' );
		$form_errors  = sanitize_promocode_request( $request_data );

		if ( ! empty( $form_errors ) ) {
			$GLOBALS['form_errors'] = $form_errors;
		} else {
			$promocode                 = new Promocode();
			$promocode->title          = str_replace( ' ', '', $request_data['title'] );
			$promocode->discount       = $request_data['discount'];
			$promocode->type_discount  = $request_data['type_discount'];
			$promocode->number_of_uses = 0;
			$promocode->p_status       = 'publish';
			$promocode->save();
		}
	}

	/**
	 * Check promocode ajax
	 * @throws \Exception
	 */
	public function check_promocode() {
		$response        = new Response;
		$promocode_title = Request::input( 'promocode', 0, true, 'sanitize_text_field' );
		if ( $error = sanitize_string( $promocode_title, 50 ) ) {
			$response->error( 'promocode', $error );
			$response->json();
		} else {
			$builder   = wp_query_builder();
			$promocode = $builder->select( '*' )
			                     ->from( 'posts' )
			                     ->where( [
				                     'post_type'   => 'promocode',
				                     'post_status' => 'publish',
				                     'post_title'  => str_replace( ' ', '', $promocode_title ),
			                     ] )
			                     ->get( ARRAY_A, function ( $row ) {
				                     return new Promocode( $row );
			                     } )[0];

			$type    = $promocode->type_discount == Promocode::PERCENT ? '%' : 'руб.';
			$message = 'Скидка ' . $promocode->discount . ' ' . $type;

			if ( $promocode ) {
				$response->success = true;
				$response->message = $message;
				$response->data    = $promocode;
				$response->json();
			} else {
				$response->error( 'promocode', 'Не существует' );
				$response->json();
			}
		}
	}
}