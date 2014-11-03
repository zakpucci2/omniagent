<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
	<div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-user"></i> My Team</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add Team Member', array('controller' => 'admins', 'action' => 'listregularadmins', 'admin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Team Member', 'id' => 'addTeamMember')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php echo $this->Form->create('admins', array('action' => 'myteam', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('User.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Team Member", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
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
                            <th><?php echo $this->Paginator->sort('User.full_name', 'Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('User.user_name', 'User Name'); ?></th>
							<th><?php echo $this->Paginator->sort('User.email', 'Email'); ?></th>
                            <th><?php echo $this->Paginator->sort('User.phone', 'Phone'); ?></th>
							<th><?php echo $this->Paginator->sort('AdminTeam.entry_ts', 'Date Joined'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $adminData) {
								?>
								<tr>
									<td>
										<?php
										if ($adminData['User']['profile_photo'] == '') {
											echo $this->Html->image('profile_photo/150x150.png', array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
										} else {
											echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto150x150")  . '/' . $adminData['User']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
										}
										?>
									</td>
									<td><?php echo ucfirst($adminData['User']['full_name']); ?></td>
									<td><?php echo $adminData['User']['user_name']; ?>@<?php echo Configure::read('SITE_EMAIL.Email'); ?></td>
									<td><?php echo $adminData['User']['email']; ?></td>
									<td><?php echo $adminData['User']['phone']; ?></td>
									<td><?php echo $adminData['AdminTeam']['ago']; ?></td>
									<td> 
										<?php // echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'admins', 'action' => 'editteamuser', base64_encode($adminData['User']['id'])), array('escape' => false, 'class' => 'btn btn-sm btn-default editRowTeamUser', 'title' => 'Edit Team User')); ?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void()', array('data-username' => $adminData['User']['first_name'] . " " . $adminData['User']['last_name'], 'data-href' => $this->Html->url(array('controller' => 'admins', 'action' => 'deleteteamuser', base64_encode($adminData['AdminTeam']['id']))), 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Remove Team User')); ?> 
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
				<h3>Delete Team Member</h3>
			</div>
			<div class="modal-body">
				<center>
					Are you sure want to delete "<strong>selected user</strong>" from team list?
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
<script type="text/javascript">
	$(document).ready(function() {
		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-username'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});

		$("#addTeamUser").click(function(event) {
			event.preventDefault();
			$('#AddTeamUserModel').modal('show');
		});
	});
</script>
