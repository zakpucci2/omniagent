<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<div class="section-center">
	<br /><br /><br /><br /><br />
	<div id="avatarImage">
		<?php echo $this->Html->image('user_placeholder.png', array('class' => 'img-circle user-placeholder')); ?>
	</div>
	&nbsp;
	<h1><span style="font-family: 'Quicksand', sans-serif;"><span style="font-size:88px;"><?php echo __('Login'); ?></span></span></h1>
	<?php echo $this->Form->create('users', array('type' => 'POST', 'action' => 'login', 'name' => 'loginform', 'id' => 'loginform')); ?>	
	<?php echo $this->Session->flash(); ?>
	<div class="hidden-error"></div>
	<div id="UserNameDiv">
		<?php echo $this->Form->input('User.user_name', array('type' => 'text', 'label' => false, 'div' => false, 'placeholder' => 'Username', 'required' => true, "autocomplete" => 'off', 'minlength' => 3, 'maxlength' => 30)); ?>
	</div>
	<div id="PasswordDiv">
		<?php echo $this->Form->input('User.password', array('type' => 'password', 'label' => false, 'div' => false, 'placeholder' => "********", "required" => true, "autocomplete" => 'off')); ?>
	</div>		
	<!-- <span style="font-size:30px;" class="glyphicon glyphicon-arrow-right" ></span> -->
	<!-- <button type="submit">
		<?php // echo $this->Html->image('check-mark-512_zps0b5449bf.png', array('height' => '16px', 'width' => '16px')); ?>
		<?php // echo __('Sign me in'); ?>
	</button> -->
	<?php echo $this->Form->end(); ?>
	<footer class="section-footer">
		<?php echo $this->Html->link('I&#39;ve lost my password', array('controller' => 'users', 'action' => 'forgotpassword'), array('class' => 'link-lostpass', 'escape' => false)); ?><br />
	</footer>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".hidden-error").removeClass('incorrect');
		$(".hidden-error").html('');
		$(".hidden-error").hide();
		
		$('#UserUserName').on('blur', function() {
			$(".hidden-error").removeClass('incorrect');
			$(".incorrect").html('');
			$(".incorrect").hide();	
			if ($('#UserUserName').val() != "" && $('#UserUserName').val() != 'undefined' && $('#UserUserName').val() != null) {
				var postData = {};
				postData.userName = $('#UserUserName').val();
				if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null) {
					postData.userPass = $('#UserPassword').val();
				}
				$.ajax({
					url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'ajaxValidateUsername', 'admin' => false)); ?>',
					type: 'post',
					dataType: 'json',
					data: postData,
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
							if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null) { 
								$("#loginform").submit();
							}
						} else {
							$("#avatarImage").html('<img src="<?php echo $this->webroot . 'img/'; ?>user_placeholder.png" class="img-circle user-placeholder" alt="Avatar">');
							if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null) { 
								$("#loginform").submit();
							}
						}
						return false;
					}
				});
			}
		});
		$('#UserPassword').keyup(function() {
			$(".hidden-error").removeClass('incorrect');
			$(".incorrect").html('');
			$(".incorrect").hide();		
			if ($('#UserUserName').val() != "" && $('#UserUserName').val() != 'undefined' && $('#UserUserName').val() != null) {
				var postData = {};
				postData.userName = $('#UserUserName').val();
				if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null && $('#UserPassword').val().length >= 6) {
					postData.userPass = $('#UserPassword').val();
					$.ajax({
						url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'ajaxValidateUsername', 'admin' => false)); ?>',
						type: 'post',
						dataType: 'json',
						data: postData,
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
								if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null) { 
									$("#loginform").submit();
								}
							} else {
								$("#avatarImage").html('<img src="<?php echo $this->webroot . 'img/'; ?>user_placeholder.png" class="img-circle user-placeholder" alt="Avatar">');
								if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null) { 
									$("#loginform").submit();
								}
							}
							return false;
						}
					});
				}
			}
			if ($('#UserPassword').val() != "" && $('#UserPassword').val() != 'undefined' && $('#UserPassword').val() != null && $('#UserPassword').val().length >= 6) {
				var postData = {};
				postData.userName = $('#UserUserName').val();
				postData.userPass = $('#UserPassword').val();
				$.ajax({
					url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'ajaxValidateUsernamePassword', 'admin' => false)); ?>',
					type: 'post',
					dataType: 'json',
					data: postData,
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