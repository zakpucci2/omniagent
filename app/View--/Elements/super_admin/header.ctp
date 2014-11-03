<?php //$this->Html->script('additional_method_validate', array('inline' => false)); //Attached it for use the jquery accept method for logo upload   ?>

<?php
if ($this->Session->check('User')) {
	$usersession = $this->Session->read('User');
}
if ($usersession['User']['cover_photo'] == '') {
	$coverPhoto = Configure::read('ROOTURL') . '/app/webroot/img/cover_photo/800_300_1.png';
} else {
	$coverPhoto = Configure::read('ROOTURL') . '/app/webroot/img/cover_photo/' . $usersession['User']['cover_photo'];
}
?>
<div class="span3 well"  style="background-image: url('<?php echo $coverPhoto; ?>'); height: 100%; width: 100%;">
	<?php echo $this->Html->link('<i class="glyphicon glyphicon-user"></i>', 'javascript:void(0)', array('title' => 'Change Profile Photo', 'escape' => false, 'class' => 'btn btn-xs btn-primary', 'id' => 'ProfilePhoto')); ?>
	<?php echo $this->Html->link('<i class="glyphicon glyphicon-picture"></i>', 'javascript:void(0)', array('title' => 'Change Cover Photo', 'escape' => false, 'class' => 'btn btn-xs btn-primary', 'id' => 'CoverPhoto')); ?>
    <center>
		<?php
		if ($usersession['User']['profile_photo'] == '') {
			echo $this->Html->image('profile_photo/150x150.png', array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
		} else {
			echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto150x150")  . '/' . $usersession['User']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
		}
		?>
        <h3><font color="white"><b>Welcome (<?php echo $usersession['User']['first_name'] . " " . $usersession['User']['last_name']; ?>) <small>You are logged in as (<?php echo $usersession['UserType']['name']; ?>)</small></b></font></h3>
    </center>
</div>

<div class="modal fade" id="coverModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Change Cover Photo - <small>Must be .PNG .JPG or .GIF</small></h4>
            </div>
			<?php echo $this->Form->create('User', array('type' => 'file', 'url' => Configure::read('ROOTURL') . '/users/change_cover_photo', 'id' => 'change_cover_photo', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
            <div class="modal-body">
                <center>
                    <div class="control-group">
                        <label class="control-label">Choose a photo</label>
                        <div class="controls">
                            <input type="file" name="data[User][cover_photo]" required="required" data-msg-required="Please upload cover photo.">
                        </div>
                    </div>
                    <br><h5>Just a tip, guys! For the best fit, your cover photo should be <strong>1143x278</strong> or larger.</h5>
                </center>
            </div>
            <div class="modal-footer">
                <center>
                    <button id="singlebutton" type="submit" name="singlebutton" class="btn btn-primary">Apply</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>
            </div>
			<?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>	

<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Change Profile Photo - <small>Must be .PNG .JPG or .GIF</small></h4>
            </div>
			<?php echo $this->Form->create('User', array('type' => 'file', 'url' => Configure::read('ROOTURL') . '/users/change_profile_photo', 'id' => 'change_profile_photo', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
            <div class="modal-body">

                <center>
                    <div class="control-group">
                        <label class="control-label">Choose a photo</label>
                        <div class="controls">
                            <input type="file" name="data[User][profile_photo]" required="required" data-msg-required="Please upload profile photo.">
                        </div>
                    </div>

                    <br><h5>Just a tip, guys! For the best fit, your profile photo should be <strong>150x150</strong>.</h5>
                </center>
            </div>
            <div class="modal-footer">
                <center>
                    <button id="singlebutton" type="submit"  name="singlebutton" class="btn btn-primary">Apply</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>
            </div>
			<?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#ProfilePhoto").click(function(event) {
			event.preventDefault();
			$('#profileModal').modal('show');
		});
		$("#CoverPhoto").click(function(event) {
			event.preventDefault();
			$('#coverModal').modal('show');
		});

<?php
if (!empty($notify_data)) {
	$i = 1;
	foreach ($notify_data as $data) {
		$notiMsg = "";
		// $notiMsg = '<div>From: ' . $data['Sender']['first_name'] . ' ' . $data['Sender']['last_name'] . '</div>';
		// $notiMsg .= '<div>Subject: ' . $data['Notification']['subject'] . '</div>';
		$notiMsg .= '<div>' . nl2br(str_replace("\r", "", str_replace("\n", "", $data['Notification']['body'])), true) . '</div>';
		$notiMsg .= '<div class="alignright"><br /><i>' . $data['Notification']['ago'] . '</i></div>';
		?>
				var n<?php echo $data['UserNotification']['id'] ?> = noty({
					text: '<?php echo (string) str_replace("\n\r", "", $notiMsg); ?>',
					type: 'notification',
					dismissQueue: true,
					layout: 'topRight',
					animation: {
						open: {height: 'toggle'},
						close: {height: 'toggle'},
						easing: 'swing',
						speed: 1000 // opening & closing animation speed
					},
					timeout: false, // delay for closing event. Set false for sticky notifications
					theme: 'defaultTheme',
					closeWith: ['button', 'click'],
					maxVisible: 3,
					buttons: [
						{
							addClass: 'btn btn-primary closebtn_' + <?php echo $data['UserNotification']['id'] ?>, text: 'Ok', onClick: function($noty) {
								$noty.close();
								search = 'closebtn_'
								var classList = $(this).attr("class").split(/\s+/);
								var currFullId = classList[2];
								var currID = currFullId.replace(search, '');
								$.post("<?php echo $this->Html->url(array('controller' => 'notifications', 'action' => 'readNotification', 'admin' => false)); ?>",
										{notyId: currID}
								);
								/* noty({
								 dismissQueue: true, 
								 force: true, 
								 layout: 'topRight', 
								 theme: 'defaultTheme', 
								 text: 'You clicked "Ok" button', 
								 type: 'success'
								 }); */
							}
						},
						{
							addClass: 'btn btn-danger dismissbtn_' + <?php echo $data['UserNotification']['id'] ?>, text: 'Dismiss', onClick: function($noty) {
								$noty.close();
								search = 'dismissbtn_'
								var classList = $(this).attr("class").split(/\s+/);
								var currFullId = classList[2];
								var currID = currFullId.replace(search, '');
								$.post("<?php echo $this->Html->url(array('controller' => 'notifications', 'action' => 'dismissNotification', 'admin' => false)); ?>",
										{notyId: currID}
								);
								/* noty({
								 dismissQueue: true, 
								 force: true, 
								 layout: 'topRight', 
								 theme: 'defaultTheme', 
								 text: 'You clicked "Cancel" button', 
								 type: 'error'
								 }); */
							}
						}
					]
				});
		<?php
		$i++;
	}
}
?>
	});
</script>