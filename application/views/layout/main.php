<?php
@session_start();
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>CCRO Portal</title>
	<script language="javascript" src="<?php echo site_url("media/js/jquery-1.7.2.min.js"); ?>"></script>
	<link rel="stylesheet" href="<?php echo site_url("media/js/development-bundle/themes/base/jquery.ui.all.css"); ?>">
	<script src="<?php echo site_url("media/js/development-bundle/jquery-1.8.0.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.core.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.widget.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.position.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.autocomplete.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.mouse.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.draggable.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.position.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.resizable.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.dialog.js"); ?>"></script>
	<script src="<?php echo site_url("media/js/development-bundle/ui/jquery.ui.datepicker.js"); ?>"></script>

	<script type="text/javascript" src="<?php echo site_url("media/js/jquery.alerts-1.1/jquery.alerts.js"); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("media/js/jquery.alerts-1.1/jquery.alerts.css"); ?>" media="screen" />
	<script type="text/javascript" src="<?php echo site_url("media/js/uploadify/swfobject.js"); ?>"></script>
	<script type="text/javascript" src="<?php echo site_url("media/js/uploadify/jquery.uploadify.v2.1.4.min.js"); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("media/js/uploadify/uploadify.css"); ?>" media="screen" />
	
	<script type="text/javascript" src="<?php echo site_url("media/custom.js?_=".time()); ?>"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo site_url("media/custom.css?_=".time()); ?>" media="screen" />
	
</head>
<body>
<div id='imagepreload' class='hidden'>
	<img src='<?php echo site_url("media/check.png"); ?>' />
	<img src='<?php echo site_url("media/x.png"); ?>' />
	<img src='<?php echo site_url("media/new.png"); ?>' />
	<img src='<?php echo site_url("media/ajax-loader.gif"); ?>' />
</div>
<div id="dialog" title="">
    <div id='dialoghtml'></div>
</div>

<div id="container">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td id='header'>
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td style='padding:10px;'>
						<a style='color:white; text-decoration:none; font-size:18px;' href="<?php echo site_url(); ?>">CCRO Portal</a>
						</td>
						<?php
						if($user){ //if logged in
							?>
							<td class='right'>
							<a href='<?php echo site_url("users/edit/".$user['id']); ?>'>[ <?php echo strtoupper($user['name']); ?> ]</a>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<a href='<?php echo site_url("admin/logout"); ?>'>Log Out</a></td>
							<?php
						}
						?>
					</tr>
				</table>	
		</tr>
		<tr>
			<td id='menus'>
				<?php
				if($user){ //if logged in
					$this->load->view("layout/menus");
				}
				?>
			</td>
		</tr>
		<tr>
			<td id='content'>
				<?php
				if($content&&$user){
					echo $content;
				}
				else if(!$user){ //if not logged in
					$this->load->view("layout/login");
				}
				else if($createcms){
					$this->load->view("layout/createcms");
				}
				?>
			</td>
		</tr>
	</table>
	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>