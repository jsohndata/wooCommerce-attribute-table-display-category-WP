# WooCommerce: Add Linked **Category** to “Additional Information” Table (Alphabetized) — WPCode-Compatible

🤖 **AI-Enhanced (GPT-5)** WooCommerce snippet that adds a **Category** row to the **Additional Information** attributes table, auto-links each category to its archive, and **alphabetizes all rows** (ASC by label).  
Lightweight, translation-ready, and compatible with WPCode, Code Snippets, or a child theme’s `functions.php`.

## Working Sample
[Sansefuria.com](https://sansefuria.com/plants/sansevieria-cylindrica-african-spear-plant-medium-cbsan02/)

---

## Features

- Adds **Category** to the attributes table (Additional Information tab)
- Each category name links to its archive (comma-separated if multiple)
- **Alphabetizes** all attribute rows by label (ascending)
- Theme-agnostic, clean, no template overrides
- Translation-ready with `__('Category', 'woocommerce')`

---

## Requirements

- WordPress with WooCommerce active
- One of the following:
  - [WPCode plugin](https://wordpress.org/plugins/wpcode/) (recommended)
  - Code Snippets plugin
  - A child theme with access to `functions.php`

---

## Installation

### Option 1: WPCode (Recommended)

1. Install and activate the **WPCode** plugin
2. Navigate to **Code Snippets → Add New**
3. Choose **"Add Your Custom Code (New Snippet)"**
4. Select **PHP Snippet**
5. Name it: `Add Category to Additional Info`
6. Paste the code from the **Code** section below
7. Set **Location** to `Run Everywhere`
8. Save and **Activate**

### Option 2: Add to `functions.php`

1. Open your child theme’s `functions.php` file
2. Paste the same PHP code at the end
3. Save the file

---

## Code

```php
/**
 * Add a linked "Category" row to the WooCommerce Additional Information table
 * and alphabetize all attribute rows by their labels (ASC).
 */
add_filter('woocommerce_display_product_attributes', 'jsd_add_category_attribute_row', 10, 2);

function jsd_add_category_attribute_row( $attributes, $product ) {
    $terms = get_the_terms( $product->get_id(), 'product_cat' );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        // Build linked category names
        $category_links = array_map( function( $term ) {
            $link = get_term_link( $term );
            if ( is_wp_error( $link ) ) {
                return esc_html( $term->name );
            }
            return '<a href="' . esc_url( $link ) . '">' . esc_html( $term->name ) . '</a>';
        }, $terms );

        // Add the Category row (key is arbitrary; Woo uses the 'label' for display)
        $attributes['jsd_product_category'] = array(
            'label'   => __( 'Category', 'woocommerce' ),
            'value'   => implode( ', ', $category_links ),
            'display' => '',
        );
    }

    // Sort all rows by their label (ascending, case-sensitive)
    uasort( $attributes, function( $a, $b ) {
        return strcmp( wp_strip_all_tags( $a['label'] ), wp_strip_all_tags( $b['label'] ) );
    } );

    return $attributes;
}
