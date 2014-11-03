<style>
	
	
</style>
<div class="modal fade" id="newmessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo $this->Form->create('messages', array('type' => 'post', 'action' => 'admin_compose_message', 'name' => 'UserComposeMessageForm', 'id' => 'UserComposeMessageForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
			<?php echo $this->Form->input('UserMessage.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Compose New Message</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">To</span> <span id="to_user" style="border: 1px solid rgb(209, 209, 209); text-align: left; display: block; width: 515px; color: rgb(153, 153, 153); padding: 3px 11px;"></span>
						<?php //echo $this->Form->input('Message.receiver_user_name', array('type' => 'text','id'=>'to_user', 'label' => false, 'div' => false, 'maxlength' => 100, 'class' => 'form-control', 'style' => 'width:76%', 'required' => true)); ?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Subject</span>
						<?php echo $this->Form->input('Message.subject', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'form-control', 'required' => true,"id"=>"subject" ,'placeholder' => 'Message subject')); ?>

					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Message</span>
						<?php echo $this->Form->textarea('Message.body', array('label' => false, 'div' => false, 'row' => 6, 'class' => 'form-control textarea-large', 'required' => true,"id"=>"send_msg" ,'placeholder' => 'Message body')); ?>

					</div><br>
				</center>
			</div>
			<div class="modal-footer">
				<center>
                    <button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">Send</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</center>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>




<script>
    $(document).ready(function () {
        $(".send_message").click(function (event) {
            event.preventDefault();
            var urlLoc = $(this).attr('data-href');
            var user_email = $(this).attr('data-useremail');
				
            $('body').modalmanager('loading');
            if(urlLoc) {
                $('#UserComposeMessageForm').attr('action', urlLoc);
                $('#to_user').html(user_email);
                $('#newmessageModal').modal('show');
            };
        });
		
		
		$("#UserComposeMessageForm").validate();
		
    });
</script>