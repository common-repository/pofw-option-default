( function ($) {
  "use strict";
  
  $.widget("pektsekye.pofwOptionDefault", {
    _create: function(){   		    

      $.extend(this, this.options);
                
      this.preselectOptions();
                                
    },    
    
    
    preselectOptions : function(){
      var ii,ll, oId, vId, input, type, changed, el;
      var l = this.optionIds.length;
      for (var i=0;i<l;i++){ 
        oId = this.optionIds[i];
        
        input = this.element.find('[name^="pofw_option['+oId+']"]').first();

        type = this.optionTypes[oId];
        
        if (type == 'radio' || type == 'checkbox'){ 
          ll = this.vIdsByOId[oId].length;
          for (ii=0;ii<ll;ii++){        
            vId = this.vIdsByOId[oId][ii];         
            if (this.valueIsSelected[vId]){
              el = $('#pofw_option_value_' + vId);
              el[0].checked = true;
              el.change();
            }
          }        
        } else if (type == 'drop_down' || type == 'multiple') {
          changed = false;
          ll = this.vIdsByOId[oId].length;
          for (ii=0;ii<ll;ii++){        
            vId = this.vIdsByOId[oId][ii];         
            if (this.valueIsSelected[vId]){
              el = input.find('option[value="'+vId+'"]');
              el[0].selected = true;
              changed = true;
            }
          }
          if (changed){
            input.change();
          }
        } else if (type == 'field' || type == 'area') {
          if (this.optionPlaceholderText[oId]){
            input[0].placeholder = this.optionPlaceholderText[oId];
          }  
        }
      }   
    }      
    
  });

})(jQuery);
    


