<?php
/**
 * Roots includes
 */
require_once locate_template('/lib/utils.php');           // Utility functions
require_once locate_template('/lib/init.php');            // Initial theme setup and constants
require_once locate_template('/lib/wrapper.php');         // Theme wrapper class
require_once locate_template('/lib/sidebar.php');         // Sidebar class
require_once locate_template('/lib/config.php');          // Configuration
require_once locate_template('/lib/activation.php');      // Theme activation
require_once locate_template('/lib/titles.php');          // Page titles
require_once locate_template('/lib/cleanup.php');         // Cleanup
require_once locate_template('/lib/nav.php');             // Custom nav modifications
require_once locate_template('/lib/gallery.php');         // Custom [gallery] modifications
require_once locate_template('/lib/comments.php');        // Custom comments modifications
require_once locate_template('/lib/relative-urls.php');   // Root relative URLs
require_once locate_template('/lib/widgets.php');         // Sidebars and widgets
require_once locate_template('/lib/scripts.php');         // Scripts and stylesheets
require_once locate_template('/lib/custom.php');          // Custom functions

//
// Ignore above
//

add_action( 'init',             'register_post_types' );
add_action( 'add_meta_boxes',   'register_post_type_meta_boxes' );
add_action( 'save_post',        'save_fixture_meta_box' );

function register_post_types() {

    $postTypes = array(
        'fixture',
        'team',
        'location',

    );
    
    foreach( $postTypes as $type ) {

        $labels = array (
            'name'               => ucfirst( $type ) . 's',
            'singular_name'      => ucfirst( $type ),
            'menu_name'          => ucfirst( $type ) . 's',
            'name_admin_bar'     => ucfirst( $type ) . 's',
            'add_new'            => 'Add new ' . ucfirst( $type ),
            'add_new_item'       => 'Add New ' . ucfirst( $type ),
            'new_item'           => 'New ' . ucfirst( $type ),
            'edit_item'          => 'Edit ' . ucfirst( $type ),
            'view_item'          => 'View ' . ucfirst( $type ),
            'all_items'          => 'All ' . ucfirst( $type ) . 's',
            'search_items'       => 'Search ' . ucfirst( $type ) . 's',
            'parent_item_colon'  => 'Parent ' . ucfirst( $type ) . 's:',
            'not_found'          => 'No ' . ucfirst( $type ) . ' found.',
            'not_found_in_trash' => 'No ' . ucfirst( $type ) . ' found in Trash.',
        );
    
        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'book' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title' )
        );
    
        register_post_type( $type, $args);
    }
}

function register_post_type_meta_boxes() {
    add_meta_box( 'fixture-data', 'Fixture Information', 'renderMetaBox', 'fixture' );
    add_meta_box( 'team-data', 'Team Information', 'renderMetaBox', 'team' );
    add_meta_box( 'location-data', 'Location Information', 'renderMetaBox', 'location' );
}

function renderMetaBox( $post ){

    $data = get_post_custom( $post->ID );
    require_once( __DIR__ . DIRECTORY_SEPARATOR . 'admin-templates' . DIRECTORY_SEPARATOR . $post->post_type . '-metabox.php' );
}

function save_fixture_meta_box( $post_id ) {

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    foreach( $_POST as $key => $val ) {
        if( strpos( $key, 'pjn_' ) !== false ) {
            update_post_meta( $post_id, $key, $val );
        }
    }
}