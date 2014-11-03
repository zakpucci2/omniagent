<div id='pagination'>
	<?php
		if ($this->Paginator->hasPage(2)) {
			echo $paginator->first("First Page", array());
			echo '&nbsp;&nbsp;';
			echo $this->Paginator->prev('<< Previous', array());
			echo '&nbsp;&nbsp;';
			echo $paginator->numbers(array('separator' => ' '), array('url' => array('action' => $paging_action)));
			echo '&nbsp;&nbsp;';
			echo $paginator->next("Next >>", array());
			echo '&nbsp;&nbsp;';
			echo $paginator->last("Last Page", array());
		}
	?>
</div>