<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<?php
$this->Html->addCrumb('Support Tickets', array('controller' => 'support_tickets', 'action' => 'listtickets'));
if (!empty($this->params["pass"][0]) && $this->params["pass"][0] == "closed") {
	$this->Html->addCrumb('Closed Tickets', array('controller' => 'support_tickets', 'action' => 'listtickets',"closed"));
}
?>
<style type="text/css">
	.custab{
		border: 1px solid #ccc;
		padding: 5px;
		margin: 5% 0;
		box-shadow: 3px 3px 2px #ccc;
		transition: 0.5s;
    }
	.custab:hover{
		box-shadow: 3px 3px 0px transparent;
		transition: 0.5s;
    }
	.media
    {
        /*box-shadow:0px 0px 4px -2px #000;*/
        margin: 20px 0;
        padding:30px;
    }
    .dp
    {
        border:10px solid #eee;
        transition: all 0.2s ease-in-out;
    }
    .dp:hover
    {
        border:2px solid #eee;
        transform:rotate(360deg);
        -ms-transform:rotate(360deg);  
        -webkit-transform:rotate(360deg);  
        /*-webkit-font-smoothing:antialiased;*/
    }
	.pager .next>a {
		float:none !important;
	}
	table { table-layout: fixed; }
	table th, table td { word-break:break-all; width:12%;}
</style>
<div class="row-fluid">
	<a href="#supportTicketSettings" data-toggle="modal" class="btn btn-setting btn-warning"><i class="halflings-icon white cog"></i> Settings</a>


	<?php
	if (!empty($this->params["pass"][0]) && $this->params["pass"][0] == "closed") {
		echo $this->Html->link("Opened Tickets", array("controller" => "support_tickets", "action" => "listtickets"), array("class" => "btn btn-primary  btn-xs pull-right", "style" => "margin-left:15px;"));
	} else {
		echo $this->Html->link("Closed Tickets", array("controller" => "support_tickets", "action" => "listtickets", "closed"), array("class" => "btn btn-primary  btn-xs pull-right", "style" => "margin-left:15px;"));
	}
	?>


	<a href="#btnSupportModal" data-toggle="modal" class="btn btn-primary btn-setting btn-xs pull-right btnSupportModal"><b>+</b> Add Support Ticket</a> 

	<table class="table table-striped custab">
		<colgroup>
			<col width="16%" />
			<col width="30%" />
			<col width="10%" />
			<col width="16%" />
			<col width="28%" />
		</colgroup>
		<thead>
			<tr>
				<th scope="col" ><?php echo $this->Paginator->sort('SupportTicket.created', 'Created Date'); ?></th>
				<th scope="col"><?php echo $this->Paginator->sort('SupportTicket.subject', 'Subject'); ?></th>
				<th scope="col"><?php echo $this->Paginator->sort('SupportTicket.status', 'Status'); ?></th>
				<th scope="col">Assigned Admin</th>
				<th scope="col" class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (isset($data) && !empty($data)) {
				$counter = 0;
				foreach ($data as $ticket_data) {
					?>
					<tr>
						<td scope="col"><?php echo $ticket_data['SupportTicket']['date']; ?></td>
						<td scope="col">
							<?php
							echo $this->Text->truncate(
								$ticket_data['SupportTicket']['subject'], 50, array(
								'ellipsis' => '...',
								'exact' => true
								)
							);
							?>
						</td>
						<td scope="col"><?php
							$status = Configure::read('TicketStatus');
							echo $status[$ticket_data['SupportTicket']['status']];
							?></td>



						<td><?php if ($ticket_data["SupportTicket"]["admin_id"] != NULL && $ticket_data["SupportTicket"]["admin_id"] != '') {
						printf("%s %s", $ticket_data["Admin"]["first_name"], $ticket_data["Admin"]["last_name"]);
					} else {
						echo "<font style=\"color:D1D1D1\">_________</font>";
						;
					} ?></td>

						<td scope="col" class="text-center">
							<?php echo $this->Html->link('<i class="halflings-icon white search"></i> View', array('controller' => 'support_tickets', 'action' => 'view_ticket', base64_encode($ticket_data['SupportTicket']['id'])), array('escape' => false, 'class' => 'btn btn-info btn-md viewTicketRow', 'title' => 'View Support Ticket Detail')); ?>						
							<?php
							if (($ticket_data["SupportTicket"]["admin_id"] != NULL && $ticket_data["SupportTicket"]["admin_id"] != '') && (@$this->params["pass"][0] != "closed")) {
								echo $this->Html->link('<i class="icon-share-alt white search"></i> Reply', array('controller' => 'support_tickets', 'action' => 'view_ticket', base64_encode($ticket_data['SupportTicket']['id'])), array('escape' => false, 'class' => 'btn btn-success btn-md replyTicketRow ', 'title' => 'Reply On Support Ticket Detail'));
							}
							?>

					<?php echo $this->Html->link('<i class="halflings-icon white trash"></i> Delete', 'javascript:void(0)', array('data-name' => $ticket_data['SupportTicket']['subject'], 'escape' => false, 'class' => 'btn btn-danger btn-md', 'title' => 'Delete SupportTicket', 'data-href' => $this->Html->url(array('controller' => 'support_tickets', 'action' => 'delete_ticket', base64_encode($ticket_data['SupportTicket']['id']))))); ?>
						</td>
					</tr>
					<?php
					$counter++;
				}
			} else {
				echo '<tr><td colspan="4"><div class="norecord">No Record Found</div></td></tr>';
			}
			?>		
		</tbody>
    </table>
    <center>
			<?php if ($this->Paginator->counter('{:pages}') > 1) { ?>
			<ul class="pager">
			<?php
			echo $this->Paginator->prev(__('<< First Page'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
			echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
			echo $this->Paginator->next(__('Last Page >>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
			?>
			</ul>
<?php } ?>
	</center>
</div>
<!-- Modal View SupportTickets -->
<?php echo $this->Element('supporttickets/view_ticket'); ?>
<?php echo $this->Element('supporttickets/reply_ticket'); ?>
<!-- Modal View SupportTickets -->
<!-- Modal Delete All SupportTickets -->
<div class="modal hide fade" id="supportTicketSettings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Support Ticket Settings</h4>
			</div>
			<div class="modal-body">
				<p>
<?php echo $this->Form->create('support_tickets', array('type' => 'post', 'action' => 'delete_alltickets', 'name' => 'DeleteAllSupportTicketForm', 'id' => 'DeleteAllSupportTicketForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
					<!-- <fieldset>
					<!-- Form Name -->
					<!-- <legend></legend>
					<!-- Multiple Checkboxes -->
				<div class="control-group">
					<label class="control-label" for="checkboxes"></label>
					<div class="controls">
						<label class="checkbox" for="checkboxes-0">
				<?php echo $this->Form->input('SupportTicket.subject', array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => 'checked', 'required' => true, 'value' => 1)); ?>
							Delete all support tickets
						</label>
					</div>
				</div>
				<!-- </fieldset> -->
<?php echo $this->Form->end(); ?>
				</p>
			</div>
			<div class="modal-footer">
				<button id="deleteAllSupportTickets" name="deleteAllSupportTickets" type="submit" class="btn btn-primary">Apply</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Delete Confirm -->
<div class="modal hide fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete Support Ticket</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete "<strong>selected support ticket(s)</strong>" from support tickets list?
                </center>	
            </div>
            <div class="modal-footer">
                <center>
                    <a href="#" class="btn btn-default" data-dismiss="modal" id="closeDelete">NO</a>
                    <a href="#" class="btn btn-primary" id="sendDelete">Yes</a>
                </center>
            </div>
        </div>
    </div>
</div>
<!-- Modal Delete Confirm End-->
<script type="text/javascript">
    $(document).ready(function () {
        $("#sendDelete").click(function () {
            window.location.href = $(this).attr('href');
        });
        $(".btn-danger").click(function (event) {
            event.preventDefault();
            $('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
            $('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
            $('#deleteModel').modal('show');
        });
        $("#deleteAllSupportTickets").click(function (event) {
            event.preventDefault();
            $("#supportTicketSettings").modal('hide');
            $('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
            $('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $("#DeleteAllSupportTicketForm").attr('action'));
            $('#deleteModel').modal('show');
        });
        $("#closeDelete").click(function () {
            $("#deleteModel").modal('hide');
        });

        $(".btnSupportModal").click(function (event) {
            event.preventDefault();
            $('#addSupportTicketModal').modal('show');
        });


    });
</script>
