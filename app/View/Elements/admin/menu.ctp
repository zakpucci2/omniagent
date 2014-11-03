<?php
$controller = $this->params['controller'];
$action = $this->params['action'];
switch ($controller) {
	
}

// Switch case to show current menu item selected in the admin section
switch ($action) {
	case 'admin_dashboard':
		$home = 'class="active"';
		$templates = $tickets = $messages = $clients = $tasks = $team = $notifications = $tasks = $files_manager = "";
		break;
	case 'admin_listnotes':
		$home = 'class="active"';
		$templates = $tickets = $messages = $clients = $tasks = $team = $notifications = $tasks = $files_manager = "";
		break;
	case 'admin_listclients':
		$clients = 'class="active"';
		$templates = $tickets = $messages = $home = $tasks = $team = $notifications = $tasks = $files_manager = "";
		break;
	case 'admin_listtasks':
		$tasks = 'class="active"';
		$templates = $tickets = $messages = $notes = $clients = $home = $team = $notifications = $files_manager = "";
		break;
	case 'admin_myteam':
	case 'admin_listregularadmins':
		$team = 'class="active"';
		$templates = $tickets = $messages = $clients = $tasks = $home = $notifications = $tasks = $files_manager = "";
		break;
	case 'admin_listnotifications':
		$notifications = 'class="active"';
		$templates = $tickets = $messages = $clients = $tasks = $team = $home = $notes = $tasks = $files_manager = "";
		break;
	case 'admin_listtickets':
	case 'admin_closedtickets':
		$tickets = 'class="active"';
		$templates = $notifications = $messages = $clients = $tasks = $team = $home = $notifications = $tasks = $files_manager = "";
		break;
	case 'admin_listmessages':
	case 'admin_sentmessages':
		$messages = 'class="active"';
		$templates = $tickets = $team = $clients = $tasks = $home = $notifications = $tasks = $files_manager = "";
		break;
	case 'admin_listtasks':
		$tasks = 'class="active"';
		$templates = $notifications = $messages = $clients = $tickets = $team = $home = $notifications = $files_manager = "";
		break;
	case 'admin_index':
		$files_manager = 'class="active"';
		$templates = $notifications = $messages = $clients = $tickets = $team = $home = $notifications = $tasks = "";
		break;
	default:
		$home = 'class="active"';
		$templates = $tickets = $messages = $clients = $tasks = $team = $notifications = $tasks = $files_manager = "";
}
?>
<ul class="nav nav-tabs">
	<li <?php echo $home ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-home"></i> Home', array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true), array('title' => 'List Invitations', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<?php
	if ($this->Session->check('User')) {
		$usersession = $this->Session->read('User');
	}
	if($usersession['User']['is_trusted_admin'] == 1) { ?>
		<li <?php echo $team ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-user"></i> My Team', array('controller' => 'admins', 'action' => 'myteam', 'admin' => true), array('title' => 'My Team', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<?php }
	?>
	<li <?php echo $clients ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-user"></i> My Clients', array('controller' => 'admins', 'action' => 'listclients', 'admin' => true), array('title' => 'List Users', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $tasks ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-time"></i> Tasks', array('controller' => 'tasks', 'action' => 'listtasks', 'admin' => true), array('title' => 'List Tasks', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $tickets ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-file"></i> Tickets <span class="label label-primary ticketbadge">' . ((isset($unreadTickets) && !empty($unreadTickets)) ? count($unreadTickets) : 0 ) . '</span>', array('controller' => 'support_tickets', 'action' => 'listtickets', 'admin' => true), array('title' => 'List Support Tickets', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
    <li <?php echo $notifications ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-file"></i> Notifications <span class="label label-primary notificationbadge">' . ((isset($unreadNotifications) && !empty($unreadNotifications)) ? count($unreadNotifications) : 0 ) . '</span>', array('controller' => 'notifications', 'action' => 'listnotifications', 'admin' => true), array('title' => 'List Notifications', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $messages ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-envelope"></i> Messages <span class="label label-primary messagebadge">' . ((isset($unreadMessages) && !empty($unreadMessages)) ? count($unreadMessages) : 0 ) . '</span>', array('controller' => 'messages', 'action' => 'listmessages', 'admin' => true), array('title' => 'List Messages', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $files_manager ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-folder-open"></i> Files Manager', array('controller' => 'files_manager', 'action' => 'index', 'admin' => true), array('title' => 'Files Manager', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li><?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i> Update Profile Details', array('controller' => 'users', 'action' => 'changepassword', 'admin' => true), array('escape' => false, 'class' => 'anchor', 'id' => 'ChangePassword', 'title' => 'Update Profile Details')); ?></li>
	<li><?php echo $this->Html->link('<i class="glyphicon glyphicon-time"></i> Logout', array('controller' => 'users', 'action' => 'logout', 'superadmin' => false), array('escape' => false)); ?></li>		
</ul>
<?php echo $this->Element('admin/changepassword'); ?>
<script type="text/javascript">
	var messTimestamp = null;
	var notyTimestamp = null;
	var ticketTimestamp = null;
	function updateMessagesBadge() {
		var msgData = {};
		if (typeof messTimestamp !== 'undefined') {
			msgData.timestamp = messTimestamp;
		}
		$.ajax({
			url: '<?php echo $this->Html->url(array('controller' => 'messages', 'action' => 'latestMessages', 'admin' => false)); ?>',
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
			}
			updateMessagesBadge(resMsgData.timestamp);
		});
	}
	
	function updateNotificationsBadge() {
		var notyData = {};
		if (typeof notyTimestamp !== 'undefined') {
			notyData.timestamp = notyTimestamp;
		}
		$.ajax({
			url: '<?php echo $this->Html->url(array('controller' => 'notifications', 'action' => 'latestNotifications', 'admin' => false)); ?>',
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
			}
			updateNotificationsBadge(resNotyData.timestamp);
		});
	}
	
	function updateTicketsBadge() {
		var ticketData = {};
		if (typeof ticketTimestamp !== 'undefined') {
			ticketData.timestamp = ticketTimestamp;
		}
		$.ajax({
			url: '<?php echo $this->Html->url(array('controller' => 'support_tickets', 'action' => 'latestSupportTickets', 'admin' => false)); ?>',
			type: "post",
			dataType: "json",
			async: true,
			data: ticketData
		}).done(function(resTicketData) {
			var newTicketData = '';
			for (i in resTicketData.tickets) {
				newTicketData += resTicketData.tickets[i] + '\n';
			}
			if (newTicketData != '') {
				// console.log(newTicketData);
				$(".ticketbadge").html(newTicketData);
			}
			updateTicketsBadge(newTicketData.timestamp);
		});
	}
	$(document).ready(function() {
		updateMessagesBadge();
		updateNotificationsBadge();
		updateTicketsBadge();
	});
</script>