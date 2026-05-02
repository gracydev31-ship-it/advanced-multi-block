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

$location = get_post_meta( $post_id, '_rp_event_location', true );
$country  = get_post_meta( $post_id, '_rp_event_country', true );

if ( ! $location && ! $country ) {
	return;
}

$location = esc_html( $location );
$country  = esc_html( $country );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-location' ) ); ?>>
	<?php if ( $location ) : ?>
		<p class="runpartner-event-location-name">
			<span class="dashicons dashicons-location" aria-hidden="true"></span>
			<?php echo $location; ?>
		</p>
	<?php endif; ?>

	<?php if ( $country ) : ?>
		<p class="runpartner-event-location-country">
			<?php echo $country; ?>
		</p>
	<?php endif; ?>
</div>
