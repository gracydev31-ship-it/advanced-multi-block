import { __ } from '@wordpress/i18n';

export default function Edit() {
	return (
		<div className="rp-pace-calculator-block">
			<p><strong>{ __( 'Pace Calculator', 'runpartner' ) }</strong></p>
			<p>{ __( 'Set your pace to see finish times for race distances.', 'runpartner' ) }</p>
		</div>
	);
}
