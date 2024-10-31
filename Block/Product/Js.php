<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionDefault_Block_Product_Js {

  protected $_odfValue;
  protected $_odfOption;
    
  protected $_productOptions;     
  

	public function __construct(){
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option.php');
    $this->_odfOption = new Pektsekye_OptionDefault_Model_Option();
    	
    include_once(Pektsekye_ODF()->getPluginPath() . 'Model/Option/Value.php');
    $this->_odfValue = new Pektsekye_OptionDefault_Model_Option_Value();			 		  			
	}


  public function getProductId(){
    global $product;
    return (int) $product->get_id();              
  }
  
  
  public function getProductOptions() {  
    if (!isset($this->_productOptions)){
      $this->_productOptions = $this->_odfOption->getProductOptions($this->getProductId());
    }    
    return $this->_productOptions;              
  }
  
  
  public function getOptionsDataJson(){
             
    $odfOptions = $this->_odfOption->getOptions($this->getProductId());
    $odfValues = $this->_odfValue->getValues($this->getProductId());
    
    $optionIds = array();
    $vIdsByOId = array();
    $optionTypes = array();              
    $optionPlaceholderText = array();  
    $valueIsSelected = array();
                
    foreach($this->getProductOptions() as $option){
    
      $oId = (int) $option['option_id'];
      
      foreach($option['values'] as $value){
        $vId = (int) $value['value_id'];       
        if (isset($odfValues[$vId])){
          if (!empty($odfValues[$vId]['is_selected']) && $odfValues[$vId]['is_selected'] > 0){
            $valueIsSelected[$vId] = 1;
          }                    
        }
        $vIdsByOId[$oId][] = $vId;                          
      }
               
      if (isset($odfOptions[$oId])){
        if (!empty($odfOptions[$oId]['placeholder_text'])){
          $optionPlaceholderText[$oId] = $odfOptions[$oId]['placeholder_text'];
        }        
      } 
              

      $optionIds[] = $oId;     
      $optionTypes[$oId] = $option['type'];                 
    }    
    
    return json_encode(array('optionIds' => $optionIds, 'vIdsByOId' => $vIdsByOId, 'optionTypes' => $optionTypes, 'optionPlaceholderText' => $optionPlaceholderText, 'valueIsSelected' => $valueIsSelected));              
  }

    
  public function toHtml(){  
    include_once(Pektsekye_ODF()->getPluginPath() . 'view/frontend/templates/product/js.php');
  }


}
