<!DOCTYPE html>
<html  lang="en" class="no-js">
    <head>
		<?php echo $this->Html->charset(); ?>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo Configure::read('SITENAME.Name'); ?> <?php echo $title_for_layout; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--<embed src="http://omnihustle.net<?php echo $this->webroot; ?>app/webroot/files/zak.wav" autostart="true" loop="true">-->
        <meta name="description" content="">
        <meta name="author" content="">
		<?php
		echo $this->Html->meta(
			'favicon.ico', '/favicon.ico', array('type' => 'icon')
		);
		?>
		<?php echo $this->fetch('meta'); ?>
		<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' />
		<?php
		echo $this->Html->css(array(
			'assets2/reset.css',
			'assets2/supersized.css',
			'assets2/style.css',
			'bootstrap-theme.min.css'
		));
		echo $this->fetch('css');
		echo $this->Html->script(array(
			'assets2/jquery-1.8.2.min.js',
			'assets2/supersized.3.2.7.min.js',
			'assets2/supersized-init.js',
			'assets2/scripts.js',
		));
		echo $this->fetch('script');
		?>
		<style type="text/css">
			.incorrect {
				background-color: #000000;
				border-radius:10px;
				padding:5px;
				opacity:0.7;
				/* width:330px; */
			}
		</style>
    </head>
    <body link="#ffffff" vlink="#ffffff" alink="#ffffff"> 
		<?php echo $this->fetch('content'); ?>   
    </body>
</html>