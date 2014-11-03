<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-ok"></i>My Invitations</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> New Invite', array('controller' => 'admins', 'action' => 'addinvite', 'admin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Invitation', 'id' => 'addInvitation')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php echo $this->Form->create('admins', array('action' => 'dashboard', 'type' => "GET"), array('class' => "control-group")); ?>
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
                            <th><?php echo $this->Paginator->sort('full_name', 'Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
                            <th><?php echo $this->Paginator->sort('request_token', 'Unique Token'); ?></th>
                            <th><?php echo $this->Paginator->sort('is_request_accepted', 'User Accepted'); ?></th>
                            <th><?php echo $this->Paginator->sort('modified', 'Invite On'); ?></th>
							<th><?php echo $this->Paginator->sort('is_superadmin_approved', 'Superadmin Approved?'); ?></th>
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
									<td><?php echo ($invitedata['Invitation']['is_request_accepted'] == 0 ? 'No' : 'Yes'); ?></td>
									<td><?php echo $invitedata['Invitation']['ago']; ?></td>
									<td><?php echo ($invitedata['Invitation']['is_superadmin_approved'] == 1 ? 'Approved' : ($invitedata['Invitation']['is_superadmin_approved'] == 2 ? 'Rejected' : 'Waiting Approval')); ?></td>
									<td>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $invitedata['Invitation']['first_name'] . ' ' . $invitedata['Invitation']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Invitation', 'data-href' => $this->Html->url(array('controller' => 'admins', 'action' => 'deleteinvitation', base64_encode($invitedata['Invitation']['id']))))); ?>
										<?php
										if ($invitedata['Invitation']['is_request_accepted'] == 0) {
											echo $this->Html->link('<i class="glyphicon glyphicon-share-alt"></i>', 'javascript:void(0)', array('data-name' => $invitedata['Invitation']['first_name'] . ' ' . $invitedata['Invitation']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default resend', 'title' => 'Resend Invitation', 'data-href' => $this->Html->url(array('controller' => 'admins', 'action' => 'resendinvitation', base64_encode($invitedata['Invitation']['id'])))));
										}
										?>
									</td> 
								</tr>
								<?php
							}
						} else {
							echo '<tr><td colspan="7"><div class="norecord">No Record Found</div></td></tr>';
						}
						?>		
                    </tbody>
                </table>

            </div> <!-- /widget-content -->

        </div> <!-- /widget -->
    </div>
    <center><?php if ($this->Paginator->counter('{:pages}') > 1) { ?>
			<ul class="pagination">
				<?php
				echo $this->Paginator->prev(__('<<'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
				echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
				echo $this->Paginator->next(__('>>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
				?>
			</ul>
		<?php } ?></center>

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
					Are you sure want to resend invitation mail to "<strong>selected user</strong>"?
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
<?php
echo $this->Element('admin/addInvitation');
?>
<!-- Modal Edit Client -->
<script type="text/javascript">
	$(document).ready(function() {

		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
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
