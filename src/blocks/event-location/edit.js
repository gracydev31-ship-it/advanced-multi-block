/**
 * WordPress dependencies
 */
import { __, _x } from '@wordpress/i18n';

/**
 * Block edit function
 */
export default function Edit() {
	return (
		<div className="rp-event-location-block">
			<p>
				<strong>{ __( 'Event Location', 'runpartner' ) }</strong>
			</p>
			<p>
				{ __(
					'This block displays location and country from event meta.',
					'runpartner'
				) }
			</p>
		</div>
	);
}
