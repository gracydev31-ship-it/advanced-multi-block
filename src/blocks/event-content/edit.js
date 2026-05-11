import { __ } from '@wordpress/i18n';

export default function Edit() {
	return (
		<div className="rp-event-content-block">
			<p>
				<strong>{ __( 'Event Content', 'runpartner' ) }</strong>
			</p>
			<p>
				{ __(
					'Tabbed content: Details, Records, History, and Reports.',
					'runpartner'
				) }
			</p>
		</div>
	);
}
