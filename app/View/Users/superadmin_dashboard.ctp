<?php $this->Html->css('bootstrap/bootstrap-editable', null, array('inline' => false)); ?>
<?php $this->Html->script('bootstrap/bootstrap-editable', array('inline' => false)); ?>
<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-ok"></i> Invitations</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> New Invite', array('controller' => 'users', 'action' => 'addinvite', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Add Invitation', 'id' => 'addInvitation')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php echo $this->Form->create('users', array('action' => 'dashboard', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('User.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Invitation", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
		?>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">Search!</button>
        </span>
    </div>
	<?php echo $this->Form->end(); ?>
    <br/>
    <div class="span7">   
        <div class="widget stacked widget-table action-table">
            <div class="widget-content">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('full_name', 'Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
                            <th><?php echo $this->Paginator->sort('request_token', 'Token'); ?></th>
                            <th><?php echo $this->Paginator->sort('is_request_accepted', 'User Accepted?'); ?></th>
                            <th><?php echo $this->Paginator->sort('modified', 'Invite On'); ?></th>
                            <th><?php echo $this->Paginator->sort('invited_by', 'Invited By'); ?></th>
							<th><?php echo $this->Paginator->sort('is_superadmin_approved', 'Is Approved?'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $invitedata) {
								?>
								<tr>
									<td><?php echo ucfirst($invitedata['Invitation']['full_name']); ?></td>
									<td><?php echo $invitedata['Invitation']['email']; ?></td>
									<td><?php echo $invitedata['Invitation']['request_token']; ?></td>
									<td><?php echo ($invitedata['Invitation']['is_request_accepted'] == 1 ? 'Yes' : ($invitedata['Invitation']['is_request_accepted'] == 2 ? 'No' : 'Pending')); ?></td>
									<td><?php echo $invitedata['Invitation']['ago']; ?></td>
									<td><?php echo $invitedata['User']['first_name'] . "&nbsp;" . $invitedata['User']['last_name']; ?></td>
									<td><?php echo ($invitedata['Invitation']['is_superadmin_approved'] == 1 ? 'Approved' : ($invitedata['Invitation']['is_superadmin_approved'] == 2 ? 'Rejected' : 'Waiting Approval')); ?></td>					
									<td>
										<?php
										if ($invitedata['Invitation']['is_superadmin_approved'] == 0) {
											echo $this->Html->link('<i class="glyphicon glyphicon-eye-open"></i>', 'javascript:void(0)', array('data-name' => $invitedata['Invitation']['email'], 'escape' => false, 'class' => 'btn btn-sm btn-default approve', 'title' => 'Approve/Reject Invitation Request', 'data-href' => $this->Html->url(array('controller' => 'users', 'action' => 'approveinvitation', base64_encode($invitedata['Invitation']['id'])))));
										}
										if ($invitedata['Invitation']['is_superadmin_approved'] == 0 && $invitedata['Invitation']['is_request_accepted'] == 0) {
											echo $this->Html->link('<i class="glyphicon glyphicon-share-alt"></i>', 'javascript:void(0)', array('data-name' => $invitedata['Invitation']['first_name'] . ' ' . $invitedata['Invitation']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default resend', 'title' => 'Resend Invitation', 'data-href' => $this->Html->url(array('controller' => 'users', 'action' => 'resendinvitation', base64_encode($invitedata['Invitation']['id'])))));
										}
										?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $invitedata['Invitation']['first_name'] . ' ' . $invitedata['Invitation']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Invitation', 'data-href' => $this->Html->url(array('controller' => 'users', 'action' => 'deleteinvitation', base64_encode($invitedata['Invitation']['id']))))); ?>
									</td> 
								</tr>
								<?php
							}
						} else {
							echo '<tr><td colspan="8"><div class="norecord">No Record Found</div></td></tr>';
						}
						?>		
                    </tbody>
                </table>
            </div> <!-- /widget-content -->
        </div> <!-- /widget -->
    </div>
    <center>
		<?php if ($this->Paginator->counter('{:pages}') > 1) { ?>
			<ul class="pagination">
				<?php
				echo $this->Paginator->prev(__('<<'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
				echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
				echo $this->Paginator->next(__('>>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
				?>
			</ul>
		<?php } ?>
	</center>
</div>
<!-- Modal Delete -->
<div id="DeleteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Delete Invitation</h3>
			</div>
			<div class="modal-body">
				<center>
					Are you sure want to delete invitation request send to "<strong>selected user</strong>"?
				</center>	
			</div>
			<div class="modal-footer">
				<center>
					<a href="#" class="btn btn-default" data-dismiss="modal">NO</a>
					<a href="#" class="btn btn-primary">Yes</a>
				</center>
			</div>
		</div>
	</div>
</div>

<div id="ApprovalActionModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Approve/Reject Invitation Request</h3>
			</div>
			<div class="modal-body">
				<center>
					Please approve or reject invitation request.
					<input type="hidden" name="userEmail" id="userEmail" />
				</center>	
			</div>
			<div class="modal-footer">
				<center>
					<a href="#" class="btn btn-default reject">Reject</a>
					<a href="#" class="btn btn-primary">Approve</a>
				</center>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="RejectActionModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo $this->Form->create('users', array('type' => 'POST', 'name' => 'UserApproveInvitation', 'id' => 'UserApproveInvitation', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Reject Invitation Request</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Comment<span class="star">*</span></span>
						<?php echo $this->Form->textarea('Invitation.rejected_comment', array('label' => false, 'div' => false, 'required' => false, 'rows' => 5,  'class' => 'form-control', 'placeholder' => 'Comment')); ?> 
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

<!-- Modal ResendModel -->
<div id="ResendModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Resend Invitation</h3>
			</div>
			<div class="modal-body">
				<center>
					Are you sure want to resend invitation email to "<strong>selected user</strong>"?
				</center>	
			</div>
			<div class="modal-footer">
				<center>
					<a href="#" class="btn btn-default" data-dismiss="modal">NO</a>
					<a href="#" class="btn btn-primary">Yes</a>
				</center>
			</div>
		</div>
	</div>
</div>

<!-- Modal Add Client -->
<?php echo $this->Element('super_admin/addInvitation'); ?>
<!-- Modal Edit Client -->

<script type="text/javascript">
	$(document).ready(function() {
		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});

		$(".approve").click(function(event) {
			event.preventDefault();
			$('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-body > center > #userEmail').val($(this).attr('data-name'));
			$('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#ApprovalActionModel').modal('show');
		});
		
		$(".reject").click(function(event) {
			event.preventDefault();
			var userEmail = $('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-body > center > #userEmail').val();
			var url = $('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href');
			$('#ApprovalActionModel').modal('hide');
			$('#UserApproveInvitation').attr('action', url);
			$("#InvitationRejectedComment").val("Your invitation request for (" + userEmail + ") has been rejected by our system administration. For more information, please inquire with your team leader. Thank you.");
			$('#RejectActionModel').modal('show');
		});
		
		$("#singlebutton").click(function(event){
			event.preventDefault();
			$("#UserApproveInvitation").submit();
		});
		
		$(".resend").click(function(event) {
			event.preventDefault();
			$('#ResendModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#ResendModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#ResendModel').modal('show');
		});

		$("#addInvitation").click(function(event) {
			event.preventDefault();
			$('#AddInvitationModel').modal('show');
		});
	});
</script>
