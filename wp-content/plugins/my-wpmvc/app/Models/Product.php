<?php

namespace MyWpmvc\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\PostModel as Model;

/**
 * Product model.
 * WordPress MVC model.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class Product extends Model {
	use FindTrait;

	/**
	 * Aliases.
	 * Mappaed against post attributes, meta data or functions.
	 * @var array
	 */
	protected $aliases = [
		'title' => 'post_title',
		'price' => 'meta_price',
	];
}