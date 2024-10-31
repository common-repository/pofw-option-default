<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Controller_Adminhtml_Product {


	public function __construct() {
	
    add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') , 15);// 15 to call it after WooCommerce
    	
    add_filter( 'woocommerce_product_data_tabs', array( $this, 'add_product_tab') , 99 , 1 );	
    add_action( 'woocommerce_product_data_panels', array( $this, 'add_tab_fields') );   							  				
	}
  
  
  public function add_product_tab( $tabs ) {
    $tabs['pofw_odf_product_data'] = array(
        'label' => __('Option Default', 'pofw-option-default' ),
        'target' => 'pofw_odf_product_data',
        'class'  => array(),
        'priority' => 91 //after the Custom Options tab              
    );
    return $tabs;
  } 
  
  
  public function enqueue_admin_scripts(){
    global $pagenow;
    if ((isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit') || ('post-new.php' == $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'product')){  
      wp_enqueue_script('pofw_odf_product_options', Pektsekye_ODF()->getPluginUrl() . 'view/adminhtml/web/product/edit/main.js', array('jquery', 'jquery-ui-widget', 'jquery-ui-dialog'));
      wp_enqueue_style('pofw_odf_product_options', Pektsekye_ODF()->getPluginUrl() . 'view/adminhtml/web/product/edit/main.css');		    
    }    
  }
    
  
	public function add_tab_fields() { 
    include_once(Pektsekye_ODF()->getPluginPath() . 'Block/Adminhtml/Product/Edit/Tab/Options.php');
    $block = new Pektsekye_OptionDefault_Block_Adminhtml_Product_Edit_Tab_Options();
    $block->toHtml();
  }
  

}
