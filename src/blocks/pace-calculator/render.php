<?php
$wrapper_attributes = get_block_wrapper_attributes();

$distances = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21.0975, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42.195, 43, 44, 45, 46, 47, 48, 49, 50 ];

$race_distances = [ 5 => '5K', 10 => '10K', 21 => 'Half Marathon', 42 => 'Marathon' ];

$default_pace_seconds = 8 * 60 + 0;
$offsets = [ -10, -5, 0, 5, 10 ];

function format_cell_time( $total_minutes ) {
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
?>

<div <?php echo $wrapper_attributes; ?> data-wp-interactive="runpartner">
	<div class="rp-pace-inputs">
		<label>
			<span class="rp-pace-label"><?php esc_html_e( 'Pace', 'runpartner' ); ?></span>
			<input
				type="number"
				min="0"
				max="59"
				value="8"
				class="rp-pace-minutes"
				data-wp-bind--value="state.paceMinutes"
				data-wp-on--input="actions.setPaceMinutes"
			/>
			<span class="rp-pace-separator">:</span>
			<input
				type="number"
				min="0"
				max="59"
				value="0"
				class="rp-pace-seconds"
				data-wp-bind--value="state.paceSeconds"
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
			<tbody data-wp-watch="callbacks.renderRows">
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
					<?php foreach ( $offsets as $offset ) :
						$cell_seconds = $default_pace_seconds + $offset;
						if ( $cell_seconds <= 0 ) : ?>
						<td>--:--</td>
						<?php else :
							$total_minutes = ( $cell_seconds / 60 ) * $km;
						?>
						<td><?php echo format_cell_time( $total_minutes ); ?></td>
						<?php endif; ?>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
