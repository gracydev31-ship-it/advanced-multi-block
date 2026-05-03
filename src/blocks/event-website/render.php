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

$website = get_post_meta( $post_id, '_rp_event_website', true );

if ( ! $website ) {
	return;
}

$website = esc_url( $website );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-website' ) ); ?>>
	<a href="<?php echo $website; ?>" class="runpartner-event-website-button" target="_blank" rel="noopener noreferrer">
		<span class="dashicons dashicons-admin-links" aria-hidden="true"></span>
		<?php echo esc_html__( 'Visit Official Website', 'runpartner' ); ?>
	</a>
</div>
