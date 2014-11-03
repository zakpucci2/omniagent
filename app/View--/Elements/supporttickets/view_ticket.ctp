<!-- Modal View Message-->
<div class="modal hide fade" id="viewTicketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<!-- Modal View Message End-->
<script type="text/html" id='viewTicketTemplate'>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">View Support Ticket Detail</h4>
			</div>

			<div class="log-margin" style="padding-left:26px;"> <strong class="lead"><i class="icon-file"></i></i> Ticket History</strong></div>      
			<!-----------Logs---------------------->
			<%
			if ((ticket_data_logs).length > 0) {
			%>
			<%
			for (i = 0; i < (ticket_data_logs).length; i++) {
			console.log(ticket_data_logs);
			if(i%2==0){
			put_class="log-even log-border log-margin";
			}else{
			put_class="log-border log-margin";
			}
			%>
			<div class="g1_00 <%=put_class %>" style="width:89%; margin-left: 25px;">
				<div class="col-sm-6 pin-cursor-pointer" style="padding: 2px;"><strong><%= ticket_data_logs[i].SupportTicketHistory.subject %></strong> </div>
				<div class="col-sm-6"><i>By: </i><%= ticket_data_logs[i].Admin.first_name %> <%= ticket_data_logs[i].Admin.last_name %></div>
				
				<div class=" clearfix"></div>
				<div class="g1_11 ">
					
					<div class="col-sm-12"  style="padding: 2px;"><label>Modified: </label><i><%= ticket_data_logs[i].SupportTicketHistory.created %></i> </div>
					<div class=" clearfix"></div>
					<div class="col-sm-12"  style="padding: 2px;"><label>Message: </label><div class="quick-zero">&nbsp;</div><%= ticket_data_logs[i].SupportTicketHistory.body %> </div>
				</div>
			</div>
			<%
			}
			}else{
			%>
			<div class="col-sm-12"><center>No Ticket History</center></div>
			<div class=" clearfix"></div>
			<% } %>
			<!--------------------------------->
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
										<td>Created On</td><td><%= ticket_data.SupportTicket.date %></td>
									</tr>
									<tr>
										<td>Subject</td><td><%= ticket_data.SupportTicket.subject %></td>
									</tr>
									<tr>
										<td colspan="2"><%= ticket_data.SupportTicket.message %></td>
									</tr>
									<tr>
										<td>Status</td><td><%= ticket_data.SupportTicket.current_status %></td>
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
    $(document).ready(function () {
        $(".viewTicketRow").click(function (event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function (data) {
                //alert(data);

                var template = $("#viewTicketTemplate").html();
                $("#viewTicketModal").html(_.template(template, {ticket_data: data.ticketData,ticket_data_logs: data.ticket_his, PopupTitle: data.PopupTitle}));
                $('#viewTicketModal').modal('show');
            });
        });
    });
</script>