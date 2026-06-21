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
        add_action('restrict_manage_posts', [$this, 'render_quote_status_filter']);
        add_action('pre_get_posts', [$this, 'filter_quotes_by_status']);
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
            <label>Artwork / Reference File
                <input type="file" name="pixel_artwork" accept=".pdf,.ai,.eps,.psd,.jpg,.jpeg,.png,.tif,.tiff,.zip" data-max-size="<?php echo esc_attr((string) $this->get_max_artwork_upload_size()); ?>">
                <small>Optional. Maximum file size: <?php echo esc_html(size_format($this->get_max_artwork_upload_size())); ?>.</small>
            </label>
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
        update_post_meta($post_id, '_pixel_quote_status', 'new');

        $upload_result = $this->maybe_handle_upload($post_id, 'quote');
        if (is_wp_error($upload_result)) {
            update_post_meta($post_id, '_pixel_upload_error', $upload_result->get_error_message());
            return '<div class="notice">Quote request submitted, but the optional file could not be uploaded: '
                . esc_html($upload_result->get_error_message())
                . '. You can send it from the Upload Artwork page.</div>';
        }

        return '<div class="notice">Quote request submitted. Our team will review your project and contact you soon.</div>';
    }

    public function render_artwork_upload(): string
    {
        $message = '';

        if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['pixel_upload_nonce'])) {
            $message = $this->handle_artwork_upload();
        }

        $requested_project = isset($_GET['product'])
            ? sanitize_text_field(str_replace('-', ' ', (string) wp_unslash($_GET['product'])))
            : '';
        $values = [
            'name'         => isset($_POST['pixel_upload_name']) ? sanitize_text_field(wp_unslash($_POST['pixel_upload_name'])) : '',
            'email'        => isset($_POST['pixel_upload_email']) ? sanitize_email(wp_unslash($_POST['pixel_upload_email'])) : '',
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
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $attachment_id = media_handle_upload(
            'pixel_artwork',
            $post_id,
            [],
            [
                'test_form' => false,
                'mimes'     => $this->get_allowed_artwork_mimes(),
            ]
        );

        if (is_wp_error($attachment_id)) {
            return new WP_Error('pixel_upload_failed', 'The artwork file could not be stored: ' . $attachment_id->get_error_message());
        }

        update_post_meta($post_id, '_pixel_artwork_attachment_id', $attachment_id);
        update_post_meta($post_id, '_pixel_upload_context', $context);
        update_post_meta($post_id, '_pixel_original_filename', sanitize_file_name((string) $_FILES['pixel_artwork']['name']));

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
        if ($post_type !== 'pixel_quote') {
            return;
        }

        $selected_status = isset($_GET['pixel_quote_status'])
            ? sanitize_title((string) wp_unslash($_GET['pixel_quote_status']))
            : '';

        echo '<select name="pixel_quote_status">';
        echo '<option value="">All quote statuses</option>';
        foreach ($this->get_quote_statuses() as $status_key => $status_label) {
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
        if (!is_admin() || !$query->is_main_query() || $query->get('post_type') !== 'pixel_quote') {
            return;
        }

        $status = isset($_GET['pixel_quote_status'])
            ? sanitize_title((string) wp_unslash($_GET['pixel_quote_status']))
            : '';

        if ($status !== '' && isset($this->get_quote_statuses()[$status])) {
            $query->set('meta_key', '_pixel_quote_status');
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
}
