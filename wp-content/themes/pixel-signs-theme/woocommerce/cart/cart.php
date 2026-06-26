<?php
/**
 * Pixel Signs Custom Cart Template
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="pixel-cart-container">
    <div class="pixel-cart-main">
        <div class="pixel-cart-head">
            <h1>Shopping Cart <span>(<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?> item<?php echo WC()->cart->get_cart_contents_count() === 1 ? '' : 's'; ?>)</span></h1>
        </div>

        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>
            
            <div class="pixel-cart-items">
                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        ?>
                        <div class="pixel-cart-item">
                            <div class="pixel-cart-media">
                                <div class="pixel-cart-image">
                                    <?php
                                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                                    if ( ! $product_permalink ) {
                                        echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    } else {
                                        printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    }
                                    ?>
                                </div>

                                <div class="pixel-cart-actions">
                                    <a class="pixel-cart-action pixel-cart-action-upload" href="<?php echo esc_url( home_url( '/upload-artwork/' ) ); ?>">Upload Artwork</a>
                                    <a class="pixel-cart-action pixel-cart-action-design" href="<?php echo esc_url( home_url( '/request-quote/' ) ); ?>">Design Online</a>
                                    <a class="pixel-cart-action pixel-cart-action-duplicate" href="<?php echo esc_url( $product_permalink ? $product_permalink : wc_get_page_permalink( 'shop' ) ); ?>">Duplicate Item</a>
                                    <a class="pixel-cart-action pixel-cart-action-specs" href="<?php echo esc_url( $product_permalink ? $product_permalink : wc_get_page_permalink( 'shop' ) ); ?>">Modify Specs</a>
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_cart_item_remove_link',
                                        sprintf(
                                            '<a href="%s" class="pixel-cart-action pixel-cart-action-remove remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">Remove From Cart</a>',
                                            esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                            esc_attr__( 'Remove this item', 'woocommerce' ),
                                            esc_attr( $product_id ),
                                            esc_attr( $_product->get_sku() )
                                        ),
                                        $cart_item_key
                                    ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    ?>
                                </div>
                            </div>

                            <div class="pixel-cart-details">
                                <h3>
                                    <?php
                                    if ( ! $product_permalink ) {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
                                    } else {
                                        echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
                                    }
                                    ?>
                                </h3>

                                <label class="pixel-project-name">
                                    <span>Project Name:</span>
                                    <input type="text" value="<?php echo esc_attr( $_product->get_name() ); ?>" aria-label="<?php esc_attr_e( 'Project name', 'pixel-signs' ); ?>">
                                </label>

                                <div class="pixel-cart-specs">
                                    <?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                    <div class="pixel-cart-spec-row"><span>Quantity:</span> <strong><?php echo esc_html( $cart_item['quantity'] ); ?></strong></div>
                                    <div class="pixel-cart-spec-row"><span>Ready To Ship In:</span> <strong>3 Business Days</strong></div>
                                </div>
                            </div>

                            <div class="pixel-cart-price-qty">
                                <div class="product-price">
                                    <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </div>
                                <div class="product-quantity">
                                    <span>Qty</span>
                                    <?php
                                    if ( $_product->is_sold_individually() ) {
                                        echo sprintf( '<input type="hidden" name="cart[%s][qty]" value="1" />', esc_attr( $cart_item_key ) );
                                    } else {
                                        woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$cart_item_key}][qty]",
                                                'input_value'  => $cart_item['quantity'],
                                                'max_value'    => $_product->get_max_purchase_quantity(),
                                                'min_value'    => '0',
                                                'product_name' => $_product->get_name(),
                                            ),
                                            $_product,
                                            false
                                        );
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            
            <div class="pixel-cart-update">
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                <?php do_action( 'woocommerce_cart_actions' ); ?>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </div>
            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
    </div>

    <div class="cart-collaterals">
        <?php do_action( 'woocommerce_cart_collaterals' ); ?>
    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
