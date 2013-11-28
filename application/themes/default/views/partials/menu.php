<div class="container-header-menu">
	<div id="menu" class="header-menu">
		<?php if ($categories) { ?>
			<ul>
				<li class="li-parent">
		    		<a href="<?php echo base_url(); ?>">Trang chủ</a>
				</li>
			    <?php $zid = 2000; 
			    foreach ($categories as $category) { ?>
			    <li class="li-parent">
		    		<a href="<?php echo $category['href']; ?>"><span><?php echo $category['name']; ?></span></a>
		      		<?php if ($category['children']) { ?>
				        <?php for ($i = 0; $i < count($category['children']);) { ?>
					        <ul  style="display: none;">
					          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
					          <?php for (; $i < $j; $i++) { ?>
					          		<?php if (isset($category['children'][$i])) { ?>
					          		<li>
					          			<a href="<?php echo $category['children'][$i]['href']; ?>"><span><?php echo $category['children'][$i]['name']; ?></span></a>
					          		</li>
					          		<?php } ?>
					          <?php } ?>
					        </ul>
		        		<?php } ?>
			       <?php } ?>
			    </li>
			    <?php $zid--;} ?>
			</ul>
		<?php } ?> 
	</div>
</div>
<script type="text/javascript">
<!--
	$(document).ready(function() {
	    $('#menu > ul').superfish({
	        hoverClass   : 'sfHover',
	        pathClass    : 'overideThisToUse',
	        delay        : 0,
	        animation    : {height: 'show'},
	        speed        : 'fast',
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
//-->
</script> 