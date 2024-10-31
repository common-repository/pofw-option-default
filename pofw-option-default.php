<?php
/**
 * Plugin Name: POFW Option Default 
 * Description: Allows to preselect product options by default.
 * Version: 1.0.0
 * Author: Pektsekye
 * Author URI: http://hottons.com
 * License: GPLv2     
 * Requires at least: 4.7
 * Tested up to: 6.4.3
 *
 * Text Domain: pofw-option-default
 *
 * WC requires at least: 3.0
 * WC tested up to: 8.6.1
 * 
 * @package OptionDefault
 * @author Pektsekye
 */
if (!defined('ABSPATH')) exit;

final class Pektsekye_OptionDefault {


  protected static $_instance = null;

  protected $_pluginUrl; 
  protected $_pluginPath;    
  

  public static function instance(){
    if (is_null(self::$_instance)) {
      self::$_instance = new self();
      self::$_instance->initApp();
    }
    return self::$_instance;
  }


  public function __construct(){
    $this->_pluginPath = plugin_dir_path(__FILE__);
    $this->_pluginUrl  = plugins_url('/', __FILE__);
  }


  public function initApp(){
    $this->includes();
    $this->init_hooks();
    $this->init_controllers();
  }
  
  
  public function includes(){  
    include_once('Model/Observer.php');              
  }
  

  private function init_hooks(){ 
    new Pektsekye_OptionDefault_Model_Observer();           
  }    


  private function init_controllers(){

		if ($this->is_request('frontend')){
      include_once('Controller/Product.php');
      new Pektsekye_OptionDefault_Controller_Product();    	
    } elseif ($this->is_request('admin')){ 
      global $pagenow;
      if ((isset($_GET['post']) && isset($_GET['action']) && $_GET['action'] == 'edit') || ('post-new.php' == $pagenow && isset($_GET['post_type']) && $_GET['post_type'] == 'product')){         
        include_once('Controller/Adminhtml/Product.php');
        new Pektsekye_OptionDefault_Controller_Adminhtml_Product();                    
      }                
    }     	  
  }  


  public function is_request($type){
    switch ($type) {
      case 'admin' :
        return is_admin();        
      case 'ajax' :
        return defined('DOING_AJAX');
      case 'cron' :
        return defined('DOING_CRON');
      case 'frontend' :
        return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON');
    }
  } 
  
  
  public function getPluginUrl(){
    return $this->_pluginUrl;
  }
  
  
  public function getPluginPath(){
    return $this->_pluginPath;
  }  
    
}


function Pektsekye_ODF(){
	return Pektsekye_OptionDefault::instance();
}

include_once('Setup/Install.php');  
register_activation_hook(__FILE__, array('Pektsekye_OptionDefault_Setup_Install', 'install'));

// If WooCommerce plugin is installed and active.
if (in_array('woocommerce/woocommerce.php', (array) get_option('active_plugins', array())) || in_array('woocommerce/woocommerce.php', array_keys((array) get_site_option('active_sitewide_plugins', array())))){
  Pektsekye_ODF();
}



