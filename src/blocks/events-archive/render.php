<?php
if (!defined('ABSPATH')) {
	exit;
}

$post_type = 'events';
$today     = date('Y-m-d');

$upcoming_page = isset($_GET['upcoming_page']) ? max(1, (int) $_GET['upcoming_page']) : 1;
$recaps_page   = isset($_GET['recaps_page']) ? max(1, (int) $_GET['recaps_page']) : 1;

$featured = get_posts([
	'post_type'      => $post_type,
	'posts_per_page' => 1,
	'meta_query'     => [
		['key' => '_rp_event_featured', 'value' => '1', 'compare' => '='],
	],
]);

if (empty($featured)) {
	$featured = get_posts([
		'post_type'      => $post_type,
		'posts_per_page' => 1,
		'meta_key'       => '_rp_event_date',
		'meta_value'     => $today,
		'meta_compare'   => '>=',
		'meta_type'      => 'DATE',
		'orderby'        => 'meta_value',
		'order'          => 'ASC',
	]);
}

$featured_id = !empty($featured) ? $featured[0]->ID : null;

$upcoming = new WP_Query([
	'post_type'      => $post_type,
	'posts_per_page' => 6,
	'paged'          => $upcoming_page,
	'post__not_in'   => $featured_id ? [$featured_id] : [],
	'meta_key'       => '_rp_event_date',
	'meta_value'     => $today,
	'meta_compare'   => '>=',
	'meta_type'      => 'DATE',
	'orderby'        => 'meta_value',
	'order'          => 'ASC',
]);

$recaps = new WP_Query([
	'post_type'      => $post_type,
	'posts_per_page' => 6,
	'paged'          => $recaps_page,
	'meta_key'       => '_rp_event_date',
	'meta_value'     => $today,
	'meta_compare'   => '<',
	'meta_type'      => 'DATE',
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
]);

$base_url = get_post_type_archive_link($post_type);

function rp_render_event_card(int $post_id): void {
	$title         = get_the_title($post_id);
	$permalink     = get_permalink($post_id);
	$date          = get_post_meta($post_id, '_rp_event_date', true);
	$location      = get_post_meta($post_id, '_rp_event_location', true);
	$country       = get_post_meta($post_id, '_rp_event_country', true);
	$distances     = get_post_meta($post_id, '_rp_event_distances', true);
	$loc_str       = trim($location . (!empty($location) && !empty($country) ? ', ' : '') . $country);
	$formatted_date = '';
	$thumb_url     = '';

	if (!empty($date)) {
		try {
			$dt = new DateTime($date);
			$formatted_date = $dt->format('M j, Y');
		} catch (Exception $e) {
		}
	}

	$thumb_id = get_post_thumbnail_id($post_id);
	if ($thumb_id) {
		$thumb_url = wp_get_attachment_image_url($thumb_id, 'medium_large');
	}
	?>
	<a href="<?php echo esc_url($permalink); ?>" class="event-archive-card">
		<?php if (!empty($thumb_url)) : ?>
		<div class="event-archive-card-image">
			<img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy" />
		</div>
		<?php endif; ?>
		<div class="event-archive-card-body">
			<?php if (!empty($formatted_date)) : ?>
				<span class="event-archive-card-date"><?php echo esc_html($formatted_date); ?></span>
			<?php endif; ?>
			<h3 class="event-archive-card-title"><?php echo esc_html($title); ?></h3>
			<?php if (!empty($loc_str)) : ?>
				<span class="event-archive-card-location"><?php echo esc_html($loc_str); ?></span>
			<?php endif; ?>
			<?php if (!empty($distances) && is_array($distances)) : ?>
				<div class="event-archive-card-distances">
				<?php foreach ($distances as $d) : ?>
					<span class="event-archive-card-pill"><?php echo esc_html($d); ?></span>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</a>
	<?php
}

function rp_render_pagination(WP_Query $query, string $base_url, string $param): void {
	if ($query->max_num_pages <= 1) {
		return;
	}
	$current = max(1, $query->get('paged') ?: 1);
	$total   = $query->max_num_pages;
	$range   = 2;
	$pages   = [];

	for ($i = 1; $i <= $total; $i++) {
		if ($i === 1 || $i === $total || abs($i - $current) <= $range) {
			if (!empty($pages) && end($pages) !== $i - 1) {
				$pages[] = '…';
			}
			$pages[] = $i;
		}
	}
	?>
	<nav class="event-archive-pagination">
		<?php if ($current > 1) : ?>
			<a href="<?php echo esc_url(add_query_arg($param, $current - 1, $base_url)); ?>"
			   data-wp-on--click="actions.navigate"
			   class="event-archive-pagination-link prev">←</a>
		<?php endif; ?>

		<?php foreach ($pages as $p) : ?>
			<?php if ($p === '…') : ?>
				<span class="event-archive-pagination-dots">…</span>
			<?php else : ?>
			<a href="<?php echo esc_url(add_query_arg($param, $p, $base_url)); ?>"
			   data-wp-on--click="actions.navigate"
			   class="event-archive-pagination-link <?php echo $p === $current ? 'active' : ''; ?>"><?php echo $p; ?></a>
			<?php endif; ?>
		<?php endforeach; ?>

		<?php if ($current < $total) : ?>
			<a href="<?php echo esc_url(add_query_arg($param, $current + 1, $base_url)); ?>"
			   data-wp-on--click="actions.navigate"
			   class="event-archive-pagination-link next">→</a>
		<?php endif; ?>
	</nav>
	<?php
}
?>
	<?php if ($featured_id) :
		$thumb_id    = get_post_thumbnail_id($featured_id);
		$thumb_url   = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'full') : '';
		$ftitle      = get_the_title($featured_id);
		$flink       = get_permalink($featured_id);
		$fdate       = get_post_meta($featured_id, '_rp_event_date', true);
		$floc        = get_post_meta($featured_id, '_rp_event_location', true);
		$fcountry    = get_post_meta($featured_id, '_rp_event_country', true);
		$floc_str    = trim($floc . (!empty($floc) && !empty($fcountry) ? ', ' : '') . $fcountry);
		$fdate_fmt   = '';
		if (!empty($fdate)) {
			try {
				$dt = new DateTime($fdate);
				$fdate_fmt = $dt->format('M j, Y');
			} catch (Exception $e) {}
		}
		$hero_style = $thumb_url ? 'background-image:url(' . esc_url($thumb_url) . ');background-size:cover;background-repeat:no-repeat;background-position:center;' : '';
	?>
	<div class="wp-block-cover alignfull post-hero is-light event-archive-hero" style="<?php echo $hero_style; ?>min-height:50vh;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--style--root--padding-left);padding-right:var(--wp--style--root--padding-right);">
		<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
		<div class="wp-block-cover__inner-container">
			<?php echo do_blocks('<!-- wp:pattern {"slug":"runpartner-theme/rounded"} /-->'); ?>
			<div style="height:30vh" aria-hidden="true" class="wp-block-spacer"></div>
			<div style="max-width:var(--wp--style--global--wide-size);margin-inline:auto;width:100%;padding-bottom:var(--wp--preset--spacing--50)">
				<div style="display:inline-block;background:var(--wp--preset--color--accent-7);padding:var(--wp--preset--spacing--40);border-radius:8px;">
				<span class="event-archive-hero-tag">Featured Race</span>
				<h1 class="event-archive-hero-title text-gradient"><?php echo esc_html($ftitle); ?></h1>
				<?php if (!empty($fdate_fmt) || !empty($floc_str)) : ?>
				<p class="event-archive-hero-meta">
					<?php echo esc_html($fdate_fmt); if (!empty($fdate_fmt) && !empty($floc_str)) echo ' · '; ?>
					<?php echo esc_html($floc_str); ?>
				</p>
				<?php endif; ?>
				<?php
				echo do_blocks('<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . esc_url($flink) . '">View Event →</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php else : ?>
	<div class="wp-block-cover alignfull post-hero is-light event-archive-hero event-archive-hero-fallback" style="min-height:50vh;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--style--root--padding-left);padding-right:var(--wp--style--root--padding-right);background:linear-gradient(135deg,var(--wp--preset--color--base),var(--wp--preset--color--accent-3));">
		<span aria-hidden="true" class="wp-block-cover__background has-background-dim-0 has-background-dim"></span>
		<div class="wp-block-cover__inner-container">
			<?php echo do_blocks('<!-- wp:pattern {"slug":"runpartner-theme/rounded"} /-->'); ?>
			<div style="height:30vh" aria-hidden="true" class="wp-block-spacer"></div>
			<div style="max-width:var(--wp--style--global--wide-size);margin-inline:auto;width:100%;padding-bottom:var(--wp--preset--spacing--50)">
				<h1 class="event-archive-hero-title text-gradient">Events</h1>
			</div>
		</div>
	</div>
	<?php endif; ?>

<div <?php echo get_block_wrapper_attributes(['class' => 'events-archive alignwide']); ?>>

	<?php $carousel_sections = [
		[
			'id'    => 'upcoming',
			'title' => 'Upcoming Events',
			'query' => $upcoming,
			'param' => 'upcoming_page',
		],
		[
			'id'    => 'recaps',
			'title' => 'Race Recaps',
			'query' => $recaps,
			'param' => 'recaps_page',
		],
	];

	foreach ($carousel_sections as $cs) :
		if (!$cs['query']->have_posts()) {
			continue;
		}
		$region_id = $cs['id'] . '-region';
	?>
	<div class="event-archive-section"
		data-wp-interactive="runpartner/events-archive"
		data-wp-router-region="<?php echo $region_id; ?>"
		data-wp-class--loading="state.core.router.isNavigation">
		<h2 class="event-archive-section-title"><?php echo $cs['title']; ?></h2>
		<div class="event-archive-carousel-wrapper"
			data-carousel-id="<?php echo $cs['id']; ?>"
			data-wp-init="callbacks.initCarousel"
			data-wp-on--mouseenter="actions.pauseCarousel"
			data-wp-on--mouseleave="actions.resumeCarousel">
			<button class="event-archive-carousel-arrow prev"
				data-wp-on--click="actions.carouselPrev"
				aria-label="Previous slide">‹</button>
			<div class="event-archive-carousel">
				<div class="event-archive-grid">
					<?php while ($cs['query']->have_posts()) : $cs['query']->the_post();
						rp_render_event_card(get_the_ID());
					endwhile;
					wp_reset_postdata(); ?>
				</div>
			</div>
			<button class="event-archive-carousel-arrow next"
				data-wp-on--click="actions.carouselNext"
				aria-label="Next slide">›</button>
		</div>
		<?php rp_render_pagination($cs['query'], $base_url, $cs['param']); ?>
	</div>
	<?php endforeach; ?>

</div>
