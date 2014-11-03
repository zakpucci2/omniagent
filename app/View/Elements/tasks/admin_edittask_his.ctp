<?php //echo $this->Html->script('jquery.validate', array('inline' => false));    ?>
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
<div class="modal fade" id="EditUserTaskModelhis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">


	<?php echo $this->Form->create('tasks', array('type' => 'file', 'action' => 'ajax_task_his_update', 'name' => 'TaskEditUserTaskFormhis', 'id' => 'TaskEditUserTaskFormhis', 'role' => 'form', 'class' => 'form-horizontal')); ?>
	<?php echo $this->Form->end(); ?>
</div>
<script type="text/html" id='EditUserTaskModelTemplatehis'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Edit Task History</h4>
            </div>
            <div class="modal-body">

                <div class="panel panel-default1" id="panel21">
                    <div class="panel-heading1">

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
                        <input type="text" id="UserTaskTaskTitle" class="form-control" maxlength="255" name="data[TaskHistory][task_title]" value="<%= task_data.TaskHistory.task_title %>" required="required" placeholder= "Name this task - ex: Responsive Web Design" />
                    </div><br>
                    <div class="input-group disable-radius" style="text-align:left;">
                        <span class="input-group-addon">Client</span> &nbsp;
                        <%= task_data.User.user_name %>@<?php echo Configure::read('SITE_EMAIL.Email'); ?>
                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon">Deadline</span>
                        <input type="datetime-local" id="UserTaskDeadlineDatetime" disabled class="form-control" name="data[TaskHistory][deadline_datetime]" value="<%= task_data.TaskHistory.deadline_datetime %>" id = "date01" required="required" />
                    </div><br>
                    <div class="input-group range">
                        <span class="input-group-addon">Status</span>

						<div class="progress progress-striped progress_modify">
							<div class="progress-bar progress-bar-info probar_width_14" role="progressbar" aria-valuenow="<%= task_data.TaskHistory.status_completed %>" aria-valuemin="0" aria-valuemax="100" style="width: <%= task_data.TaskHistory.status_completed %>%">
								<center class="task_progress_14"><%= task_data.TaskHistory.status_completed %>%</center>
							</div>
						</div>

                    </div><br>
                    <div class="input-group">
                        <span class="input-group-addon">Description</span>
                        <textarea rows="6" class="form-control" id="UserTaskTaskDescription" name="data[TaskHistory][task_description]" placeholder="Task Description" required="required"><%= task_data.TaskHistory.task_description %></textarea>
                    </div>

                </center>
            </div>
            <div class="modal-footer">
                <center>
                    <button id="submitHisButton" name="singlebutton" type="submit" class="btn btn-primary">update</button> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </center>




            </div>
        </div>
    </div>
</script>
<script type="text/javascript">
    $(document).ready(function () {

        $("#UserTaskEditStatusCompleted_1").live('change', function () {
            $("#completedStatusValue_1").html($(this).val());

        });

        $('#TaskEditUserTaskFormhis').validate({ // initialize the plugin

            submitHandler: function (form) {

                $('body').modalmanager('loading');
                $.ajax({
                    dataType: 'html',
                    type: 'post',
                    url: $("#TaskEditUserTaskFormhis").attr("action"),
                    data: $(form).serialize(),
                    success: function (responseData) {
                        obj = jQuery.parseJSON(responseData);

                        $(".task_title_" + obj.TaskHistory.id).html(obj.TaskHistory.task_title);
                        $(".task_deadline_" + obj.TaskHistory.id).html(obj.TaskHistory.deadline_datetime);
                        $(".task_modifiy_" + obj.TaskHistory.id).html(obj.TaskHistory.modified);
                        $(".task_desc_" + obj.TaskHistory.id).html(obj.TaskHistory.task_description);
                        $(".task_progress_" + obj.TaskHistory.id).html(obj.TaskHistory.status_completed + "%");
                        $(".probar_width_" + obj.TaskHistory.id).css("width", obj.TaskHistory.status_completed + "%");


                        $('#EditUserTaskModelhis').modal('hide');
                        $('body').modalmanager('removeLoading');
                    },
                    error: function (responseData) {
                        console.log('Ajax request not recieved!');
                    }
                });



            }
        });



        $("#UserTaskEditStatusCompleted").change(function () {
            $("#completedStatusValue").html(this.value);
        });

        var editTaskURL = $('#TaskEditUserTaskFormhis').attr('action');
        $(".editUserTaskRowhis").live("click", function (event) {


            event.preventDefault();
            var urlLoc = $(this).attr('href');

            $('body').modalmanager('loading');
            $.getJSON(urlLoc, function (data) {
                $('#TaskEditUserTaskFormhis').attr('action', editTaskURL + '/' + data.taskData.TaskHistory.id);
                var template = $("#EditUserTaskModelTemplatehis").html();
                $("#TaskEditUserTaskFormhis").html(_.template(template, {task_data: data.taskData, PopupTitle: data.PopupTitle}));

                if ($('#EditUserTaskModelhis').modal('show')) {
                    // $('#EditUserTaskModel').modal('hide');
                }
                $("#UserTaskEditStatusCompleted").change(function () {
                    $("#completedStatusValue").html(this.value);
                });
                $("#TaskEditUserTaskFormhis").validate();
            });
        });


    });
</script>
