<?php
/**
 * Pixel Product Options Handlers
 *
 * Renders per-product configured print fields (from admin metabox),
 * falling back to the global default fields if no config is set.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Pixel_Product_Options {

    public static function init(): void {
        add_action( 'woocommerce_before_add_to_cart_button', [ self::class, 'render_product_options' ], 10 );
        add_filter( 'woocommerce_add_to_cart_validation',    [ self::class, 'validate_product_options' ], 10, 3 );
        add_filter( 'woocommerce_add_cart_item_data',        [ self::class, 'add_cart_item_data' ], 10, 2 );
        add_filter( 'woocommerce_get_item_data',             [ self::class, 'display_cart_item_data' ], 10, 2 );
        add_action( 'woocommerce_checkout_create_order_line_item', [ self::class, 'add_order_item_meta' ], 10, 4 );
    }

    /* ------------------------------------------------------------------ */
    /*  Global default fields (Phase 3 fallback)                            */
    /* ------------------------------------------------------------------ */

    private static function get_default_fields(): array {
        return [
            [
                'key'      => 'pixel_size',
                'label'    => 'Size',
                'type'     => 'select',
                'required' => true,
                'options'  => [ 'Standard', 'Large Format', 'Custom Size' ],
            ],
            [
                'key'      => 'pixel_material',
                'label'    => 'Material',
                'type'     => 'select',
                'required' => true,
                'options'  => [ 'Standard Paper', 'Premium Cardstock', 'Vinyl', 'Rigid Board' ],
            ],
            [
                'key'      => 'pixel_finish',
                'label'    => 'Finish',
                'type'     => 'select',
                'required' => true,
                'options'  => [ 'Matte', 'Gloss', 'Laminated', 'No Finish' ],
            ],
            [
                'key'      => 'pixel_print_quantity',
                'label'    => 'Print Quantity',
                'type'     => 'select',
                'required' => true,
                'options'  => [ '100', '250', '500', '1000' ],
            ],
            [
                'key'      => 'pixel_turnaround',
                'label'    => 'Turnaround Time',
                'type'     => 'select',
                'required' => true,
                'options'  => [
                    'Standard: 3-5 Business Days',
                    'Rush: 1-2 Business Days',
                    'Custom Schedule',
                ],
            ],
            [
                'key'      => 'pixel_artwork_status',
                'label'    => 'Artwork Status',
                'type'     => 'select',
                'required' => true,
                'options'  => [
                    'Ready to Print',
                    'Needs File Review',
                    'Need Design Help',
                    'Will Send Later',
                ],
            ],
            [
                'key'      => 'pixel_special_instructions',
                'label'    => 'Special Instructions',
                'type'     => 'textarea',
                'required' => false,
                'options'  => [],
            ],
        ];
    }

    /* ------------------------------------------------------------------ */
    /*  Resolve fields for the current product                              */
    /* ------------------------------------------------------------------ */

    private static function resolve_fields( int $product_id ): array {
        // Use admin-configured fields if available
        if (
            class_exists( 'Pixel_Admin_Fields' ) &&
            $product_id > 0
        ) {
            $configured = Pixel_Admin_Fields::get_fields( $product_id );
            if ( ! empty( $configured ) ) {
                return $configured;
            }
        }

        // Fall back to global Phase 3 defaults
        return self::get_default_fields();
    }

    /* ------------------------------------------------------------------ */
    /*  Render                                                              */
    /* ------------------------------------------------------------------ */

    public static function render_product_options(): void {
        global $product;
        $product_id = $product instanceof WC_Product ? $product->get_id() : get_the_ID();
        $fields     = self::resolve_fields( (int) $product_id );

        echo '<div class="pixel-product-options" style="margin-bottom:1.5rem;">';
        echo '<h2>Configure Product</h2>';

        foreach ( $fields as $field ) {
            $key      = sanitize_key( $field['key'] ?? '' );
            $label    = esc_html( $field['label'] ?? $key );
            $type     = $field['type'] ?? 'select';
            $required = ! empty( $field['required'] );
            $options  = is_array( $field['options'] ?? null ) ? $field['options'] : [];
            $id       = 'pixel-field-' . esc_attr( $key );
            $req_attr = $required ? ' required' : '';
            $req_mark = $required ? ' <span style="color:var(--px-accent);">*</span>' : '';

            echo '<label for="' . esc_attr( $id ) . '">' . $label . $req_mark . '</label>';

            if ( $type === 'select' ) {
                echo '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '"' . $req_attr . '>';
                echo '<option value="">Select ' . $label . '</option>';
                foreach ( $options as $opt ) {
                    echo '<option value="' . esc_attr( $opt ) . '">' . esc_html( $opt ) . '</option>';
                }
                echo '</select>';

            } elseif ( $type === 'text' ) {
                echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" placeholder="' . esc_attr( $label ) . '"' . $req_attr . '>';

            } elseif ( $type === 'textarea' ) {
                echo '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $key ) . '" placeholder="' . esc_attr( $label ) . '"' . $req_attr . '></textarea>';

            } elseif ( $type === 'checkbox' ) {
                echo '<div class="pixel-checkbox-group">';
                foreach ( $options as $opt ) {
                    $opt_id  = $id . '-' . sanitize_key( $opt );
                    $opt_key = $key . '[]';
                    echo '<label class="pixel-checkbox-label" style="font-weight:normal;display:flex;gap:8px;align-items:center;">';
                    echo '<input type="checkbox" id="' . esc_attr( $opt_id ) . '" name="' . esc_attr( $opt_key ) . '" value="' . esc_attr( $opt ) . '">';
                    echo esc_html( $opt );
                    echo '</label>';
                }
                echo '</div>';
            }
        }

        echo '<input type="hidden" name="pixel_is_custom_product" value="1">';
        echo '<input type="hidden" name="pixel_product_id" value="' . esc_attr( (string) $product_id ) . '">';
        echo '</div>';
    }

    /* ------------------------------------------------------------------ */
    /*  Validation                                                          */
    /* ------------------------------------------------------------------ */

    public static function validate_product_options( bool $passed, int $product_id, int $quantity ): bool {
        if ( ! isset( $_POST['pixel_is_custom_product'] ) ) {
            return $passed;
        }

        // Use the posted product ID to resolve config (more reliable than loop context)
        $pid    = isset( $_POST['pixel_product_id'] ) ? absint( $_POST['pixel_product_id'] ) : $product_id;
        $fields = self::resolve_fields( $pid );

        foreach ( $fields as $field ) {
            $key      = sanitize_key( $field['key'] ?? '' );
            $label    = $field['label'] ?? $key;
            $required = ! empty( $field['required'] );
            $type     = $field['type'] ?? 'select';

            if ( ! $required ) {
                continue;
            }

            // Checkbox: at least one must be checked
            if ( $type === 'checkbox' ) {
                if ( empty( $_POST[ $key ] ) ) {
                    $passed = false;
                    if ( function_exists( 'wc_add_notice' ) ) {
                        /* translators: %s: field label */
                        wc_add_notice( sprintf( 'Please select at least one option for %s.', $label ), 'error' );
                    }
                }
            } else {
                if ( empty( $_POST[ $key ] ) ) {
                    $passed = false;
                    if ( function_exists( 'wc_add_notice' ) ) {
                        /* translators: %s: field label */
                        wc_add_notice( sprintf( 'Please complete the %s field before adding to cart.', $label ), 'error' );
                    }
                }
            }
        }

        return $passed;
    }

    /* ------------------------------------------------------------------ */
    /*  Cart item data                                                      */
    /* ------------------------------------------------------------------ */

    public static function add_cart_item_data( array $cart_item_data, int $product_id ): array {
        if ( ! isset( $_POST['pixel_is_custom_product'] ) ) {
            return $cart_item_data;
        }

        $pid    = isset( $_POST['pixel_product_id'] ) ? absint( $_POST['pixel_product_id'] ) : $product_id;
        $fields = self::resolve_fields( $pid );

        $cart_item_data['pixel_options'] = [];

        foreach ( $fields as $field ) {
            $key   = sanitize_key( $field['key'] ?? '' );
            $label = $field['label'] ?? $key;
            $type  = $field['type'] ?? 'select';

            if ( $type === 'checkbox' ) {
                $raw = isset( $_POST[ $key ] ) && is_array( $_POST[ $key ] ) ? $_POST[ $key ] : [];
                $values = [];
                foreach ( $raw as $v ) {
                    $values[] = sanitize_text_field( wp_unslash( $v ) );
                }
                if ( ! empty( $values ) ) {
                    $cart_item_data['pixel_options'][ $label ] = implode( ', ', $values );
                }
            } elseif ( $type === 'textarea' ) {
                if ( ! empty( $_POST[ $key ] ) ) {
                    $cart_item_data['pixel_options'][ $label ] = sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) );
                }
            } else {
                if ( ! empty( $_POST[ $key ] ) ) {
                    $cart_item_data['pixel_options'][ $label ] = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
                }
            }
        }

        // Unique key so cart lines with different options don't merge
        $cart_item_data['pixel_options_unique_key'] = md5(
            wp_json_encode( $cart_item_data['pixel_options'] ) . microtime()
        );

        return $cart_item_data;
    }

    /* ------------------------------------------------------------------ */
    /*  Cart display                                                        */
    /* ------------------------------------------------------------------ */

    public static function display_cart_item_data( array $item_data, array $cart_item ): array {
        if ( isset( $cart_item['pixel_options'] ) && is_array( $cart_item['pixel_options'] ) ) {
            foreach ( $cart_item['pixel_options'] as $label => $value ) {
                $item_data[] = [
                    'name'  => $label,
                    'value' => $value,
                ];
            }
        }
        return $item_data;
    }

    /* ------------------------------------------------------------------ */
    /*  Order item meta                                                     */
    /* ------------------------------------------------------------------ */

    public static function add_order_item_meta( $item, $cart_item_key, array $values, $order ): void {
        if ( isset( $values['pixel_options'] ) && is_array( $values['pixel_options'] ) ) {
            foreach ( $values['pixel_options'] as $label => $value ) {
                $item->add_meta_data( $label, $value, true );
            }
        }
    }
}

Pixel_Product_Options::init();
