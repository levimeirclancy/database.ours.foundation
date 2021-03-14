<?
	// This is the popover for settings ...
	echo "<amp-lightbox id='settings-popover' on='lightboxOpen:login-popover.close,navigation-sidebar.close,new-popover.close' layout='nodisplay'>";

		echo "<span role='button' tabindex='0' on='tap:".$navigation_lightboxes."' class='sidebar-back'>Close</span>";

		echo "<p>Settings coming soon: password change, account management.</p>";
	
		echo "<label>Enter new e-mail address</label>";
		echo "<input name='checkpoint_newemail'>";

		echo "<label>Enter new password</label>";
		echo "<input name='checkpoint_newpassword'>";
		echo "<input name='checkpoint_newpassword'>";
	
		echo "</amp-lightbox>";
?>
