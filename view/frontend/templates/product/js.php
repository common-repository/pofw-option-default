<?php
if (!defined('ABSPATH')) exit;
?>
<?php if (count($this->getProductOptions()) > 0): ?>   
<script type="text/javascript"> 
    var config = {};
    
    var pofwOdfData = <?php echo $this->getOptionsDataJson(); ?>;
    
    jQuery.extend(config, pofwOdfData);
      
    jQuery("#pofw_product_options").pofwOptionDefault(config);
    
</script>        
<?php endif; ?>
