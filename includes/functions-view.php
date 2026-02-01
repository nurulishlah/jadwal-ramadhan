<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared function to render the dashboard
 */
function jadwal_ramadhan_get_dashboard_html() {
    // Current Date logic
    $today_ymd = current_time( 'Ymd' );
    $today_display = date_i18n( 'l, d F Y', current_time( 'timestamp' ) );

    // Query for Today's Schedule
    $args_today = array(
        'post_type' => 'jadwal_ramadhan',
        'meta_key' => 'tanggal_masehi',
        'meta_value' => current_time('Y-m-d'), // Comparison with Y-m-d format stored in DB
        'posts_per_page' => 1
    );
    // Note: The saved date format in CPT impl was `input type="date"` which saves as Y-m-d.
    // So we should compare with Y-m-d.
    
    $query_today = new WP_Query( $args_today );
    $today_data = false;

    if ( $query_today->have_posts() ) {
        while ( $query_today->have_posts() ) {
            $query_today->the_post();
            $pid = get_the_ID();
            $today_data = array(
                'malam_ke' => get_post_meta( $pid, 'malam_ke', true ),
                'penceramah_id' => get_post_meta( $pid, 'relasi_tokoh', true ),
                'imam_tarawih_id' => get_post_meta( $pid, 'relasi_imam_tarawih', true ),
                'imam_qiyamul_id' => get_post_meta( $pid, 'relasi_imam_qiyamul', true ),
                'topik' => get_post_meta( $pid, 'topik_kajian', true ),
                'waktu' => get_post_meta( $pid, 'waktu_kajian', true ),
            );
        }
        wp_reset_postdata();
    }

    // Query for Full Schedule
    $args_all = array(
        'post_type' => 'jadwal_ramadhan',
        'posts_per_page' => 30, // Max 30 days
        'meta_key' => 'malam_ke',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    );
    $query_all = new WP_Query( $args_all );

    ob_start();
    ?>
    <div class="jadwal-wrapper bg-gray-50 p-4 rounded-xl shadow-lg font-sans text-gray-800">
        <!-- Header -->
        <div class="flex items-center justify-between bg-emerald-600 text-white p-4 rounded-t-xl">
            <h2 class="text-xl font-bold flex items-center gap-2 m-0 text-white">
                <span class="dashicons dashicons-admin-site-alt3"></span>
                Jadwal Ibadah Ramadhan 1447H
            </h2>
            <div class="text-sm opacity-90"><?php echo esc_html( $today_display ); ?></div>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex border-b border-gray-200 bg-white">
            <button class="jadwal-tab-btn flex-1 py-3 px-4 text-center font-medium text-emerald-600 border-b-2 border-emerald-600 focus:outline-none transition-colors hover:bg-emerald-50 active" data-target="tab-today">
                Hari Ini
            </button>
            <button class="jadwal-tab-btn flex-1 py-3 px-4 text-center font-medium text-gray-500 hover:text-emerald-500 focus:outline-none transition-colors hover:bg-gray-50" data-target="tab-all">
                Seluruh Jadwal
            </button>
        </div>

        <!-- Tab Content: Hari Ini -->
        <div id="tab-today" class="jadwal-tab-content block bg-white p-6 rounded-b-xl">
            <?php if ( $today_data ) : ?>
                <div class="text-center mb-6">
                    <span class="inline-block bg-emerald-100 text-emerald-800 text-sm font-semibold px-3 py-1 rounded-full">
                        Malam ke-<?php echo esc_html( $today_data['malam_ke'] ); ?>
                    </span>
                    <h3 class="text-2xl font-bold mt-2 text-gray-800"><?php echo esc_html( $today_display ); ?></h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Penceramah Card -->
                    <?php if ( $today_data['penceramah_id'] ) : 
                        $tokoh = get_post( $today_data['penceramah_id'] );
                        $img = get_the_post_thumbnail_url( $tokoh->ID, 'medium' );
                        $gelar = get_post_meta( $tokoh->ID, 'gelar', true );
                    ?>
                    <div class="bg-emerald-50 rounded-lg p-5 flex flex-col items-center shadow-sm border border-emerald-100">
                        <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-4 border-white shadow-md bg-gray-200">
                            <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($tokoh->post_title); ?>" class="w-full h-full object-cover"><?php endif; ?>
                        </div>
                        <h4 class="font-bold text-lg text-emerald-900 text-center leading-tight"><?php echo esc_html( $tokoh->post_title ); ?></h4>
                        <p class="text-xs text-emerald-600 font-medium mb-2"><?php echo esc_html( $gelar ); ?></p>
                        <span class="bg-emerald-200 text-emerald-800 text-xs px-2 py-1 rounded">Penceramah / Kultum</span>
                        <?php if($today_data['topik']): ?>
                            <p class="mt-3 text-sm text-gray-600 text-center italic">"<?php echo esc_html($today_data['topik']); ?>"</p>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Imam Tarawih Card -->
                    <?php if ( $today_data['imam_tarawih_id'] ) : 
                         $tokoh = get_post( $today_data['imam_tarawih_id'] );
                         $img = get_the_post_thumbnail_url( $tokoh->ID, 'medium' );
                         $gelar = get_post_meta( $tokoh->ID, 'gelar', true );
                    ?>
                    <div class="bg-blue-50 rounded-lg p-5 flex flex-col items-center shadow-sm border border-blue-100">
                        <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-4 border-white shadow-md bg-gray-200">
                            <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($tokoh->post_title); ?>" class="w-full h-full object-cover"><?php endif; ?>
                        </div>
                        <h4 class="font-bold text-lg text-blue-900 text-center leading-tight"><?php echo esc_html( $tokoh->post_title ); ?></h4>
                        <p class="text-xs text-blue-600 font-medium mb-2"><?php echo esc_html( $gelar ); ?></p>
                        <span class="bg-blue-200 text-blue-800 text-xs px-2 py-1 rounded">Imam Tarawih</span>
                    </div>
                    <?php endif; ?>

                    <!-- Imam Qiyamul Lail Card -->
                    <?php if ( $today_data['imam_qiyamul_id'] ) : 
                         $tokoh = get_post( $today_data['imam_qiyamul_id'] );
                         $img = get_the_post_thumbnail_url( $tokoh->ID, 'medium' );
                         $gelar = get_post_meta( $tokoh->ID, 'gelar', true );
                    ?>
                    <div class="bg-purple-50 rounded-lg p-5 flex flex-col items-center shadow-sm border border-purple-100">
                        <div class="w-24 h-24 rounded-full overflow-hidden mb-3 border-4 border-white shadow-md bg-gray-200">
                            <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($tokoh->post_title); ?>" class="w-full h-full object-cover"><?php endif; ?>
                        </div>
                        <h4 class="font-bold text-lg text-purple-900 text-center leading-tight"><?php echo esc_html( $tokoh->post_title ); ?></h4>
                        <p class="text-xs text-purple-600 font-medium mb-2"><?php echo esc_html( $gelar ); ?></p>
                        <span class="bg-purple-200 text-purple-800 text-xs px-2 py-1 rounded">Imam Qiyamul Lail</span>
                    </div>
                    <?php endif; ?>
                </div>

            <?php else : ?>
                <div class="text-center py-10">
                    <span class="text-4xl">🌙</span>
                    <p class="text-gray-500 mt-2">Tidak ada jadwal khusus hari ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab Content: Seluruh Jadwal -->
        <div id="tab-all" class="jadwal-tab-content hidden bg-white p-6 rounded-b-xl overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-4 py-3">Malam Ke</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Imam Tarawih</th>
                        <th class="px-4 py-3">Penceramah</th>
                        <th class="px-4 py-3">Imam Qiyamul</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if ( $query_all->have_posts() ) :
                        while ( $query_all->have_posts() ) : $query_all->the_post();
                            $pid = get_the_ID();
                            $malam = get_post_meta( $pid, 'malam_ke', true );
                            $date = get_post_meta( $pid, 'tanggal_masehi', true );
                            
                            $imam_tarawih = get_post_meta( $pid, 'relasi_imam_tarawih', true );
                            $imam_tarawih_name = $imam_tarawih ? get_the_title($imam_tarawih) : '-';

                            $penceramah = get_post_meta( $pid, 'relasi_tokoh', true );
                            $penceramah_name = $penceramah ? get_the_title($penceramah) : '-';

                            $imam_qiyamul = get_post_meta( $pid, 'relasi_imam_qiyamul', true );
                            $imam_qiyamul_name = $imam_qiyamul ? get_the_title($imam_qiyamul) : '-';
                    ?>
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-900 text-center"><?php echo esc_html($malam); ?></td>
                        <td class="px-4 py-3 whitespace-nowrap"><?php echo esc_html(date_i18n('d M Y', strtotime($date))); ?></td>
                        <td class="px-4 py-3"><?php echo esc_html($imam_tarawih_name); ?></td>
                        <td class="px-4 py-3"><?php echo esc_html($penceramah_name); ?></td>
                        <td class="px-4 py-3"><?php echo esc_html($imam_qiyamul_name); ?></td>
                    </tr>
                    <?php 
                        endwhile; 
                        wp_reset_postdata();
                    else:
                    ?>
                    <tr>
                        <td colspan="5" class="px-4 py-3 text-center">Belum ada data jadwal.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
