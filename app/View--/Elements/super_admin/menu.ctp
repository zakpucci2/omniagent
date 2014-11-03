<?php
$controller = $this->params['controller'];
$action = $this->params['action'];
switch ($controller) {
	
}

// Switch case to show current menu item selected in the admin section
switch ($action) {
	case 'superadmin_dashboard':
		$home = 'class="active"';
		$templates = $tickets = $messages = $users = $admins = $notifications = $newsletters = $tasks = "";
		break;
	case 'superadmin_listadmins':
	case 'superadmin_trashadmins':
		$admins = 'class="active"';
		$templates = $tickets = $messages = $users = $home = $notifications = $newsletters = $tasks = "";
		break;
	case 'superadmin_listclients':
	case 'superadmin_trashclients':
		$users = 'class="active"';
		$templates = $tickets = $messages = $admins = $home = $notifications = $newsletters = $tasks = "";
		break;
	case 'superadmin_list_template':
	case 'superadmin_edit_template':
		$templates = 'class="active"';
		$users = $tickets = $messages = $admins = $home = $notifications = $newsletters = $tasks = "";
		break;
	case 'superadmin_listnewsletters':
	case 'superadmin_add_newsletter':
	case 'superadmin_edit_newsletter':
		$newsletters = 'class="active"';
		$users = $tickets = $messages = $admins = $home = $notifications = $templates = $tasks = "";
		break;
	case 'superadmin_listnotifications':
		$notifications = 'class="active"';
		$users = $tickets = $messages = $admins = $home = $templates = $newsletters = $tasks = "";
		break;
	case 'superadmin_listmessages':
	case 'superadmin_sentmessages':
		$messages = 'class="active"';
		$users = $tickets = $notifications = $admins = $home = $templates = $newsletters = $tasks = "";
		break;
	case 'superadmin_listtickets':
	case 'superadmin_closedtickets':
		$tickets = 'class="active"';
		$templates = $users = $messages = $admins = $home = $notifications = $newsletters = $tasks = "";
		break;
	case 'superadmin_listtasks':
		$tasks = 'class="active"';
		$templates = $users = $messages = $admins = $home = $notifications = $newsletters = $tickets = "";
		break;
	default:
		$home = 'class="active"';
		$templates = $tickets = $messages = $admins = $users = $notifications = $newsletters = $tasks = "";
}
?>

<ul class="nav nav-tabs">
    <li <?php echo $home ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-home"></i> Home <span class="label label-primary invitesbadge">' . ((isset($unreadInvites) && !empty($unreadInvites)) ? count($unreadInvites) : 0 ) . '</span>', array('controller' => 'users', 'action' => 'dashboard', 'superadmin' => true), array('title' => 'List Invitations', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
    <li <?php echo $admins ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-globe"></i> Administrators', array('controller' => 'users', 'action' => 'listadmins', 'superadmin' => true), array('title' => 'List Admins', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $users ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-user"></i> Clients(Users) <span class="label label-primary invitesClientBadge">' . ((isset($unreadCientInvites) && !empty($unreadCientInvites)) ? count($unreadCientInvites) : 0 ) . '</span>', array('controller' => 'users', 'action' => 'listclients', 'superadmin' => true), array('title' => 'List Clients (Users)', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $tasks ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-time"></i> Tasks', array('controller' => 'tasks', 'action' => 'listtasks', 'superadmin' => true), array('title' => 'List Tasks', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $templates ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-list-alt"></i> Email Templates', array('controller' => 'templates', 'action' => 'list_template', 'superadmin' => true), array('title' => 'List Template', 'escape' => false, 'class' => '', 'id' => '')); ?></li>
	<li <?php echo $newsletters ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-list-alt"></i> Newsletters', array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true), array('title' => 'List Newsletters', 'escape' => false, 'class' => '', 'id' => '')); ?></li>
    <li <?php echo $notifications ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-file"></i> Notifications <span class="label label-primary notificationbadge">' . ((isset($unreadNotifications) && !empty($unreadNotifications)) ? count($unreadNotifications) : 0 ) . '</span>', array('controller' => 'notifications', 'action' => 'listnotifications', 'superadmin' => true), array('title' => 'List Notifications', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<!--<li><a href="#history" data-toggle="tab"><i class="glyphicon glyphicon-time"></i> History</a></li> -->
	<li <?php echo $tickets ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-file"></i> Tickets <span class="label label-primary ticketbadge">' . ((isset($unreadTickets) && !empty($unreadTickets)) ? count($unreadTickets) : 0 ) . '</span>', array('controller' => 'support_tickets', 'action' => 'listtickets', 'superadmin' => true), array('title' => 'List Support Tickets', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
	<li <?php echo $messages ?>><?php echo $this->Html->link('<i class="glyphicon glyphicon-envelope"></i> Messages <span class="label label-primary messagebadge">' . ((isset($unreadMessages) && !empty($unreadMessages)) ? count($unreadMessages) : 0 ) . '</span>', array('controller' => 'messages', 'action' => 'listmessages', 'superadmin' => true), array('title' => 'List Messages', 'escape' => false, 'class' => 'anchor', 'id' => '')); ?></li>
    <li><?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i> Update Profile Details', array('controller' => 'users', 'action' => 'changepassword', 'superadmin' => true), array('escape' => false, 'class' => 'anchor', 'id' => 'ChangePassword', 'title' => 'Update Profile Details')); ?></li>
    <li><?php echo $this->Html->link('<i class="glyphicon glyphicon-time"></i> Logout', array('controller' => 'users', 'action' => 'logout', 'superadmin' => false), array('escape' => false)); ?></li>		
</ul>
<?php echo $this->Element('super_admin/changepassword'); ?>
<script type="text/javascript">
	var messTimestamp = null;
	var notyTimestamp = null;
	var ticketTimestamp = null;
	var inviteTimestamp = null;
	var clientInviteTimestamp = null;
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
				$(".ticketbadge").html(newTicketData);
			}
			updateTicketsBadge(newTicketData.timestamp);
		});
	}
	
	function updateInvitesBadge() {
		var inviteData = {};
		if (typeof inviteTimestamp !== 'undefined') {
			inviteData.timestamp = inviteTimestamp;
		}
		$.ajax({
			url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'latestInvites', 'admin' => false)); ?>',
			type: "post",
			dataType: "json",
			async: true,
			data: inviteData
		}).done(function(resInviteData) {
			var newInviteData = '';
			for (i in resInviteData.invites) {
				newInviteData += resInviteData.invites[i] + '\n';
			}
			if (newInviteData != '') {
				$(".invitesbadge").html(newInviteData);
			}
			updateInvitesBadge(newInviteData.timestamp);
		});
	}
	
	function updateClientInvitesBadge() {
		var clientInviteData = {};
		if (typeof clientInviteTimestamp !== 'undefined') {
			clientInviteData.timestamp = clientInviteTimestamp;
		}
		$.ajax({
			url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'latestClientInvites', 'admin' => false)); ?>',
			type: "post",
			dataType: "json",
			async: true,
			data: clientInviteData
		}).done(function(resClientInviteData) {
			var newClientInviteData = '';
			for (i in resClientInviteData.clients) {
				newClientInviteData += resClientInviteData.clients[i] + '\n';
			}
			if (newClientInviteData != '') {
				$(".invitesClientBadge").html(newClientInviteData);
			}
			updateClientInvitesBadge(newClientInviteData.timestamp);
		});
	}
	
	$(document).ready(function() {
		updateInvitesBadge();
		updateClientInvitesBadge();
		updateMessagesBadge();
		updateNotificationsBadge();
		updateTicketsBadge();
	});
</script>