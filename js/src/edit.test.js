/**
 * External dependencies
 */
import { render } from '@testing-library/react';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import Edit from './edit';

jest.mock( '@wordpress/server-side-render', () =>
	jest.fn( () => <div></div> )
);

jest.mock( '@wordpress/block-editor', () => ( {
	useBlockProps: jest.fn(),
} ) );

describe( 'Edit', () => {
	it( 'renders the ServerSideRender component', () => {
		render( <Edit attributes={ {} } /> );
		expect( ServerSideRender ).toHaveBeenCalled();
	} );
} );
