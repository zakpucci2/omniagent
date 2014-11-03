<!-- Breadcrumb Start -->
<?php
echo $this->Html->getCrumbList(
	array(
		'class' => 'breadcrumb',
		'separator' => '&nbsp;<i class="icon-angle-right"></i>&nbsp;',
		'firstClass' => 'icon-home',
		'lastClass' => 'last-element'
	), array(
		'text' => " Home",
		'url' => array('controller' => 'users', 'action' => 'index'),
		'escape' => true
	)
);
?>
<!-- Breadcrumb Ends -->