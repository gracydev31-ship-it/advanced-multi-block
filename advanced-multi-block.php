<?php
/**
 * Plugin Name:       Advanced Multi Block
 * Description:       Example block scaffolded with Create Block tool.
 * Version:           0.1.0
 * Requires at least: 6.8
 * Requires PHP:      7.4
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       advanced-multi-block
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Registers the block(s) metadata from the `blocks-manifest.php` and registers the block type(s)
 * based on the registered block metadata. Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://make.wordpress.org/core/2025/03/13/more-efficient-block-type-registration-in-6-8/
 * @see https://make.wordpress.org/core/2024/10/17/new-block-type-registration-apis-to-improve-performance-in-wordpress-6-7/
 */
function register_blocks() {
   $build_dir = __DIR__ . '/build/blocks';
   $manifest  = __DIR__ . '/build/blocks-manifest.php';

   // Deprecated blocks — source files kept, but excluded from registration.
   // event-year kept — represents first edition year, used later.
   $excluded  = ['event-location', 'event-month', 'event-distances'];

   // Register all blocks from manifest (path string required by WP APIs).
   if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
       // WP 6.8+: one-call convenience.
       wp_register_block_types_from_metadata_collection( $build_dir, $manifest );
   } elseif ( function_exists( 'wp_register_block_metadata_collection' ) ) {
       // WP 6.7: index collection, then loop.
       wp_register_block_metadata_collection( $build_dir, $manifest );
       $manifest_data = require $manifest;
       foreach ( array_keys( $manifest_data ) as $block_type ) {
           register_block_type_from_metadata( $build_dir . '/' . $block_type );
       }
   } else {
       // WP 5.5-6.6: loop directly.
       $manifest_data = require $manifest;
       foreach ( array_keys( $manifest_data ) as $block_type ) {
           register_block_type_from_metadata( $build_dir . '/' . $block_type );
       }
   }

   // Unregister excluded blocks by their registered names.
   $manifest_data = require $manifest;
   foreach ( $excluded as $slug ) {
       if ( isset( $manifest_data[ $slug ] ) ) {
           unregister_block_type( $manifest_data[ $slug ]['name'] );
       }
   }
}
add_action( 'init', 'register_blocks' );
/**
* Enqueues the block assets for the editor
*/
function enqueue_block_assets() {
  $asset_file = include plugin_dir_path( __FILE__ ) . 'build/editor-script.asset.php';

  wp_enqueue_script(
      'editor-script-js',
      plugin_dir_url( __FILE__ ) . 'build/editor-script.js',
      $asset_file['dependencies'],
      $asset_file['version'],
      false
  );
}
add_action( 'enqueue_block_editor_assets', 'enqueue_block_assets' );

/**
* Enqueues the block assets for the frontend
*/
function enqueue_frontend_assets() {
  $asset_file = include plugin_dir_path( __FILE__ ) . 'build/frontend-script.asset.php';

  wp_enqueue_script(
      'frontend-script-js',
      plugin_dir_url( __FILE__ ) . 'build/frontend-script.js',
      $asset_file['dependencies'],
      $asset_file['version'],
      true
  );
}
add_action( 'wp_enqueue_scripts', 'enqueue_frontend_assets' );
