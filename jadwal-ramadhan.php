<?php
/**
 * Plugin Name: Jadwal Ramadhan
 * Plugin URI: https://github.com/nurulishlah/jadwal-ramadhan
 * Description: Plugin untuk mengelola dan menampilkan jadwal kegiatan Ramadhan (Imam, Penceramah, dll).
 * Version: 1.0.0
 * Author: Muhamad Ishlah
 * Author URI: https://github.com/nurulishlah
 * Text Domain: jadwal-ramadhan
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'JADWAL_RAMADHAN_PATH', plugin_dir_path( __FILE__ ) );
define( 'JADWAL_RAMADHAN_URL', plugin_dir_url( __FILE__ ) );

// Include CPTs
require_once JADWAL_RAMADHAN_PATH . 'includes/cpt-tokoh.php';
require_once JADWAL_RAMADHAN_PATH . 'includes/cpt-jadwal-ramadhan.php';

// Include View Functions (Shared Logic)
require_once JADWAL_RAMADHAN_PATH . 'includes/functions-view.php';

// Include Block
require_once JADWAL_RAMADHAN_PATH . 'includes/block-jadwal.php';

// Include Admin Import
require_once JADWAL_RAMADHAN_PATH . 'includes/admin-import.php';

/**
 * Load Archive Template
 */
function jadwal_ramadhan_template_include( $template ) {
    if ( is_post_type_archive( 'jadwal_ramadhan' ) ) {
        $new_template = JADWAL_RAMADHAN_PATH . 'templates/archive-jadwal_ramadhan.php';
        if ( file_exists( $new_template ) ) {
            return $new_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'jadwal_ramadhan_template_include' );

/**
 * Enqueue Assets
 */
function jadwal_ramadhan_enqueue_scripts() {
    // Enqueue Custom Styles
    wp_enqueue_style( 'jadwal-ramadhan-style', JADWAL_RAMADHAN_URL . 'assets/css/style.css', array(), '1.0.0' );
    wp_enqueue_script( 'jadwal-ramadhan-tabs', JADWAL_RAMADHAN_URL . 'assets/js/tabs.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'jadwal_ramadhan_enqueue_scripts' );
