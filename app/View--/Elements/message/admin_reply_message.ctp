<div class="modal fade" id="replyMessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php echo $this->Form->create('messages', array('type' => 'post', 'action' => 'admin_reply_message', 'name' => 'UserReplyMessageForm', 'id' => 'UserReplyMessageForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/html" id='replyMessageTemplate'>
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo $this->Form->input('UserMessage.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Reply Message</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">To</span>
						<input type="text" id="MessageReceiverUserName" class="form-control" maxlength="100" name="data[Message][receiver_user_name]" readonly="readonly" style="width:76%" required="required" value="<%= message_data.Sender.user_name %>" placeholder= "Type a user or admin name..." />@<?php echo Configure::read('SITE_EMAIL.Email'); ?>
						<input type="hidden" id="MessageParentMessageId" class="form-control" name="data[UserMessage][parent_message_id]" value="<%= message_data.Message.id %>" />
						<input type="hidden" id="MessageReceiverId" class="form-control" name="data[UserMessage][receiver_id]" value="<%= message_data.UserMessage.sender_id %>" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Subject</span>
						<input type="text" id="MessageSubject" class="form-control" maxlength="255" name="data[Message][subject]" value="Re: <%= message_data.Message.subject %>" required="required" placeholder= "Message Subject" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Message</span>
						<textarea rows="6" class="form-control" id="MessageBody" name="data[Message][body]" placeholder="Message Body" required="required"></textarea>
					</div><br>
				</center>
			</div>
			<div class="modal-footer">
				<center>
                    <button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">Send</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</center>
			</div>
		</div>
	</div>
</script>
<script type="text/javascript">
    $(document).ready(function() {       
        $("#UserReplyMessageForm").validate();        
        var replyMessageURL = $('#UserReplyMessageForm').attr('action');
        $(".replyRow").click(function(event) {
            event.preventDefault();
            var urlLoc = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(urlLoc, function(data) {
				$('#UserReplyMessageForm').attr('action', replyMessageURL + '/' + data.message.Message.id);
                var template = $("#replyMessageTemplate").html();
                $("#UserReplyMessageForm").html(_.template(template, {message_data:data.message,PopupTitle:data.PopupTitle}));
                $('#replyMessageModal').modal('show');
            });
        });
    });
</script>