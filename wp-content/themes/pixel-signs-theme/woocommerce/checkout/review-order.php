<?php
/**
 * Pixel Signs Custom Review Order Template
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="shop_table woocommerce-checkout-review-order-table">
    <?php
    do_action( 'woocommerce_review_order_before_cart_contents' );
    do_action( 'woocommerce_review_order_after_cart_contents' );
    ?>

    <div class="pixel-summary-totals">
        <div class="pixel-summary-row cart-subtotal">
            <span>Item(s) total</span>
            <span><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
            <div class="pixel-summary-row cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
                <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
            <?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
            <div class="pixel-summary-row shipping-row pixel-summary-row-shipping">
                <span>Shipping &amp; Handling</span>
                <span><?php echo wp_kses_post( WC()->customer->has_calculated_shipping() ? WC()->cart->get_cart_shipping_total() : '&ndash;' ); ?></span>
            </div>
            <?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
        <?php else : ?>
            <div class="pixel-summary-row shipping-row pixel-summary-row-shipping">
                <span>Shipping &amp; Handling</span>
                <span>&ndash;</span>
            </div>
        <?php endif; ?>

        <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
            <div class="pixel-summary-row fee">
                <span><?php echo esc_html( $fee->name ); ?></span>
                <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
            <?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                    <div class="pixel-summary-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                        <span><?php echo esc_html( $tax->label ); ?></span>
                        <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="pixel-summary-row tax-total">
                    <span>Tax</span>
                    <span><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="pixel-summary-row tax-total">
                <span>Tax</span>
                <span>&ndash;</span>
            </div>
        <?php endif; ?>

        <?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

        <div class="pixel-summary-row total order-total">
            <span><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
            <span><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

        <?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
    </div>
</div>
