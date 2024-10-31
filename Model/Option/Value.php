<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Model_Option_Value {


  public function __construct() {
    global $wpdb;
    
    $this->_wpdb = $wpdb;   
    $this->_mainTable = "{$wpdb->base_prefix}pofw_optiondefault_option_value";                        
  }    


  public function getValues($productId)
  {            
    $values = array();
   
    $productId = (int) $productId;     
    $select = "SELECT odf_value_id, value_id, is_selected FROM {$this->_mainTable} WHERE product_id={$productId}";
    $rows = $this->_wpdb->get_results($select, ARRAY_A);      
    
    foreach($rows as $r){
      $values[$r['value_id']] = array('odf_value_id' => $r['odf_value_id'], 'is_selected' => $r['is_selected']); 
    }
    
    return $values;                    
  }


  public function getAllValues()
  {            
    $values = array();
       
    $select = "SELECT value_id, is_selected FROM {$this->_mainTable} WHERE is_selected > 0";
    $rows = $this->_wpdb->get_results($select, ARRAY_A);      
    
    foreach($rows as $r){
      $values[$r['value_id']] = array('is_selected' => $r['is_selected']); 
    }
    
    return $values;                    
  }    


  public function saveValues($productId, $optionId, $values)
  { 
    $productId = (int) $productId;
    $optionId = (int) $optionId;
    
    foreach ($values as $r){
      $odfValueId = isset($r['odf_value_id']) ? (int) $r['odf_value_id'] : 0;    
      $valueId = (int) $r['value_id'];           
      $isSelected = isset($r['is_selected']) && $r['is_selected'] == 1 ? 1 : 0;            

      if ($odfValueId > 0){             
        $this->_wpdb->query("UPDATE {$this->_mainTable} SET is_selected = '{$isSelected}' WHERE odf_value_id = {$odfValueId}");                        
      } else {
        $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, option_id = {$optionId}, value_id = {$valueId}, is_selected = '{$isSelected}'");           
      }    
    }                     
  }  
  

  public function deleteValues($productId)
  {  
    $productId = (int) $productId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");  
  }      

}
