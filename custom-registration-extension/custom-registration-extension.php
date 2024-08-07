<?php
/**
 * Plugin Name: Custom Registration Extension for HivePress
 * Description: A custom extension for family member registration and program enrollment in HivePress.
 * Version: 1.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Include necessary files
require_once plugin_dir_path( __FILE__ ) . 'includes/class-family-member-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-enrollment.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';

// Initialize the custom post types and other functionalities
Family_Member_CPT::init();
Enrollment::init();

function create_family_member_post_type() {
    $labels = array(
        'name'               => 'Family Members',
        'singular_name'      => 'Family Member',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Family Member',
        'edit_item'          => 'Edit Family Member',
        'new_item'           => 'New Family Member',
        'view_item'          => 'View Family Member',
        'search_items'       => 'Search Family Members',
        'not_found'          => 'No family members found',
        'not_found_in_trash' => 'No family members found in Trash',
        'menu_name'          => 'Family Members',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'family_member'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array('title', 'editor', 'thumbnail'),
    );

    register_post_type('family_member', $args);
}
add_action('init', 'create_family_member_post_type');
