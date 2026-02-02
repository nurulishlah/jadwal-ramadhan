<?php
/**
 * Template Name: Jadwal Ramadhan Archive
 * Description: Custom archive template for Jadwal Ramadhan CPT.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); 
?>

<div id="primary" class="content-area w-full max-w-7xl mx-auto">
    <main id="main" class="site-main">
        <?php
        if ( function_exists( 'jadwal_ramadhan_get_dashboard_html' ) ) {
            echo jadwal_ramadhan_get_dashboard_html();
        } else {
            echo '<p>Plugin error: View function missing.</p>';
        }
        ?>
    </main>
</div>

<?php 
get_footer();
