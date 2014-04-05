<center>
<form method="post" action="<?php echo site_url(); ?>admin/login">
	<table cellpadding="3">
		<?php
		if($_GET['error']){
			?>
			<tr>
				<td class='center bold font14 red' colspan="2"><?php echo $_GET['error']; ?></td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td class='center bold font18' colspan="2">Log In</td>
		</tr>
		<tr>
			<td class='font14'>Login Name: </td>
			<td><input class='font14' type='text' id='login_email' name='login_email' value="<?php echo htmlentities($_GET['login_email']); ?>"></td>
		</tr>
		<tr>
			<td class='font14'>Password:</td>
			<td><input class='font14' type='password' id='password' name='password'></td>
		</tr>
		<tr>
			<td class='center' colspan="2">
			<input class='font14' type='submit' value='Log In' >
			</td>
		</tr>
	</table>
</form>
</center>