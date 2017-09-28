<ul>
  <li class="left <?php echo isset($name["username"]) ? 'red' : ''; ?>">Usernames are unique and thus cannot be repeated for multiple administrators</li>
  <li class="left <?php echo count($max) || count($min) > 0 ? 'red' : '';?> ">Usernames and Passwords must be between 6 and 20 characters</li>
  <li class="left <?php echo count($spaces) ? 'red' : '';?>">Usernames and Passwords cannot contain any spaces</li>
  <li class="left <?php echo count($numbers) === 0 ? 'red' : ''?>">Passwords must contain at least one number</li>
  <li class="left <?php echo count($special_chars) === 0 ? 'red' : ''?>">Passwords must contain at least one of the following special characters:  !, @, #, $, %, *, ~</li>
  <li class="left">Type "Admin" will allow the administrator to add, edit, and delete recipes</li>
  <li class="left">Type "Super Admin" will allow the same rights as type "Admin", and in addition allow
					the administrator to add, edit, and delete the rights of other administrators</li>
</ul>
<div class="red"><?php echo $error_warning;?></div>
<br />
<div>
<div class="center">
  <form method="POST">
	<table>
	  <tr>
		<td class="right">Username:  </td>
		<td class="left"><input type="text" name="username" value="<?php echo htmlentities($username); ?>"></input>
	  </tr>
	  <tr>
		<td class="right">Password:  </td>
		<td class="left"><input type="password" name="password"></input>
	  </tr>
	  <tr>
	    <td class="right">Type:  </td>
		<td class="left">
		  <select name="type">
		    <option value="admin" <?php echo $type == 'admin' ? ' selected' : ''; ?>>Admin</option>
		    <option value="superadmin" <?php echo $type == 'superadmin' ? ' selected' : ''; ?>>Super Admin</option>
		  </select>
		</td>
	  </tr>
	</table>
	</div>
	<br /><br />
	<div>
	  <input type="submit" name="submit" value="Submit" class="button"></input>
	  <a href="admins.php" class="buttonLink">Cancel</a>
</div>
</div>
</form>
</div>		

<?php
include_once("../private/layout/footer.php");
?>