<?php
/*
Plugin Name: Last Modified Dashboard Widget
Author: Takayuki Miyauchi
Plugin URI: http://wpist.me/
Description: Display Posts and Pages on the dashoboard in order of descending of modified..
Version: 0.1.0
Author URI: http://wpist.me/
Domain Path: /languages
Text Domain: last-modified-dashboard-widget
*/

$last_modified_dashboard_widget = new Last_Modified_Dashboard_Widget();
$last_modified_dashboard_widget->register();


class Last_Modified_Dashboard_Widget {

function __construct()
{

}

public function register()
{
    add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
}

public function wp_dashboard_setup()
{
    wp_add_dashboard_widget(
        'last-modified',
        'Last Modified',
        array($this, 'callback')
    );
}

public function callback()
{
    $args = array(
        'post_type' => 'any',
        'post_status' => 'any',
        'orderby' => 'modified',
        'order' => 'DESC',
        'posts_per_page' => 10,
    );

    $posts = get_posts($args);

    echo '<ul>';
    foreach ($posts as $p) {
        echo '<li><h4>';
        printf (
            '<a href="%1$s">%2$s</a>',
            get_edit_post_link($p->ID, false),
            strip_tags($p->post_title)
        );
        printf(
            '<span style="%1$s">%2$s by %3$s at %4$s</span>',
            'color:#999;font-size:12px;margin-left:3px;"',
            ucfirst($p->post_status),
            esc_html($this->get_modified_author($p->ID)),
            $p->post_modified
        );
        echo '</h4></li>';
    }
    echo '</ul>';
}

public function get_modified_author($post_id) {
    if ( $last_id = get_post_meta($post_id, '_edit_last', true)) {
        $last_user = get_userdata($last_id);
        return $last_user->display_name;
    }
}

} // end class

// EOF
