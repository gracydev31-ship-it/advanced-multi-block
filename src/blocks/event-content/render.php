<?php
$post_id = get_the_ID();
if (!$post_id) {
	return '';
}

$section    = isset($_GET['section']) ? sanitize_key($_GET['section']) : 'details';
$valid      = ['details', 'records', 'history', 'reports'];
$section    = in_array($section, $valid, true) ? $section : 'details';

$subtitle     = get_post_meta($post_id, '_rp_event_subtitle', true);
$location     = get_post_meta($post_id, '_rp_event_location', true);
$country      = get_post_meta($post_id, '_rp_event_country', true);
$distances    = get_post_meta($post_id, '_rp_event_distances', true);
$event_date   = get_post_meta($post_id, '_rp_event_date', true);
$course_record      = get_post_meta($post_id, '_rp_event_course_record', true);
$course_record_holder = get_post_meta($post_id, '_rp_event_course_record_holder', true);
$history      = get_post_meta($post_id, '_rp_event_history', true);
$past_edition_id = (int) get_post_meta($post_id, '_rp_event_past_edition', true);

$show_reports = $past_edition_id > 0 && get_post_status($past_edition_id) === 'publish';

$tabs = [
	'details' => __('Details', 'runpartner'),
	'records' => __('Records', 'runpartner'),
	'history' => __('History', 'runpartner'),
	'reports' => __('Reports', 'runpartner'),
];
$base_url = get_permalink();
?>
<div
	<?php echo get_block_wrapper_attributes(['class' => 'event-content-container']); ?>
	data-wp-interactive="runpartner/event-content"
	data-wp-router-region="event-content-region"
>
	<nav class="event-content-tab-bar" role="tablist">
		<?php foreach ($tabs as $tab_key => $tab_label) : ?>
			<?php if ('reports' === $tab_key && !$show_reports) continue; ?>
			<a
				href="<?php echo esc_url(add_query_arg('section', $tab_key, $base_url)); ?>"
				data-wp-on--click="actions.navigate"
				class="event-content-tab-button <?php echo $section === $tab_key ? 'active' : ''; ?>"
				role="tab"
				aria-selected="<?php echo $section === $tab_key ? 'true' : 'false'; ?>"
			>
				<?php echo esc_html($tab_label); ?>
			</a>
		<?php endforeach; ?>
	</nav>

	<div class="event-content-tab-panel" role="tabpanel">
		<?php if ('details' === $section) : ?>
			<div class="event-content-details">
				<?php if (!empty($subtitle) || !empty($location) || !empty($country) || !empty($distances) || !empty($event_date)) : ?>
				<div class="event-content-intro-card">
					<?php if (!empty($subtitle)) : ?>
						<p class="event-content-subtitle"><?php echo esc_html($subtitle); ?></p>
					<?php endif; ?>

					<?php if (!empty($location) || !empty($country)) : ?>
					<div class="event-content-meta-row">
						<span class="event-content-meta-label"><?php esc_html_e('Location', 'runpartner'); ?></span>
						<span class="event-content-meta-value">
							<?php echo esc_html(trim($location . (!empty($location) && !empty($country) ? ', ' : '') . $country)); ?>
						</span>
					</div>
					<?php endif; ?>

					<?php if (!empty($distances) && is_array($distances)) : ?>
					<div class="event-content-meta-row">
						<span class="event-content-meta-label"><?php esc_html_e('Distances', 'runpartner'); ?></span>
						<div class="event-content-distance-list">
							<?php foreach ($distances as $d) : ?>
								<span class="event-content-distance-pill"><?php echo esc_html($d); ?></span>
							<?php endforeach; ?>
						</div>
					</div>
					<?php endif; ?>

					<?php if (!empty($event_date)) : ?>
					<div class="event-content-countdown-badge">
						<?php
						try {
							$date_obj = new DateTime($event_date);
							$now      = new DateTime('today');
							$diff     = $now->diff($date_obj);
							$days     = (int) $diff->format('%r%a');

							if ($days < 0) {
								echo '<span class="event-content-countdown-past">' . esc_html__('Event passed', 'runpartner') . '</span>';
							} elseif (0 === $days) {
								echo '<span class="event-content-countdown-today">' . esc_html__('Today!', 'runpartner') . '</span>';
							} elseif (1 === $days) {
								echo '<span class="event-content-countdown-future">' . esc_html__('Tomorrow!', 'runpartner') . '</span>';
							} else {
								echo '<span class="event-content-countdown-future">' . sprintf(esc_html__('%d days away', 'runpartner'), $days) . '</span>';
							}
						} catch (Exception $e) {
							// Invalid date — silently ignore countdown
						}
						?>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="event-content-body">
					<?php the_content(); ?>
				</div>
			</div>

		<?php elseif ('records' === $section) : ?>
			<div class="event-content-records">
				<?php if (!empty($course_record) || !empty($course_record_holder)) : ?>
					<div class="event-content-records-card">
						<?php if (!empty($course_record)) : ?>
						<div class="event-content-records-time">
							<span class="event-content-meta-label"><?php esc_html_e('Course Record', 'runpartner'); ?></span>
							<span class="event-content-record-value"><?php echo esc_html($course_record); ?></span>
						</div>
						<?php endif; ?>
						<?php if (!empty($course_record_holder)) : ?>
						<div class="event-content-records-holder">
							<span class="event-content-meta-label"><?php esc_html_e('Record Holder', 'runpartner'); ?></span>
							<span class="event-content-record-value"><?php echo esc_html($course_record_holder); ?></span>
						</div>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<p class="event-content-empty"><?php esc_html_e('No course records have been recorded for this event yet.', 'runpartner'); ?></p>
				<?php endif; ?>
			</div>

		<?php elseif ('history' === $section) : ?>
			<div class="event-content-history">
				<?php if (!empty($history)) : ?>
					<div class="event-content-history-text"><?php echo wp_kses_post($history); ?></div>
				<?php else : ?>
					<p class="event-content-empty"><?php esc_html_e('No history has been documented for this event yet.', 'runpartner'); ?></p>
				<?php endif; ?>
			</div>

		<?php elseif ('reports' === $section && $show_reports) : ?>
			<div class="event-content-reports">
				<?php
				$past_post = get_post($past_edition_id);
				if ($past_post) {
					echo apply_filters('the_content', $past_post->post_content);
				} else {
					echo '<p class="event-content-empty">' . esc_html__('Report not found.', 'runpartner') . '</p>';
				}
				?>
			</div>
		<?php endif; ?>
	</div>
</div>
