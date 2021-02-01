<?php
/**
 * shortcodes.orders view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */

use MyWpmvc\Controllers\OrderController;

if ( isset( $_POST ) && ! empty( $_POST ) && isset($_POST['delete'])) {
    OrderController::delete();
}

OrderController::index();
?>
