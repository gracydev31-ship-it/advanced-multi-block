<?php
$post_id = get_the_ID();
if (! $post_id) {
    return '';
}

$title = get_the_title($post_id);
$subtitle = get_post_meta($post_id, '_rp_athlete_subtitle', true);
$birth_year = (int) get_post_meta($post_id, '_rp_athlete_birth_year', true);
$death_year = (int) get_post_meta($post_id, '_rp_athlete_death_year', true);
$nationality = get_post_meta($post_id, '_rp_athlete_nationality', true);
$achievements = get_post_meta($post_id, '_rp_athlete_achievements', true);

$years = '';
if ($birth_year > 0) {
    $years = '(' . $birth_year;
    $years .= ($death_year > 0) ? '–' . $death_year : '–Present';
    $years .= ')';
}

$discipline_terms = get_the_terms($post_id, 'discipline');
$disciplines_list = ! empty($discipline_terms) && ! is_wp_error($discipline_terms) ? $discipline_terms : [];
$achievement_lines = ! empty($achievements) ? explode("\n", $achievements) : [];
?>
<div <?php echo get_block_wrapper_attributes(array('class' => 'runpartner-athlete-header')); ?>>

    <?php if (! empty($subtitle)) : ?>
        <div class="athlete-header-subtitle"><?php echo esc_html($subtitle); ?></div>
    <?php endif; ?>

    <div class="athlete-header-top">
        <span class="athlete-header-title"><?php echo esc_html($title); ?></span>
        <?php if (! empty($years)) : ?>
            <span class="athlete-header-years"><?php echo esc_html($years); ?></span>
        <?php endif; ?>
        <?php if (! empty($nationality)) : ?>
            <span class="athlete-header-nationality"><?php echo esc_html($nationality); ?></span>
        <?php endif; ?>
    </div>

    <?php if (! empty($disciplines_list)) : ?>
    <div class="athlete-header-section athlete-disciplines">
        <div class="athlete-header-section-label"><?php echo esc_html__('Disciplines', 'runpartner'); ?></div>
        <ul class="athlete-header-achievements-list">
            <?php foreach ($disciplines_list as $term) : ?>
                <li><?php echo esc_html($term->name); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php if (! empty($achievement_lines)) : ?>
    <div class="athlete-header-section athlete-achievements">
        <div class="athlete-header-section-label"><?php echo esc_html__('Key Achievements', 'runpartner'); ?></div>
        <ul class="athlete-header-achievements-list">
            <?php foreach ($achievement_lines as $line) : ?>
                <?php $line = trim($line); if (! empty($line)) : ?>
                    <li><?php echo esc_html($line); ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</div>
