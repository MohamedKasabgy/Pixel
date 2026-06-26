<?php
/**
 * Core plugin class.
 */

if (!defined('ABSPATH')) {
    exit;
}

final class Pixel_Core
{
    private static ?Pixel_Core $instance = null;

    public static function instance(): Pixel_Core
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', [$this, 'register_post_types']);
        add_action('init', [$this, 'register_order_statuses']);
        add_filter('wc_order_statuses', [$this, 'add_order_statuses_to_woocommerce']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('pixel_quote_form', [$this, 'render_quote_form']);
        add_shortcode('pixel_artwork_upload', [$this, 'render_artwork_upload']);
        add_shortcode('pixel_client_portal', [$this, 'render_client_portal']);
        add_shortcode('pixel_order_tracker', [$this, 'render_order_tracker']);
        add_action('admin_menu', [$this, 'register_admin_pages']);
        add_action('add_meta_boxes', [$this, 'register_quote_meta_boxes']);
        add_action('add_meta_boxes', [$this, 'register_artwork_meta_boxes']);
        add_action('save_post_pixel_quote', [$this, 'save_quote_meta'], 10, 2);
        add_action('save_post_pixel_artwork', [$this, 'save_artwork_meta'], 10, 2);
        add_filter('manage_pixel_quote_posts_columns', [$this, 'register_quote_admin_columns']);
        add_action('manage_pixel_quote_posts_custom_column', [$this, 'render_quote_admin_column'], 10, 2);
        add_filter('manage_pixel_artwork_posts_columns', [$this, 'register_artwork_admin_columns']);
        add_action('manage_pixel_artwork_posts_custom_column', [$this, 'render_artwork_admin_column'], 10, 2);
        add_action('restrict_manage_posts', [$this, 'render_quote_status_filter']);
        add_action('pre_get_posts', [$this, 'filter_quotes_by_status']);
        add_action('admin_post_pixel_download_artwork', [$this, 'handle_artwork_download']);
        add_action('before_delete_post', [$this, 'delete_private_artwork_file']);
    }

    public static function activate(): void
    {
        add_role('pixel_print_staff', 'Print Staff', [
            'read'                 => true,
            'upload_files'         => true,
            'edit_posts'           => true,
            'edit_pixel_quotes'    => true,
            'read_pixel_quote'     => true,
            'edit_pixel_quote'     => true,
        ]);

        add_role('pixel_print_manager', 'Print Manager', [
            'read'                 => true,
            'upload_files'         => true,
            'edit_posts'           => true,
            'edit_others_posts'    => true,
            'publish_posts'        => true,
            'manage_woocommerce'   => true,
            'view_woocommerce_reports' => true,
        ]);

        self::instance()->register_post_types();
        flush_rewrite_rules();
    }

    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }

    public function enqueue_assets(): void
    {
        wp_enqueue_style('pixel-core', PIXEL_CORE_URL . 'assets/css/pixel-core.css', [], PIXEL_CORE_VERSION);
        wp_enqueue_script('pixel-core', PIXEL_CORE_URL . 'assets/js/pixel-core.js', [], PIXEL_CORE_VERSION, true);
    }

    public function register_post_types(): void
    {
        register_post_type('pixel_quote', [
            'labels' => [
                'name'          => 'Quote Requests',
                'singular_name' => 'Quote Request',
                'add_new_item'  => 'Add Quote Request',
                'edit_item'     => 'Edit Quote Request',
            ],
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-media-document',
            'supports'            => ['title', 'editor', 'author'],
            'capability_type'     => 'post',
            'map_meta_cap'        => true,
        ]);

        register_post_type('pixel_artwork', [
            'labels' => [
                'name'          => 'Artwork Files',
                'singular_name' => 'Artwork File',
            ],
            'public'       => false,
            'show_ui'      => true,
            'show_in_menu' => true,
            'menu_icon'    => 'dashicons-format-gallery',
            'supports'     => ['title', 'author'],
        ]);
    }

    public function register_order_statuses(): void
    {
        $statuses = [
            'wc-artwork-review' => 'Artwork Review',
            'wc-proof-needed'   => 'Proof Needed',
            'wc-in-production'  => 'In Production',
            'wc-quality-check'  => 'Quality Check',
            'wc-ready-pickup'   => 'Ready for Pickup',
            'wc-print-shipped'  => 'Shipped',
            'wc-out-delivery'   => 'Out for Delivery',
            'wc-print-delivered'=> 'Delivered',
        ];

        foreach ($statuses as $key => $label) {
            register_post_status($key, [
                'label'                     => $label,
                'public'                    => true,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop($label . ' <span class="count">(%s)</span>', $label . ' <span class="count">(%s)</span>'),
            ]);
        }
    }

    public function add_order_statuses_to_woocommerce(array $order_statuses): array
    {
        $new = [];

        foreach ($order_statuses as $key => $label) {
            $new[$key] = $label;
            if ($key === 'wc-processing') {
                $new['wc-artwork-review']  = 'Artwork Review';
                $new['wc-proof-needed']    = 'Proof Needed';
                $new['wc-in-production']   = 'In Production';
                $new['wc-quality-check']   = 'Quality Check';
                $new['wc-ready-pickup']    = 'Ready for Pickup';
                $new['wc-print-shipped']   = 'Shipped';
                $new['wc-out-delivery']    = 'Out for Delivery';
                $new['wc-print-delivered'] = 'Delivered';
            }
        }

        return $new;
    }

    public function render_quote_form(): string
    {
        $message = '';

        if (isset($_GET['quote_success'])) {
            return '<div class="notice">Quote request submitted. Our team will review your project and contact you soon.</div>';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['pixel_quote_nonce'])) {
            $message = $this->handle_quote_submission();
        }

        $products = [
            'business-cards'      => 'Business Cards',
            'flyers'              => 'Flyers',
            'brochures'           => 'Brochures',
            'postcards'           => 'Postcards',
            'stickers'            => 'Stickers & Labels',
            'custom-labels'       => 'Custom Labels',
            'roll-labels'         => 'Roll Labels',
            'vinyl-banners'       => 'Vinyl Banners',
            'retractable-banners' => 'Retractable Banners',
            'posters'             => 'Posters',
            'signage'             => 'Signs & Display Graphics',
            'yard-signs'          => 'Yard Signs',
            'foam-boards'         => 'Foam Boards',
            'vehicle-wraps'       => 'Vehicle Wraps',
            'apparel-printing'    => 'Apparel Printing',
            'packaging-boxes'     => 'Packaging Boxes',
            'paper-bags'          => 'Paper Bags',
            'packing-tape'        => 'Packing Tape',
            'custom-print-project'=> 'Other / Custom Print Project',
        ];
        $requested_product = isset($_GET['product']) ? sanitize_key(wp_unslash($_GET['product'])) : '';
        $selected_product  = isset($_POST['pixel_product_type'])
            ? sanitize_key(wp_unslash($_POST['pixel_product_type']))
            : $requested_product;

        if (!isset($products[$selected_product])) {
            $selected_product = '';
        }

        // Pre-fill from current user if logged in
        $current_user = is_user_logged_in() ? wp_get_current_user() : null;
        $prefill_name  = $current_user ? trim($current_user->first_name . ' ' . $current_user->last_name) : '';
        $prefill_email = $current_user ? $current_user->user_email : '';
        $prefill_phone = $current_user ? (string) get_user_meta($current_user->ID, 'billing_phone', true) : '';

        $values = [
            'name'            => isset($_POST['pixel_name']) ? sanitize_text_field(wp_unslash($_POST['pixel_name'])) : $prefill_name,
            'email'           => isset($_POST['pixel_email']) ? sanitize_email(wp_unslash($_POST['pixel_email'])) : $prefill_email,
            'company'         => isset($_POST['pixel_company']) ? sanitize_text_field(wp_unslash($_POST['pixel_company'])) : '',
            'phone'           => isset($_POST['pixel_phone']) ? sanitize_text_field(wp_unslash($_POST['pixel_phone'])) : $prefill_phone,
            'quantity'        => isset($_POST['pixel_quantity']) ? absint($_POST['pixel_quantity']) : '',
            'quantities'      => isset($_POST['pixel_quantities']) ? sanitize_text_field(wp_unslash($_POST['pixel_quantities'])) : '',
            'size'            => isset($_POST['pixel_size']) ? sanitize_text_field(wp_unslash($_POST['pixel_size'])) : '',
            'width'           => isset($_POST['pixel_width']) ? sanitize_text_field(wp_unslash($_POST['pixel_width'])) : '',
            'height'          => isset($_POST['pixel_height']) ? sanitize_text_field(wp_unslash($_POST['pixel_height'])) : '',
            'stock'           => isset($_POST['pixel_stock']) ? sanitize_text_field(wp_unslash($_POST['pixel_stock'])) : '',
            'colors'          => isset($_POST['pixel_colors']) ? sanitize_text_field(wp_unslash($_POST['pixel_colors'])) : '',
            'material'        => isset($_POST['pixel_material']) ? sanitize_text_field(wp_unslash($_POST['pixel_material'])) : '',
            'finish'          => isset($_POST['pixel_finish']) ? sanitize_text_field(wp_unslash($_POST['pixel_finish'])) : '',
            'finishing'       => isset($_POST['pixel_finishing']) ? sanitize_text_field(wp_unslash($_POST['pixel_finishing'])) : '',
            'artwork_status'  => isset($_POST['pixel_artwork_status']) ? sanitize_key(wp_unslash($_POST['pixel_artwork_status'])) : '',
            'due_date'        => isset($_POST['pixel_due_date']) ? sanitize_text_field(wp_unslash($_POST['pixel_due_date'])) : '',
            'deliver_to'      => isset($_POST['pixel_deliver_to']) ? sanitize_text_field(wp_unslash($_POST['pixel_deliver_to'])) : '',
            'contact_method'  => isset($_POST['pixel_contact_method']) ? sanitize_key(wp_unslash($_POST['pixel_contact_method'])) : '',
            'delivery_method' => isset($_POST['pixel_delivery_method']) ? sanitize_key(wp_unslash($_POST['pixel_delivery_method'])) : '',
            'details'         => isset($_POST['pixel_details']) ? sanitize_textarea_field(wp_unslash($_POST['pixel_details'])) : '',
        ];

        ob_start();
        echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <form class="quote-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('pixel_quote_request', 'pixel_quote_nonce'); ?>
            <div class="form-step-heading">
                <span>Step 1</span>
                <h2>Select a Print Product</h2>
            </div>
            <div class="form-grid">
                <label>Full Name<input name="pixel_name" value="<?php echo esc_attr((string) $values['name']); ?>" autocomplete="name" required></label>
                <label>Email<input type="email" name="pixel_email" value="<?php echo esc_attr((string) $values['email']); ?>" autocomplete="email" required></label>
            </div>
            <div class="form-grid">
                <label>Company Name<input name="pixel_company" value="<?php echo esc_attr((string) $values['company']); ?>" autocomplete="organization"></label>
                <label>Phone<input type="tel" name="pixel_phone" value="<?php echo esc_attr((string) $values['phone']); ?>" autocomplete="tel" required></label>
            </div>
            <label>Product Type
                <select name="pixel_product_type" required>
                    <option value="">Select a product</option>
                    <?php foreach ($products as $product_key => $product_label) : ?>
                        <option value="<?php echo esc_attr($product_key); ?>" <?php selected($selected_product, $product_key); ?>>
                            <?php echo esc_html($product_label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <div class="form-step-heading">
                <span>Step 2</span>
                <h2>Select Product Options</h2>
            </div>
            <div class="form-grid">
                <label>Quantity<input type="number" min="1" name="pixel_quantity" value="<?php echo esc_attr((string) $values['quantity']); ?>" required></label>
                <label>Quantity Breaks<input name="pixel_quantities" value="<?php echo esc_attr((string) $values['quantities']); ?>" placeholder="e.g. 250, 500, 1000"></label>
            </div>
            <div class="form-grid three">
                <label>Finish Width<input name="pixel_width" value="<?php echo esc_attr((string) $values['width']); ?>" placeholder="Width"></label>
                <label>Finish Height<input name="pixel_height" value="<?php echo esc_attr((string) $values['height']); ?>" placeholder="Height"></label>
                <label>Size Notes<input name="pixel_size" value="<?php echo esc_attr((string) $values['size']); ?>" placeholder="e.g. 24 x 36 in or custom"></label>
            </div>
            <div class="form-grid">
                <label>Stock / Paper
                    <select name="pixel_stock">
                        <option value="">Select stock</option>
                        <option value="standard-paper" <?php selected($values['stock'], 'standard-paper'); ?>>Standard Paper</option>
                        <option value="premium-cardstock" <?php selected($values['stock'], 'premium-cardstock'); ?>>Premium Cardstock</option>
                        <option value="vinyl" <?php selected($values['stock'], 'vinyl'); ?>>Vinyl</option>
                        <option value="rigid-board" <?php selected($values['stock'], 'rigid-board'); ?>>Rigid Board</option>
                        <option value="custom" <?php selected($values['stock'], 'custom'); ?>>Custom / Not Sure</option>
                    </select>
                </label>
                <label>Colors
                    <select name="pixel_colors">
                        <option value="">Select colors</option>
                        <option value="full-color-front" <?php selected($values['colors'], 'full-color-front'); ?>>Full Color Front</option>
                        <option value="full-color-both-sides" <?php selected($values['colors'], 'full-color-both-sides'); ?>>Full Color Both Sides</option>
                        <option value="black-only" <?php selected($values['colors'], 'black-only'); ?>>Black Only</option>
                        <option value="spot-color" <?php selected($values['colors'], 'spot-color'); ?>>Spot Color / Brand Match</option>
                    </select>
                </label>
            </div>
            <div class="form-grid">
                <label>Material<input name="pixel_material" value="<?php echo esc_attr((string) $values['material']); ?>" placeholder="e.g. Vinyl, cardstock, acrylic"></label>
                <label>Stakes / Finishing Options<input name="pixel_finishing" value="<?php echo esc_attr((string) $values['finishing']); ?>" placeholder="e.g. H-stakes, grommets, lamination"></label>
            </div>
            <div class="form-grid">
                <label>Finish<input name="pixel_finish" value="<?php echo esc_attr((string) $values['finish']); ?>" placeholder="e.g. Matte, gloss, laminated"></label>
                <label>Artwork Status
                    <select name="pixel_artwork_status">
                        <option value="">Select status</option>
                        <option value="ready-to-print" <?php selected($values['artwork_status'], 'ready-to-print'); ?>>Ready to Print</option>
                        <option value="needs-review" <?php selected($values['artwork_status'], 'needs-review'); ?>>Needs File Review</option>
                        <option value="need-design-help" <?php selected($values['artwork_status'], 'need-design-help'); ?>>Need Design Help</option>
                        <option value="will-send-later" <?php selected($values['artwork_status'], 'will-send-later'); ?>>Will Send Later</option>
                    </select>
                </label>
            </div>
            <div class="form-grid">
                <label>Deadline<input type="date" name="pixel_due_date" value="<?php echo esc_attr((string) $values['due_date']); ?>"></label>
                <label>Delivery Method
                    <select name="pixel_delivery_method">
                        <option value="">Select a method</option>
                        <option value="standard-shipping" <?php selected($values['delivery_method'], 'standard-shipping'); ?>>Standard Shipping</option>
                        <option value="express-shipping" <?php selected($values['delivery_method'], 'express-shipping'); ?>>Express Shipping</option>
                        <option value="local-pickup" <?php selected($values['delivery_method'], 'local-pickup'); ?>>Local Pickup</option>
                    </select>
                </label>
            </div>
            <div class="form-grid">
                <label>Deliver To<input name="pixel_deliver_to" value="<?php echo esc_attr((string) $values['deliver_to']); ?>" placeholder="City, state, pickup branch, or shipping address"></label>
                <label>Preferred Contact Method
                    <select name="pixel_contact_method">
                        <option value="">Select contact method</option>
                        <option value="email" <?php selected($values['contact_method'], 'email'); ?>>Email</option>
                        <option value="phone" <?php selected($values['contact_method'], 'phone'); ?>>Phone</option>
                        <option value="either" <?php selected($values['contact_method'], 'either'); ?>>Either</option>
                    </select>
                </label>
            </div>
            <label>Project Notes<textarea name="pixel_details" placeholder="Describe the project, intended use, delivery notes, and any special requirements."><?php echo esc_textarea((string) $values['details']); ?></textarea></label>
            <label>Artwork / Reference File
                <input type="file" name="pixel_artwork" accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tif,.tiff,.zip" data-max-size="<?php echo esc_attr((string) $this->get_max_artwork_upload_size()); ?>">
                <small>Optional. Maximum file size: <?php echo esc_html(size_format($this->get_max_artwork_upload_size())); ?>.</small>
            </label>
            <label class="checkbox-line"><input type="checkbox" name="pixel_quote_terms" value="1" required> I confirm these project details are accurate and agree to be contacted about this quote.</label>
            <button class="btn btn-primary" type="submit">Submit Quote Request</button>
        </form>
        <?php
        return ob_get_clean();
    }

    private function handle_quote_submission(): string
    {
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pixel_quote_nonce'] ?? '')), 'pixel_quote_request')) {
            return '<div class="notice error">Security check failed. Please refresh and try again.</div>';
        }

        $products = [
            'business-cards'       => 'Business Cards',
            'flyers'               => 'Flyers',
            'brochures'            => 'Brochures',
            'postcards'            => 'Postcards',
            'stickers'             => 'Stickers & Labels',
            'custom-labels'        => 'Custom Labels',
            'roll-labels'          => 'Roll Labels',
            'vinyl-banners'        => 'Vinyl Banners',
            'retractable-banners'  => 'Retractable Banners',
            'posters'              => 'Posters',
            'signage'              => 'Signs & Display Graphics',
            'yard-signs'           => 'Yard Signs',
            'foam-boards'          => 'Foam Boards',
            'vehicle-wraps'        => 'Vehicle Wraps',
            'apparel-printing'     => 'Apparel Printing',
            'packaging-boxes'      => 'Packaging Boxes',
            'paper-bags'           => 'Paper Bags',
            'packing-tape'         => 'Packing Tape',
            'custom-print-project' => 'Other / Custom Print Project',
        ];
        $delivery_methods = [
            'standard-shipping' => 'Standard Shipping',
            'express-shipping'  => 'Express Shipping',
            'local-pickup'      => 'Local Pickup',
        ];

        $name             = sanitize_text_field(wp_unslash($_POST['pixel_name'] ?? ''));
        $email            = sanitize_email(wp_unslash($_POST['pixel_email'] ?? ''));
        $company          = sanitize_text_field(wp_unslash($_POST['pixel_company'] ?? ''));
        $phone            = sanitize_text_field(wp_unslash($_POST['pixel_phone'] ?? ''));
        $product_key      = sanitize_key(wp_unslash($_POST['pixel_product_type'] ?? ''));
        $quantities       = sanitize_text_field(wp_unslash($_POST['pixel_quantities'] ?? ''));
        $size             = sanitize_text_field(wp_unslash($_POST['pixel_size'] ?? ''));
        $width            = sanitize_text_field(wp_unslash($_POST['pixel_width'] ?? ''));
        $height           = sanitize_text_field(wp_unslash($_POST['pixel_height'] ?? ''));
        $stock            = sanitize_text_field(wp_unslash($_POST['pixel_stock'] ?? ''));
        $colors           = sanitize_text_field(wp_unslash($_POST['pixel_colors'] ?? ''));
        $material         = sanitize_text_field(wp_unslash($_POST['pixel_material'] ?? ''));
        $finish           = sanitize_text_field(wp_unslash($_POST['pixel_finish'] ?? ''));
        $finishing        = sanitize_text_field(wp_unslash($_POST['pixel_finishing'] ?? ''));
        $artwork_status   = sanitize_key(wp_unslash($_POST['pixel_artwork_status'] ?? ''));
        $due_date         = sanitize_text_field(wp_unslash($_POST['pixel_due_date'] ?? ''));
        $deliver_to       = sanitize_text_field(wp_unslash($_POST['pixel_deliver_to'] ?? ''));
        $contact_method   = sanitize_key(wp_unslash($_POST['pixel_contact_method'] ?? ''));
        $delivery_key     = sanitize_key(wp_unslash($_POST['pixel_delivery_method'] ?? ''));
        $details          = sanitize_textarea_field(wp_unslash($_POST['pixel_details'] ?? ''));
        $qty              = absint($_POST['pixel_quantity'] ?? 0);

        if ($name === '' || !is_email($email) || $phone === '' || !isset($products[$product_key]) || $qty < 1 || empty($_POST['pixel_quote_terms'])) {
            return '<div class="notice error">Please complete the required fields.</div>';
        }

        if ($due_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due_date)) {
            return '<div class="notice error">Please enter a valid project deadline.</div>';
        }

        if ($delivery_key !== '' && !isset($delivery_methods[$delivery_key])) {
            return '<div class="notice error">Please select a valid delivery method.</div>';
        }

        $contact_methods = ['email', 'phone', 'either'];
        if ($contact_method !== '' && !in_array($contact_method, $contact_methods, true)) {
            return '<div class="notice error">Please select a valid contact method.</div>';
        }

        $product = $products[$product_key];
        $post_id = wp_insert_post([
            'post_type'    => 'pixel_quote',
            'post_title'   => sprintf('Quote Request - %s - %s', $product, $name),
            'post_content' => $details,
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
        ], true);

        if (is_wp_error($post_id)) {
            return '<div class="notice error">Could not create quote request. Please try again.</div>';
        }

        update_post_meta($post_id, '_pixel_customer_name', $name);
        update_post_meta($post_id, '_pixel_customer_email', $email);
        update_post_meta($post_id, '_pixel_customer_company', $company);
        update_post_meta($post_id, '_pixel_customer_phone', $phone);
        update_post_meta($post_id, '_pixel_product_key', $product_key);
        update_post_meta($post_id, '_pixel_product_type', $product);
        update_post_meta($post_id, '_pixel_quantity', $qty);
        update_post_meta($post_id, '_pixel_quantities', $quantities);
        update_post_meta($post_id, '_pixel_size', $size);
        update_post_meta($post_id, '_pixel_width', $width);
        update_post_meta($post_id, '_pixel_height', $height);
        update_post_meta($post_id, '_pixel_stock', $stock);
        update_post_meta($post_id, '_pixel_colors', $colors);
        update_post_meta($post_id, '_pixel_material', $material);
        update_post_meta($post_id, '_pixel_finish', $finish);
        update_post_meta($post_id, '_pixel_finishing', $finishing);
        update_post_meta($post_id, '_pixel_artwork_status', $artwork_status);
        update_post_meta($post_id, '_pixel_due_date', $due_date);
        update_post_meta($post_id, '_pixel_deliver_to', $deliver_to);
        update_post_meta($post_id, '_pixel_contact_method', $contact_method);
        update_post_meta($post_id, '_pixel_delivery_method', $delivery_key !== '' ? $delivery_methods[$delivery_key] : '');
        update_post_meta($post_id, '_pixel_quote_status', 'new');

        $upload_result = $this->maybe_handle_upload($post_id, 'quote');
        if (is_wp_error($upload_result)) {
            update_post_meta($post_id, '_pixel_upload_error', $upload_result->get_error_message());
            // Still redirect on success even if optional file upload failed
            if (is_user_logged_in()) {
                wp_safe_redirect(add_query_arg('quote_success', '1', home_url('/client-dashboard/')));
                exit;
            }
            return '<div class="notice">Quote request submitted, but the optional file could not be uploaded: '
                . esc_html($upload_result->get_error_message())
                . '. You can send it from the Upload Artwork page.</div>';
        }

        if (is_user_logged_in()) {
            wp_safe_redirect(add_query_arg('quote_success', '1', home_url('/client-dashboard/')));
            exit;
        }

        return '<div class="notice">Quote request submitted. Our team will review your project and contact you soon.</div>';
    }

    public function render_artwork_upload(): string
    {
        $message = '';

        if (isset($_GET['upload_success'])) {
            return '<div class="notice">Artwork uploaded successfully. Our pre-press team will review the file.</div>';
        }

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['pixel_upload_nonce'])) {
            $message = $this->handle_artwork_upload();
        }

        $requested_project = isset($_GET['product'])
            ? sanitize_text_field(str_replace('-', ' ', (string) wp_unslash($_GET['product'])))
            : '';
        // Pre-fill from current user if logged in
        $current_user     = is_user_logged_in() ? wp_get_current_user() : null;
        $prefill_name     = $current_user ? trim($current_user->first_name . ' ' . $current_user->last_name) : '';
        $prefill_email    = $current_user ? $current_user->user_email : '';
        $values = [
            'name'         => isset($_POST['pixel_upload_name']) ? sanitize_text_field(wp_unslash($_POST['pixel_upload_name'])) : $prefill_name,
            'email'        => isset($_POST['pixel_upload_email']) ? sanitize_email(wp_unslash($_POST['pixel_upload_email'])) : $prefill_email,
            'order_number' => isset($_POST['pixel_order_number']) ? sanitize_text_field(wp_unslash($_POST['pixel_order_number'])) : '',
            'project'      => isset($_POST['pixel_project_name']) ? sanitize_text_field(wp_unslash($_POST['pixel_project_name'])) : ucwords($requested_project),
            'notes'        => isset($_POST['pixel_upload_notes']) ? sanitize_textarea_field(wp_unslash($_POST['pixel_upload_notes'])) : '',
        ];

        ob_start();
        echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <form class="upload-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('pixel_artwork_upload', 'pixel_upload_nonce'); ?>
            <div class="form-grid">
                <label>Full Name<input name="pixel_upload_name" value="<?php echo esc_attr((string) $values['name']); ?>" autocomplete="name" required></label>
                <label>Email<input type="email" name="pixel_upload_email" value="<?php echo esc_attr((string) $values['email']); ?>" autocomplete="email" required></label>
            </div>
            <div class="form-grid">
                <label>Order / Quote Number<input name="pixel_order_number" value="<?php echo esc_attr((string) $values['order_number']); ?>" placeholder="PX-8492 or QT-4402" required></label>
                <label>Product / Project Name<input name="pixel_project_name" value="<?php echo esc_attr((string) $values['project']); ?>" placeholder="e.g. Summer storefront banner" required></label>
            </div>
            <label>Notes<textarea name="pixel_upload_notes" placeholder="Tell us what this file is for and include any production instructions."><?php echo esc_textarea((string) $values['notes']); ?></textarea></label>
            <label>Artwork File
                <input type="file" name="pixel_artwork" accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tif,.tiff,.zip" data-max-size="<?php echo esc_attr((string) $this->get_max_artwork_upload_size()); ?>" required>
                <small>PDF, PNG, JPG, TIFF, AI, PSD, EPS, or ZIP. Maximum file size: <?php echo esc_html(size_format($this->get_max_artwork_upload_size())); ?>.</small>
            </label>
            <button class="btn btn-primary" type="submit">Upload Artwork</button>
        </form>
        <?php
        return ob_get_clean();
    }

    private function handle_artwork_upload(): string
    {
        if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pixel_upload_nonce'] ?? '')), 'pixel_artwork_upload')) {
            return '<div class="notice error">Security check failed. Please refresh and try again.</div>';
        }

        $name         = sanitize_text_field(wp_unslash($_POST['pixel_upload_name'] ?? ''));
        $order_number = sanitize_text_field(wp_unslash($_POST['pixel_order_number'] ?? ''));
        $project_name = sanitize_text_field(wp_unslash($_POST['pixel_project_name'] ?? ''));
        $email        = sanitize_email(wp_unslash($_POST['pixel_upload_email'] ?? ''));
        $notes        = sanitize_textarea_field(wp_unslash($_POST['pixel_upload_notes'] ?? ''));

        if ($name === '' || $order_number === '' || $project_name === '' || !is_email($email)) {
            return '<div class="notice error">Please complete the required upload details.</div>';
        }

        $file_validation = $this->validate_artwork_upload($_FILES['pixel_artwork'] ?? null, true);
        if (is_wp_error($file_validation)) {
            return '<div class="notice error">' . esc_html($file_validation->get_error_message()) . '</div>';
        }

        $post_id = wp_insert_post([
            'post_type'    => 'pixel_artwork',
            'post_title'   => sprintf('Artwork Upload - %s - %s', $order_number, $project_name),
            'post_content' => $notes,
            'post_status'  => 'publish',
            'post_author'  => get_current_user_id(),
        ], true);

        if (is_wp_error($post_id)) {
            return '<div class="notice error">Could not create upload record. Please try again.</div>';
        }

        update_post_meta($post_id, '_pixel_upload_name', $name);
        update_post_meta($post_id, '_pixel_order_number', $order_number);
        update_post_meta($post_id, '_pixel_project_name', $project_name);
        update_post_meta($post_id, '_pixel_upload_email', $email);
        update_post_meta($post_id, '_pixel_artwork_status', 'uploaded');

        $upload_result = $this->maybe_handle_upload($post_id, 'artwork', true);
        if (is_wp_error($upload_result)) {
            wp_delete_post($post_id, true);
            return '<div class="notice error">' . esc_html($upload_result->get_error_message()) . '</div>';
        }

        if (is_user_logged_in()) {
            wp_safe_redirect(add_query_arg('upload_success', '1', home_url('/client-dashboard/')));
            exit;
        }

        return '<div class="notice">Artwork uploaded. Our pre-press team will review the file.</div>';
    }

    private function maybe_handle_upload(int $post_id, string $context, bool $required = false)
    {
        $validation = $this->validate_artwork_upload($_FILES['pixel_artwork'] ?? null, $required);
        if (is_wp_error($validation)) {
            return $validation;
        }

        if ($validation === false) {
            return 0;
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        $uploaded_file = wp_handle_upload(
            $_FILES['pixel_artwork'],
            [
                'test_form' => false,
                'mimes'     => $this->get_allowed_artwork_mimes(),
            ]
        );

        if (isset($uploaded_file['error'])) {
            return new WP_Error('pixel_upload_failed', 'The artwork file could not be stored: ' . $uploaded_file['error']);
        }

        $public_path = (string) ($uploaded_file['file'] ?? '');
        $private_directory = $this->get_private_artwork_directory();
        if ($public_path === '' || !wp_mkdir_p($private_directory)) {
            if ($public_path !== '' && is_file($public_path)) {
                wp_delete_file($public_path);
            }
            return new WP_Error('pixel_private_storage_failed', 'The private artwork storage directory is unavailable.');
        }

        $private_filename = wp_unique_filename($private_directory, sanitize_file_name((string) $_FILES['pixel_artwork']['name']));
        $private_path = trailingslashit($private_directory) . $private_filename;
        $moved = @rename($public_path, $private_path); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

        if (!$moved) {
            $moved = copy($public_path, $private_path);
            if ($moved) {
                wp_delete_file($public_path);
            }
        }

        if (!$moved) {
            wp_delete_file($public_path);
            return new WP_Error('pixel_private_storage_failed', 'The artwork file could not be moved into private storage.');
        }

        $attachment_id = wp_insert_attachment(
            [
                'post_mime_type' => sanitize_mime_type((string) ($uploaded_file['type'] ?? 'application/octet-stream')),
                'post_title'     => sanitize_text_field((string) pathinfo($private_filename, PATHINFO_FILENAME)),
                'post_content'   => '',
                'post_status'    => 'inherit',
            ],
            false,
            $post_id,
            true
        );

        if (is_wp_error($attachment_id)) {
            wp_delete_file($private_path);
            return new WP_Error('pixel_attachment_failed', 'The artwork record could not be created.');
        }

        update_post_meta($post_id, '_pixel_artwork_attachment_id', $attachment_id);
        update_post_meta($post_id, '_pixel_upload_context', $context);
        update_post_meta($post_id, '_pixel_original_filename', $private_filename);
        update_post_meta($post_id, '_pixel_private_file_path', $private_path);
        update_post_meta($post_id, '_pixel_artwork_mime_type', sanitize_mime_type((string) ($uploaded_file['type'] ?? 'application/octet-stream')));
        update_post_meta($attachment_id, '_pixel_private_file_path', $private_path);

        return $attachment_id;
    }

    private function validate_artwork_upload($file, bool $required = false)
    {
        if (!is_array($file) || empty($file['name'])) {
            return $required ? new WP_Error('pixel_file_required', 'Please select an artwork file.') : false;
        }

        $upload_error = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
        if ($upload_error !== UPLOAD_ERR_OK) {
            $messages = [
                UPLOAD_ERR_INI_SIZE   => 'The file exceeds the server upload limit.',
                UPLOAD_ERR_FORM_SIZE  => 'The file exceeds the allowed upload limit.',
                UPLOAD_ERR_PARTIAL    => 'The file upload was interrupted. Please try again.',
                UPLOAD_ERR_NO_FILE    => 'Please select an artwork file.',
                UPLOAD_ERR_NO_TMP_DIR => 'The server is missing a temporary upload directory.',
                UPLOAD_ERR_CANT_WRITE => 'The server could not write the uploaded file.',
                UPLOAD_ERR_EXTENSION  => 'A server extension stopped the upload.',
            ];
            return new WP_Error('pixel_upload_error', $messages[$upload_error] ?? 'The file upload failed.');
        }

        $size = isset($file['size']) ? (int) $file['size'] : 0;
        if ($size < 1) {
            return new WP_Error('pixel_empty_file', 'The selected artwork file is empty.');
        }

        if ($size > $this->get_max_artwork_upload_size()) {
            return new WP_Error(
                'pixel_file_too_large',
                sprintf('The artwork file must be smaller than %s.', size_format($this->get_max_artwork_upload_size()))
            );
        }

        $filename = sanitize_file_name((string) $file['name']);
        $extension = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));
        $allowed_extensions = ['pdf', 'ai', 'eps', 'psd', 'jpg', 'jpeg', 'png', 'tif', 'tiff', 'zip'];

        if (!in_array($extension, $allowed_extensions, true)) {
            return new WP_Error('pixel_unsupported_file', 'Unsupported file type. Upload PDF, PNG, JPG, TIFF, AI, PSD, EPS, or ZIP.');
        }

        if (!empty($file['tmp_name']) && in_array($extension, ['pdf', 'jpg', 'jpeg', 'png', 'tif', 'tiff', 'zip'], true)) {
            $checked = wp_check_filetype_and_ext((string) $file['tmp_name'], $filename, $this->get_allowed_artwork_mimes());
            if (empty($checked['ext']) || empty($checked['type'])) {
                return new WP_Error('pixel_invalid_file', 'The file contents do not match the selected artwork format.');
            }
        }

        if ($extension === 'zip') {
            $zip_validation = $this->validate_artwork_zip((string) ($file['tmp_name'] ?? ''));
            if (is_wp_error($zip_validation)) {
                return $zip_validation;
            }
        }

        return true;
    }

    private function validate_artwork_zip(string $path)
    {
        if ($path === '' || !class_exists('ZipArchive')) {
            return true;
        }

        $archive = new ZipArchive();
        if ($archive->open($path) !== true) {
            return new WP_Error('pixel_invalid_zip', 'The ZIP archive could not be opened.');
        }

        $blocked_extensions = ['php', 'phtml', 'phar', 'cgi', 'pl', 'py', 'sh', 'bash', 'exe', 'com', 'bat', 'cmd', 'js', 'html', 'htm'];
        $total_uncompressed = 0;
        $max_entries = 500;
        $max_uncompressed = 500 * MB_IN_BYTES;

        if ($archive->numFiles > $max_entries) {
            $archive->close();
            return new WP_Error('pixel_zip_too_many_files', 'The ZIP archive contains too many files.');
        }

        for ($index = 0; $index < $archive->numFiles; $index++) {
            $entry = $archive->statIndex($index);
            if (!is_array($entry)) {
                continue;
            }

            $entry_name = (string) ($entry['name'] ?? '');
            $entry_extension = strtolower((string) pathinfo($entry_name, PATHINFO_EXTENSION));
            $total_uncompressed += (int) ($entry['size'] ?? 0);

            if (str_contains($entry_name, '../') || str_starts_with($entry_name, '/')) {
                $archive->close();
                return new WP_Error('pixel_unsafe_zip_path', 'The ZIP archive contains an unsafe file path.');
            }

            if (in_array($entry_extension, $blocked_extensions, true)) {
                $archive->close();
                return new WP_Error('pixel_unsafe_zip_file', 'The ZIP archive contains a blocked executable or web file.');
            }

            if ($total_uncompressed > $max_uncompressed) {
                $archive->close();
                return new WP_Error('pixel_zip_too_large', 'The ZIP archive expands beyond the allowed size.');
            }
        }

        $archive->close();
        return true;
    }

    private function get_allowed_artwork_mimes(): array
    {
        return [
            'pdf'      => 'application/pdf',
            'ai|eps'   => 'application/postscript',
            'psd'      => 'image/vnd.adobe.photoshop',
            'jpg|jpeg' => 'image/jpeg',
            'png'      => 'image/png',
            'tif|tiff' => 'image/tiff',
            'zip'      => 'application/zip',
        ];
    }

    private function get_max_artwork_upload_size(): int
    {
        $plugin_limit = (int) apply_filters('pixel_max_artwork_upload_size', 100 * MB_IN_BYTES);
        return max(1, min($plugin_limit, (int) wp_max_upload_size()));
    }

    private function get_private_artwork_directory(): string
    {
        $default_directory = trailingslashit(dirname(untrailingslashit(ABSPATH))) . 'pixel-private-uploads';
        return (string) apply_filters('pixel_private_artwork_directory', $default_directory);
    }

    public function render_client_portal(): string
    {
        if (!is_user_logged_in()) {
            ob_start();
            ?>
            <div class="notice portal-preview-notice">
                This is a client portal preview. <a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">Log in</a> to see your real orders, quotes, and artwork.
            </div>
            <div class="portal-cards">
                <div class="portal-card"><span>Active Quotes</span><div class="metric">3</div><p>awaiting approval</p></div>
                <div class="portal-card"><span>Orders in Production</span><div class="metric">2</div><p>estimated delivery this week</p></div>
                <div class="portal-card"><span>Uploaded Files</span><div class="metric">4</div><p>available to your print team</p></div>
            </div>
            <div class="client-table-wrap">
                <table class="client-table">
                    <thead><tr><th>Job ID</th><th>Project Name</th><th>Status</th><th>Date Updated</th><th>Action</th></tr></thead>
                    <tbody>
                        <tr><td>#ORD-9021</td><td><strong>Retail Window Vinyls</strong></td><td><span class="status-pill">In Production</span></td><td>Recently</td><td><a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">Track</a></td></tr>
                        <tr><td>#QT-4402</td><td><strong>Corporate Fleet Vehicle Wraps</strong></td><td><span class="status-pill neutral">Quoted</span></td><td>Recently</td><td>Review</td></tr>
                        <tr><td>#FILE-184</td><td><strong>Trade Show Backdrop Artwork</strong></td><td><span class="status-pill neutral">Approved</span></td><td>Recently</td><td>File</td></tr>
                    </tbody>
                </table>
            </div>
            <?php
            return ob_get_clean();
        }

        $user = wp_get_current_user();
        $customer_email = strtolower((string) $user->user_email);
        $quotes = $this->get_customer_records(
            'pixel_quote',
            '_pixel_customer_email',
            (int) $user->ID,
            $customer_email
        );
        $artwork_files = $this->get_customer_records(
            'pixel_artwork',
            '_pixel_upload_email',
            (int) $user->ID,
            $customer_email
        );
        $orders = function_exists('wc_get_orders')
            ? wc_get_orders([
                'customer_id' => (int) $user->ID,
                'limit'       => 20,
                'orderby'     => 'date',
                'order'       => 'DESC',
            ])
            : [];

        $inactive_quote_statuses = ['rejected', 'converted-to-order'];
        $active_quotes = array_filter(
            $quotes,
            fn(WP_Post $quote): bool => !in_array(
                $this->normalize_quote_status((string) get_post_meta($quote->ID, '_pixel_quote_status', true)),
                $inactive_quote_statuses,
                true
            )
        );
        $inactive_order_statuses = ['completed', 'cancelled', 'refunded', 'failed'];
        $active_orders = array_filter(
            $orders,
            static fn($order): bool => is_object($order) && method_exists($order, 'get_status')
                && !in_array($order->get_status(), $inactive_order_statuses, true)
        );

        $activity = [];
        foreach ($orders as $order) {
            if (!is_object($order) || !method_exists($order, 'get_id')) {
                continue;
            }

            $modified = $order->get_date_modified() ?: $order->get_date_created();
            $activity[] = [
                'timestamp' => $modified ? $modified->getTimestamp() : 0,
                'id'        => 'ORD-' . $order->get_order_number(),
                'project'   => $this->get_order_project_name($order),
                'status'    => function_exists('wc_get_order_status_name') ? wc_get_order_status_name($order->get_status()) : ucfirst($order->get_status()),
                'action'    => '<a href="' . esc_url(add_query_arg('order_number', $order->get_order_number(), home_url('/order-tracking/'))) . '">Track</a>',
            ];
        }

        foreach ($quotes as $quote) {
            $activity[] = [
                'timestamp' => (int) get_post_modified_time('U', true, $quote),
                'id'        => 'QT-' . $quote->ID,
                'project'   => (string) get_post_meta($quote->ID, '_pixel_product_type', true),
                'status'    => $this->get_quote_status_label((string) get_post_meta($quote->ID, '_pixel_quote_status', true)),
                'action'    => '<span>Quote</span>',
            ];
        }

        foreach ($artwork_files as $artwork) {
            $download_url = $this->get_artwork_download_url($artwork->ID);
            $activity[] = [
                'timestamp' => (int) get_post_modified_time('U', true, $artwork),
                'id'        => 'FILE-' . $artwork->ID,
                'project'   => (string) get_post_meta($artwork->ID, '_pixel_project_name', true),
                'status'    => $this->get_artwork_status_label((string) get_post_meta($artwork->ID, '_pixel_artwork_status', true)),
                'action'    => $download_url !== '' ? '<a href="' . esc_url($download_url) . '">Download</a>' : '<span>File</span>',
            ];
        }

        usort($activity, static fn(array $first, array $second): int => $second['timestamp'] <=> $first['timestamp']);
        $activity = array_slice($activity, 0, 10);

        ob_start();
        ?>
        <div class="portal-cards">
            <div class="portal-card"><span>Active Quotes</span><div class="metric"><?php echo esc_html((string) count($active_quotes)); ?></div><p>open quote requests</p></div>
            <div class="portal-card"><span>Active Orders</span><div class="metric"><?php echo esc_html((string) count($active_orders)); ?></div><p>currently being processed</p></div>
            <div class="portal-card"><span>Uploaded Files</span><div class="metric"><?php echo esc_html((string) count($artwork_files)); ?></div><p>available to your print team</p></div>
        </div>
        <div class="client-table-wrap">
            <table class="client-table">
                <thead><tr><th>Job ID</th><th>Project Name</th><th>Status</th><th>Date Updated</th><th>Action</th></tr></thead>
                <tbody>
                    <?php if ($activity === []) : ?>
                        <tr><td colspan="5"><div class="portal-empty-state"><strong>No project activity yet.</strong><p>Request a quote or upload artwork to start your first print project.</p></div></td></tr>
                    <?php else : ?>
                        <?php foreach ($activity as $item) : ?>
                            <tr>
                                <td>#<?php echo esc_html($item['id']); ?></td>
                                <td><strong><?php echo esc_html($item['project'] !== '' ? $item['project'] : 'Print Project'); ?></strong></td>
                                <td><span class="status-pill neutral"><?php echo esc_html($item['status']); ?></span></td>
                                <td><?php echo esc_html(wp_date(get_option('date_format'), $item['timestamp'])); ?></td>
                                <td><?php echo wp_kses($item['action'], ['a' => ['href' => []], 'span' => []]); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_order_tracker(): string
    {
        $order_number = isset($_GET['order_number'])
            ? sanitize_text_field(wp_unslash($_GET['order_number']))
            : '';
        $billing_email = is_user_logged_in() ? (string) wp_get_current_user()->user_email : '';
        $message = '';
        $order = null;
        $search_attempted = $order_number !== '';

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['pixel_tracking_nonce'])) {
            $search_attempted = true;
            $order_number = sanitize_text_field(wp_unslash($_POST['pixel_tracking_order'] ?? ''));
            $billing_email = sanitize_email(wp_unslash($_POST['pixel_tracking_email'] ?? ''));
            if ($billing_email === '' && is_user_logged_in()) {
                $billing_email = (string) wp_get_current_user()->user_email;
            }

            if (!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pixel_tracking_nonce'])), 'pixel_track_order')) {
                $message = '<div class="notice error">Security check failed. Please refresh and try again.</div>';
            } elseif ($order_number === '' || (!is_user_logged_in() && !is_email($billing_email))) {
                $message = '<div class="notice error">Enter your order number and billing email.</div>';
            }
        }

        if ($search_attempted && $message === '') {
            if (!function_exists('wc_get_order')) {
                $message = '<div class="notice error">Order tracking is temporarily unavailable.</div>';
            } else {
                $order = $this->find_woocommerce_order($order_number);
                if (!$order || !$this->can_view_tracked_order($order, $billing_email)) {
                    $order = null;
                    $message = '<div class="notice error">We could not find an order matching those details.</div>';
                }
            }
        }

        ob_start();
        echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <form class="tracking-search-form" method="post">
            <?php wp_nonce_field('pixel_track_order', 'pixel_tracking_nonce'); ?>
            <div>
                <label for="pixel-tracking-order">Order Number</label>
                <input id="pixel-tracking-order" name="pixel_tracking_order" value="<?php echo esc_attr($order_number); ?>" placeholder="e.g. 8492 or PX-8492" required>
            </div>
            <div>
                <label for="pixel-tracking-email">Billing Email</label>
                <input id="pixel-tracking-email" type="email" name="pixel_tracking_email" value="<?php echo esc_attr($billing_email); ?>" placeholder="you@company.com" <?php echo is_user_logged_in() ? '' : 'required'; ?>>
            </div>
            <button class="btn btn-primary" type="submit">Track Order</button>
        </form>

        <?php if ($order) : ?>
            <?php
            $order_status = $order->get_status();
            $status_label = function_exists('wc_get_order_status_name')
                ? wc_get_order_status_name($order_status)
                : ucwords(str_replace('-', ' ', $order_status));
            $current_step = $this->get_order_timeline_position($order_status);
            $timeline_steps = [
                'Order Placed',
                'Artwork Review',
                'Proof Approval',
                'In Production',
                'Quality Check',
                'Ready for Pickup',
                'Out for Delivery',
                'Completed',
            ];
            $order_date = $order->get_date_created();
            $shipping_address = $order->get_formatted_shipping_address();
            if ($shipping_address === '') {
                $shipping_address = $order->get_formatted_billing_address();
            }
            $tracking_number = (string) $order->get_meta('_tracking_number');
            $tracking_provider = (string) $order->get_meta('_tracking_provider');
            $shipping_method = (string) $order->get_shipping_method();
            $associated_files = $this->get_order_artwork_files($order);
            ?>
            <div class="tracking-card">
                <div class="tracking-top">
                    <div>
                        <h2>Order #<?php echo esc_html((string) $order->get_order_number()); ?> <span class="status-pill"><?php echo esc_html($status_label); ?></span></h2>
                        <p>Placed <?php echo esc_html($order_date ? wp_date(get_option('date_format'), $order_date->getTimestamp()) : 'recently'); ?></p>
                    </div>
                    <div>
                        <small>Order Total</small>
                        <h2><?php echo wp_kses_post($order->get_formatted_order_total()); ?></h2>
                    </div>
                </div>
                <div class="timeline-wrap">
                    <div class="timeline">
                        <?php foreach ($timeline_steps as $index => $step_label) : ?>
                            <?php
                            $step_class = '';
                            if ($index < $current_step) {
                                $step_class = 'done';
                            } elseif ($index === $current_step) {
                                $step_class = 'current';
                            }
                            ?>
                            <div class="timeline-step <?php echo esc_attr($step_class); ?>">
                                <?php echo esc_html($step_label); ?><br>
                                <small><?php echo $index < $current_step ? 'Complete' : ($index === $current_step ? 'Current stage' : 'Pending'); ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="portal-cards tracking-details-grid">
                <div class="portal-card">
                    <h3>Order Items</h3>
                    <ul class="tracking-list">
                        <?php foreach ($order->get_items() as $item) : ?>
                            <li><span><?php echo esc_html($item->get_name()); ?></span><strong>× <?php echo esc_html((string) $item->get_quantity()); ?></strong></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="portal-card">
                    <h3>Associated Files</h3>
                    <?php if ($associated_files === []) : ?>
                        <p>No artwork files are attached to this order yet.</p>
                    <?php else : ?>
                        <ul class="tracking-list">
                            <?php foreach ($associated_files as $artwork) : ?>
                                <?php $download_url = $this->get_artwork_download_url($artwork->ID); ?>
                                <li>
                                    <span>
                                        <?php if ($download_url !== '' && is_user_logged_in()) : ?>
                                            <a href="<?php echo esc_url($download_url); ?>"><?php echo esc_html((string) get_post_meta($artwork->ID, '_pixel_original_filename', true)); ?></a>
                                        <?php else : ?>
                                            <?php echo esc_html((string) get_post_meta($artwork->ID, '_pixel_original_filename', true)); ?>
                                        <?php endif; ?>
                                    </span>
                                    <strong><?php echo esc_html($this->get_artwork_status_label((string) get_post_meta($artwork->ID, '_pixel_artwork_status', true))); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="portal-card">
                    <h3>Shipping Details</h3>
                    <p><strong><?php echo esc_html($tracking_provider !== '' ? $tracking_provider : ($shipping_method !== '' ? $shipping_method : 'Delivery method pending')); ?></strong></p>
                    <p><?php echo wp_kses_post($shipping_address !== '' ? $shipping_address : 'Delivery address will appear here when assigned.'); ?></p>
                    <p><?php echo $tracking_number !== '' ? 'Tracking: ' . esc_html($tracking_number) : 'Tracking number not yet assigned.'; ?></p>
                </div>
            </div>
        <?php else : ?>
            <div class="tracking-card">
                <div class="tracking-top">
                    <div>
                        <h2>Demo Order #PX-9021 <span class="status-pill">In Production</span></h2>
                        <p>Use this preview to show customers how Pixel order status will appear after checkout.</p>
                    </div>
                    <div>
                        <small>Estimated Completion</small>
                        <h2>3-5 days</h2>
                    </div>
                </div>
                <div class="timeline-wrap">
                    <div class="timeline">
                        <?php
                        $demo_steps = ['Quote Requested', 'Under Review', 'Approved', 'In Production', 'Quality Check', 'Ready for Pickup', 'Out for Delivery', 'Completed'];
                        foreach ($demo_steps as $index => $step_label) :
                            $step_class = $index < 3 ? 'done' : ($index === 3 ? 'current' : '');
                            ?>
                            <div class="timeline-step <?php echo esc_attr($step_class); ?>">
                                <?php echo esc_html($step_label); ?><br>
                                <small><?php echo $index < 3 ? 'Complete' : ($index === 3 ? 'Current stage' : 'Pending'); ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="portal-cards tracking-details-grid">
                <div class="portal-card">
                    <h3>Order Items</h3>
                    <ul class="tracking-list">
                        <li><span>Retail Window Vinyls</span><strong>x 2</strong></li>
                        <li><span>QR Code Stickers</span><strong>x 500</strong></li>
                    </ul>
                </div>
                <div class="portal-card">
                    <h3>Associated Files</h3>
                    <ul class="tracking-list">
                        <li><span>window-vinyl-final.pdf</span><strong>Approved</strong></li>
                        <li><span>qr-sticker-sheet.pdf</span><strong>Under Review</strong></li>
                    </ul>
                </div>
                <div class="portal-card">
                    <h3>Shipping Details</h3>
                    <p><strong>Local Pickup</strong></p>
                    <p>Pixel production desk<br>123 Print Avenue, Suite 100</p>
                    <p>Tracking number pending.</p>
                </div>
            </div>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    public function register_admin_pages(): void
    {
        add_submenu_page(
            'edit.php?post_type=pixel_quote',
            'Pixel Reports',
            'Reports',
            'manage_options',
            'pixel-reports',
            [$this, 'render_reports_page']
        );
    }

    public function render_reports_page(): void
    {
        echo '<div class="wrap"><h1>Pixel Reports</h1><p>Placeholder analytics dashboard for total revenue, pending quotes, active orders, new files, urgent tasks, and weekly trends.</p></div>';
    }

    public function register_artwork_meta_boxes(): void
    {
        add_meta_box('pixel_artwork_details', 'Artwork Details', [$this, 'render_artwork_meta_box'], 'pixel_artwork', 'normal', 'high');
    }

    public function render_artwork_meta_box(WP_Post $post): void
    {
        wp_nonce_field('pixel_save_artwork_meta', 'pixel_artwork_meta_nonce');
        $fields = [
            '_pixel_upload_name'  => ['label' => 'Customer Name', 'type' => 'text'],
            '_pixel_upload_email' => ['label' => 'Customer Email', 'type' => 'email'],
            '_pixel_order_number' => ['label' => 'Order / Quote Number', 'type' => 'text'],
            '_pixel_project_name' => ['label' => 'Project Name', 'type' => 'text'],
        ];

        echo '<table class="form-table">';
        foreach ($fields as $key => $field) {
            $value = get_post_meta($post->ID, $key, true);
            printf(
                '<tr><th><label for="%1$s">%2$s</label></th><td><input class="regular-text" type="%3$s" id="%1$s" name="%1$s" value="%4$s"></td></tr>',
                esc_attr($key),
                esc_html($field['label']),
                esc_attr($field['type']),
                esc_attr((string) $value)
            );
        }

        $current_status = $this->normalize_artwork_status((string) get_post_meta($post->ID, '_pixel_artwork_status', true));
        echo '<tr><th><label for="_pixel_artwork_status">Review Status</label></th><td><select id="_pixel_artwork_status" name="_pixel_artwork_status">';
        foreach ($this->get_artwork_statuses() as $status_key => $status_label) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($status_key),
                selected($current_status, $status_key, false),
                esc_html($status_label)
            );
        }
        echo '</select></td></tr>';

        $download_url = $this->get_artwork_download_url($post->ID);
        echo '<tr><th>Artwork File</th><td>';
        if ($download_url !== '') {
            printf(
                '<a class="button button-secondary" href="%1$s">Download %2$s</a>',
                esc_url($download_url),
                esc_html((string) get_post_meta($post->ID, '_pixel_original_filename', true))
            );
        } else {
            echo '<em>No file is attached.</em>';
        }
        echo '</td></tr>';
        echo '</table>';
    }

    public function save_artwork_meta(int $post_id, WP_Post $post): void
    {
        if (!isset($_POST['pixel_artwork_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pixel_artwork_meta_nonce'])), 'pixel_save_artwork_meta')) {
            return;
        }

        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || !current_user_can('edit_post', $post_id)) {
            return;
        }

        $text_fields = ['_pixel_upload_name', '_pixel_order_number', '_pixel_project_name'];
        foreach ($text_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
            }
        }

        if (isset($_POST['_pixel_upload_email'])) {
            $email = sanitize_email(wp_unslash($_POST['_pixel_upload_email']));
            if (is_email($email)) {
                update_post_meta($post_id, '_pixel_upload_email', $email);
            }
        }

        if (isset($_POST['_pixel_artwork_status'])) {
            $status = sanitize_title((string) wp_unslash($_POST['_pixel_artwork_status']));
            if (isset($this->get_artwork_statuses()[$status])) {
                update_post_meta($post_id, '_pixel_artwork_status', $status);
            }
        }
    }

    public function register_artwork_admin_columns(array $columns): array
    {
        return [
            'cb'              => $columns['cb'] ?? '<input type="checkbox">',
            'title'           => 'Artwork Record',
            'pixel_customer'  => 'Customer',
            'pixel_reference' => 'Order / Quote',
            'pixel_project'   => 'Project',
            'pixel_file'      => 'File',
            'pixel_status'    => 'Status',
            'date'            => $columns['date'] ?? 'Date',
        ];
    }

    public function render_artwork_admin_column(string $column, int $post_id): void
    {
        if ($column === 'pixel_customer') {
            $name = get_post_meta($post_id, '_pixel_upload_name', true);
            $email = get_post_meta($post_id, '_pixel_upload_email', true);
            echo $name !== '' ? esc_html((string) $name) : '&mdash;';
            if ($email !== '') {
                echo '<br><a href="mailto:' . esc_attr((string) $email) . '">' . esc_html((string) $email) . '</a>';
            }
            return;
        }

        if ($column === 'pixel_file') {
            $download_url = $this->get_artwork_download_url($post_id);
            $filename = get_post_meta($post_id, '_pixel_original_filename', true);
            if ($download_url !== '') {
                echo '<a href="' . esc_url($download_url) . '">' . esc_html((string) $filename) . '</a>';
            } else {
                echo '&mdash;';
            }
            return;
        }

        $values = [
            'pixel_reference' => get_post_meta($post_id, '_pixel_order_number', true),
            'pixel_project'   => get_post_meta($post_id, '_pixel_project_name', true),
            'pixel_status'    => $this->get_artwork_status_label((string) get_post_meta($post_id, '_pixel_artwork_status', true)),
        ];

        if (isset($values[$column])) {
            echo $values[$column] !== '' ? esc_html((string) $values[$column]) : '&mdash;';
        }
    }

    public function handle_artwork_download(): void
    {
        $post_id = isset($_GET['artwork_id']) ? absint($_GET['artwork_id']) : 0;
        $nonce = isset($_GET['_wpnonce']) ? sanitize_text_field(wp_unslash($_GET['_wpnonce'])) : '';

        if ($post_id < 1 || !wp_verify_nonce($nonce, 'pixel_download_artwork_' . $post_id)) {
            wp_die('Invalid artwork download request.', 'Artwork Download', ['response' => 403]);
        }

        if (!$this->current_user_can_access_artwork($post_id)) {
            wp_die('You do not have permission to download this artwork.', 'Artwork Download', ['response' => 403]);
        }

        $file_path = (string) get_post_meta($post_id, '_pixel_private_file_path', true);
        $private_directory = realpath($this->get_private_artwork_directory());
        $real_file_path = $file_path !== '' ? realpath($file_path) : false;

        if ($private_directory === false || $real_file_path === false || !str_starts_with($real_file_path, trailingslashit($private_directory)) || !is_file($real_file_path)) {
            wp_die('The artwork file is no longer available.', 'Artwork Download', ['response' => 404]);
        }

        $filename = sanitize_file_name((string) get_post_meta($post_id, '_pixel_original_filename', true));
        $mime_type = sanitize_mime_type((string) get_post_meta($post_id, '_pixel_artwork_mime_type', true));
        if ($filename === '') {
            $filename = basename($real_file_path);
        }
        if ($mime_type === '') {
            $mime_type = 'application/octet-stream';
        }

        nocache_headers();
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . (string) filesize($real_file_path));
        header('X-Content-Type-Options: nosniff');
        readfile($real_file_path); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
        exit;
    }

    public function delete_private_artwork_file(int $post_id): void
    {
        if (!in_array(get_post_type($post_id), ['pixel_artwork', 'pixel_quote', 'attachment'], true)) {
            return;
        }

        $file_path = (string) get_post_meta($post_id, '_pixel_private_file_path', true);
        if ($file_path !== '' && is_file($file_path)) {
            wp_delete_file($file_path);
        }

        if (get_post_type($post_id) !== 'attachment') {
            $attachment_id = (int) get_post_meta($post_id, '_pixel_artwork_attachment_id', true);
            if ($attachment_id > 0 && get_post_type($attachment_id) === 'attachment') {
                wp_delete_attachment($attachment_id, true);
            }
        }
    }

    public function register_quote_meta_boxes(): void
    {
        add_meta_box('pixel_quote_details', 'Quote Details', [$this, 'render_quote_meta_box'], 'pixel_quote', 'normal', 'high');
    }

    public function render_quote_meta_box(WP_Post $post): void
    {
        wp_nonce_field('pixel_save_quote_meta', 'pixel_quote_meta_nonce');
        $fields = [
            '_pixel_customer_name'    => 'Customer Name',
            '_pixel_customer_email'   => 'Customer Email',
            '_pixel_customer_company' => 'Company',
            '_pixel_customer_phone'   => 'Phone',
            '_pixel_product_type'     => 'Product Type',
            '_pixel_quantity'         => 'Quantity',
            '_pixel_quantities'       => 'Quantity Breaks',
            '_pixel_width'            => 'Finish Width',
            '_pixel_height'           => 'Finish Height',
            '_pixel_size'             => 'Size',
            '_pixel_stock'            => 'Stock / Paper',
            '_pixel_colors'           => 'Colors',
            '_pixel_material'         => 'Material',
            '_pixel_finish'           => 'Finish',
            '_pixel_finishing'        => 'Stakes / Finishing',
            '_pixel_artwork_status'   => 'Artwork Status',
            '_pixel_due_date'         => 'Deadline',
            '_pixel_deliver_to'       => 'Deliver To',
            '_pixel_contact_method'   => 'Contact Method',
            '_pixel_delivery_method'  => 'Delivery Method',
        ];

        echo '<table class="form-table">';
        foreach ($fields as $key => $label) {
            $value = get_post_meta($post->ID, $key, true);
            printf(
                '<tr><th><label for="%1$s">%2$s</label></th><td><input class="regular-text" id="%1$s" name="%1$s" value="%3$s"></td></tr>',
                esc_attr($key),
                esc_html($label),
                esc_attr((string) $value)
            );
        }

        $current_status = $this->normalize_quote_status((string) get_post_meta($post->ID, '_pixel_quote_status', true));
        echo '<tr><th><label for="_pixel_quote_status">Quote Status</label></th><td><select id="_pixel_quote_status" name="_pixel_quote_status">';
        foreach ($this->get_quote_statuses() as $status_key => $status_label) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($status_key),
                selected($current_status, $status_key, false),
                esc_html($status_label)
            );
        }
        echo '</select></td></tr>';

        $download_url = $this->get_artwork_download_url($post->ID);
        if ($download_url !== '') {
            printf(
                '<tr><th>Reference File</th><td><a class="button button-secondary" href="%1$s">Download %2$s</a></td></tr>',
                esc_url($download_url),
                esc_html((string) get_post_meta($post->ID, '_pixel_original_filename', true))
            );
        }
        echo '</table>';
    }

    public function save_quote_meta(int $post_id, WP_Post $post): void
    {
        if (!isset($_POST['pixel_quote_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pixel_quote_meta_nonce'])), 'pixel_save_quote_meta')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = [
            '_pixel_customer_name',
            '_pixel_customer_email',
            '_pixel_customer_company',
            '_pixel_customer_phone',
            '_pixel_product_type',
            '_pixel_quantity',
            '_pixel_quantities',
            '_pixel_width',
            '_pixel_height',
            '_pixel_size',
            '_pixel_stock',
            '_pixel_colors',
            '_pixel_material',
            '_pixel_finish',
            '_pixel_finishing',
            '_pixel_artwork_status',
            '_pixel_due_date',
            '_pixel_deliver_to',
            '_pixel_contact_method',
            '_pixel_delivery_method',
        ];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
            }
        }

        if (isset($_POST['_pixel_quote_status'])) {
            $status = $this->normalize_quote_status((string) wp_unslash($_POST['_pixel_quote_status']));
            if (isset($this->get_quote_statuses()[$status])) {
                update_post_meta($post_id, '_pixel_quote_status', $status);
            }
        }
    }

    public function register_quote_admin_columns(array $columns): array
    {
        return [
            'cb'             => $columns['cb'] ?? '<input type="checkbox">',
            'title'          => 'Quote Request',
            'pixel_customer' => 'Customer',
            'pixel_product'  => 'Product',
            'pixel_quantity' => 'Quantity',
            'pixel_status'   => 'Status',
            'date'           => $columns['date'] ?? 'Date',
        ];
    }

    public function render_quote_admin_column(string $column, int $post_id): void
    {
        if ($column === 'pixel_customer') {
            $name  = get_post_meta($post_id, '_pixel_customer_name', true);
            $email = get_post_meta($post_id, '_pixel_customer_email', true);
            echo esc_html((string) $name);
            if ($email !== '') {
                echo '<br><a href="mailto:' . esc_attr((string) $email) . '">' . esc_html((string) $email) . '</a>';
            }
            return;
        }

        $meta_keys = [
            'pixel_product'  => '_pixel_product_type',
            'pixel_quantity' => '_pixel_quantity',
            'pixel_status'   => '_pixel_quote_status',
        ];

        if (isset($meta_keys[$column])) {
            $value = get_post_meta($post_id, $meta_keys[$column], true);
            if ($column === 'pixel_status') {
                $value = $this->get_quote_status_label((string) $value);
            }
            echo $value !== '' ? esc_html((string) $value) : '&mdash;';
        }
    }

    public function render_quote_status_filter(string $post_type): void
    {
        if (!in_array($post_type, ['pixel_quote', 'pixel_artwork'], true)) {
            return;
        }

        $is_quote = $post_type === 'pixel_quote';
        $query_key = $is_quote ? 'pixel_quote_status' : 'pixel_artwork_status';
        $statuses = $is_quote ? $this->get_quote_statuses() : $this->get_artwork_statuses();
        $selected_status = isset($_GET[$query_key])
            ? sanitize_title((string) wp_unslash($_GET[$query_key]))
            : '';

        echo '<select name="' . esc_attr($query_key) . '">';
        echo '<option value="">All ' . ($is_quote ? 'quote' : 'artwork') . ' statuses</option>';
        foreach ($statuses as $status_key => $status_label) {
            printf(
                '<option value="%1$s" %2$s>%3$s</option>',
                esc_attr($status_key),
                selected($selected_status, $status_key, false),
                esc_html($status_label)
            );
        }
        echo '</select>';
    }

    public function filter_quotes_by_status(WP_Query $query): void
    {
        $post_type = $query->get('post_type');
        if (!is_admin() || !$query->is_main_query() || !in_array($post_type, ['pixel_quote', 'pixel_artwork'], true)) {
            return;
        }

        $is_quote = $post_type === 'pixel_quote';
        $query_key = $is_quote ? 'pixel_quote_status' : 'pixel_artwork_status';
        $meta_key = $is_quote ? '_pixel_quote_status' : '_pixel_artwork_status';
        $statuses = $is_quote ? $this->get_quote_statuses() : $this->get_artwork_statuses();
        $status = isset($_GET[$query_key])
            ? sanitize_title((string) wp_unslash($_GET[$query_key]))
            : '';

        if ($status !== '' && isset($statuses[$status])) {
            $query->set('meta_key', $meta_key);
            $query->set('meta_value', $status);
        }
    }

    private function get_quote_statuses(): array
    {
        return [
            'new'                => 'New',
            'under-review'       => 'Under Review',
            'quoted'             => 'Quoted',
            'approved'           => 'Approved',
            'rejected'           => 'Rejected',
            'converted-to-order' => 'Converted to Order',
        ];
    }

    private function get_order_project_name($order): string
    {
        if (!is_object($order) || !method_exists($order, 'get_items')) {
            return 'Print Order';
        }

        $item_names = [];
        foreach ($order->get_items() as $item) {
            if (is_object($item) && method_exists($item, 'get_name')) {
                $item_names[] = $item->get_name();
            }
            if (count($item_names) === 2) {
                break;
            }
        }

        return $item_names !== [] ? implode(', ', $item_names) : 'Print Order';
    }

    private function get_customer_records(string $post_type, string $email_meta_key, int $user_id, string $customer_email): array
    {
        $query_args = [
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => 20,
            'orderby'        => 'modified',
            'order'          => 'DESC',
        ];
        $records = $user_id > 0
            ? get_posts($query_args + ['author' => $user_id])
            : [];

        if ($customer_email !== '') {
            $email_records = get_posts(
                $query_args + [
                    'meta_key'   => $email_meta_key,
                    'meta_value' => $customer_email,
                ]
            );

            foreach ($email_records as $record) {
                $records[$record->ID] = $record;
            }
        }

        $records_by_id = [];
        foreach ($records as $record) {
            if ($record instanceof WP_Post) {
                $records_by_id[$record->ID] = $record;
            }
        }

        usort(
            $records_by_id,
            static fn(WP_Post $first, WP_Post $second): int =>
                (int) get_post_modified_time('U', true, $second) <=> (int) get_post_modified_time('U', true, $first)
        );

        return array_slice($records_by_id, 0, 20);
    }

    private function find_woocommerce_order(string $order_number)
    {
        $normalized = trim(ltrim($order_number, '#'));
        $order_id = 0;

        if (ctype_digit($normalized)) {
            $order_id = absint($normalized);
        } elseif (preg_match('/(\d+)$/', $normalized, $matches)) {
            $order_id = absint($matches[1]);
        }

        if ($order_id > 0) {
            $order = wc_get_order($order_id);
            if ($order) {
                return $order;
            }
        }

        if (!function_exists('wc_get_orders')) {
            return null;
        }

        $orders = wc_get_orders([
            'limit'      => 1,
            'return'     => 'objects',
            'meta_query' => [
                [
                    'key'   => '_order_number',
                    'value' => $normalized,
                ],
            ],
        ]);

        return $orders[0] ?? null;
    }

    private function can_view_tracked_order($order, string $billing_email): bool
    {
        if (!is_object($order) || !method_exists($order, 'get_billing_email')) {
            return false;
        }

        if (current_user_can('manage_woocommerce')) {
            return true;
        }

        if (is_user_logged_in()) {
            $customer_id = method_exists($order, 'get_customer_id') ? (int) $order->get_customer_id() : 0;
            if ($customer_id > 0 && $customer_id === get_current_user_id()) {
                return true;
            }
        }

        $order_email = strtolower((string) $order->get_billing_email());
        $submitted_email = strtolower(sanitize_email($billing_email));
        return $order_email !== '' && $submitted_email !== '' && hash_equals($order_email, $submitted_email);
    }

    private function get_order_timeline_position(string $status): int
    {
        $positions = [
            'pending'         => 0,
            'on-hold'         => 0,
            'processing'      => 1,
            'artwork-review'  => 1,
            'proof-needed'    => 2,
            'in-production'   => 3,
            'quality-check'   => 4,
            'ready-pickup'    => 5,
            'print-shipped'   => 6,
            'out-delivery'    => 6,
            'print-delivered' => 7,
            'completed'       => 7,
        ];

        return $positions[$status] ?? 0;
    }

    private function get_order_artwork_files($order): array
    {
        if (!is_object($order) || !method_exists($order, 'get_order_number')) {
            return [];
        }

        $order_number = (string) $order->get_order_number();
        $references = array_values(array_unique([
            $order_number,
            'PX-' . $order_number,
            'ORD-' . $order_number,
            '#' . $order_number,
        ]));
        $meta_query = ['relation' => 'OR'];
        foreach ($references as $reference) {
            $meta_query[] = [
                'key'   => '_pixel_order_number',
                'value' => $reference,
            ];
        }

        return get_posts([
            'post_type'      => 'pixel_artwork',
            'post_status'    => 'publish',
            'posts_per_page' => 20,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'meta_query'     => $meta_query,
        ]);
    }

    private function normalize_quote_status(string $status): string
    {
        $normalized = sanitize_title($status);
        return isset($this->get_quote_statuses()[$normalized]) ? $normalized : 'new';
    }

    private function get_quote_status_label(string $status): string
    {
        $normalized = $this->normalize_quote_status($status);
        return $this->get_quote_statuses()[$normalized];
    }

    private function get_artwork_statuses(): array
    {
        return [
            'uploaded'       => 'Uploaded',
            'under-review'   => 'Under Review',
            'approved'       => 'Approved',
            'needs-revision' => 'Needs Revision',
            'rejected'       => 'Rejected',
        ];
    }

    private function normalize_artwork_status(string $status): string
    {
        $normalized = sanitize_title($status);
        return isset($this->get_artwork_statuses()[$normalized]) ? $normalized : 'uploaded';
    }

    private function get_artwork_status_label(string $status): string
    {
        $normalized = $this->normalize_artwork_status($status);
        return $this->get_artwork_statuses()[$normalized];
    }

    private function get_artwork_download_url(int $post_id): string
    {
        $file_path = (string) get_post_meta($post_id, '_pixel_private_file_path', true);
        if ($file_path === '' || !is_file($file_path)) {
            return '';
        }

        return wp_nonce_url(
            add_query_arg(
                [
                    'action'     => 'pixel_download_artwork',
                    'artwork_id' => $post_id,
                ],
                admin_url('admin-post.php')
            ),
            'pixel_download_artwork_' . $post_id
        );
    }

    private function current_user_can_access_artwork(int $post_id): bool
    {
        if (!in_array(get_post_type($post_id), ['pixel_artwork', 'pixel_quote'], true)) {
            return false;
        }

        if (current_user_can('edit_post', $post_id)) {
            return true;
        }

        if (!is_user_logged_in()) {
            return false;
        }

        $user = wp_get_current_user();
        $author_id = (int) get_post_field('post_author', $post_id);
        if ($author_id > 0 && $author_id === (int) $user->ID) {
            return true;
        }

        $email_meta_key = get_post_type($post_id) === 'pixel_quote' ? '_pixel_customer_email' : '_pixel_upload_email';
        $record_email = strtolower((string) get_post_meta($post_id, $email_meta_key, true));
        return $record_email !== '' && hash_equals($record_email, strtolower((string) $user->user_email));
    }
}
