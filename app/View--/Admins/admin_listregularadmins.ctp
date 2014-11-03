<style>.block-icon-default { color: #E34C3B !important; }</style>
<div><br>
	<div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-user"></i> Regular Admin(s)</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-user"></i> My Team', array('controller' => 'admins', 'action' => 'myteam', 'admin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'My Team', 'id' => 'myTeam')); ?>
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
            <div class="widget-content" id="listingContent">
				<table class="table table-striped table-bordered">
                    <thead>
                        <tr>
							<th>Avatar</th>
                            <th><?php echo $this->Paginator->sort('User.full_name', 'Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('User.user_name', 'User Name'); ?></th>
							<th><?php echo $this->Paginator->sort('User.email', 'Email'); ?></th>
                            <th><?php echo $this->Paginator->sort('User.phone', 'Phone'); ?></th>
							<th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							$counter = 0;
							foreach ($data as $adminData) {
								?>
								<tr>
									<td>
										<?php
										if ($adminData['User']['profile_photo'] == '') {
											echo $this->Html->image('profile_photo/150x150.png', array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
										} else {
											echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto150x150") . '/' . $adminData['User']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
										}
										?>
									</td>
									<td><?php echo ucfirst($adminData['User']['full_name']); ?></td>
									<td><?php echo $adminData['User']['user_name']; ?>@<?php echo Configure::read('SITE_EMAIL.Email'); ?></td>
									<td><?php echo $adminData['User']['email']; ?></td>
									<td><?php echo $adminData['User']['phone']; ?></td>
									<td> 
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i>', 'javascript:void()', array('data-username' => $adminData['User']['full_name'], 'data-name' => base64_encode($adminData['User']['id']), 'escape' => false, 'class' => 'btn btn-sm btn-default addTeamMember', 'title' => 'Add Team Member')); ?> 
									</td> 
								</tr>
								<?php
								$counter++;
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
<div id="AddConfirmModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Release Request</h3>
			</div>
			<div class="modal-body">
				<center>
					"<strong>selected user</strong>" is currently associated with another Team Leader. Are you sure want to add "<strong>selected user</strong>" to your team? Please send release request to current Team Leader.<br />
					<div class="input-group range">
						<span class="input-group-addon">Days required?</span>
						<input type="range" id="UserDaysRequired" class="form-control" name="UserDaysRequired" min="1" max="30" value="10" />
						<output id="completedStatusValue">10</output>
					</div><br>
				</center>
			</div>
			<div class="modal-footer">
				<center>
					<a href="#" class="btn btn-default cancelAction">NO</a>
					<a href="#" class="btn btn-primary addAction">Yes</a>
				</center>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".addTeamMember").click(function() {
			var postData = {};
			postData.userId = $(this).attr('data-name');
			var currUserName = $(this).attr('data-username');
			$('body').modalmanager('loading');
			$.ajax({
				url: '<?php echo $this->Html->url(array('controller' => 'admins', 'action' => 'checkUserTeam', 'admin' => false)); ?>',
				type: "post",
				dataType: "json",
				async: true,
				data: postData
			}).done(function(resUserData) {
				if (resUserData.userData === false) {
					$.ajax({
						url: '<?php echo $this->Html->url(array('controller' => 'admins', 'action' => 'addmyteam', 'admin' => false)); ?>',
						type: "post",
						dataType: "json",
						async: true,
						data: postData
					}).done(function(resInsertData) {
						if (resInsertData.resMgs === true) {
							updateTableContent();
						} else {
							<?php echo "flash('error', 'Regular admin was not added into your team.', 'Error');"; ?>
							$('body').modalmanager('removeLoading');
						}
					});
				} else {
					$('body').modalmanager('removeLoading');
					$('#AddConfirmModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html(currUserName);
					$('#AddConfirmModel > div.modal-dialog > div.modal-content > div.modal-header > h3').html('Send Release Request to "' + resUserData.userData.TeamLeader.first_name + " " + resUserData.userData.TeamLeader.last_name + '"');
					$('#AddConfirmModel').modal('show');
					$("#UserDaysRequired").val('10');
					$("#completedStatusValue").html('10');
					$(".cancelAction").click(function() {
						$('#AddConfirmModel').modal('hide');
					});
					$(".addAction").click(function() {
						$('#AddConfirmModel').modal('hide');
						$('body').modalmanager('loading');
						var requestPostData = {};
						requestPostData.userId = resUserData.userData.AdminTeam.user_id;
						requestPostData.currentTeamLeadId = resUserData.userData.AdminTeam.admin_id;
						requestPostData.daysRequired = $('#UserDaysRequired').val();
						requestPostData.messageBody = 'Hello ' + resUserData.userData.TeamLeader.first_name + " " + resUserData.userData.TeamLeader.last_name + ', I need ' + resUserData.userData.User.full_name + ' for my current project. Could you please release him for ' + ($('#UserDaysRequired').val()) + ' days.';
						$.ajax({
							url: '<?php echo $this->Html->url(array('controller' => 'admins', 'action' => 'release_request', 'admin' => true)); ?>',
							type: "post",
							dataType: "json",
							async: false,
							data: requestPostData
						}).done(function(requestInsertData) {
							if (requestInsertData.resMgs === true) {
								<?php echo "flash('info', 'Release request sent to Team Leader successfully.', 'Information');"; ?>
							} else {
								<?php echo "flash('error', 'Release request was not sent to Team Leader.', 'Error');"; ?>
							}
							$('body').modalmanager('removeLoading');
						});
					});
				}
			});
		});

		$("#UserDaysRequired").change(function() {
			$("#completedStatusValue").html(this.value);
		});
	});

	function updateTableContent() {
		$.ajax({
			url: '<?php echo $this->Html->url(array('controller' => 'admins', 'action' => 'updated_team_list', 'admin' => false)); ?>',
			dataType: "html",
			async: true
		}).done(function(resHTMLData) {
			if (resHTMLData != "") {
				$("#listingContent").html(resHTMLData);
				<?php echo "flash('success', 'Regular admin added into your team successfully', 'Success');"; ?>
				$('body').modalmanager('removeLoading');
			}
		});
	}
</script>