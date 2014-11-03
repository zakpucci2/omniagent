<!-- Modal View Message-->
<div class="modal hide fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php echo $this->Form->create('messages', array('type' => 'post', 'action' => 'reply_message', 'name' => 'UserReplyMessageForm', 'id' => 'UserReplyMessageForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<?php echo $this->Form->end(); ?>
</div>
<!-- Modal View Message End-->
<script type="text/html" id='replyMessageTemplate'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Reply Message</h4>
			</div>
			<div class="modal-body">
				<div class="control-group">
					<label class="control-label">To</label>
					<div class="controls">
						<input type="text" id="MessageReceiverUserName" class="form-control" maxlength="100" name="data[Message][receiver_user_name]" readonly="readonly" style="width:76%" required="required" value="<%= message_data.Sender.user_name %>" placeholder= "Type a user or admin name..." />@<?php echo Configure::read('SITE_EMAIL.Email'); ?>
						<input type="hidden" id="MessageParentMessageId" class="input-xlarge" name="data[UserMessage][parent_message_id]" value="<%= message_data.Message.id %>" />
						<input type="hidden" id="MessageReceiverId" class="input-xlarge" maxlength="100" name="data[UserMessage][receiver_id]" value="<%= message_data.UserMessage.sender_id %>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Subject</label>
					<div class="controls">
						<input type="text" id="MessageSubject" class="input-xlarge" maxlength="255" name="data[Message][subject]" value="Re: <%= message_data.Message.subject %>" required="required" placeholder= "Message Subject" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Message</label>
					<div class="controls">
						<textarea row="6" class="input-xlarge textarea-large" id="MessageBody" name="data[Message][body]" placeholder="Message Body" required="required"></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<center>
					<button id="sendMessage" type="submit" value="Send Reply" name="sendMessage" class="btn btn-primary">Send</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                $('#UserReplyMessageForm').attr('action', replyMessageURL + '/' + data.message.Message.id);
                var template = $("#replyMessageTemplate").html();
                $("#UserReplyMessageForm").html(_.template(template, {message_data:data.message,PopupTitle:data.PopupTitle}));
                $('#replyModal').modal('show');
            });
        });
    });
</script>