<div id="sidebar-left" class="span2">
	<br>
	<!--Start User Avatar/Contact-->
	<center>
		<span class="hidden-tablet">
			<?php 
				if($usersession['User']['profile_photo'] == '') {
					echo $this->Html->image('profile_photo/125x125.png', array('alt' => "avatar", 'class' => 'img-rounded', 'style' => 'border:3px solid white; width:125px;')); 
				} else {
					if(file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/profile_photo/' . $usersession['User']['profile_photo'])) {
						echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto150x150")  . '/' . $usersession['User']['profile_photo'], array('alt' => "avatar", 'class' => 'img-rounded', 'style' => 'border:3px solid white; width:125px;'));  
					} else {
						echo $this->Html->image('profile_photo/125x125.png', array('alt' => "avatar", 'class' => 'img-rounded', 'style' => 'border:3px solid white; width:125px;')); 
					}
				}
			 ?>
			<br />
			<p><font color="white"><?php echo __('Logged in as: ' . ((isset($usersession['User']['business_name']) && !empty($usersession['User']['business_name'])) ? $usersession['User']['business_name'] : ((isset($usersession['User']['first_name']) && !empty($usersession['User']['first_name'])) ? $usersession['User']['first_name'] : $usersession['User']['user_name']))); ?></font></p>
			<p>
				<a href="#changePhotoModal" class="btn-setting changePhoto" id="avatarDiv"><button class="btn btn-mini btn-primary"><i class="halflings-icon white picture"></i> <?php echo __('Change Avatar'); ?></button></a>
			</p>
		</span>
	</center>
	<!--End User Avatar/Contact-->
	
<?php
$controller = $this->params['controller'];
$action = $this->params['action'];
switch ($controller) {
	
}

// Switch case to show current menu item selected in the admin section

switch ($action) {
	case 'sentmessages':
		$sentmessage = 'class="active"';
		break;
}
?>
	
	<br>
	<div class="nav-collapse sidebar-nav">
		<ul class="nav nav-tabs nav-stacked main-menu">
			<li id="dashboardDiv"><a href="<?php echo $this->webroot . 'users/dashboard'; ?>"><i class="icon-bar-chart"></i><span class="hidden-tablet"> <?php echo __('Dashboard'); ?></span></a></li>	
			<li <?php echo (isset($sentmessage) ? $sentmessage : '')?> id="messagesCounterDiv"><a href="<?php echo $this->webroot . 'messages/listmessages'; ?>"><i class="icon-envelope"></i><span class="hidden-tablet"> <?php echo __('Messages'); ?></span></a></li>
			<li id="notificationCounterDiv"><a href="<?php echo $this->webroot . 'notifications/listnotifications'; ?>"><i class="icon-warning-sign"></i><span class="hidden-tablet"> <?php echo __('Notifications'); ?></span></a></li>
			<li id="supportTicketDiv"><a href="<?php echo $this->webroot . 'support_tickets/listtickets'; ?>"><i class="icon-file"></i><span class="hidden-tablet"> <?php echo __('Support Tickets'); ?></span></a></li>
			<li id="tasksCounterDiv"><a href="<?php echo $this->webroot . 'tasks/listtasks'; ?>"><i class="icon-tasks"></i><span class="hidden-tablet"> <?php echo __('Tasks'); ?></span></a></li>
			<li id="galleryDiv"><a href="<?php echo $this->webroot . 'gallery/listimages'; ?>"><i class="icon-picture"></i><span class="hidden-tablet"> <?php echo __('Gallery'); ?></span></a></li>
			<li id="calendarDiv"><a href="<?php echo $this->webroot . 'calendar'; ?>"><i class="icon-calendar"></i><span class="hidden-tablet"> <?php echo __('Calendar'); ?></span></a></li>
			<li id="fileManagerDiv"><a href="<?php echo $this->webroot . 'files_manager'; ?>"><i class="icon-folder-open"></i><span class="hidden-tablet"> <?php echo __('File Manager'); ?></span></a></li>
			<li><a href="<?php echo $this->webroot . 'users/logout'; ?>"><i class="icon-lock"></i><span class="hidden-tablet"> <?php echo __('Logout'); ?></span></a></li>
		</ul>
	</div>
</div>
<script type="text/javascript">
	$(".changePhoto").click(function(event) {
		event.preventDefault();
		$("#change_profile_photo").validate();
		$('#changePhotoModal').modal('show');
	});
</script>