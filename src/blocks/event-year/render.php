<?php
$post_id = get_the_ID();
if ( ! $post_id ) {
	return;
}

$year = get_post_meta( $post_id, '_rp_event_year', true );

if ( ! $year ) {
	return;
}

$year = absint( $year );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-year' ) ); ?>>
	<span class="runpartner-event-meta-label"><?php echo esc_html__( 'Since', 'runpartner' ); ?></span>
	<span class="runpartner-event-year-text"><?php echo esc_html( $year ); ?></span>
</div>
