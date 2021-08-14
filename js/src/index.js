/**
 * WordPress dependencies
 */
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import { apiVersion, category, icon, name } from '../../block.json';
import Edit from './edit';

/**
 * Registers the dynamic block 'Site Counts'.
 */
registerBlockType( name, {
	apiVersion,
	title: __( 'Site Counts', 'site-counts' ),
	description: __( 'Counts of posts and taxonomies', 'site-counts' ),
	category,
	icon,
	keywords: [
		__( 'data', 'site-counts' ),
		__( 'statistics', 'site-counts' ),
	],
	edit: Edit,

	/**
	 * Leaves the front-end rendering to the PHP 'render_callback', this being a dynamic block.
	 *
	 * @return {null} Delegates front-end rendering to the PHP callback.
	 */
	save: () => null,
} );
