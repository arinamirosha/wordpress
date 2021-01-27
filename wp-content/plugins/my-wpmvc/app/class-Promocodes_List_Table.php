<?php

// расширять класс нужно после или во время admin_init
// класс удобнее поместить в отдельный файл.

use MyWpmvc\Controllers\PromocodesController;
use MyWpmvc\Models\Promocode;

class Promocodes_List_Table extends WP_List_Table {

    function __construct(){
        parent::__construct(array(
            'singular' => 'promocode',
            'plural'   => 'promocodes',
            'ajax'     => false,
        ));

        // screen option
        add_screen_option( 'per_page', array(
            'label'   => 'Показывать на странице',
            'default' => 20,
            'option'  => 'promocodes_per_page',
        ) );

        $this->bulk_action_handler();
        $this->prepare_items();

        add_action( 'wp_print_scripts', [ __CLASS__, '_list_table_css' ] );
    }

    // создает элементы таблицы
    function prepare_items(){
        global $wpdb;

        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $builder = wp_query_builder();
        $orderby = ! empty( $_GET['orderby'] ) ? $_GET['orderby'] : 'ID';
        $order   = ! empty($_GET['order'] ) ? $_GET['order'] : 'desc';
        $s       = ! empty($_POST['s'] ) ? $_POST['s'] : '';

        $this->items = $builder->select( '*' )
                               ->from( 'posts' )
                               ->where( [
                                   'post_status' => 'publish',
                                   'post_type' => 'promocode',
                                   'post_title' => [
                                       'operator' => 'LIKE',
                                       'value'    => '%' . $s . '%',
                                   ],
                               ] )
                               ->order_by($orderby, $order)
                               ->get( ARRAY_A, function ( $row ) {
                                   return new Promocode( $row );
                               } );

        // пагинация
        $per_page = $this->get_items_per_page('promocodes_per_page');
        $cur_page = $this->get_pagenum();
        $offset   = ($cur_page-1) * $per_page;

        $this->set_pagination_args( array(
            'total_items' => count($this->items),
            'per_page'    => $per_page,
        ) );

        $this->items = array_slice( $this->items, $offset, $per_page );
    }

    // колонки таблицы
    function get_columns(){
        return array(
            'cb'    => '<input type="checkbox" />',
            'ID'    => 'ID',
            'title' => 'Название',
            'discount' => 'Скидка',
            'type_discount' => 'Тип скидки',
            'number_of_uses' => 'Количество использований',
            'date' => 'Дата публикации',
        );
    }

    // сортируемые колонки
    function get_sortable_columns(){
        return array(
            'ID' => array( 'ID', false ),
            'date' => array( 'post_date', false ),
        );
    }

    protected function get_bulk_actions() {
        return array(
            'delete' => 'Удалить',
        );
    }

//     Элементы управления таблицей. Расположены между групповыми действиями и панагией.
//    function extra_tablenav( $which ) {
//        echo '<div class="alignleft actions">Hello world</div>';
//    }

    // вывод каждой ячейки таблицы -------------

    static function _list_table_css() {
        ?>
        <style>
            table.promocodes .column-cb{ width:1%; }
            table.promocodes .column-ID{ width:2%; }
            table.promocodes .column-title{ width:10%; }
            table.promocodes .column-discount{ width:10%; }
            table.promocodes .column-type_discount{ width:10%; }
            table.promocodes .column-number_of_uses{ width:10%; }
            table.promocodes .column-date{ width:10%; }
        </style>
        <?php
    }

    // вывод каждой ячейки таблицы...
    function column_default( $item, $colname ) {
        switch ($colname) {
            case 'title':
                $actions = array();

//                $actions['edit'] = sprintf( '<a href="%s">%s</a>', '#',  'Изменить');
//                $actions['edit'] = sprintf('<a href="?page=%s&action=%s&promocode=%s">Изменить</a>',$_REQUEST['page'], 'edit', $item->ID);

                $actions['delete'] = sprintf( '<a href="%s">%s</a>', get_delete_post_link( $item->ID, '', true  ), 'Удалить' );

                $title = $item->title ? $item->title : 'Untitled';
                return  $title . $this->row_actions( $actions );
            case 'type_discount':
                $t_discount = $item->type_discount;
                if ( $t_discount == Promocode::PERCENT ) {
                    return '%';
                } elseif ( $t_discount == Promocode::MONEY ) {
                    return 'руб.';
                } else {
                    return '';
                }
            default:
                return $item->$colname ? $item->$colname : '';
        }
    }

    // заполнение колонки cb
    function column_cb( $item ) {
        echo '<input type="checkbox" name="promids[]" id="cb-select-'. $item->ID .'" value="'. $item->ID .'" />';
    }

    // helpers -------------

    private function bulk_action_handler(){
        if( empty($_POST['promids']) || empty($_POST['_wpnonce']) ) return;

        if ( ! $action = $this->current_action() ) return;

        if( ! wp_verify_nonce( $_POST['_wpnonce'], 'bulk-' . $this->_args['plural'] ) )
            wp_die('nonce error');

        // делает что-то...
        if ( $action == 'delete') {
            PromocodesController::delete();
        }
    }

}