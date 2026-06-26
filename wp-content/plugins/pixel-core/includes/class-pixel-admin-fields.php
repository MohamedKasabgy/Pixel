<?php
/**
 * Pixel Admin Fields — WooCommerce product tab for per-product print field config.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Pixel_Admin_Fields {

    public static function init(): void {
        add_filter( 'woocommerce_product_data_tabs',   [ self::class, 'register_tab' ] );
        add_action( 'woocommerce_product_data_panels', [ self::class, 'render_panel' ] );
        add_action( 'woocommerce_process_product_meta', [ self::class, 'save_fields' ] );
        add_action( 'admin_footer', [ self::class, 'inline_script' ] );
        add_action( 'admin_head',   [ self::class, 'inline_styles' ] );
    }

    /* ------------------------------------------------------------------ */
    /*  Tab registration                                                     */
    /* ------------------------------------------------------------------ */

    public static function register_tab( array $tabs ): array {
        $tabs['pixel_print_options'] = [
            'label'    => 'Pixel Print Options',
            'target'   => 'pixel_print_options_panel',
            'class'    => [],
            'priority' => 80,
        ];
        return $tabs;
    }

    /* ------------------------------------------------------------------ */
    /*  Panel HTML                                                           */
    /* ------------------------------------------------------------------ */

    public static function render_panel(): void {
        global $post;
        $fields = self::get_fields( (int) $post->ID );
        wp_nonce_field( 'pixel_save_product_fields', 'pixel_product_fields_nonce' );
        ?>
        <div id="pixel_print_options_panel" class="panel woocommerce_options_panel">
            <div class="options_group pixel-admin-fields-wrap">
                <p style="padding:12px 12px 0; color:#555;">
                    <strong>Pixel Print Options</strong> — configure the product fields shown before Add to Cart.
                    Mark required fields carefully; customers must complete them before checkout. Leave empty to use the global default fields.
                </p>

                <table class="widefat pixel-fields-table" style="margin:12px;">
                    <thead>
                        <tr>
                            <th style="width:22%">Label</th>
                            <th style="width:18%">Field Key</th>
                            <th style="width:11%">Type</th>
                            <th style="width:8%">Required?</th>
                            <th style="width:8%">Order</th>
                            <th>Options for select / checkbox fields (one per line)</th>
                            <th style="width:48px"></th>
                        </tr>
                    </thead>
                    <tbody id="pixel-fields-rows">
                        <?php foreach ( $fields as $i => $field ) : ?>
                        <tr class="pixel-field-row">
                            <td><input type="text"
                                    name="pixel_fields[<?php echo esc_attr( (string) $i ); ?>][label]"
                                    value="<?php echo esc_attr( $field['label'] ?? '' ); ?>"
                                    placeholder="e.g. Banner Size"
                                    class="pixel-label-input"
                                    style="width:100%"></td>
                            <td><input type="text"
                                    name="pixel_fields[<?php echo esc_attr( (string) $i ); ?>][key]"
                                    value="<?php echo esc_attr( $field['key'] ?? '' ); ?>"
                                    placeholder="pixel_banner_size"
                                    class="pixel-key-input"
                                    style="width:100%"></td>
                            <td>
                                <select name="pixel_fields[<?php echo esc_attr( (string) $i ); ?>][type]"
                                        class="pixel-type-select" style="width:100%">
                                    <?php foreach ( [ 'select', 'text', 'textarea', 'checkbox' ] as $type ) : ?>
                                    <option value="<?php echo esc_attr( $type ); ?>"
                                        <?php selected( $field['type'] ?? 'select', $type ); ?>>
                                        <?php echo esc_html( ucfirst( $type ) ); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="pixel_fields[<?php echo esc_attr( (string) $i ); ?>][required]"
                                        style="width:100%">
                                    <option value="1" <?php selected( ! empty( $field['required'] ), true ); ?>>Yes</option>
                                    <option value="0" <?php selected( ! empty( $field['required'] ), false ); ?>>No</option>
                                </select>
                            </td>
                            <td><input type="number"
                                    name="pixel_fields[<?php echo esc_attr( (string) $i ); ?>][sort_order]"
                                    value="<?php echo esc_attr( (string) ( $field['sort_order'] ?? $i ) ); ?>"
                                    style="width:56px" min="0"></td>
                            <td>
                                <textarea name="pixel_fields[<?php echo esc_attr( (string) $i ); ?>][options_raw]"
                                          rows="3"
                                          placeholder="Option A&#10;Option B&#10;Option C"
                                          class="pixel-options-textarea"
                                          style="width:100%;font-size:12px;"><?php
                                    $opts = $field['options'] ?? [];
                                    echo esc_textarea( is_array( $opts ) ? implode( "\n", $opts ) : $opts );
                                ?></textarea>
                            </td>
                            <td style="text-align:center;vertical-align:middle;">
                                <button type="button" class="button pixel-remove-row" title="Remove">✕</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <p style="padding:0 12px 12px;">
                    <button type="button" class="button button-secondary" id="pixel-add-field-row">
                        + Add Field
                    </button>
                </p>
            </div>
        </div>
        <?php
    }

    /* ------------------------------------------------------------------ */
    /*  Save                                                                 */
    /* ------------------------------------------------------------------ */

    public static function save_fields( int $post_id ): void {
        if (
            ! isset( $_POST['pixel_product_fields_nonce'] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST['pixel_product_fields_nonce'] ) ),
                'pixel_save_product_fields'
            )
        ) {
            return;
        }

        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        $raw    = isset( $_POST['pixel_fields'] ) && is_array( $_POST['pixel_fields'] )
                  ? $_POST['pixel_fields']  // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
                  : [];
        $clean  = [];
        $allowed_types = [ 'select', 'text', 'textarea', 'checkbox' ];

        foreach ( $raw as $row ) {
            if ( ! is_array( $row ) ) {
                continue;
            }
            $label = sanitize_text_field( wp_unslash( $row['label'] ?? '' ) );
            if ( $label === '' ) {
                continue; // skip empty rows
            }
            $key_raw = sanitize_key( wp_unslash( $row['key'] ?? '' ) );
            if ( $key_raw === '' ) {
                $key_raw = 'pixel_' . sanitize_key( $label );
            }
            $type = sanitize_key( wp_unslash( $row['type'] ?? 'select' ) );
            if ( ! in_array( $type, $allowed_types, true ) ) {
                $type = 'select';
            }
            $required   = ! empty( $row['required'] ) && $row['required'] !== '0';
            $sort_order = absint( $row['sort_order'] ?? 0 );

            // Options: split textarea by newline
            $options_raw = sanitize_textarea_field( wp_unslash( $row['options_raw'] ?? '' ) );
            $options     = [];
            if ( $options_raw !== '' ) {
                foreach ( explode( "\n", $options_raw ) as $opt ) {
                    $opt = trim( $opt );
                    if ( $opt !== '' ) {
                        $options[] = sanitize_text_field( $opt );
                    }
                }
            }

            $clean[] = [
                'key'        => $key_raw,
                'label'      => $label,
                'type'       => $type,
                'required'   => $required,
                'options'    => $options,
                'sort_order' => $sort_order,
            ];
        }

        // Sort by sort_order
        usort( $clean, static fn( $a, $b ) => $a['sort_order'] <=> $b['sort_order'] );

        if ( $clean === [] ) {
            delete_post_meta( $post_id, '_pixel_print_fields' );
        } else {
            update_post_meta( $post_id, '_pixel_print_fields', wp_json_encode( $clean ) );
        }
    }

    /* ------------------------------------------------------------------ */
    /*  Public helper: get configured fields for a product                   */
    /* ------------------------------------------------------------------ */

    public static function get_fields( int $product_id ): array {
        $raw = get_post_meta( $product_id, '_pixel_print_fields', true );
        if ( ! $raw ) {
            return [];
        }
        $decoded = json_decode( $raw, true );
        return is_array( $decoded ) ? $decoded : [];
    }

    /* ------------------------------------------------------------------ */
    /*  Inline admin JS                                                      */
    /* ------------------------------------------------------------------ */

    public static function inline_script(): void {
        $screen = get_current_screen();
        if ( ! $screen || $screen->id !== 'product' ) {
            return;
        }
        // Counter starts after existing rows
        ?>
        <script>
        (function($) {
            var rowIndex = $('#pixel-fields-rows .pixel-field-row').length;

            function slugify(str) {
                return 'pixel_' + str.toLowerCase()
                    .replace(/[^a-z0-9]+/g, '_')
                    .replace(/^_|_$/g, '');
            }

            $('#pixel-add-field-row').on('click', function() {
                var i = rowIndex++;
                var row = `<tr class="pixel-field-row">
                    <td><input type="text" name="pixel_fields[${i}][label]" value="" placeholder="e.g. Banner Size" class="pixel-label-input" style="width:100%"></td>
                    <td><input type="text" name="pixel_fields[${i}][key]" value="" placeholder="pixel_banner_size" class="pixel-key-input" style="width:100%"></td>
                    <td>
                        <select name="pixel_fields[${i}][type]" class="pixel-type-select" style="width:100%">
                            <option value="select">Select</option>
                            <option value="text">Text</option>
                            <option value="textarea">Textarea</option>
                            <option value="checkbox">Checkbox</option>
                        </select>
                    </td>
                    <td>
                        <select name="pixel_fields[${i}][required]" style="width:100%">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </td>
                    <td><input type="number" name="pixel_fields[${i}][sort_order]" value="${i}" style="width:56px" min="0"></td>
                    <td><textarea name="pixel_fields[${i}][options_raw]" rows="3" placeholder="Option A&#10;Option B&#10;Option C" class="pixel-options-textarea" style="width:100%;font-size:12px;"></textarea></td>
                    <td style="text-align:center;vertical-align:middle;"><button type="button" class="button pixel-remove-row" title="Remove">✕</button></td>
                </tr>`;
                $('#pixel-fields-rows').append(row);
            });

            // Auto-fill key from label
            $(document).on('input', '.pixel-label-input', function() {
                var $row = $(this).closest('tr');
                var $key = $row.find('.pixel-key-input');
                if ($key.val() === '' || $key.data('auto') !== false) {
                    $key.val(slugify($(this).val())).data('auto', true);
                }
            });
            $(document).on('input', '.pixel-key-input', function() {
                $(this).data('auto', false);
            });

            // Remove row
            $(document).on('click', '.pixel-remove-row', function() {
                $(this).closest('tr').remove();
            });
        })(jQuery);
        </script>
        <?php
    }

    /* ------------------------------------------------------------------ */
    /*  Inline admin CSS                                                      */
    /* ------------------------------------------------------------------ */

    public static function inline_styles(): void {
        $screen = get_current_screen();
        if ( ! $screen || $screen->id !== 'product' ) {
            return;
        }
        ?>
        <style>
        .pixel-fields-table th { font-size: 12px; padding: 6px 8px; background: #f9f9f9; }
        .pixel-fields-table td { padding: 6px 8px; vertical-align: top; }
        .pixel-fields-table tr:nth-child(even) td { background: #fafafa; }
        </style>
        <?php
    }
}
