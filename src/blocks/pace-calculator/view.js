import { store } from '@wordpress/interactivity';

function formatTime( totalMinutes ) {
	if ( ! totalMinutes || totalMinutes <= 0 ) return '--:--';
	const hours = Math.floor( totalMinutes / 60 );
	const minutes = Math.floor( totalMinutes % 60 );
	const seconds = Math.round( ( totalMinutes % 1 ) * 60 );
	if ( hours > 0 ) {
		return `${ hours }:${ String( minutes ).padStart( 2, '0' ) }:${ String( seconds ).padStart( 2, '0' ) }`;
	}
	return `${ minutes }:${ String( seconds ).padStart( 2, '0' ) }`;
}

const distances = {
	mi: [
		{ label: '5K', value: 3.107 },
		{ label: '10K', value: 6.214 },
		{ label: 'Half Marathon', value: 13.109 },
		{ label: 'Marathon', value: 26.219 },
	],
	km: [
		{ label: '5K', value: 5 },
		{ label: '10K', value: 10 },
		{ label: 'Half Marathon', value: 21.0975 },
		{ label: 'Marathon', value: 42.195 },
	],
};

const { state } = store( 'runpartner', {
	state: {
		paceMinutes: 8,
		paceSeconds: 0,
		unit: 'km',
		get paceMinutesPerUnit() {
			return state.paceMinutes + state.paceSeconds / 60;
		},
		get unitLabel() {
			return state.unit;
		},
		get unitToggleLabel() {
			return state.unit === 'mi' ? 'Switch to km' : 'Switch to mi';
		},
		get time5K() {
			return formatTime( state.paceMinutesPerUnit * distances[ state.unit ][ 0 ].value );
		},
		get time10K() {
			return formatTime( state.paceMinutesPerUnit * distances[ state.unit ][ 1 ].value );
		},
		get timeHalf() {
			return formatTime( state.paceMinutesPerUnit * distances[ state.unit ][ 2 ].value );
		},
		get timeMarathon() {
			return formatTime( state.paceMinutesPerUnit * distances[ state.unit ][ 3 ].value );
		},
	},
	actions: {
		setPaceMinutes( event ) {
			state.paceMinutes = parseInt( event.target.value ) || 0;
		},
		setPaceSeconds( event ) {
			state.paceSeconds = parseInt( event.target.value ) || 0;
		},
		toggleUnit() {
			state.unit = state.unit === 'mi' ? 'km' : 'mi';
		},
	},
} );
