<div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>
    <form action="options.php" method="POST">
		<?php
		settings_fields( 'option_group' );
		do_settings_sections( 'my_wpmvc_page' );
		submit_button();
		?>
    </form>

	<?php
	if ( isset( $GLOBALS['message'] ) ) {
		echo '<strong>' . $GLOBALS['message'] . '</strong>';
	}
	?>

    <table class="form-table">
        <tr>
            <th>Города для доставки</th>
            <td>
				<?php $cities_for_delivery = get_option( 'cities_for_delivery', [] ); ?>
				<?php if ( ! empty( $cities_for_delivery ) ) : ?>

					<?php foreach ( $cities_for_delivery as $key => $city ) : ?>
                        <form action="" method="post">
							<?php echo $city ?>
                            <input type="hidden" name="delete_option_city" value="<?php echo $key; ?>">
                            <button type="submit" class="del-button--no-style bg-admin hover-underline danger">
                                Удалить
                            </button>
                        </form>
					<?php endforeach; ?>

				<?php else : ?>
                    Пусто
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><label for="city">Город </label></th>
            <td>
                <form action="" method="post">
                    <input type="text" id="city" name="city">
                    <button class="button" type="submit">Добавить</button>
                </form>
            </td>
        </tr>
    </table>

    <table class="form-table">
        <tr>
            <th>Адреса для самовывоза</th>
            <td>
				<?php $addresses_for_pickup = get_option( 'addresses_for_pickup', [] ); ?>
				<?php if ( ! empty( $addresses_for_pickup ) ) : ?>

					<?php foreach ( $addresses_for_pickup as $key => $address ) : ?>
                        <form action="" method="post">
							<?php echo $address ?>
                            <input type="hidden" name="delete_option_address" value="<?php echo $key; ?>">
                            <button type="submit" class="del-button--no-style bg-admin hover-underline danger">
                                Удалить
                            </button>
                        </form>
					<?php endforeach; ?>

				<?php else : ?>
                    Пусто
				<?php endif; ?>
            </td>
        </tr>
        <tr>
            <th><label for="address">Адрес (полностью)</label></th>
            <td>
                <form action="" method="post">
                    <input type="text" id="address" name="address">
                    <button class="button" type="submit">Добавить</button>
                </form>
            </td>
        </tr>
    </table>

    <p>
        Шорткод <strong>[shop_cart_add_button]</strong> - кнопка добавления в корзину (на странице товара)<br/>
        Шорткод <strong>[shop_cart]</strong> - корзина (на отдельной странице)
    </p>
    <p>
        Для типа постов "<strong>products</strong>" с мета-полем "<strong>price</strong>"
    </p>

</div>