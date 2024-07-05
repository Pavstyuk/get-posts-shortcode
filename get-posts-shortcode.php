<?php
/*
Plugin Name: Plugin Get Posts by Shortcode 
Plugin URI: https://github.com/pavstyuk/get-posts-shortcode
Description: Plugin to Get Posts by Shortcode with parameters "count", "category", "orderby", "order". PGP it is abbr of Plugin Get Posts
Version: 0.2
Author: Mikhail Pavstyuk
Author URI: https://pavstyuk.ru/
*/

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

// define('PGP_DIR', plugin_dir_path(__FILE__));

function pgp_get_posts_function($atts = [])
{
    do_action('qm/start', 'funtime');

    $atts = array_change_key_case((array) $atts, CASE_LOWER);
    $atts = shortcode_atts([
        'count' => 10,
        'category'  => '0',
        'orderby' => 'date',
        'order' => 'ASC'
    ], $atts);

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $atts['count'],
        'cat' => $atts["category"],
        'orderby' => $atts["orderby"],
        'order' => $atts["order"]
    );

    $posts = get_posts($args);

    if (count($posts) > 0) {
        foreach ($posts as $post) {
            setup_postdata($post);
            $show_post = "<article class='post-$post->ID post' id='post-$post->ID'>" .
                "<div><a href='" . get_the_permalink($post->ID) . "'><img src='" . get_the_post_thumbnail_url($post->ID, 'large') . "' /></a></div>" .
                "<a href='" . get_the_permalink($post->ID) . "'><h3 class='entry-title'>$post->post_title</h3></a>" .
                "<p class='entry-content'>$post->post_content</p>" .
                '</article>';
            echo $show_post;
        }
    } else {
        echo 'No Posts';
    }
    wp_reset_postdata();

    do_action('qm/stop', 'funtime');
    do_action('qm/debug', 'Ready');
}

add_shortcode('get_posts_shortcode', 'pgp_get_posts_function');



// Add Page To Admin Panel

add_action('admin_menu', function () {
    add_menu_page('Plugin Get Posts by Shortcode', 'Post Shortcode', 'manage_options', 'site-options', 'pgp_get_posts_setting_page', '', 90);
});

function pgp_get_posts_setting_page()
{
?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>
        <h3>How to use Get Posts by Shortcode</h3>
        <p>
            You can use default parameters:
        </p>
        <pre>
        [get_posts_shortcode]
        </pre>

        <p>
            Or use some parameters:<br>
            <code>count</code> - count of posts, default count=10<br>
            <code>category</code> - id's of categories separate by comma, default category="0" (any)<br>
            <code>orderby</code> - order by name or date of post, default orderby="date"<br>
            <code>order</code> - use ASC or DESC order, default order="ASC"<br>
        </p>

        <pre>

        [get_posts_shortcode count="8" category="2" orderby="date" order="DESC"]<br>
        [get_posts_shortcode count="15" category="3,4" orderby="name" order="ASC"]
        </pre>

        <h4>Developed just fo fun by Mikhail Pavstyuk</h4>
        <a href="https://pavstyuk.ru/">https://pavstyuk.ru/</a>
    </div>
<?php

}
