<?php
$post_id = get_the_ID();
if ( ! $post_id ) {
	return;
}

$registration = get_post_meta( $post_id, '_rp_event_registration', true );

if ( ! $registration ) {
	return;
}

$registration = esc_url( $registration );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-registration' ) ); ?>>
	<a href="<?php echo $registration; ?>" class="runpartner-event-registration-button" target="_blank" rel="noopener noreferrer">
		<?php echo esc_html__( 'Register Now', 'runpartner' ); ?>
	</a>
</div>
