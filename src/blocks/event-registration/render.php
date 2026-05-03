<?php
/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

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
		<span class="dashicons dashicons-clipboard" aria-hidden="true"></span>
		<?php echo esc_html__( 'Register Now', 'runpartner' ); ?>
	</a>
</div>
