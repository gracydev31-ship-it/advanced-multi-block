<?php
$post_id = get_the_ID();
if (!$post_id) {
	return '';
}

$section    = isset($_GET['section']) ? sanitize_key($_GET['section']) : 'details';
$valid      = ['details', 'records', 'history', 'course', 'reports', 'athletes'];
$section    = in_array($section, $valid, true) ? $section : 'details';

$subtitle     = get_post_meta($post_id, '_rp_event_subtitle', true);
$location     = get_post_meta($post_id, '_rp_event_location', true);
$country      = get_post_meta($post_id, '_rp_event_country', true);
$distances    = get_post_meta($post_id, '_rp_event_distances', true);
$event_date   = get_post_meta($post_id, '_rp_event_date', true);
$records    = get_post_meta($post_id, '_rp_event_records', true);
$records    = is_array($records) ? $records : [];

// Migration fallback: read old flat records if new structure is empty
if (empty($records)) {
    $old_time   = get_post_meta($post_id, '_rp_event_course_record', true);
    $old_holder = get_post_meta($post_id, '_rp_event_course_record_holder', true);
    if (!empty($old_time) || !empty($old_holder)) {
        $records = [
            [
                'category'    => 'men',
                'distance'    => '',
                'time'        => $old_time,
                'holder'      => $old_holder,
                'nationality' => '',
                'year'        => '',
            ],
        ];
    }
}
$categories = get_post_meta($post_id, '_rp_event_categories', true);
$categories = is_array($categories) && !empty($categories) ? $categories : ['men', 'women'];
$history        = get_post_meta($post_id, '_rp_event_history', true);
$course_overview = get_post_meta($post_id, '_rp_event_course_overview', true);
$editions       = get_post_meta($post_id, '_rp_event_editions', true);
$editions   = is_array($editions) ? $editions : [];
$show_reports = !empty($editions);

$tabs = [
	'details' => __('Overview', 'runpartner'),
	'records' => __('Records', 'runpartner'),
	'course'  => __('Course', 'runpartner'),
	'history' => __('History', 'runpartner'),
	'reports' => __('Reports', 'runpartner'),
	'athletes' => __('Famous Athletes', 'runpartner'),
];
$base_url = get_permalink();
?>
<div
	<?php echo get_block_wrapper_attributes(['class' => 'event-content-container']); ?>
	data-wp-interactive="runpartner/event-content"
	data-wp-router-region="event-content-region"
>
	<div class="event-content-layout">
		<aside class="event-content-sidebar">
			<nav class="event-content-tab-list" role="tablist">
				<?php foreach ($tabs as $tab_key => $tab_label) : ?>
					<?php if ('reports' === $tab_key && !$show_reports) continue; ?>
					<a
						href="<?php echo esc_url(add_query_arg('section', $tab_key, $base_url)); ?>"
						data-wp-on--click="actions.navigate"
						class="event-content-tab-item <?php echo $section === $tab_key ? 'active' : ''; ?>"
						role="tab"
						aria-selected="<?php echo $section === $tab_key ? 'true' : 'false'; ?>"
					>
						<?php echo esc_html($tab_label); ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</aside>
		<div class="event-content-main">
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
			<?php
			$active_cat = isset($_GET['record_cat']) ? sanitize_key($_GET['record_cat']) : $categories[0];
			if (!in_array($active_cat, $categories, true)) {
				$active_cat = $categories[0];
			}

			$grouped = [];
			foreach ($records as $r) {
				$cat = $r['category'] ?? 'other';
				if (!in_array($cat, $categories, true)) continue;
				$grouped[$cat][] = $r;
			}
			?>
			<div class="event-content-records">
				<?php if (!empty($records)) : ?>
					<nav class="event-content-sub-tabs" role="tablist">
						<?php foreach ($categories as $cat) : ?>
							<a
								href="<?php echo esc_url(add_query_arg(['section' => 'records', 'record_cat' => $cat], get_permalink())); ?>"
								data-wp-on--click="actions.navigate"
								class="event-content-sub-tab-button <?php echo $active_cat === $cat ? 'active' : ''; ?>"
								role="tab"
								aria-selected="<?php echo $active_cat === $cat ? 'true' : 'false'; ?>"
							>
								<?php echo esc_html(ucfirst($cat)); ?>
							</a>
						<?php endforeach; ?>
					</nav>

					<div class="event-content-records-panel" role="tabpanel">
						<?php $cat_records = $grouped[$active_cat] ?? []; ?>
						<?php if (!empty($cat_records)) : ?>
							<div class="event-content-records-table">
								<div class="event-content-records-table-header">
									<span class="event-content-records-th"><?php esc_html_e('Distance', 'runpartner'); ?></span>
									<span class="event-content-records-th"><?php esc_html_e('Time', 'runpartner'); ?></span>
									<span class="event-content-records-th"><?php esc_html_e('Holder', 'runpartner'); ?></span>
									<span class="event-content-records-th"><?php esc_html_e('Nationality', 'runpartner'); ?></span>
									<span class="event-content-records-th"><?php esc_html_e('Year', 'runpartner'); ?></span>
								</div>
								<?php foreach ($cat_records as $r) : ?>
								<div class="event-content-records-row">
									<span class="event-content-records-td event-content-records-distance"><?php echo esc_html($r['distance'] ?? ''); ?></span>
									<span class="event-content-records-td event-content-records-time"><?php echo esc_html($r['time'] ?? ''); ?></span>
									<span class="event-content-records-td event-content-records-holder"><?php echo esc_html($r['holder'] ?? ''); ?></span>
									<span class="event-content-records-td event-content-records-nationality"><?php echo esc_html($r['nationality'] ?? ''); ?></span>
									<span class="event-content-records-td event-content-records-year"><?php echo esc_html($r['year'] ?? ''); ?></span>
								</div>
								<?php endforeach; ?>
							</div>
						<?php else : ?>
							<p class="event-content-empty"><?php echo esc_html(sprintf(__('No %s course records recorded yet.', 'runpartner'), $active_cat)); ?></p>
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

		<?php elseif ('course' === $section) : ?>
			<div class="event-content-course">
				<?php if (!empty($course_overview)) : ?>
					<div class="event-content-course-text"><?php echo wp_kses_post($course_overview); ?></div>
				<?php else : ?>
					<p class="event-content-empty"><?php esc_html_e('No course overview available for this event yet.', 'runpartner'); ?></p>
				<?php endif; ?>
			</div>

		<?php elseif ('reports' === $section && $show_reports) : ?>
			<div class="event-content-reports">
				<div class="event-content-reports-accordion">
					<?php
					usort($editions, function ($a, $b) {
						return ($b['year'] ?? '') <=> ($a['year'] ?? '');
					});
					foreach ($editions as $entry) :
						$year = $entry['year'] ?? '';
						$report = $entry['report'] ?? '';
						if (empty($year)) continue;
					?>
					<div
						class="event-content-report-item"
						data-wp-interactive="runpartner/event-content"
						data-wp-context='{ "isOpen": false }'
					>
						<button
							class="event-content-report-toggle"
							data-wp-on--click="actions.toggleReport"
							data-wp-bind--aria-expanded="context.isOpen"
						>
							<span class="event-content-report-year"><?php echo esc_html($year); ?></span>
							<span class="event-content-report-icon" data-wp-bind--class="context.isOpen ? 'is-open' : ''">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
									<path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
						</button>
						<div
							class="event-content-report-content"
							data-wp-bind--hidden="!context.isOpen"
							hidden
						>
							<?php echo wp_kses_post($report); ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

		<?php elseif ('athletes' === $section) : ?>
			<?php
			$famous_athletes = get_post_meta($post_id, '_rp_event_famous_athletes', true);
			$famous_athletes = is_array($famous_athletes) ? $famous_athletes : [];
			?>
			<div class="event-content-athletes">
				<?php if (!empty($famous_athletes)) :
					$athlete_ids = array_filter(array_map('absint', array_column($famous_athletes, 'athlete_id')));
					if (!empty($athlete_ids)) :
						$athlete_posts = get_posts([
							'post_type'      => 'athlete',
							'post__in'       => $athlete_ids,
							'posts_per_page' => count($athlete_ids),
							'orderby'        => 'post__in',
						]);
						$athlete_map = [];
						foreach ($athlete_posts as $ap) {
							$athlete_map[$ap->ID] = $ap;
						}
					?>
					<div class="event-content-athletes-list">
						<?php foreach ($famous_athletes as $fa) :
							$aid = absint($fa['athlete_id'] ?? 0);
							if (!isset($athlete_map[$aid])) continue;
							$athlete = $athlete_map[$aid];
						?>
						<div class="event-content-athlete-card">
							<div class="event-content-athlete-avatar">
								<?php if (has_post_thumbnail($aid)) : ?>
									<?php echo get_the_post_thumbnail($aid, 'thumbnail', ['class' => 'event-content-athlete-image', 'loading' => 'lazy']); ?>
								<?php else : ?>
									<div class="event-content-athlete-initials"><?php echo esc_html(mb_substr(get_the_title($aid), 0, 1)); ?></div>
								<?php endif; ?>
							</div>
							<div class="event-content-athlete-info">
								<a href="<?php echo esc_url(get_permalink($aid)); ?>" class="event-content-athlete-link">
									<?php echo esc_html(get_the_title($aid)); ?>
								</a>
								<?php if (!empty($fa['performance'])) : ?>
									<p class="event-content-athlete-performance"><?php echo esc_html($fa['performance']); ?></p>
								<?php endif; ?>
								<?php if (!empty($fa['year'])) : ?>
									<span class="event-content-athlete-year"><?php echo esc_html($fa['year']); ?></span>
								<?php endif; ?>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
				<?php else : ?>
					<p class="event-content-empty"><?php esc_html_e('No famous athletes have been associated with this event yet.', 'runpartner'); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
		</div>
	</div>
</div>
