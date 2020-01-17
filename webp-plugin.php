<?php
/**
 * Plugin name: Webp image plugin
 * Plugin URI:        https://skener.github.io/cv
 * Description:       Change all raster media to webp format.
 * Version:           1.0.0
 * Author:            Andriy Tserkovnyk <skenerster@gmail.com>
 * Author URI:        https://skener.github.io/cv
 * Text Domain:       skener
 *
 * @package WordPress.
 */

// check WP env is loaded
if (!defined('ABSPATH')) {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use Webp\WebP;

if (!class_exists('Webp\Webp')) {

    echo 'Some Error';
    return;

} else {

    $webp = new Webp();
}


