<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Model_Option {
           
  protected $_wpdb;          
  protected $_odfValue;
     
      
  public function __construct(){
    global $wpdb;    
    $this->_wpdb = $wpdb; 
    $this->_mainTable = "{$wpdb->base_prefix}pofw_optiondefault_option"; 
    
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option/Value.php');
    $this->_odfValue = new Pektsekye_OptionDefault_Model_Option_Value();                        		
  }	


  public function getProductOptions($productId)
  {
    $productOptions = array();
    if (function_exists('Pektsekye_PO')){
      include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option.php' );
      $optionModel = new Pektsekye_ProductOptions_Model_Option();
      $productOptions = $optionModel->getProductOptions($productId);
    }
    return $productOptions;  
  }


  public function getOptions($productId)
  {            
    $options = array();
   
    $productId = (int) $productId;     
    $select = "SELECT odf_option_id, option_id, placeholder_text FROM {$this->_mainTable} WHERE product_id={$productId}";
    $rows = $this->_wpdb->get_results($select, ARRAY_A);      
    
    foreach ($rows as $r){
      $options[$r['option_id']] = array('odf_option_id' => $r['odf_option_id'], 'placeholder_text' => $r['placeholder_text']); 
    }
    
    return $options;                    
  }


  public function getAllOptions()
  {            
    $options = array();
       
    $select = "SELECT option_id, placeholder_text FROM {$this->_mainTable} WHERE placeholder_text != ''";
    $rows = $this->_wpdb->get_results($select, ARRAY_A);      
    
    foreach($rows as $r){
      $options[$r['option_id']] = array('placeholder_text' => $r['placeholder_text']); 
    }
    
    return $options;                    
  }    


  public function saveOptions($productId, $options)
  { 
    $productId = (int) $productId;

    foreach ($options as $r){
      $odfOptionId = isset($r['odf_option_id']) ? (int) $r['odf_option_id'] : 0;    
      $optionId = (int) $r['option_id']; 
      $placeholderText = isset($r['placeholder_text']) ? esc_sql($r['placeholder_text']) : '';                    

      if ($odfOptionId > 0){             
        $this->_wpdb->query("UPDATE {$this->_mainTable} SET placeholder_text = '{$placeholderText}' WHERE odf_option_id = {$odfOptionId}");                        
      } else {
        $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, option_id = {$optionId}, placeholder_text = '{$placeholderText}'");           
      }    
    
      $this->_odfValue->saveValues($productId, $optionId, $r['values']);    
    }  
                       
  }  
  

  public function deleteOptions($productId)
  {  
    $productId = (int) $productId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");  
  
    $this->_odfValue->deleteValues($productId);   
  }


}
