<?php

namespace HP\Page_Embed;

add_action( 'init', 'HP\Page_Embed\add_shortcode' );

function add_shortcode(){
	\add_shortcode( 'page_embed', 'HP\Page_Embed\display_shortcode' );
}

function display_shortcode( $atts ) {
	$default = array(
		'id' => 0,
		'wrapper' => '',
		'wrapper_id' => '',
		'wrapper_class' => '',
		'container' => 'div',
		'container_id' => '',
		'container_class' => '',
		'featured_image' => 'background',
		'featured_image_size' => 'large',
	);
	$atts = shortcode_atts( $default, $atts );

	if ( 0 === absint( $atts['id'] ) ) {
		return '<!-- Invalid page entered, nothing to embed. -->';
	}

	if ( absint( $atts['id'] ) == get_post()->ID ) {
		return '<!-- Cannot embed page in itself. -->';
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

	if ( ! empty( $wrapper ) ) {
		$content .= '</' . $wrapper . '>';
	}

	return $content;
}
