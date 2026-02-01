<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function jadwal_ramadhan_add_import_page() {
    add_submenu_page(
        'edit.php?post_type=jadwal_ramadhan',
        'Import Data',
        'Import Data',
        'manage_options',
        'jadwal-import',
        'jadwal_ramadhan_render_import_page'
    );
}
add_action( 'admin_menu', 'jadwal_ramadhan_add_import_page' );

function jadwal_ramadhan_render_import_page() {
    ?>
    <div class="wrap">
        <h1>Import Jadwal Ramadhan Data</h1>
        
        <?php
        if ( isset( $_POST['run_import'] ) && check_admin_referer( 'jadwal_import_action', 'jadwal_import_nonce' ) ) {
            jadwal_ramadhan_run_import_process();
        }
        ?>

        <div class="card" style="max-width: 600px; padding: 20px; margin-top: 20px;">
            <p>Click the button below to populate the 'Tokoh' and 'Jadwal Ramadhan' based on the provided images.</p>
            <p><strong>Warning:</strong> This may create duplicate entries if run multiple times (logic checks for existing Titles).</p>
            <form method="post">
                <?php wp_nonce_field( 'jadwal_import_action', 'jadwal_import_nonce' ); ?>
                <input type="submit" name="run_import" class="button button-primary" value="Run Import Process">
            </form>
        </div>
    </div>
    <?php
}

function jadwal_ramadhan_run_import_process() {
    // DATA ARRAYS
    $tokoh_list = [
        "Ust. Arif Amiruddin, Al-Hafidz" => ["Gelar" => "Al-Hafidz"],
        "Ust. Deva Abdussalam" => ["Gelar" => ""],
        "Ust. Ogy Ridwan Muhajirin, Al-Hafidz" => ["Gelar" => "Al-Hafidz"],
        "Ust. Muhammad Amrin" => ["Gelar" => ""],
        "Ust. Sadewa Al-Haidiantoro Al- Hafidz" => ["Gelar" => "Al-Hafidz"],
        "Ust. Rian Fakhruddin" => ["Gelar" => ""],
        "Ust. Mahmudi, S.Th.I, M. Ag" => ["Gelar" => "S.Th.I, M.Ag"],
        "Ust. Syaikhuddin, S.Pd.I., M.Ag" => ["Gelar" => "S.Pd.I., M.Ag"],
        "Ust. Gemilang Pandu Purnama" => ["Gelar" => ""],
        "Ust. Mahfudz Hidayat" => ["Gelar" => ""],
        "Ust. Kyai Dede Supriyatna, S.Ag, M.Pd.I" => ["Gelar" => "S.Ag, M.Pd.I"],
        "Ust. Dr. Muhyidin Junaidi" => ["Gelar" => "Dr."],
        "Ust. Dr. Dadang Holiyullah" => ["Gelar" => "Dr."],
        "Ust. Dr. Ahmadi Usman" => ["Gelar" => "Dr."],
        "Ust. Riza Afrizal, Lc." => ["Gelar" => "Lc."],
        "Ust. Inayatullah Hasyim" => ["Gelar" => ""],
        "Ust. M. Taufiq Daud, M.PH., M.A." => ["Gelar" => "M.PH., M.A."],
        "Ust. Abdul Khalim, S.Ag, M.Pd" => ["Gelar" => "S.Ag, M.Pd"],
        "Ust. KH . Abbas Aula, Lc., MHI." => ["Gelar" => "KH., Lc., MHI."],
        "Ust. M. Iqbal Tawakal, Lc." => ["Gelar" => "Lc."],
        "Ust. Dr. Buchori Muslim" => ["Gelar" => "Dr."],
        "Ust. Fahmi Amhar" => ["Gelar" => ""],
        "Ust. Suyud Arif" => ["Gelar" => ""],
        "Ust. DR. Agusman M.E.I" => ["Gelar" => "DR., M.E.I"],
        "Ust. Dr. Ibdalsyah" => ["Gelar" => "Dr."],
        "Ust. Ahmad Tefur, S.Si." => ["Gelar" => "S.Si."],
        "Ust. Asnan Purba, LC. M.PdI. QWP.CWC" => ["Gelar" => "LC. M.PdI."],
        "Ust. Dr. Akhmad Alim, Lc., MA" => ["Gelar" => "Dr., Lc., MA"],
        "Ust. Taufik Nurrahman" => ["Gelar" => ""],
        "Ust. Prof. DR. H Endin Mujahidin M.SI" => ["Gelar" => "Prof. DR. H., M.SI"],
        "Ust. DR. Samsul Basri,S.Si. M.E.I" => ["Gelar" => "DR., S.Si., M.E.I"]
    ];

    $schedule_master = [
        1  => ["2026-02-17", "Ust. Arif Amiruddin, Al-Hafidz"],
        2  => ["2026-02-18", "Ust. Deva Abdussalam"],
        3  => ["2026-02-19", "Ust. Ogy Ridwan Muhajirin, Al-Hafidz"],
        4  => ["2026-02-20", "Ust. Muhammad Amrin"],
        5  => ["2026-02-21", "Ust. Sadewa Al-Haidiantoro Al- Hafidz"],
        6  => ["2026-02-22", "Ust. Rian Fakhruddin"],
        7  => ["2026-02-23", "Ust. Mahmudi, S.Th.I, M. Ag"],
        8  => ["2026-02-24", "Ust. Arif Amiruddin, Al-Hafidz"],
        9  => ["2026-02-25", "Ust. Deva Abdussalam"],
        10 => ["2026-02-26", "Ust. Ogy Ridwan Muhajirin, Al-Hafidz"],
        11 => ["2026-02-27", "Ust. Arif Amiruddin, Al-Hafidz"],
        12 => ["2026-02-28", "Ust. Sadewa Al-Haidiantoro Al- Hafidz"],
        13 => ["2026-03-01", "Ust. Muhammad Amrin"],
        14 => ["2026-03-02", "Ust. Mahmudi, S.Th.I, M. Ag"],
        15 => ["2026-03-03", "Ust. Deva Abdussalam"],
        16 => ["2026-03-04", "Ust. Ogy Ridwan Muhajirin, Al-Hafidz"],
        17 => ["2026-03-05", "Ust. Syaikhuddin, S.Pd.I., M.Ag"],
        18 => ["2026-03-06", "Ust. Arif Amiruddin, Al-Hafidz"],
        19 => ["2026-03-07", "Ust. Mahmudi, S.Th.I, M. Ag"],
        20 => ["2026-03-08", "Ust. Rian Fakhruddin"],
        21 => ["2026-03-09", "Ust. Arif Amiruddin, Al-Hafidz"],
        22 => ["2026-03-10", "Ust. Syaikhuddin, S.Pd.I., M.Ag"],
        23 => ["2026-03-11", "Ust. Ogy Ridwan Muhajirin, Al-Hafidz"],
        24 => ["2026-03-12", "Ust. Muhammad Amrin"],
        25 => ["2026-03-13", "Ust. Sadewa Al-Haidiantoro Al- Hafidz"],
        26 => ["2026-03-14", "Ust. Gemilang Pandu Purnama"],
        27 => ["2026-03-15", "Ust. Syaikhuddin, S.Pd.I., M.Ag"],
        28 => ["2026-03-16", "Ust. Rian Fakhruddin"],
        29 => ["2026-03-17", "Ust. Ogy Ridwan Muhajirin, Al-Hafidz"],
        30 => ["2026-03-18", "Ust. Sadewa Al-Haidiantoro Al- Hafidz"],
    ];

    $qiyamul_schedule = [
        21 => "Ust. Arif Amiruddin, Al-Hafidz",
        22 => "Ust. Syaikhuddin, S.Pd.I., M.Ag",
        23 => "Ust. Ogy Ridwan Muhajirin, Al-Hafidz",
        24 => "Ust. Muhammad Amrin",
        25 => "Ust. Sadewa Al-Haidiantoro Al- Hafidz",
        26 => "Ust. Gemilang Pandu Purnama",
        27 => "Ust. Syaikhuddin, S.Pd.I., M.Ag",
        28 => "Ust. Rian Fakhruddin",
        29 => "Ust. Ogy Ridwan Muhajirin, Al-Hafidz",
        30 => "Ust. Sadewa Al-Haidiantoro Al- Hafidz",
    ];

    $kajian_schedule = [
        "2026-02-21" => [["Ust. Mahfudz Hidayat", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-3"], ["Ust. Kyai Dede Supriyatna, S.Ag, M.Pd.I", "Ba'da 'Isya", "Kultum Tarawih"]],
        "2026-02-22" => [["Ust. Dr. Muhyidin Junaidi", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-3"], ["Ust. Dr. Dadang Holiyullah", "Ba'da 'Isya", "Kultum Tarawih"]],
        "2026-02-28" => [["Ust. Dr. Ahmadi Usman", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-4"], ["Ust. Riza Afrizal, Lc.", "Ba'da 'Isya", "Kultum Tarawih"]],
        "2026-03-01" => [["Ust. Inayatullah Hasyim", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-1"], ["Ust. M. Taufiq Daud, M.PH., M.A.", "Ba'da 'Isya", "Kultum Tarawih"]],
        "2026-03-06" => [["Ust. Abdul Khalim, S.Ag, M.Pd", "Ba'da 'Isya", "Kultum Nuzul Al Qur'an"]],
        "2026-03-07" => [["Ust. KH . Abbas Aula, Lc., MHI.", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-1"], ["Ust. M. Iqbal Tawakal, Lc.", "Ba'da 'Isya", "Kultum Tarawih"]],
        "2026-03-08" => [["Ust. Dr. Buchori Muslim", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-2"], ["Ust. Fahmi Amhar", "Ba'da 'Isya", "Kultum Tarawih"]],
        "2026-03-09" => [["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"]],
        "2026-03-10" => [["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"], ["Ust. DR. Agusman M.E.I", "22.30-23.30", "Menata Hati Melalui Ibadah Iktikaf (Malam 21)"]],
        "2026-03-11" => [["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"]],
        "2026-03-12" => [["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"], ["Ust. DR. Inayatullah Hasyim", "22.30-23.30", "Refleksi Diri Melalui Iktikaf (Malam 23)"]],
        "2026-03-13" => [["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"]],
        "2026-03-14" => [["Ust. Dr. Ibdalsyah", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-2"], ["Ust. Ahmad Tefur, S.Si.", "Ba'da 'Isya", "Kultum Tarawih"], ["Ust. Asnan Purba, LC. M.PdI. QWP.CWC", "22.30-23.30", "Kajian ayat-ayat tentang malam (Malam 25)"]],
        "2026-03-15" => [["Ust. Dr. Akhmad Alim, Lc., MA", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-3"], ["Ust. Taufik Nurrahman", "Ba'da 'Isya", "Kultum Tarawih"]],
        "2026-03-16" => [["Ust. Prof. DR. H Endin Mujahidin M.SI", "22.30-23.30", "Mempererat Hubungan dengan Allah (Malam 27)"]],
        "2026-03-18" => [["Ust. DR. Samsul Basri,S.Si. M.E.I", "22.30-23.30", "Mengubah Hidup Menjadi Lebih (Malam 29)"]],
    ];

    // Helper Function
    function jr_get_tokoh_id_helper($name, $tokoh_list) {
        $name = trim($name);
        $existing = get_page_by_title( $name, OBJECT, 'tokoh' );
        if ( $existing ) return $existing->ID;

        $pid = wp_insert_post( array(
            'post_title'    => $name,
            'post_status'   => 'publish',
            'post_type'     => 'tokoh'
        ));
        
        if ( $pid && !is_wp_error($pid) && isset( $tokoh_list[$name]['Gelar'] ) ) {
            update_post_meta( $pid, 'gelar', $tokoh_list[$name]['Gelar'] );
        }
        return $pid;
    }

    $log = "";

    // 1. Process Tokoh
    foreach ( array_keys($tokoh_list) as $name ) {
        jr_get_tokoh_id_helper($name, $tokoh_list);
    }
    $log .= "Tokoh processed.<br>";

    // 2. Process Schedule
    foreach ( $schedule_master as $malam => $data ) {
        $date = $data[0];
        $imam_name = $data[1];
        
        // Find existing Jadwal
        $args = [
            'post_type' => 'jadwal_ramadhan',
            'meta_key' => 'malam_ke',
            'meta_value' => $malam,
            'posts_per_page' => 1
        ];
        $q = new WP_Query($args);
        
        if ( $q->have_posts() ) {
            $q->the_post();
            $pid = get_the_ID();
        } else {
            $pid = wp_insert_post( array(
                'post_title'    => "Ramadhan Malam ke-$malam",
                'post_status'   => 'publish',
                'post_type'     => 'jadwal_ramadhan',
            ));
        }
        wp_reset_postdata();

        if ( $pid ) {
            update_post_meta( $pid, 'malam_ke', $malam );
            update_post_meta( $pid, 'tanggal_masehi', $date );

            // Imam Tarawih
            if ( $imam_name ) {
                $imam_id = jr_get_tokoh_id_helper( $imam_name, $tokoh_list );
                update_post_meta( $pid, 'relasi_imam_tarawih', $imam_id );
            }

            // Imam Qiyamul
            if ( isset( $qiyamul_schedule[$malam] ) ) {
                $q_id = jr_get_tokoh_id_helper( $qiyamul_schedule[$malam], $tokoh_list );
                update_post_meta( $pid, 'relasi_imam_qiyamul', $q_id );
            }

            // Kajian
            if ( isset( $kajian_schedule[$date] ) ) {
                $entries = $kajian_schedule[$date];
                $chosen = null;
                // Prioritize Isya/Tarawih
                foreach($entries as $e) {
                    if ( strpos($e[1], 'Isya') !== false ) {
                        $chosen = $e;
                        break;
                    }
                }
                if (!$chosen && !empty($entries)) $chosen = $entries[0];

                if ( $chosen ) {
                    $p_id = jr_get_tokoh_id_helper( $chosen[0], $tokoh_list );
                    update_post_meta( $pid, 'relasi_tokoh', $p_id );
                    update_post_meta( $pid, 'waktu_kajian', $chosen[1] );
                    update_post_meta( $pid, 'topik_kajian', $chosen[2] ?? '' );
                }
            }
        }
    }
    $log .= "Jadwal processed.<br>";

    // Show Notice
    echo '<div class="notice notice-success is-dismissible"><p>Import Completed Successfully!<br>' . $log . '</p></div>';
}
