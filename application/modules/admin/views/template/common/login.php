<div id="content">
	<div class="login-form box">
		<div class="form-top">
			<h1>
				<img src="<?php echo site_url().APPPATH;?>modules/admin/views/images/lockscreen.png" alt="" />
				<?php echo $heading_title;?>
			</h1>
		</div>
		<div class="form-content">
			<form action="<?php echo site_url();?>admin/login" id="form_login" method="post">
				<div class="login-block-img">
					<img src="<?php echo site_url().APPPATH?>modules/admin/views/images/login.png" alt="" />
				</div>
				<div class="login-block">
					<fieldset class="login_field">
					<?php if (isset($error_message)) {?>
						<div class="error login_field_row error_message">
							<?php echo $error_message;?>
						</div>
					<?php }?>
						<div class="login_field_row">
			    			<div class="row_left">
			    				<label><span class="required">*</span><?php echo $user_name; ?></label>
			    			</div>    			
			    			<div class="row_right">
			    				<input type="text" class="textbox" name="username" id="username"/>
			    			</div>					    			    			
		    			</div>
		    			<div class="login_field_row">
			    			<div class="row_left">
			    				<label><span class="required">*</span><?php echo $password; ?></label>
			    			</div>    			
			    			<div class="row_right">
			    				<input type="password" class="textbox" name="password" id="password"/>
			    			</div>
		    			</div>
		    			<div class="login_field_row field-row-alone">
			    			<div class="row_right row-right-alone">
			    				<a href="<?php echo $forgotpasswd_url; ?>"><?php echo $forgotpasswd; ?></a>
			    			</div>
		    			</div>
		    			<div class="login_field_row">
			    			<div class="row_right row-right-alone">
			    				<input type="submit" class="textbox" name="submit" id="submit" value='<?php echo $submit; ?>'/>
			    			</div>
		    			</div> 
					</fieldset>
				</div>
			</form>
		</div>
		<div class="form-bot"></div>
	</div>
 </div>
