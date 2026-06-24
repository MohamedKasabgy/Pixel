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
        add_action('save_post_pixel_quote', [$this, 'save_quote_meta'], 10, 2);
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
            'wc-ready-pickup'   => 'Ready for Pickup',
            'wc-print-shipped'  => 'Shipped',
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
                $new['wc-ready-pickup']    = 'Ready for Pickup';
                $new['wc-print-shipped']   = 'Shipped';
                $new['wc-print-delivered'] = 'Delivered';
            }
        }

        return $new;
    }

    public function render_quote_form(): string
    {
        $message = '';

<<<<<<< Updated upstream
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pixel_quote_nonce'])) {
            $message = $this->handle_quote_submission();
        }

=======
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

>>>>>>> Stashed changes
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
                <label>Full Name<input name="pixel_name" required></label>
                <label>Email<input type="email" name="pixel_email" required></label>
            </div>
            <div class="form-grid">
                <label>Company<input name="pixel_company"></label>
                <label>Phone<input name="pixel_phone"></label>
            </div>
            <label>Product Type
                <select name="pixel_product_type" required>
                    <option value="Business Cards">Business Cards</option>
                    <option value="Large Format Banner">Large Format Banner</option>
                    <option value="Acrylic Sign">Acrylic Sign</option>
                    <option value="Vehicle Wrap">Vehicle Wrap</option>
                    <option value="Apparel Printing">Apparel Printing</option>
                    <option value="Other">Other</option>
                </select>
            </label>
            <div class="form-step-heading">
                <span>Step 2</span>
                <h2>Select Product Options</h2>
            </div>
            <div class="form-grid">
<<<<<<< Updated upstream
                <label>Quantity<input type="number" min="1" name="pixel_quantity" required></label>
                <label>Needed By<input type="date" name="pixel_due_date"></label>
            </div>
            <label>Project Details<textarea name="pixel_details" placeholder="Size, material, finish, delivery notes, and any special requirements."></textarea></label>
            <label>Artwork / Reference File<input type="file" name="pixel_artwork" accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tif,.tiff,.zip"></label>
=======
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
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
        $name    = sanitize_text_field(wp_unslash($_POST['pixel_name'] ?? ''));
        $email   = sanitize_email(wp_unslash($_POST['pixel_email'] ?? ''));
        $company = sanitize_text_field(wp_unslash($_POST['pixel_company'] ?? ''));
        $product = sanitize_text_field(wp_unslash($_POST['pixel_product_type'] ?? ''));
        $details = sanitize_textarea_field(wp_unslash($_POST['pixel_details'] ?? ''));
        $qty     = absint($_POST['pixel_quantity'] ?? 0);

        if ($name === '' || $email === '' || $product === '' || $qty < 1) {
            return '<div class="notice error">Please complete the required fields.</div>';
        }

=======
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
>>>>>>> Stashed changes
        $post_id = wp_insert_post([
            'post_type'    => 'pixel_quote',
            'post_title'   => sprintf('Quote Request - %s - %s', $product, $name),
            'post_content' => $details,
            'post_status'  => 'publish',
        ], true);

        if (is_wp_error($post_id)) {
            return '<div class="notice error">Could not create quote request. Please try again.</div>';
        }

        update_post_meta($post_id, '_pixel_customer_name', $name);
        update_post_meta($post_id, '_pixel_customer_email', $email);
        update_post_meta($post_id, '_pixel_customer_company', $company);
        update_post_meta($post_id, '_pixel_product_type', $product);
        update_post_meta($post_id, '_pixel_quantity', $qty);
<<<<<<< Updated upstream
        update_post_meta($post_id, '_pixel_quote_status', 'New');

        $this->maybe_handle_upload($post_id, 'quote');
=======
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
>>>>>>> Stashed changes

        if (is_user_logged_in()) {
            wp_safe_redirect(add_query_arg('quote_success', '1', home_url('/client-dashboard/')));
            exit;
        }

        return '<div class="notice">Quote request submitted. Our team will review your project and contact you soon.</div>';
    }

    public function render_artwork_upload(): string
    {
        $message = '';

<<<<<<< Updated upstream
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pixel_upload_nonce'])) {
            $message = $this->handle_artwork_upload();
        }

=======
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

>>>>>>> Stashed changes
        ob_start();
        echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <form class="upload-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('pixel_artwork_upload', 'pixel_upload_nonce'); ?>
            <div class="form-grid">
                <label>Order / Quote Number<input name="pixel_order_number" placeholder="PX-8492 or QT-4402" required></label>
                <label>Email<input type="email" name="pixel_upload_email" required></label>
            </div>
            <label>Notes<textarea name="pixel_upload_notes" placeholder="Tell us what this file is for."></textarea></label>
            <label>Artwork File<input type="file" name="pixel_artwork" accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tif,.tiff,.zip" required></label>
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

        $order_number = sanitize_text_field(wp_unslash($_POST['pixel_order_number'] ?? ''));
        $email        = sanitize_email(wp_unslash($_POST['pixel_upload_email'] ?? ''));
        $notes        = sanitize_textarea_field(wp_unslash($_POST['pixel_upload_notes'] ?? ''));

        if ($order_number === '' || $email === '') {
            return '<div class="notice error">Please enter your order number and email.</div>';
        }

        $post_id = wp_insert_post([
            'post_type'    => 'pixel_artwork',
            'post_title'   => sprintf('Artwork Upload - %s', $order_number),
            'post_content' => $notes,
            'post_status'  => 'publish',
        ], true);

        if (is_wp_error($post_id)) {
            return '<div class="notice error">Could not create upload record. Please try again.</div>';
        }

        update_post_meta($post_id, '_pixel_order_number', $order_number);
        update_post_meta($post_id, '_pixel_upload_email', $email);
        $this->maybe_handle_upload($post_id, 'artwork');

        if (is_user_logged_in()) {
            wp_safe_redirect(add_query_arg('upload_success', '1', home_url('/client-dashboard/')));
            exit;
        }

        return '<div class="notice">Artwork uploaded. Our pre-press team will review the file.</div>';
    }

    private function maybe_handle_upload(int $post_id, string $context): void
    {
        if (empty($_FILES['pixel_artwork']['name'])) {
            return;
        }

        $allowed = ['pdf', 'ai', 'eps', 'psd', 'jpg', 'jpeg', 'png', 'tif', 'tiff', 'zip'];
        $filename = sanitize_file_name((string) $_FILES['pixel_artwork']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            update_post_meta($post_id, '_pixel_upload_error', 'Unsupported file type.');
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_id = media_handle_upload('pixel_artwork', $post_id);

        if (!is_wp_error($attachment_id)) {
            update_post_meta($post_id, '_pixel_artwork_attachment_id', $attachment_id);
            update_post_meta($post_id, '_pixel_upload_context', $context);
        }
    }

    public function render_client_portal(): string
    {
        ob_start();
        ?>
        <div class="portal-cards">
            <div class="portal-card"><span>Active Quotes</span><div class="metric">3</div><p>awaiting approval</p></div>
            <div class="portal-card"><span>Orders in Production</span><div class="metric">2</div><p>estimated delivery this week</p></div>
            <div class="portal-card dark-card"><h3>Need Assistance?</h3><p>Connect with your dedicated account manager.</p></div>
        </div>
        <table class="client-table">
            <thead><tr><th>Job ID</th><th>Project Name</th><th>Status</th><th>Date Updated</th><th>Action</th></tr></thead>
            <tbody>
                <tr><td>#ORD-9021</td><td><strong>Retail Window Vinyls - Summer Promo</strong></td><td><span class="status-pill">Printing</span></td><td>Today, 10:42 AM</td><td>View</td></tr>
                <tr><td>#QT-4402</td><td><strong>Corporate Fleet Vehicle Wraps</strong></td><td><span class="status-pill neutral">Quote Ready</span></td><td>Yesterday, 3:15 PM</td><td>View</td></tr>
                <tr><td>#ORD-8995</td><td><strong>Trade Show Booth Backdrop & Banners</strong></td><td><span class="status-pill neutral">Delivered</span></td><td>Oct 24, 2024</td><td>View</td></tr>
            </tbody>
        </table>
        <?php
        return ob_get_clean();
    }

    public function render_order_tracker(): string
    {
        ob_start();
        ?>
        <div class="tracking-card">
            <div class="tracking-top">
                <div><h2>Order #PX-8492 <span class="status-pill">In Production</span></h2><p>Placed on October 24, 2024</p></div>
                <div><small>Estimated Delivery</small><h2>Oct 30 - Nov 1</h2></div>
            </div>
            <div class="timeline">
                <div class="timeline-step done">Order Placed<br><small>Oct 24, 09:41 AM</small></div>
                <div class="timeline-step current">In Production<br><small>Currently printing</small></div>
                <div class="timeline-step">Shipped<br><small>Pending</small></div>
                <div class="timeline-step">Out for Delivery<br><small>Pending</small></div>
                <div class="timeline-step">Delivered<br><small>Pending</small></div>
            </div>
<<<<<<< Updated upstream
        </div>
        <div class="portal-cards two-col">
            <div class="portal-card"><h3>Associated Files</h3><p>Large_Format_Banner_v2.pdf • Approved</p><p>Vector_Logo_Assets.ai • Approved</p></div>
            <div class="portal-card"><h3>Shipping Details</h3><p><strong>FedEx Ground</strong><br>Tracking number not yet assigned.</p></div>
        </div>
=======
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
>>>>>>> Stashed changes
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
            '_pixel_product_type'     => 'Product Type',
            '_pixel_quantity'         => 'Quantity',
<<<<<<< Updated upstream
            '_pixel_quote_status'     => 'Quote Status',
=======
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
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
        $fields = ['_pixel_customer_name', '_pixel_customer_email', '_pixel_customer_company', '_pixel_product_type', '_pixel_quantity', '_pixel_quote_status'];
=======
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
>>>>>>> Stashed changes
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
            }
        }
    }
}
