<style>
	.block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
	<div class="col-lg-12 ch-heading">
		<div class="row">
			<div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-list-alt"></i> Email Templates</strong></div>
			<div class="pull-right">
				<?php //echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add User', array('controller' => 'users', 'action' => 'add','admin'=>true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Client', 'id' => 'addUser')); ?>
			</div>
		</div>
	</div>
	<br><br>
	<div class="span7">   
		<div class="widget stacked widget-table action-table">
			<div class="widget-content">
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th><?php echo $this->Paginator->sort('template_for', 'Template For'); ?></th>
							<th><?php echo $this->Paginator->sort('mail_subject', 'Mail Subject'); ?></th>
							<th><?php echo $this->Paginator->sort('sender_name', 'Sender Name'); ?></th>
							<th><?php echo $this->Paginator->sort('sender_email', 'Sender Email'); ?></th>
							<th><?php echo $this->Paginator->sort('modified', 'Last Modified'); ?></th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $res) {
								?>
								<tr>
									<td><?php echo $res['EmailTemplate']['template_for']; ?></td>
									<td><?php echo $res['EmailTemplate']['mail_subject']; ?></td>
									<td><?php echo $res['EmailTemplate']['sender_name']; ?></td>
									<td><?php echo $res['EmailTemplate']['sender_email']; ?></td>
									<td><?php echo $res['EmailTemplate']['modified']; ?></td>								
									<td><?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'templates', 'action' => 'edit_template', base64_encode($res['EmailTemplate']['id'])), array('escape' => false, 'class' => 'btn btn-sm btn-default edit', 'title' => 'Edit Template')); ?>
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