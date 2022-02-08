/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import block from '../../block.json';
import Edit from './edit';

/**
 * Registers the dynamic block 'Site Counts'.
 */
registerBlockType( block.name, {
	edit: Edit,

	/**
	 * Leaves the front-end rendering to the PHP 'render_callback', this being a dynamic block.
	 *
	 * @return {null} Delegates front-end rendering to the PHP callback.
	 */
	save: () => null,
} );
