</div>
<div id="footer">
<div id="wrapper">
  <?php if ($informations) { ?>
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <!-- <div class="column">
    <h3><?php echo $text_extra; ?></h3>
    <ul>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
    </ul>
  </div> -->
  <div class="column">
    <h3><?php echo $text_account; ?></h3>
    <ul>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_products_footer; ?></h3>
    <ul>
		<?php foreach ($categories as $category) { ?>
		<li>
			<a href="<?php echo $category['href']; ?>" ><?php echo $category['name']; ?></a></li>
		<?php } ?>
	</ul>
  </div>
  <div class="column last-column">
    <h3>Kontakt</h3>
    <ul>
      <li>0904 857 224</li>
      <li>info@multikoptery.sk</li>
	  <li>
		  <div id="social">
			<a href="https://www.facebook.com/" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
			<a href="https://twitter.com/" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
		  </div>
	  </li>
    </ul>
  </div>
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
<!-- <div id="powered">Design By <a href="http://foojee.net/">foojee:design</a> / Powered By <a href="http://www.opencart.com">OpenCart</a> / Your Store &copy; 2012</div> -->
<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->
</div>
</div>
</body></html>
