<div class="modal fade" id="NotifyModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-focus-on="#UserFirstName">
    <div class="modal-dialog">
		<?php echo $this->Form->create('notifications', array('type' => 'POST', 'url' => Configure::read('ROOTURL') . '/notifications/read_notification', 'name' => 'read_notification', 'id' => 'read_notification', 'class' => 'form-horizontal', 'role' => 'form')); ?>	

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Your Notification</h4>
            </div>
            <div class="modal-body">
				<?php
				if (!empty($notify_data)) {
					$i = 1;
					foreach ($notify_data as $data):
						?>
						<div class="input-group"><b><?php echo $i; ?>) Subject : <?php echo $data['Notification']['subject']; ?></b></div>
						<div class="input-group">Notification :<?php echo $data['Notification']['body']; ?></div>
						<div class="input-group"><b><i>Notification By :<?php echo $data['Sender']['first_name'] . ' ' . $data['Sender']['last_name']; ?> &nbsp;<?php echo $data['Notification']['ago']; ?></i></b></div><br>
						<?php
						$i++;
					endforeach;
				}
				?>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
				<button type="submit" id="singlebutton" class="btn btn-primary">I READ IT</button>
			</div>
		</div>
	<?php echo $this->Form->end(); ?>
	</div>
</div>