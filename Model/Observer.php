<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Model_Observer {  

  protected $_odfOption;        
  protected $_odfValue; 
                 
  protected $_productOptions = array();
  protected $_skipOIds = array();     
      
      
  public function __construct(){           
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option.php' );
    $this->_odfOption = new Pektsekye_OptionDefault_Model_Option();
    
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option/Value.php');
    $this->_odfValue = new Pektsekye_OptionDefault_Model_Option_Value();
     
    add_action('woocommerce_process_product_meta', array($this, 'save_product_options'), 11); //after the Product Options plugin  
    add_filter('pofw_csv_export_data_option_rows', array($this, 'add_data_to_csv_export_option_rows'), 10, 1);       
    add_filter('pofw_csv_export_data_option_value_rows', array($this, 'add_data_to_csv_export_option_value_rows'), 10, 1);
    add_action("pofw_csv_import_product_options_saved", array($this, 'save_product_options_from_csv'), 10, 2);                   
		add_action('delete_post', array($this, 'delete_post'));    	          		
  }	  


  public function getOptionModel(){
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option.php');		
    return new Pektsekye_OptionDefault_Model_Option();    
  }  
  
  
  public function getProductOptions($productId){
    if (!isset($this->_productOptions[$productId])){    
      $this->_productOptions[$productId] = $this->getOptionModel()->getProductOptions($productId);
    }
    return $this->_productOptions[$productId];
  }
  
 
  public function save_product_options($post_id){
    if (isset($_POST['pofw_odf_changed']) && $_POST['pofw_odf_changed'] == 1){
      $productId = (int) $post_id;  
          
      $options = array();
      
      if (isset($_POST['pofw_odf_options'])){                          
        foreach ($_POST['pofw_odf_options'] as $optionId => $o){
         
          $values = array();
          if (isset($o['values'])){          
            foreach ($o['values'] as $valueId => $v){
            
              $isSelected = false;          
              if (isset($o['selected_ids'])){
                if (is_array($o['selected_ids'])){
                  $isSelected = in_array($valueId, $o['selected_ids']);
                } else {
                  $isSelected = $o['selected_ids'] == $valueId;
                }
              }
                   
              $values[] = array(
                'value_id' => (int) $valueId,          
                'odf_value_id' => (int) $v['odf_value_id'],                                      
                'is_selected' => $isSelected ? 1 : 0
              );                        
            }
          } 
                      
          $options[] = array(
            'option_id' => (int) $optionId,          
            'odf_option_id' => (int) $o['odf_option_id'],                       
            'placeholder_text' => isset($o['placeholder_text']) ? sanitize_text_field(stripslashes($o['placeholder_text'])) : '',
            'values' => $values
          );                         
        }                
      }      

      $this->_odfOption->saveOptions($productId, $options);                     
    }
  }
  

  public function add_data_to_csv_export_option_rows($rows){
       
    $options = $this->_odfOption->getAllOptions();

    foreach ($rows as $k => $row){ 
      $optionId = $row['option_id'];
      if (isset($options[$optionId]) && !empty($options[$optionId]['placeholder_text'])){ 
        $rows[$k]['placeholder_text'] = $options[$optionId]['placeholder_text'];     
      }                            
    }
    
    return $rows;    
  }


  public function add_data_to_csv_export_option_value_rows($rows){  
    $values = $this->_odfValue->getAllValues();

    foreach ($rows as $k => $row){ 
      $valueId = $row['value_id'];
      if (isset($values[$valueId]) && $values[$valueId]['is_selected'] == 1){       
        $rows[$k]['is_selected'] = 1;
      }                                 
    }
    
    return $rows;    
  }
   
  
  public function save_product_options_from_csv($productId, $optionsData){

    $options = array();
    
    $this->_odfOption->deleteOptions($productId);
       
    foreach($optionsData as $o){       
      $values = array();
      if (isset($o['values'])){           
        foreach($o['values'] as $v){
          $values[] = array(
            'value_id' => (int) $v['value_id'],          
            'odf_value_id' => -1,                               
            'is_selected' => isset($v['is_selected']) && $v['is_selected'] == 1 ? 1 : 0
          );
        }       
      }
      
      $options[] = array(
        'option_id' => (int) $o['option_id'],          
        'odf_option_id' => -1,                       
        'placeholder_text' => isset($o['placeholder_text']) ? $o['placeholder_text'] : '',
        'values' => $values
      );      
      
    }    
    
    $this->_odfOption->saveOptions($productId, $options);                
  }      
      
	
	public function delete_post($id){
		if (!current_user_can('delete_posts') || !$id || get_post_type($id) != 'product'){
			return;
		}
		 		
    $this->_odfOption->deleteOptions($id);             
	}		
		
}
