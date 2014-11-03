<?php $this->Html->addCrumb('Edit Profile', array('controller' => 'users', 'action' => 'editprofile')); ?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon picture"></i><span class="break"></span>Edit Profile</h2>
			<div class="box-icon">
				<a href="#" id="toggle-fullscreen" class="hidden-phone hidden-tablet"><i class="halflings-icon fullscreen"></i></a>
				<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<div class="well">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#home" data-toggle="tab">Profile</a></li>
					<li><a href="#profile" data-toggle="tab">Password</a></li>
					<li><a href="#payments" data-toggle="tab">Payments</a></li>
					<li><a href="#about" data-toggle="tab">About Me</a></li>
					<li><a href="#social" data-toggle="tab">Social</a></li>
					<li><a href="#offers" data-toggle="tab">Offers</a></li>
				</ul>
				<div id="myTabContent" class="tab-content">
					<div class="tab-pane active in" id="home">
						<?php echo $this->Form->create('users', array('type' => 'post', 'action' => 'editprofile/', 'name' => 'UserEditProfile', 'id' => 'UserEditProfile', 'role' => 'form')); ?>
							<label><i class=" halflings-icon user"></i> Display Name <i class="halflings-icon info-sign noty" data-noty-options='{"text":"<strong>Notice:</strong> This is how you will be identified across all OmniHustle platforms. Choose your name wisely.","layout":"topRight","type":"success"}'></i></label>
							<?php echo $this->Form->input('User.business_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 100, 'class' => 'input-xlarge')); ?>
							<label><i class=" halflings-icon asterisk"></i> First Name</label>
							<?php echo $this->Form->input('User.first_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'class' => 'input-xlarge', 'required' => true)); ?>
							<label><i class=" halflings-icon asterisk"></i> Last Name</label>
							<?php echo $this->Form->input('User.last_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'class' => 'input-xlarge', 'required' => true)); ?>
							<label><i class=" halflings-icon envelope"></i> Email</label>
							<?php echo $this->Form->input('User.email', array('type' => 'email', 'label' => false, 'div' => false, 'maxlength' => 200, 'email' => true, 'remote' => $this->Html->url(array('controller' => 'users', 'action' => 'ajax_check_email/' . $this->Session->read('User.User.id'), 'superadmin' => false)), 'class' => 'input-xlarge', 'required' => true)); ?>
							<label><i class=" halflings-icon map-marker"></i> Address Line 1</label>
							<?php echo $this->Form->input('User.address_line1', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xlarge')); ?>
							<label><i class=" halflings-icon map-marker"></i> Address Line 2</label>
							<?php echo $this->Form->input('User.address_line2', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xlarge')); ?>
							<label><i class=" halflings-icon time"></i> Time Zone</label>
							<?php $timeZones = Configure::read('TimeZones'); ?>
							<?php $defaultTimezone = $this->request->data['User']['timezone']; ?>
							<?php echo $this->Form->select('User.timezone', $timeZones, array('empty' => false, 'default' => (!empty($defaultTimezone) ? $defaultTimezone : 'GMT +0:00'), 'label' => false, 'div' => false, 'class' => 'input-xlarge')); ?>
							<div>
								<button class="btn btn-primary" type="submit" id="editProfile">Update</button>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
					<div class="tab-pane fade" id="profile">
						<?php echo $this->Form->create('users', array('type' => 'post', 'action' => 'editprofile', 'name' => 'UserChangePassword', 'id' => 'UserChangePassword', 'role' => 'form')); ?>
							<label><i class=" halflings-icon lock"></i> New Password</label>
							<?php echo $this->Form->input('User.password', array('type' => 'password', 'label' => false, 'div' => false, 'required' => true, 'minlength' => '6', 'maxlength' => 50, 'class' => "input-xlarge", 'placeholder' => "**********", 'value' => "", 'autocomplete' => 'off')); ?>
							<label><i class="halflings-icon info-sign noty" data-noty-options='{"text":"<strong>Warning:</strong> It is imperative that you keep your password as safe as possible. Never give your password to anyone for any reason.","layout":"topRight","type":"error"}'></i> Confirm Password</label>
							<?php echo $this->Form->input('User.cpassword', array('type' => 'password', 'label' => false, 'div' => false, 'required' => true, 'minlength' => '6', 'maxlength' => 50, 'class' => "input-xlarge", 'placeholder' => "**********", 'value' => "", 'autocomplete' => 'off')); ?>
							<br />
							<div>
								<button class="btn btn-primary" id="changePassword">Update</button>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
					<div class="tab-pane fade" id="payments">
						<?php echo $this->Form->create('users', array('type' => 'post', 'action' => 'editprofile/', 'name' => 'UserPaymentDetails', 'id' => 'UserPaymentDetails', 'role' => 'form')); ?>
							<?php echo $this->Form->input('Metauser.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<label>Card Type</label>
							<?php $cardTypes = Configure::read('CardTypes'); ?>
							<?php $defaultcardTypes = $this->request->data['Metauser']['card_type']; ?>
							<?php echo $this->Form->select('Metauser.card_type', $cardTypes, array('empty' => false, 'default' => (!empty($defaultcardTypes) ? $defaultcardTypes : 'Visa'), 'label' => false, 'div' => false, 'class' => 'input-xlarge')); ?>
							<label>Card Number</label>
							<?php echo $this->Form->input('Metauser.card_number', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 30, 'class' => 'input-xlarge')); ?>
							<label>CVC</label>
							<?php echo $this->Form->input('Metauser.cvv', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 4, 'class' => 'input-xlarge')); ?>
							<label>Name On Card</label>
							<?php echo $this->Form->input('Metauser.name_on_card', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'class' => 'input-xlarge')); ?>
							<label>Receipt Email</label>
							<?php echo $this->Form->input('Metauser.receipt_email', array('type' => 'email', 'label' => false, 'div' => false, 'maxlength' => 200, 'email' => true, 'remote' => $this->Html->url(array('controller' => 'users', 'action' => 'ajax_check_receiptemail/' . $this->Session->read('User.User.id'), 'superadmin' => false)), 'class' => 'input-xlarge', 'required' => true)); ?>
							<label>Zip-code</label>
							<?php echo $this->Form->input('Metauser.zip_code', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 7, 'class' => 'input-xlarge')); ?><br>
							<?php echo $this->Form->input('Metauser.remember_card', array('type' => 'checkbox', 'label' => false, 'div' => false, 'class' => 'input-xlarge', 'value' => '1')); ?>Remember<br>
							<div><br>
								<button class="btn btn-primary" id="updatePaymentDetail">Update</button>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
					<div class="tab-pane fade" id="about">
						<?php echo $this->Form->create('users', array('type' => 'post', 'action' => 'editprofile', 'name' => 'UserServices', 'id' => 'UserServices', 'role' => 'form')); ?>
							<?php echo $this->Form->input('Metauser.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<label><i class=" halflings-icon briefcase"></i> Company Name</label>
							<?php echo $this->Form->input('Metauser.company_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 100, 'class' => 'input-xlarge')); ?>
							<label><i class=" halflings-icon user"></i> Title</label>
							<?php echo $this->Form->input('Metauser.title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 100, 'class' => 'input-xlarge')); ?>
							<label><i class=" halflings-icon th-list"></i> Services <i class="halflings-icon info-sign noty" data-noty-options='{"text":"<strong>Services:</strong> Choose the Top 3 Services that you would like to showcase on your user profile. Include descriptions to really sell yourself!","layout":"topRight","type":"success"}'></i></label>
							<?php echo $this->Form->input('UserService.0.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserService.0.service_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge', 'placeholder' => 'Service 1')); ?>
							<?php echo $this->Form->input('UserService.0.service_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<?php echo $this->Form->input('UserService.1.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserService.1.service_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge', 'placeholder' => 'Service 2')); ?>
							<?php echo $this->Form->input('UserService.1.service_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<?php echo $this->Form->input('UserService.2.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserService.2.service_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge', 'placeholder' => 'Service 3')); ?>
							<?php echo $this->Form->input('UserService.2.service_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<div class="control-group">
								<label class="checkbox inline">
									<?php echo $this->Form->input('Metauser.is_show_services', array('type' => 'checkbox', 'label' => false, 'div' => false, 'class' => 'input-xlarge', 'value' => '1')); ?>Active/Inactive
								</label>
							</div>
							<br>
							<label><i class=" halflings-icon question-sign"></i> About Me</label>
							<?php echo $this->Form->textarea('Metauser.about_me', array('label' => false, 'div' => false, 'row' => 3, 'class' => 'input-xlarge textarea-large')); ?><br>
							<div>
								<button class="btn btn-primary" id="updateServices">Update</button>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
					<div class="tab-pane fade" id="social">
						<?php echo $this->Form->create('users', array('type' => 'post', 'action' => 'editprofile', 'name' => 'UserSocials', 'id' => 'UserSocials', 'role' => 'form')); ?>
							<?php echo $this->Form->input('Metauser.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserSocial.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<label><i class=" halflings-icon user"></i> Facebook URL</label>
							<?php echo $this->Form->input('UserSocial.facebook_url', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge')); ?>
							<?php echo $this->Form->input('UserSocial.facebook_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<label><i class=" halflings-icon user"></i> Twitter URL</label>
							<?php echo $this->Form->input('UserSocial.twitter_url', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge')); ?>
							<?php echo $this->Form->input('UserSocial.twitter_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<label><i class=" halflings-icon user"></i> LinkdIn URL</label>
							<?php echo $this->Form->input('UserSocial.linkedin_url', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge')); ?>
							<?php echo $this->Form->input('UserSocial.linkedin_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<label><i class=" halflings-icon user"></i> Instagram URL</label>
							<?php echo $this->Form->input('UserSocial.instagram_url', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge')); ?>
							<?php echo $this->Form->input('UserSocial.instagram_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<label><i class=" halflings-icon user"></i> Tumblr URL</label>
							<?php echo $this->Form->input('UserSocial.tumblr_url', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge')); ?>
							<?php echo $this->Form->input('UserSocial.tumblr_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<label><i class=" halflings-icon globe"></i> Website URL</label>
							<?php echo $this->Form->input('UserSocial.website_url', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 200, 'class' => 'input-xlarge')); ?>
							<?php echo $this->Form->input('UserSocial.website_description', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xxlarge', 'placeholder' => 'Description')); ?>
							<div>
								<div class="control-group">
									<label class="checkbox inline">
										<?php echo $this->Form->input('Metauser.is_show_social_media', array('type' => 'checkbox', 'label' => false, 'div' => false, 'class' => 'input-xlarge', 'value' => '1')); ?>Active/Inactive
									</label>
								</div>
								<br>
								<button class="btn btn-primary" id="updateSocials">Update</button>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
					<div class="tab-pane fade" id="offers">
						<?php echo $this->Form->create('users', array('type' => 'post', 'action' => 'editprofile', 'name' => 'UserOffers', 'id' => 'UserOffers', 'role' => 'form')); ?>
							<?php echo $this->Form->input('Metauser.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<label><i class=" halflings-icon certificate"></i> Offer</label>
							<?php echo $this->Form->input('UserOffer.0.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserOffer.0.offer_type', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 20, 'required' => true, 'class' => 'input-mini', 'placeholder' => 'ex: 10%')); ?>
							<?php echo $this->Form->input('UserOffer.0.offer_title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xlarge', 'placeholder' => 'ex: 10% OFF!')); ?>
							<?php echo $this->Form->input('UserOffer.0.offer_detail', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xxlarge', 'placeholder' => 'Offer Description - 50 characters or less')); ?>						
							<label><i class=" halflings-icon certificate"></i> Offer</label>
							<?php echo $this->Form->input('UserOffer.1.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserOffer.1.offer_type', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 20, 'required' => true, 'class' => 'input-mini', 'placeholder' => 'ex: Deal!')); ?>
							<?php echo $this->Form->input('UserOffer.1.offer_title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xlarge', 'placeholder' => 'ex: FREE Massage!')); ?>
							<?php echo $this->Form->input('UserOffer.1.offer_detail', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xxlarge', 'placeholder' => 'Offer Description - 50 characters or less')); ?>
							<label><i class=" halflings-icon certificate"></i> Offer</label>
							<?php echo $this->Form->input('UserOffer.2.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserOffer.2.offer_type', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 20, 'required' => true, 'class' => 'input-mini', 'placeholder' => 'ex: 50%')); ?>
							<?php echo $this->Form->input('UserOffer.2.offer_title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xlarge', 'placeholder' => 'ex: Half Off Today!')); ?>
							<?php echo $this->Form->input('UserOffer.2.offer_detail', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xxlarge', 'placeholder' => 'Offer Description - 50 characters or less')); ?>
							<label><i class=" halflings-icon certificate"></i> Offer</label>
							<?php echo $this->Form->input('UserOffer.3.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
							<?php echo $this->Form->input('UserOffer.3.offer_type', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 20, 'required' => true, 'class' => 'input-mini', 'placeholder' => 'ex: FREE!')); ?>
							<?php echo $this->Form->input('UserOffer.3.offer_title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xlarge', 'placeholder' => 'ex: Weekend Sale!')); ?>
							<?php echo $this->Form->input('UserOffer.3.offer_detail', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'input-xxlarge', 'placeholder' => 'Offer Description - 50 characters or less')); ?>
							<div>
								<div class="control-group">
									<label class="checkbox inline">
										<?php echo $this->Form->input('Metauser.is_show_offers', array('type' => 'checkbox', 'label' => false, 'div' => false, 'class' => 'input-xlarge', 'value' => '1')); ?>Active/Inactive
									</label>
								</div>
								<br>
								<button class="btn btn-primary" id="updateOffers">Update</button>
							</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>	
		</div>
	</div>		
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#UserEditProfile").validate();
		$("#UserChangePassword").validate();
		$("#UserPaymentDetails").validate();
		$("#UserServices").validate();
		$("#UserSocials").validate();
		$("#UserOffers").validate();
	});
</script>