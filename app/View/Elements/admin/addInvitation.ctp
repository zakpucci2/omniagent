<?php //$this->Html->css('bootstrap/form_validaion',null, array('inline' => false));     ?>
<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>.star {color:red;}</style>
<div class="modal fade" id="AddInvitationModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-focus-on="#UserFirstName">
	<div class="modal-dialog">
		<?php echo $this->Form->create('admins', array('type' => 'POST', 'action' => 'addinvite', 'name' => 'addNewInvite', 'id' => 'addNewInvite', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Invitation</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">First Name<span class="star">*</span></span>            
						<?php echo $this->Form->input('Invitation.first_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'First Name')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Last Name<span class="star">*</span></span>            
						<?php echo $this->Form->input('Invitation.last_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Last Name')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Email<span class="star">*</span></span>            
						<?php echo $this->Form->input('Invitation.email', array('type' => 'email', 'label' => false, 'div' => false, 'maxlength' => 100, 'required' => true, 'email' => true, 'class' => 'form-control', 'placeholder' => 'Example : example@example.com', 'remote' => $this->Html->url(array('controller' => 'Users', 'action' => 'ajax_check_email_invite', 'superadmin' => false)), 'value' => null)); ?> 
					</div><br>
					<div class="modal-footer">
						<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
						<button type="submit" id="singlebutton" class="btn btn-primary">Send</button>
					</div>
				</center>
			</div>
			<?php echo $this->Form->end(); ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#addNewInvite").validate();
	});
</script>