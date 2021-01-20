<?php

class my_example_widget extends WP_Widget {

    public function __construct() {
        $widget_options = array(
            'classname' => 'my_widget',
            'description' => 'Это наш первый виджет',
        );
        parent::__construct( 'my_widget', 'My Widget', $widget_options );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        $blog_title = get_bloginfo( 'name' );
        $tagline = get_bloginfo( 'description' );

        echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; ?>
        <p><strong>Site Name:</strong> <?php echo $blog_title ?></p>
        <p><strong>Tagline:</strong> <?php echo $tagline ?></p>
        <?php
        global $wpdb;
        $last_product = $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE post_type = 'products' AND post_status = 'publish' ORDER BY id DESC" );
        ?>
        <p><strong>The last added product:</strong>
            <a href="<?php the_permalink($last_product->ID); ?>" rel="bookmark"><?php echo $last_product->post_title ?></a>
        </p>
        <?php echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p><?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        return $instance;
    }

}

function my_register_widget() {
    register_widget( 'my_example_widget' );
}
add_action( 'widgets_init', 'my_register_widget' );