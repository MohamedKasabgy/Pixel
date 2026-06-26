<?php
/**
 * Template Name: Pickup Locations
 */
get_header();
?>
<section class="section narrow">
    <div class="section-heading">
        <span class="eyebrow">Pickup</span>
        <h1>Pickup Locations</h1>
        <p>Prefer to collect your order in person? Pick up from one of our locations during business hours.</p>
    </div>

    <div class="feature-grid" style="grid-template-columns: 1fr 1fr;">
        <article class="content-card" style="padding: 28px;">
            <h3>Downtown Print Studio</h3>
            <p>123 Print Avenue, Suite 100<br>New York, NY 10001</p>
            <p><strong>Hours:</strong> Mon–Fri, 9:00 AM – 6:00 PM</p>
            <p><strong>Phone:</strong> <a href="tel:+15551234567">(555) 123-4567</a></p>
        </article>
        <article class="content-card" style="padding: 28px;">
            <h3>Northside Production Center</h3>
            <p>456 Industrial Way<br>Long Island City, NY 11101</p>
            <p><strong>Hours:</strong> Mon–Sat, 8:00 AM – 5:00 PM</p>
            <p><strong>Phone:</strong> <a href="tel:+15551234568">(555) 123-4568</a></p>
        </article>
    </div>

    <div class="map-placeholder" role="img" aria-label="Pickup locations map placeholder" style="margin-top: 28px;">Map placeholder</div>

    <p style="margin-top: 24px;">Please wait for your <strong>Ready for Pickup</strong> notification before collecting your order. Bring your order number for fast service.</p>
</section>
<?php get_footer(); ?>
