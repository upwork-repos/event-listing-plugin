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
define( 'EVENTLISTING_NAME',			'Event Listing Plugin' );
// Plugin version
define( 'EVENTLISTING_VERSION',		'1.0.1' );
// Plugin Root File
define( 'EVENTLISTING_PLUGIN_FILE',	__FILE__ );
// Plugin base
define( 'EVENTLISTING_PLUGIN_BASE',	plugin_basename( EVENTLISTING_PLUGIN_FILE ) );
// Plugin Folder Path
define( 'EVENTLISTING_PLUGIN_DIR',	plugin_dir_path( EVENTLISTING_PLUGIN_FILE ) );
// Plugin Folder URL
define( 'EVENTLISTING_PLUGIN_URL',	plugin_dir_url( EVENTLISTING_PLUGIN_FILE ) );





function evntlst_register_posttype(){
    $labels = array(
        'name'                  => 'Events',
        'singular_name'         => 'Event'
    );
    $supports = ['title', 'thumbnail','editor', 'excerpt'];
    $name = 'Events';
    $singular_name = 'Event';
    $taxonomies = [];
    $id = "kbr_event";
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
        'kbr_event',

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
        'url', 'location', 'date'
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
