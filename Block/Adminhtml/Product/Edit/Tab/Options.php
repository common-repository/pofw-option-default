<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Block_Adminhtml_Product_Edit_Tab_Options {

  protected $_odfValue;
  protected $_odfOption;
    
  protected $_productOptions;      
 
  
	public function __construct() {
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option.php');
    $this->_odfOption = new Pektsekye_OptionDefault_Model_Option();
    	
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option/Value.php');
    $this->_odfValue = new Pektsekye_OptionDefault_Model_Option_Value();
  }



  public function getProductId() {
    global $post;    
    return (int) $post->ID;  
  }
  
  
  public function getProductOptions() {  
    if (!isset($this->_productOptions)){
      $this->_productOptions = $this->_odfOption->getProductOptions($this->getProductId());
    }    
    return $this->_productOptions;              
  }


  public function getOptions(){  
    $options = array();
    
    $odfOptions = $this->_odfOption->getOptions($this->getProductId());
    $odfValues = $this->_odfValue->getValues($this->getProductId());      
    
    foreach($this->getProductOptions() as $optionId => $option){    
      $optionId = (int) $optionId;
      
      $values = array();
      foreach($option['values'] as $value){
        $vId = (int) $value['value_id'];        
        $values[$vId] = array(
         'odf_value_id' => isset($odfValues[$vId]) ? $odfValues[$vId]['odf_value_id'] : -1,            
         'title' => $value['title'],
         'is_selected' => isset($odfValues[$vId]) ? $odfValues[$vId]['is_selected'] : ''                      
        );                
      }
 
      $options[$optionId] = array(
        'odf_option_id' => isset($odfOptions[$optionId]) ? $odfOptions[$optionId]['odf_option_id'] : -1,    
        'title' => $option['title'],
        'type' => $option['type'],
        'placeholder_text' => isset($odfOptions[$optionId]) ? $odfOptions[$optionId]['placeholder_text'] : '',        
        'values' => $values
      );                 
    }
    
    return $options;
  }
  
  
  public function getProductOptionsPluginEnabled(){
    return function_exists('Pektsekye_PO');  
  }
   
  
  public function toHtml() {

    
    echo '<div id="pofw_odf_product_data" class="panel woocommerce_options_panel hidden">';
    
    include_once(Pektsekye_ODF()->getPluginPath() . 'view/adminhtml/templates/product/edit/tab/options.php');
    
    echo ' </div>';
  }


}
