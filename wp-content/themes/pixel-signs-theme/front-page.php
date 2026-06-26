<?php
get_header();

$featured_products = pixel_signs_get_featured_home_products(6);

$use_cases = [
    ['title' => 'Restaurants', 'items' => 'Menus, window vinyls, table tents, loyalty cards'],
    ['title' => 'Cards, Stationery & Art', 'items' => 'Business cards, postcards, art prints, appointment cards'],
    ['title' => 'Retail & Packaging', 'items' => 'Labels, bags, box sleeves, hang tags, stickers'],
    ['title' => 'Events', 'items' => 'Banners, foam boards, badges, step-and-repeat backdrops'],
    ['title' => 'Startup Branding', 'items' => 'Launch kits, merch, signage, mailers, packaging'],
];

$promos = [
    ['title' => 'Startup Branding Essentials', 'copy' => 'Bundle cards, stickers, packaging labels, and launch signage into one coordinated first impression.'],
    ['title' => 'Marketing Materials That Stand Out', 'copy' => 'Flyers, brochures, postcards, and mail-ready pieces produced with clean color and reliable finishing.'],
    ['title' => 'Trade Show Printing', 'copy' => 'Retractable banners, backdrops, table covers, and handouts prepared for quick setup.'],
    ['title' => 'Premium Custom Packaging', 'copy' => 'Labels, sleeves, bags, tissue, and tapes that make product delivery feel designed.'],
    ['title' => 'Smart Merch, Strong Brand', 'copy' => 'T-shirts, hats, notepads, calendars, and magnets for teams, events, and repeat customers.'],
];
?>
<section class="hero commerce-hero section">
    <div class="hero-copy">
<<<<<<< Updated upstream
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
            <svg class="hero-visual-art" viewBox="0 0 240 180" fill="none" aria-hidden="true" focusable="false">
                <rect x="20" y="40" width="200" height="110" rx="10" fill="rgba(255,255,255,.10)"/>
                <rect x="20" y="40" width="200" height="34" rx="10" fill="rgba(255,255,255,.16)"/>
                <rect x="40" y="92" width="74" height="40" rx="6" fill="var(--px-accent)"/>
                <rect x="126" y="92" width="74" height="18" rx="4" fill="rgba(255,255,255,.55)"/>
                <rect x="126" y="116" width="50" height="16" rx="4" fill="rgba(255,255,255,.30)"/>
                <circle cx="38" cy="57" r="6" fill="var(--px-accent)"/>
                <rect x="54" y="52" width="80" height="10" rx="5" fill="rgba(255,255,255,.45)"/>
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
        $partner_names = ['Northwind', 'Brightline', 'Summit Co.', 'Vertex', 'Harbor & Main', 'Lumen', 'Crestwave', 'Ironclad'];
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
=======
        <span class="eyebrow">Commercial Print Excellence</span>
        <h1>Custom Signs &amp; Printing to Elevate Your Brand</h1>
        <p>Order banners, labels, business cards, packaging, apparel, and marketing materials from one polished Pixel workflow. Configure standard products, request custom quotes, upload artwork, and track production from your client portal.</p>
        <div class="hero-actions">
            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/products/')); ?>">Browse Products</a>
            <a class="btn btn-outline" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request Quote</a>
        </div>
        <div class="hero-proof-row">
            <span><strong>15+</strong> launch catalog items</span>
            <span><strong>Free</strong> file review</span>
            <span><strong>COD</strong> demo checkout ready</span>
        </div>
    </div>
    <div class="hero-media">
        <div class="product-collage" role="img" aria-label="Pixel product collage showing banners, labels, cards, packaging, and apparel">
            <div class="collage-card collage-banner"><span>Vinyl Banner</span></div>
            <div class="collage-card collage-card-print"><span>Business Cards</span></div>
            <div class="collage-card collage-labels"><span>Roll Labels</span></div>
            <div class="collage-card collage-box"><span>Packaging Sleeve</span></div>
            <div class="collage-card collage-shirt"><span>Team Merch</span></div>
        </div>
    </div>
</section>

<section class="section" id="featured-products">
    <div class="section-heading split">
        <div>
            <span class="eyebrow">Featured Products</span>
            <h2>Print products customers ask for every week</h2>
            <p>Live WooCommerce products with clear actions for shopping, quoting, and artwork submission.</p>
        </div>
        <a class="btn btn-outline" href="<?php echo esc_url(home_url('/products/')); ?>">All Products</a>
    </div>
    <?php if (!empty($featured_products)) : ?>
        <div class="featured-product-grid">
            <?php foreach ($featured_products as $product) : ?>
                <?php
                if (!$product instanceof WC_Product) {
                    continue;
                }

                $product_slug = $product->get_slug();
                $product_url = get_permalink($product->get_id());
                $request_quote_url = add_query_arg('product', $product_slug, home_url('/request-quote/'));
                $upload_artwork_url = add_query_arg('product', $product_slug, home_url('/upload-artwork/'));
                $price_html = $product->get_price_html();
                ?>
                <article class="commerce-product-card">
                    <a class="commerce-product-visual <?php echo $product->get_image_id() ? 'has-product-image' : ''; ?>" data-product-mockup="<?php echo esc_attr(pixel_signs_get_product_mockup_key($product)); ?>" href="<?php echo esc_url($product_url); ?>">
                        <?php if ($product->get_image_id()) : ?>
                            <?php echo wp_kses_post($product->get_image('woocommerce_thumbnail', ['class' => 'pixel-product-card-image', 'alt' => $product->get_name()])); ?>
                        <?php else : ?>
                            <span><?php echo esc_html(pixel_signs_get_product_category_label($product)); ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="commerce-product-body">
                        <span class="product-card-category"><?php echo esc_html(pixel_signs_get_product_category_label($product)); ?></span>
                        <h3><?php echo esc_html($product->get_name()); ?></h3>
                        <p><?php echo esc_html(pixel_signs_get_product_card_description($product, 18)); ?></p>
                        <div class="commerce-product-meta">
                            <strong><?php echo $price_html !== '' ? wp_kses_post($price_html) : esc_html__('Request Quote', 'pixel-signs'); ?></strong>
                        </div>
                        <div class="product-actions commerce-product-actions">
                            <a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html__('View Details', 'pixel-signs'); ?></a>
                            <a href="<?php echo esc_url($request_quote_url); ?>"><?php echo esc_html__('Request Quote', 'pixel-signs'); ?></a>
                            <a href="<?php echo esc_url($upload_artwork_url); ?>"><?php echo esc_html__('Upload Artwork', 'pixel-signs'); ?></a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="empty-panel catalog-empty-state">
            <span class="eyebrow"><?php echo esc_html__('Featured Products', 'pixel-signs'); ?></span>
            <h2><?php echo esc_html__('Publish WooCommerce products to fill this section', 'pixel-signs'); ?></h2>
            <p><?php echo esc_html__('Products marked as featured appear here first. If none are featured, recent published products will show automatically.', 'pixel-signs'); ?></p>
            <a class="btn btn-primary" href="<?php echo esc_url(home_url('/products/')); ?>"><?php echo esc_html__('Browse Products', 'pixel-signs'); ?></a>
        </div>
    <?php endif; ?>
</section>

<section class="section use-case-section">
    <div class="section-heading center">
        <span class="eyebrow">Shop By Use Case</span>
        <h2>Organized for how businesses actually buy print</h2>
        <p>Pixel groups products around real customer needs so a client can find the right path quickly.</p>
    </div>
    <div class="use-case-grid">
        <?php foreach ($use_cases as $case_index => $case) : ?>
            <a class="use-case-card" href="<?php echo esc_url(home_url('/products/')); ?>">
                <span><?php echo esc_html('0' . ($case_index + 1)); ?></span>
                <h3><?php echo esc_html($case['title']); ?></h3>
                <p><?php echo esc_html($case['items']); ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="section promo-section">
    <div class="section-heading split">
        <div>
            <span class="eyebrow">Pixel Print Programs</span>
            <h2>Campaign blocks for common commercial jobs</h2>
        </div>
        <a class="btn btn-primary" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Build a Custom Quote</a>
    </div>
    <div class="promo-grid">
        <?php foreach ($promos as $promo_index => $promo) : ?>
            <article class="promo-card <?php echo $promo_index === 0 ? 'wide' : ''; ?>">
                <span>PX-<?php echo esc_html((string) ($promo_index + 1)); ?></span>
                <h3><?php echo esc_html($promo['title']); ?></h3>
                <p><?php echo esc_html($promo['copy']); ?></p>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="section customer-story-section">
    <div class="section-heading center">
        <span class="eyebrow">Latest Customers</span>
        <h2>Recent print stories from growing businesses</h2>
    </div>
    <div class="story-grid">
        <article class="story-card">
            <div class="story-visual" data-story="retail"></div>
            <h3>Retail packaging refresh</h3>
            <p>Labels, bag toppers, and shelf cards prepared for a boutique product launch.</p>
        </article>
        <article class="story-card">
            <div class="story-visual" data-story="event"></div>
            <h3>Trade show launch kit</h3>
            <p>Retractable banners, table cover, postcards, and branded staff shirts.</p>
        </article>
        <article class="story-card">
            <div class="story-visual" data-story="restaurant"></div>
            <h3>Restaurant menu rollout</h3>
            <p>Laminated menus, window decals, QR stickers, and delivery inserts.</p>
        </article>
    </div>
</section>

<section class="section quality-section">
    <div class="quality-panel">
        <div>
            <span class="eyebrow">About Pixel Quality</span>
            <h2>Pre-press review, production discipline, and print materials that hold up</h2>
            <p>Pixel is designed around a professional shop workflow: collect the right specs, validate artwork, confirm production details, and keep customers informed through quote, upload, checkout, and tracking screens.</p>
        </div>
        <div class="quality-stats">
            <div><strong>100%</strong><span>artwork review workflow</span></div>
            <div><strong>8</strong><span>print order statuses</span></div>
            <div><strong>24h</strong><span>quote response target</span></div>
        </div>
    </div>
</section>

<section class="section" id="reviews">
    <div class="section-heading center">
        <span class="eyebrow">Reviews</span>
        <h2>What demo customers say</h2>
    </div>
    <div class="review-grid">
        <blockquote>
            <div class="stars" aria-label="Five star review">*****</div>
            <p>"Pixel made our banner and card reorder feel simple. The quote was clear, and the proof notes were easy to follow."</p>
            <cite>Brightline Studio</cite>
        </blockquote>
        <blockquote>
            <div class="stars" aria-label="Five star review">*****</div>
            <p>"The packaging labels looked premium without slowing down our launch schedule."</p>
            <cite>Harbor &amp; Main</cite>
        </blockquote>
        <blockquote>
            <div class="stars" aria-label="Five star review">*****</div>
            <p>"Our event kit arrived organized and ready for setup. The portal made tracking painless."</p>
            <cite>Summit Co.</cite>
        </blockquote>
    </div>
</section>

<section class="section" id="partners">
    <div class="section-heading center">
        <span class="eyebrow">Trusted by growing businesses</span>
        <h2>Demo brand placeholders ready for client logos</h2>
    </div>
    <div class="partner-logos" aria-label="Partner and client brands">
        <?php
        $partner_names = ['Northwind', 'Brightline', 'Summit Co.', 'Vertex', 'Harbor & Main', 'Lumen', 'Crestwave', 'Ironclad'];
        foreach ($partner_names as $partner_name) :
        ?>
            <span class="partner-logo" role="img" aria-label="<?php echo esc_attr($partner_name); ?>">
                <span class="partner-logo-mark"><?php echo esc_html(substr($partner_name, 0, 1)); ?></span>
                <span class="partner-logo-text"><?php echo esc_html($partner_name); ?></span>
            </span>
        <?php endforeach; ?>
>>>>>>> Stashed changes
    </div>
</section>

<section class="cta-band">
    <h2>Ready to print something customers remember?</h2>
    <p>Start with a standard product, request a custom quote, or upload artwork for review.</p>
    <div class="hero-actions center-actions">
        <a class="btn btn-primary" href="<?php echo esc_url(home_url('/products/')); ?>">Shop Now</a>
        <a class="btn btn-outline light" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request Quote</a>
    </div>
</section>
<?php get_footer(); ?>
