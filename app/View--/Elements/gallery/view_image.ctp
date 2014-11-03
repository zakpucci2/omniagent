<style>
.imageDiv {
	width: 100%;
	text-align: center;
}
</style>
<div id="viewLightbox" class="modal hide fade" tabindex="-1" role="dialog">
</div>
<script type="text/html" id='viewImageTemplate'>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">×</button>
		<h3 class="modal-title"><%= image_data.UserImage.image_title %></h3>
	</div>
	<div class="modal-body">
		<div class="imageDiv">
			<img src="<?php echo $this->webroot . 'img/gallery/'; ?><%= image_data.UserImage.image_name %>"><br /><br />
		</div>
		<p><%= image_data.UserImage.image_description %></p>
	</div>
	<div class="modal-footer">
		<button class="btn btn-primary" onclick="javascript: downloadImage(this);" data-href="<?php echo $this->Html->url(array('controller' => 'gallery', 'action' => 'download_photo')) . '/' . '<%= imageId %>' . '/1'; ?>">Download Photo</button>
		<button class="btn" data-dismiss="modal">Close</button>
	</div>
</script>
<script type="text/javascript">
    $(document).ready(function() {  
        $(".viewRow").click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                var template = $("#viewImageTemplate").html();
                $("#viewLightbox").html(_.template(template, {image_data:data.image,PopupTitle:data.PopupTitle, imageId: data.imageId}));
				$('#viewLightbox').modal({show:true});
            });
        });
    });
	function downloadImage(obj) {
		window.location.href = $(obj).attr('data-href');
	};
</script>