import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
	return (
		<p { ...useBlockProps() }>
			{ __( 'Event Hero Meta – displays the date and location from event meta.', 'runpartner' ) }
		</p>
	);
}
