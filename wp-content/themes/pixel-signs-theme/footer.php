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
            <a href="<?php echo esc_url(home_url('/refund-policy/')); ?>">Refund Policy</a>
        </div>
    </div>
    <div class="footer-bottom">© <?php echo esc_html(date('Y')); ?> Pixel Signs & Printing. All rights reserved.</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
