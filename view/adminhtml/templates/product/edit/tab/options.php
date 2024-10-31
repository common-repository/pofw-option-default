<?php
if (!defined('ABSPATH')) exit;
?>
<div class="pofw-odf-container">
<?php if (!$this->getProductOptionsPluginEnabled()): ?>
  <div class="pofw_optiondefault-create-ms"><?php echo __('Please, install and enable the <a href="https://wordpress.org/plugins/pofw-option-default/" target="_blank">Product Options</a> plugin.', 'pofw-option-default'); ?></div>
<?php else: ?>
  <div id="pofw_odf_options">
    <?php foreach ($this->getOptions() as $optionId => $option): ?>
      <div>
        <div class="pofw-odf-option-title">
          <span class="pofw-title"><?php echo htmlspecialchars($option['title']); ?></span>         
          <input type="hidden" name="pofw_odf_options[<?php echo $optionId; ?>][odf_option_id]" value="<?php echo $option['odf_option_id']; ?>"/>                    
        </div>  
        <div class="pofw-odf-option-fields">   
          <?php if ($option['type'] == 'field' || $option['type'] == 'area'): ?>             
            <div class="pofw-odf-option-placeholder-text">
              <label for="pofw_odf_options_<?php echo $optionId; ?>_placeholder_text"><?php echo __('Placeholder text', 'pofw-option-default'); ?>:</label> <input type="text" id="pofw_odf_options_<?php echo $optionId; ?>_placeholder_text" name="pofw_odf_options[<?php echo $optionId; ?>][placeholder_text]" value="<?php echo htmlspecialchars($option['placeholder_text']); ?>" autocomplete="off"/>                                                                  
            </div>
          <?php endif; ?>          
        </div>
        <?php if (!empty($option['values'])): ?>                        
          <div class="pofw-odf-values">
          
            <div class="pofw-odf-value">
              <?php if ($option['type'] == 'drop_down' || $option['type'] == 'radio'): ?>            
                <div class="pofw-odf-value-th">
                  <label for="pofw_odf_option_<?php echo $optionId; ?>_is_selected_all"><?php echo __('Not selected', 'pofw-option-default'); ?></label>
                </div>
                <div class="pofw-odf-value-th">
                  <input id="pofw_odf_option_<?php echo $optionId; ?>_is_selected_all" name="pofw_odf_options[<?php echo $optionId; ?>][<?php echo $option['type'] == 'drop_down' || $option['type'] == 'radio' ? 'selected_ids' : 'selected_ids_all' ; ?>]" <?php echo $option['type'] == 'drop_down' || $option['type'] == 'radio' ? 'type="radio"' : 'type="checkbox"' ; ?> value="0" class="pofw-odf-option-select-all"/>
                </div>
              <?php endif; ?>                                                         
            </div>    
                
            <?php foreach ($option['values'] as $valueId => $value): ?>
              <div class="pofw-odf-value">
                <div class="pofw-odf-value-title">
                  <label for="pofw_odf_value_<?php echo $valueId; ?>_is_selected"><?php echo htmlspecialchars($value['title']); ?></label>
                </div>
                <div class="pofw-odf-value-is-selected">
                  <input id="pofw_odf_value_<?php echo $valueId; ?>_is_selected" name="pofw_odf_options[<?php echo $optionId; ?>][selected_ids]<?php echo $option['type'] == 'drop_down' || $option['type'] == 'radio' ? '' : '[]' ; ?>" <?php echo $option['type'] == 'drop_down' || $option['type'] == 'radio' ? 'type="radio"' : 'type="checkbox"' ; ?> value="<?php echo $valueId; ?>" class="pofw-odf-value-is-selected-input" <?php echo $value['is_selected'] == 1 ? 'checked="checked"' : ''; ?>/>                    
                  <input type="hidden" name="pofw_odf_options[<?php echo $optionId; ?>][values][<?php echo $valueId; ?>][odf_value_id]" value="<?php echo $value['odf_value_id']; ?>"/>                            
                </div>                                      
              </div>          
            <?php endforeach; ?>        
          </div>
        <?php endif; ?>                  
      </div>    
    <?php endforeach; ?>               
    <input type="hidden" id="pofw_odf_changed" name="pofw_odf_changed" value="0">        
  </div> 
   <script type="text/javascript">
      var config = {};
  
      jQuery('#pofw_odf_options').pofwOptionDefault(config);   
        
  </script>                 
<?php endif; ?>     
</div>

    