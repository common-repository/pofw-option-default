(function ($) {
  "use strict";

  $.widget("pektsekye.pofwOptionDefault", { 


    _create : function () {

      $.extend(this, this.options);
                          
      this._on({                          
        "change input": $.proxy(this.setChanged, this)                                                                                                                    
      });                            
    },       
    
        
    setChanged : function(){
      $('#pofw_odf_changed').val(1);     
    }  	        
    	
  }); 
   
})(jQuery);
