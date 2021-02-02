<?php

namespace MyWpmvc\Controllers;

use WPMVC\MVC\Controller;
use WPMVC\Request;

/**
 * OptionsController
 * WordPress MVC controller.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class OptionsController extends Controller {
	/**
	 * @return
	 * @since 1.0.0
	 *
	 *
	 */
	public function save() {
		$option = Request::input( 'city', 0, true, 'sanitize_text_field' );
		if ( ! $option || strlen( $option ) > 150 ) {
			$GLOBALS['message'] = 'Ошибка: пустое поле / длина > 150 символов';

			return;
		}

		$cities_for_delivery = get_option( 'cities_for_delivery', [] );

		if ( empty( $cities_for_delivery ) ) {
			add_option( 'cities_for_delivery', [ $option ] );
		} else {
			array_push( $cities_for_delivery, $option );
			update_option( 'cities_for_delivery', $cities_for_delivery );
		}
	}
}