<div>
	<br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-file"></i> Notifications</strong></div>
            <div class="pull-right">
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-envelope"></i> Send Push Notification', array('controller' => 'notifications', 'action' => 'send_push_notification', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Send Push Notification', 'id' => 'sendPushNotification')); ?>
				<?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> New Notification', array('controller' => 'notifications', 'action' => 'addnotification', 'superadmin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Notification', 'id' => 'addNotification')); ?>
            </div>
        </div>
    </div>
    <br><br>
    <br/>
    <div class="span7">   
        <div class="widget stacked widget-table action-table">
            <div class="widget-content">
                <table class="table table-striped table-bordered">
					<colgroup>
						<col width="8%">
						<col width="23%" />
						<col width="23%" />
						<col width="18%" />
						<col width="18%" />
						<col width="12%" />
					</colgroup>
                    <thead>
                        <tr>
							<th>&nbsp;</th>
                            <th scope="col"><?php echo $this->Paginator->sort('Notification.subject', 'Subject'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Notification.body', 'Notification'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Notification.created', 'Notification On'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('UserNotification.sender_id', 'Sender'); ?></th>
                            <th scope="col">Actions</th>
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
									<td scope="col"><?php
									echo $this->Text->truncate(
										$notification_data['Notification']['subject'], 50, array(
										'ellipsis' => '...',
										'exact' => true
										)
									);
									?></td>
									<td scope="col"><?php
									echo $this->Text->truncate(
										$notification_data['Notification']['body'], 100, array(
										'ellipsis' => '...',
										'exact' => true
										)
									);
									?></td>
									<td scope="col"><?php echo $notification_data['Notification']['date']; ?></td>
									<td scope="col"><?php echo $notification_data['Sender']['first_name'] . ' ' . $notification_data['Sender']['last_name']; ?></td>
									<td scope="col">
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-search"></i> ', array('controller' => 'notifications', 'action' => 'view_notification', base64_encode($notification_data['Notification']['id']), 'admin' => true), array('escape' => false, 'class' => 'btn btn-sm btn-default viewNotificationRow', 'title' => 'View Notification Detail')); ?>									
										<?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $notification_data['Notification']['subject'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Notification', 'data-href' => $this->Html->url(array('controller' => 'notifications', 'action' => 'deletenotification', base64_encode($notification_data['Notification']['id']))))); ?>
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
                <h3>Delete Notification</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete this notification  "<strong>selected user</strong>"?
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
<!-- Modal Add Client -->
<?php echo $this->Element('admin/addNotification'); ?>
<?php echo $this->Element('notifications/admin_view_notification'); ?>
<?php echo $this->Element('notifications/admin_send_push_notification'); ?>
<!-- Modal Edit Client -->

<script type="text/javascript">
	$(document).ready(function() {
		$(".delete").click(function(event) {
			event.preventDefault();
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#DeleteModel').modal('show');
		});
		$(".resend").click(function(event) {
			event.preventDefault();
			$('#ResendModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#ResendModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#ResendModel').modal('show');
		});
		$("#addNotification").click(function(event) {
			event.preventDefault();
			$('#AddNotificationModel').modal('show');
		});
	});
</script>