<?php
/**
 * Plugin Name:       Woo Demo
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Description:       Plugin that demoes the usage of the Interactivity API in Woo.
 * Author:            Automattic
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       woo-demo
 * Requires Plugins:  woocommerce
 */

require_once __DIR__ . '/lib/add-catalog-sorting.php';
require_once __DIR__ . '/lib/add-categories-filter.php';
require_once __DIR__ . '/lib/add-csn-to-reviews.php';
require_once __DIR__ . '/lib/add-search-filter.php';
require_once __DIR__ . '/lib/enable-full-csn.php';
require_once __DIR__ . '/lib/enqueue-scripts.php';
