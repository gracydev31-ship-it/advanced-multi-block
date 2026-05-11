import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="athlete-header-editor-placeholder">
				<div className="athlete-header-editor-label">
					{ __( 'Athlete Header', 'runpartner' )}
				</div>
				<div className="athlete-header-editor-desc">
					{ __( 'Displays title, subtitle, nationality, years, disciplines, and achievements from athlete meta.', 'runpartner' )}
				</div>
			</div>
		</div>
	);
}
