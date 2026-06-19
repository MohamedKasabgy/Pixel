<?php get_header(); ?>
<section class="hero section">
    <div class="hero-copy">
        <span class="eyebrow">Precision in Every Pixel</span>
        <h1>Commercial Print Excellence</h1>
        <p>High-fidelity large format, signage, marketing materials, and branded apparel engineered for impact.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Start a Project →</a>
            <a class="btn btn-outline" href="<?php echo esc_url(home_url('/products/')); ?>">Browse Products</a>
        </div>
    </div>
    <div class="hero-media">
        <div class="hero-image-placeholder">Industrial press image</div>
        <div class="quality-badge"><strong>HQ</strong><span>Premium Quality<br>Commercial Grade Materials</span></div>
    </div>
</section>

<section class="section">
    <div class="section-heading">
        <h2>Featured Products</h2>
        <p>Industrial scale solutions for every physical touchpoint.</p>
    </div>
    <div class="feature-grid">
        <a class="feature-card large" href="<?php echo esc_url(home_url('/products/')); ?>">
            <span>Large Format</span>
            <p>Banners, rigid signs, and trade show displays engineered for maximum impact.</p>
        </a>
        <a class="feature-card" href="<?php echo esc_url(home_url('/products/')); ?>">
            <span>Business Cards</span>
            <p>Premium stocks and finishes.</p>
        </a>
        <a class="feature-card wide" href="<?php echo esc_url(home_url('/products/')); ?>">
            <span>Apparel</span>
            <p>Direct-to-garment and screen printing for corporate wear.</p>
        </a>
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
