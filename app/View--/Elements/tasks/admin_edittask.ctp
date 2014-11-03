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
<div class="modal fade" id="EditUserTaskModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
	<?php echo $this->Form->create('tasks', array('type' => 'file', 'action' => 'edittask', 'name' => 'TaskEditUserTaskForm', 'id' => 'TaskEditUserTaskForm', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/html" id='EditUserTaskModelTemplate'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Edit Task </h4>
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
                            <div class="g1_11_45">
                                <div class="col-sm-6"><label>Deadline: </label> <i><%= task_data_logs[i].TaskHistory.deadline_datetime %></i> </div>
                                <div class="col-sm-6"><label>Modified: </label><i><%= task_data_logs[i].TaskHistory.modified %></i> </div>
                                <div class=" clearfix"></div>
                                <div class="col-sm-12"><label>Description: </label><div class="quick-zero">&nbsp;</div><%= task_data_logs[i].TaskHistory.task_description %> </div>
                            </div>
                        </div>
                        <%
                        }
                        }
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

                    <div class="input-group disable-radius">
                        <span class="input-group-addon">Platform</span>&nbsp;
                        <%= task_data.Task.name %>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon">Title</span>
                        <input type="text" id="UserTaskTaskTitle" class="form-control" maxlength="255" name="data[UserTask][task_title]" value="<%= task_data.UserTask.task_title %>" required="required" placeholder= "Name this task - ex: Responsive Web Design" />
                    </div><br>
                    <div class="input-group disable-radius">
                        <span class="input-group-addon">Client</span> &nbsp;
                        <%= task_data.Client.user_name %>@<?php echo Configure::read('SITE_EMAIL.Email'); ?>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon">Deadline</span>
                        <input type="datetime-local" id="UserTaskDeadlineDatetime" class="form-control" name="data[UserTask][deadline_datetime]" value="<%= task_data.UserTask.deadline_datetime %>" id = "date01" required="required" />
                    </div><br>
                    <div class="input-group range">
                        <span class="input-group-addon">Status</span>
                        <input type="range" id="UserTaskEditStatusCompleted" class="form-control" name="data[UserTask][status_completed]" min="1" max="100" value="<%= task_data.UserTask.status_completed %>" />
                        <output id="completedStatusValue"><%= task_data.UserTask.status_completed %></output>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon">Description</span>
                        <textarea rows="6" class="form-control" id="UserTaskTaskDescription" name="data[UserTask][task_description]" placeholder="Task Description" required="required"><%= task_data.UserTask.task_description %></textarea>
                    </div>
                </center>
            </div>
            <div class="modal-footer">
                <center>
                    <button id="singlebutton" name="singlebutton" type="submit" class="btn btn-primary">update</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>




            </div>
        </div>
    </div>
</script>
<script type="text/javascript">
    $(document).ready(function () {

        $(".g1_00").live("click", function () {
            $(this).children(".g1_11_45").toggle();
        });



        $("#UserTaskEditStatusCompleted").change(function () {
            $("#completedStatusValue").html(this.value);
        });
        $("#TaskEditUserTaskForm").validate();
        var editTaskURL = $('#TaskEditUserTaskForm').attr('action');
        $(".editUserTaskRow").click(function (event) {
            event.preventDefault();
            var urlLoc = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(urlLoc, function (data) {
                $('#TaskEditUserTaskForm').attr('action', editTaskURL + '/' + data.taskData.UserTask.id);
                var template = $("#EditUserTaskModelTemplate").html();
                $("#TaskEditUserTaskForm").html(_.template(template, {task_data: data.taskData, task_data_logs: data.tastData, PopupTitle: data.PopupTitle}));

                $('#EditUserTaskModel').modal('show');
                $("#UserTaskEditStatusCompleted").change(function () {
                    $("#completedStatusValue").html(this.value);
                });
                $("#TaskEditUserTaskForm").validate();
            });
        });


    });
</script>
