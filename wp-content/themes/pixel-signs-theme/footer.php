<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
</main>
<footer class="site-footer">
    <div class="footer-newsletter">
        <div>
            <span class="eyebrow dark">Pixel Print Desk</span>
            <h2>Get smarter print planning in your inbox.</h2>
            <p>Occasional production tips, seasonal ideas, and practical file setup guidance from the Pixel team.</p>
        </div>
        <form class="footer-signup" action="<?php echo esc_url(home_url('/contact/')); ?>">
            <label class="screen-reader-text" for="pixel-footer-email">Email address</label>
            <input id="pixel-footer-email" type="email" placeholder="Email address">
            <button class="btn btn-primary" type="submit">Join</button>
        </form>
    </div>

    <div class="footer-grid">
        <div class="footer-brand">
            <h2>Pixel Signs &amp; Printing</h2>
            <p>Commercial signs, print marketing, branded packaging, and artwork support for businesses that need the physical details handled well.</p>
            <div class="footer-socials" aria-label="Social links">
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" aria-label="LinkedIn">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6.5 9.5V19M6.5 5v.1M11 19v-9.5M11 13.5c0-2.4 1.5-4 3.8-4 2.1 0 3.2 1.3 3.2 3.7V19" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><rect x="5" y="5" width="14" height="14" rx="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/><path d="M16.5 7.5h.1" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                </a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" aria-label="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14 8h2V5h-2.4C11.4 5 10 6.4 10 8.7V11H8v3h2v5h3v-5h2.4l.6-3h-3V8.9c0-.6.3-.9 1-.9Z" fill="currentColor"/></svg>
                </a>
            </div>
        </div>
        <div>
            <h3>Products &amp; Services</h3>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">All Products</a>
            <a href="<?php echo esc_url(home_url('/products/?category=marketing')); ?>">Mailing Services</a>
            <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Design Services</a>
            <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Custom Quotes</a>
            <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Free File Review</a>
            <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
        </div>
        <div>
            <h3>Help &amp; Resources</h3>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
            <a href="<?php echo esc_url(home_url('/pickup-locations/')); ?>">Pickup Locations</a>
            <a href="<?php echo esc_url(home_url('/shipping-delivery-policy/')); ?>">Turnaround Information</a>
            <a href="<?php echo esc_url(home_url('/faq/')); ?>">File Preparation Guide</a>
            <a href="<?php echo esc_url(home_url('/refund-policy/')); ?>">Cancellation &amp; Refunds</a>
            <a href="<?php echo esc_url(home_url('/faq/')); ?>">Help Center</a>
            <a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a>
        </div>
        <div>
            <h3>Pixel</h3>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About Us</a>
            <a href="<?php echo esc_url(home_url('/#reviews')); ?>">Customer Reviews</a>
            <a href="<?php echo esc_url(home_url('/terms-conditions/')); ?>">Terms &amp; Conditions</a>
            <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/refund-policy/')); ?>">Refund Policy</a>
            <a href="<?php echo esc_url(home_url('/shipping-delivery-policy/')); ?>">Shipping Policy</a>
        </div>
        <div class="footer-contact">
            <h3>Join Us</h3>
            <p><strong>Email</strong><br><a href="mailto:hello@pixelsignsprinting.com">hello@pixelsignsprinting.com</a></p>
            <p><strong>Phone</strong><br><a href="tel:+15551234567">(555) 123-4567</a></p>
            <p><strong>Studio</strong><br>123 Print Avenue, Suite 100<br>New York, NY 10001</p>
            <p><strong>Hours</strong><br>Mon-Fri, 9:00 AM - 6:00 PM</p>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?php echo esc_html(date('Y')); ?> Pixel Signs &amp; Printing. All rights reserved.</span>
        <span>No payment card data is stored by Pixel; checkout uses WooCommerce gateways.</span>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
