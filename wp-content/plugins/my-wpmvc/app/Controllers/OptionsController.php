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
	 * Save or update cities_for_delivery option
	 *
	 * @return
	 * @since 1.0.0
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

	/**
	 * Delete cities_for_delivery option by key
	 *
	 * @return
	 * @since 1.0.0
	 */
	public function delete() {
		$key                 = Request::input( 'delete_option_city', 0, true, 'intval' );
		$cities_for_delivery = get_option( 'cities_for_delivery', [] );

		if ( $key >= 0 && array_key_exists( $key, $cities_for_delivery ) ) {
			unset( $cities_for_delivery[ $key ] );
			if ( empty( $cities_for_delivery ) ) {
				if ( ! delete_option( 'cities_for_delivery' ) ) {
					$GLOBALS['message'] = 'Удаление настроек вызвало ошибку. Настройки удалить не удалось!';
				}
			} else {
				update_option( 'cities_for_delivery', $cities_for_delivery );
			}
		} else {
			$GLOBALS['message'] = 'Ошибка!';
		}
	}
}