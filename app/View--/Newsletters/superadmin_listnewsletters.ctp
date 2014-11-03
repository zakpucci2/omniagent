<style>
	.block-icon-default { color: #E34C3B !important; }
	.star {color:red;}
</style>
<div><br>
	<div class="col-lg-12 ch-heading">
		<div class="row">
			<div class="pull-left"><strong class="lead">Newsletter Templates</strong></div>
			<div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> Add Newsletter', array('controller' => 'newsletters', 'action' => 'add_newsletter', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Newsletter', 'id' => 'addNewsletter')); ?>
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
							<th><?php echo $this->Paginator->sort('title', 'Template For'); ?></th>
							<th><?php echo $this->Paginator->sort('mail_subject', 'Newsletter Subject'); ?></th>
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
									<td><?php echo $res['Newsletter']['title']; ?></td>
									<td><?php echo $res['Newsletter']['mail_subject']; ?></td>
									<td><?php echo $res['Newsletter']['sender_name']; ?></td>
									<td><?php echo $res['Newsletter']['sender_email']; ?></td>
									<td><?php echo $res['Newsletter']['modified']; ?></td>								
									<td>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'newsletters', 'action' => 'edit_newsletter', base64_encode($res['Newsletter']['id'])), array('escape' => false, 'escape' => false, 'class' => 'btn btn-sm btn-default edit', 'title' => 'Edit Newsletter')); ?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $res['Newsletter']['title'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Newsletter', 'data-href' => $this->Html->url(array('controller' => 'newsletters', 'action' => 'delete', base64_encode($res['Newsletter']['id']))))); ?>
										<?php echo $this->Html->link('Send', 'javascript:void(0)', array('escape' => false, 'class' => 'btn btn-sm btn-primary go sendNewsletter', 'rel' => base64_encode($res['Newsletter']['id']), 'data-href' => $this->Html->url(array('controller' => 'newsletters', 'action' => 'send_newsletter', 'superadmin' => true)))); ?>
										<?php echo $this->Html->link('Send to All', 'javascript:void(0)', array('escape' => false, 'class' => 'btn btn-sm btn-primary go sendNewsletterToAll', 'rel' => base64_encode($res['Newsletter']['id']), 'data-href' => $this->Html->url(array('controller' => 'newsletters', 'action' => 'send_newsletter', 'superadmin' => true)))); ?>
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
<!-- Modal Delete -->
<div id="DeleteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>Delete Newsletter</h3>
			</div>
			<div class="modal-body">
				<center>
					Are you sure want to delete the newsletter "<strong>selected newsletter</strong>"?
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
		$('.sendNewsletter').click(function() {
			$.post(
				$(this).attr('data-href'),
				{id: $(this).attr('rel')},
				function(data) {
					if (data == 'success') {
						location.reload(true);
					}
				}
			);
			return false;
		});
		$('.sendNewsletterToAll').click(function() {
			$.post(
				$(this).attr('data-href'),
				{id: $(this).attr('rel'), sendTo: 'all'},
				function(data) {
					if (data == 'success') {
						location.reload(true);
					}
				}
			);
			return false;
		});
	});
</script>		
