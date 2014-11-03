<?php echo $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>
	.star {color:red;}
    .block-icon-default { color: #E34C3B !important; }
</style>
<div class="modal fade" id="AddTaskModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php echo $this->Form->create('tasks', array('type' => 'file', 'action' => 'addtask', 'name' => 'TaskAddTaskForm', 'id' => 'TaskAddTaskForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">New Task</h4>
			</div>
			<div class="modal-body">
				<center>
					<div class="input-group">
						<span class="input-group-addon">Task Icon</span>
						<?php echo $this->Form->input('Task.image', array('type' => 'file', 'label' => false, 'div' => false, 'maxlength' => 255, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Please upload task icon.')); ?> 
					</div><span style="text-align:right">Icon should be 128x128 in .png format.</span><br />
					<div class="input-group">
						<span class="input-group-addon">Task Name</span>
						<?php echo $this->Form->input('Task.name', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Ex: Content Creation')); ?> 
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Task Price For</span>
						<?php $options = Configure::read("PriceType"); ?>
						<?php echo $this->Form->select('Task.price_type', $options, array('label' => false, 'div' => false, 'class' => 'form-control', 'required' => true, 'empty' => false)); ?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Task Price</span>
						<?php echo $this->Form->input('Task.price', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 8, 'class' => 'form-control', 'required' => true, 'placeholder' => 'Task Price', 'priceRegex' => true)); ?>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Description</span>
						<?php echo $this->Form->textarea('Task.description', array('label' => false, 'div' => false, 'row' => 5, 'class' => 'form-control', 'required' => true, 'placeholder' => 'Task Description')); ?>
					</div>
				</center>
			</div>
			<div class="modal-footer">
				<center>
                    <button id="singlebutton" name="singlebutton" class="btn btn-primary">Create Task</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</center>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$.validator.addMethod("priceRegex", function(value, element) {
			return this.optional(element) || /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(value);
		}, "Please enter valid monatory value.");
		$("#TaskAddTaskForm").validate();
	});
</script>