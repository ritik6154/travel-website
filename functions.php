<?php
/**
 * VedaWays WordPress Theme
 * functions.php — Theme setup, enqueue scripts/styles, custom post types
 */

// ===== THEME SETUP =====
function vedaways_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);
    add_theme_support('custom-logo', [
        'height'      => 80,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary'   => __('Primary Navigation', 'vedaways'),
        'footer'    => __('Footer Navigation', 'vedaways'),
    ]);
}
add_action('after_setup_theme', 'vedaways_setup');

// ===== ENQUEUE SCRIPTS & STYLES =====
function vedaways_scripts() {
    // Google Fonts
    wp_enqueue_style('vedaways-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Jost:wght@300;400;500;600&display=swap',
        [], null
    );

    // Main stylesheet
    wp_enqueue_style('vedaways-style', get_stylesheet_uri(), [], '1.0.0');

    // Custom styles
    wp_enqueue_style('vedaways-main', get_template_directory_uri() . '/assets/css/main.css', [], '1.0.0');

    // Main JS
    wp_enqueue_script('vedaways-main', get_template_directory_uri() . '/assets/js/main.js', [], '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('vedaways-main', 'vedaways_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('vedaways_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'vedaways_scripts');

// ===== CUSTOM POST TYPE: ITINERARY =====
function vedaways_register_post_types() {
    register_post_type('itinerary', [
        'labels' => [
            'name'          => __('Itineraries', 'vedaways'),
            'singular_name' => __('Itinerary', 'vedaways'),
            'add_new'       => __('Add New Journey', 'vedaways'),
            'add_new_item'  => __('Add New Itinerary', 'vedaways'),
            'edit_item'     => __('Edit Itinerary', 'vedaways'),
            'menu_name'     => __('Journeys', 'vedaways'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'journeys'],
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'menu_icon'    => 'dashicons-location-alt',
        'show_in_rest' => true,
    ]);

    // Destinations CPT
    register_post_type('destination', [
        'labels' => [
            'name'          => __('Destinations', 'vedaways'),
            'singular_name' => __('Destination', 'vedaways'),
            'menu_name'     => __('Destinations', 'vedaways'),
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'destinations'],
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'    => 'dashicons-admin-site',
        'show_in_rest' => true,
    ]);

    // Testimonials CPT
    register_post_type('testimonial', [
        'labels' => [
            'name'     => __('Testimonials', 'vedaways'),
            'menu_name' => __('Testimonials', 'vedaways'),
        ],
        'public'      => false,
        'show_ui'     => true,
        'supports'    => ['title', 'editor', 'thumbnail'],
        'menu_icon'   => 'dashicons-format-quote',
        'show_in_rest'=> true,
    ]);
}
add_action('init', 'vedaways_register_post_types');

// ===== CUSTOM TAXONOMIES =====
function vedaways_register_taxonomies() {
    // Journey Category
    register_taxonomy('journey_category', 'itinerary', [
        'labels' => [
            'name'          => __('Journey Categories', 'vedaways'),
            'singular_name' => __('Journey Category', 'vedaways'),
        ],
        'hierarchical' => true,
        'rewrite'      => ['slug' => 'journey-type'],
        'show_in_rest' => true,
    ]);

    // Destinations Taxonomy
    register_taxonomy('journey_destination', 'itinerary', [
        'labels' => [
            'name'          => __('Destinations', 'vedaways'),
            'singular_name' => __('Destination', 'vedaways'),
        ],
        'hierarchical' => false,
        'rewrite'      => ['slug' => 'destination-tag'],
        'show_in_rest' => true,
    ]);
}
add_action('init', 'vedaways_register_taxonomies');

// ===== CUSTOM META BOXES: ITINERARY DETAILS =====
function vedaways_add_meta_boxes() {
    add_meta_box('itinerary_details', 'Journey Details', 'vedaways_itinerary_meta_box', 'itinerary', 'normal', 'high');
    add_meta_box('testimonial_details', 'Testimonial Details', 'vedaways_testimonial_meta_box', 'testimonial', 'normal', 'high');
}
add_action('add_meta_boxes', 'vedaways_add_meta_boxes');

function vedaways_itinerary_meta_box($post) {
    wp_nonce_field('vedaways_itinerary_nonce', 'vedaways_nonce_field');
    $fields = [
        '_route'         => 'Route (e.g., Delhi → Agra → Jaipur)',
        '_duration'      => 'Duration (e.g., 6 Days / 5 Nights)',
        '_price_min'     => 'Price From (₹, numbers only)',
        '_price_max'     => 'Price To (₹, numbers only)',
        '_hotel_type'    => 'Hotel Type (e.g., 4★ Heritage Hotels)',
        '_includes'      => 'Includes (comma separated)',
        '_excludes'      => 'Excludes (comma separated)',
        '_highlights'    => 'Highlights (comma separated)',
        '_badge_text'    => 'Badge Text (e.g., Popular)',
        '_badge_color'   => 'Badge Color (hex, e.g., #C45C30)',
    ];
    echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:16px">';
    foreach ($fields as $key => $label) {
        $val = get_post_meta($post->ID, $key, true);
        echo "<div><label style='display:block;font-weight:600;margin-bottom:4px'>$label</label>";
        echo "<input type='text' name='$key' value='" . esc_attr($val) . "' style='width:100%;padding:8px;border:1px solid #ddd;border-radius:4px'/></div>";
    }
    echo '</div>';
}

function vedaways_testimonial_meta_box($post) {
    wp_nonce_field('vedaways_testimonial_nonce', 'vedaways_testimonial_nonce_field');
    $fields = [
        '_traveler_name'    => 'Traveler Name',
        '_traveler_country' => 'Country',
        '_rating'           => 'Rating (1-5)',
        '_trip_type'        => 'Trip Type',
    ];
    echo '<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;padding:16px">';
    foreach ($fields as $key => $label) {
        $val = get_post_meta($post->ID, $key, true);
        echo "<div><label style='display:block;font-weight:600;margin-bottom:4px'>$label</label>";
        echo "<input type='text' name='$key' value='" . esc_attr($val) . "' style='width:100%;padding:8px;border:1px solid #ddd;border-radius:4px'/></div>";
    }
    echo '</div>';
}

function vedaways_save_meta($post_id) {
    if (!isset($_POST['vedaways_nonce_field']) || !wp_verify_nonce($_POST['vedaways_nonce_field'], 'vedaways_itinerary_nonce')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $fields = ['_route','_duration','_price_min','_price_max','_hotel_type','_includes','_excludes','_highlights','_badge_text','_badge_color'];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_itinerary', 'vedaways_save_meta');

// ===== AJAX: INQUIRY FORM =====
function vedaways_submit_inquiry() {
    check_ajax_referer('vedaways_nonce', 'nonce');

    $data = [
        'post_title'   => sanitize_text_field($_POST['name']) . ' — Inquiry',
        'post_content' => sanitize_textarea_field($_POST['message'] ?? ''),
        'post_type'    => 'inquiry_lead',
        'post_status'  => 'private',
    ];

    $post_id = wp_insert_post($data);

    if ($post_id) {
        update_post_meta($post_id, '_name', sanitize_text_field($_POST['name']));
        update_post_meta($post_id, '_email', sanitize_email($_POST['email']));
        update_post_meta($post_id, '_phone', sanitize_text_field($_POST['phone'] ?? ''));
        update_post_meta($post_id, '_interest', sanitize_text_field($_POST['interest'] ?? ''));
        update_post_meta($post_id, '_budget', sanitize_text_field($_POST['budget'] ?? ''));
        update_post_meta($post_id, '_duration', sanitize_text_field($_POST['duration'] ?? ''));

        // Send email to admin
        $admin_email = get_option('admin_email');
        $subject = "🌟 New Trip Inquiry — " . sanitize_text_field($_POST['name']);
        $message = "New inquiry from VedaWays website:\n\n";
        $message .= "Name: " . $_POST['name'] . "\n";
        $message .= "Email: " . $_POST['email'] . "\n";
        $message .= "Phone: " . ($_POST['phone'] ?? 'N/A') . "\n";
        $message .= "Interest: " . ($_POST['interest'] ?? 'N/A') . "\n";
        $message .= "Duration: " . ($_POST['duration'] ?? 'N/A') . "\n";
        $message .= "Budget: ₹" . ($_POST['budget'] ?? 'N/A') . "\n";

        wp_mail($admin_email, $subject, $message);

        wp_send_json_success(['message' => 'Inquiry received!', 'id' => $post_id]);
    } else {
        wp_send_json_error(['message' => 'Failed to save inquiry']);
    }
}
add_action('wp_ajax_vedaways_inquiry', 'vedaways_submit_inquiry');
add_action('wp_ajax_nopriv_vedaways_inquiry', 'vedaways_submit_inquiry');

// ===== AJAX: CONTACT FORM =====
function vedaways_submit_contact() {
    check_ajax_referer('vedaways_nonce', 'nonce');

    $to = get_option('admin_email');
    $subject = "📬 Contact: " . sanitize_text_field($_POST['name']);
    $message = "Name: " . $_POST['name'] . "\nEmail: " . $_POST['email'] . "\nMessage: " . $_POST['message'];

    wp_mail($to, $subject, $message);
    wp_send_json_success(['message' => 'Message sent!']);
}
add_action('wp_ajax_vedaways_contact', 'vedaways_submit_contact');
add_action('wp_ajax_nopriv_vedaways_contact', 'vedaways_submit_contact');

// ===== WIDGETIZED AREAS =====
function vedaways_widgets_init() {
    register_sidebar([
        'name'          => __('Footer Widget Area', 'vedaways'),
        'id'            => 'footer-widget-area',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ]);
}
add_action('widgets_init', 'vedaways_widgets_init');

// ===== HELPER: Get Itinerary Price =====
function vedaways_get_price_range($post_id) {
    $min = get_post_meta($post_id, '_price_min', true);
    $max = get_post_meta($post_id, '_price_max', true);
    if ($min && $max) {
        return '₹' . number_format($min) . ' – ₹' . number_format($max);
    }
    return 'Price on request';
}

// ===== HELPER: Star Rating =====
function vedaways_star_rating($rating) {
    $stars = '';
    for ($i = 0; $i < 5; $i++) {
        $stars .= $i < $rating ? '★' : '☆';
    }
    return '<span class="stars" style="color:#C9A84C">' . $stars . '</span>';
}

// Add inquiry_lead CPT (for WordPress CRM)
function vedaways_register_inquiry_cpt() {
    register_post_type('inquiry_lead', [
        'labels'   => ['name' => 'Inquiry Leads', 'singular_name' => 'Inquiry Lead', 'menu_name' => 'Leads'],
        'public'   => false,
        'show_ui'  => true,
        'supports' => ['title', 'editor'],
        'menu_icon'=> 'dashicons-email-alt',
        'capability_type' => 'post',
    ]);
}
add_action('init', 'vedaways_register_inquiry_cpt');
