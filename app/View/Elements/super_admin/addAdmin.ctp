<?php //$this->Html->css('bootstrap/form_validaion',null, array('inline' => false));   ?>
<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>.star {color:red;}</style>
<div class="modal fade" id="AddAdminModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-focus-on="#UserFirstName">
	<div class="modal-dialog">
		<?php echo $this->Form->create('users', array('type' => 'file', 'action' => 'addadmin', 'name' => 'addNewAdmin', 'id' => 'addNewAdmin', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Administrator</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Trusted Admin<span class="star">*</span></span>            
						<label class="checkbox-inline">
						  <?php echo $this->Form->checkbox('User.is_trusted_admin', array('type' => 'checkbox', 'id' => 'AddUserIsTrustedAdmin', 'div' => false, 'label' => false, 'hiddenField' => false, 'value' => 1)); ?>
						</label>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">First Name<span class="star">*</span></span>            
						<?php echo $this->Form->input('User.first_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'First Name')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Last Name<span class="star">*</span></span>            
						<?php echo $this->Form->input('User.last_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Last Name')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">User Name<span class="star">*</span></span>            
						<?php echo $this->Form->input('User.user_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Please enter username', 'remote' => $this->Html->url(array('controller' => 'Users', 'action' => 'ajax_check_username', 'superadmin' => false)), 'loginRegex' => true, 'minlength' => '2', 'maxlength' => '30')); ?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Email<span class="star">*</span></span>            
						<?php echo $this->Form->input('User.email', array('type' => 'email', 'label' => false, 'div' => false, 'maxlength' => 100, 'required' => true, 'email' => true, 'class' => 'form-control', 'placeholder' => 'Example : example@example.com', 'remote' => $this->Html->url(array('controller' => 'Users', 'action' => 'ajax_check_email', 'superadmin' => false)), 'value' => null)); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Password<span class="star">*</span></span>            
						<?php echo $this->Form->input('User.password', array('type' => 'password', 'id' => 'AddUserPassword', 'label' => false, 'div' => false, 'required' => true, 'class' => 'form-control', 'minlength' => '6', 'maxlength' => 50)); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Confirm Password<span class="star">*</span></span>            
						<?php echo $this->Form->input('User.cpassword', array('type' => 'password', 'id' => 'AddUserCpassword', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'equalTo' => '#AddUserPassword')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Phone</span>            
						<?php echo $this->Form->input('User.phone', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 20, 'required' => false, 'class' => 'form-control', 'placeholder' => 'Example : +1-646-222-9999', 'number' => true)); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Address1</span>            
						<?php echo $this->Form->input('User.address_line1', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Address Line 1')); ?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Address2</span>       
						<?php echo $this->Form->input('User.address_line2', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 100, 'class' => 'form-control', 'placeholder' => 'Address Line 2')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Avatar</span>
						<?php echo $this->Form->input('User.profile_avatar', array('type' => 'file', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'form-control', 'placeholder' => 'Please upload profile avatar.')); ?> 
					</div><span style="text-align:right">Avatar should be 125x125 in .png format.</span><br />
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
						<button type="submit" id="singlebutton" class="btn btn-primary">Save</button>
					</div>
				</center>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
		}, "Username must contain only letters, numbers, or dashes.");
		$("#addNewAdmin").validate();
	});
</script>