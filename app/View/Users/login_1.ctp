<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>
	.error {font-weight: 700;}
</style>
<div class="page-container">
	<center>
		<h1><?php echo __('Login'); ?></h1>
		<?php echo $this->Form->create('users', array('type' => 'POST', 'action' => 'login', 'name' => 'loginform', 'id' => 'loginform')); ?>	
		<?php echo $this->Session->flash(); ?>
		<?php echo $this->Form->input('User.user_name', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => "Username")); ?>
		<?php echo $this->Form->input('User.password', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => "Password")); ?>
		<br><font size="-2"><?php echo $this->Html->link('Forgot password?', array('controller' => 'users', 'action' => 'forgotpassword')); ?></font>
		<button type="submit">
			<?php echo $this->Html->image('check-mark-512_zps0b5449bf.png', array('height' => '16px', 'width' => '16px'));  ?>
			 <?php echo __('Sign me in'); ?>
		</button>
		<!-- <div class="error"><span>+</span></div>	 -->			
		<?php echo $this->Form->end(); ?>
	</center>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#loginform").validate();	
	});
</script>