
<div class="formbox">
	<div style="margin-left: 50px; margin-bottom: 50px;">
		<h3>Edit User Details</h3>
		<?php make_kvform(array('Username:'=>ui_input_readonly($userinfo['username'], (!empty($val_errors['username']) ? $val_errors['username'] : null)),
								'Name:'=>ui_input('fullname', $userinfo['fullname'], (!empty($val_errors['fullname']) ? $val_errors['fullname'] : null)),
								'Email:'=>ui_input('email', $userinfo['email'], (!empty($val_errors['email']) ? $val_errors['email'] : null)),
								'User Type:'=>ui_dropdown('u_type_id', $user_types, $userinfo['u_type_id'], (!empty($val_errors['u_type_id']) ? $val_errors['u_type_id'] : null))
								), 
						  'user_management/change_details/'.$u_id,
						  array(ui_button_update(), 
						  	  	ui_button_cancel('user_management')
						  )
		); 
		?>


		<br/>
		<br/>
		<h3>Edit password</h3>
		<?php make_kvform(array('Password:' => ui_input_password('password',(!empty($val_errors['password']) ? $val_errors['password'] : null))),
						  'user_management/change_userpass/'.$u_id,
						  array(ui_button_update(), 
						  	  	ui_button_cancel('user_management')
						  )
		);
		?>
	</div>
</div>

