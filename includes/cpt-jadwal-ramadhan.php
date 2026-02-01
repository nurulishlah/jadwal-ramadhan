<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register CPT Jadwal Ramadhan
 */
function jadwal_ramadhan_register_jadwal() {
	$labels = array(
		'name'                  => _x( 'Jadwal Ramadhan', 'Post Type General Name', 'jadwal-ramadhan' ),
		'singular_name'         => _x( 'Jadwal', 'Post Type Singular Name', 'jadwal-ramadhan' ),
		'menu_name'             => __( 'Jadwal Ramadhan', 'jadwal-ramadhan' ),
		'name_admin_bar'        => __( 'Jadwal Ramadhan', 'jadwal-ramadhan' ),
		'archives'              => __( 'Jadwal Archives', 'jadwal-ramadhan' ),
		'attributes'            => __( 'Jadwal Attributes', 'jadwal-ramadhan' ),
		'parent_item_colon'     => __( 'Parent Jadwal:', 'jadwal-ramadhan' ),
		'all_items'             => __( 'All Jadwal', 'jadwal-ramadhan' ),
		'add_new_item'          => __( 'Add New Jadwal', 'jadwal-ramadhan' ),
		'add_new'               => __( 'Add New', 'jadwal-ramadhan' ),
		'new_item'              => __( 'New Jadwal', 'jadwal-ramadhan' ),
		'edit_item'             => __( 'Edit Jadwal', 'jadwal-ramadhan' ),
		'update_item'           => __( 'Update Jadwal', 'jadwal-ramadhan' ),
		'view_item'             => __( 'View Jadwal', 'jadwal-ramadhan' ),
		'view_items'            => __( 'View Jadwal', 'jadwal-ramadhan' ),
		'search_items'          => __( 'Search Jadwal', 'jadwal-ramadhan' ),
		'not_found'             => __( 'Not found', 'jadwal-ramadhan' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'jadwal-ramadhan' ),
	);
	$args   = array(
		'label'                 => __( 'Jadwal Ramadhan', 'jadwal-ramadhan' ),
		'description'           => __( 'Daily schedule for Ramadhan', 'jadwal-ramadhan' ),
		'labels'                => $labels,
		'supports'              => array( 'title' ), // Only title, others are meta
		'taxonomies'            => array(),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-calendar-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'jadwal_ramadhan', $args );
}
add_action( 'init', 'jadwal_ramadhan_register_jadwal', 0 );

/**
 * Add Meta Boxes for Jadwal
 */
function jadwal_ramadhan_meta_boxes() {
    add_meta_box(
        'jadwal_details',
        'Detail Jadwal',
        'jadwal_ramadhan_meta_cb',
        'jadwal_ramadhan',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'jadwal_ramadhan_meta_boxes' );

function jadwal_ramadhan_meta_cb( $post ) {
    wp_nonce_field( 'jadwal_ramadhan_save', 'jadwal_ramadhan_nonce' );
    
    $tanggal_masehi = get_post_meta( $post->ID, 'tanggal_masehi', true );
    $malam_ke = get_post_meta( $post->ID, 'malam_ke', true );
    $relasi_tokoh = get_post_meta( $post->ID, 'relasi_tokoh', true ); // Penceramah
    $relasi_imam_tarawih = get_post_meta( $post->ID, 'relasi_imam_tarawih', true );
    $relasi_imam_qiyamul = get_post_meta( $post->ID, 'relasi_imam_qiyamul', true );
    $topik_kajian = get_post_meta( $post->ID, 'topik_kajian', true );
    $waktu_kajian = get_post_meta( $post->ID, 'waktu_kajian', true );

    // Get List of Tokoh for Dropdowns
    $tokoh_posts = get_posts(array(
        'post_type' => 'tokoh',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    ?>
    <p>
        <label>Tanggal Masehi:</label><br>
        <input type="date" name="tanggal_masehi" value="<?php echo esc_attr( $tanggal_masehi ); ?>" class="widefat">
    </p>
    <p>
        <label>Malam Ke- (1-30):</label><br>
        <input type="number" name="malam_ke" value="<?php echo esc_attr( $malam_ke ); ?>" class="widefat" min="1" max="30">
    </p>
    <p>
        <label>Penceramah (Kultum/Kajian):</label><br>
        <select name="relasi_tokoh" class="widefat">
            <option value="">-- Pilih Tokoh --</option>
            <?php foreach($tokoh_posts as $p): ?>
                <option value="<?php echo $p->ID; ?>" <?php selected($relasi_tokoh, $p->ID); ?>><?php echo esc_html($p->post_title); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Imam Tarawih:</label><br>
        <select name="relasi_imam_tarawih" class="widefat">
            <option value="">-- Pilih Tokoh --</option>
            <?php foreach($tokoh_posts as $p): ?>
                <option value="<?php echo $p->ID; ?>" <?php selected($relasi_imam_tarawih, $p->ID); ?>><?php echo esc_html($p->post_title); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Imam Qiyamul Lail:</label><br>
        <select name="relasi_imam_qiyamul" class="widefat">
            <option value="">-- Pilih Tokoh --</option>
            <?php foreach($tokoh_posts as $p): ?>
                <option value="<?php echo $p->ID; ?>" <?php selected($relasi_imam_qiyamul, $p->ID); ?>><?php echo esc_html($p->post_title); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Topik Kajian:</label><br>
        <input type="text" name="topik_kajian" value="<?php echo esc_attr( $topik_kajian ); ?>" class="widefat">
    </p>
    <p>
        <label>Waktu Kajian:</label><br>
        <input type="text" name="waktu_kajian" value="<?php echo esc_attr( $waktu_kajian ); ?>" class="widefat" placeholder="Contoh: Ba'da Shubuh">
    </p>
    <?php
}

/**
 * Save Meta Data for Jadwal
 */
function jadwal_ramadhan_save_meta( $post_id ) {
    if ( ! isset( $_POST['jadwal_ramadhan_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['jadwal_ramadhan_nonce'], 'jadwal_ramadhan_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    $fields = array('tanggal_masehi', 'malam_ke', 'relasi_tokoh', 'relasi_imam_tarawih', 'relasi_imam_qiyamul', 'topik_kajian', 'waktu_kajian');
    foreach($fields as $field) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }
}
add_action( 'save_post', 'jadwal_ramadhan_save_meta' );
