<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-time"></i> Tasks</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add Task', array('controller' => 'tasks', 'action' => 'addtask', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Add Task', 'id' => 'addTask')); ?>
				<?php // echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i> Trash', array('controller' => 'tasks', 'action' => 'trashtasks', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-md btn-danger', 'title' => 'Deleted Tasks')); ?>
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
		echo $this->Form->input('Task.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Tasks!", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
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
						<col width="13%" />
						<col width="40%" />
						<col width="18%" />
						<col width="18%" />
						<col width="10%" />
					</colgroup>
                    <thead>
                        <tr>
							<th>Task Icon</th>
							<th><?php echo $this->Paginator->sort('Task.name', 'Task Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Task.price', 'Task Price'); ?></th>
                            <th><?php echo $this->Paginator->sort('Task.created', 'Created On'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							$tasksPriceType = Configure::read('PriceType');
							foreach ($data as $task_data) {
								?>
								<tr>
									<td>
										<?php
										if ($task_data['Task']['icon_image'] == '') {
											echo $this->Html->image('128x128.gif', array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
										} else {
											if(file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/tasks_icons/' . $task_data['Task']['icon_image'])) {
												echo $this->Html->image('tasks_icons/' . $task_data['Task']['icon_image'], array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
											} else {
												echo $this->Html->image('128x128.gif', array('class' => 'img-rounded', 'style' => 'border:4px solid white'));
											}
										}
										?>
									</td>
									<td><?php echo $task_data['Task']['name']; ?></td>
									<td><?php echo $this->Number->currency($task_data['Task']['price'], Configure::read("SETTINGS.CURRENCY")) . "/" . $tasksPriceType[$task_data['Task']['price_type']] ; ?></td>
									<td><?php echo $task_data['Task']['created']; ?></td>
									<td class="td-actions">
										<!-- <?php // echo $this->Html->link('<i class="glyphicon glyphicon-eye-open"></i>', array('controller' => 'tasks', 'action' => 'view_task', base64_encode($task_data['Task']['id']), 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-xs btn-default viewTaskRow', 'title' => 'View Task Detail')); ?> -->
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'tasks', 'action' => 'edittask', base64_encode($task_data['Task']['id']), 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-xs btn-primary editTaskRow', 'title' => 'Edit Task')); ?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $task_data['Task']['name'], 'escape' => false, 'class' => 'btn btn-xs btn-danger delete', 'title' => 'Delete Task', 'data-href' => $this->Html->url(array('controller' => 'tasks', 'action' => 'delete_task', base64_encode($task_data['Task']['id']), 'superadmin' => true)))); ?>
									</td>
								</tr>
							<?php
							}
						} else {
							echo '<tr><td colspan="5"><div class="norecord">No Record Found</div></td></tr>';
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
<!-- Modal View Task -->
<?php echo $this->Element('tasks/superadmin_addtask'); ?>
<!-- Modal View Message-->
<!-- Modal Reply Message-->
<?php echo $this->Element('tasks/superadmin_edittask'); ?>
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
<script type="text/javascript">
	$(document).ready(function() {
		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});
		
		$("#addTask").click(function(event) {
			event.preventDefault();
			$('#AddTaskModel').modal('show');
		});
	});
</script>