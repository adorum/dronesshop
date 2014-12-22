<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_total; ?></td>
            <td><input type="text" name="cod_fee_total" value="<?php echo $cod_fee_total; ?>" /></td>
          </tr>
           <tr>
            <td><?php echo $entry_fee_type; ?></td>
            <td><select name="cod_fee_fee_type" id="cod_fee_fee_type" onchange="minmax_onoff();">
                  <option value="f" <?php echo $cod_fee_fee_type == 'f' ? 'selected="selected"' : ''; ?>><?php echo $text_fixed; ?></option>
				  <option value="p" <?php echo $cod_fee_fee_type == 'p' ? 'selected="selected"' : ''; ?>><?php echo $text_perc; ?></option>
                </select></td>
          </tr>
         <tr>
            <td><span class="required">*</span> <?php echo $entry_fee; ?></td>
            <td><input type="text" name="cod_fee_fee" value="<?php echo $cod_fee_fee; ?>" />
			    <?php if ($error_fee) { ?>
                <span class="error"><?php echo $error_fee; ?></span>
                <?php } ?></td>
          </tr>
         <tr>
            <td><?php echo $entry_fee_min; ?></td>
            <td><input type="text" name="cod_fee_fee_min" id="cod_fee_fee_min" value="<?php echo $cod_fee_fee_min; ?>" /></td>
          </tr>
         <tr>
            <td><?php echo $entry_fee_max; ?></td>
            <td><input type="text" name="cod_fee_fee_max" id="cod_fee_fee_max" value="<?php echo $cod_fee_fee_max; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_tax_class; ?></td>
            <td><select name="cod_fee_tax_class_id">
                  <option value="0"><?php echo $text_none; ?></option>
                  <?php foreach ($tax_classes as $tax_class) { ?>
                  <?php if ($tax_class['tax_class_id'] == $cod_fee_tax_class_id) { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="cod_fee_status">
                <?php if ($cod_fee_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="cod_fee_sort_order" value="<?php echo $cod_fee_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  <div align="center">
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
      <input type="hidden" name="cmd" value="_s-xclick">
      <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
      <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
      <img alt="" border="0" src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif" width="1" height="1">
    </form>
  </div>
</div>
<script type="text/javascript">
function minmax_onoff() {
	if(document.getElementById('cod_fee_fee_type').value == 'f') {
		document.getElementById('cod_fee_fee_min').disabled = true;
		document.getElementById('cod_fee_fee_max').disabled = true;
	} else {
		document.getElementById('cod_fee_fee_min').disabled = false;
		document.getElementById('cod_fee_fee_max').disabled = false;
	}
}
minmax_onoff(); 
</script>
<?php echo $footer; ?> 