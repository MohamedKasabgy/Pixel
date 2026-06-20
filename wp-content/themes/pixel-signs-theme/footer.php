<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
</main>
<footer class="site-footer">
    <div class="footer-grid">
        <div>
            <h2>Pixel Signs &<br>Printing</h2>
            <p>High-fidelity precision in every pixel. Commercial printing solutions for modern businesses.</p>
        </div>
        <div>
            <h3>Explore</h3>
            <a href="<?php echo esc_url(home_url('/products/')); ?>">Products</a>
            <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request Quote</a>
            <a href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">About</a>
        </div>
        <div>
            <h3>Support</h3>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact</a>
            <a href="<?php echo esc_url(home_url('/faq/')); ?>">FAQ</a>
            <a href="<?php echo esc_url(home_url('/shipping-policy/')); ?>">Shipping &amp; Delivery</a>
            <a href="<?php echo esc_url(home_url('/pickup-locations/')); ?>">Pickup Locations</a>
        </div>
        <div>
            <h3>Legal</h3>
            <a href="<?php echo esc_url(home_url('/privacy/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/terms/')); ?>">Terms</a>
            <a href="<?php echo esc_url(home_url('/refund-policy/')); ?>">Refund Policy</a>
        </div>
        <div class="footer-contact">
            <h3>Contact</h3>
            <p><strong>Email</strong><br><a href="mailto:hello@pixelsigns.example">hello@pixelsigns.example</a></p>
            <p><strong>Phone</strong><br><a href="tel:+10000000000">+1 (000) 000-0000</a></p>
            <p><strong>Visit</strong><br>123 Print Avenue, Suite 100<br>Your City, ST 00000</p>
            <p><strong>Hours</strong><br>Mon–Fri, 9:00 AM – 6:00 PM</p>
        </div>
    </div>
    <div class="footer-bottom">© <?php echo esc_html(date('Y')); ?> Pixel Signs &amp; Printing. All rights reserved.</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
