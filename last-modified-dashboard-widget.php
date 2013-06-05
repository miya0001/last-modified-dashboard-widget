<?php
/*
Plugin Name: Recent Updates Dashboard Widget
Author: Takayuki Miyauchi
Plugin URI: http://wpist.me/
Description: Display Posts and Pages on the dashboard in order of descending of modified.
Version: 0.2.0
Author URI: http://wpist.me/
Domain Path: /languages
Text Domain: last-modified-dashboard-widget
*/

$last_modified_dashboard_widget = new Last_Modified_Dashboard_Widget();
$last_modified_dashboard_widget->register();


class Last_Modified_Dashboard_Widget {

public function register()
{
    add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
}

public function wp_dashboard_setup()
{
    wp_add_dashboard_widget(
        'last-modified',
        'Recent Updates',
        array($this, 'callback')
    );
}

public function callback()
{
    $args = array(
        'post_type' => 'any',
        //'post_status' => array('publish', 'pending', 'draft', 'future', 'private', 'inherit', 'trash'),
        'post_status' => array('publish', 'pending', 'draft', 'future', 'private'),
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
            esc_html($this->get_modified_author($p)),
            $p->post_modified
        );
        echo '</h4></li>';
    }
    echo '</ul>';
}

public function get_modified_author($post) {
    $last_id = get_post_meta($post->ID, '_edit_last', true);
    if (!$last_id) {
        $last_id = $post->post_author;
    }
    $last_user = get_userdata($last_id);
    return $last_user->display_name;
}

} // end class

// EOF
