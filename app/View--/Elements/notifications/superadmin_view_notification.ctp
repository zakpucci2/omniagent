<?php //$this->Html->css('bootstrap/form_validaion',null, array('inline' => false));  ?>
<?php $this->Html->css('bootstrap/bootstrap-editable', null, array('inline' => false)); ?>
<?php $this->Html->script('bootstrap/bootstrap-editable', array('inline' => false)); ?>
<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>.star {color:red;}</style>
<!-- Modal View Notification-->
<div class="modal fade" id="viewNotificationModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<!-- Modal View Notification End-->
<script type="text/html" id='viewNotificationTemplate'>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">View Notification Detail</h4>
			</div>
			<div class="modal-body">
				<center>
					<br>
					<div class="row-fluid">   
						<div class="widget stacked widget-table action-table">
							<div class="widget-content">
								<table class="table table-striped table-bordered">
									<tbody>
										<tr>
											<td>From</td><td><%= notification_data.Sender.first_name + " " + notification_data.Sender.last_name %></td>
										</tr>
										<tr>
											<td>Receivers</td>
											<td>
												<% if(notification_data.UserNotification != "") { 
													var receivers = '';
													for(var user_notification_data in notification_data.UserNotification) { %>
														<%= user_notification_data %>
														<%

													}
												%>
												<%= receivers %>
												<% } %>
											</td>	
										</tr>
										<tr>
											<td>Received On</td><td><%= notification_data.Notification.ago %></td>
										</tr>
										<tr>
											<td>Subject</td><td><%= notification_data.Notification.subject %></td>
										</tr>
										<tr>
											<td colspan="2"><%= notification_data.Notification.body %></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</center>
			</div>
			<div class="modal-footer">
				<center>
					<button type="button" class="btn btn-default" id="closeViewModel" data-dismiss="modal">Close</button>
				</center>
			</div>
		</div>
	</div>
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".viewNotyBadgeRow").click(function(event) {
			var currObj = $(this);
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                var template = $("#viewNotificationTemplate").html();
                $("#viewNotificationModal").html(_.template(template, {notification_data:data.notificationData,PopupTitle:data.PopupTitle}));
                $('#viewNotificationModal').modal('show');
				$('#viewNotificationModal').on('hidden.bs.modal', function () {
					window.location.reload();
					// $(currObj).parent().parent().removeClass('boldtr');
				});
            });
        });
        $(".viewNotificationRow").click(function(event) {
			var currObj = $(this);
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
				console.log(data);
				var template = $("#viewNotificationTemplate").html();
                $("#viewNotificationModal").html(_.template(template, {notification_data:data.notificationData,PopupTitle:data.PopupTitle}));
                $('#viewNotificationModal').modal('show');
				$('#viewNotificationModal').on('hidden.bs.modal', function () {
					$(currObj).parent().parent().removeClass('boldtr');
				});
            });
        });
    });
</script>