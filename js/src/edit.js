/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import { name } from '../../block.json';

/*
 * The edit function for the block editor display.
 *
 * @param {Object} props The properties.
 * @return {Function} The block editor display.
 */
const Edit = ( { attributes } ) => {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<ServerSideRender block={ name } attributes={ attributes } />
		</div>
	);
};

export default Edit;
