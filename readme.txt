=== Jadwal Ramadhan ===
Contributors: nurulishlah
Tags: ramadhan, jadwal, schedule, mosque, masjid
Requires at least: 6.0
Tested up to: 6.7
Stable tag: 1.0.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Manage and display Ramadhan daily schedules including Imsakiyah, Tarawih Inams, and Lectures (Kajian).

== Description ==

Jadwal Ramadhan is a lightweight plugin designed to help Mosque administrators manage their Ramadhan activities. It provides a clean, modern dashboard to display the daily schedule.

**Features:**
*   **Custom Post Types**: Manage "Tokoh" (Speakers/Imams) and "Jadwal Ramadhan" entries easily.
*   **Repeater Field for Kajian**: Add unlimited lecture types per day (e.g., Kultum Subuh, Dhuha, Tarawih).
*   **Hijri Date Support**: Auto-generates Hijri dates (e.g., "1 Ramadhan 1447H").
*   **Frontend Dashboard**: Shows a tabbed interface ("Today" vs "Full Schedule").
*   **Mobile Responsive**: Optimized for all devices using lightweight custom CSS.
*   **Admin Import**: Built-in tool to populate initial data quickly.

== Installation ==

1.  Upload the plugin files to the `/wp-content/plugins/jadwal-ramadhan` directory, or install the plugin through the WordPress plugins screen directly.
2.  Activate the plugin through the 'Plugins' screen in WordPress.
3.  Use the "Import Data" submenu to populate initial data, or add data manually via the "Tokoh" and "Jadwal Ramadhan" menus.
4.  Use the `[jadwal_ramadhan]` shortcode or the dedicated Gutenberg block to display the schedule on any page.

== Frequently Asked Questions ==

= How do I add multiple speakers for one day? =
Edit the Jadwal entry, scroll to the "Daftar Kajian" section, and click "Tambah Kajian" to add as many rows as needed.

= Can I use this for non-Ramadhan events? =
Currently, the plugin is tailored for Ramadhan (Malam Ke-X logic), but it can be adapted by developers for other uses.

== Screenshots ==

1.  Frontend Dashboard - "Hari Ini" View
2.  Frontend Dashboard - "Seluruh Jadwal" Table View
3.  Admin Interface - Repeater Fields

== Changelog ==

= 1.0.1 =
*   Added avatars for Imams and Speakers.
*   Improved table layout (top align).
*   Fixed N+1 query performance issue.

= 1.0.0 =
*   Initial release.
*   Added CPT Tokoh & Jadwal.
*   Added Frontend Dashboard with Tabs.
*   Added Admin Import tool.
*   Implemented Dynamic Repeater fields for Kajian.
*   Implemented Hijri Date support.
