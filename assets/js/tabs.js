jQuery(document).ready(function ($) {
    $('.jadwal-tab-btn').on('click', function () {
        // Remove active class from all buttons
        $('.jadwal-tab-btn').removeClass('active text-emerald-600 border-b-2 border-emerald-600').addClass('text-gray-500 border-transparent');

        // Add active class to clicked button
        $(this).addClass('active text-emerald-600 border-b-2 border-emerald-600').removeClass('text-gray-500 border-transparent');

        // Hide all tab contents
        $('.jadwal-tab-content').addClass('hidden').removeClass('block');

        // Show target content
        var target = $(this).data('target');
        $('#' + target).removeClass('hidden').addClass('block');
    });
});
