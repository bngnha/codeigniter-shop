<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="<?php echo ADMIN_HTTP_RESOURCE;?>styles/stylesheet.css?v=<?php echo VERSION;?>" type="text/css" rel="stylesheet"/>
<link href="<?php echo ADMIN_HTTP_RESOURCE;?>/styles/content.css?v=<?php echo VERSION;?>" type="text/css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/jquery-1.7.2.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<!--[if IE]>
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/fancybox/jquery.fancybox-1.3.4-iefix.js"></script>
<![endif]--> 
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/jquery.validate.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_HTTP_RESOURCE;?>javascripts/jquery/superfish/js/superfish.js"></script>
    <title><?php echo $title;?></title>
</head>
<body>
    <div id="container">
        <div id="header">
        <?php 
            if (isset($loggedin_flg))
            {
                if (file_exists(APPPATH."modules/admin/views/template/common/banner".EXT))
                { 
                    include_once(APPPATH."modules/admin/views/template/common/banner".EXT);  
                }
                if (file_exists(APPPATH."modules/admin/views/template/common/menu".EXT))
                {
                    include_once(APPPATH."modules/admin/views/template/common/menu".EXT);
                }
            }
            else
            {
            	if (file_exists(APPPATH."modules/admin/views/template/common/banner".EXT))
                { 
                    include_once(APPPATH."modules/admin/views/template/common/banner".EXT);  
                }
            }
        ?>
        </div>
