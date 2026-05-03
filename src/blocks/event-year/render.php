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

$year = get_post_meta( $post_id, '_rp_event_year', true );

if ( ! $year ) {
	return;
}

$year = absint( $year );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-year' ) ); ?>>
	<span class="dashicons dashicons-calendar" aria-hidden="true"></span>
	<span class="runpartner-event-label"><?php echo esc_html__( 'First edition:', 'runpartner' ); ?></span>
	<span class="runpartner-event-year-text"><?php echo esc_html( $year ); ?></span>
</div>
