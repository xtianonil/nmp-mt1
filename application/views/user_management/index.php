<?php 
$this->js = <<<JS
$('.deletebutton').click(function(event) {
	return confirm( 'Are you sure you want to delete this user?');
});
JS;
?>

<div class="formbox">
	<div class="form_filter">
		<form method="get" action="<?php echo site_url('user_management/index')?>">
			<span class="filterby">User <?php echo ui_input('username', $filters['username'], null)?></span>
			<span class="filterby">Name <?php echo ui_input('fullname', $filters['fullname'], null)?></span>
			<span class="filterby">User Type <?php echo ui_dropdown('u_type_id', $user_types, $filters['u_type_id'], null)?></span>
			<button type="submit" class="filter_button">  Filter</button>
		</form>
	</div>
	
	<br/>

	<!--pagination-->
	<?php echo make_pagination('user_management', $page['current'], $page['total'], $page['per'], $filters); ?>

	<br/>

	<?php echo ui_linkbutton('Add New User', 'user_management/adduser', 'btn-custom1_default2') ?>

	<table class="datatable sortable tablelayout_unfix table-striped">
		<thead>
			<tr>
				<th>User Name</th>
				<th>Full Name</th>
				<th>Email</th>
				<th>User Type</th>
				<th class="nosort">Action</th>
			</tr>
		</thead>                                                                             

		<tbody>
			<?php if (empty($userlist)){ ?>
				<tr><td><a style="color:red">No results found.</a></td></tr>
			<?php }else{ ?>
				<?php foreach ($userlist as $row){ ?>
				<tr>
				<td><?php echo $row['username'] ?></td>
				<td><?php echo $row['fullname'] ?></td>
				<td><?php echo $row['email'] ?></td>
				<td><?php if($row['u_type_id'] == 1) echo 'admin'; else echo 'user'; ?></td>
				<td class="actionrow">
					<?php echo ui_linkbutton('Edit', 'user_management/change_details/'.$row['u_id'], 'btn-custom1_default2'); ?>
					<?php echo ui_linkbutton('Delete', 'user_management/delete_user/'.$row['u_id'], 'btn-custom1_default2 deletebutton'); ?>
				</td>
			<?php } ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
