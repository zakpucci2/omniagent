<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<div class="section-center">
	<br /><br /><br /><br /><br />
	<div id="avatarImage">
		<?php 
			if($UserCachedData['User']['profile_photo'] == '') {
				echo $this->Html->image('user_placeholder.png', array('alt' => "avatar", 'class' => 'img-circle user-placeholder')); 
			} else {
				if(file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/profile_photo/' . $UserCachedData['User']['profile_photo'])) {
					echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto150x150")  . '/' . $UserCachedData['User']['profile_photo'], array('alt' => "avatar", 'class' => 'img-circle user-placeholder'));  
				} else {
					echo $this->Html->image('user_placeholder.png', array('alt' => "avatar", 'class' => 'img-circle user-placeholder')); 
				}
			}
		?>
	</div>
	&nbsp;
	<h1><span style="font-family: 'Quicksand', sans-serif;"><span style="font-size:88px;"><?php echo __('Hello'); ?>, <span class="username"><?php echo __(((isset($UserCachedData['User']['business_name']) && !empty($UserCachedData['User']['business_name'])) ? $UserCachedData['User']['business_name'] : ((isset($UserCachedData['User']['first_name']) && !empty($UserCachedData['User']['first_name'])) ? $UserCachedData['User']['first_name'] : $UserCachedData['User']['user_name']))); ?></span></span></span></h1>
	<?php echo $this->Form->create('users', array('type' => 'POST', 'action' => 'login_unlock', 'name' => 'loginform', 'id' => 'loginform')); ?>	
	<?php echo $this->Session->flash(); ?>
	<div class="hidden-error"></div>
	<div id="UserNameDiv">
		<?php echo $this->Form->input('User.user_name', array('type' => 'hidden', 'label' => false, 'div' => false, 'placeholder' => 'Username', 'required' => true, "autocomplete" => 'off', "value" => trim($UserCachedData['User']['user_name']), 'minlength' => 3, 'maxlength' => 30)); ?>
	</div>
	<div id="PasswordDiv">
		<?php echo $this->Form->input('User.password', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => "********", "required" => true, "autocomplete" => 'off')); ?>
	</div>
	<!-- <span style="font-size:30px;" class="glyphicon glyphicon-arrow-right" ></span> -->
	<!-- <button type="submit">
		<?php // echo $this->Html->image('check-mark-512_zps0b5449bf.png', array('height' => '16px', 'width' => '16px'));  ?>
		<?php // echo __('Sign me in'); ?>
	</button> -->
	<?php echo $this->Form->end(); ?>
	<footer class="section-footer">
		<?php echo $this->Html->link('Hey! I&#39;m not <span>' . __(((isset($UserCachedData['User']['business_name']) && !empty($UserCachedData['User']['business_name'])) ? $UserCachedData['User']['business_name'] : ((isset($UserCachedData['User']['first_name']) && !empty($UserCachedData['User']['first_name'])) ? $UserCachedData['User']['first_name'] : $UserCachedData['User']['user_name']))) . '</span>', array('controller' => 'users', 'action' => 'login_clear'), array('class' => 'btn', 'escape' => false)); ?><br />
		<?php echo $this->Html->link('I&#39;ve lost my password', array('controller' => 'users', 'action' => 'forgotpassword'), array('class' => 'link-lostpass', 'escape' => false)); ?><br />
	</footer>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".hidden-error").removeClass('incorrect');
		$(".hidden-error").html('');
		$(".hidden-error").hide();
		
		$('#UserPassword').keyup(function() {
			$(".hidden-error").removeClass('incorrect');
			$(".incorrect").html('');
			$(".incorrect").hide();
			if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null && $('#UserPassword').val().length >= 6) {
				$.ajax({
					url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'ajaxValidateUsernamePassword', 'admin' => false)); ?>',
					type: 'post',
					dataType: 'json',
					data: {userName: $('#UserUserName').val(), userPass: $('#UserPassword').val()},
					success: function(data) {
						if (data.status == false) {
							$(".hidden-error").addClass('incorrect');
							$(".hidden-error").html('<h2>'+ data.errormessage + '</h2>');
							$(".hidden-error").show();
						}
						if (data.status == true) {
							$("#loginform").submit();
						}
						return false;
					}
				});
			}
		});
		$("#loginform").validate({
			errorElement: "div"
		});
	});
</script>