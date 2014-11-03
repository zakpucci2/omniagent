<!-- Modal Compose Message-->
<div class="modal fade" id="composeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Compose New Message</h4>
			</div>
			<div class="modal-body">
				<?php echo $this->Form->create('messages', array('type' => 'post', 'action' => 'compose_message', 'name' => 'UserComposeMessageForm', 'id' => 'UserComposeMessageForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
					<?php echo $this->Form->input('UserMessage.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
					<div class="control-group">
						<label class="control-label">To</label>
						<div class="controls">
						<?php echo $this->Form->input('Message.receiver_user_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 100, 'class' => 'form-control', 'style' => 'width:76%', 'required' => true, 'placeholder' => 'Type a user or admin name...')); ?>@<?php echo Configure::read('SITE_EMAIL.Email');?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Subject</label>
						<div class="controls">
							<?php echo $this->Form->input('Message.subject', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xlarge', 'required' => true, 'placeholder' => 'Message subject')); ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label">Message</label>
						<div class="controls">
							<?php echo $this->Form->textarea('Message.body', array('label' => false, 'div' => false, 'row' => 6, 'class' => 'input-xlarge textarea-large', 'required' => true, 'placeholder' => 'Message body')); ?>
						</div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
			<div class="modal-footer">
				<center>
					<button id="sendMessage" type="submit"  name="sendMessage" class="btn btn-primary">Send</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</center>
			</div>
		</div>
	</div>
</div>
<!-- Modal Compose Message End-->