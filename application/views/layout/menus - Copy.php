<?php
$controller = $this->router->class;
$method = $this->router->method;
?>
<ul class='menu'>
	<?php
	//if($this->user_validation->validate("users", "%", false)){
	if($this->user_validation->validate("users", "index", false)){
		?>
		<li <?php if($controller=="users"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("users");?>"'>
			<a href='<?php echo site_url("users");?>'>Users</a>
		</li>
		<?php
	}
	if($this->user_validation->validate("user_permissions", "index", false)){
		?>
		<li <?php if($controller=="user_permissions"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("user_permissions");?>"'>
			<a href='<?php echo site_url("user_permissions");?>'>User Groups and Permissions</a>
		</li>
		<?php
	}
	if($this->user_validation->validate("admin", "createcms", false)){
		?>
		<li <?php if($controller=="admin"&&$method=="createcms"){ echo "class='selected'"; } ?> onclick='self.location="<?php echo site_url("admin/createcms");?>"'>
			<a href='<?php echo site_url("admin/createcms");?>'>Create CMS</a>
		</li>
		<?php
	}
	/*[[MENU]]*/
	?>
	
</ul>