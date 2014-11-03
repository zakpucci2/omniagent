<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<div class="navbar">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".top-nav.nav-collapse,.sidebar-nav.nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="<?php echo Configure::read('FULL_BASE_URL.URL'); ?>"><span><?php echo Configure::read('SITENAME.Name'); ?> User Dashboard</span></a>
            <!-- start: Header Menu -->
            <div class="nav-no-collapse header-nav">
                <ul class="nav pull-right">
                    <li style="padding:7px"><input class="input-large focused" id="focusedInput" type="search" placeholder="Search tags or users" style="border-radius:10px;"></li>

                    <li class="dropdown hidden-phone">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Notifications <span class="badge notificationbadge"><?php echo ((isset($unreadNotifications) && !empty($unreadNotifications)) ? count($unreadNotifications) : 0 ); ?></span>
                        </a>
                        <ul id="notyListing" class="dropdown-menu notifications">
                            <li class="dropdown-menu-title">
                                <span class="notificationsubbadge">You have <?php echo ((isset($unreadNotifications) && !empty($unreadNotifications)) ? count($unreadNotifications) : 0 ); ?> notifications</span>
                                <a href="#refresh" class="refresh"><i class="icon-repeat"></i></a>
                            </li>
                            <!--Single Notification-->
                            <?php if (isset($unreadNotifications) && !empty($unreadNotifications)) {
                                foreach ($unreadNotifications as $unreadNotification) {
                                    ?>
                                    <li>
                                        <a href="<?php echo $this->HTML->url(array('controller' => 'notifications', 'action' => 'view_notification', base64_encode($unreadNotification['Notification']['id']))); ?>" class="viewNotyBadgeRow">
                                            <span class="avatar">
                                                <?php
                                                if ($unreadNotification['Sender']['profile_photo'] == '') {
                                                    echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $unreadNotification['Sender']['first_name'] . " " . $unreadNotification['Sender']['last_name']));
                                                } else {
                                                    if (file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40") . '/' . $unreadNotification['Sender']['profile_photo'])) {
                                                        echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40") . '/' . $unreadNotification['Sender']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $unreadNotification['Sender']['first_name'] . " " . $unreadNotification['Sender']['last_name']));
                                                    } else {
                                                        echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $unreadNotification['Sender']['first_name'] . " " . $unreadNotification['Sender']['last_name']));
                                                    }
                                                }
                                                ?>
                                            </span>
                                            <!-- <span class="icon blue"><i class="icon-user"></i></span> -->
                                            <span class="header">
                                                <span class="from">
                                                    <?php
                                                    echo $this->Text->truncate(
                                                        $unreadNotification['Notification']['subject'], 15, array(
                                                        'ellipsis' => '...',
                                                        'exact' => true
                                                        )
                                                    );
                                                    ?>
                                                </span>
                                                <span class="time">
                                                    <?php echo $unreadNotification['Notification']['ago']; ?>
                                                </span>
                                            </span>
                                            <span class="message">
                                                <?php
                                                echo $this->Text->truncate(
                                                    $unreadNotification['Notification']['body'], 20, array(
                                                    'ellipsis' => '...',
                                                    'exact' => true
                                                    )
                                                );
                                                ?>
                                            </span>  
                                        </a>
                                    </li>
                                <?php }
                            }
                            ?>						

                            <!--Single Notification-->
                            <li>
                                <a href="<?php echo $this->webroot . 'notifications/listnotifications'; ?>" class="dropdown-menu-sub-footer">View all notifications</a>
                            </li>
                        </ul>
                    </li>
                    <!-- start: Notifications Dropdown -->
                    <li class="dropdown hidden-phone">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Tasks <span class="badge inprogressbadge"><?php echo ((isset($inProgressTasks) && !empty($inProgressTasks)) ? count($inProgressTasks) : 0 ); ?></span>
                        </a>
                        <ul class="dropdown-menu tasks">
                            <li class="dropdown-menu-title">
                                <span class="inprogresstaskssubbadge">You have <?php echo ((isset($inProgressTasks) && !empty($inProgressTasks)) ? count($inProgressTasks) : 0 ); ?> tasks in progress</span>
                                <a href="#refresh" class="refresh"><i class="icon-repeat"></i></a>
                            </li>
                            <!--Single Task-->
                            <?php if (isset($inProgressTasks) && !empty($inProgressTasks)) {
                                foreach ($inProgressTasks as $inProgressTasks) {
                                    ?>
                                    <li>
                                        <a href="<?php echo $this->HTML->url(array('controller' => 'tasks', 'action' => 'view_task', base64_encode($inProgressTasks['Task']['id']))); ?>" class="viewTaskBadgeRow">
                                            <span class="header">
                                                <span class="title">
                                                    <?php
                                                    echo $this->Text->truncate(
                                                        $inProgressTasks['UserTask']['task_title'], 35, array(
                                                        'ellipsis' => '...',
                                                        'exact' => true
                                                        )
                                                    );
                                                    ?>
                                                </span>
                                                <span class="percent"></span>
                                            </span>
                                            <div class="taskProgress progressSlim red"><?php echo $inProgressTasks['UserTask']['status_completed']; ?></div> 
                                        </a>
                                    </li>
                                <?php }
                            }
                            ?>
                            <!--Single Task-->
                            <li>
                                <a href="<?php echo $this->webroot . 'tasks/listtasks'; ?>" class="dropdown-menu-sub-footer">View all Tasks</a>
                            </li>	
                        </ul>
                    </li>
                    <!-- end: Notifications Dropdown -->
                    <!-- start: Message Dropdown -->
                    <li class="dropdown hidden-phone">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                            Messages <span class="badge messagebadge"><?php echo ((isset($unreadMessages) && !empty($unreadMessages)) ? count($unreadMessages) : 0 ); ?></span>
                        </a>
                        <ul id="msgListing" class="dropdown-menu messages">
                            <li class="dropdown-menu-title">
                                <span class="messagesubbadge">You have <?php echo ((isset($unreadMessages) && !empty($unreadMessages)) ? count($unreadMessages) : 0 ); ?> messages</span>
                                <a href="#refresh" class="refresh"><i class="icon-repeat"></i></a>
                            </li>	
                            <!--Single Message-->
                            <?php if (isset($unreadMessages) && !empty($unreadMessages)) {
                                foreach ($unreadMessages as $unreadMessage) {
                                    ?>
                                    <li class="messBlock">
                                        <a href="<?php echo $this->HTML->url(array('controller' => 'messages', 'action' => 'view_message', base64_encode($unreadMessage['Message']['id']))); ?>" class="viewBadgeRow">
                                            <span class="avatar">
                                                <?php
                                                if ($unreadMessage['Sender']['profile_photo'] == '') {
                                                    echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $unreadMessage['Sender']['first_name'] . " " . $unreadMessage['Sender']['last_name']));
                                                } else {
                                                    if (file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/' . Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40") . '/' . $unreadMessage['Sender']['profile_photo'])) {
                                                        echo $this->Html->image(Configure::read("IMAGES_SIZES_DIR.ProfilePhoto40x40") . '/' . $unreadMessage['Sender']['profile_photo'], array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $unreadMessage['Sender']['first_name'] . " " . $unreadMessage['Sender']['last_name']));
                                                    } else {
                                                        echo $this->Html->image('profile_photo40x40/40x40.gif', array('class' => 'img-rounded', 'style' => 'border:1px solid white', 'alt' => $unreadMessage['Sender']['first_name'] . " " . $unreadMessage['Sender']['last_name']));
                                                    }
                                                }
                                                ?>
                                            </span>
                                            <span class="header">
                                                <span class="from">
                                                    <?php
                                                    echo $this->Text->truncate(
                                                        $unreadMessage['Message']['subject'], 20, array(
                                                        'ellipsis' => '...',
                                                        'exact' => true
                                                        )
                                                    );
                                                    ?>
                                                </span>
                                                <span class="time">
                                                <?php echo $unreadMessage['Message']['ago']; ?>
                                                </span>
                                            </span>
                                            <span class="message">
                                                <?php
                                                echo $this->Text->truncate(
                                                    $unreadMessage['Message']['body'], 20, array(
                                                    'ellipsis' => '...',
                                                    'exact' => true
                                                    )
                                                );
                                                ?>
                                            </span>  
                                        </a>
                                    </li>
                                <?php }
                            }
                            ?>
                            <!--Single Message-->
                            <li>
                                <a href="<?php echo $this->webroot . 'messages/listmessages'; ?>" class="dropdown-menu-sub-footer">View all messages</a>
                            </li>
                        </ul>
                    </li>
                    <!-- end: Message Dropdown -->
                    <!-- start: User Dropdown -->
                    <li class="dropdown" id="userNameActionLi">
                        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" id="userNameAction">
                            <i class="halflings-icon white user"></i> 
                            <?php echo __(((isset($usersession['User']['business_name']) && !empty($usersession['User']['business_name'])) ? $usersession['User']['business_name'] : ((isset($usersession['User']['first_name']) && !empty($usersession['User']['first_name'])) ? $usersession['User']['first_name'] : $usersession['User']['user_name']))); ?>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="dropdown-menu-title">
                                <span>Account Settings</span>
                            </li>
                            <li id="welcomeDivOops" class="">
                                <a href="<?php echo $this->webroot . 'users/editprofile'; ?>"  >
                                    <i class="halflings-icon wrench"></i>Edit Profile
                                </a>
                            </li>
                            <li>
                                <a href="javascript: void(0);" id="supportModal">
                                    <i class="halflings-icon plus"></i>Support
                                </a>
                            </li>
                            <li><a href="http://www.omnihustle.net/userprofile.html"><i class="halflings-icon user"></i> Profile</a></li>
                            <li><a href="<?php echo $this->webroot . 'users/logout'; ?>"><i class="halflings-icon off"></i> Logout</a></li>
                        </ul>
                    </li>
                    <!-- end: User Dropdown -->
                </ul>
            </div>
            <!-- end: Header Menu -->
        </div>
    </div>
</div>
<!-- Modal Delete All Messages End-->
<?php echo $this->Element('message/view_message'); ?>
<?php echo $this->Element('tasks/view_task'); ?>
<?php echo $this->Element('notifications/view_notification'); ?>
<?php echo $this->Element('supporttickets/add_support_ticket'); ?>
<!-- Modal Send Message-->
<script type="text/javascript">
    var messTimestamp = null;
    var messBoxTimestamp = null;
    var notyTimestamp = null;
    var taskTimestamp = null;
    function updateMessagesBadge() {
        var msgData = {};
        if (typeof messTimestamp !== 'undefined') {
            msgData.timestamp = messTimestamp;
        }
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'latestMessages')); ?>',
            type: "post",
            dataType: "json",
            async: true,
            data: msgData
        }).done(function(resMsgData) {
            var newMsgData = '';
            for (i in resMsgData.messages) {
                newMsgData += resMsgData.messages[i] + '\n';
            }
            if (newMsgData != '') {
                // console.log(newMsgData);
                $(".messagebadge").html(newMsgData);
                $(".messagesubbadge").html("You have " + newMsgData + " messages");
            }
            updateMessagesBadge(resMsgData.timestamp);
        });
    }

    function updateMessagesBadgeBox() {
        var msgBoxData = {};
        if (typeof messBoxTimestamp !== 'undefined') {
            msgBoxData.timestamp = messBoxTimestamp;
        }
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'latestBoxMessages')); ?>',
            type: "post",
            dataType: "json",
            async: true,
            data: msgBoxData
        }).done(function(resMsgBoxData) {
            console.log(resMsgBoxData);
            var newMsgBoxData = '';
            for (mesData in resMsgBoxData.messages) {
                // console.log(resMsgBoxData.messages[mesData].Message.id);
            }
            if (newMsgBoxData != '') {
                // console.log(newMsgBoxData);
                // $(".messagebadge").html(newMsgBoxData);
                // $(".messagesubbadge").html("You have " + newMsgBoxData + " messages");
            }
            updateMessagesBadgeBox(resMsgBoxData.timestamp);
        });
    }

    function updateNotificationsBadge() {
        var notyData = {};
        if (typeof notyTimestamp !== 'undefined') {
            notyData.timestamp = notyTimestamp;
        }
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'notifications', 'action' => 'latestNotifications')); ?>',
            type: "post",
            dataType: "json",
            async: true,
            data: notyData
        }).done(function(resNotyData) {
            var newNotyData = '';
            for (i in resNotyData.notifications) {
                newNotyData += resNotyData.notifications[i] + '\n';
            }
            if (newNotyData != '') {
                // console.log(newNotyData);
                $(".notificationbadge").html(newNotyData);
                $(".notificationsubbadge").html("You have " + newNotyData + " notifications");
            }
            updateNotificationsBadge(resNotyData.timestamp);
        });
    }

    function updateInProgressTasksBadge() {
        var taskData = {};
        if (typeof taskTimestamp !== 'undefined') {
            taskData.timestamp = taskTimestamp;
        }
        $.ajax({
            url: '<?php echo $this->Html->url(array('controller' => 'tasks', 'action' => 'latestInProgressTasks')); ?>',
            type: "post",
            dataType: "json",
            async: true,
            data: taskData
        }).done(function(resTaskData) {
            var newTaskData = '';
            for (i in resTaskData.tasks) {
                newTaskData += resTaskData.tasks[i] + '\n';
            }
            if (newTaskData != '') {
                // console.log(newNotyData);
                $(".inprogressbadge").html(newTaskData);
                $(".inprogressbadgesubbadge").html("You have " + newTaskData + " tasks in progress");
            }
            updateInProgressTasksBadge(resTaskData.timestamp);
        });
    }

    $(document).ready(function() {
        updateMessagesBadge();
        // updateMessagesBadgeBox();
        updateNotificationsBadge();
        updateInProgressTasksBadge();
        $(".refresh").click(function() {
            window.location.reload();
        });

        $("#supportModal").click(function(event) {
            event.preventDefault();
            $('#addSupportTicketModal').modal('show');
        });

<?php
if ($usersession['User']['is_tour_completed'] == 1 && (!empty($notify_data))) {
    $i = 1;
    $notiTypes = Configure::read('notification_types');
    foreach ($notify_data as $data) {
        $notiMsg = "";

        // $notiMsg = '<div>From: ' . $data['Sender']['first_name'] . ' ' . $data['Sender']['last_name'] . '</div>';
        // $notiMsg .= '<div>Subject: ' . $data['Notification']['subject'] . '</div>';
        $notiMsg .= '<div>' . $this->Html->image(Configure::read('IMAGES_SIZES_DIR.ProfilePhoto40x40') . '/' . $data['Sender']['profile_photo'], array("class" => "img-thumbnail", "style" => "margin:0 10px 0 0;float:left;"));
        $notiMsg .= " " . $notiTypes[$data["Notification"]["notification_type_id"]];
        $notiMsg .= " from " . $data["Sender"]["first_name"] . " " . $data["Sender"]["last_name"];
        $notiMsg .= '<div class="alignright"><br /><i>' . $data['Notification']['ago'] . '</i></div>';
        ?>
                var n<?php echo $data['UserNotification']['id'] ?> = noty({
                    text: '<?php echo (string) str_replace("\n\r", "", $notiMsg); ?>',
                    type: 'notification',
                    dismissQueue: true,
                    layout: 'topRight',
                    animation: {
                        open: {height: 'toggle'},
                        close: {height: 'toggle'},
                        easing: 'swing',
                        speed: 1000 // opening & closing animation speed
                    },
                    timeout: false, // delay for closing event. Set false for sticky notifications
                    theme: 'defaultTheme',
                    closeWith: ['button', 'click'],
                    maxVisible: 3,
                    buttons: [
                        {
                            addClass: 'btn btn-primary closebtn_' + <?php echo $data['UserNotification']['id'] ?>, text: 'Ok', onClick: function($noty) {
                                $noty.close();
                                search = 'closebtn_'
                                var classList = $(this).attr("class").split(/\s+/);
                                var currFullId = classList[2];
                                var currID = currFullId.replace(search, '');
                                $.post("<?php echo $this->Html->url(array('controller' => 'notifications', 'action' => 'readNotification', 'admin' => false)); ?>",
                                    {notyId: currID}
                                );
                                /* noty({
                                 dismissQueue: true, 
                                 force: true, 
                                 layout: 'topRight', 
                                 theme: 'defaultTheme', 
                                 text: 'You clicked "Ok" button', 
                                 type: 'success'
                                 }); */
                            }
                        },
                        {
                            addClass: 'btn btn-danger dismissbtn_' + <?php echo $data['UserNotification']['id'] ?>, text: 'Dismiss', onClick: function($noty) {
                                $noty.close();
                                search = 'dismissbtn_'
                                var classList = $(this).attr("class").split(/\s+/);
                                var currFullId = classList[2];
                                var currID = currFullId.replace(search, '');
                                $.post("<?php echo $this->Html->url(array('controller' => 'notifications', 'action' => 'dismissNotification', 'admin' => false)); ?>",
                                    {notyId: currID}
                                );
                                /* noty({
                                 dismissQueue: true, 
                                 force: true, 
                                 layout: 'topRight', 
                                 theme: 'defaultTheme', 
                                 text: 'You clicked "Cancel" button', 
                                 type: 'error'
                                 }); */
                            }
                        }
                    ]
                });
        <?php
        $i++;
    }
}
?>
    });
</script>