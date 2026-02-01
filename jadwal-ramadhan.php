<?php
/**
 * Plugin Name: Jadwal Ramadhan
 * Description: Plugin untuk mengelola dan menampilkan jadwal kegiatan Ramadhan (Imam, Penceramah, dll).
 * Version: 1.0.0
 * Author: Antigravity
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
    // Enqueue Tailwind via CDN (Scoped) - In real prod better to compile, but per request:
    wp_register_style( 'jadwal-tailwind', 'https://cdn.tailwindcss.com', array(), '3.0' ); // Note: CDN direct usage is limited in scope/config but requested. 
    // Actually, for scoping we might need a custom build or a wrapper. 
    // Since user asked for CDN and scoping, we will use a raw CSS file that imports or we just load it and wrap the HTML carefully.
    // For now, loading it globally or on specific pages.
    
    // Better approach for "Scoped" with CDN is hard without build step. 
    // We will load it and assume the user puts everything in .jadwal-wrapper. 
    // Tailwind CDN is script-based for JIT or CSS based. 
    // Let's use the script version for dev speed as requested, but queueing it as script? 
    // "Use Tailwind CSS (load via CDN for now...)"
    wp_enqueue_script( 'tailwind-cdn', 'https://cdn.tailwindcss.com', array(), '3.4.1', false );
    
    // Custom Configuration for Tailwind to work with .jadwal-wrapper prefix if possible? 
    // Actually, Tailwind CDN scans DOM. We can't easily scope it to a class without building. 
    // We will rely on unique class names + a wrapper and maybe some custom CSS in style.css to reset/protect.

    wp_enqueue_style( 'jadwal-ramadhan-style', JADWAL_RAMADHAN_URL . 'assets/css/style.css', array(), '1.0.0' );
    wp_enqueue_script( 'jadwal-ramadhan-tabs', JADWAL_RAMADHAN_URL . 'assets/js/tabs.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'jadwal_ramadhan_enqueue_scripts' );
