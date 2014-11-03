<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<?php $this->Html->addCrumb('Tasks', array('controller' => 'tasks', 'action' => 'listtasks')); ?>
<style type="text/css">
	.row{
		margin-top:40px;
		padding: 0 10px;
	}
	.clickable{
		cursor: pointer;   
	}
	.panel-heading div {
		margin-top: -18px;
		font-size: 15px;
	}
	.panel-heading div span{
		margin-left:5px;
	}
	.panel-body{
		display: none;
	}
	.custab{
		border: 1px solid #ccc;
		padding: 5px;
		margin: 5% 0;
		box-shadow: 3px 3px 2px #ccc;
		transition: 0.5s;
    }
	.custab:hover{
		box-shadow: 3px 3px 0px transparent;
		transition: 0.5s;
    }
	.media
    {
        /*box-shadow:0px 0px 4px -2px #000;*/
        margin: 20px 0;
        padding:30px;
    }
    .dp
    {
        border:10px solid #eee;
        transition: all 0.2s ease-in-out;
    }
    .dp:hover
    {
        border:2px solid #eee;
        transform:rotate(360deg);
        -ms-transform:rotate(360deg);  
        -webkit-transform:rotate(360deg);  
        /*-webkit-font-smoothing:antialiased;*/
    }
	.pager .next>a {
		float:none !important;
	}
	table { table-layout: fixed; }
	table th, table td { word-break:break-all;}
</style>
<div class="row-fluid">
	<h1>Tasks</h1>
	<div class="col-md-6">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title"></h3>
				<div class="pull-right">
					<span class="clickable filter" data-toggle="tooltip" title="Toggle table filter" data-container="body">
						<i class="glyphicon glyphicon-filter"></i>
					</span>
				</div>
			</div>
			<div class="panel-body">
				<input type="text" class="form-control" id="task-table-filter" data-action="filter" data-filters="#task-table" placeholder="Filter Tasks">
			</div>
			<table class="table table-hover" id="task-table">
				<colgroup>
					<col width="5%" />
					<col width="35%" />
					<col width="35%" />
					<col width="23%" />
					<col width="15%" />
				</colgroup>
				<thead>
					<tr>
						<th scope="col" class="text-center">#</th>
						<th scope="col"><?php echo $this->Paginator->sort('UserTask.task_title', 'Task'); ?></th>
						<th scope="col"><?php echo $this->Paginator->sort('UserTask.task_id', 'Platform'); ?></th>
						<th scope="col"><?php echo $this->Paginator->sort('UserTask.status_completed', 'Satus'); ?></th>
						<th scope="col"><?php echo 'Action'?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (isset($data) && !empty($data)) {
						$counter = 0;
						foreach ($data as $task_data) {
							?>
							<tr>
								<td scope="col"><?php echo ($counter + 1); ?></td>
								<td scope="col"><?php echo $task_data['UserTask']['task_title']; ?></td>
								<td scope="col"><?php echo $task_data['Task']['name']; ?></td>
								<td>
									<div class="progress progress-info progress-striped" style="margin-bottom: 9px;">
										<div class="bar" style="width: <?php echo $task_data['UserTask']['status_completed']; ?>%">
											<?php echo $task_data['UserTask']['status_completed']; ?>%
										</div>
									</div>
								</td>
								<td>
                                    <?php echo $this->Html->link('<i class="halflings-icon white search"></i> View', array('controller' => 'tasks', 'action' => 'view_task', base64_encode($task_data['UserTask']['task_id'])), array('escape' => false, 'class' => 'btn btn-info btn-md viewTaskRow', 'title' => 'View Task Detail')); ?>
                                </td>
							</tr>
							<?php
							$counter++;
						}
					} else {
						echo '<tr><td colspan="4"><div class="norecord">No Record Found</div></td></tr>';
					}
					?>		
				</tbody>
			</table>
		</div>
	</div>
    <center>
		<?php if ($this->Paginator->counter('{:pages}') > 1) { ?>
			<ul class="pager">
				<?php
				echo $this->Paginator->prev(__('<< First Page'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
				echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
				echo $this->Paginator->next(__('Last Page >>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
				?>
			</ul>
		<?php } ?>
	</center>
</div>
