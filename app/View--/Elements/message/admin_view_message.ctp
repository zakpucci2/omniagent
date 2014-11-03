<div class="modal fade" id="viewMessageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<script type="text/html" id='viewMessageTemplate'>
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo $this->Form->input('UserMessage.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">View Message Details</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">From</span>
						<input type="text" id="MessageReceiverUserName" class="form-control" maxlength="100" readonly="readonly" required="required" value="<%= message_data.Sender.first_name + ' ' + message_data.Sender.last_name %>" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Arrived On</span>
						<input type="text" id="MessageReceiverUserName" class="form-control" maxlength="100" readonly="readonly" required="required" value="<%= message_data.Message.date %>" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Subject</span>
						<input type="text" id="MessageSubject" class="form-control" maxlength="255" readonly="readonly" value="<%= message_data.Message.subject %>" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Message</span>
						<textarea rows="6" class="form-control" readonly="readonly" id="MessageBody"><%= message_data.Message.body %></textarea>
					</div><br>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</script>
<script type="text/javascript">
    $(document).ready(function() {       
        $("#UserViewMessageForm").validate();        
        var replyMessageURL = $('#UserViewMessageForm').attr('action');
        $(".viewRow").click(function(event) {
			var currObj = $(this);
            event.preventDefault();
            var urlLoc = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(urlLoc, function(data) {
                var template = $("#viewMessageTemplate").html();
                $("#viewMessageModal").html(_.template(template, {message_data:data.message,PopupTitle:data.PopupTitle}));
                $('#viewMessageModal').modal('show');
				$('#viewMessageModal').on('hidden.bs.modal', function () {
					$(currObj).parent().parent().removeClass('boldtr');
				});
            });
        });
    });
</script>