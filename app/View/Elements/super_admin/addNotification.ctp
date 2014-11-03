<?php //$this->Html->css('bootstrap/form_validaion',null, array('inline' => false));  ?>
<?php $this->Html->css('bootstrap/bootstrap-editable', null, array('inline' => false)); ?>
<?php $this->Html->script('bootstrap/bootstrap-editable', array('inline' => false)); ?>
<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>.star {color:red;}</style>
<div class="modal fade" id="AddNotificationModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-focus-on="#UserFirstName">
	<div class="modal-dialog">
		<?php echo $this->Form->create('notifications', array('type' => 'POST', 'action' => 'addnotification', 'name' => 'addNewNotification', 'id' => 'addNewNotification', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Notification</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Subject<span class="star">*</span></span>            
						<?php echo $this->Form->input('Notification.subject', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Notification Subject')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Body<span class="star">*</span></span>            
						<?php echo $this->Form->input('Notification.body', array('type' => 'textarea', 'label' => false, 'div' => false, 'rows' => 5, 'cols' => 20, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Notification Message', 'value' => null)); ?> 
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
		$("#addNewNotification").validate();
	});
</script>