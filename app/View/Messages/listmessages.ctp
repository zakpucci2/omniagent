<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<?php echo $this->Html->css('jquery.autocomplete'); ?>
<?php echo $this->Html->script('jquery.autocomplete.min.js'); ?>
<?php $this->Html->addCrumb('Messages', array('controller' => 'messages', 'action' => 'listmessages')); ?>
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
	table th, table td { word-break:break-all;}
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
<div class="row-fluid">
	<a href="#messageSettings" data-toggle="modal" class="btn btn-setting btn-warning" id="messageSettingsModal"><i class="halflings-icon white cog"></i> Settings</a>&nbsp;&nbsp;
	<?php echo $this->Html->link('<i class="halflings-icon white envelope"></i> Sent Messages', array('controller' => 'messages', 'action' => 'sentmessages'), array('escape' => false, 'class' => 'btn btn-success', 'title' => 'Sent Messages', 'id' => 'sentMessages')); ?>
	<a href="#composeModal" data-toggle="modal" class="btn btn-primary btn-setting btn-xs pull-right"><b>+</b> Compose New</a> 
	<table class="table table-striped custab">
		<colgroup>
			<col width="8%" />
			<col width="15%" />
			<col width="18%" />
			<col width="25%" />
			<col width="25%" />
		</colgroup>
		<thead>
			<tr>
				<td>&nbsp;</td>
				<th scope="col"><?php echo $this->Paginator->sort('Message.created', 'Date'); ?></th>
				<th scope="col"><?php echo $this->Paginator->sort('UserMessage.sender_id', 'From'); ?></th>
				<th scope="col"><?php echo $this->Paginator->sort('Message.subject', 'Subject'); ?></th>
				<th scope="col" class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (isset($data) && !empty($data)) {
				$counter = 0;
				foreach ($data as $message_data) {
					if(isset($message_data['UserMessage']['is_read']) && $message_data['UserMessage']['is_read'] == 0) {
						$class = 'class="boldtr"';
					} else {
						$class = "";
					}
					?>
					<tr <?php echo $class; ?>>
						<td scope="col">
							<?php
							if ($message_data['Sender']['profile_photo'] == '') {
								echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $message_data['Sender']['first_name'] . " " . $message_data['Sender']['last_name']));
							} else {
								if(file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40") . '/' . $message_data['Sender']['profile_photo'])) {
									echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40")  . '/' . $message_data['Sender']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $message_data['Sender']['first_name'] . " " . $message_data['Sender']['last_name']));
								} else {
									echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $message_data['Sender']['first_name'] . " " . $message_data['Sender']['last_name']));
								}
							}
							?>
						</td>
						<td scope="col"><?php echo $message_data['Message']['date']; ?></td>
						<td scope="col"><?php echo $message_data['Sender']['first_name'] . ' ' . $message_data['Sender']['last_name']; ?></td>
						<td scope="col">
							<?php echo $this->Text->truncate(
								$message_data['Message']['subject'],
								50,
								array(
									'ellipsis' => '...',
									'exact' => true
								)
							); ?>
						</td>
						<td scope="col" class="text-center">
							<?php echo $this->Html->link('<i class="halflings-icon white search"></i> View', array('controller' => 'messages', 'action' => 'view_message', base64_encode($message_data['Message']['id'])), array('escape' => false, 'class' => 'btn btn-info btn-md viewMessageRow', 'title' => 'View Message Detail')); ?>
							<?php echo $this->Html->link('<i class="halflings-icon white share-alt"></i> Reply', array('controller' => 'messages', 'action' => 'reply_message', base64_encode($message_data['Message']['id'])), array('escape' => false, 'class' => 'btn btn-success btn-md replyRow', 'title' => 'Reply Message')); ?>
							<?php echo $this->Html->link('<i class="halflings-icon white trash"></i> Delete', 'javascript:void(0)', array('data-name' => $message_data['Message']['subject'], 'escape' => false, 'class' => 'btn btn-danger btn-md delRow', 'title' => 'Delete Message', 'data-href' => $this->Html->url(array('controller' => 'messages', 'action' => 'delete_message', base64_encode($message_data['UserMessage']['id']))))); ?>
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
<!-- Modal Delete All Messages -->
<div class="modal hide fade" id="messageSettings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Message Settings</h4>
			</div>
			<div class="modal-body">
				<p>
				<?php echo $this->Form->create('messages', array('type' => 'post', 'action' => 'delete_allmessage', 'name' => 'DeleteAllMessageForm', 'id' => 'DeleteAllMessageForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
					<!-- <fieldset>
					<!-- Form Name -->
					<!-- <legend></legend>
					<!-- Multiple Checkboxes -->
					<div class="control-group">
						<label class="control-label" for="checkboxes"></label>
						<div class="controls">
							<label class="checkbox" for="checkboxes-0">
								<?php echo $this->Form->input('Message.subject', array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => 'checked', 'required' => true, 'value' => 1)); ?>
								Delete all messages
							</label>
						</div>
					</div>
					<!-- </fieldset> -->
				<?php echo $this->Form->end(); ?>
				</p>
			</div>
			<div class="modal-footer">
				<button id="deleteAllMessages" name="deleteAllMessages" type="submit" class="btn btn-primary">Apply</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php echo $this->Element('message/compose_message'); ?>
<!-- Modal Send Message End -->
<!-- Modal Reply Message -->
<?php echo $this->Element('message/reply_message'); ?>
<!-- Modal Reply Message End -->
<!-- Modal Delete Confirm -->
<div class="modal hide fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete Message</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete "<strong>selected message</strong>" from messages list?
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
		/* $( "#MessageReceiverUserName" ).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "http://127.0.0.1/projects/OmniHustle/GitClone/25032014/omni/users/findUser",
					dataType: "jsonp",
					data: {q:request.term},
					success: function( data ) {
						console.log(data.User);
						response( $.map( data.User, function( item ) {
							console.log(item.User.first_name);
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
		$(".delRow").click(function(event) {
			event.preventDefault();
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#deleteModel').modal('show');
		});
		$("#deleteAllMessages").click(function(event) {
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
