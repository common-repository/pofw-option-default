<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Setup_Install {
	

	public static function install(){
	
		if ( !class_exists( 'WooCommerce' ) ) { 
		  deactivate_plugins('pofw-option-default');
		  wp_die( __('The POFW Option Default plugin requires WooCommerce to run. Please install WooCommerce and activate.', 'pofw-option-default'));
	  }

    if ( version_compare( WC()->version, '3.0', "<" ) ) {
      wp_die(sprintf(__('WooCommerce %s or higher is required (You are running %s)', 'pofw-option-default'), '3.0', WC()->version));
    }	
    	
		self::create_tables();
				
	}


	private static function create_tables(){
		global $wpdb;

		$wpdb->hide_errors();
		 
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta(self::get_schema());
	}


	private static function get_schema(){
		global $wpdb;

		$collate = '';

		if ($wpdb->has_cap('collation')){
			if (!empty( $wpdb->charset)){
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if (!empty( $wpdb->collate)){
				$collate .= " COLLATE $wpdb->collate";
			}
		}
		
		return "
CREATE TABLE {$wpdb->prefix}pofw_optiondefault_option (
  `odf_option_id` int(11) unsigned NOT NULL auto_increment,
  `product_id` int(11) unsigned NOT NULL,
  `option_id` int(11) unsigned NOT NULL,  
  `placeholder_text` text,           
  PRIMARY KEY (odf_option_id)    
) $collate;		
CREATE TABLE {$wpdb->base_prefix}pofw_optiondefault_option_value (
  `odf_value_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) unsigned NOT NULL,
  `option_id` int(11) unsigned NOT NULL,  
  `value_id` int(11) unsigned NOT NULL,    
  `is_selected` tinyint(1) unsigned NOT NULL default 0,           
  PRIMARY KEY (odf_value_id)    
) $collate;		
		";
		
	}

}
