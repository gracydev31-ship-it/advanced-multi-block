<?php
$coach_id = get_the_ID();

if (! $coach_id) {
    return '';
}

$athletes = get_posts([
    'post_type'      => 'athlete',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
    'meta_query'     => [
        [
            'key'   => '_rp_athlete_coach',
            'value' => $coach_id,
        ],
    ],
]);

if (empty($athletes)) {
    return '';
}
?>
<div <?php echo get_block_wrapper_attributes(['class' => 'runpartner-coach-athletes']); ?>>

    <div class="coach-athletes-title"><?php echo esc_html__('Athletes', 'runpartner'); ?></div>

    <div class="coach-athletes-grid">
        <?php foreach ($athletes as $athlete) : ?>
            <a class="coach-athlete-card" href="<?php echo esc_url(get_permalink($athlete->ID)); ?>">
                <?php if (has_post_thumbnail($athlete->ID)) : ?>
                    <?php echo get_the_post_thumbnail($athlete->ID, 'thumbnail', ['alt' => esc_attr(get_the_title($athlete->ID))]); ?>
                <?php else : ?>
                    <span class="coach-athlete-card-avatar-placeholder"></span>
                <?php endif; ?>
                <span class="coach-athlete-card-name"><?php echo esc_html(get_the_title($athlete->ID)); ?></span>
            </a>
        <?php endforeach; ?>
    </div>

</div>
