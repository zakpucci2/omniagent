<?php $this->Html->css('bootstrap/bootstrap-editable', null, array('inline' => false)); ?>
<?php $this->Html->script('bootstrap/bootstrap-editable', array('inline' => false)); ?>
<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-user"></i> Clients(Users)</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add Client', array('controller' => 'users', 'action' => 'addclient', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Add Client', 'id' => 'addClient')); ?>
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i> Trash', array('controller' => 'users', 'action' => 'trashclients', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-md btn-danger', 'title' => 'Deleted Users')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php echo $this->Form->create('users', array('action' => 'listclients', 'type' => "GET"), array('class' => "control-group")); ?>
	<div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('User.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Clients", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
		?>
		<span class="input-group-btn">
			<button class="btn btn-primary" type="submit">
				Search!</button>
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
							<th>Avatar</th>
                            <th><?php echo $this->Paginator->sort('full_name', 'Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('user_name', 'User Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('business_name', 'Business Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('invited_by', 'Invited By'); ?></th>
							<th><?php echo $this->Paginator->sort('is_approved_client', 'Is Approved'); ?></th>
							<th><?php echo $this->Paginator->sort('associated_admin_id', 'Admin'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $clientdata) {
								?>
								<tr>
									<td>
										<?php
										if ($clientdata['User']['profile_photo'] == '') {
											echo $this->Html->image('profile_photo/150x150.png', array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
										} else {
											echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto150x150")  . '/' . $clientdata['User']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
										}
										?>
									</td>
									<td><?php echo ucfirst($clientdata['User']['full_name']); ?></td>
									<td><?php echo $clientdata['User']['user_name']; ?>@<?php echo Configure::read("SITE_EMAIL.Email"); ?></td>
									<td><?php echo $clientdata['User']['business_name']; ?></td>
									<td><?php echo $clientdata['InvitedByUser']['first_name'] . "&nbsp;" . $clientdata['InvitedByUser']['last_name']; ?></td>
									<td><?php echo ($clientdata['User']['is_approved_client'] == 1 ? 'Approved' : ($clientdata['User']['is_approved_client'] == 2 ? 'Rejected' : 'Waiting Approval')); ?></td>
									<td>
										<?php
										$admin = '';
										$adminId = '';
										if (!empty($clientdata['AssociatedAdmin'])) {
											$admin = $clientdata['AssociatedAdmin']['first_name'] . "&nbsp;" . $clientdata['AssociatedAdmin']['last_name'];
											$adminId = $clientdata['AssociatedAdmin']['id'];
											?>
											<a href="javascript: void(0);" id="<?php echo $clientdata['User']['id']; ?>" data-type="select" data-value="<?php echo @adminId; ?>" data-name="<?php echo $clientdata['User']['id']; ?>" data-pk="<?php echo $clientdata['User']['id']; ?>" data-url="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'assignAssociatedAdmin', 'superadmin' => false)); ?>" data-original-title="Assign Admin"><?php echo @$admin; ?></a>
											<?php
										} else {
											?>
											<a href="javascript: void(0);" id="<?php echo $clientdata['User']['id']; ?>" data-type="select" data-name="<?php echo $clientdata['User']['id']; ?>" data-pk="<?php echo $clientdata['User']['id']; ?>" data-url="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'assignAssociatedAdmin', 'superadmin' => false)); ?>" data-original-title="Assign Admin">Assign Admin</a>
											<?php
										}
										?>
									</td>
									<td> 
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'users', 'action' => 'editclient', base64_encode($clientdata['User']['id'])), array('escape' => false, 'class' => 'btn btn-sm btn-default editRowClient', 'title' => 'Edit Client')); ?>
										<?php
										if ($clientdata['User']['is_blocked'] == 0) {
											echo $this->Html->link('<b class="glyphicon glyphicon-ban-circle"></b>', array('controller' => 'users', 'action' => 'blockclient', base64_encode($clientdata['User']['id']), 'block'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default block', 'title' => 'Block Client'));
										} else {
											echo $this->Html->link('<i class="glyphicon glyphicon-ban-circle block-icon-default"></i>', array('controller' => 'users', 'action' => 'blockclient', base64_encode($clientdata['User']['id']), 'unblock'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default block', 'title' => 'Unblock Client'));
										}
										?>
										<?php
										if ($clientdata['User']['is_approved_client'] == 1) {
											echo $this->Html->link('<i class="glyphicon glyphicon-remove-circle"></i>', array('controller' => 'users', 'action' => 'approveclient', base64_encode($clientdata['User']['id']), 'reject', 'superadmin' => true), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default approve', 'title' => 'Reject Client'));
										} elseif ($clientdata['User']['is_approved_client'] == 2) {
											echo $this->Html->link('<i class="glyphicon glyphicon-ok-circle"></i>', array('controller' => 'users', 'action' => 'approveclient', base64_encode($clientdata['User']['id']), 'approve', 'superadmin' => true), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default approve', 'title' => 'Approve Client'));
										} else {
											echo $this->Html->link('<i class="glyphicon glyphicon-eye-open"></i>', 'javascript:void(0)', array('data-name' => base64_encode($clientdata['User']['id']), 'escape' => false, 'class' => 'btn btn-sm btn-default approveaction', 'title' => 'Approve/Reject Invitation Request', 'data-href' => $this->Html->url(array('controller' => 'users', 'action' => 'approveclient', base64_encode($clientdata['User']['id']), 'superadmin' => true))));
										}
										?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $clientdata['User']['first_name'] . ' ' . $clientdata['User']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Client(User)', 'data-href' => $this->Html->url(array('controller' => 'users', 'action' => 'deleteclient', base64_encode($clientdata['User']['id']))))); ?> 
									</td> 
								</tr>
								<script type="text/javascript">
									$(document).ready(function() {
										$('#<?php echo $clientdata['User']['id']; ?>').editable({
											value: [<?php echo @$adminId ?>],
											separator: ',',
											source: [<?php echo $admins; ?>]
										});
									});
								</script>
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
<!-- Modal Block/Unblock Client -->
<div id="BlockUnblock" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Block Client</h3>
            </div>
            <div class="modal-body">
                <center>Are you sure want to <span>block</span> "<strong>selected user</strong>" from client list?</center>
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
				<h3>Approve/Reject Client</h3>
			</div>
			<div class="modal-body">
				<center>
					Please approve or reject client.
					<input type="hidden" name="userAction" id="userAction" />
				</center>	
			</div>
			<div class="modal-footer">
				<center>
					<a href="#" class="btn btn-default btnReject">Reject</a>
					<a href="#" class="btn btn-primary btnApprove">Approve</a>
				</center>
			</div>
		</div>
	</div>
</div>
<!-- Modal Approve/Reject Client -->
<div id="ApproveRejectModal" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Approve/Reject Client</h3>
            </div>
            <div class="modal-body">
                <center>Are you sure want to <span>reject</span> "<strong>selected user</strong>" from client list?</center>
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
						<!-- <span class="input-group-addon">Comment<span class="star">*</span></span> -->
						<?php // echo $this->Form->textarea('Invitation.rejected_comment', array('label' => false, 'div' => false, 'required' => false, 'rows' => 5,  'class' => 'form-control', 'placeholder' => 'Comment')); ?> 
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
<!-- Modal Delete -->
<div id="DeleteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Delete Admin</h3>
			</div>
			<div class="modal-body">
				<center>
					Are you sure want to delete "<strong>selected user</strong>" from user list?
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
<?php echo $this->Element('super_admin/addClient'); ?>
<!-- Modal Edit Client -->
<?php echo $this->Element('super_admin/editClient'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		//$('.checkbox').prettyCheckable();        
		$(".block").click(function(event) {
			event.preventDefault();
			if ($(this).children('i').hasClass('block-icon-default')) {
				var bl = 'unblock';
				var title = 'Unblock Client';
			} else {
				var bl = 'block';
				var title = 'Block Client';
			}
			$message = 'Are you sure want to ' + bl + ' "<strong>' + $(this).attr('data-username') + '</strong>" from client list?';
			$('#BlockUnblock > div.modal-dialog > div.modal-content > div.modal-body > center').html($message);
			$('#BlockUnblock > div.modal-dialog > div.modal-content > div.modal-header h3').html(title);
			$('#BlockUnblock > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('href'));
			$('#BlockUnblock').modal('show');
		});

		$(".approve").click(function(event) {
			event.preventDefault();
			if ($(this).children('i').hasClass('glyphicon-ok-circle')) {
				var bl = 'approve';
				var title = 'Approve Client';
			} else {
				var bl = 'reject';
				var title = 'Reject Client';
			}
			$message = 'Are you sure want to ' + bl + ' "<strong>' + $(this).attr('data-username') + '</strong>" from client list?';
			$('#ApproveRejectModal > div.modal-dialog > div.modal-content > div.modal-body > center').html($message);
			$('#ApproveRejectModal > div.modal-dialog > div.modal-content > div.modal-header h3').html(title);
			$('#ApproveRejectModal > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('href'));
			$('#ApproveRejectModal').modal('show');
		});
		
		$(".approveaction").click(function(event) {
			event.preventDefault();
			$('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-body > center > #userAction').val($(this).attr('data-name'));
			$('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#ApprovalActionModel').modal('show');
		});
		
		$(".btnReject").click(function(event) {
			event.preventDefault();
			var url = $('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href')+'/reject';
			$('#ApprovalActionModel').modal('hide');
			$('#UserApproveInvitation').attr('action', url);
			// $("#InvitationRejectedComment").val("Your new client request for (" + userEmail + ") has been rejected by our system administration. For more information, please inquire with your team leader. Thank you.");
			$("#UserApproveInvitation").submit();
		});

		$(".btnApprove").click(function(event) {
			event.preventDefault();
			var url = $('#ApprovalActionModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href')+'/approve';
			$('#ApprovalActionModel').modal('hide');
			$('#UserApproveInvitation').attr('action', url);
			$("#UserApproveInvitation").submit();
		});

		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});

		$("#addClient").click(function(event) {
			event.preventDefault();
			$('#AddClientModel').modal('show');
		});
	});
</script>
