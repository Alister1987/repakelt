<?php
    /**
     * Product Loop Start
     *
     * @author        WooThemes
     * @package       WooCommerce/Templates
     * @version       2.0.0
     */
    global $sf_options;
    $product_display_type    = $sf_options['product_display_type'];
    $product_display_gutters = $sf_options['product_display_gutters'];
?>
<?php if ( ! $product_display_gutters && ( $product_display_type == "gallery" || $product_display_type == "gallery-bordered" ) ) { ?>
    <ul id="products" class="products product-grid no-gutters product-type-<?php echo $product_display_type; ?> clearfix">
<?php } else { ?>
    <ul id="products" class="products product-grid gutters row product-type-<?php echo $product_display_type; ?> clearfix">
<?php } ?>