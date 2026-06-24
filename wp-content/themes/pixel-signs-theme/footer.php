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
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">In</a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">Ig</a>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>">Fb</a>
            </div>
        </div>
        <div>
<<<<<<< Updated upstream
            <h3>Company</h3>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About Our Tech</a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact Us</a>
            <a href="<?php echo esc_url(home_url('/bulk-orders/')); ?>">Bulk Orders</a>
        </div>
        <div>
            <h3>Support</h3>
            <a href="<?php echo esc_url(home_url('/shipping-policy/')); ?>">Shipping Policy</a>
            <a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a>
            <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">File Setup Guide</a>
        </div>
        <div>
            <h3>Legal</h3>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy</a>
            <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms of Service</a>
=======
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
>>>>>>> Stashed changes
            <a href="<?php echo esc_url(home_url('/refund-policy/')); ?>">Refund Policy</a>
            <a href="<?php echo esc_url(home_url('/shipping-delivery-policy/')); ?>">Shipping Policy</a>
        </div>
<<<<<<< Updated upstream
    </div>
    <div class="footer-bottom">© <?php echo esc_html(date('Y')); ?> Pixel Signs & Printing. All rights reserved.</div>
=======
        <div class="footer-contact">
            <h3>Join Us</h3>
            <p><strong>Email</strong><br><a href="mailto:hello@pixelsigns.example">hello@pixelsigns.example</a></p>
            <p><strong>Phone</strong><br><a href="tel:+10000000000">+1 (000) 000-0000</a></p>
            <p><strong>Studio</strong><br>123 Print Avenue, Suite 100<br>Your City, ST 00000</p>
            <p><strong>Hours</strong><br>Mon-Fri, 9:00 AM - 6:00 PM</p>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; <?php echo esc_html(date('Y')); ?> Pixel Signs &amp; Printing. All rights reserved.</span>
        <span>No payment card data is stored by Pixel; checkout uses WooCommerce gateways.</span>
    </div>
>>>>>>> Stashed changes
</footer>
<?php wp_footer(); ?>
</body>
</html>
