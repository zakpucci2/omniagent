<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>
	.error {font-weight: 700;}
</style>
<div class="page-container">
	<center>
		<h1><?php echo __('Forgot Password ?'); ?></h1>
		<font size="-2">Fill out the form below. Keep an eye on your inbox for your new password.</font>
		<?php echo $this->Form->create('users', array('type' => 'POST', 'action' => 'forgotpassword', 'name' => 'forgotPasswordForm', 'id' => 'forgotPasswordForm')); ?>
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->Form->input('User.user_name', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => "Username")); ?>
		<?php echo $this->Form->input('User.email', array('type' => 'email', 'label' => false, 'div' => false, 'placeholder' => "Email")); ?>
		<button type="submit">
			<?php echo $this->Html->image('check-mark-512_zps0b5449bf.png', array('height' => '16px', 'width' => '16px'));  ?>
			 <?php echo __('Recover'); ?>
		</button>
		<?php echo $this->Form->end(); ?>
	</center>
	<br />
	<br />
	<center>
		<h2>
			<font color="white">
				<a href="<?php echo Configure::read('FULL_BASE_URL.URL'); ?>"><strong>Back to login</strong></a>
			</font>
		</h2>
	</center>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#forgotPasswordForm").validate();	
	});
</script>