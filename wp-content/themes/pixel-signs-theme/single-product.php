<?php get_header(); ?>
<section class="section product-detail-layout">
    <div>
        <div class="breadcrumb">Home › Products › Business Cards</div>
        <div class="product-gallery-card">
            <div class="main-product-image">Bestseller</div>
            <div class="thumb-row"><span></span><span></span><span></span><span></span></div>
        </div>
        <h1>Premium Business Cards</h1>
        <p class="lead">Make a lasting impression with high-fidelity, premium business cards engineered for professional clarity and crisp visual detail.</p>
        <div class="benefit-row">
            <span>⚡ Fast Turnaround</span>
            <span>✹ Quality Guarantee</span>
            <span>♻ Eco Options</span>
        </div>
    </div>
    <aside class="config-card">
        <h2>Configure Your Cards</h2>
        <label>Card Size</label>
        <div class="option-row"><button>Standard<br><small>3.5" × 2"</small></button><button>Slim</button><button>Square</button></div>
        <label>Paper Stock</label><select><option>14pt Coated Cover (Standard)</option></select>
        <label>Printed Sides</label><div><label><input type="radio" checked> Front Only</label> <label><input type="radio"> Front & Back</label></div>
        <label>Finish & Coating</label><select><option>Smooth Matte</option></select>
        <label>Quantity</label><select><option>250</option><option>500</option><option>1000</option></select>
        <div class="estimated-total"><span>Estimated Total</span><strong>$45.00</strong></div>
        <a class="btn btn-dark full" href="<?php echo esc_url(home_url('/upload-artwork/')); ?>">Upload Artwork</a>
        <a class="btn btn-primary full" href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request Custom Quote →</a>
    </aside>
</section>
<section class="section narrow">
    <h2 class="center-text">Frequently Asked Questions</h2>
    <details open><summary>What file formats do you accept for artwork?</summary><p>We recommend high-resolution PDF files with crop marks and bleed. We also accept TIFF, JPEG, EPS, AI, and PSD when production-ready.</p></details>
    <details><summary>What is the difference between Matte and Spot UV finish?</summary><p>Matte is a soft full-surface finish. Spot UV adds glossy highlights to selected areas.</p></details>
</section>
<?php get_footer(); ?>
