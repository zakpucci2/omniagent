<!DOCTYPE html>
<html lang="en">
    <head>
		<!-- start: Meta -->
		<?php echo $this->Html->charset(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo Configure::read('SITENAME.Name'); ?> :: <?php echo $title_for_layout; ?></title>
        <meta name="description" content="<?php echo Configure::read('SITENAME.Name'); ?> User Dashboard" />
        <meta name="author" content="">
        <meta name="keywords" content="<?php echo Configure::read('SITENAME.Name'); ?>, User, Dashboard" />
		<?php
		echo $this->Html->meta(
			'favicon.ico', '/favicon.ico', array('type' => 'icon')
		);
		?>
		<!-- end: Meta -->
		<!-- start: Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<!-- end: Mobile Specific -->
		<!-- start: CSS -->
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,latin-ext' rel='stylesheet' type='text/css'>
		<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
			<link id="ie-style" href="css/ie.css" rel="stylesheet">
		<![endif]-->

		<!--[if IE 9]>
			<link id="ie9style" href="css/ie9.css" rel="stylesheet">
		<![endif]-->
		<!-- end: CSS -->
		<?php
		echo $this->Html->css(array(
			'bootstrap.min',
			'bootstrap-responsive.min',
			'bootstrap/bootstrap-modal',
			'bootstrap-tour-standalone.min',
			'style',
			'style-forms',
			'style-responsive',
			'pnotify/jquery.pnotify.default',
			'pnotify/jquery.pnotify.default.icons',
		));
		echo $this->fetch('css');
		?>
		<!-- start: JavaScript -->
		<script type="text/javascript">
			var SITE_URL = '<?php echo $this->Html->url('/', true); ?>';
		</script>
		<?php
		echo $this->Html->script(array(
			'jquery-1.9.1.min',
			'underscore-min.js',
			'modernizr',
			'bootstrap/bootstrap.min',
			'bootstrap/bootstrap-modalmanager',
			'bootstrap/bootstrap-modal',
			'bootstrap-tour-standalone.min',
			'jquery-migrate-1.0.0.min',
			'jquery-ui-1.10.0.custom.min',
			'jquery.ui.touch-punch',
			'base',
			'jquery.validate',
			'jquery.cookie',
			'fullcalendar.min',
			'jquery.dataTables.min',
			'excanvas',
			'jquery.flot',
			'jquery.flot.pie',
			'jquery.flot.stack',
			'jquery.flot.resize.min',
			'jquery.chosen.min',
			'jquery.uniform.min',
			'jquery.cleditor.min',
			'jquery.noty',
			'jquery.elfinder.min',
			'jquery.raty.min',
			'jquery.iphone.toggle',
			'jquery.uploadify-3.1.min',
			'jquery.gritter.min',
			'jquery.imagesloaded',
			'jquery.masonry.min',
			'jquery.knob.modified',
			'jquery.sparkline.min',
			'counter',
			'retina',
			'custom',
			'pnotify/jquery.pnotify',
			'noty/packaged/jquery.noty.packaged.min',
		));
		echo $this->fetch('script');
		?>
		<!-- end: JavaScript -->
		<!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css"> -->
	</head>
	<body>
		<!-- start: Header -->
		<?php echo $this->Element('header'); ?>
		<!-- start: Header -->
		<div class="container-fluid-full">
			<div class="row-fluid">
				<!-- start: Main Menu -->
				<?php echo $this->Element('sidebar'); ?>
				<!-- end: Main Menu -->
				<noscript>
					<div class="alert alert-block span10">
						<h4 class="alert-heading">Warning!</h4>
						<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
					</div>
				</noscript>
				<!-- start: Content -->
				<div id="content" class="span10">
					<?php echo $this->Element('breadcrumb'); ?>
					<?php echo $this->fetch('content'); ?>
				</div>	
			</div>
			<?php echo $this->Element('footer'); ?>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				<?php
				if ($this->Session->check('Message.flash')) {
					if (!$this->Session->check('Message.flash.params')) {
						$message = 'info';
					} else {
						$message = $this->Session->read('Message.flash.params');
					}
					switch ($message) {
						case 'success':
							echo "flash('success','{$this->Session->flash()}','Success');";
							break;
						case 'error':
							echo "flash('error','{$this->Session->flash()}','Error');";
							break;
						case 'info':
							echo "flash('info','{$this->Session->flash()}','Information');";
							break;
						case 'notice':
							echo "flash('notice','{$this->Session->flash()}','Notice');";
							break;
						default:
							echo "flash('info','{$this->Session->flash()}','Information');";
							break;
					}
				}
				?>
			});
        </script>
		<script type="text/javascript">
			$(document).ready(function() {
				$("#CoverPhoto").click(function(event) {
					event.preventDefault();
					$('#coverModal').modal('show');
				});
			});
		</script>
	</body>
</html>