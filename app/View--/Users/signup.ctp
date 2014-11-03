<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<div class="section-center">
	<br /><br /><br /><br /><br />
	<?php echo $this->Html->image('user_placeholder.png', array('class' => 'img-circle user-placeholder'));  ?>
	&nbsp;
	<h1><span style="font-family: 'Quicksand', sans-serif;"><span style="font-size:88px;"><?php echo __('Sign Up'); ?></span></span></h1>
	<?php echo $this->Form->create('users', array('type' => 'POST', 'action' => 'signup/' . $uniqueKey, 'name' => 'addNewClient', 'id' => 'addNewClient', 'role' => 'form')); ?>
	<?php echo $this->Session->flash(); ?>
	<?php echo $this->Form->input('User.first_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'placeholder' => "First Name", 'value' => trim($userInfo['Invitation']['first_name']))); ?><br />
	<?php echo $this->Form->input('User.last_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'placeholder' => "Last Name", 'value' => trim($userInfo['Invitation']['last_name']))); ?><br />
	<?php echo $this->Form->input('User.email', array('type' => 'email', 'label' => false, 'div' => false, 'readonly' => true, 'maxlength' => 200, 'required' => true, 'email' => true, 'placeholder' => 'Example : example@example.com', 'remote' => $this->Html->url(array('controller' => 'users', 'action' => 'ajax_check_email', 'superadmin' => false)), 'value' => trim($userInfo['Invitation']['email']))); ?><br />
	<?php echo $this->Form->input('User.user_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'placeholder' => 'Choose a username', 'autocomplete' => 'off', 'remote' => $this->Html->url(array('controller' => 'users', 'action' => 'ajax_check_username', 'superadmin' => false)), 'loginRegex' => true, 'minlength' => '2', 'maxlength' => '30')); ?><br />
	<?php echo $this->Form->input('User.password', array('type' => 'password', 'id' => 'AddUserPassword', 'label' => false, 'div' => false, 'required' => true, 'minlength' => '6', 'maxlength' => 50, 'placeholder' => 'Choose a password')); ?><br />
	<!-- <span style="font-size:30px;" class="glyphicon glyphicon-arrow-right" ></span> -->
	<button type="submit">
		<?php echo $this->Html->image('check-mark-512_zps0b5449bf.png', array('height' => '16px', 'width' => '16px'));  ?>
		<?php echo __('Sign me up'); ?>
	</button>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
		}, "Username must contain only letters, numbers, or dashes.");
		$("#addNewClient").validate({
			errorElement: "div"
		});
	});
</script>
