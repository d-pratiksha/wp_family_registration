<?php
class Family_Member_CPT {
    // Initialize the custom post type
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_family_member_cpt' ] );
    }

    // Register the custom post type
    public static function register_family_member_cpt() {
        register_post_type( 'family_member', [
            'label' => __( 'Family Member', 'custom-registration-extension' ),
            'public' => false, // Not publicly visible on the front-end
            'show_ui' => true, // Show in WordPress admin dashboard
            'supports' => [ 'title' ], // Supports only the title field
            'has_archive' => false, // No archive page
            'capability_type' => 'post', // Uses the default 'post' capabilities
            'show_in_menu' => 'hivepress', // Show in HivePress menu
            'menu_icon' => 'dashicons-groups', // Custom icon
        ]);
    }
}
