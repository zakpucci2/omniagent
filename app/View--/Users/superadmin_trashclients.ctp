<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-trash"></i> Trash Client(Users)</strong></div>
			<div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-user"></i> Client (Users)', array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'List Clients')); ?>
			</div>
        </div>
    </div>
    <br>
	<!--
		<center><h2><span class="glyphicon glyphicon"></span>List Users</h2><br>
			<form class="control-group" role="search">
			<div class="input-group">
				<input type="text" class="form-control" placeholder="Search Messages" />
				<span class="input-group-btn">
					<button class="btn btn-primary" type="button">
					Search!</button>
				</span>
			</div>
			</form>
		</center> 
	-->

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
											echo $admin = $clientdata['AssociatedAdmin']['first_name'] . "&nbsp;" . $clientdata['AssociatedAdmin']['last_name'];
											$adminId = $clientdata['AssociatedAdmin']['id'];
											?>
											<?php
										} else {
											?>
											<?php
										}
										?>
									</td>
									<td> 
										<?php echo $this->Html->link('RESTORE', 'javascript:void(0)', array('data-name' => $clientdata['User']['first_name'] . ' ' . $clientdata['User']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-primary act_go', 'title' => 'Restore Client(user)', 'data-href' => $this->Html->url(array('controller' => 'users', 'action' => 'restoreclient', base64_encode($clientdata['User']['id']))))); ?>
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
<!-- Modal Trash -->
<div id="GoModal" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Restore Client</h3>
			</div>
			<div class="modal-body">
				<center>
					Are you sure want to restore "<strong>selected user</strong>" from Clients list?
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
<?php echo $this->Element('super_admin/addAdmin'); ?>
<!-- Modal Edit Client -->
<?php echo $this->Element('super_admin/editAdmin'); ?>

<script type="text/javascript">
	$(document).ready(function() {
		//$('.checkbox').prettyCheckable();        
		$(".block").click(function(event) {
			event.preventDefault();
			if ($(this).children('i').hasClass('block-icon-default')) {
				var bl = 'unblock';
				var title = 'Unblock Admin';
			} else {
				var bl = 'block';
				var title = 'Block Admin';
			}
			$message = 'Are you sure want to ' + bl + ' "<strong>' + $(this).attr('data-username') + '</strong>" from administrator list?';
			$('#BlockUnblock > div.modal-dialog > div.modal-content > div.modal-body > center').html($message);
			$('#BlockUnblock > div.modal-dialog > div.modal-content > div.modal-header h3').html(title);
			$('#BlockUnblock > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('href'));
			$('#BlockUnblock').modal('show');
		});

		$(".act_go").click(function(event) {
			event.preventDefault();
			$('#GoModal > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#GoModal > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#GoModal').modal('show');
		});

		$("#addAdmin").click(function(event) {
			event.preventDefault();
			$('#AddAdminModel').modal('show');
		});
	});
</script>
