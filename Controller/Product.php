<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Controller_Product {


	public function __construct() {
    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts')); 	
    add_action('woocommerce_before_add_to_cart_button', array($this, 'display_options_on_product_page'), 20);	//after all product option plugins							  				
	}


  public function enqueue_frontend_scripts(){
    wp_enqueue_script('pofw_odf_product_view', Pektsekye_ODF()->getPluginUrl() . 'view/frontend/web/main.js', array('jquery', 'jquery-ui-widget'));    		  		  			
  }
  
  
	public function display_options_on_product_page() { 
    include_once(Pektsekye_ODF()->getPluginPath() . 'Block/Product/Js.php');
    $block = new Pektsekye_OptionDefault_Block_Product_Js();
    $block->toHtml();
  }
  

}
