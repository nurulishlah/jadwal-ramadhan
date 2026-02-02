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
        'meta_value' => current_time('Y-m-d'), 
        'posts_per_page' => 1
    );
    
    $query_today = new WP_Query( $args_today );
    $today_data = false;

    if ( $query_today->have_posts() ) {
        while ( $query_today->have_posts() ) {
            $query_today->the_post();
            $pid = get_the_ID();
            $kajian_raw = get_post_meta( $pid, 'kajian_data', true );
            
            $today_data = array(
                'malam_ke' => get_post_meta( $pid, 'malam_ke', true ),
                'tanggal_hijriyah' => get_post_meta( $pid, 'tanggal_hijriyah', true ),
                'imam_tarawih_id' => get_post_meta( $pid, 'relasi_imam_tarawih', true ),
                'imam_qiyamul_id' => get_post_meta( $pid, 'relasi_imam_qiyamul', true ),
                'kajian_list' => is_array($kajian_raw) ? $kajian_raw : array(),
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
    <div class="jadwal-wrapper">
        <!-- Header -->
        <div class="jadwal-header">
            <h2>
                <span class="dashicons dashicons-calendar-alt"></span>
                Jadwal Ibadah Ramadhan 1447H
            </h2>
            <div class="jadwal-header-date">
                <?php if ( $today_data && !empty($today_data['tanggal_hijriyah']) ) : ?>
                    <span class="jadwal-hijri"><?php echo esc_html( $today_data['tanggal_hijriyah'] ); ?></span>
                <?php endif; ?>
                <?php echo esc_html( $today_display ); ?>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="jadwal-tabs">
            <button class="jadwal-tab-btn active" data-target="tab-today">
                Hari Ini
            </button>
            <button class="jadwal-tab-btn" data-target="tab-all">
                Seluruh Jadwal
            </button>
        </div>

        <!-- Tab Content: Hari Ini -->
        <div id="tab-today" class="jadwal-tab-content block">
            <?php if ( $today_data ) : ?>
                <div class="jadwal-today-header">
                    <span class="jadwal-badge-malam">
                        Malam ke-<?php echo esc_html( $today_data['malam_ke'] ); ?>
                    </span>
                    <h3 class="jadwal-today-date"><?php echo esc_html( $today_data['tanggal_hijriyah'] ? $today_data['tanggal_hijriyah'] : $today_display ); ?></h3>
                </div>

                <div class="jadwal-grid">
                    
                    <!-- Imam Tarawih Card -->
                    <?php if ( $today_data['imam_tarawih_id'] ) : 
                         $tokoh = get_post( $today_data['imam_tarawih_id'] );
                         $img = get_the_post_thumbnail_url( $tokoh->ID, 'medium' );
                         $gelar = get_post_meta( $tokoh->ID, 'gelar', true );
                    ?>
                    <div class="jadwal-card card-tarawih">
                        <div class="jadwal-card-img-wrapper">
                            <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($tokoh->post_title); ?>" class="jadwal-card-img"><?php endif; ?>
                        </div>
                        <h4 class="jadwal-card-title"><?php echo esc_html( $tokoh->post_title ); ?></h4>
                        <p class="jadwal-card-role"><?php echo esc_html( $gelar ); ?></p>
                        <span class="jadwal-badge-role">Imam Tarawih</span>
                    </div>
                    <?php endif; ?>

                    <!-- Imam Qiyamul Lail Card -->
                    <?php if ( $today_data['imam_qiyamul_id'] ) : 
                         $tokoh = get_post( $today_data['imam_qiyamul_id'] );
                         $img = get_the_post_thumbnail_url( $tokoh->ID, 'medium' );
                         $gelar = get_post_meta( $tokoh->ID, 'gelar', true );
                    ?>
                    <div class="jadwal-card card-qiyamul">
                        <div class="jadwal-card-img-wrapper">
                            <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($tokoh->post_title); ?>" class="jadwal-card-img"><?php endif; ?>
                        </div>
                        <h4 class="jadwal-card-title"><?php echo esc_html( $tokoh->post_title ); ?></h4>
                        <p class="jadwal-card-role"><?php echo esc_html( $gelar ); ?></p>
                        <span class="jadwal-badge-role">Imam Qiyamul Lail</span>
                    </div>
                    <?php endif; ?>

                    <!-- Loop Kajian Cards -->
                    <?php foreach($today_data['kajian_list'] as $kajian): 
                        if ( empty($kajian['tokoh_id']) ) continue;
                        $tokoh = get_post( $kajian['tokoh_id'] );
                        if (!$tokoh) continue;
                        
                        $img = get_the_post_thumbnail_url( $tokoh->ID, 'medium' );
                        $gelar = get_post_meta( $tokoh->ID, 'gelar', true );
                        $waktu = $kajian['waktu'] ?: 'Kajian';
                        $topik = $kajian['topik'];
                    ?>
                    <div class="jadwal-card card-penceramah">
                        <div class="jadwal-card-img-wrapper">
                            <?php if($img): ?><img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($tokoh->post_title); ?>" class="jadwal-card-img"><?php endif; ?>
                        </div>
                        <h4 class="jadwal-card-title"><?php echo esc_html( $tokoh->post_title ); ?></h4>
                        <p class="jadwal-card-role"><?php echo esc_html( $gelar ); ?></p>
                        <span class="jadwal-badge-role"><?php echo esc_html($waktu); ?></span>
                        <?php if($topik): ?>
                            <p class="jadwal-card-topic">"<?php echo esc_html($topik); ?>"</p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>

                </div>

            <?php else : ?>
                <div class="jadwal-empty">
                    <span class="jadwal-empty-icon">🌙</span>
                    <p class="jadwal-empty-text">Tidak ada jadwal khusus hari ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Tab Content: Seluruh Jadwal -->
        <div id="tab-all" class="jadwal-tab-content">
            <div class="jadwal-table-container">
                <table class="jadwal-table">
                    <thead>
                        <tr>
                            <th>Malam Ke</th>
                            <th>Tanggal</th>
                            <th>Imam Tarawih</th>
                            <th>Imam Qiyamul</th>
                            <th>Agenda Kajian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if ( $query_all->have_posts() ) :
                            while ( $query_all->have_posts() ) : $query_all->the_post();
                                $pid = get_the_ID();
                                $malam = get_post_meta( $pid, 'malam_ke', true );
                                $date = get_post_meta( $pid, 'tanggal_masehi', true );
                                $hijri = get_post_meta( $pid, 'tanggal_hijriyah', true );
                                
                                $imam_tarawih = get_post_meta( $pid, 'relasi_imam_tarawih', true );
                                $imam_tarawih_name = $imam_tarawih ? get_the_title($imam_tarawih) : '-';

                                $imam_qiyamul = get_post_meta( $pid, 'relasi_imam_qiyamul', true );
                                $imam_qiyamul_name = $imam_qiyamul ? get_the_title($imam_qiyamul) : '-';

                                $kajian_raw = get_post_meta( $pid, 'kajian_data', true );
                                $kajian_display = '-';
                                if ( is_array($kajian_raw) && !empty($kajian_raw) ) {
                                    $items = [];
                                    foreach($kajian_raw as $k) {
                                        if(empty($k['tokoh_id'])) continue;
                                        $t = get_the_title($k['tokoh_id']);
                                        $w = $k['waktu'];
                                        $items[] = "<strong>$w</strong>: $t";
                                    }
                                    if(!empty($items)) $kajian_display = implode('<br>', $items);
                                }
                        ?>
                        <tr>
                            <td class="jadwal-table-malam"><?php echo esc_html($malam); ?></td>
                            <td class="jadwal-table-date">
                                <strong><?php echo esc_html($hijri); ?></strong>
                                <span><?php echo esc_html(date_i18n('d M Y', strtotime($date))); ?></span>
                            </td>
                            <td><?php echo esc_html($imam_tarawih_name); ?></td>
                            <td><?php echo esc_html($imam_qiyamul_name); ?></td>
                            <td><?php echo $kajian_display; // Allowed html ?></td>
                        </tr>
                        <?php 
                            endwhile; 
                            wp_reset_postdata();
                        else:
                        ?>
                        <tr>
                            <td colspan="5" class="jadwal-empty-text">Belum ada data jadwal.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
