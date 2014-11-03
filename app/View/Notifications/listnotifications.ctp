<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<?php $this->Html->addCrumb('Notifications', array('controller' => 'notifications', 'action' => 'listnotifications')); ?>
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
</style>
<div class="row-fluid">
	<a href="#notificationSettings" data-toggle="modal" class="btn btn-setting btn-warning"><i class="halflings-icon white cog"></i> Settings</a>
	<table class="table table-striped custab">
		<colgroup>
			<col width="8%" />
			<col width="15%" />
			<col width="17%" />
			<col width="20%" />
			<col width="30%" />
			<col width="18%" />
		</colgroup>
		<thead>
			<tr>
				<td>&nbsp;</td>
				<th scope="col"><?php echo $this->Paginator->sort('Notofication.created', 'Date'); ?></th>
				<th scope="col"><?php echo $this->Paginator->sort('UserNotification.sender_id', 'From'); ?></th>
				<th scope="col"><?php echo $this->Paginator->sort('Notofication.subject', 'Subject'); ?></th>
				<th scope="col">Notification</th>
				<th scope="col" class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (isset($data) && !empty($data)) {
				$counter = 0;
				foreach ($data as $notification_data) {
					if (isset($notification_data['UserNotification']['is_read']) && $notification_data['UserNotification']['is_read'] == 0) {
						$class = 'class="boldtr"';
					} else {
						$class = "";
					}
					?>
					<tr <?php echo $class; ?>>
						<td scope="col">
							<?php
							if ($notification_data['Sender']['profile_photo'] == '') {
								echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $notification_data['Sender']['first_name'] . " " . $notification_data['Sender']['last_name']));
							} else {
								if(file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40") . '/' . $notification_data['Sender']['profile_photo'])) {
									echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40")  . '/' . $notification_data['Sender']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $notification_data['Sender']['first_name'] . " " . $notification_data['Sender']['last_name']));
								} else {
									echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $notification_data['Sender']['first_name'] . " " . $notification_data['Sender']['last_name']));
								}
							}
							?>
						</td>
						<td scope="col"><?php echo $notification_data['Notification']['date']; ?></td>
						<td scope="col"><?php echo $notification_data['Sender']['first_name'] . ' ' . $notification_data['Sender']['last_name']; ?></td>
						<td scope="col">
							<?php
							echo $this->Text->truncate(
								$notification_data['Notification']['subject'], 50, array(
								'ellipsis' => '...',
								'exact' => true
								)
							);
							?>
						</td>
						<td scope="col">
							<?php
							echo $this->Text->truncate(
								$notification_data['Notification']['body'], 100, array(
								'ellipsis' => '...',
								'exact' => true
								)
							);
							?>
						</td>
						<td scope="col" class="text-center">
							<?php echo $this->Html->link('<i class="halflings-icon white search"></i> View', array('controller' => 'notifications', 'action' => 'view_notification', base64_encode($notification_data['Notification']['id'])), array('escape' => false, 'class' => 'btn btn-info btn-md viewNotificationRow', 'title' => 'View Notification Detail')); ?>
							<?php echo $this->Html->link('<i class="halflings-icon white trash"></i> Delete', 'javascript:void(0)', array('data-name' => $notification_data['Notification']['subject'], 'escape' => false, 'class' => 'btn btn-danger btn-md delRow', 'title' => 'Delete Notification', 'data-href' => $this->Html->url(array('controller' => 'notifications', 'action' => 'delete_notification', base64_encode($notification_data['UserNotification']['id']))))); ?>
						</td>
					</tr>
					<?php
					$counter++;
				}
			} else {
				echo '<tr><td colspan="5"><div class="norecord">No Record Found</div></td></tr>';
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
<!-- Modal Delete All Notifications -->
<div class="modal hide fade" id="notificationSettings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Notification Settings</h4>
			</div>
			<div class="modal-body">
				<p>
					<?php echo $this->Form->create('notifications', array('type' => 'post', 'action' => 'delete_allnotification', 'name' => 'DeleteAllNotificationForm', 'id' => 'DeleteAllNotificationForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
					<!-- <fieldset>
					<!-- Form Name -->
					<!-- <legend></legend>
					<!-- Multiple Checkboxes -->
					<div class="control-group">
						<label class="control-label" for="checkboxes"></label>
						<div class="controls">
							<label class="checkbox" for="checkboxes-0">
								<?php echo $this->Form->input('Notification.subject', array('type' => 'checkbox', 'label' => false, 'div' => false, 'checked' => 'checked', 'required' => true, 'value' => 1)); ?>
								Delete all notifications
							</label>
						</div>
					</div>
					<!-- </fieldset> -->
					<?php echo $this->Form->end(); ?>
				</p>
			</div>
			<div class="modal-footer">
				<button id="deleteAllNotifications" name="deleteAllNotifications" type="submit" class="btn btn-primary">Apply</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                <h3>Delete Notification</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete "<strong>selected notification(s)</strong>" from notifications list?
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
		$("#sendDelete").click(function() {
			window.location.href = $(this).attr('href');
		});
		$(".delRow").click(function(event) {
			event.preventDefault();
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#deleteModel').modal('show');
		});
		$("#deleteAllNotifications").click(function(event) {
			event.preventDefault();
			$("#notificationSettings").modal('hide');
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $("#DeleteAllNotificationForm").attr('action'));
			$('#deleteModel').modal('show');
		});
		$("#closeDelete").click(function() {
			$("#deleteModel").modal('hide');
		});
	});
</script>
