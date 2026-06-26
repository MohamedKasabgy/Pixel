<?php
/**
 * Template Name: Contact
 */
get_header();
?>
<section class="section">
    <div class="section-heading">
        <span class="eyebrow">Get in touch</span>
        <h1>Contact Us</h1>
        <p>Questions about a project, a quote, or an existing order? Reach out and our team will get back to you.</p>
    </div>

    <div class="contact-grid">
        <?php /* Demo contact form: visual placeholder only — no backend handler is wired yet (see handoff notes). */ ?>
        <form class="content-card contact-form" action="#" method="post" novalidate>
            <div class="form-grid">
                <div>
                    <label for="contact-name">Full name</label>
                    <input type="text" id="contact-name" name="contact_name" placeholder="Jane Doe">
                </div>
                <div>
                    <label for="contact-email">Email</label>
                    <input type="email" id="contact-email" name="contact_email" placeholder="you@company.com">
                </div>
            </div>
            <div>
                <label for="contact-subject">Subject</label>
                <input type="text" id="contact-subject" name="contact_subject" placeholder="How can we help?">
            </div>
            <div>
                <label for="contact-message">Message</label>
                <textarea id="contact-message" name="contact_message" placeholder="Tell us about your project..."></textarea>
            </div>
            <button class="btn btn-primary full" type="submit">Send Message</button>
            <p style="font-size: 13px; margin: 0;">Prefer a quote? Use the <a href="<?php echo esc_url(home_url('/request-quote/')); ?>">Request a Quote</a> form for fastest turnaround.</p>
        </form>

        <div>
            <dl class="contact-info">
                <dt>Email</dt>
                <dd><a href="mailto:hello@pixelsignsprinting.com">hello@pixelsignsprinting.com</a></dd>
                <dt>Phone</dt>
                <dd><a href="tel:+15551234567">(555) 123-4567</a></dd>
                <dt>Location</dt>
                <dd>123 Print Avenue, Suite 100<br>New York, NY 10001</dd>
                <dt>Working hours</dt>
                <dd>Monday – Friday: 9:00 AM – 6:00 PM<br>Saturday: 10:00 AM – 2:00 PM<br>Sunday: Closed</dd>
            </dl>
            <div class="map-placeholder" role="img" aria-label="Map location placeholder">Map placeholder</div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
