<?php
	echo $this->Html->css(array('buttons'));
    $this->Html->addCrumb('Dashboard', array('controller' => 'users', 'action' => 'dashboard'));
?>
<div class="row-fluid">
	<div class="box black span4" onTablet="span12" onDesktop="span4" style="border-radius:10px;">
		<div class="box-header" style="border-radius:10px;">
			<h2><i class="halflings-icon white user"></i><span class="break"></span>Subscribers</h2>
			<div class="box-icon">
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content" style="border-radius:10px;">
			<ul class="dashboard-list metro">
				<li style="border-radius:10px;">
					<a href="#">
						<i class="icon-user green"></i>
						<strong>dd/mm/yyyy - </strong>
						Email@email.com 
					</a>
				</li>
			</ul>
		</div>
	</div><!--/span-->
	<div class="box black span4 noMargin" onTablet="span12" onDesktop="span4" style="border-radius:10px;">
		<div class="box-header" style="border-radius:10px;">
			<h2><i class="halflings-icon white list"></i><span class="break"></span>Weekly Stat</h2>
			<div class="box-icon">
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content" style="border-radius:10px;">
			<ul class="dashboard-list metro" style="border-radius:10px;">

				<!--Single Stat-->
				<li style="border-radius:10px;">
					<a href="#">
						<i class="icon-arrow-up green"></i>                               
						<strong><!--# OF STATS--></strong>
						STAT TYPE                                    
					</a>
				</li>
				<!--Single Stat-->

			</ul>
		</div>
	</div> <!--/span-->
	<div class="box black span4 noMargin" onTablet="span12" onDesktop="span4" style="border-radius:10px;" id="toDoDiv">
		<div class="box-header" style="border-radius:10px;">
			<h2><i class="halflings-icon white check"></i><span class="break"></span>To Do List</h2>
			<div class="box-icon">

				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content" style="border-radius:10px;">
			<div class="todo metro">
				<ul class="todo-list" style="border-radius:10px;">

					<!--Single Task-->
					<li class="red" style="border-radius:10px;">
						<a class="action icon-check-empty" href="#"></a>	
						TASK 
						<strong>DATE ADDED</strong>
					</li>
					<!--Single Task-->

				</ul>
			</div>	
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="sparkLineStats span4 widget green" onTablet="span12" onDesktop="span4" style="border-radius:10px; height:317px;">
		<ul class="unstyled"><!--Google Analytics Variables START-->
			<li><span class="sparkLineStats3"></span> 
				Pageviews: 
				<span class="number">INPUT</span>
			</li>
			<li><span class="sparkLineStats4"></span>
				Pages / Visit: 
				<span class="number">INPUT</span>
			</li>
			<li><span class="sparkLineStats5"></span>
				Avg. Visit Duration: 
				<span class="number">INPUT</span>
			</li>
			<li><span class="sparkLineStats6"></span>
				Bounce Rate: <span class="number">INPUT</span>
			</li>
			<li><span class="sparkLineStats7"></span>
				% New Visits: 
				<span class="number">INPUT</span>
			</li>
			<li><span class="sparkLineStats8"></span>
				% Returning Visitor: 
				<span class="number">INPUT</span>
			</li>
		</ul><!--Google Analytics Variables END-->
		<div class="clearfix"></div>
	</div><!-- End .sparkStats -->
	<div class="widget blue span5 noMargin" onTablet="span12" onDesktop="span5" style="border-radius:10px;">
		<h2><span class="glyphicons globe"><i></i></span> Demographics</h2>
		<hr>
		<div class="content">
			<div class="verticalChart">
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>US %</span>
						</div>
					</div>
					<div class="title">US</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>PL %</span>
						</div>
					</div>
					<div class="title">PL</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>GB %</span>
						</div>
					</div>
					<div class="title">GB</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>DE %</span>
						</div>
					</div>
					<div class="title">DE</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>NL %</span>
						</div>
					</div>
					<div class="title">NL</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>CA %</span>
						</div>
					</div>
					<div class="title">CA</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>FI %</span>
						</div>
					</div>
					<div class="title">FI</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>RU %</span>
						</div>
					</div>
					<div class="title">RU</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>AU %</span>
						</div>
					</div>
					<div class="title">AU</div>
				</div>
				<div class="singleBar">
					<div class="bar">
						<div class="value">
							<span>N/A %</span>
						</div>
					</div>
					<div class="title">N/A</div>
				</div>	
				<div class="clearfix"></div>
			</div>
		</div>
	</div><!--/span-->
	<br>
	<div class="span3 statbox green noMargin" onTablet="span12" onDesktop="span3" style="border-radius:10px;">
		<div class="boxchart">1,2,6,4,0,8,2,4,5,3,1,7,5</div>
		<div class="number">123<i class="icon-arrow-up"></i></div>
		<div class="title">subscribers</div>
		<div class="footer">
			<a href="#"> read full report</a>
		</div>
	</div>
	<div class="span3 statbox purple noMargin" onTablet="span12" onDesktop="span3" style="border-radius:10px;">
		<div class="boxchart">5,6,7,2,0,4,2,4,8,2,3,3,2</div>
		<div class="number">854<i class="icon-arrow-up"></i></div>
		<div class="title">visits</div>
		<div class="footer">
			<a href="#"> read full report</a>
		</div>	
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	// Instance the tour
	var tour = new Tour({
		container: "body",
		useLocalStorage: false,
		backdrop: true,
		animation:true,
		onEnd:function(tour) {
			$.post( "<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'updateTourStatus', 'admin' => false)); ?>", 
				{userId: '<?php echo base64_encode($usersession['User']['id']); ?>'}
			);
		}
	}) ;
	tour.addSteps(
		[
			{
				element: "#welcomeDivCenter",
				title: "Welcome to OmniHustle, <?php echo ((isset($usersession['User']['business_name']) && !empty($usersession['User']['business_name'])) ? $usersession['User']['business_name'] : ((isset($usersession['User']['first_name']) && !empty($usersession['User']['first_name'])) ? $usersession['User']['first_name'] : $usersession['User']['user_name'])); ?>!",
				content: "Welcome to OmniHustle. Click next to view demo tour",
				placement: "left",
				orphan:true
			},
			{
				element: "#welcomeDiv", 
				title: "Account Details",
				content: "If you're unsure what to do next, updating your account details is a good place to start!",
				placement: "left",
				backdrop:false,
				onShow: function(tour) {
					$( "#userNameAction" ).trigger( "click" );
					$( "#userNameAction" ).click();
				}
			},
			{
				element: "#avatarDiv",
				title: "Profile Photo",
				content: "Let's put a face to your Omnihustle profile. Click here to choose an avatar for your account.",
				placement: "right",
				backdrop:false
			},
			{
				element: "#dashboardDiv",
				title: "OmniHustle Dashboard",
				content: "This is where all of your most important campaign metrics can be viewed and managed.",
				placement: "right",
				onShow: function(tour) {
					
				}
				
			},
			{
				element: "#messagesCounterDiv",
				title: "OmniHustle Mail",
				content: "During signup, we took the liberty of creating a brand new email just for you! You can now send and receive email from your own @omnihustle.com email address, as well as communicate with anyone in your Omnihustle network. Try it out!",
				placement: "right"
			},
			{
				element: "#notificationCounterDiv",
				title: "Notifications Inbox",
				content: "You will receive many notifications over the course of your stay with us, this is where you will be able to view all of your past notifications.",
				placement: "right"
			},
			{
				element: "#supportTicketDiv",
				title: "OmniHustle Support",
				content: "Have any questions, issues, or concerns? Or just want to throw out a new idea for the system? Here is a great place to do just that! File a support ticket at any time and our team at OmniHustle will get right on it.",
				placement: "right"
			},

			{
				element: "#tasksCounterDiv",
				title: "OmniHustle Tasks",
				content: "The beating heart of our OmniHustle system, and one of the most convenient features for you as a user. Anything you want, when you want it. Simply choose a task from our ever-growing list of offered tasks, and your Omnihustle Agent will get to work on it straight away. No strings, no fuss, just Tasks. Give it a shot!",
				placement: "right"		
			},
	
			{
				element: "#galleryDiv",
				title: "Image Gallery",
				content: "Your own personal storage bin for all images related to your campaign. Have a few photos that you want your agent to utilize? Upload them here!",
				placement: "right"
			},
			{
				element: "#calendarDiv",
				title: "OmniHustle Post Schedule",
				content: "A personal favorite of our OmniHustle Agents, and the pioneer aspect of our system. Connect your chosen social networks to your account, and schedule posts across all networks to post on your chosen date and time! Get posting!",
				placement: "right"
			},
			{
				element: "#fileManagerDiv",
				title: "Manage your files!",
				content: "Another convenient spot for you as a user to upload all of your pertinent files and documents for your Omnihustle Agent to utilize.",
				placement: "right"
			},
			{
				element: "#toDoDiv",
				title: "Work with your Agent!",
				content: "Each time you purchase or schedule a task with your Agent, we may need a little more information before we can begin. This is where we will let you know. Keep an eye out for new tasks, complete your tasks, and simply click on your task to get rid of it. Easy!",
				placement:"left"
			}
		]
	);

<?php 
if($usersession['User']['is_tour_completed'] == 0) {
?>
	// Initialize the tour
	tour.init();

	// Start the tour
	tour.restart();
<?php } ?>
});
</script>