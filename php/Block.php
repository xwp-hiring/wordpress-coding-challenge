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
	 * Renders the block.
	 *
	 * @param array    $attributes The attributes for the block.
	 * @param string   $content    The block content, if any.
	 * @param WP_Block $block      The instance of this block.
	 * @return string The markup of the block.
	 */
	public function render_callback( $attributes, $content, $block ) {
		$post_types = get_post_types(  [ 'public' => true ] );
		$class_name = $attributes['className'];
		ob_start();

		?>
        <div class="<?php echo $class_name; ?>">
			<h2>Post Counts</h2>
			<ul>
			<?php
			foreach ( $post_types as $post_type_slug ) :
                $post_type_object = get_post_type_object( $post_type_slug  );
                $post_count = count(
                    get_posts(
						[
							'post_type' => $post_type_slug,
							'posts_per_page' => -1,
						]
					)
                );

				?>
				<li><?php echo 'There are ' . $post_count . ' ' .
					  $post_type_object->labels->name . '.'; ?></li>
			<?php endforeach;	?>
			</ul><p><?php echo 'The current post ID is ' . $_GET['post_id'] . '.'; ?></p>

			<?php
			$query = new WP_Query(  array(
				'post_type' => ['post', 'page'],
				'post_status' => 'any',
				'date_query' => array(
					array(
						'hour'      => 9,
						'compare'   => '>=',
					),
					array(
						'hour' => 17,
						'compare'=> '<=',
					),
				),
                'tag'  => 'foo',
                'category_name'  => 'baz',
				  'post__not_in' => [ get_the_ID() ],
			));

			if ( $query->found_posts ) :
				?>
				 <h2>5 posts with the tag of foo and the category of baz</h2>
                <ul>
                <?php

                 foreach ( array_slice( $query->posts, 0, 5 ) as $post ) :
                    ?><li><?php echo $post->post_title ?></li><?php
				endforeach;
			endif;
		 	?>
			</ul>
		</div>
		<?php

		return ob_get_clean();
	}
}
