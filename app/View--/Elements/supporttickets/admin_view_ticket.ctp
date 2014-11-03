<div class="modal fade" id="ViewSupportTicketModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
</div>
<script type="text/html" id='ViewSupportTicketTemplate'>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">View Support Ticket Details</h4>
			</div>
			<div class="modal-body">
				
				<!-----------Logs---------------------->
				<%

				if ((ticket_data_logs).length > 0) {

				%>
				<div class="log-margin"> <strong class="lead"><i class="glyphicon glyphicon-file"></i> Ticket History</strong></div>
				<%
				for (i = 0; i < (ticket_data_logs).length; i++) {
				if(i%2==0){
				put_class="log-even log-border log-margin";
				}else{
				put_class="log-border log-margin";
				}

				%>


				<div class="g1_00 <%=put_class %>">
					<div class="col-sm-6 pin-cursor-pointer"><strong><%= ticket_data_logs[i].SupportTicketHistory.subject %></strong> </div>
					<div class="col-sm-6"><i>By: </i><%= ticket_data_logs[i].Admin.first_name %> <%= ticket_data_logs[i].Admin.last_name %></div><div class=" clearfix"></div>
					<div class="g1_11_45">
						<div class="col-sm-12"><label>Modified: </label><i><%= ticket_data_logs[i].SupportTicketHistory.created %></i> </div>
						<div class=" clearfix"></div>
						<div class="col-sm-12"><label>Message: </label><div class="quick-zero">&nbsp;</div><%= ticket_data_logs[i].SupportTicketHistory.body %> </div>
					</div>
				</div>
				<div class=" clearfix"></div>
				<%
				}
				%>
				<hr/>
				<%
				}else{%>
				<div class="col-sm-12"><center>No Ticket History</center></div>
				<hr/>
				<div class=" clearfix"></div>
				<%}
				%>
				<!--------------------------------->

				
				<center>
					<div class="input-group">
						<span class="input-group-addon">From</span>
						<input type="text" id="TicketSenderUserName" class="form-control" maxlength="100" readonly="readonly" value="<%= ticket_data.User.user_name + '@' %><?php echo Configure::read('SITE_EMAIL.Email'); ?> [<%= ticket_data.User.first_name + ' ' + ticket_data.User.last_name %>]" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Arrived On</span>
						<input type="text" id="TicketRecievedOn" class="form-control" maxlength="100" readonly="readonly" value="<%= ticket_data.SupportTicket.date %>" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Subject</span>
						<input type="text" id="TicketSubject" class="form-control" maxlength="255" readonly="readonly" value="<%= ticket_data.SupportTicket.subject %>" />
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Message</span>
						<textarea rows="6" class="form-control" readonly="readonly" id="TicketMessageBody"><%= ticket_data.SupportTicket.message %></textarea>
					</div><br>
					<div class="input-group">
						<span class="input-group-addon">Status</span>
						<input type="text" id="TicketStatus" class="form-control" maxlength="255" readonly="readonly" value="<%= ticket_data.SupportTicket.current_status %>" />
					</div><br>
				</center>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
			
			
			<p style="margin-bottom: 20px">&nbsp;</p>
		</div>
		
		
		
	</div>
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".viewTicketRow").click(function (event) {
            event.preventDefault();
            var urlLoc = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(urlLoc, function (data) {
                var template = $("#ViewSupportTicketTemplate").html();
                $("#ViewSupportTicketModal").html(_.template(template, {ticket_data: data.ticketData, ticket_data_logs: data.ticket_his, PopupTitle: data.PopupTitle}));
                $('#ViewSupportTicketModal').modal('show');
                console.log((data.all_data).length);
				html='';
				for(i=0; i< (data.all_data).length;i++){
					html +="<li style=\"border-bottom:1px solid #d1d1d1;\"><a class='log_by_user' style='cursor:pointer;'>"+data.all_data[i].User.first_name+" "+data.all_data[i].User.last_name+"</a><p>Subject:"+data.all_data[i].SupportTicketHistory.subject+"</p><p>Message:"+data.all_data[i].SupportTicketHistory.body+"</p></li>";
				}
				$("#log_list").html(html);


            });
        });
		
		$("#loginfo").live("click",function(){
			$("#log_list").toggle();
		});
		
		
        $(".g1_00").live("click", function () {
            $(this).children(".g1_11_45").toggle();
        });
    });
</script>