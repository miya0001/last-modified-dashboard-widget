<?php
/*
Plugin Name: Last Modified Dashboard Widget
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
        echo '<a href="'.get_edit_post_link($p->ID, false).'">'.$p->post_title.'</a>';
        echo ' <span style="color:#999;font-size:12px;margin-left:3px;" class="rss-date">'.$p->post_modified.' by '.esc_html($this->get_the_modified_author($p->ID)).'</span>';
        echo '</h4></li>';
    }
    echo '</ul>';
}

public function get_the_modified_author($post_id) {
	if ( $last_id = get_post_meta($post_id, '_edit_last', true)) {
		$last_user = get_userdata($last_id);
		return $last_user->display_name;
	}
}

} // end class

// EOF
