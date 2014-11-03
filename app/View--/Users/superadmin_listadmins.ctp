<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div>
	<br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-globe"></i> Administrators</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add Admin', array('controller' => 'users', 'action' => 'addadmin', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Add Client', 'id' => 'addAdmin')); ?>
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i> Trash', array('controller' => 'users', 'action' => 'trashadmins', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-md btn-danger', 'title' => 'Deleted Administrators')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php echo $this->Form->create('users', array('action' => 'listadmins', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('User.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Administrators", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
		?>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">
                Search!</button>
        </span>
    </div>
	<?php echo $this->Form->end(); ?>
	<!--<center><h2><span class="glyphicon glyphicon"></span>List Users</h2><br>
	<form class="control-group" role="search">
	<div class="input-group">
	<input type="text" class="form-control" placeholder="Search Messages" />
	<span class="input-group-btn">
		<button class="btn btn-primary" type="button">Search!</button>
	</span>
	</div>
	</form>
	</center>-->
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
                            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
							<th><?php echo $this->Paginator->sort('is_trusted_admin', 'Admin Type'); ?></th>
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
									<td><?php echo $clientdata['User']['user_name']; ?>@<?php echo Configure::read("SITE_EMAIL.Email")?></td>
									<td><?php echo $clientdata['User']['email']; ?></td>
									<td><?php echo ((isset($clientdata['User']['is_trusted_admin']) && $clientdata['User']['is_trusted_admin'] == 1) ? "Trusted" : "Regular" ); ?></td>
									<td> 
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'users', 'action' => 'editadmin', base64_encode($clientdata['User']['id'])), array('escape' => false, 'class' => 'btn btn-sm btn-default editRow', 'title' => 'Edit Admin')); ?>
										<?php
										if ($clientdata['User']['is_blocked'] == 0) {
											echo $this->Html->link('<b class="glyphicon glyphicon-ban-circle"></b>', array('controller' => 'users', 'action' => 'blockadmin', base64_encode($clientdata['User']['id']), 'block'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default block', 'title' => 'Block Admin'));
										} else {
											echo $this->Html->link('<i class="glyphicon glyphicon-ban-circle block-icon-default"></i>', array('controller' => 'users', 'action' => 'blockadmin', base64_encode($clientdata['User']['id']), 'unblock'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default block', 'title' => 'Unblock Admin'));
										}
										?> 
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $clientdata['User']['first_name'] . ' ' . $clientdata['User']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Admin', 'data-href' => $this->Html->url(array('controller' => 'users', 'action' => 'deleteadmin', base64_encode($clientdata['User']['id'])))));
										?>
										<?php
										if ($clientdata['User']['is_trusted_admin'] == 1) {
											echo $this->Html->link($this->Html->image('lock_open.png'), array('controller' => 'users', 'action' => 'trusted_untrusted_admin', base64_encode($clientdata['User']['id']), 'close'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default trusted', 'title' => 'Make regular admin'));
										} else {
											echo $this->Html->link($this->Html->image('lock.png'), array('controller' => 'users', 'action' => 'trusted_untrusted_admin', base64_encode($clientdata['User']['id']), 'open'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default trusted open1', 'title' => 'Make trusted admin'));
										}
										?>
									</td> 
								</tr>
								<?php
							}
						} else {
							echo '<tr><td colspan="6"><div class="norecord">No Record Found</div></td></tr>';
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
                <h3>Block Admin</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to <span>block</span> "<strong>selected user</strong>" from administrator list?	
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
<!-- Modal Block/Unblock Client -->
<div id="TrustedUnTrusted" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Trusted/Untrusted Admin</h3>
			</div>
			<div class="modal-body">
				<center>
					Are you sure want to <span>trusted</span> "<strong>selected user</strong>" from administrator list?	
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
					Are you sure want to delete "<strong>selected user</strong>" from administrator list?
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

		$(".trusted").click(function(event) {
			event.preventDefault();
			if ($(this).hasClass('open1')) {
				var title = 'Trusted (Full Privileges)';
			} else {
				var title = 'Regular (Less Privileges)';
			}
			$message = 'Are you sure want to make <strong>' + $(this).attr('data-username') + '</strong> as <strong>' + title + '</strong> Admin ?';
			$('#TrustedUnTrusted > div.modal-dialog > div.modal-content > div.modal-body > center').html($message);
			//$('#BlockUnblock > div.modal-dialog > div.modal-content > div.modal-header h3').html(title);
			$('#TrustedUnTrusted > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('href'));
			$('#TrustedUnTrusted').modal('show');
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

		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});

		$("#addAdmin").click(function(event) {
			event.preventDefault();
			$('#AddAdminModel').modal('show');
		});
	});
</script>
