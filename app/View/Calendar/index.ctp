<?php $this->Html->addCrumb('Calendar', array('controller' => 'calendar', 'action' => 'index')); ?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon calendar"></i><span class="break"></span><?php echo __('Calendar'); ?></h2>
		</div>
		<div class="box-content">
			<div id="external-events" class="span4 hidden-phone hidden-tablet" style="padding-bottom:2px; padding-top:2px">
				<h4>Schedule Your Posts (Drag us!)</h4>
				<div class="external-event badge" style="padding-bottom:2px; padding-top:2px">Post 1</div>
				<div class="external-event badge badge-success" style="padding-bottom:2px; padding-top:2px">Post 2</div>
				<div class="external-event badge badge-warning" style="padding-bottom:2px; padding-top:2px">Post 3</div>
				<div class="external-event badge badge-important" style="padding-bottom:2px; padding-top:2px">Post 4</div>
				<div class="external-event badge badge-info" style="padding-bottom:2px; padding-top:2px">Post 5</div>
				<div class="external-event badge badge-inverse" style="padding-bottom:2px; padding-top:2px">Post 6</div>
				<div class="external-event badge" style="padding-bottom:2px; padding-top:2px">Post 7</div>
				<div class="external-event badge badge-success" style="padding-bottom:2px; padding-top:2px">Post 8</div>
				<div class="external-event badge badge-warning" style="padding-bottom:2px; padding-top:2px">Post 9</div>
				<div class="external-event badge badge-important" style="padding-bottom:2px; padding-top:2px">Post 10</div>
				<div class="external-event badge badge-info" style="padding-bottom:2px; padding-top:2px">Post 11</div>
				<div class="external-event badge badge-info" style="padding-bottom:2px; padding-top:2px">Post 12</div>
				<div class="external-event badge badge-inverse" style="padding-bottom:2px; padding-top:2px"><i class="halflings-icon white info-sign noty" data-noty-options='{"text":"<strong>Help!:</strong> This is your <strong>post schedule</strong>, you are allowed <strong>12 posts</strong> per month, and all you need to do is simply drag your post onto the day or time you would like it to be posted.<br><br>Fill in the corresponding post box with the update that you would like to be sent out to your profiles. Boom! Easy! Give it a shot!","layout":"topRight","type":"info"}'></i> Help?</div>
				<p><br>
					<label for="drop-remove"><input type="checkbox" id="drop-remove" checked disabled /> remove after drop</label>
				</p>
				<br>
				<?php // echo $this->Form->create('calendar', array('type' => 'file', 'action' => 'addpost', 'name' => 'addPost', 'id' => 'addPost', 'class' => 'form-horizontal', 'role' => 'form')); ?>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 1</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 2</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 3</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 4</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 5</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 6</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 7</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 8</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 9</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 10</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 11</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<div class="input-prepend">
							<span class="add-on">Post 12</span><input id="prependedInput" size="16" type="text">
						</div>
					</div>
				</div>
				<br><a href="#" class="btn btn-primary"><span class="glyphicon glyphicon-ok-sign"></span> Update</a>
				<?php // echo $this->Form->end(); ?>
			</div>
			<div id="calendar" class="span8" style="padding:10px; padding-top:5px"></div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>