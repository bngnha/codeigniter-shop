<div class="container1">
	<div class="container-center">
		<div class="border-bottom fl list_product"> 
			<div class="fl padding-bottom-20px">
				<div id="slideshow" class="nivoSlider" style="width: 940px; height: 333px;">
				<?php if (isset($banners)) { ?>
					<?php foreach ($banners as $banner) { ?>
					<a class="imgNone" href="<?php echo base_url(); ?>">
						<img src="<?php echo $banner; ?>">
					</a>
					<?php } ?>
				<?php } ?>
				</div>
			</div>
		<?php if ($success) { ?>
		  	<div class="success"><?php echo $success; ?></div>
		<?php } ?>
	<?php if (isset($products)) { ?>
		<?php foreach ($products as $product) { ?>
			<div class="container-block">
				<div class="block">
					<div align="center" style="height: 100%;" class="width">
						<a class="tooltip" href="<?php echo $product['href']; ?>">
							<img id="tooltip<?php echo $product['product_id']; ?>" src="<?php echo $product['normal']; ?>">
						</a>
					</div>
					<div class="product-info">
						<a href="<?php echo $product['href']; ?>">
							<p class="color-red"><?php echo $product['name']; ?></p>
						</a>
						<p style="margin-top:5px;">
							<span class="price"><?php echo $product['price']; ?> VNĐ</span>
						</p>
					</div>
				</div>
				<a href="<?php echo $product['href']; ?>">
					<div class="see_view fl">
						<span class="marc">Xem chi tiết</span>
					 	<div class="arrow-red"></div>
					</div>
				</a>
			</div>
		<?php }?>
	<?php } ?>		
		</div>
	</div>
</div>

<script type="text/javascript">
<!--
var isIE = (navigator.userAgent.toLowerCase().indexOf("msie") == -1 ? false : true);
var isIE6 = (navigator.userAgent.toLowerCase().indexOf("msie 6") == -1 ? false : true);
var isIE7 = (navigator.userAgent.toLowerCase().indexOf("msie 7") == -1 ? false : true);
var isChrome= (navigator.userAgent.toLowerCase().indexOf("chrome") == -1 ? false : true);

$(function(){
	tooltipProductTrack();
});

function tooltipProductTrack() {
	if(isIE6 || isIE7) return;
	<?php foreach ($products as $product) { ?>
	$("#tooltip<?php echo $product['product_id'];?>").tooltip({	
		bodyHandler: function() {
			return $("<img/>").attr("src", "<?php echo $product['large']; ?>");
		},
		track: true,
		showURL: false,
		fade: false
	});
	<?php } ?>
}
//-->
</script>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$('#slideshow').nivoSlider();
});
-->
</script>