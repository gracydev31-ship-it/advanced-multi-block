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

$subtitle = get_post_meta( $post_id, '_rp_event_subtitle', true );

if ( ! $subtitle ) {
	return;
}

$subtitle = esc_html( $subtitle );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-subtitle' ) ); ?>>
	<span class="runpartner-event-subtitle-text"><?php echo $subtitle; ?></span>
</div>
