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
