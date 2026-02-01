<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Gutenberg Block
 */
function jadwal_ramadhan_register_block() {
    register_block_type( 'jadwal-ramadhan/dashboard', array(
        'api_version' => 3,
        'title'       => __( 'Jadwal Ramadhan Dashboard', 'jadwal-ramadhan' ),
        'category'    => 'widgets',
        'icon'        => 'calendar-alt',
        'description' => __( 'Displays the Ramadhan schedule dashboard.', 'jadwal-ramadhan' ),
        'keywords'    => array( 'jadwal', 'ramadhan', 'puasa' ),
        'supports'    => array(
            'align' => array( 'wide', 'full' ),
        ),
        'render_callback' => 'jadwal_ramadhan_render_block_callback',
    ) );
}
add_action( 'init', 'jadwal_ramadhan_register_block' );

/**
 * Render Callback
 */
function jadwal_ramadhan_render_block_callback( $attributes, $content ) {
    if ( function_exists( 'jadwal_ramadhan_get_dashboard_html' ) ) {
        return jadwal_ramadhan_get_dashboard_html();
    }
    return 'Error: View function not found.';
}
