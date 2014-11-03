<?php echo $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>
    .star {color:red;}
    .block-icon-default { color: #E34C3B !important; }
    .panel-heading a:after {
        font-family: 'Glyphicons Halflings';
        content: "\e114";    
        float: right; 
        color: grey; 
    }
    .panel-heading a.collapsed:after {
        content: "\e080";
    }
</style>
<div class="modal fade" id="viewAdminTaskModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
</div>
<script type="text/html" id='viewAdminTaskTemplate'>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">View Task Detail</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default1" id="panel21">
                    <div class="panel-heading1">
                      
                        <!-----------Logs---------------------->
                        <%

                        if ((task_data_logs).length > 0) {

                        %>
                        <div class="log-margin"> <strong class="lead"><i class="glyphicon glyphicon-time"></i> Task History</strong></div>
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
                            <div class="col-sm-6 .quick-zero-h">



                                <div class="progress progress-striped progress_modify" >
                                    <div style="width: <%= task_data_logs[i].TaskHistory.status_completed  %>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<%= task_data_logs[i].TaskHistory.status_completed  %>" role="progressbar" class="progress-bar progress-bar-info">
                                        <center><%= task_data_logs[i].TaskHistory.status_completed %>%</center>
                                    </div>
                                </div>
                            </div>

                            <div class="g1_11 ">
                                <div class="col-sm-6"><label>Deadline: </label> <i><%= task_data_logs[i].TaskHistory.deadline_datetime %></i> </div>
                                <div class="col-sm-6"><label>Modified: </label><i><%= task_data_logs[i].TaskHistory.modified %></i> </div>
                                <div class=" clearfix"></div>
                                <div class="col-sm-12"><label>Description: </label><div class="quick-zero">&nbsp;</div><%= task_data_logs[i].TaskHistory.task_description %> </div>
                            </div>
                        </div>
                        <%

                        }
                        } else { %>
                        <div>No task history found.</div>
                        <% }
                        %>
                        <!--------------------------------->
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div id="put_html" class="puthtml"></div>
                        </div>
                    </div>
                </div>
                <center>
                    <table class="table table-striped table-bordered">
                        <tbody>
                            <tr>
                                <td>Title</td><td><%= task_data.UserTask.task_title %></td>
                            </tr>
                            <tr>
                                <td>Platform</td><td><%= task_data.Task.name %></td>
                            </tr>
                            <tr>
                                <td colspan="2"><%= task_data.UserTask.task_description %></td>
                            </tr>
                            <tr>
                                <td>Completed Status</td>
                                <td>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<%= task_data.UserTask.status_completed %>" aria-valuemin="0" aria-valuemax="100" style="width: <%= task_data.UserTask.status_completed %>%">
                                            <center><%= task_data.UserTask.status_completed %>%</center>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>End Date</td><td><%= task_data.UserTask.date %></td>
                            </tr>
                        </tbody>
                    </table>
                </center>
            </div>
            <div class="modal-footer">
                <center>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
                $("#viewAdminTaskModal").html(_.template(template, {task_data: data.taskData, task_data_logs: data.tastData, PopupTitle: data.PopupTitle}));
                $('#viewAdminTaskModal').modal('show');
            });
        });
        $(".g1_00").live("click", function() {
            $(this).children(".g1_11").toggle();
        });
    });
</script>
