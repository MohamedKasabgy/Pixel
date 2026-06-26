#!/usr/bin/env bash
# Pixel Signs & Printing — Local Page Setup Script
# Automatically creates and updates pages with the correct slugs and templates via WP-CLI.

set -euo pipefail

# Ensure WP-CLI is available
if ! command -v wp &> /dev/null; then
    echo "Error: wp command (WP-CLI) is not installed or not in your PATH."
    echo "Please run this script inside the LocalWP Site Shell or ensure wp is available."
    exit 1
fi

echo "Initializing Pixel local page setup..."

# Configure permalinks for clean URLs
echo "Configuring permalinks..."
wp permalink structure '/%postname%/'

# Function to safely create or update a page
ensure_page() {
    local title="$1"
    local slug="$2"
    local template="$3"

    # Check if a page with the given slug already exists (any status)
    local post_id
    post_id=$(wp post list --post_type=page --name="$slug" --post_status=any --format=ids 2>/dev/null)

    if [ -n "$post_id" ]; then
        echo " - Page '$title' (/$slug/) already exists with ID $post_id. Ensuring published status..."
        wp post update "$post_id" --post_status=publish > /dev/null
        if [ -n "$template" ]; then
            echo "   Assigning template: $template..."
            wp post meta update "$post_id" _wp_page_template "$template" > /dev/null
        fi
    else
        echo " - Creating page '$title' (/$slug/)..."
        if [ -n "$template" ]; then
            post_id=$(wp post create --post_type=page --post_title="$title" --post_name="$slug" --post_status=publish --porcelain)
            wp post meta update "$post_id" _wp_page_template "$template" > /dev/null
        else
            wp post create --post_type=page --post_title="$title" --post_name="$slug" --post_status=publish > /dev/null
        fi
    fi
}

# Ensure required pages with templates
ensure_page "About Us" "about" "page-templates/about.php"
ensure_page "Contact Us" "contact" "page-templates/contact.php"
ensure_page "FAQ" "faq" "page-templates/faq.php"
ensure_page "Privacy Policy" "privacy-policy" "page-templates/privacy-policy.php"
ensure_page "Terms & Conditions" "terms-conditions" "page-templates/terms.php"
ensure_page "Refund Policy" "refund-policy" "page-templates/refund-policy.php"
ensure_page "Shipping & Delivery" "shipping-delivery-policy" "page-templates/shipping-delivery.php"
ensure_page "Pickup Locations" "pickup-locations" "page-templates/pickup-locations.php"
ensure_page "Client Dashboard" "client-dashboard" "page-templates/client-dashboard.php"
ensure_page "Order Tracking" "order-tracking" "page-templates/order-tracking.php"
ensure_page "Upload Artwork" "upload-artwork" "page-templates/upload-artwork.php"
ensure_page "Request Quote" "request-quote" "page-templates/request-quote.php"

# Ensure standard WooCommerce pages (slugs cart and checkout)
ensure_page "Cart" "cart" ""
ensure_page "Checkout" "checkout" ""

echo "Pixel local page setup completed successfully."
