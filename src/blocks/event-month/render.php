<?php
$post_id = get_the_ID();
if ( ! $post_id ) {
	return;
}

$month = get_post_meta( $post_id, '_rp_event_month', true );

if ( ! $month ) {
	return;
}

$month = esc_html( $month );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-month' ) ); ?>>
	<span class="runpartner-event-meta-label"><?php echo esc_html__( 'Held in', 'runpartner' ); ?></span>
	<span class="runpartner-event-month-text"><?php echo $month; ?></span>
</div>
