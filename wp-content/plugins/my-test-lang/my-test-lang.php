<?php
/**
 * Plugin Name: My test lang
 * Text Domain: myl10n
 * Domain Path: /lang
 */

// строки для перевода заголовков плагина, чтобы они попали в .po файл.
__( 'Demo WordPress translation', 'myl10n' );
__( 'Test plugin for learning how to create translations in WordPress', 'myl10n' );

// подключение файла перевода
// здесь .mo файл должен лежать в папке /lang, которая находится в папке текущего файла
// файл должен назваиться "$domain-локаль": myl10n-ru_RU.mo
add_action( 'plugins_loaded', function(){
    load_plugin_textdomain( 'myl10n', false, dirname( plugin_basename(__FILE__) ) . '/lang' );
} );

// страница админки
add_action( 'admin_menu', function(){

    add_options_page( __('Demo translation','myl10n'), __('Demo translation','myl10n'), 'manage_options', 'myl10n_plugin', function(){

        _nx_noop( '%s noop star','%s noop stars','Контекст _nx_noop','myl10n' );
        _n_noop( '%s noop star','%s noop stars','myl10n' );

        ?>
        <div class="wrap">
            <h2><?php echo get_admin_page_title() ?></h2>
        </div>

        <h3><?= __( 'Different variants of translation in WordPress.','myl10n') ?></h3>
        <p class="description"><?= __( 'WordPress translation functions.','myl10n') ?></p>

        <p>_e() — <?php _e( 'Some translation text.','myl10n' ); ?></p>

        <p>_ex() — <?php _ex( 'Some translation text.','Фраза контекста _ex','myl10n' ); ?></p>

        <p>_x() — <?php echo _x( 'Some translation text.','Контенкст echo _x','myl10n' ); ?></p>

        <p>_n(1) — <?php printf( _n( '%s star','%s stars', 1, 'myl10n' ), 1 ); ?></p>
        <p>_n(3) — <?php printf( _n( '%s star','%s stars', 3, 'myl10n' ), 3 ); ?></p>
        <p>_n(10) — <?php printf( _n( '%s star','%s stars', 10, 'myl10n' ), 10 ); ?></p>

        <p>_nx(1) — <?php printf( _nx( '%s star','%s stars', 1, 'Фраза контекста для множественного числа _nx','myl10n' ), 1 ); ?></p>
        <p>_nx(3) — <?php printf( _nx( '%s star','%s stars', 3, 'Фраза контекста для множественного числа _nx','myl10n' ), 3 ); ?></p>
        <p>_nx(10) — <?php printf( _nx( '%s star','%s stars', 10, 'Фраза контекста для множественного числа _nx','myl10n' ), 10 ); ?></p>

        <p>esc_attr__() — <?php echo esc_attr__('string 1','myl10n') ?></p>
        <p>esc_attr_e() — <?php esc_attr_e('string 2','myl10n') ?></p>
        <p>esc_html__() — <?php echo esc_html__('string 3','myl10n') ?></p>
        <p>esc_html_e() — <?php esc_html_e('string 4','myl10n') ?></p>

        <?php

    } );

} );