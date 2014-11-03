<?php echo $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>
	.star {color:red;}
    .block-icon-default { color: #E34C3B !important; }
</style>
<div class="modal fade" id="AddUserTaskModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php echo $this->Form->create('tasks', array('type' => 'file', 'action' => 'addusertask', 'name' => 'UserTaskAddTaskForm', 'id' => 'UserTaskAddTaskForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">New Task</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Platform</span>
						<?php echo $this->Form->select('UserTask.task_id', $tasksList, array('label' => false, 'div' => false, 'class' => 'form-control', 'required' => true, 'empty' => false)); ?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Title</span>
						<?php echo $this->Form->input('UserTask.task_title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Name this task - ex: Responsive Web Design')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Client</span>
						<?php echo $this->Form->input('UserTask.client_user_name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'form-control', 'style' => 'width:76%', 'required' => true, 'placeholder' => 'Type a user or admin name...')); ?>@<?php echo Configure::read('SITE_EMAIL.Email');?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Deadline</span>
						<?php echo $this->Form->input('UserTask.deadline_datetime', array('type' => 'datetime-local', 'label' => false, 'div' => false, 'class' => 'form-control', 'required' => true, "id" => "date01", "value" => "dd/mm/yyyy")); ?>
					</div><br>
					<div class="input-group range">
						<span class="input-group-addon">Status</span>
						<?php echo $this->Form->input('UserTask.status_completed', array('type' => 'range', 'label' => false, 'div' => false, 'min' => 1, 'max' => 100, 'class' => 'form-control', 'value' => 0)); ?>
						<output id="statusValue">0</output>
					</div><br>		
					<div class="input-group">
						<span class="input-group-addon">Description</span>
						<?php echo $this->Form->textarea('UserTask.task_description', array('label' => false, 'div' => false, 'row' => 5, 'class' => 'form-control', 'required' => true, 'placeholder' => 'Task Description')); ?>
					</div>
				</center>
			</div>
			<div class="modal-footer">
				<center>
                    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Add Task</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</center>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#UserTaskStatusCompleted").change(function(){
			$("#statusValue").html(this.value);
		});
		$("#UserTaskAddTaskForm").validate();
	});
</script>