<?php echo $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>
	.star {color:red;}
    .block-icon-default { color: #E34C3B !important; }
</style>
<div class="modal fade" id="EditTaskModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	<?php echo $this->Form->create('tasks', array('type' => 'file', 'action' => 'edittask', 'name' => 'TaskEditTaskForm', 'id' => 'TaskEditTaskForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/html" id='EditTaskModelTemplate'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Edit Task</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Task Icon</span>
						<input type="file" id="TaskImage" class="form-control" maxlength="255" name="data[Task][image]" placeholer="Please upload task icon." />
							<% if(task_data.Task.icon_imaage != "") { %>
							<p>
								<img src="<?php echo Router::url('/', true) . 'img/tasks_icons/' ?><%= task_data.Task.icon_image %>" class="img-rounded" style="border:4px solid white" alt="">
							</p>
							<% } %>
					</div><span style="text-align:right">Icon should be 128x128 in .png format.</span><br />
					<div class="input-group">
						<span class="input-group-addon">Task Name</span>
						<input type="text" id="TaskName" class="form-control" maxlength="255" name="data[Task][name]" value="<%= task_data.Task.name %>" required="required" placeholder= "Task name" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Task Price For</span>
						<?php $options = Configure::read("PriceType"); ?>
						<select name="data[Task][price_type]" id="TaskPriceType" class="form-control" required="required">
						<?php 
						foreach($options as $key => $priceType) { ?>
							<% if(task_data.Task.price_type == <?php echo $key; ?>) { %>
								<option value="<?php echo $key; ?>" selected="selected"><?php echo $priceType ?></option>
							<% } else { %>
								<option value="<?php echo $key; ?>"><?php echo $priceType ?></option>
							<% } %>
						<?php }
						?>
						</select>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Task Price</span>
						<input type="text" id="TaskPrice" class="form-control" maxlength="8" name="data[Task][price]" value="<%= task_data.Task.price %>" required="required" placeholder= "Task price" priceRegex=true />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Description</span>
						<textarea rows="6" class="form-control" id="TaskDescription" name="data[Task][description]" placeholder="Task Description" required="required"><%= task_data.Task.description %></textarea>
					</div><br>
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
		$.validator.addMethod("priceRegex", function(value, element) {
			return this.optional(element) || /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
		}, "Please enter valid monatory value.");
		$("#TaskEditTaskForm").validate();
        var editTaskURL = $('#TaskEditTaskForm').attr('action');
        $(".editTaskRow").click(function(event) {
            event.preventDefault();
            var urlLoc = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(urlLoc, function(data) {
				$('#TaskEditTaskForm').attr('action', editTaskURL + '/' + data.taskData.Task.id);
                var template = $("#EditTaskModelTemplate").html();
                $("#TaskEditTaskForm").html(_.template(template, {task_data:data.taskData,PopupTitle:data.PopupTitle}));
                $('#EditTaskModel').modal('show');
            });
        });
    });
</script>