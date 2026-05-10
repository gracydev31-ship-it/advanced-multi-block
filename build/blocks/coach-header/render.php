<?php
$post_id = get_the_ID();
if (! $post_id) {
    return '';
}

$title = get_the_title($post_id);
$subtitle = get_post_meta($post_id, '_rp_coach_subtitle', true);
$birth_year = (int) get_post_meta($post_id, '_rp_coach_birth_year', true);
$death_year = (int) get_post_meta($post_id, '_rp_coach_death_year', true);
$nationality = get_post_meta($post_id, '_rp_coach_nationality', true);
$era_start = get_post_meta($post_id, '_rp_coach_era_start', true);
$era_end = get_post_meta($post_id, '_rp_coach_era_end', true);
$approach = get_post_meta($post_id, '_rp_coach_approach', true);
$notable_athletes = get_post_meta($post_id, '_rp_coach_notable_athletes', true);
$contributions = get_post_meta($post_id, '_rp_coach_contributions', true);

$years = '';
if ($birth_year > 0) {
    $years = '(' . $birth_year;
    $years .= ($death_year > 0) ? '–' . $death_year : '–Present';
    $years .= '),';
}

$era = '';
if (! empty($era_start) && (int) $era_start > 0) {
    $era = $era_start;
    $era .= (! empty($era_end) && (int) $era_end > 0) ? '–' . $era_end : '–Present';
}

$approach_lines = ! empty($approach) ? explode("\n", $approach) : [];
$athlete_lines = ! empty($notable_athletes) ? explode("\n", $notable_athletes) : [];
$contribution_lines = ! empty($contributions) ? explode("\n", $contributions) : [];
?>
<div <?php echo get_block_wrapper_attributes(array('class' => 'runpartner-coach-header')); ?> data-wp-interactive="runpartner">

    <?php if (! empty($subtitle)) : ?>
        <div class="coach-header-subtitle"><?php echo esc_html($subtitle); ?></div>
    <?php endif; ?>

    <div class="coach-header-top">
        <span class="coach-header-title"><?php echo esc_html($title); ?></span>
        <?php if (! empty($years)) : ?>
            <span class="coach-header-years"><?php echo esc_html($years); ?></span>
        <?php endif; ?>
        <?php if (! empty($nationality)) : ?>
            <span class="coach-header-nationality"><?php echo esc_html($nationality); ?></span>
        <?php endif; ?>
    </div>

    <?php if (! empty($approach_lines)) : ?>
    <div class="coach-header-section coach-approach"
        <?php echo wp_interactivity_data_wp_context(array('isOpen' => false)); ?>
        data-wp-class--is-open="context.isOpen">
        <button
            class="coach-header-section-title"
            data-wp-on--click="actions.toggleCoachSection"
            data-wp-bind--aria-expanded="context.isOpen"
        >
            <?php echo esc_html__('Training Philosophy', 'runpartner'); ?>
            <span class="coach-header-accordion-icon" aria-hidden="true"></span>
        </button>
        <div class="coach-header-section-content">
            <div class="coach-header-section-inner">
                <?php foreach ($approach_lines as $line) : ?>
                    <?php $line = trim($line); if (! empty($line)) : ?>
                        <p><?php echo esc_html($line); ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (! empty($athlete_lines)) : ?>
    <div class="coach-header-section coach-athletes"
        <?php echo wp_interactivity_data_wp_context(array('isOpen' => false)); ?>
        data-wp-class--is-open="context.isOpen">
        <button
            class="coach-header-section-title"
            data-wp-on--click="actions.toggleCoachSection"
            data-wp-bind--aria-expanded="context.isOpen"
        >
            <?php echo esc_html__('Notable Athletes', 'runpartner'); ?>
            <span class="coach-header-accordion-icon" aria-hidden="true"></span>
        </button>
        <div class="coach-header-section-content">
            <div class="coach-header-section-inner">
                <ul>
                    <?php foreach ($athlete_lines as $line) : ?>
                        <?php $line = trim($line); if (! empty($line)) : ?>
                            <li><?php echo esc_html($line); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (! empty($contribution_lines)) : ?>
    <div class="coach-header-section coach-contributions"
        <?php echo wp_interactivity_data_wp_context(array('isOpen' => true)); ?>
        data-wp-class--is-open="context.isOpen">
        <button
            class="coach-header-section-title"
            data-wp-on--click="actions.toggleCoachSection"
            data-wp-bind--aria-expanded="context.isOpen"
        >
            <?php echo esc_html__('Key Contributions', 'runpartner'); ?>
            <span class="coach-header-accordion-icon" aria-hidden="true"></span>
        </button>
        <div class="coach-header-section-content">
            <div class="coach-header-section-inner">
                <?php if (! empty($era)) : ?>
                    <div class="coach-header-era-label"><?php echo esc_html__('Era:', 'runpartner'); ?> <?php echo esc_html($era); ?></div>
                <?php endif; ?>
                <ul>
                    <?php foreach ($contribution_lines as $line) : ?>
                        <?php $line = trim($line); if (! empty($line)) : ?>
                            <li><?php echo esc_html($line); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>
