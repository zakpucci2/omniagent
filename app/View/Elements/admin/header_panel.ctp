<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-cogwheels"></span> Admin Panel</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<?php echo $this->Html->link('<span class="glyphicon glyphicon-star"></span> <br/>My Clients', array('controller' => 'admins', 'action' => 'listclients', 'favorites', 'admin' => true), array('title' => 'List Users', 'escape' => false, 'class' => 'btn btn-warning btn-lg', 'id' => '')); ?>
						<!--<a href="#clientsModal" data-toggle="modal" class="btn btn-warning btn-lg" role="button"><span class="glyphicon glyphicon-star"></span> <br/>My Clients</a>-->
						<a href="<?php echo Configure::read('FULL_BASE_URL.URL'); ?>/userprofile.html" class="btn btn-success btn-lg" role="button"><span class="glyphicon glyphicon-user"></span> <br/>My Profile</a>
						<!--<a href="#noteModal" data-toggle="modal" class="btn btn-info btn-lg" role="button"><span class="glyphicon glyphicon-file"></span> <br/>My Notes</a>-->
						<?php echo $this->Html->link('<span class="glyphicon glyphicon-file"></span> <br/>My Notes', array('controller' => 'notes', 'action' => 'listnotes', 'admin' => true), array('title' => 'List Notes', 'escape' => false, 'class' => 'btn btn-info btn-lg', 'id' => '')); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>