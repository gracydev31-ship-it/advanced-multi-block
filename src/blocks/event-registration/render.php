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
	<div class="wp-block-buttons">
		<div class="wp-block-button">
			<a class="wp-block-button__link wp-element-button" href="<?php echo $registration; ?>" target="_blank" rel="noopener noreferrer">
				<?php echo esc_html__( 'Register Now', 'runpartner' ); ?>
			</a>
		</div>
	</div>
</div>
