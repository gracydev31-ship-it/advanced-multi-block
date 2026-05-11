import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="coach-athletes-editor-placeholder">
				<div className="coach-athletes-editor-label">
					{ __( 'Coach Athletes', 'runpartner' )}
				</div>
				<div className="coach-athletes-editor-desc">
					{ __( 'Displays athletes trained by this coach as clickable profile cards.', 'runpartner' )}
				</div>
			</div>
		</div>
	);
}
