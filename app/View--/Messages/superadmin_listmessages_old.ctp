<?php $this->Html->css('bootstrap/bootstrap-editable', null, array('inline' => false)); ?>
<?php $this->Html->script('bootstrap/bootstrap-editable', array('inline' => false)); ?>
<style>
    .block-icon-default { color: #E34C3B !important; }
</style>
<div>
	<br />
    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-envelope"></i> Messages</strong></div>
            <div class="pull-right">
				<a href="#newmessageModal" data-toggle="modal" class="btn btn-lmd btn-success"><span class="glyphicon glyphicon-plus"></span> New Message</a>
				<a href="#" class="btn btn-md btn-danger"><span class="glyphicon glyphicon-trash"></span> Trash</a>
				<?php // echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> New Message', array('controller' => 'messages', 'action' => 'addmessage', 'admin' => true), array('escape' => false, 'class' => 'btn btn-lmd btn-success', 'title' => 'New Message', 'id' => 'addMessage', 'data-toggle' => "modal")); ?>
            </div>
        </div>
    </div>
    <br /><br />
	<?php echo $this->Form->create('messages', array('action' => 'listmessages', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
		<?php
		if (isset($_GET['search']) && trim($_GET['search']) != '') {
			$val = $_GET['search'];
		} else {
			$val = '';
		}
		echo $this->Form->input('UserMessage.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Message", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
		?>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">
                Search!</button>
        </span>
    </div>
	<?php echo $this->Form->end(); ?>
    <br />
    <div class="span7">   
        <div class="widget stacked widget-table action-table">
            <div class="widget-content">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('Sender.first_name', 'First Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Sender.last_name', 'Last Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('user_name', 'User Name'); ?></th>
                            <th><?php echo $this->Paginator->sort('Message.subject', 'Subject'); ?></th>
							<th><?php echo $this->Paginator->sort('Message.created', 'Message On'); ?></th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
						<?php
						if (isset($data) && !empty($data)) {
							foreach ($data as $message_data) {
								?>
								<tr>
									<td><?php echo $message_data['Sender']['first_name']; ?></td>
									<td><?php echo $message_data['Sender']['last_name']; ?></td>
									<td><?php echo $message_data['Sender']['user_name']; ?></td>
									<td><?php echo $message_data['Message']['subject']; ?></td>
									<td><?php echo $message_data['Message']['created']; ?></td>
									<td class="td-actions">
										<a href="#" class="btn btn-xs btn-primary" alt="View" title="Reply"><i class="glyphicon glyphicon-share-alt"></i> </a>
										<a href="#" class="btn btn-xs btn-info" alt="Reply" title="View"><i class="glyphicon glyphicon-envelope"></i> </a>
										<a href="#" class="btn btn-xs btn-warning" alt="Admin Profile" title="Admin Profile"><i class="glyphicon glyphicon-user"></i> </a>
										<a href="#" class="btn btn-xs btn-success" alt="Send Notification" title="Send Notification"><i class="glyphicon glyphicon-warning-sign"></i> </a>
										<a href="#" class="btn btn-xs btn-danger" alt="Delete Message" title="Delete Message"><i class="glyphicon glyphicon-trash"></i> </a>		
									</td>
								</tr>
								<?php
							}
						} else {
							echo '<tr><td colspan="6"><div class="norecord">No Record Found</div></td></tr>';
						}
						?>		
                    </tbody>
                </table>

            </div> <!-- /widget-content -->

        </div> <!-- /widget -->
    </div>
    <center>
		<?php if ($this->Paginator->counter('{:pages}') > 1) { ?>
			<ul class="pagination">
				<?php
					echo $this->Paginator->prev(__('<<'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
					echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
					echo $this->Paginator->next(__('>>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
				?>
			</ul>
		<?php } ?>
	</center>
</div>