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

$distances = get_post_meta( $post_id, '_rp_event_distances', true );

if ( empty( $distances ) ) {
	return;
}

// Handle both array and comma-separated string formats
if ( is_array( $distances ) ) {
	$distance_list = $distances;
} else {
	$distance_list = explode( ',', $distances );
}

$distance_list = array_map( 'trim', $distance_list );
$distance_list = array_filter( $distance_list );
?>

<div <?php echo get_block_wrapper_attributes( array( 'class' => 'runpartner-event-distances' ) ); ?>>
	<span class="runpartner-event-label"><?php echo esc_html__( 'Distances:', 'runpartner' ); ?></span>
	<span class="dashicons dashicons-list-view" aria-hidden="true"></span>
	<ul class="runpartner-event-distances-list">
		<?php foreach ( $distance_list as $distance ) : ?>
			<li class="runpartner-event-distance-item"><?php echo esc_html( $distance ); ?></li>
		<?php endforeach; ?>
	</ul>
</div>
