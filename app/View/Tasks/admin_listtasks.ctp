<?php echo $this->Html->css('jquery.autocomplete'); ?>
<?php echo $this->Html->script('jquery.autocomplete.min.js'); ?>
<style>
    .block-icon-default { color: #E34C3B !important; }
	.acResults {
		z-index: 5000;
	}
	.acResults ul li {
		margin: 0px;
		padding: 2px 5px;
		cursor: pointer;
		display: block;
		font: menu;
		overflow: hidden;
		color: #333;
	}
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-time"></i>Task</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add Task', array('controller' => 'tasks', 'action' => 'addtask', 'admin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Add User Task', 'id' => 'addUserTask')); ?>
				<?php // echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i> Trash', array('controller' => 'tasks', 'action' => 'trashtasks', 'admin' => true), array('escape' => false, 'class' => 'btn btn-md btn-danger', 'title' => 'Deleted UserTasks')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php echo $this->Form->create('tasks', array('action' => 'listtasks', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('UserTask.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Task!", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
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
					<colgroup>
						<col width="30%" />
						<col width="22%" />
						<col width="22%" />
						<col width="15%" />
						<col width="10%" />
					</colgroup>
                    <thead>
                        <tr>
							<th><?php echo $this->Paginator->sort('UserTask.title', 'Task Title'); ?></th>
                            <th><?php echo $this->Paginator->sort('UserTask.client_id', 'Assigned To'); ?></th>
							<th><?php echo $this->Paginator->sort('UserTask.status_completed', 'Status'); ?></th>
							<th><?php echo $this->Paginator->sort('UserTask.deadline', 'Deadline'); ?></th>
                            <!-- <th><?php // echo $this->Paginator->sort('UserTask.created', 'Created On'); ?></th>
							<th><?php // echo $this->Paginator->sort('UserTask.modified', 'Last Modified On'); ?></th> -->
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $user_task_data) {
								?>
								<tr>
									<td><?php echo $user_task_data['UserTask']['task_title']; ?></td>
									<td><?php echo $user_task_data['Client']['first_name'] . " " . $user_task_data['Client']['last_name']; ?></td>
									<td>
										<div class="progress progress-striped">
											<div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<?php echo $user_task_data['UserTask']['status_completed']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $user_task_data['UserTask']['status_completed']; ?>%">
												<center><?php echo $user_task_data['UserTask']['status_completed']; ?>%</center>
											</div>
										</div>
									</td>
									<td><?php echo $user_task_data['UserTask']['deadline_datetime']; ?></td>
									<!-- <td><?php // echo $user_task_data['UserTask']['created']; ?></td>
									<td><?php // echo $user_task_data['UserTask']['modified']; ?></td> -->
									<td class="td-actions">
										<!-- <?php // echo $this->Html->link('<i class="glyphicon glyphicon-eye-open"></i>', array('controller' => 'tasks', 'action' => 'view_task', base64_encode($user_task_data['UserTask']['id']), 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-xs btn-default viewUserTaskRow', 'title' => 'View UserTask Detail')); ?> -->
										<?php 
										if($user_task_data['UserTask']['is_completed'] == 0) {
											echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'tasks', 'action' => 'edittask', base64_encode($user_task_data['UserTask']['id']), 'admin' => true), array('escape' => false, 'class' => 'btn btn-xs btn-primary editUserTaskRow', 'title' => 'Edit UserTask')); 
										} 
										?>
										<?php 
										if($user_task_data['UserTask']['is_completed'] == 1) {
											echo $this->Html->link('<i class="glyphicon glyphicon-search"></i>', array('controller' => 'tasks', 'action' => 'view_task', base64_encode($user_task_data['UserTask']['id']), 'admin' => true), array('escape' => false, 'class' => 'btn btn-xs btn-primary viewUserTaskRow', 'title' => 'View UserTask')); 
										} 
										?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $user_task_data['UserTask']['task_title'], 'escape' => false, 'class' => 'btn btn-xs btn-danger delete', 'title' => 'Delete Task', 'data-href' => $this->Html->url(array('controller' => 'tasks', 'action' => 'delete_task', base64_encode($user_task_data['UserTask']['id']), 'admin' => true)))); ?>
										<?php 
										if($user_task_data['UserTask']['is_completed'] == 0) {
											echo $this->Html->link('<i class="glyphicon glyphicon-ok"></i>', 'javascript:void(0)', array('data-name' => $user_task_data['UserTask']['task_title'], 'escape' => false, 'class' => 'btn btn-xs btn-success markComplete', 'title' => 'Task Complete', 'data-href' => $this->Html->url(array('controller' => 'tasks', 'action' => 'complete_task', base64_encode($user_task_data['UserTask']['task_id']), 'admin' => true)))); 
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
<!-- Modal View UserTask -->
<?php echo $this->Element('tasks/admin_addtask'); ?>
<!-- Modal View Message-->
<!-- Modal Reply Message-->
<?php echo $this->Element('tasks/admin_edittask'); ?>
<?php echo $this->Element('tasks/admin_edittask_his'); ?>
<?php echo $this->Element('tasks/admin_view_task'); ?>
<!-- Modal Reply Message-->
<!-- Modal Delete -->
<div id="DeleteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete Task</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete this task  "<strong>selected user</strong>"?
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
<div id="MarkCompleteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Mark Task as Complete</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to mark this task  "<strong>selected user</strong>" as completed?
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
		
		$("#UserTaskClientUserName").autocomplete(
			'<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'findUser.json', 'superadmin' => false)); ?>', {
				mustMatch: true,
				minChars: 2,
				selectFirst: false,
				autoFill: false,
				selectOnly: true
			}
		);
		
		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});
		
		$(".markComplete").click(function(event) {
			event.preventDefault();
			$('#MarkCompleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#MarkCompleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#MarkCompleteModel').modal('show');
		});
		
		$("#addUserTask").click(function(event) {
			event.preventDefault();
			$('#AddUserTaskModel').modal('show');
		});
	});
</script>
