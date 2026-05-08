<?php
$wrapper_attributes = get_block_wrapper_attributes();
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

	<table class="rp-pace-table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Distance', 'runpartner' ); ?></th>
				<th><?php esc_html_e( 'Finish Time', 'runpartner' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>5K</td>
				<td><span data-wp-text="state.time5K"></span></td>
			</tr>
			<tr>
				<td>10K</td>
				<td><span data-wp-text="state.time10K"></span></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Half Marathon', 'runpartner' ); ?></td>
				<td><span data-wp-text="state.timeHalf"></span></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'Marathon', 'runpartner' ); ?></td>
				<td><span data-wp-text="state.timeMarathon"></span></td>
			</tr>
		</tbody>
	</table>
</div>
