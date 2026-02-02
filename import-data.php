<?php
/**
 * Import Script for Jadwal Ramadhan
 * Usage: wp eval-file import-data.php
 */

if ( ! defined( 'ABSPATH' ) ) {
    require_once( explode( 'wp-content', __DIR__ )[0] . 'wp-load.php' );
}

// 1. Data Transcribed from Images

// List of Tokoh (Name -> Gelar helpers inferred or default)
// I will try to parse titles from the names provided in the images.
$tokoh_list = [
    "Ust. Arif Amiruddin, Al-Hafidz" => ["Gelar" => "Al-Hafidz"],
    "Ust. Deva Abdussalam" => ["Gelar" => ""],
    "Ust. Ogy Ridwan Muhajirin, Al-Hafidz" => ["Gelar" => "Al-Hafidz"],
    "Ust. Muhammad Amrin" => ["Gelar" => ""],
    "Ust. Sadewa Al-Haidiantoro Al- Hafidz" => ["Gelar" => "Al-Hafidz"], // Fixed typo Al- Hafidz
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
    "Ust. Inayatullah Hasyim" => ["Gelar" => ""], // Also listed as DR later?
    "Ust. M. Taufiq Daud, M.PH., M.A." => ["Gelar" => "M.PH., M.A."],
    "Ust. Abdul Khalim, S.Ag, M.Pd" => ["Gelar" => "S.Ag, M.Pd"],
    "Ust. KH . Abbas Aula, Lc., MHI." => ["Gelar" => "KH., Lc., MHI."],
    "Ust. M. Iqbal Tawakal, Lc." => ["Gelar" => "Lc."],
    "Ust. Dr. Buchori Muslim" => ["Gelar" => "Dr."],
    "Ust. Fahmi Amhar" => ["Gelar" => ""],
    "Ust. Suyud Arif" => ["Gelar" => ""],
    "Ust. DR. Agusman M.E.I" => ["Gelar" => "DR., M.E.I"],
    // "Ust. DR. Inayatullah Hasyim" => ["Gelar" => "DR."], // Duplicate logic handled
    "Ust. Dr. Ibdalsyah" => ["Gelar" => "Dr."],
    "Ust. Ahmad Tefur, S.Si." => ["Gelar" => "S.Si."],
    "Ust. Asnan Purba, LC. M.PdI. QWP.CWC" => ["Gelar" => "LC. M.PdI."],
    "Ust. Dr. Akhmad Alim, Lc., MA" => ["Gelar" => "Dr., Lc., MA"],
    "Ust. Taufik Nurrahman" => ["Gelar" => ""],
    "Ust. Prof. DR. H Endin Mujahidin M.SI" => ["Gelar" => "Prof. DR. H., M.SI"],
    "Ust. DR. Samsul Basri,S.Si. M.E.I" => ["Gelar" => "DR., S.Si., M.E.I"]
];

// Master Schedule (Malam 1-30) FROM IMAGE 2
// Malam | Date (Feb/Mar 2026) | Imam Tarawih
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

// Qiyamul Lail (Malam 21-30) FROM IMAGE 1
// It matches the Imam Tarawih for those nights in the table BUT let's verify.
// Image 1: Malam 21 - Ust. Arif Amiruddin. Image 2: Malam 21 - Ust. Arif Amiruddin. Matches.
// Image 1: Malam 26 - Ust. Gemilang Pandu Purnama. Image 2: Malam 26 - Ust. Gemilang Pandu Purnama. Matches.
// So I can use the same Imam for Qiyamul as Tarawih for 21-30, OR simply map it specifically.
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

// Kajian / Kultum FROM IMAGE 3
// Date Mapping needs care. Image 3 uses dates.
// Key: Date ID (YYYY-MM-DD) -> [Type, Speaker, Time, Topic]
// "Ba'da Isya" roughly corresponds to Tarawih Kultum (so it belongs to that Malam).
// "Ba'da Shubuh" belongs to the morning of that date.
// ISSUE: The CPT 'Jadwal Ramadhan' basically represents a "24h window" usually centered around the Fasting Day or the Night?
// Usually Ramadhan Schedule App groups by "Ramadhan Day 1".
// Ramadhan Day 1:
// - Tarawih (Previous Night)
// - Sahur / Shubuh (Morning)
// - Buka (Evening)
// - Tarawih (Night of Day 1 -> leads to Day 2).
// User requirement: "Tabs: Hari Ini". "Card View showing: Date, Speaker (Kultum), Imam (Tarawih), Imam (Qiyamul)".
// If I visit on "Ramadhan Day 1" (Daytime):
// I want to see:
// - Imam Tarawih (Last night? Or Tonight? Usually Tonight's Tarawih is for Tomorrow).
// - Speaker Kultum (Subuh today OR Tarawih tonight).
// Let's look at the structure again.
// Meta: `tanggal_masehi`, `malam_ke`.
// Image 2: "Malam ke-1 | 17 Feb". This is Eve of Ramadhan.
// Image 3: "Sabtu, 21 Feb".
// Let's assume CPT 'Jadwal' is keyed by DATE.
// If I create a post for "2026-02-17" (Malam 1):
// - Imam Tarawih: Arif Amiruddin.
// - Imam Qiyamul: -
// - Kultum?
// Image 3 starts at "Sabtu, 21 Feb". (Malam 5).
// What about 17 Feb? Not in Image 3.
// Wait, Image 3 has "No 1". Maybe the first few days are missing or I just see "No 1"?
// Ah, the image 3 shows "No 1 | Sabtu, 21 Pebruari 2026". Maybe Week 1 is missing or this is "Kajian Rutin"?
// The title says "Jadwal Kultum Shalat Tarawih / ... / Kajian Rutin ... Pekan-3". 
// Maybe the user only provided partial pics.
// I will map what I have.
$kajian_schedule = [
    // Format: 'YYYY-MM-DD' => ['Speaker', 'Waktu', 'Topik']
    // Note: If Waktu is Ba'da Isya, it aligns with the Night (Malam).
    // If Waktu is Ba'da Shubuh, it aligns with the Day morning.
    // I will try to merge these into the "Jadwal" post of that DATE.
    
    // "2026-02-21" (Sabtu)
    "2026-02-21" => [
        ["Ust. Mahfudz Hidayat", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-3"],
        ["Ust. Kyai Dede Supriyatna, S.Ag, M.Pd.I", "Ba'da 'Isya", "Kultum Tarawih"],
    ],
    // "2026-02-22" (Ahad)
    "2026-02-22" => [
        ["Ust. Dr. Muhyidin Junaidi", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-3"],
        ["Ust. Dr. Dadang Holiyullah", "Ba'da 'Isya", "Kultum Tarawih"],
    ],
    // "2026-02-28" (Sabtu)
    "2026-02-28" => [
        ["Ust. Dr. Ahmadi Usman", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-4"],
        ["Ust. Riza Afrizal, Lc.", "Ba'da 'Isya", "Kultum Tarawih"],
    ],
    // "2026-03-01" (Ahad)
    "2026-03-01" => [
        ["Ust. Inayatullah Hasyim", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-1"],
        ["Ust. M. Taufiq Daud, M.PH., M.A.", "Ba'da 'Isya", "Kultum Tarawih"],
    ],
    // "2026-03-06" (Jum'at)
    "2026-03-06" => [
        ["Ust. Abdul Khalim, S.Ag, M.Pd", "Ba'da 'Isya", "Kultum Nuzul Al Qur'an"],
    ],
    // "2026-03-07" (Sabtu)
    "2026-03-07" => [
        ["Ust. KH . Abbas Aula, Lc., MHI.", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-1"],
        ["Ust. M. Iqbal Tawakal, Lc.", "Ba'da 'Isya", "Kultum Tarawih"],
    ],
    // "2026-03-08" (Ahad)
    "2026-03-08" => [
        ["Ust. Dr. Buchori Muslim", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-2"],
        ["Ust. Fahmi Amhar", "Ba'da 'Isya", "Kultum Tarawih"],
    ],
    // "2026-03-09" (Senin)
    "2026-03-09" => [
        ["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"],
    ],
    // "2026-03-10" (Selasa)
    "2026-03-10" => [
        ["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"],
        ["Ust. DR. Agusman M.E.I", "22.30-23.30", "Menata Hati Melalui Ibadah Iktikaf (Malam 21)"],
    ],
    // "2026-03-11" (Rabu)
    "2026-03-11" => [
        ["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"],
    ],
    // "2026-03-12" (Kamis)
    "2026-03-12" => [
        ["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"],
        ["Ust. DR. Inayatullah Hasyim", "22.30-23.30", "Refleksi Diri Melalui Iktikaf (Malam 23)"],
    ],
    // "2026-03-13" (Jum'at)
    "2026-03-13" => [
        ["Ust. Suyud Arif", "Ba'da Shubuh", "Kajian Tematik: Ilmu Waris (Faroid)"],
    ],
    // "2026-03-14" (Sabtu)
    "2026-03-14" => [
        ["Ust. Dr. Ibdalsyah", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-2"],
        ["Ust. Ahmad Tefur, S.Si.", "Ba'da 'Isya", "Kultum Tarawih"],
        ["Ust. Asnan Purba, LC. M.PdI. QWP.CWC", "22.30-23.30", "Kajian ayat-ayat tentang malam (Malam 25)"],
    ],
    // "2026-03-15" (Ahad)
    "2026-03-15" => [
        ["Ust. Dr. Akhmad Alim, Lc., MA", "Ba'da Shubuh", "Kajian Rutin Akhir Pekan-3"],
        ["Ust. Taufik Nurrahman", "Ba'da 'Isya", "Kultum Tarawih"],
    ],
    // "2026-03-16" (Senin)
    "2026-03-16" => [
        ["Ust. Prof. DR. H Endin Mujahidin M.SI", "22.30-23.30", "Mempererat Hubungan dengan Allah (Malam 27)"],
    ],
    // "2026-03-18" (Rabu) - Note: 17 skipped in image 3
    "2026-03-18" => [
        ["Ust. DR. Samsul Basri,S.Si. M.E.I", "22.30-23.30", "Mengubah Hidup Menjadi Lebih (Malam 29)"],
    ]
];

// Helper to get Tokoh ID
function jr_get_tokoh_id( $name_raw ) {
    global $tokoh_list;
    // Normalized Name search
    $name = trim($name_raw);
    
    // Check existing
    $existing = get_page_by_title( $name, OBJECT, 'tokoh' );
    if ( $existing ) return $existing->ID;

    // Create New
    $post_data = array(
        'post_title'    => $name,
        'post_status'   => 'publish',
        'post_type'     => 'tokoh',
    );
    $pid = wp_insert_post( $post_data );
    
    if ( $pid && !is_wp_error($pid) ) {
        // Try to map gelar
        if ( isset( $tokoh_list[$name]['Gelar'] ) ) {
            update_post_meta( $pid, 'gelar', $tokoh_list[$name]['Gelar'] );
        }
        echo "Created Tokoh: $name\n";
    }
    return $pid;
}

echo "Starting Import...\n";

// A. Create/check Tokohs from our lists
// 1. From Tokoh List key
foreach ( array_keys($tokoh_list) as $name ) {
    jr_get_tokoh_id( $name );
}

// B. Process Schedule (Malam 1-30)
foreach ( $schedule_master as $malam => $data ) {
    $date = $data[0];
    $imam_name = $data[1];
    
    // Create/Search Jadwal Post
    // We try to find existing jadwal by 'malam_ke' or just create new clean title
    $title = "Ramadhan Malam ke-$malam";
    
    // Check if exists to avoid dupes on re-run
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
        echo "Updating existing Jadwal Malam $malam\n";
    } else {
        $post_data = array(
            'post_title'    => $title,
            'post_status'   => 'publish',
            'post_type'     => 'jadwal_ramadhan',
        );
        $pid = wp_insert_post( $post_data );
        echo "Created Jadwal Malam $malam ($date)\n";
    }
    wp_reset_postdata();

    // Updating Meta
    update_post_meta( $pid, 'malam_ke', $malam );
    update_post_meta( $pid, 'tanggal_masehi', $date );

    // Link Imam Tarawih
    if ( $imam_name ) {
        // Fix potential typo "Al- Hafidz" in master list to match strict map
        if ($imam_name == "Ust. Sadewa Al-Haidiantoro Al- Hafidz") {
           // It's in the key list precisely like this.
        }
        $imam_id = jr_get_tokoh_id( $imam_name );
        update_post_meta( $pid, 'relasi_imam_tarawih', $imam_id );
    }

    // Link Imam Qiyamul if exists
    if ( isset( $qiyamul_schedule[$malam] ) ) {
        $q_name = $qiyamul_schedule[$malam];
        $q_id = jr_get_tokoh_id( $q_name );
        update_post_meta( $pid, 'relasi_imam_qiyamul', $q_id );
    }

    // Link Kajian if date matches
    // Note: Kajian array has multiple items. CPT only has single "relasi_tokoh" (Penceramah), "topik_kajian", "waktu_kajian".
    // If multiple exist, we might overwrite or need a solution.
    // For now, I will prioritize "Kultum Tarawih" for the main slot, or the "Subuh" one?
    // User requested "The Speaker (Kultum)". 
    // Usually "Kultum Tarawih" is the main one displayed in "Jadwal Ibadah Ramadhan" cards for the night?
    // But "Subuh" is also important.
    // Given the single field constraint: I will join them or pick the "Kultum Tarawih" as primary if available, else "Kajian".
    
    if ( isset( $kajian_schedule[$date] ) ) {
        $entries = $kajian_schedule[$date];
        
        // Strategy: Concatenate info or Pick one.
        // Let's pick the FIRST one for the relationship field, but append others to Topik? 
        // Or better: Use the "Kultum Tarawih" (Ba'da Isya) as it matches "Malam".
        // If there is a Shubuh lecture, it technically belongs to the "Previous Night's" day? OR the "Current Day"?
        // 21 Feb is Malam 5.
        // 21 Feb Shubuh is Day 4 Morning? Or Day 5 Morning?
        // If Malam 1 is 17 Feb.
        // Day 1 is 18 Feb?
        // Then 21 Feb is Day 4.
        // If Image 3 says "21 Feb is 3 Ramadhan" -> Day 3?
        // Conflict is real.
        // Decision: Match by DATE string. 
        // If multiple entries, I will favor "Ba'da Isya" (Tarawih) for the main "Speaker" field,
        // as the User asked for "The Speaker (Kultum)".
        
        $chosen = null;
        foreach($entries as $e) {
            if ( strpos($e[1], 'Isya') !== false ) {
                $chosen = $e;
                break;
            }
        }
        if (!$chosen && !empty($entries)) $chosen = $entries[0]; // Fallback to first

        if ( $chosen ) {
            $p_id = jr_get_tokoh_id( $chosen[0] );
            update_post_meta( $pid, 'relasi_tokoh', $p_id );
            update_post_meta( $pid, 'waktu_kajian', $chosen[1] );
            update_post_meta( $pid, 'topik_kajian', $chosen[2] ?? '' ); // Some don't have topic
        }
    }
}

echo "Import Complete.\n";
