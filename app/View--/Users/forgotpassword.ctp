<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<div class="section-center">
	<br /><br /><br /><br /><br />
	<div id="avatarImage">
		<?php echo $this->Html->image('user_placeholder.png', array('class' => 'img-circle user-placeholder'));  ?>
	</div>
	&nbsp;
	<h1><span style="font-family: 'Quicksand', sans-serif;"><span style="font-size:88px;"><?php echo __('Recover'); ?></span></span></h1>
	<?php echo $this->Form->create('users', array('type' => 'POST', 'action' => 'forgotpassword', 'name' => 'forgotPasswordForm', 'id' => 'forgotPasswordForm')); ?>
	<?php echo $this->Session->flash(); ?>
	<div class="hidden-error"></div>
	<div id="UserNameDiv">
		<?php echo $this->Form->input('User.user_name', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => 'Username', 'required' => true, "autocomplete" => 'off', 'minlength' => 3, 'maxlength' => 30)); ?>
	</div>
	<div id="UserEmailDiv">
		<?php echo $this->Form->input('User.email', array('type' => 'email', 'label' => false, 'div' => false, 'placeholder' => "Email", "required" => true, "autocomplete" => 'off')); ?>
	</div>
	<!-- <span style="font-size:30px;" class="glyphicon glyphicon-arrow-right" ></span> -->
	<!-- <button type="submit">
		<?php // echo $this->Html->image('check-mark-512_zps0b5449bf.png', array('height' => '16px', 'width' => '16px'));  ?>
		<?php // echo __('Recover'); ?>
	</button> -->
	<?php echo $this->Form->end(); ?>
	<footer class="section-footer">
		<?php echo $this->Html->link(__('Back to login'), Configure::read('FULL_BASE_URL.URL') , array('class' => 'link-lostpass', 'escape' => false)); ?>
	</footer>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#UserUserName').on('blur', function() {
			if ($('#UserUserName').val() != "" && $('#UserUserName').val() != 'undefined' && $('#UserUserName').val() != null) {
				$.ajax({
					url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'ajaxValidateUsername', 'admin' => false)); ?>',
					type: 'post',
					dataType: 'json',
					data: {userName: $('#UserUserName').val()},
					success: function(data) {
						if (data.status == false) {
							$("#UserUserName").addClass('error');
							$("#UserUserName").removeClass('valid');
							if($('div[for="UserUserName"]').length == 0) {
								$('<div for="UserUserName" class="error">' + data.errormessage + '</div>').appendTo('#UserNameDiv');
								$('div[for="UserUserName"]').show();
							} else {
								$('div[for="UserUserName"]').html(data.errormessage);
								$('div[for="UserUserName"]').show();
							}
						}
						if (data.status == true && data.User.profile_photo != '') {
							$("#avatarImage").html('<img src="<?php echo $this->webroot . 'img/' . Configure::read("IMAGES_SIZES_DIR.ProfilePhoto150x150") . '/'; ?>' + data.User.profile_photo + '" class="img-circle user-placeholder" alt="Avatar">');
						} else {
							$("#avatarImage").html('<img src="<?php echo $this->webroot . 'img/'; ?>user_placeholder.png" class="img-circle user-placeholder" alt="Avatar">');
						}
						return false;
					}
				});
			}
		});
		$('#UserEmail').keyup(function() {
			if ($('#UserEmail').val() != "" && $('#UserEmail').val() != 'undefined' && $('#UserEmail').val() != null && validateEmail($('#UserEmail').val()) === true) {
				$.ajax({
					url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'ajaxValidateUsernameEmail', 'admin' => false)); ?>',
					type: 'post',
					dataType: 'json',
					data: {userName: $('#UserUserName').val(), userEmail: $('#UserEmail').val()},
					success: function(data) {
						if (data.status == false) {
							$("#UserEmail").addClass('error');
							$("#UserEmail").removeClass('valid');
							if($('div[for="UserEmail"]').length == 0) {
								$('<div for="UserEmail" class="error">' + data.errormessage + '</div>').appendTo('#UserNameDiv');
								$('div[for="UserEmail"]').show();
							} else {
								$('div[for="UserEmail"]').html(data.errormessage);
								$('div[for="UserEmail"]').show();
							}
						}
						if (data.status == true) {
							$("#forgotPasswordForm").submit();
						}
						return false;
					}
				});
			}
		});
		$("#forgotPasswordForm").validate({
			errorElement: "div"
		});
		
		function validateEmail(email) {
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if (!emailReg.test(email)) {
				return false;
			} else {
				return true;
			}
		}
	});
</script>