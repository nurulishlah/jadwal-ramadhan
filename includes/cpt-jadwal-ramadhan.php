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
    $tanggal_hijriyah = get_post_meta( $post->ID, 'tanggal_hijriyah', true );
    $malam_ke = get_post_meta( $post->ID, 'malam_ke', true );
    $relasi_imam_tarawih = get_post_meta( $post->ID, 'relasi_imam_tarawih', true );
    $relasi_imam_qiyamul = get_post_meta( $post->ID, 'relasi_imam_qiyamul', true );
    
    // Get Repeater Data (Stored as JSON)
    $kajian_data = get_post_meta( $post->ID, 'kajian_data', true );
    if ( ! is_array( $kajian_data ) ) {
        $kajian_data = array();
    }

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
        <label>Tanggal Hijriyah (Text):</label><br>
        <input type="text" name="tanggal_hijriyah" value="<?php echo esc_attr( $tanggal_hijriyah ); ?>" class="widefat" placeholder="Contoh: 1 Ramadhan 1447H">
    </p>
    <p>
        <label>Malam Ke- (1-30):</label><br>
        <input type="number" name="malam_ke" value="<?php echo esc_attr( $malam_ke ); ?>" class="widefat" min="1" max="30">
    </p>
    
    <hr style="margin: 20px 0; border: none; border-bottom: 1px solid #ddd;">
    <h4 style="margin-bottom: 10px;">Imam Salat</h4>
    
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

    <hr style="margin: 20px 0; border: none; border-bottom: 1px solid #ddd;">
    <h4 style="margin-bottom: 10px;">Daftar Kajian (Kultum, Subuh, dll)</h4>

    <div id="kajian-repeater-container">
        <?php foreach ( $kajian_data as $index => $row ) : 
            $t_id = isset( $row['tokoh_id'] ) ? $row['tokoh_id'] : '';
            $waktu = isset( $row['waktu'] ) ? $row['waktu'] : '';
            $topik = isset( $row['topik'] ) ? $row['topik'] : '';
        ?>
        <div class="kajian-row" style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin-bottom: 10px; border-radius: 4px;">
            <p style="margin-top: 0;">
                <label>Waktu Kajian (e.g. Ba'da Shubuh):</label>
                <input type="text" name="kajian_data[<?php echo $index; ?>][waktu]" value="<?php echo esc_attr( $waktu ); ?>" class="widefat">
            </p>
            <p>
                <label>Penceramah:</label>
                <select name="kajian_data[<?php echo $index; ?>][tokoh_id]" class="widefat">
                    <option value="">-- Pilih Tokoh --</option>
                    <?php foreach($tokoh_posts as $p): ?>
                        <option value="<?php echo $p->ID; ?>" <?php selected($t_id, $p->ID); ?>><?php echo esc_html($p->post_title); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label>Topik:</label>
                <input type="text" name="kajian_data[<?php echo $index; ?>][topik]" value="<?php echo esc_attr( $topik ); ?>" class="widefat">
            </p>
            <button type="button" class="button remove-kajian-row" style="color: #a00;">Hapus Kajian ini</button>
        </div>
        <?php endforeach; ?>
    </div>

    <button type="button" class="button button-primary" id="add-kajian-row">Tambah Kajian</button>
    
    <!-- Template for JS -->
    <script type="text/template" id="kajian-row-template">
        <div class="kajian-row" style="background: #f9f9f9; padding: 10px; border: 1px solid #ddd; margin-bottom: 10px; border-radius: 4px;">
            <p style="margin-top: 0;">
                <label>Waktu Kajian (e.g. Ba'da Shubuh):</label>
                <input type="text" name="kajian_data[{index}][waktu]" value="" class="widefat">
            </p>
            <p>
                <label>Penceramah:</label>
                <select name="kajian_data[{index}][tokoh_id]" class="widefat">
                    <option value="">-- Pilih Tokoh --</option>
                    <?php foreach($tokoh_posts as $p): ?>
                        <option value="<?php echo $p->ID; ?>"><?php echo esc_html($p->post_title); ?></option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label>Topik:</label>
                <input type="text" name="kajian_data[{index}][topik]" value="" class="widefat">
            </p>
            <button type="button" class="button remove-kajian-row" style="color: #a00;">Hapus Kajian ini</button>
        </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var container = $('#kajian-repeater-container');
        var template = $('#kajian-row-template').html();
        
        $('#add-kajian-row').on('click', function() {
            var index = container.children().length;
            var newRow = template.replace(/{index}/g, index);
            container.append(newRow);
        });

        container.on('click', '.remove-kajian-row', function() {
            $(this).closest('.kajian-row').remove();
            // Re-index logic could be added here if PHP relies on continuous indexes, 
            // but typical array submission handles non-contiguous fine usually if just iterating.
            // WP preserves keys. So we might get kajian_data[0], kajian_data[2].
            // We should strip keys before saving in PHP just to be safe/clean.
        });
    });
    </script>
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

    // Flat fields
    $fields = array('tanggal_masehi', 'tanggal_hijriyah', 'malam_ke', 'relasi_imam_tarawih', 'relasi_imam_qiyamul');
    foreach($fields as $field) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
    }

    // Repeater field
    if ( isset( $_POST['kajian_data'] ) && is_array( $_POST['kajian_data'] ) ) {
        $clean_kajian = array();
        foreach ( $_POST['kajian_data'] as $row ) {
            if ( ! empty( $row['waktu'] ) || ! empty( $row['tokoh_id'] ) || ! empty( $row['topik'] ) ) {
                $clean_kajian[] = array(
                    'waktu'    => sanitize_text_field( $row['waktu'] ),
                    'tokoh_id' => sanitize_text_field( $row['tokoh_id'] ),
                    'topik'    => sanitize_text_field( $row['topik'] ),
                );
            }
        }
        // Save as JSON based meta (serialized array is default WP behavior for arrays passed to update_post_meta)
        update_post_meta( $post_id, 'kajian_data', $clean_kajian );
    } else {
        delete_post_meta( $post_id, 'kajian_data' );
    }
}
add_action( 'save_post', 'jadwal_ramadhan_save_meta' );
