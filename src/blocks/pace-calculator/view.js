import { store, getContext, getElement } from '@wordpress/interactivity';

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

function generateAllDistances() {
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
	result[ 4 ] = { label: '5K', km: 5, mi: 3.107, race: true };
	result[ 9 ] = { label: '10K', km: 10, mi: 6.214, race: true };
	result[ 20 ] = { label: 'Half Marathon', km: 21.0975, mi: 13.109, race: true };
	result[ 41 ] = { label: 'Marathon', km: 42.195, mi: 26.219, race: true };
	return result;
}

const keyDistances = [
	{ label: '5K', km: 5, mi: 3.107, race: true },
	{ label: '10K', km: 10, mi: 6.214, race: true },
	{ label: 'Half Marathon', km: 21.0975, mi: 13.109, race: true },
	{ label: 'Marathon', km: 42.195, mi: 26.219, race: true },
];

const allDistances = generateAllDistances();
const offsets = [ -10, -5, 0, 5, 10 ];

function getValue( d, unit ) {
	return unit === 'km' ? d.km : d.mi;
}

function generateRows( distancesList, paceSeconds, unit ) {
	return distancesList.map( ( d ) => {
		const cells = offsets.map( ( offset ) => {
			const totalSeconds = paceSeconds + offset;
			if ( totalSeconds <= 0 ) return '<td>--:--</td>';
			const totalMinutes = ( totalSeconds / 60 ) * getValue( d, unit );
			return '<td>' + formatTime( totalMinutes ) + '</td>';
		} ).join( '' );
		const cls = d.race ? ' class="rp-row-race"' : '';
		return '<tr' + cls + '><td>' + d.label + '</td>' + cells + '</tr>';
	} ).join( '' );
}

function getCurrentPaceSeconds() {
	const context = getContext();
	return ( context.paceMinutes || 0 ) * 60 + ( context.paceSeconds || 0 );
}

store( 'runpartner', {
	state: {
		get unitLabel() {
			const context = getContext();
			return context.unit || 'km';
		},
		get unitToggleLabel() {
			const context = getContext();
			return context.unit === 'mi' ? 'Switch to km' : 'Switch to mi';
		},
		get toggleLabel() {
			const context = getContext();
			return context.showFullTable ? 'Hide full breakdown' : 'Show full 1K–50K breakdown';
		},
		get col0() { return formatPace( getCurrentPaceSeconds() + offsets[ 0 ] ); },
		get col1() { return formatPace( getCurrentPaceSeconds() + offsets[ 1 ] ); },
		get col2() { return formatPace( getCurrentPaceSeconds() + offsets[ 2 ] ); },
		get col3() { return formatPace( getCurrentPaceSeconds() + offsets[ 3 ] ); },
		get col4() { return formatPace( getCurrentPaceSeconds() + offsets[ 4 ] ); },
	},
	actions: {
		setPaceMinutes( event ) {
			const context = getContext();
			context.paceMinutes = parseInt( event.target.value ) || 0;
		},
		setPaceSeconds( event ) {
			const context = getContext();
			context.paceSeconds = parseInt( event.target.value ) || 0;
		},
		toggleUnit() {
			const context = getContext();
			context.unit = context.unit === 'mi' ? 'km' : 'mi';
		},
		toggleFullTable() {
			const context = getContext();
			context.showFullTable = ! context.showFullTable;
		},
	},
	callbacks: {
		renderRows() {
			const { ref } = getElement();
			if ( ! ref ) return;

			const context = getContext();
			const paceSeconds = ( context.paceMinutes || 0 ) * 60 + ( context.paceSeconds || 0 );
			const unit = context.unit || 'km';

			const keyTbody = ref.querySelector( '.rp-key-tbody' );
			const fullTbody = ref.querySelector( '.rp-full-tbody' );

			const keyHtml = generateRows( keyDistances, paceSeconds, unit );
			const fullHtml = generateRows( allDistances, paceSeconds, unit );

			if ( keyTbody ) keyTbody.innerHTML = keyHtml;
			if ( fullTbody ) fullTbody.innerHTML = fullHtml;
		},
	},
} );
