<?php $this->Html->addCrumb('File Manager', array('controller' => 'files_manager', 'action' => 'index')); ?>
<div class="row-fluid">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon icon-folder-open"></i><span class="break"></span><?php echo __('File Manager'); ?></h2>
			<div class="box-icon">
				<a href="#" id="toggle-fullscreen" class="hidden-phone hidden-tablet"><i class="halflings-icon fullscreen"></i></a>
				<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<div class="file-manager"></div>
		</div>
	</div>		
</div>