<?php
/**
 * shopcart.show view.
 * WordPress MVC view.
 *
 * @author Arina
 * @package my-wpmvc
 * @version 1.0.0
 */
?>

<?php if ($shopcarts) : ?>

    <?php if ($GLOBALS['message']) echo $GLOBALS['message'] . '<hr />'; ?>

    <div class="row h5">
        <div class="col-sm-3">Наименование</div>
        <div class="col-sm-2">Количество</div>
        <div class="col-sm-2">Стоимость</div>
        <div class="col-sm-2">Всего</div>
    </div>

    <hr />

    <?php foreach ($shopcarts as $shopcart) { ?>
        <div class="row shopcart-item">
            <div class="col-sm-3"><a href="<?php the_permalink($shopcart->product_id); ?>"><?php echo $shopcart->product->title ?></a></div>
            <div class="col-sm-2">
                <form action="" method="post" id="quantity-form-<?php echo $shopcart->ID ?>">
                    <input type="hidden" name="shopcart_id" value="<?php echo $shopcart->ID ?>" readonly>
                    <input type="checkbox" class="hidden" name="change_qty" checked>
                    <input class="form-input--50" type="number" name="quantity" min="1" step="1" value="<?php echo $shopcart->quantity ?>"
                           onchange="submit_qty_form(<?php echo $shopcart->ID ?>)"
                           onkeypress="show_info()"> шт.
                </form>
            </div>
            <div class="col-sm-2"><?php echo $shopcart->product->price ?> руб.</div>
            <div class="col-sm-2"><span class="total-price"><?php echo $shopcart->total_price ?></span> руб.</div>
            <div class="col-sm-3">
                <form action="" method="post">
                    <input type="hidden" name="shopcart_id" value="<?php echo $shopcart->ID ?>" readonly>
                    <input type="checkbox" class="hidden" name="delete_cart_item" checked>
                    <button type="submit" class="del-button--no-style"><i class="fa fa-trash"></i></button>
                </form>
            </div>
        </div>
    <?php } ?>

    <span id="info" class="display-none">Нажмите Enter, чтобы применить</span>

    <hr />

    <form action="<?php echo admin_url("admin-ajax.php") ?>" method="post" id="promocode-form">
        <div class="row">
            <div class="col-sm-2"><label for="promocode">Промокод</label></div>
            <div class="col-sm-10">
                <input type="text" name="promocode" id="promocode">
                <input type="hidden" name="action" value="check_promocode">
                <button type="submit" id="submit-promocode">Применить</button>

                <span id="message"></span>
            </div>
        </div>
    </form>

    <hr />

    <div class="row h5">
        <div class="col-sm-5"></div>
        <div class="col-sm-2">Итого</div>
        <div class="col-sm-5"><span id="total"><?php echo $total ?></span> руб.</div>
    </div>

    <hr />

    <?php
    $form_errors = $GLOBALS['form_errors'];
	$first_name = get_user_meta( get_current_user_id(), 'first_name', true );
	$last_name  = get_user_meta( get_current_user_id(), 'last_name', true );
	?>

    <form action="" method="post">
        <div class="row">
            <div class="col-sm-2"><label for="first_name">Имя</label></div>
            <div class="col-sm-6">
                <input type="text" name="first_name" id="first_name" value="<?php echo $_POST['first_name'] ? $_POST['first_name'] : $first_name ?>">
                <?php if ( ! empty($form_errors) && $form_errors['first_name'] ) : ?>
                    <span class="danger">
                        <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                        <?php echo $form_errors['first_name'] ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><label for="last_name">Фамилия</label></div>
            <div class="col-sm-6">
                <input type="text" name="last_name" id="last_name" value="<?php echo $_POST['last_name'] ? $_POST['last_name'] : $last_name ?>">
                <?php if ( ! empty($form_errors) && $form_errors['last_name'] ) : ?>
                    <span class="danger">
                        <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                        <?php echo $form_errors['last_name'] ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"><label for="patronymic">Отчество</label></div>
            <div class="col-sm-6">
                <input type="text" name="patronymic" id="patronymic" value="<?php echo $_POST['patronymic'] ?>">
                <?php if ( ! empty($form_errors) && $form_errors['patronymic'] ) : ?>
                    <span class="danger">
                        <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                        <?php echo $form_errors['patronymic'] ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"><label for="phone_number">Тел. <i class="fa fa-phone"></i></label></div>
            <div class="col-sm-6">
                <input type="text" name="phone_number" id="phone_number" value="<?php echo $_POST['phone_number'] ?>">
                <?php if ( ! empty($form_errors) && $form_errors['phone_number'] ) : ?>
                    <span class="danger">
                        <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                        <?php echo $form_errors['phone_number'] ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><label for="pickup">Самовывоз</label></div>
            <div class="col-sm-7"><input type="checkbox" id="pickup" name="pickup" <?php if ( ! $_POST['checkout'] || $_POST['pickup'] ) echo 'checked' ?>></div>
        </div>

	    <?php $addresses_for_pickup = get_option( 'addresses_for_pickup', [] ); ?>
	    <?php if ( ! empty( $addresses_for_pickup ) ) : ?>
            <div class="row pickup">
                <div class="col-sm-2"><label for="address_key">Адрес</label></div>
                <div class="col-sm-6">
				    <?php if ( sizeof( $addresses_for_pickup ) == 1 ) : ?>
					    <?php echo array_shift( $addresses_for_pickup ); ?>
				    <?php else : ?>
                        <select name="address_key" id="address_key">
						    <?php foreach ( $addresses_for_pickup as $key => $address ) : ?>
                                <option value="<?php echo $key; ?>"
								    <?php if ( isset( $_POST['address_key'] ) && $_POST['address_key'] == $key ) echo ' selected'; ?>
                                ><?php echo $address; ?></option>
						    <?php endforeach; ?>
                        </select>
					    <?php if ( ! empty($form_errors) && $form_errors['address_key'] ) : ?>
                            <span class="danger">
                            <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                            <?php echo $form_errors['address_key'] ?>
                        </span>
					    <?php endif; ?>
				    <?php endif; ?>
                </div>
            </div>
	    <?php endif; ?>

        <div class="row pickup">
            <div class="col-sm-2"><strong>График</strong></div>
            <div class="col-sm-6"><?php echo esc_attr( get_option('schedule', '') ) ?></div>
        </div>

        <?php $cities_for_delivery = get_option( 'cities_for_delivery', [] ); ?>
        <?php if ( ! empty( $cities_for_delivery ) ) : ?>
        <div class="row delivery">
            <div class="col-sm-2"><label for="city_key">Город</label></div>
            <div class="col-sm-6">
	            <?php if ( sizeof( $cities_for_delivery ) == 1 ) : ?>
                    <?php echo array_shift( $cities_for_delivery ); ?>
                <?php else : ?>
                    <select name="city_key" id="city_key">
	                    <?php foreach ( $cities_for_delivery as $key => $city ) : ?>
                            <option value="<?php echo $key; ?>"
                                <?php if ( isset( $_POST['city_key'] ) && $_POST['city_key'] == $key ) echo ' selected'; ?>
                            ><?php echo $city; ?></option>
	                    <?php endforeach; ?>
                    </select>
		            <?php if ( ! empty($form_errors) && $form_errors['city_key'] ) : ?>
                        <span class="danger">
                            <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                            <?php echo $form_errors['city_key'] ?>
                        </span>
		            <?php endif; ?>
	            <?php endif; ?>
            </div>
        </div>
	    <?php endif; ?>

        <div class="row delivery">
            <div class="col-sm-2"><label for="address">Адрес</label></div>
            <div class="col-sm-6"><input type="text" name="address" id="address" value="<?php echo $_POST['address'] ?>">
                <?php if ( ! empty($form_errors) && $form_errors['address'] ) : ?>
                    <span class="danger">
                        <i class="fa fa-exclamation fa-lg" aria-hidden="true"></i>
                        <?php echo $form_errors['address'] ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="row delivery">
            <div class="col-sm-2"><strong>Доставка</strong></div>

            <?php
            $free_delivery_from = esc_attr( get_option('free_delivery_from', 0) );
            $delivery_price = esc_attr( get_option('delivery_price', 0) );
            ?>
            <span class="display-none" id="delivery-price"><?php echo $delivery_price ?></span>
            <span class="display-none" id="free-from"><?php echo $free_delivery_from ?></span>

            <?php if ( $delivery_price && ( (! $free_delivery_from) || ( $free_delivery_from && $total < $free_delivery_from )) ) : ?>

                <div class="col-sm-6"><?php echo $delivery_price ?> руб.
                    <?php if ( $free_delivery_from ) : ?>
                        (бесплатно от <?php echo $free_delivery_from ?> руб.)
                    <?php endif; ?>
                </div>

            <?php else : ?>

                <div class="col-sm-6" id="free">Бесплатно</div>

            <?php endif; ?>

        </div>

        <hr />

        <div class="row h5">
            <div class="col-sm-5"></div>
            <div class="col-sm-2">К оплате</div>
            <div class="col-sm-5"><span id="to-pay"></span> руб.</div>
        </div>

        <hr />

        <div class="row text-center">
            <input type="checkbox" class="hidden" name="checkout" checked>
            <span id="promocode-applied"></span>
            <div class="col-sm-12"><button type="submit">Оформить заказ</button></div>
        </div>
    </form>

<?php else : ?>

    <div class="row h3 text-center">
        Пусто
    </div>

<?php endif; ?>
