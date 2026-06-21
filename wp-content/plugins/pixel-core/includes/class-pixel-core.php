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
        add_filter('manage_pixel_quote_posts_columns', [$this, 'register_quote_admin_columns']);
        add_action('manage_pixel_quote_posts_custom_column', [$this, 'render_quote_admin_column'], 10, 2);
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

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['pixel_quote_nonce'])) {
            $message = $this->handle_quote_submission();
        }

        $products = [
            'business-cards'      => 'Business Cards',
            'flyers'              => 'Flyers',
            'brochures'           => 'Brochures',
            'stickers'            => 'Stickers & Labels',
            'vinyl-banners'       => 'Vinyl Banners',
            'posters'             => 'Posters',
            'signage'             => 'Signs & Display Graphics',
            'vehicle-wraps'       => 'Vehicle Wraps',
            'apparel-printing'    => 'Apparel Printing',
            'packaging-boxes'     => 'Packaging Boxes',
            'custom-print-project'=> 'Other / Custom Print Project',
        ];
        $requested_product = isset($_GET['product']) ? sanitize_key(wp_unslash($_GET['product'])) : '';
        $selected_product  = isset($_POST['pixel_product_type'])
            ? sanitize_key(wp_unslash($_POST['pixel_product_type']))
            : $requested_product;

        if (!isset($products[$selected_product])) {
            $selected_product = '';
        }

        $values = [
            'name'            => isset($_POST['pixel_name']) ? sanitize_text_field(wp_unslash($_POST['pixel_name'])) : '',
            'email'           => isset($_POST['pixel_email']) ? sanitize_email(wp_unslash($_POST['pixel_email'])) : '',
            'company'         => isset($_POST['pixel_company']) ? sanitize_text_field(wp_unslash($_POST['pixel_company'])) : '',
            'phone'           => isset($_POST['pixel_phone']) ? sanitize_text_field(wp_unslash($_POST['pixel_phone'])) : '',
            'quantity'        => isset($_POST['pixel_quantity']) ? absint($_POST['pixel_quantity']) : '',
            'size'            => isset($_POST['pixel_size']) ? sanitize_text_field(wp_unslash($_POST['pixel_size'])) : '',
            'material'        => isset($_POST['pixel_material']) ? sanitize_text_field(wp_unslash($_POST['pixel_material'])) : '',
            'finish'          => isset($_POST['pixel_finish']) ? sanitize_text_field(wp_unslash($_POST['pixel_finish'])) : '',
            'due_date'        => isset($_POST['pixel_due_date']) ? sanitize_text_field(wp_unslash($_POST['pixel_due_date'])) : '',
            'delivery_method' => isset($_POST['pixel_delivery_method']) ? sanitize_key(wp_unslash($_POST['pixel_delivery_method'])) : '',
            'details'         => isset($_POST['pixel_details']) ? sanitize_textarea_field(wp_unslash($_POST['pixel_details'])) : '',
        ];

        ob_start();
        echo $message; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
        <form class="quote-form" method="post" enctype="multipart/form-data">
            <?php wp_nonce_field('pixel_quote_request', 'pixel_quote_nonce'); ?>
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
            <div class="form-grid">
                <label>Quantity<input type="number" min="1" name="pixel_quantity" value="<?php echo esc_attr((string) $values['quantity']); ?>" required></label>
                <label>Size<input name="pixel_size" value="<?php echo esc_attr((string) $values['size']); ?>" placeholder="e.g. 24 × 36 in or custom"></label>
            </div>
            <div class="form-grid">
                <label>Material<input name="pixel_material" value="<?php echo esc_attr((string) $values['material']); ?>" placeholder="e.g. Vinyl, cardstock, acrylic"></label>
                <label>Finish<input name="pixel_finish" value="<?php echo esc_attr((string) $values['finish']); ?>" placeholder="e.g. Matte, gloss, laminated"></label>
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
            <label>Project Notes<textarea name="pixel_details" placeholder="Describe the project, intended use, delivery notes, and any special requirements."><?php echo esc_textarea((string) $values['details']); ?></textarea></label>
            <label>Artwork / Reference File<input type="file" name="pixel_artwork" accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tif,.tiff,.zip"></label>
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
            'stickers'             => 'Stickers & Labels',
            'vinyl-banners'        => 'Vinyl Banners',
            'posters'              => 'Posters',
            'signage'              => 'Signs & Display Graphics',
            'vehicle-wraps'        => 'Vehicle Wraps',
            'apparel-printing'     => 'Apparel Printing',
            'packaging-boxes'      => 'Packaging Boxes',
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
        $size             = sanitize_text_field(wp_unslash($_POST['pixel_size'] ?? ''));
        $material         = sanitize_text_field(wp_unslash($_POST['pixel_material'] ?? ''));
        $finish           = sanitize_text_field(wp_unslash($_POST['pixel_finish'] ?? ''));
        $due_date         = sanitize_text_field(wp_unslash($_POST['pixel_due_date'] ?? ''));
        $delivery_key     = sanitize_key(wp_unslash($_POST['pixel_delivery_method'] ?? ''));
        $details          = sanitize_textarea_field(wp_unslash($_POST['pixel_details'] ?? ''));
        $qty              = absint($_POST['pixel_quantity'] ?? 0);

        if ($name === '' || !is_email($email) || $phone === '' || !isset($products[$product_key]) || $qty < 1) {
            return '<div class="notice error">Please complete the required fields.</div>';
        }

        if ($due_date !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $due_date)) {
            return '<div class="notice error">Please enter a valid project deadline.</div>';
        }

        if ($delivery_key !== '' && !isset($delivery_methods[$delivery_key])) {
            return '<div class="notice error">Please select a valid delivery method.</div>';
        }

        $product = $products[$product_key];
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
        update_post_meta($post_id, '_pixel_customer_phone', $phone);
        update_post_meta($post_id, '_pixel_product_key', $product_key);
        update_post_meta($post_id, '_pixel_product_type', $product);
        update_post_meta($post_id, '_pixel_quantity', $qty);
        update_post_meta($post_id, '_pixel_size', $size);
        update_post_meta($post_id, '_pixel_material', $material);
        update_post_meta($post_id, '_pixel_finish', $finish);
        update_post_meta($post_id, '_pixel_due_date', $due_date);
        update_post_meta($post_id, '_pixel_delivery_method', $delivery_key !== '' ? $delivery_methods[$delivery_key] : '');
        update_post_meta($post_id, '_pixel_quote_status', 'New');

        $this->maybe_handle_upload($post_id, 'quote');

        return '<div class="notice">Quote request submitted. Our team will review your project and contact you soon.</div>';
    }

    public function render_artwork_upload(): string
    {
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pixel_upload_nonce'])) {
            $message = $this->handle_artwork_upload();
        }

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
        </div>
        <div class="portal-cards two-col">
            <div class="portal-card"><h3>Associated Files</h3><p>Large_Format_Banner_v2.pdf • Approved</p><p>Vector_Logo_Assets.ai • Approved</p></div>
            <div class="portal-card"><h3>Shipping Details</h3><p><strong>FedEx Ground</strong><br>Tracking number not yet assigned.</p></div>
        </div>
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
            '_pixel_customer_phone'   => 'Phone',
            '_pixel_product_type'     => 'Product Type',
            '_pixel_quantity'         => 'Quantity',
            '_pixel_size'             => 'Size',
            '_pixel_material'         => 'Material',
            '_pixel_finish'           => 'Finish',
            '_pixel_due_date'         => 'Deadline',
            '_pixel_delivery_method'  => 'Delivery Method',
            '_pixel_quote_status'     => 'Quote Status',
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

        $fields = [
            '_pixel_customer_name',
            '_pixel_customer_email',
            '_pixel_customer_company',
            '_pixel_customer_phone',
            '_pixel_product_type',
            '_pixel_quantity',
            '_pixel_size',
            '_pixel_material',
            '_pixel_finish',
            '_pixel_due_date',
            '_pixel_delivery_method',
            '_pixel_quote_status',
        ];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
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
            echo $value !== '' ? esc_html((string) $value) : '&mdash;';
        }
    }
}
