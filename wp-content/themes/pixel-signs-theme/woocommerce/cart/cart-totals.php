<?php
/**
 * Pixel Signs Custom Cart Totals Template
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="pixel-order-summary cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

    <?php do_action( 'woocommerce_before_cart_totals' ); ?>

    <h2>Order Summary</h2>

    <div class="pixel-summary-row">
        <span>Item(s) total</span>
        <span><?php wc_cart_totals_subtotal_html(); ?></span>
    </div>

    <?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
        <div class="pixel-summary-row coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
            <span><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
            <span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
        </div>
    <?php endforeach; ?>

    <?php if ( WC()->cart->needs_shipping() ) : ?>
        <div class="pixel-summary-row">
            <span>Shipping &amp; Handling</span>
            <span>
                <?php
                if ( WC()->cart->show_shipping() && WC()->customer->has_calculated_shipping() ) {
                    echo wp_kses_post( WC()->cart->get_cart_shipping_total() );
                } else {
                    echo '&ndash;';
                }
                ?>
            </span>
        </div>
    <?php endif; ?>

    <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
        <div class="pixel-summary-row fee">
            <span><?php echo esc_html( $fee->name ); ?></span>
            <span><?php wc_cart_totals_fee_html( $fee ); ?></span>
        </div>
    <?php endforeach; ?>

    <?php
    if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
        $taxable_address = WC()->customer->get_taxable_address();
        $estimated_text  = '';

        if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
            /* translators: %s location. */
            $estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
        }

        if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
            foreach ( WC()->cart->get_tax_totals() as $code => $tax ) {
                ?>
                <div class="pixel-summary-row tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
                    <span><?php echo esc_html( $tax->label ) . $estimated_text; ?></span>
                    <span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="pixel-summary-row tax-total">
                <span>Tax<?php echo wp_kses_post( $estimated_text ); ?></span>
                <span><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
            <?php
        }
    } else {
        ?>
        <div class="pixel-summary-row tax-total">
            <span>Tax</span>
            <span>&ndash;</span>
        </div>
        <?php
    }
    ?>

    <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>

    <div class="pixel-summary-row total">
        <span>Total</span>
        <span><?php wc_cart_totals_order_total_html(); ?></span>
    </div>

    <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>

    <div class="wc-proceed-to-checkout">
        <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button alt wc-forward">
            Checkout Now
        </a>
    </div>

    <?php if ( WC()->cart->needs_shipping() ) : ?>
        <div class="pixel-estimate-shipping">
            <h3>Estimate Tax &amp; Shipping</h3>
            <?php woocommerce_shipping_calculator(); ?>
        </div>
    <?php endif; ?>

    <div class="pixel-continue-shopping">
        <a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">Continue Shopping</a>
    </div>

    <?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
