<?php echo $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>
	.star {color:red;}
    .block-icon-default { color: #E34C3B !important; }
</style>
<div class="modal fade" id="EditUserTaskModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	<?php echo $this->Form->create('tasks', array('type' => 'file', 'action' => 'edittask', 'name' => 'TaskEditUserTaskForm', 'id' => 'TaskEditUserTaskForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/html" id='EditUserTaskModelTemplate'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Edit Task</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Platform</span>
						<%= task_data.Task.name %>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Title</span>
						<input type="text" id="UserTaskTaskTitle" class="form-control" maxlength="255" name="data[UserTask][task_title]" value="<%= task_data.UserTask.task_title %>" required="required" placeholder= "Name this task - ex: Responsive Web Design" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Client</span>
						<%= task_data.Client.user_name %>@<?php echo Configure::read('SITE_EMAIL.Email');?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Deadline</span>
						<input type="datetime-local" id="UserTaskDeadlineDatetime" class="form-control" name="data[UserTask][deadline_datetime]" value="<%= task_data.UserTask.deadline_datetime %>" id = "date01" required="required" />
					</div><br>
					<div class="input-group range">
						<span class="input-group-addon">Status</span>
						<input type="range" id="UserTaskEditStatusCompleted" class="form-control" name="data[UserTask][status_completed]" min="1" max="100" value="<%= task_data.UserTask.status_completed %>" />
						<output id="completedStatusValue"><%= task_data.UserTask.status_completed %></output>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Description</span>
						<textarea rows="6" class="form-control" id="UserTaskTaskDescription" name="data[UserTask][task_description]" placeholder="Task Description" required="required"><%= task_data.UserTask.task_description %></textarea>
					</div>
				</center>
			</div>
			<div class="modal-footer">
				<center>
                    <button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">Send</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</center>
			</div>
		</div>
	</div>
</script>
<script type="text/javascript">
    $(document).ready(function() {       
		$("#UserTaskEditStatusCompleted").change(function(){
			$("#completedStatusValue").html(this.value);
		});
		$("#TaskEditUserTaskForm").validate();
        var editTaskURL = $('#TaskEditUserTaskForm').attr('action');
        $(".editUserTaskRow").click(function(event) {
            event.preventDefault();
            var urlLoc = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(urlLoc, function(data) {
				$('#TaskEditUserTaskForm').attr('action', editTaskURL + '/' + data.taskData.UserTask.id);
                var template = $("#EditUserTaskModelTemplate").html();
                $("#TaskEditUserTaskForm").html(_.template(template, {task_data:data.taskData,PopupTitle:data.PopupTitle}));
                $('#EditUserTaskModel').modal('show');
				$("#UserTaskEditStatusCompleted").change(function(){
					$("#completedStatusValue").html(this.value);
				});
				$("#TaskEditUserTaskForm").validate();
            });
        });
    });
</script>