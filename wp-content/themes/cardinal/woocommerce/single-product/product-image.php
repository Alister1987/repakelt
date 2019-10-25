<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( version_compare( WC_VERSION, '2.7', '>=' ) ) { 

	global $post, $product, $sf_options;
	$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
	$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
	$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
	$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
	$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
	$product_slider_thumbs_pos = "bottom";
	if ( isset( $sf_options['product_slider_thumbs_pos'] ) ) {
			$product_slider_thumbs_pos = $sf_options['product_slider_thumbs_pos'];
	}
	$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . $placeholder,
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'woocommerce-thumb-nav--'. $product_slider_thumbs_pos,
		'images',
	) );
	?>
	<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
		<figure class="woocommerce-product-gallery__wrapper">
			<?php
			$attributes = array(
				'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);
	
			if ( has_post_thumbnail() ) {
				$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
				$html .= get_the_post_thumbnail( $post->ID, 'shop_single', $attributes );
				$html .= '</a></div>';
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src() ), esc_html__( 'Awaiting product image', 'woocommerce' ) );
				$html .= '</div>';
			}
	
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, get_post_thumbnail_id( $post->ID ) );
	
			do_action( 'woocommerce_product_thumbnails' );
			?>
		</figure>
	</div>
	
<?php } else {

    global $post, $woocommerce, $product, $sf_options;

    $attachment_ids      = array();
    $product_layout      = sf_get_post_meta( $post->ID, 'sf_product_layout', true );
    $product_image_width = apply_filters( 'sf_product_image_width', 600 );

    if ( $product_layout == "fw-split" ) {
        $product_image_width = apply_filters( 'sf_product_fw_image_width', 1200 );
    }

?>
	<div class="images">
	
	    <div id="product-img-slider" class="flexslider">
	        <div class="badge-wrap">
	            <?php
	
	                $postdate      = get_the_time( 'Y-m-d' );            // Post date
	                $postdatestamp = strtotime( $postdate );            // Timestamped post date
	                $newness       = $sf_options['new_badge'];    // Newness in days
	
	                if ( sf_is_out_of_stock() ) {
	
	                    echo '<span class="out-of-stock-badge">' . __( 'Out of Stock', 'swiftframework' ) . '</span>';
	
	                } else if ( $product->is_on_sale() ) {
	
	                    echo apply_filters( 'woocommerce_sale_flash', '<span class="onsale">' . __( 'Sale!', 'swiftframework' ) . '</span>', $post, $product );
	
	                } else if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) {
	
	                    // If the product was published within the newness time frame display the new badge
	                    echo '<span class="wc-new-badge">' . __( 'New', 'swiftframework' ) . '</span>';
	
	                } else if ( ! $product->get_price() ) {
	
	                    echo '<span class="free-badge">' . __( 'Free', 'swiftframework' ) . '</span>';
	
	                }
	            ?>
	        </div>
	        <ul class="slides">
	            <?php
	                if ( has_post_thumbnail() ) {
	
	                    $image_object = get_the_post_thumbnail( $post->ID, 'full' );
	                    $image_title  = esc_attr( get_the_title( get_post_thumbnail_id() ) );
	                    $image_alt    = esc_attr( sf_get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) );
	                    $image_link   = wp_get_attachment_url( get_post_thumbnail_id() );
	
	                    $image = sf_aq_resize( $image_link, $product_image_width, null, true, false );
	
	                    if ( $image ) {
	
	                        $image_html = '<img class="product-slider-image" data-zoom-image="' . $image_link . '" src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . $image_alt . '" title="' . $image_title . '" />';
	
	                        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<li itemprop="image">%s<a href="%s" itemprop="image" class="woocommerce-main-image zoom lightbox" data-rel="ilightbox[product]" title="%s" alt="%s"><i class="ss-expand"></i></a></li>', $image_html, $image_link, $image_title, $image_alt ), $post->ID );
	
	                    }
	
	                }
	
	                $loop    = 0;
	                $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	
	                $attachment_ids = $product->get_gallery_attachment_ids();
	
	                if ( $attachment_ids ) {
	
	                    foreach ( $attachment_ids as $attachment_id ) {
	
	                        $classes = array( 'zoom' );
	
	                        if ( $loop == 0 || $loop % $columns == 0 ) {
	                            $classes[] = 'first';
	                        }
	
	                        if ( ( $loop + 1 ) % $columns == 0 ) {
	                            $classes[] = 'last';
	                        }
	
	                        $image_link = wp_get_attachment_url( $attachment_id );
	
	                        if ( ! $image_link ) {
	                            continue;
	                        }
	
	                        $image = sf_aq_resize( $image_link, $product_image_width, null, true, false );
	
	                        $image_class = esc_attr( implode( ' ', $classes ) );
	                        $image_title = esc_attr( get_the_title( $attachment_id ) );
	                        $image_alt   = esc_attr( sf_get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) );
	
	                        if ( $image ) {
	
	                            $image_html = '<img class="product-slider-image" data-zoom-image="' . $image_link . '" src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . $image_alt . '" title="' . $image_title . '" />';
	
	                            echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<li>%s<a href="%s" class="%s lightbox" data-rel="ilightbox[product]" title="%s" alt="%s"><i class="ss-expand"></i></a></li>', $image_html, $image_link, $image_class, $image_title, $image_alt ), $attachment_id, $post->ID, $image_class );
	
	                        }
	
	                        $loop ++;
	                    }
	
	                }
	            ?>
	        </ul>
	    </div>
	
	    <?php if ( $attachment_ids ) { ?>
	        <div id="product-img-nav" class="flexslider">
	            <ul class="slides">
	                <?php if ( has_post_thumbnail() ) { ?>
	                    <li><?php echo get_the_post_thumbnail( $post->ID, 'shop_thumbnail' ); ?></li>
	                <?php } ?>
	                <?php do_action( 'woocommerce_product_thumbnails' ); ?>
	            </ul>
	        </div>
	    <?php } ?>
	
	</div>

<?php } ?>