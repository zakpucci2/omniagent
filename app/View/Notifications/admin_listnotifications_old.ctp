<?php $this->Html->css('bootstrap/bootstrap-editable', null, array('inline' => false)); ?>
<?php $this->Html->script('bootstrap/bootstrap-editable', array('inline' => false)); ?>
<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div><br>
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-file"></i> Notifications</strong></div>
            <div class="pull-right">
				<?php // echo $this->Html->link('<i class="glyphicon glyphicon-envelope"></i> Send Push Notification', array('controller' => 'notifications', 'action' => 'send_push_notification', 'admin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'Send Push Notification', 'id' => 'sendPushNotification')); ?>
				<?php // echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> New Notification', array('controller' => 'notifications', 'action' => 'addnotification', 'admin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Notification', 'id' => 'addNotification')); ?>
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
						<col width="25%" />
						<col width="30%" />
						<col width="10%" />
						<col width="15%" />
						<col width="15%" />
						<col width="5%" />
					</colgroup>
                    <thead>
                        <tr>
                            <th scope="col"><?php echo $this->Paginator->sort('Notification.subject', 'Subject'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Notification.body', 'Body'); ?></th>
                            <th scope="col"><?php echo $this->Paginator->sort('Notification.created', 'Notification On'); ?></th>
                            <th scope="col">Receivers</th>
                            <th scope="col">Sender</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $notification_data) {
								?>
								<tr>
									<td scope="col"><?php echo $notification_data['Notification']['subject']; ?></td>
									<td scope="col"><?php echo $notification_data['Notification']['body']; ?></td>
									<td scope="col"><?php echo $notification_data['Notification']['ago']; ?></td>
									<td scope="col"><?php
										if (!empty($notification_data['UserNotification'])) {
											$receivers = '';
											$receivers_id = '';
											foreach ($notification_data['UserNotification'] as $user_notification_data) {
												$receivers.=$user_notification_data['Receiver']['first_name'] . ' ' . $user_notification_data['Receiver']['last_name'] . ',';
												$receivers_id.=$user_notification_data['receiver_id'] . ',';
											}
											?>
											<a href="#" id="<?php echo $notification_data['Notification']['id']; ?>" data-type="checklist"  data-value="<?php echo @trim(@$receivers_id, ',') ?>" data-pk="<?php echo $notification_data['Notification']['id']; ?>" data-url="<?php echo $this->webroot; ?>notifications/addusers" data-title="Select Users"><?php echo trim($receivers, ","); ?></a>
											<?php
										} else {
											?><a href="#" id="<?php echo $notification_data['Notification']['id']; ?>" data-type="checklist"   data-pk="<?php echo $notification_data['Notification']['id']; ?>" data-url="<?php echo $this->webroot; ?>notifications/addusers" data-title="Select Users">Select Users</a>
											<?php
										}
										?>
									</td>
									<td scope="col"><?php echo @$notification_data['UserNotification'][0]['Sender']['first_name'] . ' ' . @$notification_data['UserNotification'][0]['Sender']['last_name']; ?></td>
									<td scope="col"><?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $notification_data['Notification']['subject'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Notification', 'data-href' => $this->Html->url(array('controller' => 'notifications', 'action' => 'deletenotification', base64_encode($notification_data['Notification']['id']))))); ?></td> 
								</tr>
							<script type="text/javascript">
								$(document).ready(function() {
									$('#<?php echo $notification_data['Notification']['id']; ?>').editable({
										value: [<?php echo @trim(@$receivers_id, ',') ?>],
										separator: ',',
										source: [<?php echo $users; ?>]
									});
								});
							</script>
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
<?php // echo $this->Element('notifications/admin_send_push_notification'); ?>
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
