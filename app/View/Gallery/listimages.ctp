<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<style>
	.pager .next>a {
		float:none !important;
	}
</style>
<?php $this->Html->addCrumb('Gallery', array('controller' => 'gallery', 'action' => 'listimages')); ?>
<div class="row-fluid">	
	<a href="#uploadModal" data-toggle="modal" class="btn btn-primary btn-success"><i class="halflings-icon white plus"></i> <?php echo __('Upload Image'); ?></a>
	<br /><br />
		<?php
		if (isset($data) && !empty($data)) {
			$counter = 1; ?>
			<ul class="thumbnails">
			<?php
				foreach ($data as $image_data) {
					if($counter == 4) {
						$counter = 1;
						echo '</ul>' . "\n";
						echo '<ul class="thumbnails">';
					}
				?>
				<li class="span4">
					<div class="thumbnail">
						<?php
						if ($image_data['UserImage']['image_name'] == '') {
							echo $this->Html->image('gallery/thumbnails320x200/320x200.gif', array('alt' => "", 'style' => 'width:320px; height:200px'));
						} else {
							if (file_exists(str_replace('\\', '/', WWW_ROOT) . 'img/gallery/thumbnails320x200/' . $image_data['UserImage']['image_name'])) {
								echo $this->Html->image('gallery/thumbnails320x200/' . $image_data['UserImage']['image_name'], array('alt' => $image_data['UserImage']['image_title'], 'title' => $image_data['UserImage']['image_title']));
							} else {
								echo $this->Html->image('gallery/thumbnails320x200/320x200.gif', array('alt' => "", 'style' => 'width:320px; height:200px'));
							}
						}
						?>
						<div class="caption caption-long-text">
							<h3>
								<?php echo $this->Text->truncate(
									$image_data['UserImage']['image_title'],
									40,
									array(
										'ellipsis' => '...',
										'exact' => true
									)
								); ?>
							</h3>
							<p>
								<?php echo $this->Text->truncate(
									$image_data['UserImage']['image_description'],
									45,
									array(
										'ellipsis' => '...',
										'exact' => true
									)
								); ?>
							</p>
							<p align="center">
								<?php echo $this->Html->link('<i class="halflings-icon white search"></i> View', array('controller' => 'gallery', 'action' => 'view_image', base64_encode($image_data['UserImage']['id'])), array('escape' => false, 'class' => 'btn btn2 btn-info btn-md viewRow', 'title' => 'View Image Detail')); ?>
								<?php echo $this->Html->link('<i class="halflings-icon white share-alt"></i> Edit', array('controller' => 'gallery', 'action' => 'update_photo', base64_encode($image_data['UserImage']['id'])), array('escape' => false, 'class' => 'btn btn2 btn-success btn-md replyRow', 'title' => 'Update Image')); ?>
								<?php echo $this->Html->link('<i class="halflings-icon white download"></i> Download', 'javascript:void(0)', array('escape' => false, 'class' => 'btn btn2 btn-setting btn-warning downloadRow', 'title' => 'Download Image', 'data-href' => $this->Html->url(array('controller' => 'gallery', 'action' => 'download_photo', base64_encode($image_data['UserImage']['id']))))); ?>
								<?php echo $this->Html->link('<i class="halflings-icon white trash"></i> Delete', 'javascript:void(0)', array('data-name' => $image_data['UserImage']['image_title'], 'escape' => false, 'class' => 'btn btn2 btn-danger btn-md delRow', 'title' => 'Delete Image', 'data-href' => $this->Html->url(array('controller' => 'gallery', 'action' => 'delete_image', base64_encode($image_data['UserImage']['id']))))); ?>
							</p>
						</div>
					</div>
				</li>
				<?php
				$counter++;
			} ?>
			</ul>
		<?php
		} else {
			echo '<ul class="thumbnails"><li class="span4"><div class="norecord">No Record Found</div></li></ul>';
		}
		?>
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
<?php echo $this->Element('gallery/view_image'); ?>
<?php echo $this->Element('gallery/update_image'); ?>
<div class="modal hide fade" id="uploadModal">
	<?php echo $this->Form->create('UserImage', array('type' => 'file', 'url' => Configure::read('ROOTURL') . '/gallery/upload_photo', 'id' => 'UploadImageForm', 'class' => 'form-horizontal', 'role' => 'form')); ?>
	<?php echo $this->Form->input('UserImage.id', array('type' => 'hidden', 'label' => false, 'div' => false)); ?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title" id="myModalLabel">Upload Image</h4>
	</div>
	<div class="modal-body">
		<div class="control-group">
			<label class="control-label">Upload Image</label>
			<div class="controls">
				<?php echo $this->Form->input('UserImage.image', array('type' => 'file', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xlarge', 'required' => true, 'placeholder' => 'Please upload image.')); ?>
			</div>Image should be 320x200 in .jpeg, .png, .gif format.
		</div>
		<div class="control-group">
			<label class="control-label">Image Title</label>
			<div class="controls">
				<?php echo $this->Form->input('UserImage.image_title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 255, 'class' => 'input-xlarge', 'required' => true, 'placeholder' => 'Image Title')); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Image Description</label>
			<div class="controls">
				<?php echo $this->Form->textarea('UserImage.image_description', array('label' => false, 'div' => false, 'row' => 6, 'maxlength' => 500, 'class' => 'input-xlarge textarea-large', 'required' => true, 'placeholder' => 'Image Description')); ?>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button id="singlebutton" type="submit"  name="singlebutton" class="btn btn-primary">Upload</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<div class="modal hide fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Delete Gallery Image</h3>
            </div>
            <div class="modal-body">
                <center>
                    Are you sure want to delete "<strong>selected message</strong>" from gallery images?
                </center>	
            </div>
            <div class="modal-footer">
                <center>
                    <a href="#" class="btn btn-default" data-dismiss="modal" id="closeDelete">NO</a>
                    <a href="#" class="btn btn-primary" id="sendDelete">Yes</a>
                </center>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#UploadImageForm").validate();
		
		$("#sendDelete").click(function() {
			window.location.href = $(this).attr('href');
		});
		
		$(".delRow").click(function(event) {
			event.preventDefault();
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
			$('#deleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
			$('#deleteModel').modal('show');
		});
		
		$(".downloadRow").click(function(event) {
			event.preventDefault();
			window.location.href = $(this).attr('data-href');
		});
	});
</script>