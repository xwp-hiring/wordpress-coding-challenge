<?php
/**
 * Block class.
 *
 * @package SiteCounts
 */

namespace XWP\SiteCounts;

use WP_Block;
use WP_Query;

/**
 * The Site Counts dynamic block.
 *
 * Registers and renders the dynamic block.
 */
class Block {

	/**
	 * The Plugin instance.
	 *
	 * @var Plugin
	 */
	protected $plugin;

	/**
	 * Instantiates the class.
	 *
	 * @param Plugin $plugin The plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Adds the action to register the block.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Registers the block.
	 */
	public function register_block() {
		register_block_type_from_metadata(
			$this->plugin->dir(),
			[
				'render_callback' => [ $this, 'render_callback' ],
			]
		);
	}

	/**
	 * Callback function for array_map over wp post type objects
	 *
	 * @param \WP_Post_Type $post_type The post type we are currently looping over.
	 *
	 * @return string
	 */
	public function decorate_count_posts( \WP_Post_Type $post_type ): string {
		$publish_count = wp_count_posts( $post_type->name )->publish ?? 0;

		if ( 1 != $publish_count ) {
			// translators: %d is a number and %s is a label's plural name.
			$output = sprintf( __( 'There are %1$d %2$s.', 'site-counts' ), $publish_count, $post_type->labels->name );
		} else {
			// translators: %s is the label's singular name.
			$output = sprintf( __( 'There is 1 %s.', 'site-counts' ), $post_type->labels->singular_name );
		}

		return sprintf( '<li>%s</li>', $output );
	}

	/**
	 * Returns HTML output for the post counts section
	 *
	 * @return string
	 */
	public function count_posts_section(): string {
		$post_types  = get_post_types( [ 'public' => true ], 'object' );
		$post_counts = array_map( [ $this, 'decorate_count_posts' ], $post_types );

		if ( count( $post_counts ) > 0 ) {
			$output = sprintf( '<ul>%s</ul>', implode( $post_counts ) );
		} else {
			$output = sprintf( '<p>%s</p>', __( ' No posts found', 'site-counts' ) );
		}

		return sprintf( '<h2>%1$s</h2>%2$s', __( 'Post Counts', 'site-counts' ), $output );
	}

	/**
	 * Returns HTML output for the current post section
	 *
	 * @return string
	 */
	public function current_post_section(): string {
		// translators: %d is a post ID (number).
		$format = sprintf( __( 'The current post ID is %d.', 'site-counts' ), get_the_ID() );

		return sprintf( '<p>%s</p>', $format );
	}

	/**
	 * Returns HTML output for the query posts section
	 *
	 * @return string
	 */
	public function query_posts_section(): string {
		/*
		 * Query returns a maximum of 5 posts/pages of any status, tagged with 'foo' and categorized as 'baz',
		 * written between 9AM to 5PM, excluding the current post
		 */
		$query = new WP_Query(
			[
				'post_type'      => [ 'post', 'page' ],
				'post_status'    => 'any',
				'date_query'     => [
					[
						'hour'    => 9,
						'compare' => '>=',
					],
					[
						'hour'    => 17,
						'compare' => '<=',
					],
				],
				'tag'            => 'foo',
				'category_name'  => 'baz',
				'posts_per_page' => 5,
				'post__not_in'   => [ get_the_ID() ],
			]
		);

		$query_posts = [];
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$query_posts[] = sprintf( '<li>%s</li>', get_the_title() );
			}
		}
		wp_reset_postdata();

		if ( 0 === count( $query_posts ) ) {
			return sprintf( '<p>%s</p>', __( 'Sorry, no posts matched your criteria.', 'site-counts' ) );
		}

		$headline = __( '5 posts with the tag of foo and the category of baz', 'site-counts' );

		return sprintf( '<h2>%s</h2><ul>%s</ul>', $headline, implode( $query_posts ) );
	}

	/**
	 * Renders the block.
	 *
	 * @param array    $attributes The attributes for the block.
	 * @param string   $content    The block content, if any.
	 * @param WP_Block $block      The instance of this block.
	 * @return string The markup of the block.
	 */
	public function render_callback( array $attributes, string $content, WP_Block $block ): string {
		$class_name = $attributes['className'] ?? '';

		return sprintf(
			'<div class="%1$s">%2$s %3$s %4$s</div>',
			esc_attr( $class_name ),
			$this->count_posts_section(),
			$this->current_post_section(),
			$this->query_posts_section()
		);
	}
}
