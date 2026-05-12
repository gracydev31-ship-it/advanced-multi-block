import { __ } from '@wordpress/i18n';

export default function Edit() {
	return (
		<div className="rp-event-archive-block">
			<p>
				<strong>{ __( 'Events Archive', 'runpartner' ) }</strong>
			</p>
			<p>
				{ __(
					'Featured event hero, upcoming events, and race recaps.',
					'runpartner'
				) }
			</p>
		</div>
	);
}
