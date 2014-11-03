<!DOCTYPE html>
<html lang="en">
    <head>
		<!-- start: Meta -->
		<?php echo $this->Html->charset(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php echo Configure::read('SITENAME.Name'); ?> :: Admin :: <?php echo $title_for_layout; ?></title>
        <meta name="description" content="<?php echo Configure::read('SITENAME.Name'); ?> Super Admin Dashboard" />
        <meta name="author" content="">
        <meta name="keywords" content="<?php echo Configure::read('SITENAME.Name'); ?>, Super Admin, Dashboard" />
		<?php
		echo $this->Html->meta(
			'favicon.ico', '/favicon.ico', array('type' => 'icon')
		);
		?>
		<!-- end: Meta -->
		<!-- start: Mobile Specific -->
        <meta name="viewport" content="width=device-width, initial-scale=1" />
		<!-- end: Mobile Specific -->
		<link rel="stylesheet" type="text/css" media="screen" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/themes/smoothness/jquery-ui.css" />
		<?php
		echo $this->Html->css(array(
			'admin.css',
			'pnotify/jquery.pnotify.default',
			'pnotify/jquery.pnotify.default.icons',
		));
		echo $this->fetch('css');
		?>
		<!-- start: JavaScript -->
		<script type="text/javascript">
			var SITE_URL = '<?php echo $this->Html->url('/', true); ?>admin/';
		</script>
		<?php
		echo $this->Html->script(array(
			'jquery-1.9.1.min',
			// 'jquery-ui.js',
			'bootstrap/bootstrap',
			'bootstrap/bootstrap-modalmanager',
			'bootstrap/bootstrap-modal',
			'underscore-min',
			'jquery-migrate-1.0.0.min',
			'jquery-ui-1.10.0.custom.min',
			'jquery.ui.touch-punch',
			'pnotify/jquery.pnotify',
			'noty/packaged/jquery.noty.packaged.min',
			'base',
			'jquery.validate',
			'jquery.elfinder.min',
			'admin'
		));
		/* echo $this->Html->css(array('superadmin.css',
		  'bootstrap/bootstrap-patch',
		  'checkbox/prettyCheckable',
		  'bootstrap/form_validaion'
		  ));
		  echo $this->fetch('css');
		  echo $this->Html->script(array(
		  'jquery.min.js',
		  'jquery-ui-1.10.0.custom.min.js',
		  'bootstrap/bootstrap.min',
		  'underscore-min.js',
		  'bootstrap/bootstrap-modalmanager',
		  'pnotify/jquery.pnotify',
		  'bootstrap/jquery.custom-scrollbar',
		  'checkbox/prettyCheckable',
		  'base',
		  'bootstrap/jquery.validate'
		  )); */
		echo $this->fetch('script');
		?>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" />
		<?php 
		echo $this->Html->css(array(
			'bootstrap/bootstrap-modal',
			'elfinder.min',
			'elfinder.theme'
		)); ?>
		<!-- <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script> -->
    </head>
    <body>
        <div class="element">
			<h3><a href="<?php echo Configure::read('FULL_BASE_URL.URL'); ?>"><font color="white"><?php echo Configure::read('SITENAME.Name'); ?> Administrator Panel</font></a></h3>
		</div>
        <br>
        <div class="container">
			<?php echo $this->Element('admin/header'); ?>
        </div>
		<center>
			<div class="container">
				<?php echo $this->Element('admin/header_panel'); ?>
			</div>
		</center>
		<div class="container">
			<div class="span3 well">
				<?php echo $this->Element('admin/menu'); ?>
				<div id="myTabContent" class="tab-content">
					<?php echo $this->fetch('content'); ?>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			$(document).ready(function() {
				/* ---------- File Manager ---------- */
				var elf = $('.file-manager').elfinder({
					url: SITE_URL + 'files/index', // connector URL (REQUIRED)
					lang: 'en',
					getFileCallback: function(file) {

					},
				}).elfinder('instance');
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
	</body>
</html>