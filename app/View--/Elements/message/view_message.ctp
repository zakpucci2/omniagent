<!-- Modal View Message-->
<div class="modal hide fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<!-- Modal View Message End-->
<script type="text/html" id='viewMessageTemplate'>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">View Message Detail</h4>
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
											<td>From</td><td><%= message_data.Sender.first_name + " " + message_data.Sender.last_name %></td>
										</tr>
										<tr>
											<td>Username/Email</td><td><%= message_data.Sender.user_name %>@<?php echo Configure::read('SITE_EMAIL.Email'); ?></td>
										</tr>
										<tr>
											<td>Received On</td><td><%= message_data.Message.date %></td>
										</tr>
										<tr>
											<td>Subject</td><td><%= message_data.Message.subject %></td>
										</tr>
										<tr>
											<td colspan="2"><%= message_data.Message.body %></td>
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
        $(".viewBadgeRow").click(function(event) {
			var currObj = $(this);
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                var template = $("#viewMessageTemplate").html();
                $("#viewModal").html(_.template(template, {message_data:data.messageData,PopupTitle:data.PopupTitle}));
                $('#viewModal').modal('show');
				$('#viewModal').on('hidden.bs.modal', function () {
					window.location.reload();
					// $(currObj).parent().parent().removeClass('boldtr');
				});
            });
        });
        $(".viewMessageRow").click(function(event) {
			var currObj = $(this);
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                var template = $("#viewMessageTemplate").html();
                $("#viewModal").html(_.template(template, {message_data:data.messageData,PopupTitle:data.PopupTitle}));
                $('#viewModal').modal('show');
				$('#viewModal').on('hidden.bs.modal', function () {
					$(currObj).parent().parent().removeClass('boldtr');
				});
            });
        });
    });
</script>