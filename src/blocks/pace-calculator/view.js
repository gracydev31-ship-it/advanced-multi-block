import { store } from '@wordpress/interactivity';

function formatTime( totalMinutes ) {
	if ( ! totalMinutes || totalMinutes <= 0 ) return '--:--';
	const totalSeconds = Math.round( totalMinutes * 60 );
	const hours = Math.floor( totalSeconds / 3600 );
	const minutes = Math.floor( ( totalSeconds % 3600 ) / 60 );
	const seconds = totalSeconds % 60;
	if ( hours > 0 ) {
		return `${ hours }:${ String( minutes ).padStart( 2, '0' ) }:${ String( seconds ).padStart( 2, '0' ) }`;
	}
	return `${ minutes }:${ String( seconds ).padStart( 2, '0' ) }`;
}

function formatPace( totalSeconds ) {
	if ( totalSeconds <= 0 ) return '0:00';
	const minutes = Math.floor( totalSeconds / 60 );
	const seconds = totalSeconds % 60;
	return `${ minutes }:${ String( seconds ).padStart( 2, '0' ) }`;
}

function generateDistances() {
	const raceKm = [ 5, 10, 21.0975, 42.195 ];
	const raceLabels = [ '5K', '10K', 'Half Marathon', 'Marathon' ];
	const result = [];
	for ( let i = 1; i <= 50; i++ ) {
		const km = i;
		const label = km + 'K';
		const raceIdx = raceKm.findIndex( r => Math.abs( r - km ) < 0.01 );
		result.push( {
			label: raceIdx !== -1 ? raceLabels[ raceIdx ] : label,
			km,
			mi: km * 0.621371,
			race: raceIdx !== -1,
		} );
	}
	// Add exact race distance entries (override the integer approximations)
	result[ 4 ] = { label: '5K', km: 5, mi: 3.107, race: true };
	result[ 9 ] = { label: '10K', km: 10, mi: 6.214, race: true };
	result[ 20 ] = { label: 'Half Marathon', km: 21.0975, mi: 13.109, race: true };
	result[ 41 ] = { label: 'Marathon', km: 42.195, mi: 26.219, race: true };
	return result;
}

const distances = generateDistances();

const offsets = [ -10, -5, 0, 5, 10 ];

function getValue( d, unit ) {
	return unit === 'km' ? d.km : d.mi;
}

const { state } = store( 'runpartner', {
	state: {
		paceMinutes: 8,
		paceSeconds: 0,
		unit: 'km',
		get unitLabel() {
			return state.unit;
		},
		get unitToggleLabel() {
			return state.unit === 'mi' ? 'Switch to km' : 'Switch to mi';
		},
		get col0() { return formatPace( state.paceMinutes * 60 + state.paceSeconds + offsets[ 0 ] ); },
		get col1() { return formatPace( state.paceMinutes * 60 + state.paceSeconds + offsets[ 1 ] ); },
		get col2() { return formatPace( state.paceMinutes * 60 + state.paceSeconds + offsets[ 2 ] ); },
		get col3() { return formatPace( state.paceMinutes * 60 + state.paceSeconds + offsets[ 3 ] ); },
		get col4() { return formatPace( state.paceMinutes * 60 + state.paceSeconds + offsets[ 4 ] ); },
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
	callbacks: {
		renderRows() {
			const tbody = document.querySelector( '.wp-block-runpartner-pace-calculator .rp-pace-table tbody' );
			if ( ! tbody ) return;

			const paceSeconds = state.paceMinutes * 60 + state.paceSeconds;
			const rows = distances.map( ( d ) => {
				const cells = offsets.map( ( offset ) => {
					const totalSeconds = paceSeconds + offset;
					if ( totalSeconds <= 0 ) return '<td>--:--</td>';
					const totalMinutes = ( totalSeconds / 60 ) * getValue( d, state.unit );
					return '<td>' + formatTime( totalMinutes ) + '</td>';
				} ).join( '' );
				const cls = d.race ? ' class="rp-row-race"' : '';
				return '<tr' + cls + '><td>' + d.label + '</td>' + cells + '</tr>';
			} ).join( '' );

			tbody.innerHTML = rows;
		},
	},
} );
