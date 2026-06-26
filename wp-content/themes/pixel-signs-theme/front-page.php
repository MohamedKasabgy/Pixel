<?php get_header(); ?>
<section class="hero section">
    <div class="hero-copy">
        <span class="eyebrow">Precision in Every Pixel</span>
        <h1>High-Quality Printing for Growing Businesses</h1>
        <p>Signs, marketing materials, apparel, and large-format prints — produced with commercial-grade equipment and reviewed by real people before they hit the press.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request a Quote →</a>
            <a class="btn btn-outline" href="<?php echo esc_url(home_url('/products/')); ?>">Browse Products</a>
            <a class="btn btn-dark" href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
        </div>
    </div>
    <div class="hero-media">
        <?php /* Visual slot: CSS/SVG now; swap for a real photo later via background-image or an inner <img>. */ ?>
        <div class="hero-visual" role="img" aria-label="Commercial printing and signage production">
            <svg class="hero-visual-art" viewBox="0 0 260 190" fill="none" aria-hidden="true" focusable="false">
                <rect x="24" y="38" width="212" height="112" rx="12" fill="rgba(255,255,255,.12)"/>
                <rect x="24" y="38" width="212" height="32" rx="12" fill="rgba(255,255,255,.18)"/>
                <circle cx="43" cy="54" r="5" fill="var(--px-accent)"/>
                <circle cx="60" cy="54" r="5" fill="rgba(255,255,255,.5)"/>
                <rect x="78" y="49" width="86" height="10" rx="5" fill="rgba(255,255,255,.46)"/>
                <rect x="42" y="92" width="82" height="42" rx="6" fill="var(--px-accent)"/>
                <path d="M42 114h82" stroke="rgba(255,255,255,.55)" stroke-width="3"/>
                <rect x="142" y="88" width="74" height="14" rx="4" fill="rgba(255,255,255,.58)"/>
                <rect x="142" y="111" width="58" height="14" rx="4" fill="rgba(255,255,255,.34)"/>
                <path d="M54 150h152l-18 22H72z" fill="rgba(255,255,255,.16)"/>
                <path d="M72 161h116" stroke="rgba(255,255,255,.32)" stroke-width="3" stroke-linecap="round"/>
                <path d="M26 78h208" stroke="rgba(255,255,255,.13)" stroke-width="4"/>
            </svg>
        </div>
        <div class="quality-badge"><strong>HQ</strong><span>Premium Quality<br>Commercial Grade Materials</span></div>
    </div>
</section>

<section class="section" id="categories">
    <div class="section-heading center">
        <h2>Print Categories</h2>
        <p>Everything you need across signage, marketing, apparel, and packaging — all from one print partner.</p>
    </div>
    <div class="category-grid">
        <a class="category-card" href="<?php echo esc_url(home_url('/products/?category=large-format')); ?>">
            <span class="category-visual" data-cat="large-format" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" focusable="false"><rect x="7" y="11" width="34" height="22" rx="2" stroke="currentColor" stroke-width="2.5"/><path d="M24 33v8M16 41h16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
            </span>
            <h3>Large Format</h3>
            <p>Banners, posters, rigid signs, and trade-show displays built for impact.</p>
            <span class="category-link">Explore Large Format →</span>
        </a>
        <a class="category-card" href="<?php echo esc_url(home_url('/products/?category=marketing')); ?>">
            <span class="category-visual" data-cat="marketing" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" focusable="false"><path d="M10 18h20l8-6v24l-8-6H10z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M14 30v6a3 3 0 0 0 6 0v-4" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
            </span>
            <h3>Marketing Materials</h3>
            <p>Flyers, brochures, postcards, and stickers that get your message out.</p>
            <span class="category-link">Explore Marketing →</span>
        </a>
        <a class="category-card" href="<?php echo esc_url(home_url('/products/?category=signage')); ?>">
            <span class="category-visual" data-cat="signage" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" focusable="false"><rect x="9" y="10" width="30" height="18" rx="2" stroke="currentColor" stroke-width="2.5"/><path d="M24 28v12M18 40h12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><path d="M15 19h18" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
            </span>
            <h3>Signage</h3>
            <p>Indoor and outdoor signs for retail, events, job sites, and wayfinding.</p>
            <span class="category-link">Explore Signage →</span>
        </a>
        <a class="category-card" href="<?php echo esc_url(home_url('/products/?category=apparel')); ?>">
            <span class="category-visual" data-cat="apparel" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" focusable="false"><path d="M18 9l6 4 6-4 9 6-4 6-3-2v14H16V19l-3 2-4-6z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg>
            </span>
            <h3>Apparel</h3>
            <p>Screen-printed and direct-to-garment apparel for teams and merch.</p>
            <span class="category-link">Explore Apparel →</span>
        </a>
        <a class="category-card" href="<?php echo esc_url(home_url('/products/?category=business-cards')); ?>">
            <span class="category-visual" data-cat="business-cards" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" focusable="false"><rect x="8" y="14" width="32" height="20" rx="3" stroke="currentColor" stroke-width="2.5"/><path d="M13 22h8M13 27h14" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><circle cx="31" cy="23" r="3" stroke="currentColor" stroke-width="2.5"/></svg>
            </span>
            <h3>Business Cards</h3>
            <p>Premium stocks with matte, gloss, and soft-touch finishes.</p>
            <span class="category-link">Explore Business Cards →</span>
        </a>
        <?php /* Packaging has no filter in products.php yet — link safely to all products. See handoff note for Mohamed. */ ?>
        <a class="category-card" href="<?php echo esc_url(home_url('/products/')); ?>">
            <span class="category-visual" data-cat="packaging" aria-hidden="true">
                <svg viewBox="0 0 48 48" fill="none" focusable="false"><path d="M24 8l14 7v18l-14 7-14-7V15z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M10 15l14 7 14-7M24 22v18" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg>
            </span>
            <h3>Packaging</h3>
            <p>Custom boxes, labels, and branded packaging for products of every size.</p>
            <span class="category-link">Explore Packaging →</span>
        </a>
    </div>
</section>

<section class="section" id="partners">
    <div class="section-heading center">
        <h2>Trusted by growing businesses</h2>
        <p>Local shops, agencies, and growing brands rely on Pixel for their print and signage.</p>
    </div>
    <div class="partner-logos" aria-label="Partner and client brands">
        <?php
        /* Placeholder logo marks (SVG now). Each slot can hold a real <img> logo later. */
        $partner_names = ['The Coffee House', 'Summit Real Estate', 'Harbor Dental', 'Brightline Events', 'North Market', 'Lumen Fitness', 'Crestwave Studio', 'Ironclad Builders'];
        foreach ($partner_names as $partner_name) :
        ?>
            <span class="partner-logo" role="img" aria-label="<?php echo esc_attr($partner_name); ?>">
                <svg class="partner-logo-mark" viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/><path d="M8 12l3 3 5-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                <span class="partner-logo-text"><?php echo esc_html($partner_name); ?></span>
            </span>
        <?php endforeach; ?>
    </div>
</section>

<section class="section" id="why">
    <div class="section-heading center">
        <h2>Why Choose Us</h2>
        <p>A print partner that combines speed, craft, and genuinely helpful service.</p>
    </div>
    <div class="why-grid">
        <div class="why-card">
            <span class="why-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" focusable="false"><path d="M17 4L7 18h7l-1 10 11-15h-8z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg></span>
            <h3>Fast Turnaround</h3>
            <p>Standard jobs ship in days, not weeks — with rush options when you need them.</p>
        </div>
        <div class="why-card">
            <span class="why-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" focusable="false"><path d="M6 9h14M6 16h20M6 23h10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/><circle cx="24" cy="9" r="3" stroke="currentColor" stroke-width="2.5"/></svg></span>
            <h3>Custom Quotes</h3>
            <p>Tell us the spec and quantity and get clear, transparent pricing fast.</p>
        </div>
        <div class="why-card">
            <span class="why-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" focusable="false"><path d="M9 5h10l7 7v15H9z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><path d="M19 5v7h7M13 19l3 3 5-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            <h3>Free File Review</h3>
            <p>Our team checks every artwork file for print issues before we run it — at no cost.</p>
        </div>
        <div class="why-card">
            <span class="why-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" focusable="false"><path d="M16 4l3.5 7.5L27 13l-5.5 5.3L23 26l-7-4-7 4 1.5-7.7L5 13l7.5-1.5z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/></svg></span>
            <h3>Premium Print Quality</h3>
            <p>Commercial-grade presses and materials for crisp color and durable results.</p>
        </div>
        <div class="why-card">
            <span class="why-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" focusable="false"><path d="M4 11h13v11H4zM17 14h6l4 4v4h-10z" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/><circle cx="9" cy="24" r="2.5" stroke="currentColor" stroke-width="2.5"/><circle cx="22" cy="24" r="2.5" stroke="currentColor" stroke-width="2.5"/></svg></span>
            <h3>Local Delivery / Pickup</h3>
            <p>Choose local delivery or pick up your order from a nearby Pixel location.</p>
        </div>
        <div class="why-card">
            <span class="why-icon" aria-hidden="true"><svg viewBox="0 0 32 32" fill="none" focusable="false"><rect x="5" y="6" width="9" height="9" rx="1.5" stroke="currentColor" stroke-width="2.5"/><rect x="18" y="6" width="9" height="9" rx="1.5" stroke="currentColor" stroke-width="2.5"/><rect x="5" y="18" width="9" height="9" rx="1.5" stroke="currentColor" stroke-width="2.5"/><rect x="18" y="18" width="9" height="9" rx="1.5" stroke="currentColor" stroke-width="2.5"/></svg></span>
            <h3>Bulk Order Support</h3>
            <p>Dedicated help and volume pricing for large runs and repeat reorders.</p>
        </div>
    </div>
</section>

<section class="section center">
    <div class="section-heading center">
        <h2>How It Works</h2>
        <p>A streamlined process designed for efficiency and precision.</p>
    </div>
    <div class="steps-grid">
        <div class="step-card"><b>1</b><h3>Choose Product</h3><p>Select from our commercial print catalog.</p></div>
        <div class="step-card"><b>2</b><h3>Upload Design</h3><p>Provide artwork or let our team assist.</p></div>
        <div class="step-card"><b>3</b><h3>Quote Request</h3><p>Receive a transparent estimate.</p></div>
        <div class="step-card"><b>4</b><h3>Delivered</h3><p>Fast, secure shipping to your facility.</p></div>
    </div>
</section>

<section class="cta-band">
    <h2>Ready to Print?</h2>
    <p>Get a precise quote for your commercial printing needs today.</p>
    <form class="inline-form" action="<?php echo esc_url(home_url('/request-quote/')); ?>">
        <input type="email" placeholder="Email Address" aria-label="Email Address">
        <button class="btn btn-primary" type="submit">Request a Quote</button>
    </form>
</section>
<?php get_footer(); ?>
