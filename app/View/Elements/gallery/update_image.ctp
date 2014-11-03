<!-- Modal View Message-->
<div class="modal hide fade" id="updateImageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<?php echo $this->Form->create('UserImage', array('type' => 'file', 'url' => Configure::read('ROOTURL') . '/gallery/update_photo', 'id' => 'UpdateUserImageForm', 'class' => 'form-horizontal', 'role' => 'form')); ?>
	<?php echo $this->Form->end(); ?>
</div>
<!-- Modal View Message End-->
<script type="text/html" id='updateImageTemplate'>
	<input type="hidden" id="ImageId" name="data[UserImage][id]" value="<%= image_data.UserImage.id %>" />
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h4 class="modal-title" id="myModalLabel">Update Image Details</h4>
	</div>
	<div class="modal-body">
		<div class="control-group">
			<label class="control-label">Upload Image</label>
			<div class="controls">
				<input type="file" id="Image" class="input-xlarge" maxlength="255" name="data[UserImage][image]" placeholder= "Image Name" />
			</div>
			<center>
				<p>Image should be 320x200 in .jpeg, .png, .gif format.<p><br />
				<img src="<?php echo $this->webroot . 'img/gallery/thumbnails320x200/'; ?><%= image_data.UserImage.image_name %>" /><br /><br />
			</center>
		</div>
		<div class="control-group">
			<label class="control-label">Image Title</label>
			<div class="controls">
				<input type="text" id="ImageTitle" class="input-xlarge" maxlength="255" name="data[UserImage][image_title]" value="<%= image_data.UserImage.image_title %>" required="required" placeholder= "Image Title" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Image Description</label>
			<div class="controls">
				<textarea row="6" class="input-xlarge textarea-large" id="ImageDescription"  maxlength="500" name="data[UserImage][image_description]" placeholder="Image Description" required="required"><%= image_data.UserImage.image_description %></textarea>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button id="singlebutton" type="submit"  name="singlebutton" class="btn btn-primary">Update</button>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
</script>
<script type="text/javascript">
    $(document).ready(function() {       
        $("#UpdateUserImageForm").validate();        
        var updateImageURL = $('#UpdateUserImageForm').attr('action');
        $(".replyRow").click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                $('#UpdateUserImageForm').attr('action', updateImageURL + '/' + data.image.UserImage.id);
                var template = $("#updateImageTemplate").html();
                $("#UpdateUserImageForm").html(_.template(template, {image_data:data.image,PopupTitle:data.PopupTitle}));
                $('#updateImageModal').modal('show');
            });
        });
    });
</script>