<?php
/**
 * Plugin Name:       osd-mattclegg
 * Plugin URI:        https://github.com/mattclegg/osd-mattclegg
 * Description:       Fetch and display all subpages' titles, truncated after 20 characters
 * Version:           1.0.0
 * Author:            Matt Clegg <cleggmatt@gmail.com>
 * Author URI:        http://mattclegg.com
 * Text Domain:       plugin-name-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

//Check requirements
if(version_compare(PHP_VERSION, '5.3.0') < 0) {
    throw new Exception("This plugin requires at least version 5.3 of PHP");
}

//Core framework
require_once( plugin_dir_path( __FILE__ ) . 'framework/core.php' );

//Add shortcode
add_shortcode( 'osd-mattclegg', array( 'OSD_MattClegg_SubPages', 'render' ) );

//Add widget
add_action( 'widgets_init', function(){
    require_once( plugin_dir_path( __FILE__ ) . 'framework/widget.php' );
    register_widget( 'OSD_MattClegg_SubPages_Widget' );
});