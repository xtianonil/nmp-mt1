<div class="formbox">
	<div style="margin-left: 50px; margin-bottom: 50px;">
		<h3>Add user</h3>
		
		<br/>
		
		<?php make_kvform(array('Username'=>ui_input('username',  $userinfo['username'], (!empty($val_errors['username']) ? $val_errors['username'] : null)),
								'Password'=>ui_input_password('password', (!empty($val_errors['password']) ? $val_errors['password'] : null)),
								'Name'=>ui_input('fullname', $userinfo['fullname'], (!empty($val_errors['fullname']) ? $val_errors['fullname'] : null)),
								'Email'=>ui_input('email', $userinfo['email'], (!empty($val_errors['email']) ? $val_errors['email'] : null)),
								'User Type'=>ui_dropdown('u_type_id', $user_types, $userinfo['u_type_id'], (!empty($val_errors['u_type_id']) ? $val_errors['u_type_id'] : null))
								), 
						  'user_management/adduser/',
						  array(ui_button_add(), 
						  	  	ui_button_cancel('user_management')
						  )
						); 
		?>    
	</div>             
</div>

<script>
	$(document).ready(function(){
		//workaround for Chrome autofill
		$('form').attr('autocomplete', 'off');
		$('input[name=username]').attr('autocomplete', 'off');
		if ($('input[name=username]').val().length == 0) $('input[name=username]').val(' ');
		$('input[name=username]').focus(function(){
			var trimmed = $(this).val().trim();
			$(this).val(trimmed);
		});
	});
</script>                   
