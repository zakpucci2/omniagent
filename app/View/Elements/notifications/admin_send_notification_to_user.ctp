<div class="modal fade" id="SendPushNotificationModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo $this->Form->create('notifications', array('type' => 'POST', 'action' => 'send_push_notification', 'name' => 'sendPushNotificationForm', 'id' => 'sendPushNotificationForm', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">Send Push Notification</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Subject<span class="star">*</span></span>            
						<?php echo $this->Form->input('Notification.subject', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Notification Title')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Body<span class="star">*</span></span>            
						<?php echo $this->Form->input('Notification.body', array('type' => 'textarea', 'label' => false, 'div' => false, 'rows' => 5, 'cols' => 20, 'maxlength' => 255, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Notification Body')); ?> 
					</div><br>
					<div class="input-group">
						<?php /*<span class="input-group-addon">Send Notification To</span> <span id="user_name"></span> */?>            
						
						<label class="checkbox-inline">
							<?php echo $this->Form->input('Notification.user_id', array("type"=>"hidden",'id' => 'NotificationAdminUser', 'div' => false, 'label' => false, 'hiddenField' => false)); ?> 
						</label>
						
					</div><br>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
				<button type="submit" id="singlebutton" class="btn btn-primary">Send</button>
			</div>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('#sendPushNotificationForm').validate({// initialize the plugin
			rules: {
				'UserType[]': {
					required: true,
					maxlength: 2
				}
			},
			messages: {
				'UserType[]': {
					required: "You must check at least 1 box",
					maxlength: "Check no more than {0} boxes"
				}
			}
		});
		$(".sendNotificationToUser").click(function(event) {
			event.preventDefault();
			
			var url=$(this).attr("data-href");
			var user_name=$(this).attr("data-username");
			var user_name_id=$(this).attr("data-user-id");
			$('#sendPushNotificationForm').attr("action",url);
			$('#user_name').html(user_name);
			$('#NotificationAdminUser').val(user_name_id);
			
			$('#SendPushNotificationModel').modal('show');
		});
	});
</script>