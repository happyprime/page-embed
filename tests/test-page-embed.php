<?php
/**
 * Class PageEmbedTest
 *
 * @package Page Embed
 */

/**
 * Tests for the page_embed shortcode.
 */
class PageEmbedTest extends WP_UnitTestCase {

	function test_page_embed_with_no_wrapper_or_container() {
		$embedded = wp_insert_post( array(
			'post_type' => 'page',
			'post_content' => 'This is my sample content.',
		) );

		$page = wp_insert_post( array(
			'post_type' => 'page',
			'post_content' => '[page_embed id=' . $embedded . ']',
		) );

		$page_content = apply_filters( 'the_content', get_post( $page )->post_content );
		$expected_content = '<p>This is my sample content.</p>';

		$this->assertEquals( $expected_content, $page_content );
	}
}
