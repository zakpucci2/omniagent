<?php //$this->Html->css('bootstrap/form_validaion', null, array('inline' => false));  ?>
<style>
    form label.error{
		position: absolute;
		color:#FF0000;
		font-size: 12px;
		margin:0px;
		padding: 0px;
		font-weight: normal;
		right:5px;
		top:35px;
	}
</style>
<?php //$this->Html->script('jquery.validate', array('inline' => false)); ?>
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
            </div>
			<?php echo $this->Form->create('User', array('type' => 'POST', 'url' => Configure::read('ROOTURL') . '/users/changepassword', 'id' => 'changePassForm1', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
			<?php //echo $this->Form->input('User.id', array('type' => 'hidden', 'label' => false, 'div' => false, 'required' => true)); ?> 
            <div class="modal-body">
                <center>
					<div class="input-group">
                        <span class="input-group-addon">New Password&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
						<?php echo $this->Form->input('User.password', array('type' => 'password', 'label' => false, 'div' => false, 'required' => true, 'PasswordStrength' => 1, 'class' => 'form-control', 'minlength' => '6', 'maxlength' => 50, 'id' => 'UserChPassword1')); ?> 
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon">Re-type Password</span>
						<?php echo $this->Form->input('User.cpassword', array('type' => 'password', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'id' => 'UserChCPassword1', 'equalTo' => '#UserChPassword1')); ?>
                </center>
            </div>
            <div class="modal-footer">
                <center>
                    <button id="singlebutton" type="submit" name="singlebutton" class="btn btn-primary">Update</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>
            </div>
			<?php echo $this->Form->end(); ?>       
        </div>
    </div>
</div>	
<script type="text/javascript">
	$(document).ready(function() {
		$("#ChangePassword").click(function(event) {
			event.preventDefault();
			$('#changePasswordModal').modal('show');
		});
		$("#changePassForm1").validate();
	});
</script>