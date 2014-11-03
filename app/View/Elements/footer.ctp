<div class="modal hide fade" id="changePhotoModal">
	<?php echo $this->Form->create('User', array('type' => 'file', 'url' => Configure::read('ROOTURL') . '/users/change_profile_photo', 'id' => 'change_profile_photo', 'class' => 'form-horizontal', 'role' => 'form')); ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title" id="myModalLabel">Change Avatar</h4>
	</div>
	<div class="modal-body">
		<p></p>
		<center>
			<div class="control-group">
				<label class="control-label">File Upload</label>
				<div class="controls">
					<input type="file" name="data[User][profile_photo]" required="required" data-msg-required="Please upload profile photo." />
				</div>
			</div>Avatar should be 125x125 in .png format.
		</center>
	</div>
	<div class="modal-footer">
		<button id="singlebutton" type="submit"  name="singlebutton" class="btn btn-primary">Apply</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<div class="clearfix"></div>
<div class="navbar navbar-default navbar-fixed-bottom">
	<div class="navbar-header"></div>
	<ul class="nav navbar-nav"> 
		<li><a href="<?php echo Configure::read('FULL_BASE_URL.URL'); ?>" target="_blank"><?php echo Configure::read('SITENAME.Copyright'); ?></a></li>
	</ul>
</div>