<?php
$post_id = get_the_ID();
if ( ! $post_id ) {
	return;
}

$location      = get_post_meta( $post_id, '_rp_event_location', true );
$country       = get_post_meta( $post_id, '_rp_event_country', true );
$event_date    = get_post_meta( $post_id, '_rp_event_date', true );
$event_date_end = get_post_meta( $post_id, '_rp_event_date_end', true );

$location_output = '';
if ( $location || $country ) {
	$location_output = esc_html( trim( $location . ( $location && $country ? ', ' : '' ) . $country ) );
}

$date_output = '';
if ( ! empty( $event_date ) ) {
	$format_date_range = function ( string $start, string $end = '' ): string {
		if ( empty( $start ) ) {
			return '';
		}
		try {
			$start_dt = new DateTime( $start );
			if ( empty( $end ) ) {
				return date_i18n( 'F j, Y', $start_dt->getTimestamp() );
			}
			$end_dt     = new DateTime( $end );
			$start_month = $start_dt->format( 'n' );
			$end_month   = $end_dt->format( 'n' );
			$start_year  = $start_dt->format( 'Y' );
			$end_year    = $end_dt->format( 'Y' );
			if ( $start_year !== $end_year ) {
				return date_i18n( 'F j, Y', $start_dt->getTimestamp() ) . ' – ' . date_i18n( 'F j, Y', $end_dt->getTimestamp() );
			}
			if ( $start_month !== $end_month ) {
				return date_i18n( 'F j', $start_dt->getTimestamp() ) . ' – ' . date_i18n( 'F j, Y', $end_dt->getTimestamp() );
			}
			return date_i18n( 'F j', $start_dt->getTimestamp() ) . '–' . date_i18n( 'j, Y', $end_dt->getTimestamp() );
		} catch ( Exception $e ) {
			return $start;
		}
	};
	$date_output = esc_html( $format_date_range( $event_date, $event_date_end ) );
}

if ( ! $location_output && ! $date_output ) {
	return;
}

$parts = array_filter( [ $location_output, $date_output ] );
$text  = implode( ' · ', $parts );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-hero-meta' ) ); ?>>
	<span class="runpartner-event-hero-meta-text"><?php echo $text; ?></span>
</div>
