<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left">
				<strong class="lead"><i class="glyphicon glyphicon-user"></i> <?php
					if (isset($favorites) && $favorites != '') {
						echo "My Favorite Clients(Users)";
					} else {
						echo "Clients (Users)";
					}
					?>
				</strong>
			</div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add Client', array('controller' => 'users', 'action' => 'addclient', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Client', 'id' => 'addClient')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php
	if (isset($favorites) && $favorites != '') {
		echo $this->Form->create('admins', array('action' => 'listclients/favorites', 'type' => "GET"), array('class' => "control-group"));
	} else {
		echo $this->Form->create('admins', array('action' => 'listclients', 'type' => "GET"), array('class' => "control-group"));
	}
	?>
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
							<th>&nbsp;</th>
							<th>Avatar</th>
                            <th><?php echo $this->Paginator->sort('full_name', 'Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('user_name', 'User Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('business_name', 'Business Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('is_approved_client', 'Superadmin Approved?'); ?></th>
                            <th><?php echo $this->Paginator->sort('email', 'Email'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $clientdata) {
								if (isset($clientdata['AdminUser']['is_favorite']) && ($clientdata['AdminUser']['is_favorite'] == 0)) {
									$display_star = "";
									$display_star_yellow = "style='display:none;'";
								} else {
									$display_star = "style='display:none;'";
									$display_star_yellow = "";
								}
								?>
								<tr>
									<td>
										<a title="Mark as Favorite" href="javascript:void(0);" <?php echo $display_star; ?> class="star-empty star-silver" id="star_<?php echo $clientdata['AdminUser']['id']; ?>" data-Id="<?php echo $clientdata['AdminUser']['id']; ?>" data-status="1" data-href="<?php echo $this->params->base . '/Admins/ajax_set_favorite.json' ?>"></a>
										<a title="Mark as Not Favorite" href="javascript:void(0);" <?php echo $display_star_yellow; ?> class="star-empty star-yellow" id="star-yellow_<?php echo $clientdata['AdminUser']['id']; ?>" data-Id="<?php echo $clientdata['AdminUser']['id']; ?>" data-status="0" data-href="<?php echo $this->params->base . '/Admins/ajax_set_favorite.json' ?>"></a>   
										<?php echo $this->Html->image('loader16.GIF', array('id' => 'img' . $clientdata['AdminUser']['id'], 'style' => 'display:none;', 'class' => 'star-loader')); ?>
									</td>
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
									<td><?php echo $clientdata['User']['user_name']; ?></td>
									<td><?php echo $clientdata['User']['business_name']; ?></td>
									<td><?php echo ($clientdata['User']['is_approved_client'] == 1 ? 'Approved' : ($clientdata['User']['is_approved_client'] == 2 ? 'Rejected' : 'Waiting Approval')); ?></td>
									<td><?php echo $clientdata['User']['email']; ?></td>
									<td>
										<?php $currentUserSession = $this->Session->read('User'); ?>
										<?php if($currentUserSession['User']['is_trusted_admin'] == 1) { 
											echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'admins', 'action' => 'editclient', base64_encode($clientdata['User']['id'])), array('escape' => false, 'class' => 'btn btn-sm btn-default editRowClient', 'title' => 'Edit Client')); 
											if ($clientdata['User']['is_blocked'] == 0) {
												echo $this->Html->link('<b class="glyphicon glyphicon-ban-circle"></b>', array('controller' => 'admins', 'action' => 'blockclient', base64_encode($clientdata['User']['id']), 'block'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default block', 'title' => 'Block Client'));
											} else {
												echo $this->Html->link('<i class="glyphicon glyphicon-ban-circle block-icon-default"></i>', array('controller' => 'admins', 'action' => 'blockclient', base64_encode($clientdata['User']['id']), 'unblock'), array('data-username' => $clientdata['User']['user_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default block', 'title' => 'Unblock Client'));
											} 
											echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $clientdata['User']['first_name'] . ' ' . $clientdata['User']['last_name'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Client(User)', 'data-href' => $this->Html->url(array('controller' => 'admins', 'action' => 'deleteclient', base64_encode($clientdata['User']['id'])))));
										}
										?>
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
<!-- Modal Block/Unblock Client -->
<div id="BlockUnblock" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Block Client</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to <span>block</span> "<strong>selected user</strong>" from client list?	
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
					Are you sure want to delete "<strong>selected user</strong>" from clients list?
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
<?php echo $this->Element('admin/addClient'); ?>
<!-- Modal Edit Client -->
<?php echo $this->Element('admin/editClient'); ?>
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

		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-username'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});

		$("#addClient").click(function(event) {
			event.preventDefault();
			$('#AddClientModel').modal('show');
		});
	});

	$(document).ready(function() {
		$('.star-empty').click(function(e) {
			var star_id = $(this).attr("id");
			var split = star_id.split('_');
			var id = split[1];
			var url = $(this).attr('data-href');
			var adminuser_id = $(this).attr('data-Id');
			var status = $(this).attr('data-status');
			loader_setting(id, status, 'start');
			var request = $.ajax({
				url: url,
				type: "POST",
				cache: false,
				data: "adminuser_id=" + adminuser_id + "&status=" + status
			});

			request.done(function(data) {
				if (data.result.status === 'success') {
					loader_setting(id, status, 'success');
					//flash('success',data.result.data.message,'Success');
				} else {
					loader_setting(id, status, 'error');
					//flash('error',data.result.data.message,'Error');
				}
			});

			request.fail(function() {
				loader_setting(id, status, 'error');
				flash('error', 'Please try again or refresh your browser.', 'Server Error');
			});
		});
	});

	function loader_setting(starId, status, message) {
		if (message === 'start')
		{
			$("#star_" + starId).hide();
			$("#star-yellow_" + starId).hide();
			$("#img" + starId).show();
		}
		else if (message === 'success')
		{
			loader_check(starId, status);
		}
		else if (message === 'error')
		{
			if (status === '1')
				status = '0';
			else
				status = '1';
			loader_check(starId, status);
		}
	}

	function loader_check(starId, status) {
		$("#img" + starId).hide();
		if (status === '1')
		{
			$("#star_" + starId).hide();
			$("#star-yellow_" + starId).show();
		}
		else
		{
			$("#star_" + starId).show();
			$("#star-yellow_" + starId).hide();
		}
	}
</script>