<?php

namespace MyWpmvc\Models;

use WPMVC\MVC\Traits\FindTrait;
use WPMVC\MVC\Models\UserModel as Model;

/**
 * User model.
 * WordPress MVC model.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
class User extends Model
{
    use FindTrait;
}