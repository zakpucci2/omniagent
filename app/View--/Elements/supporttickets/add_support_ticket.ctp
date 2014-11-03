<!-- Modal Compose Message-->
<div class="modal hide fade" id="addSupportTicketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Support Ticket</h4>
			</div>
			<div class="modal-body">
				<?php echo $this->Form->create('support_tickets', array('type' => 'post', 'action' => 'add_support_ticket', 'name' => 'UserAddSupportTicketForm', 'id' => 'UserAddSupportTicketForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
					<div class="control-group">
						<label class="control-label">Subject</label>
						<div class="controls">
							<?php echo $this->Form->input('SupportTicket.subject', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xlarge', 'required' => true, 'placeholder' => 'Subject')); ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Message</label>
						<div class="controls">
							<?php echo $this->Form->textarea('SupportTicket.message', array('label' => false, 'div' => false, 'row' => 6, 'class' => 'input-xlarge textarea-large', 'required' => true, 'placeholder' => 'Description of ticket')); ?>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="modal-footer">
				<center>
					<button id="btnAddTicket" type="submit"  name="btnAddTicket" class="btn btn-primary">Send</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</center>
			</div>
		</div>
	</div>
</div>
<!-- Modal Compose Message End-->
<script type="text/javascript">
$(document).ready(function(){
	$("#UserAddSupportTicketForm").validate();
	$("#btnAddTicket").click(function(){
		$("#UserAddSupportTicketForm").submit();
	});
});
</script>