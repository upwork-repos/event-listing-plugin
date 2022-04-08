<?php
/**
 * Event Listing
 *
 * @package       EVENTLISTING
 * @author        Kibru Demeke
 * @version       0.0.1
 *
 * @wordpress-plugin
 * Plugin Name:   Event Listing
 * Plugin URI:    https://dkibru.github.io/
 * Description:   An event listing plugin for a code challenge
 * Version:       0.0.1
 * Author:        Kibru Demeke
 * Author URI:    https://dkibru.github.io/
 * Text Domain:   event-listing
 * Domain Path:   /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'EVENTLISTING',            'EVENTLISTING' );
define( 'EVENTLISTING_ID',            'kbr_event' );
define( 'EVENTLISTING_NAME',			'Event Listing Plugin' );
define( 'EVENTLISTING_VERSION',		'1.0.1' );






/*
* 
* Register custom post type and metabox
* 
*/
function evntlst_register_posttype(){
    $labels = array(
        'name'                  => 'Events',
        'singular_name'         => 'Event'
    );
    $supports = ['title', 'thumbnail','editor', 'excerpt'];
    $name = 'Events';
    $singular_name = 'Event';
    $taxonomies = [];
    $id = EVENTLISTING_ID;
    $args = array(
        'label'                 => $singular_name,
        'description'           => $name,
        'labels'                => $labels,
        'supports'              => $supports,
        'taxonomies'            => $taxonomies,
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        // 'menu_icon'             => '',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( $id, $args );
}

function evntlst_metabox_show($post){
    include( 'views/metabox.php' );
}

function evntlst_metabox($post_id){
    add_meta_box(
        'additional_fields',
        'Additional Fields', 
        'evntlst_metabox_show',
        EVENTLISTING_ID,
    );  
}

function evntlst_verify_nonce()
{
    if(!isset($_POST[EVENTLISTING.'_nonce'])) 
        return false;
    $nonce_name = $_POST[EVENTLISTING.'_nonce'];
    $nonce_action = EVENTLISTING.'_nonce_action';

    return wp_verify_nonce($nonce_name,$nonce_action);
}

function evntlst_save_metabox($post_id){

    if(!evntlst_verify_nonce()) 
        return;
    $fields = [
        'url', 'location', 'date', 'location_name'
    ];


    foreach ($fields as $f) {
        if ( isset( $_POST[$f] ) ) {
            update_post_meta( $post_id, $f, sanitize_text_field( $_POST[$f] ) );
        }
    }
}

add_action( 'init', 'evntlst_register_posttype' );
add_action( 'add_meta_boxes',  'evntlst_metabox' );
add_action( 'save_post', 'evntlst_save_metabox' , 10 , 3 );







/*
* 
* Display custom values on the table
* 
*/

function evntlst_set_custom_columns($columns)
{
    unset( $columns['author'] );
    $columns['title'] = __( 'Title', 'your_text_domain' );
    $columns['location'] = __( 'Location', 'your_text_domain' );
    $columns['event_date'] = __( 'Event Date', 'your_text_domain' );
    $columns['link'] = __( 'Link', 'your_text_domain' );

    return $columns;
}

function evntlst_custom_event_column($column, $post_id ){
    $location = get_post_meta( $post_id, 'location_name', true );
    $date = get_post_meta( $post_id, 'date', true );
    $timestamp = strtotime($date);
    $excerpt = get_the_excerpt($post_id);
    $title = get_the_title($post_id);


    switch ( $column ) {

        case 'location' :
            echo $location;
            break;
        case 'event_date' :
            echo $date;
            break;
        case 'link' :
            $url = get_post_meta( $post_id, 'url', true );
            echo "<a class='button action' href='$url' title='Go to the external site' target='_blank'>Go to the event site</a> <br>
                  <a class='button action' href='https://www.google.com/calendar/render?action=TEMPLATE&text=$title&details=$excerpt&location=$location&dates=$timestamp%2F$date'  target='_blank' >Add to Google Calendar</a>";
            break;

    }
}


add_filter( 'manage_'.EVENTLISTING_ID.'_posts_columns', 'evntlst_set_custom_columns' );
add_action( 'manage_'.EVENTLISTING_ID.'_posts_custom_column' , 'evntlst_custom_event_column', 10, 2 );



/*
* 
* Display custom values on the table
* 
*/

add_filter('posts_join', 'evntlst_table_join' );
function evntlst_table_join($wp_join)
{
    if(is_post_type_archive(EVENTLISTING_ID) || (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == EVENTLISTING_ID)) {
        global $wpdb;
        $wp_join .= " LEFT JOIN (
                SELECT post_id, meta_value as event_date
                FROM $wpdb->postmeta
                WHERE meta_key =  'date' ) AS DD
                ON $wpdb->posts.ID = DD.post_id ";
    }
    return ($wp_join);
}


add_filter('posts_orderby', 'evntlst_table_order' );
function evntlst_table_order( $orderby )
{
    if(is_post_type_archive(EVENTLISTING_ID) || (is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == EVENTLISTING_ID)) {
            $orderby = 'event_date DESC';
    }
    return $orderby;
}

