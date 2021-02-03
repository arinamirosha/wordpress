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
	 * Save or update cities_for_delivery or addresses_for_pickup option
	 *
	 * @return
	 * @since 1.0.0
	 */
	public function save() {
		if ( isset( $_POST['city'] ) ) {
			$input    = 'city';
			$opt_name = 'cities_for_delivery';
		} elseif ( isset( $_POST['address'] ) ) {
			$input    = 'address';
			$opt_name = 'addresses_for_pickup';
		} else {
			$input    = '';
			$opt_name = '';
		}

		$option = Request::input( $input, 0, true, 'sanitize_text_field' );
		if ( ! $option || strlen( $option ) > 150 ) {
			$GLOBALS['message'] = 'Ошибка: пустое поле / длина > 150 символов';

			return;
		}

		$cities_addresses = get_option( $opt_name, [] );

		if ( empty( $cities_addresses ) ) {
			add_option( $opt_name, [ $option ] );
		} else {
			array_push( $cities_addresses, $option );
			update_option( $opt_name, $cities_addresses );
		}
	}

	/**
	 * Delete cities_for_delivery or addresses_for_pickup option by key
	 *
	 * @return
	 * @since 1.0.0
	 */
	public function delete() {

		if ( isset( $_POST['delete_option_city'] ) ) {
			$input    = 'delete_option_city';
			$opt_name = 'cities_for_delivery';
		} elseif ( isset( $_POST['delete_option_address'] ) ) {
			$input    = 'delete_option_address';
			$opt_name = 'addresses_for_pickup';
		} else {
			$input    = '';
			$opt_name = '';
		}

		$key              = Request::input( $input, 0, true, 'intval' );
		$cities_addresses = get_option( $opt_name, [] );

		if ( $key >= 0 && array_key_exists( $key, $cities_addresses ) ) {
			unset( $cities_addresses[ $key ] );
			if ( empty( $cities_addresses ) ) {
				if ( ! delete_option( $opt_name ) ) {
					$GLOBALS['message'] = 'Удаление настроек вызвало ошибку. Настройки удалить не удалось!';
				}
			} else {
				update_option( $opt_name, $cities_addresses );
			}
		} else {
			$GLOBALS['message'] = 'Ошибка!';
		}
	}
}