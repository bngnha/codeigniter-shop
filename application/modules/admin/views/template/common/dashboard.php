<div id="content">
	<div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
	    	<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
	    <?php } ?>
     </div>
     <div class="box">
		<div class="heading">
	      <h1><img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/home.png"> Dashboard</h1>
	    </div>
	    <div class="content">
			<div class="cpanel-left">
				<div class="cpanel">
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_category; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-category.png" />
								<span>Category Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-product.png"/>
								<span>Product Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_news; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-article-add.png"/>
								<span>Article Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_information; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-information.png" />
								<span>Information Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_manufacturer; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-manufacturer.png" />
								<span>Manufacturer Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_review; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon_preview.png" />
								<span>Reviews Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_faq; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-fqa.png" />
								<span>FQA Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_setting; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-config.png" />
								<span>Global Configuration</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_user; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-user.png" />
								<span>User Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_usergrp; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-groups.png" />
								<span>User group Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_country; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-country.png" />
								<span>Country Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_language; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-language.png" />
								<span>Language Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_currency; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-currency.png" />
								<span>Currency Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_stock; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-stock_status.png" />
								<span>Stocks Status Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_zone; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-country_zones.png" />
								<span>Country Zones Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_help; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-help_header.png" />
								<span>Helps</span>
							</a>
						</div>
					</div>
						

				<!-- 
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_module; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-module.png" />
								<span>Module Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_extension; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-extension.png" />
								<span>Extension Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_template; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-themes.png" />
								<span>Template Manager</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_update; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-jupdate-uptodate.png" />
								<span>Update to</span>
							</a>
						</div>
					</div>
					
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_extension; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-plugin.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-banner.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-contacts-categories.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
					
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-massmail.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-contacts.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-links-cat.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-levels.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
					<div class="icon-wrapper">
						<div class="icon">
							<a href="<?php echo $link_product; ?>">
								<img alt="" src="<?php echo site_url().APPPATH?>modules/admin/views/images/icon-clear.png" />
								<span>Unknown extensions<br>update status</span>
							</a>
						</div>
					</div>
				 -->
				</div>
			</div>
		</div>
     </div>
</div>