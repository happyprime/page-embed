<?php
/**
 * Implement the page_embed shortcode.
 *
 * @package Page Embed
 * @since 0.0.1
 */

namespace HP\Page_Embed;

add_action( 'init', 'HP\Page_Embed\add_shortcode' );

/**
 * Register the page_embed shortcode.
 *
 * @since 0.0.1
 */
function add_shortcode() {
	\add_shortcode( 'page_embed', 'HP\Page_Embed\display_shortcode' );
}

/**
 * Display the page_embed shortcode.
 *
 * @since 0.0.1
 *
 * @param array $atts List of attributes passed to the shortcode.
 *
 * @return string Content to display.
 */
function display_shortcode( $atts ) {
	$default = array(
		'id' => 0,
		'wrapper' => '',
		'wrapper_id' => '',
		'wrapper_class' => '',
		'container' => '',
		'container_id' => '',
		'container_class' => '',
		'featured_image' => 'background',
		'featured_image_size' => 'large',
	);
	$atts = shortcode_atts( $default, $atts );

	if ( 0 === absint( $atts['id'] ) ) {
		return '<!-- Invalid page entered, nothing to embed. -->';
	}

	$page = get_post( absint( $atts['id'] ) );

	if ( ! $page ) {
		return '<!-- Page not found, nothing to embed. -->';
	}

	$image_url = get_the_post_thumbnail_url( $page->ID, $atts['featured_image_size'] );
	$wrapper = sanitize_key( $atts['wrapper'] );
	$container = sanitize_key( $atts['container'] );
	$content = '';

	if ( ! empty( $wrapper ) ) {
		$content .= '<' . $wrapper;

		if ( ! empty( $atts['wrapper_id'] ) ) {
			$content .= ' id="' . esc_attr( $atts['wrapper_id'] ) . '"';
		}

		if ( ! empty( $atts['wrapper_class'] ) ) {
			$content .= ' class="' . esc_attr( $atts['wrapper_class'] ) . '"';
		}

		if ( 'background' === $atts['featured_image'] && $image_url ) {
			$content .= ' style="background-image: url(' . esc_url( $image_url ) . ');"';
		}

		$content .= '>';
	}

	if ( ! empty( $container ) ) {
		$content .= '<' . $container;

		if ( ! empty( $atts['container_id'] ) ) {
			$content .= ' id="' . esc_attr( $atts['container_id'] ) . '"';
		}

		if ( ! empty( $atts['container_class'] ) ) {
			$content .= ' class="' . esc_attr( $atts['container_class'] ) . '"';
		}

		if ( 'background-container' === $atts['featured_image'] && $image_url ) {
			$content .= ' style="background-image: url(' . esc_url( $image_url ) . ');"';
		}

		$content .= '>';
	}

	// Prevent embedded pages from embedding pages.
	remove_shortcode( 'page_embed' );

	$content .= apply_filters( 'the_content', wp_kses_post( $page->post_content ) );

	add_shortcode(); // Re-add the shortcode.

	if ( ! empty( $container ) ) {
		$content .= '</' . $container . '>';
	}

	if ( ! empty( $wrapper ) ) {
		$content .= '</' . $wrapper . '>';
	}

	return $content;
}
