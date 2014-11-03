<?php $this->Html->css('bootstrap/bootstrap-editable', null, array('inline' => false)); ?>
<?php $this->Html->script('bootstrap/bootstrap-editable', array('inline' => false)); ?>
<?php echo $this->Html->css('jquery.autocomplete'); ?>
<?php echo $this->Html->script('jquery.autocomplete.min.js'); ?>
<style>
    .block-icon-default { color: #E34C3B !important; }
	.acResults {
		z-index: 5000;
	}
	.acResults ul li {
		margin: 0px;
		padding: 2px 5px;
		cursor: pointer;
		display: block;
		font: menu;
		overflow: hidden;
		color: #333;
	}
</style>
<div>
	<br />
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-envelope"></i> Sent Messages</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-envelope"></i> Messages', array('controller' => 'messages', 'action' => 'listmessages', 'admin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Messages', 'id' => 'listMessages')); ?>
				<a href="#newmessageModal" data-toggle="modal" class="btn btn-lmd btn-primary"><span class="glyphicon glyphicon-plus"></span> New Message</a>
				<a href="#messageSettings" class="btn btn-md btn-danger" id="deleteAllMessages"><span class="glyphicon glyphicon-trash"></span> Trash</a>
				<?php // echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> New Message', array('controller' => 'messages', 'action' => 'addmessage', 'admin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'New Message', 'id' => 'addMessage', 'data-toggle' => "modal")); ?>
            </div>
        </div>
    </div>
    <br /><br />
	<?php echo $this->Form->create('messages', array('action' => 'sentmessages', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('UserMessage.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Message", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
		?>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">
                Search!</button>
        </span>
    </div>
	<?php echo $this->Form->end(); ?>
    <br />
    <div class="span7">   
        <div class="widget stacked widget-table action-table">
            <div class="widget-content">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
							<th>&nbsp;</th>
                            <th><?php echo $this->Paginator->sort('Receiver.first_name', 'First Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Receiver.last_name', 'Last Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('user_name', 'User Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Message.subject', 'Subject'); ?></th>
							<th><?php echo $this->Paginator->sort('Message.created', 'Message On'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $message_data) {
								?>
								<tr>
									<td scope="col">
										<?php
										if ($message_data['Receiver']['profile_photo'] == '') {
											echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $message_data['Receiver']['first_name'] . " " . $message_data['Receiver']['last_name']));
										} else {
											if(file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40") . '/' . $message_data['Receiver']['profile_photo'])) {
												echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40")  . '/' . $message_data['Receiver']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $message_data['Receiver']['first_name'] . " " . $message_data['Receiver']['last_name']));
											} else {
												echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $message_data['Receiver']['first_name'] . " " . $message_data['Receiver']['last_name']));
											}
										}
										?>
									</td>
									<td><?php echo $message_data['Receiver']['first_name']; ?></td>
									<td><?php echo $message_data['Receiver']['last_name']; ?></td>
									<td><?php echo $message_data['Receiver']['user_name']; ?></td>
									<td>
										<?php echo $this->Text->truncate(
											$message_data['Message']['subject'],
											50,
											array(
												'ellipsis' => '...',
												'exact' => true
											)
										); ?>
									</td>
									<td><?php echo $message_data['Message']['created']; ?></td>
									<td class="td-actions">
										<?php // echo $this->Html->link('<i class="glyphicon glyphicon-share-alt"></i> ', array('controller' => 'messages', 'action' => 'reply_message', base64_encode($message_data['Message']['id'])), array('escape' => false, 'class' => 'btn btn-xs btn-primary replyRow', 'title' => 'Reply Message')); ?>
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-envelope"></i> ', array('controller' => 'messages', 'action' => 'view_sent_message', base64_encode($message_data['Message']['id'])), array('escape' => false, 'class' => 'btn btn-xs btn-info viewRow', 'title' => 'View Sent Message Detail')); ?>
										<!-- <a href="#" class="btn btn-xs btn-warning" title="Admin Profile"><i class="glyphicon glyphicon-user"></i> </a> -->
										<!-- <a href="#" class="btn btn-xs btn-success" title="Send Notification"><i class="glyphicon glyphicon-warning-sign"></i> </a> -->
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i> ', 'javascript:void(0)', array('data-name' => $message_data['Message']['subject'], 'escape' => false, 'class' => 'btn btn-xs btn-danger rowDelete', 'title' => 'Delete Sent Message', 'data-href' => $this->Html->url(array('controller' => 'messages', 'action' => 'delete_sent_message', base64_encode($message_data['UserMessage']['id']))))); ?>
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
<!-- Modal Delete All Messages -->
<div class="modal fade" id="messageSettings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Message Settings</h4>
			</div>
			<div class="modal-body">
				<p>
				<?php echo $this->Form->create('messages', array('type' => 'post', 'action' => 'delete_allsentmessage', 'name' => 'DeleteAllMessageForm', 'id' => 'DeleteAllMessageForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
					<!-- <fieldset>
					<!-- Form Name -->
					<!-- <legend></legend>
					<!-- Multiple Checkboxes -->
					<div class="control-group">
						<label class="control-label" for="checkboxes"></label>
						<div class="controls">
							<label class="checkbox" for="checkboxes-0">
								<?php echo $this->Form->input('Message.subject', array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => 'checked', 'required' => true, 'value' => 1)); ?>
								Delete all sent messages
							</label>
						</div>
					</div>
					<!-- </fieldset> -->
				<?php echo $this->Form->end(); ?>
				</p>
			</div>
			<div class="modal-footer">
				<button id="delAllMessages" name="delAllMessages" type="submit" class="btn btn-primary">Apply</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal Delete All Messages End-->
<?php echo $this->Element('message/admin_view_sent_message'); ?>
<!-- Modal Send Message-->
<?php echo $this->Element('message/admin_compose_message'); ?>
<!-- Modal Send Message End -->
<!-- Modal Reply Message -->
<?php // echo $this->Element('message/admin_reply_message'); ?>
<!-- Modal Reply Message End -->
<!-- Modal Delete Confirm -->
<div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete Message</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete "<strong>selected message</strong>" from sent messages list?
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
	$(document).ready(function() {
		$("#UserComposeMessageForm").validate();
		$("#sendMessage").click(function(){
			$("#UserComposeMessageForm").submit();
		});
		$("#sendDelete").click(function() {
			window.location.href = $(this).attr('href');
		});
		$("#MessageReceiverUserName").autocomplete(
			'<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'findUser.json', 'admin' => false)); ?>', {
				mustMatch: true,
				minChars: 2,
				selectFirst: false,
				autoFill: false,
				selectOnly: true
			}
		);
		/* $( "#UserMessageReceiverId" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "http://localhost/projects/OmniHustle/GitClone/25032014/omni/autocomplete/fetch_users/User/first_name/"+request.term,
					dataType: "jsonp",
					data: {},
					success: function( data ) {
						response( $.map( data.User, function( item ) {
							return {
							  label: item.User.first_name,
							  value: item.User.last_name
							};
						}));
					}
				});
			},
			minLength: 2,
			select: function( event, ui ) {
			  log( ui.item ?
				"Selected: " + ui.item.label :
				"Nothing selected, input was " + this.value);
			},
			open: function() {
				$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
			},
			close: function() {
				$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
			}
		}); */
		$(".rowDelete").click(function(event) {
			event.preventDefault();
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#deleteModel').modal('show');
		});
		$("#deleteAllMessages").click(function(event) {
			event.preventDefault();
			$("#messageSettings").modal('show');
		});
		$("#delAllMessages").click(function(event) {
			event.preventDefault();
			$("#messageSettings").modal('hide');
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $("#DeleteAllMessageForm").attr('action'));
			$('#deleteModel').modal('show');
		});
		$("#closeDelete").click(function() {
			$("#deleteModel").modal('hide');
		});
	});
</script>
