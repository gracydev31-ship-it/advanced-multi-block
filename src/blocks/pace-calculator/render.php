<?php
$wrapper_attributes = get_block_wrapper_attributes();

$distances = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21.0975, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42.195, 43, 44, 45, 46, 47, 48, 49, 50 ];

$key_distances = [
	[ 'km' => 5, 'label' => '5K', 'race' => true ],
	[ 'km' => 10, 'label' => '10K', 'race' => true ],
	[ 'km' => 21.0975, 'label' => 'Half Marathon', 'race' => true ],
	[ 'km' => 42.195, 'label' => 'Marathon', 'race' => true ],
];

$race_distances = [ 5 => '5K', 10 => '10K', 21 => 'Half Marathon', 42 => 'Marathon' ];

$default_pace_seconds = 8 * 60 + 0;
$offsets = [ -10, -5, 0, 5, 10 ];

wp_interactivity_state(
	'runpartner',
	array(
		'paceOffsets'  => $offsets,
		'defaultPace'  => 8 * 60,
	)
);

$initial_context = array(
	'paceMinutes'   => 8,
	'paceSeconds'   => 0,
	'unit'          => 'km',
	'showFullTable' => false,
);

if ( ! function_exists( 'runpartner_format_cell_time' ) ) {
	function runpartner_format_cell_time( $total_minutes ) {
		if ( $total_minutes <= 0 ) {
			return '--:--';
		}
		$total_seconds = round( $total_minutes * 60 );
		$hours = floor( $total_seconds / 3600 );
		$minutes = floor( ( $total_seconds % 3600 ) / 60 );
		$seconds = $total_seconds % 60;
		if ( $hours > 0 ) {
			return sprintf( '%d:%02d:%02d', $hours, $minutes, $seconds );
		}
		return sprintf( '%d:%02d', $minutes, $seconds );
	}
}
?>

<div <?php echo $wrapper_attributes; ?> data-wp-interactive="runpartner" <?php echo wp_interactivity_data_wp_context( $initial_context ); ?>>
	<div class="rp-pace-inputs">
		<label>
			<span class="rp-pace-label"><?php esc_html_e( 'Pace', 'runpartner' ); ?></span>
			<input
				type="number"
				min="0"
				max="59"
				value="8"
				class="rp-pace-minutes"
				data-wp-bind--value="context.paceMinutes"
				data-wp-on--input="actions.setPaceMinutes"
			/>
			<span class="rp-pace-separator">:</span>
			<input
				type="number"
				min="0"
				max="59"
				value="0"
				class="rp-pace-seconds"
				data-wp-bind--value="context.paceSeconds"
				data-wp-on--input="actions.setPaceSeconds"
			/>
		</label>
		<span class="rp-pace-unit">
			<?php esc_html_e( 'min/', 'runpartner' ); ?><span data-wp-text="state.unitLabel">km</span>
		</span>
		<button
			class="rp-unit-toggle"
			data-wp-on--click="actions.toggleUnit"
			data-wp-text="state.unitToggleLabel"
		><?php esc_html_e( 'Switch to mi', 'runpartner' ); ?></button>
	</div>

	<div class="rp-tables-wrapper" data-wp-watch="callbacks.renderRows">
		<div class="rp-section rp-section--key">
			<h3 class="rp-section-label"><?php esc_html_e( 'Key Distances', 'runpartner' ); ?></h3>
			<div class="rp-table-wrap">
				<table class="rp-pace-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Distance', 'runpartner' ); ?></th>
							<th><span data-wp-text="state.col0">7:50</span></th>
							<th><span data-wp-text="state.col1">7:55</span></th>
							<th class="rp-col-active"><span data-wp-text="state.col2">8:00</span></th>
							<th><span data-wp-text="state.col3">8:05</span></th>
							<th><span data-wp-text="state.col4">8:10</span></th>
						</tr>
					</thead>
					<tbody class="rp-key-tbody">
						<?php foreach ( $key_distances as $d ) : ?>
						<tr class="rp-row-race">
							<td><?php echo esc_html( $d['label'] ); ?></td>
							<?php
							foreach ( $offsets as $offset ) :
								$cell_seconds = $default_pace_seconds + $offset;
								if ( $cell_seconds <= 0 ) :
									?>
									<td>--:--</td>
									<?php
								else :
									$total_minutes = ( $cell_seconds / 60 ) * $d['km'];
									?>
									<td><?php echo runpartner_format_cell_time( $total_minutes ); ?></td>
									<?php
								endif;
							endforeach;
							?>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>

		<button
			class="rp-toggle-btn"
			data-wp-on--click="actions.toggleFullTable"
			data-wp-class--is-open="context.showFullTable"
			aria-expanded="false"
		>
			<span class="rp-toggle-arrow"></span>
			<span class="rp-toggle-label" data-wp-text="state.toggleLabel"><?php esc_html_e( 'Show full 1K–50K breakdown', 'runpartner' ); ?></span>
		</button>

		<div
			class="rp-section rp-section--full"
			data-wp-bind--hidden="!context.showFullTable"
			hidden
		>
			<h3 class="rp-section-label"><?php esc_html_e( 'Full 1K–50K Breakdown', 'runpartner' ); ?></h3>
			<div class="rp-table-wrap">
				<table class="rp-pace-table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Distance', 'runpartner' ); ?></th>
							<th><span data-wp-text="state.col0">7:50</span></th>
							<th><span data-wp-text="state.col1">7:55</span></th>
							<th class="rp-col-active"><span data-wp-text="state.col2">8:00</span></th>
							<th><span data-wp-text="state.col3">8:05</span></th>
							<th><span data-wp-text="state.col4">8:10</span></th>
						</tr>
					</thead>
					<tbody class="rp-full-tbody">
						<?php foreach ( $distances as $km ) :
							$label       = round( $km ) . 'K';
							$is_race     = false;
							if ( abs( $km - 21.0975 ) < 0.01 ) {
								$label   = 'Half Marathon';
								$is_race = true;
							} elseif ( abs( $km - 42.195 ) < 0.01 ) {
								$label   = 'Marathon';
								$is_race = true;
							} elseif ( isset( $race_distances[ (int) round( $km ) ] ) ) {
								$label   = $race_distances[ (int) round( $km ) ];
								$is_race = true;
							}
						?>
						<tr class="<?php echo $is_race ? 'rp-row-race' : ''; ?>">
							<td><?php echo esc_html( $label ); ?></td>
							<?php
							foreach ( $offsets as $offset ) :
								$cell_seconds = $default_pace_seconds + $offset;
								if ( $cell_seconds <= 0 ) :
									?>
									<td>--:--</td>
									<?php
								else :
									$total_minutes = ( $cell_seconds / 60 ) * $km;
									?>
									<td><?php echo runpartner_format_cell_time( $total_minutes ); ?></td>
									<?php
								endif;
							endforeach;
							?>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
