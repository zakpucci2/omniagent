<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-file"></i> Support Tickets</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-floppy-saved"></i> Closed Tickets', array('controller' => 'support_tickets', 'action' => 'closedtickets', 'admin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Closed Tickets', 'id' => 'closedTickets')); ?>
            </div>
        </div>
    </div>
    <br><br>
	<?php echo $this->Form->create('support_tickets', array('action' => 'listtickets', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('SupportTicket.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Tickets", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
		?>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">
                Search!</button>
        </span>
    </div>
	<?php echo $this->Form->end(); ?>
    <br/>
    <div class="span7">   
        <div class="widget stacked widget-table action-table">
            <div class="widget-content">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
							<th><?php echo $this->Paginator->sort('SupportTicket.id', 'Ticket Number'); ?></th>
							<th><?php echo $this->Paginator->sort('SupportTicket.user_id', 'Added By'); ?></th>
                            <th><?php echo $this->Paginator->sort('SupportTicket.subject', 'Subject'); ?></th>
                            <th><?php echo $this->Paginator->sort('SupportTicket.created', 'Created On'); ?></th>
							<th><?php echo $this->Paginator->sort('SupportTicket.status', 'Status'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
					<?php
						if (isset($data) && !empty($data)) {
							$status = Configure::read('TicketStatus');
							foreach ($data as $supportticket_data) {
								?>
								<tr>
									<td>&#35;<?php echo $supportticket_data['SupportTicket']['id']; ?></td>
									<td><?php echo $supportticket_data['User']['first_name'] . " " . $supportticket_data['User']['last_name']; ?></td>
									<td><?php echo $supportticket_data['SupportTicket']['subject']; ?></td>
									<td><?php echo $supportticket_data['SupportTicket']['ago']; ?></td>
									<td><?php echo $status[$supportticket_data['SupportTicket']['status']]; ?></td>
									<td class="td-actions">
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-eye-open"></i>', array('controller' => 'support_tickets', 'action' => 'view_ticket', base64_encode($supportticket_data['SupportTicket']['id']), 'admin' => true), array('escape' => false, 'class' => 'btn btn-xs btn-default viewTicketRow', 'title' => 'View Support Ticket Detail')); ?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-share-alt"></i>', array('controller' => 'support_tickets', 'action' => 'reply_ticket', base64_encode($supportticket_data['SupportTicket']['id']), 'admin' => true), array('escape' => false, 'class' => 'btn btn-xs btn-primary replyTicketRow', 'title' => 'Reply To Sender')); ?>
										<!-- <a href="#" class="btn btn-xs btn-warning" alt="User Profile" title="User Profile"><i class="glyphicon glyphicon-user"></i> </a> -->
										<?php if ($supportticket_data['SupportTicket']['status'] != 4) {
											echo $this->Html->link('<i class="glyphicon glyphicon-ok"></i>', 'javascript:void(0)', array('data-name' => $supportticket_data['SupportTicket']['subject'], 'escape' => false, 'class' => 'btn btn-xs btn-success markFixed', 'title' => 'Issue Resolved', 'data-href' => $this->Html->url(array('controller' => 'support_tickets', 'action' => 'mark_fixed', base64_encode($supportticket_data['SupportTicket']['id'])))));
										} ?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $supportticket_data['SupportTicket']['subject'], 'escape' => false, 'class' => 'btn btn-xs btn-danger delete', 'title' => 'Delete Support Ticket', 'data-href' => $this->Html->url(array('controller' => 'support_tickets', 'action' => 'delete_ticket', base64_encode($supportticket_data['SupportTicket']['id']))))); ?>
									</td>
								</tr>
							<?php
							}
						} else {
							echo '<tr><td colspan="6"><div class="norecord">No Record Found</div></td></tr>';
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
<!-- Modal View Ticket -->
<?php echo $this->Element('supporttickets/admin_view_ticket'); ?>
<!-- Modal Send Message-->
<!-- Modal Reply Message-->
<?php echo $this->Element('supporttickets/admin_reply_ticket'); ?>

<!-- Modal Reply Message-->
<!-- Modal Delete -->
<div id="DeleteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete Support Ticket</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete this support ticket  "<strong>selected user</strong>"?
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
                <h3>Mark as Resolved?</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to mark this support ticket  "<strong>selected user</strong>" as resolved?
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

		$(".markFixed").click(function(event) {
			event.preventDefault();
			$('#MarkCompleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#MarkCompleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#MarkCompleteModel').modal('show');
		});
	});
</script>
