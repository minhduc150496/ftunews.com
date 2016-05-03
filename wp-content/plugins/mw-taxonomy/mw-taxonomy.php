<?php
/*
Plugin Name: MW Taxonomy
Plugin URI: http://matswestholm.se/en/wordpress-plugin/mw-taxonomy/
Description: Makes it possible to add any custom taxonomy to your wp website
Version: 1.0
Auther Mats Westholm
Auther URI: http://matswestholm.se/
Licens: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: mw-taxonomy
Domain Path: /languages
*/

add_action( 'plugins_loaded', 'mw_taxonomy_load_textdomain' );
function mw_taxonomy_load_textdomain(){
	load_plugin_textdomain( 'mw-taxonomy', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
}

require 'includes/class-mw-taxonomy.php';
$obj = new MW_taxonomy();
$obj->run();

?>
