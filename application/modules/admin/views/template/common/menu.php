<?php if ($loggedin_flg) { ?>
 <div id="menu">
   <ul class="left" style="display: none;">
     <li id="dashboard"><a href="<?php echo base_url();?>admin/dashboard" class="top"><?php echo $text_home; ?></a>
     </li>
     <li id="catalog"><a class="top"><?php echo $text_catalog;?></a>
       <ul>
         <li><a href="<?php echo base_url();?>admin/category"><?php echo $text_category;?></a></li>
         <li><a href="<?php echo base_url();?>admin/product"><?php echo $text_product;?></a></li>
         <li><a href="<?php echo base_url();?>admin/news"><?php echo $text_news;?></a></li>
         <li><a href="<?php echo base_url();?>admin/manufacturer"><?php echo $text_manufacturer;?></a></li>
         <li><a href="<?php echo base_url();?>admin/stock"><?php echo $text_stock_status; ?></a></li>
         <li><a href="<?php echo base_url();?>admin/review"><?php echo $text_review;?></a></li>
         <li><a href="<?php echo base_url();?>admin/faq"><?php echo $text_faq;?></a></li>
       </ul>
     </li>
     <li id="system"><a class="top"><?php echo $text_system; ?></a>
        <ul>
          <li><a href="<?php echo base_url();?>admin/setting"><?php echo $text_setting; ?></a></li>
          
          <li><a class="parent"><?php echo $text_users; ?></a>
            <ul>
              <li><a href="<?php echo base_url();?>admin/user"><?php echo $text_users; ?></a></li>
              <li><a href="<?php echo base_url();?>admin/usergrp"><?php echo $text_usergrp; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo base_url();?>admin/country"><?php echo $text_country; ?></a></li>
          <li><a href="<?php echo base_url();?>admin/language"><?php echo $text_language; ?></a></li>
          <li><a href="<?php echo base_url();?>admin/currency"><?php echo $text_currency; ?></a></li>
          <li><a href="<?php echo base_url();?>admin/information"><?php echo $text_information;?></a></li>
          <li><a href="<?php echo base_url();?>admin/zone"><?php echo $text_zone; ?></a></li>
          <li><a href="<?php echo base_url();?>admin/contact"><?php echo $text_contact; ?></a></li>
        </ul>
      </li>
   </ul>
   <ul class="right">
     <li id="store">
        <a onClick="window.open('<?php echo base_url();?>');" class="top">
	        <span class="viewsite">
	            <?php echo $text_frontend; ?>
	        </span>
        </a>
     </li>
     <li id="store">
        <a class="top" href="<?php echo base_url();?>admin/logout">
        	<span class="logout">
        	 	<?php echo $text_logout; ?>
        	</span>
        </a>
     </li>
   </ul>
<script type="text/javascript">
	<!--
	$(document).ready(function() {
	    $('#menu > ul').superfish({
	        hoverClass   : 'sfHover',
	        pathClass    : 'overideThisToUse',
	        delay        : 0,
	        animation    : {height: 'show'},
	        speed        : 'normal',
	        autoArrows   : false,
	        dropShadows  : false, 
	        disableHI    : false, /* set to true to disable hoverIntent detection */
	        onInit       : function(){},
	        onBeforeShow : function(){},
	        onShow       : function(){},
	        onHide       : function(){}
	    });
	    
	    $('#menu > ul').css('display', 'block');
	});

	$(document).ready(function() {
		var url = window.location.pathname;

		$('#menu').find('>ul:first').children().each(function(){
			var parent_mask = $(this);
			$(this).find('a').each(function(){
				if ($(this).attr('href') !== undefined){
					var arr_url = $(this).attr('href').split('/');
					if (arr_url.length >= 7) {
						var pattern = arr_url[6];
					} else {
						var pattern = arr_url[5];
					}
			    	if(new RegExp(pattern).test(url)){
			    		parent_mask.addClass('selected');
			    		return true;	    		
			    	}
				}
			});
		});
	});
	//-->
</script> 
</div>
<?php } ?>