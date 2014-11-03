<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
		<?php echo $this->Html->charset(); ?>
        <title><?php echo Configure::read('SITENAME.Name'); ?> :: <?php echo $title_for_layout; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="" />
		<?php echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon')); ?>
		<?php echo $this->fetch('meta'); ?>
		<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Quicksand:300,400' rel='stylesheet' type='text/css'>
		<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
		<?php
		echo $this->Html->css(array('outer_user_style'));
		echo $this->fetch('css');
		?>
		<!-- Latest compiled and minified JavaScript -->
		<?php echo $this->Html->script(array('jquery-1.9.1.min')); ?>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
		<?php echo $this->fetch('script'); ?>
    </head>
    <body background="<?php echo $this->webroot; ?>img/10-New-Collection-Green-Background-.jpg">
		<div id="logo" style="font-family: 'Quicksand', sans-serif;">
			<a href="<?php echo Configure::read('FULL_BASE_URL.URL'); ?>"><?php echo Configure::read('SITENAME.Name'); ?> <span class="glyphicon glyphicon-stats"></span></a>
		</div>
		<section class="section-login">
			<p></p>
			<?php echo $this->fetch('content'); ?>
		</section>
    </body>
</html>