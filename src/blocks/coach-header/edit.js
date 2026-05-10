import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="coach-header-editor-placeholder">
				<div className="coach-header-editor-label">
					{ __( 'Coach Header', 'runpartner' )}
				</div>
				<div className="coach-header-editor-desc">
					{ __( 'Displays title, subtitle, years, nationality, era, training philosophy, notable athletes, and key contributions from coach meta.', 'runpartner' )}
				</div>
			</div>
		</div>
	);
}
