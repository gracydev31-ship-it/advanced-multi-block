<?php
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
