<div class="container width fl border-bottom">
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
	<div class="container width fl ">
		<div id="column-left">
	<?php if ($categories) { ?>
			<ul>
			    <?php $zid = 2000; 
			    foreach ($categories as $category) { ?>
			    <li>
		    		<a href="<?php echo $category['href']; ?>"><span><?php echo $category['name']; ?></span></a>
		      		<?php if ($category['children']) { ?>
				        <?php for ($i = 0; $i < count($category['children']);) { ?>
					        <ul>
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
		<div id="content">
			<form id="contact" enctype="multipart/form-data" method="post" action="<?php echo $action; ?>">
				<h3>Thông tin của chúng tôi</h3>
				<div class="contact-info">
					<div class="content">
						<div class="left">
							<b>Địa chỉ:</b><br><?php echo $config_name; ?><br><?php echo $config_address; ?>
						</div>
						<div class="center">
							<b>Email:</b><br><?php echo $config_email; ?><br><br>
						</div>
						<div class="right">
							<b>Điện thoại:</b><br><?php echo $config_telephone; ?><br><br>
						</div>
					</div>
				</div>
				<h3>Thông tin liên hệ</h3>
				<div class="content">
					<b>Họ tên:</b><br>
						<input type="text" value="<?php if (isset($field_name)) { echo $field_name; } ?>" name="name"><br>
					<?php if ($error_name) { ?>
						<span class="error"><?php echo $error_name; ?></span>
					<?php } ?>
					<b>E-Mail:</b><br>
						<input type="text" value="<?php if (isset($field_email)) { echo $field_email; } ?>" name="email"><br>
					<?php if ($error_email) { ?>
						<span class="error"><?php echo $error_email; ?></span>
					<?php } ?>
					<b>Tiêu đề:</b><br>
						<input type="text" value="<?php if (isset($field_title)) { echo $field_title; } ?>" name="title"><br>
					<?php if ($error_title) { ?>
						<span class="error"><?php echo $error_title; ?></span>
					<?php } ?>
					<b>Nội dung:</b><br>
						<textarea style="width: 99%;" rows="6" cols="40" name="enquiry"><?php if (isset($field_enquiry)) { echo $field_enquiry; } ?></textarea><br>
					<?php if ($error_content) { ?>
						<span class="error"><?php echo $error_content; ?></span>
					<?php } ?>
					<br/>
					<b>Nhập các ký tự bên dưới:</b><br>
						<input type="text" value="" name="captcha"><br>
					<?php if ($error_captcha) { ?>
						<span class="error"><?php echo $error_captcha; ?></span>
					<?php } ?>
						<?php echo $captcha; ?>
				</div>
				<div class="buttons">
					<div class="right">
						<a class="button" onclick="$('#contact').submit();">
							<span>Tiếp tục</span>
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
<!--
$(document).ready(function() {
	$('#slideshow').nivoSlider();
});
-->
</script>