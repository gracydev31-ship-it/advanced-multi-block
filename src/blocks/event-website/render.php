<?php
$post_id = get_the_ID();
if ( ! $post_id ) {
	return;
}

$website = get_post_meta( $post_id, '_rp_event_website', true );

if ( ! $website ) {
	return;
}

$website = esc_url( $website );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-website' ) ); ?>>
	<div class="wp-block-buttons">
		<div class="wp-block-button is-style-outline">
			<a class="wp-block-button__link wp-element-button" href="<?php echo $website; ?>" target="_blank" rel="noopener noreferrer">
				<?php echo esc_html__( 'Official Website', 'runpartner' ); ?>
			</a>
		</div>
	</div>
</div>
