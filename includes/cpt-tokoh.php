<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register CPT Tokoh
 */
function jadwal_ramadhan_register_tokoh() {
	$labels = array(
		'name'                  => _x( 'Tokoh', 'Post Type General Name', 'jadwal-ramadhan' ),
		'singular_name'         => _x( 'Tokoh', 'Post Type Singular Name', 'jadwal-ramadhan' ),
		'menu_name'             => __( 'Tokoh', 'jadwal-ramadhan' ),
		'name_admin_bar'        => __( 'Tokoh', 'jadwal-ramadhan' ),
		'archives'              => __( 'Tokoh Archives', 'jadwal-ramadhan' ),
		'attributes'            => __( 'Tokoh Attributes', 'jadwal-ramadhan' ),
		'parent_item_colon'     => __( 'Parent Tokoh:', 'jadwal-ramadhan' ),
		'all_items'             => __( 'All Tokoh', 'jadwal-ramadhan' ),
		'add_new_item'          => __( 'Add New Tokoh', 'jadwal-ramadhan' ),
		'add_new'               => __( 'Add New', 'jadwal-ramadhan' ),
		'new_item'              => __( 'New Tokoh', 'jadwal-ramadhan' ),
		'edit_item'             => __( 'Edit Tokoh', 'jadwal-ramadhan' ),
		'update_item'           => __( 'Update Tokoh', 'jadwal-ramadhan' ),
		'view_item'             => __( 'View Tokoh', 'jadwal-ramadhan' ),
		'view_items'            => __( 'View Tokoh', 'jadwal-ramadhan' ),
		'search_items'          => __( 'Search Tokoh', 'jadwal-ramadhan' ),
		'not_found'             => __( 'Not found', 'jadwal-ramadhan' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'jadwal-ramadhan' ),
		'featured_image'        => __( 'Foto Tokoh', 'jadwal-ramadhan' ),
		'set_featured_image'    => __( 'Set foto tokoh', 'jadwal-ramadhan' ),
		'remove_featured_image' => __( 'Remove foto tokoh', 'jadwal-ramadhan' ),
		'use_featured_image'    => __( 'Use as foto tokoh', 'jadwal-ramadhan' ),
		'insert_into_item'      => __( 'Insert into tokoh', 'jadwal-ramadhan' ),
		'uploaded_to_this_item' => __( 'Uploaded to this tokoh', 'jadwal-ramadhan' ),
		'items_list'            => __( 'Tokoh list', 'jadwal-ramadhan' ),
		'items_list_navigation' => __( 'Tokoh list navigation', 'jadwal-ramadhan' ),
		'filter_items_list'     => __( 'Filter tokoh list', 'jadwal-ramadhan' ),
	);
	$args   = array(
		'label'                 => __( 'Tokoh', 'jadwal-ramadhan' ),
		'description'           => __( 'Database of religious leaders', 'jadwal-ramadhan' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ), // Title (Name), Thumbnail (Photo)
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-admin-users',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'tokoh', $args );
}
add_action( 'init', 'jadwal_ramadhan_register_tokoh', 0 );

/**
 * Add Meta Boxes for Tokoh
 */
function jadwal_ramadhan_tokoh_meta_boxes() {
    add_meta_box(
        'tokoh_details',
        'Detail Tokoh',
        'jadwal_ramadhan_tokoh_meta_cb',
        'tokoh',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'jadwal_ramadhan_tokoh_meta_boxes' );

function jadwal_ramadhan_tokoh_meta_cb( $post ) {
    wp_nonce_field( 'jadwal_ramadhan_tokoh_save', 'jadwal_ramadhan_tokoh_nonce' );
    
    $gelar = get_post_meta( $post->ID, 'gelar', true );
    $biografi = get_post_meta( $post->ID, 'biografi', true );
    ?>
    <p>
        <label for="gelar" class="components-base-control__label">Gelar (contoh: Lc., MA)</label><br>
        <input type="text" id="gelar" name="gelar" value="<?php echo esc_attr( $gelar ); ?>" class="widefat">
    </p>
    <p>
        <label for="biografi" class="components-base-control__label">Biografi Singkat</label><br>
        <textarea id="biografi" name="biografi" rows="4" class="widefat"><?php echo esc_textarea( $biografi ); ?></textarea>
    </p>
    <?php
}

/**
 * Save Meta Data for Tokoh
 */
function jadwal_ramadhan_tokoh_save_meta( $post_id ) {
    if ( ! isset( $_POST['jadwal_ramadhan_tokoh_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['jadwal_ramadhan_tokoh_nonce'], 'jadwal_ramadhan_tokoh_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['gelar'] ) ) {
        update_post_meta( $post_id, 'gelar', sanitize_text_field( $_POST['gelar'] ) );
    }
    if ( isset( $_POST['biografi'] ) ) {
        update_post_meta( $post_id, 'biografi', sanitize_textarea_field( $_POST['biografi'] ) );
    }
}
add_action( 'save_post', 'jadwal_ramadhan_tokoh_save_meta' );
