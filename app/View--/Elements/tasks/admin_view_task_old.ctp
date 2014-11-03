<!-- Modal View Task-->
<div class="modal fade" id="viewAdminTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
</div>
<!-- Modal View Task End-->
<script type="text/html" id='viewAdminTaskTemplate'>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">View Task Detail</h4>

                           <div class="log-margin"> <strong class="lead"><i class="icon-time"></i> Task History</strong></div>   
                        <!-----------Logs---------------------->
                        <%

                        if ((task_data_logs).length > 0) {
                        
                        %>
                       
                        <%
                        for (i = 0; i < (task_data_logs).length; i++) {

                        console.log(task_data_logs);

                        
                        if(i%2==0){
                        
                        put_class="log-even log-border log-margin";
                        }else{
                        put_class="log-border log-margin";
                        }
                        %>


                        <div class="g1_00 <%=put_class %>">
                            <div class="col-sm-6 pin-cursor-pointer"><strong><%= task_data_logs[i].TaskHistory.task_title %></strong> </div>
                            <div class="col-sm-6 ">
                                
<div style="margin-bottom: 9px;" class="progress progress-info progress-striped">
										<div style="width: <%= task_data_logs[i].TaskHistory.status_completed  %>%" class="bar">
											<%= task_data_logs[i].TaskHistory.status_completed  %>%
										</div>
									</div>

                                
                            </div>
<div class=" clearfix"></div>
                            <div class="g1_11 ">
                                <div class="col-sm-6"><label>Deadline: </label> <i><%= task_data_logs[i].TaskHistory.deadline_datetime %></i> </div>
                                <div class="col-sm-6"><label>Modified: </label><i><%= task_data_logs[i].TaskHistory.modified %></i> </div>
                                <div class=" clearfix"></div>
                                <div class="col-sm-12"><label>Description: </label><div class="quick-zero">&nbsp;</div><%= task_data_logs[i].TaskHistory.task_description %> </div>
                            </div>
                        </div>




                        <%
                        
                        }
                        }else{
                        %>
                        <div class="col-sm-6"><center>No Task History</center></div>
                        <% } %>
                        <!--------------------------------->
			</div>


			<div class="modal-body" style="overflow:inherit;">
				<center>
					<br>
					<div class="row-fluid">   
						<div class="widget stacked widget-table action-table">
							<div class="widget-content">
								<table class="table table-striped table-bordered">
									<tbody>
										<tr>
											<td>Title</td><td><%= task_data.UserTask.task_title %></td>
										</tr>
										<tr>
											<td>Platform</td><td><%= task_data.Task.name %></td>
										</tr>
										<tr>
											<td>Platform</td><td><%= task_data.Task.name %></td>
										</tr>
										<tr>
											<td colspan="2"><%= task_data.UserTask.task_description %></td>
										</tr>
										<tr>
											<td>Completed Status</td><td><div class="progress progress-info progress-striped" style="margin-bottom: 9px;">
												<div class="bar" style="width:<%= task_data.UserTask.status_completed %>%">
													<%= task_data.UserTask.status_completed %>%
												</div>
											</div></td>
										</tr>
										<tr>
											<td>End Date</td><td><%= task_data.UserTask.date %></td>
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
        $(".viewUserTaskRow").click(function(event) {
            var currObj = $(this);
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                var template = $("#viewAdminTaskTemplate").html();
                $("#viewAdminTaskModal").html(_.template(template, {task_data:data.taskData,task_data_logs: data.tastData ,PopupTitle:data.PopupTitle}));
                $('#viewAdminTaskModal').modal('show');
            });
        });
        $(".g1_00").live("click", function() {
            $(this).children(".g1_11").toggle();
        });
    });
</script>