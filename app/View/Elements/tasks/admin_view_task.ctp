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
                            <div class="col-sm-5 pin-cursor-pointer"><strong class="viewg_89 task_title_<%= task_data_logs[i].TaskHistory.id %>"><%= task_data_logs[i].TaskHistory.task_title %></strong> </div>
                            <div class="col-sm-5 .quick-zero-h">
                                <div class="progress progress-striped progress_modify" >
                                    <div style="width: <%= task_data_logs[i].TaskHistory.status_completed  %>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<%= task_data_logs[i].TaskHistory.status_completed  %>" role="progressbar" class="progress-bar progress-bar-info probar_width_<%= task_data_logs[i].TaskHistory.id %>">
                                        <center class="task_progress_<%= task_data_logs[i].TaskHistory.id %>"><%= task_data_logs[i].TaskHistory.status_completed %>%</center>
                                    </div>
                                </div>
                            </div>
							
							<div class="col-sm-2" style="margin-right: 0px; text-align: right; padding-right: 14px;">
								<a href="<?php echo $this->webroot ?>admin/tasks/edittask_his/<%= task_data_logs[i].TaskHistory.id %>" class="editUserTaskRowhis btn btn-xs btn-primary" title="Edit task history" ><i class="glyphicon glyphicon-pencil"></i></a>
								<a  href="javascript:void(0)" data-href="<?php echo $this->webroot ?>admin/tasks/deltask_his/<%= task_data_logs[i].TaskHistory.id %>" class="btn btn-xs btn-danger delete" title="Delete task history" ><i class="glyphicon glyphicon-trash"></i></a>
								<?php //echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $user_task_data['UserTask']['task_title'], 'escape' => false, 'class' => 'btn btn-xs btn-danger delete', 'title' => 'Delete Task', 'data-href' => $this->Html->url(array('controller' => 'tasks', 'action' => 'delete_task', base64_encode($user_task_data['UserTask']['id']), 'admin' => true)))); ?>
							</div>
							<div class=" clearfix">&nbsp;</div>							

                            <div class="g1_11 ">
                                <div class="col-sm-6"><label>Deadline: </label> <i class="task_deadline_<%= task_data_logs[i].TaskHistory.id %>"><%= task_data_logs[i].TaskHistory.deadline_datetime %></i> </div>
                                <div class="col-sm-6"><label>Modified: </label><i class="task_modifiy_<%= task_data_logs[i].TaskHistory.id %>"><%= task_data_logs[i].TaskHistory.modified %></i> </div>
                                <div class=" clearfix"></div>
                                <div class="col-sm-12"><label>Description: </label><div class="quick-zero">&nbsp;</div><p class="task_desc_<%= task_data_logs[i].TaskHistory.id %>"><%= task_data_logs[i].TaskHistory.task_description %> </p></div>
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
                                <td>Status</td>
                                <td>
                                    <div class="progress progress-striped">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<%= task_data.UserTask.status_completed %>" aria-valuemin="0" aria-valuemax="100" style="width: <%= task_data.UserTask.status_completed %>%">
                                            <center><%= task_data.UserTask.status_completed %>%</center>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Deadline</td><td><%= task_data.UserTask.date %></td>
                            </tr>
                            <tr>
                                <td>Description</td><td><%= task_data.Task.description %></td>
                            </tr>
                            
							<tr>
                                <td>Required Info 1</td><td><%= task_data.Task.info1 %></td>
                            </tr>
							
							<tr>
                                <td>Required Info 2</td><td><%= task_data.Task.info2 %></td>
                            </tr>
							
							<tr>
                                <td>Required Info 3</td><td><%= task_data.Task.info3 %></td>
                            </tr>
							
							<tr>
                                <td>Required Info 4</td><td><%= task_data.Task.info4 %></td>
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
        $(".viewg_89").live("click", function() {
            $(this).parent().parent().children(".g1_11").toggle();
        });
    });
</script>
