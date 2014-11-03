<div id="editClientModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php echo $this->Form->create('Admin', array('type' => 'file', 'action' => 'editclient', 'name' => 'editClient', 'id' => 'editClient', 'class' => 'form-horizontal styleThese', 'role' => 'form', '')); ?>	
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/html" id='editTemplate'>
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Edit Client(User)</h3>
			</div>
			<div class="modal-body">
				<input type="hidden" id="UserId" name="data[User][id]"  value="<%= user.id %>" /> 
				<center>
					<div class="input-group">
						<span class="input-group-addon">First Name<span class="star">*</span></span>            
						<input type="text" id="UserFirstName" placeholder="First Name" class="form-control" required="required" maxlength="50" name="data[User][first_name]" value="<%= user.first_name %>"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Last Name<span class="star">*</span></span>            
						<input type="text" id="UserLastName" placeholder="Last Name" class="form-control" required="required" maxlength="50" name="data[User][last_name]" value="<%= user.last_name %>"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">User Name<span class="star">*</span></span>            
						<input type="text" id="UserUserName" minlength="2" loginregex="1" remote="<?php echo $this->webroot; ?>Users/ajax_check_username/<%= user.id %>" placeholder="Please enter username" class="form-control" required="required" maxlength="30" name="data[User][user_name]" value="<%= user.user_name %>"></div><br>
					<div class="input-group">
						<span class="input-group-addon">Email<span class="star">*</span></span>            
						<input type="email" id="UserEmail" remote="<?php echo $this->webroot; ?>Users/ajax_check_email/<%= user.id %>" placeholder="Example : example@example.com" class="form-control" email="1" required="required" maxlength="100" name="data[User][email]" value="<%= user.email %>"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Password<span class="star"></span></span>            
						<input type="password" id="UserChangePassword" placeholder="Update Password" class="form-control" minlength="6" maxlength="50" name="data[User][change_password]"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Phone</span>            
						<input type="text" id="UserPhone" number="1" placeholder="Example : +1-646-222-9999" class="form-control" maxlength="20" name="data[User][phone]" value="<%= user.phone %>"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Business Name<span class="star">*</span></span>            
						<input type="text" id="UserBusinessName" required="required" placeholder="Business Name" class="form-control" maxlength="20" name="data[User][business_name]" value="<%= user.business_name %>"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Website</span>            
						<input type="text" id="UserWebsite" placeholder="http://www.yourpage.com" class="form-control" maxlength="20" name="data[User][website]" value="<%= user.website %>"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Address1</span>            
						<input type="text" id="UserAddressLine1" placeholder="Address Line 1" class="form-control" maxlength="100" name="data[User][address_line1]" value="<%= user.address_line1 %>">
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Address2</span>            
						<input type="text" id="UserAddressLine2" placeholder="Address Line 2" class="form-control" maxlength="100" name="data[User][address_line2]" value="<%= user.address_line2 %>"> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Avatar</span>
						<?php echo $this->Form->input('User.profile_avatar', array('type' => 'file', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'form-control', 'placeholder' => 'Please upload profile avatar.')); ?> 
					</div><span style="text-align:right">Avatar should be 125x125 in .png format.</span><br />
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
				<button type="submit" id="singlebutton" class="btn btn-primary">Save</button>
			</div>
		</div>
    </div>
</script>
<script type="text/javascript">
	$(document).ready(function() {
		$.validator.addMethod("loginRegex", function(value, element) {
			return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
		}, "Username must contain only letters, numbers, or dashes.");

		$("#editClient").validate();
		var editUrl = $('#editClient').attr('action');
		$(".editRowClient").click(function(event) {
			event.preventDefault();
			var url = $(this).attr('href');
			$('body').modalmanager('loading');
			$.getJSON(url, function(data) {
				$('#editClient').attr('action', editUrl + '/' + data.users.User.id);
				var template = $("#editTemplate").html();
				$("#editClient").html(_.template(template, {user: data.users.User, PopupTitle: data.PopupTitle}));
				$('#editClientModel').modal('show');
			});
		});
	});
</script>