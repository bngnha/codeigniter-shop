<div class="container width fl border-bottom padding-bottom-20px">
  	<div class="margin-left-20 maring-right-20">
		<div class="width padding-10"></div> 
	</div>
	<div class="border-bottom container width fl margin-top-20">
		<div class="container-detail">
		<div class="image-warrap">
			<div align="center" id="image" class="images-product fl">
				<a style="position: relative; display: block;" href="<?php echo $product['large']; ?>"
					rel="tint: '#fff', tintOpacity:0.5 , smoothMove:2, zoomWidth:480, adjustY:-4, adjustX:10"
					class="cloud-zoom" id="zoom1">
					<img align="left" style="display: block;" src="<?php echo $product['normal']; ?>" alt="" title="">
				</a>
			</div>
		<?php foreach ($product_images as $product_image) {?>
			<div class="fl">
				<div class="container width">
					<div style="margin-top: 10px;" class="fl tiny-images">
						<a href="<?php echo $product_image['large']; ?>" title="<?php echo $product_image['name']?>"
							rel="useZoom: 'zoom1', smallImage: '<?php echo $product_image['normal']; ?>' "
							class="cloud-zoom-gallery">
							<img src="<?php echo $product_image['small']; ?>" alt="<?php echo $product_image['name']?>">
						</a>
					</div>
				</div>
			</div>
		<?php } ?>
		</div>
		<div class="detail-right">
			<div class="width">
				<div style="font-size: 17px; margin-left: 20px; color: rgb(233, 125, 19); font-weight: bold; font-family: Tahoma,sans-serif; "
					class="container fl"><?php echo $product['name']?>
				</div>
				<div style="line-height: 20px;" class="width fl">
					<div>
						<div class="fl f1extra">
							<div class="product-info-detail">Tình trạng:</div><span style="font-size: 12px;" class="bold"><?php echo $product['stock_status']; ?></span><br/>
							<div class="product-info-detail">Giá:</div><span style="font-size: 12px;" class="bold"><?php echo $product['price']; ?> VNĐ</span><br/>
							<div class="product-info-detail">Kích thước:</div><span style="font-size: 12px;" class="bold"><?php echo $product['size']; ?></span><br/>
							<div class="product-info-detail">Màu sắc:</div><span style="font-size: 12px;" class="bold"><?php echo $product['color']; ?></span><br/>
							<div class="product-info-detail">Số lượng:</div><span style="font-size: 12px;" class="bold"><?php echo $product['quantity']; ?> chiếc</span><br/>
							<div class="product-info-detail">Lượt xem:</div><span style="font-size: 12px;" class="bold"><?php echo $product['viewed']; ?> lượt</span><br/>
							<div class="product-info-detail" style="margin-bottom: 8px;">Đánh giá:</div><img alt="0 reviews" src="<?php echo Asset::get_filepath_img('stars-'. $product['rating'] .'.png', true); ?>">
							
							<!-- AddThis Button BEGIN -->
							<div class="addthis_default_style"><a class="addthis_button_compact at300m" href="#"><span class="at16nc at300bs at15nc at15t_compact at16t_compact"><span class="at_a11y">More Sharing Services</span></span>Share</a> <a class="addthis_button_email at300b" title="Email" href="#"><span class="at16nc at300bs at15nc at15t_email at16t_email"><span class="at_a11y">Share on email</span></span></a><a class="addthis_button_print at300b" title="Print" href="#"><span class="at16nc at300bs at15nc at15t_print at16t_print"><span class="at_a11y">Share on print</span></span></a> <a class="addthis_button_facebook at300b" title="Facebook" href="#"><span class="at16nc at300bs at15nc at15t_facebook at16t_facebook"><span class="at_a11y">Share on facebook</span></span></a> <a class="addthis_button_twitter at300b" title="Tweet This" href="#"><span class="at16nc at300bs at15nc at15t_twitter at16t_twitter"><span class="at_a11y">Share on twitter</span></span></a></div>
							<script src="http://s7.addthis.com/js/250/addthis_widget.js" type="text/javascript"></script> 
							<!-- AddThis Button END -->
						</div>
						<div class="fl f1extra" style="height: 123px; border-width: 1px; border-style: solid; border-color: #DEDEDE;">
							<div style="width: 30%; float: left; margin-top: 14px; margin-left: 12px; margin-right: 10px;">
								<img alt="Nhật Minh Shop" src="<?php echo Asset::get_filepath_img('contact_logo.jpg', true)?>">
							</div>
							<div style="width: 65%; margin-top: 14px; float: left;">
								<div class="product-info-detail" style="font-size: 18px; margin-left: 10px;"><?php echo $shop_name; ?></div><br/>
								<div class="product-info-detail">Địa chỉ:</div><span style="font-size: 12px;"><?php echo $shop_address; ?></span><br/>
								<div class="product-info-detail">Điện thoại:</div><span style="font-size: 12px;"><?php echo $shop_telephone; ?></span><br/>
								<div class="product-info-detail">Email:</div><span style="font-size: 12px;"><a href="mailto:<?php echo $shop_email; ?>" style="color: #014C8F; "><?php echo $shop_email; ?></a></span><br/>
							</div>
						</div>
						<div class="button-addtobag fl">
							<div class="buttons">
								<div class="left">
									<a class="button" href="<?php echo $contact_link; ?>">
										<span>Liên hệ mua hàng</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div class="htabs" id="tabs">
			<a href="#tab-desciption" style="display: inline;" class="selected">Mô tả</a>
			<a href="#tab-review" style="display: inline;" class="">Đánh giá (<?php echo $product['rating']; ?>)</a>
		</div>
		<div id="tab-desciption" class="detail-product">
			<?php echo $product['description']; ?>
		</div>
		<div id="tab-review" class="detail-product">
		<?php if ($reviews) { ?>
			<div id="review">
			<?php foreach ($reviews as $review) { ?>
				<div class="content" style="margin-bottom: 20px;">
					<b><?php echo $review['author']; ?></b> | <?php echo $review['date_added']; ?><br>
					<img alt="2 reviews" src="<?php echo Asset::get_filepath_img('stars-'. $review['rating'] .'.png', true); ?>"><br><br>
					<?php echo $review['text']; ?>
				</div>
			<?php } ?>
			</div>
		<?php } ?>
			<h3 class="review-title">Viết đánh giá</h3>
			<div id="success_id" style="width: 99%;">
			</div>
			<div class="content">
				<b>Họ tên của bạn:</b><br />
				<input type="hidden" name="productId" value="<?php echo $product['product_id']; ?>" /><br/>
				<input type="text" name="name" value="" /><br/>
				<span id="name_id"></span>
				<?php if ($error_name) { ?>
					<span class="error"><?php echo $error_name; ?></span>
				<?php } ?>
				<b>Viết đánh giá:</b>
				<textarea name="review" cols="40" rows="5" style="width: 98%;"></textarea>
				<span id="review_id"></span>
				<?php if ($error_review) { ?>
					<span class="error"><?php echo $error_review; ?></span>
				<?php } ?>
				<span style="font-size: 11px;">Lưu ý: Không hỗ trợ HTML</span><br />
				<b>Bình chọn:</b>
				<span>Xấu</span>&nbsp;
					<input type="radio" name="rating" value="1" />&nbsp;
					<input type="radio" name="rating" value="2" />&nbsp;
					<input type="radio" name="rating" value="3" checked="checked"/>&nbsp;
					<input type="radio" name="rating" value="4" />&nbsp;
					<input type="radio" name="rating" value="5" />&nbsp;
				<span>Tốt</span><br /><br />
				<b>Nhập mã kiểm tra vào ô bên dưới</b><br />
				<input type="text" name="captcha" value=""/><br>
				<span id="captcha_id"></span>
				<?php if ($error_captcha) { ?>
					<span class="error"><?php echo $error_captcha; ?></span>
				<?php } ?>
				<?php echo $captcha; ?>
			</div>
		    <div class="buttons" style="background-color: #F7F7F7; padding-left: 0px;">
				<div class="left">
					<a class="button" id="review_button">
						<span>Gởi đánh giá</span>
					</a>
				</div>
			</div>
		   	
		</div>
	</div>
	<div class="container width fl margin-top-20">
		<div class="other">
			<div class="title">
				<div class="fl"></div>
				<div class="fc">
					<span>Các sản phẩm liên quan</span>
				</div>
				<div class="fr"></div>
			</div>
		<?php if (count($related_products)> 0 ) { ?>
			<?php if (count($related_products) <= 8 ) { ?>
				<div class="content">
					<div class="list_product other_product">
				<?php foreach ( $related_products as $related_product ) { ?>
						<div class="fl width_4" style="width: 12.5%;">
							<div class="block">
								<div class="picture_small">
									<a href="<?php echo $related_product['href']; ?>">
										<img src="<?php echo $related_product['image']?>" alt="<?php echo $related_product['atl']?>"
										original="<?php echo $related_product['image']?>" style="display: inline;">
									</a>
								</div>
								<div class="name">
									<a href="<?php echo $related_product['href']; ?>" title="<?php echo $related_product['name']; ?>">
										<?php echo $related_product['name']; ?>
									</a>
								</div>
								<div class="price"><?php echo $related_product['price']; ?> VNĐ</div>
							</div>
						</div>
				<?php } ?>	
						<div class="clear"></div>
					</div>
				</div>
			<?php } else { ?>
				<div class="content jcarousel-skin">
					<div id="carousel" class="list_product other_product">
						<ul class="jcarousel-list jcarousel-list-horizontal">
					<?php foreach ( $related_products as $related_product ) { ?>
							<li>
								<div class="fl width_4" style="width: 100%;">
									<div class="block">
										<div class="picture_small">
											<a href="<?php echo $related_product['href']; ?>">
												<img src="<?php echo $related_product['image']?>" alt="<?php echo $related_product['atl']?>"
												original="<?php echo $related_product['image']?>" style="display: inline;">
											</a>
										</div>
										<div class="name">
											<a href="<?php echo $related_product['href']; ?>" title="<?php echo $related_product['name']; ?>">
												<?php echo $related_product['name']; ?>
											</a>
										</div>
										<div class="price"><?php echo $related_product['price']; ?> VNĐ</div>
									</div>
								</div>
							</li>
					<?php } ?>	
						</ul>
						<div class="clear"></div>
					</div>
				</div>
		<?php } ?>
		<?php } else { ?>
			<div class="content">
				<div class="list_product other_product">
					<span style="color: red; font-size: 14px; padding-left: 10px; padding-top: 0px; margin-top: 5px; float: left;">Không có sản phẩm liên quan!</span>
				</div>
			</div>
		<?php } ?>
			<div class="bottom">
				<div class="fl"></div>
				<div class="fr"></div>
			</div>
		</div>
	</div>
  </div>
<script type="text/javascript"><!--
$('#tabs a').tabs();//-->
</script>
<?php if (count($related_products) > 0 &&  count($related_products) > 8) { ?>
<script type="text/javascript"><!--
$('#carousel ul').jcarousel({
	wrap: 'last',
	auto: 2,
	initCallback : initCallback,
	vertical: false,
	visible: 8,
	scroll: 1
});
function initCallback(carousel)
{
    // Disable autoscrolling if the user clicks the prev or next button.
    carousel.buttonNext.bind('click', function() {
        carousel.startAuto(0);
    });

    carousel.buttonPrev.bind('click', function() {
        carousel.startAuto(0);
    });

    // Pause autoscrolling if the user moves with the cursor over the clip.
    carousel.clip.hover(function() {
        carousel.stopAuto();
    }, function() {
        carousel.startAuto();
    });
};//-->
</script>
<?php } ?>
<script type="text/javascript"><!--
$(document).ready(function(){
	$('#review_button').bind('click', function(){
		var url = '<?php echo $review_action; ?>';
		$('#name_id').removeClass('error').html('');
		$('#review_id').removeClass('error').html('');
		$('#captcha_id').removeClass('error').html('');
		var img_close = '<img class="close" alt="" src="<?php echo Asset::get_filepath_img('close.png', true); ?>">';
		var data = {product_id: $('input[name=productId]').val(),
					name: $('input[name=name]').val(), 
					review: $('textarea[name=review]').val(), 
					rating: $('input[name=rating]:checked').val(), 
					captcha: $('input[name=captcha]').val()};
		$.ajax({
			type:"POST",
			data: data,
			url: url,
			dataType:"json",
			success: function(response)	{
				if (response.type == 1) {
					$('#name_id').addClass('error').html(response.error_name);
					$('#review_id').addClass('error').html(response.error_review);
					$('#captcha_id').addClass('error').html(response.error_captcha);
				} else {
					$('#success_id').addClass('success').html(response.success);
					$('#success_id').append(img_close);
					
					$('input[name=name]').val('');
					$('textarea[name=review]').val('');
					$('input[name=captcha]').val('');					
				}
			},
			error:function (xhr, ajaxOptions, thrownError){
				alert(xhr.status);
				alert(thrownError);
			}
		});
	});
	$('#success_id.close').bind('click', function() {
		 $('#success_id').slideUp('slow');
		 $('#success_id').remove();
		 $('#success_id').slideDown('slow');
	});
});//-->
</script>