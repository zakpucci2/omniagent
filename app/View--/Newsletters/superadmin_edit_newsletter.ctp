<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<?php echo $this->Html->script('fckeditor'); ?>
<style>
	form label.error {
		position: inherit !important;
	}
</style>
<div class="headline_cont fw">
	<h2>Edit Newsletter Template</h2>			
</div>
<?php echo $this->Form->create('newsletters', array('type' => 'POST', 'action' => 'edit_newsletter', 'name' => 'editNewsletterForm', 'id' => 'editNewsletterForm')); ?>	
<?php echo $this->Form->input('Newsletter.id', array('type' => 'hidden')); ?>     
<div class="">
	<label>Template Used For</label>
	<?php echo $this->Form->input('Newsletter.title', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'maxlength' => 100, 'required' => true)); ?> <br><br>
	<label>Sender Name</label>
	<?php echo $this->Form->input('Newsletter.sender_name', array('type' => 'text', 'class' => 'form-control', 'label' => false, 'div' => false, 'maxlength' => 255, 'required' => true)); ?> <br><br>
	<label>Sender Email</label>
	<?php echo $this->Form->input('Newsletter.sender_email', array('type' => 'email', 'class' => 'form-control', 'label' => false, 'div' => false, 'maxlength' => 255, 'required' => true)); ?> <br><br>
	<label>Newsletter Subject</label>
	<?php echo $this->Form->input('Newsletter.mail_subject', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 100, 'required' => true, 'class' => 'form-control')); ?> <br><br>
	<label>Newsletter Body</label>
	<?php
	echo $this->Form->input('Newsletter.mail_body', array('type' => 'textarea', 'rows' => 10, 'cols' => 50, 'label' => false, 'div' => false, 'required' => true, 'class' => 'form-control'));
	echo $this->Fck->load('NewsletterMailBody');
	?><br><br>
	<button class="btn btn-primary btnSubmit" type="submit">Update</button>
	<button class="btn btn-default cancel" data-href="<?php echo $this->Html->url(array('controller' => 'newsletters', 'action' => 'listnewsletters', 'superadmin' => true)); ?>" type="button">Cancel</button>
</div>
<?php echo $this->Form->end(); ?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#editNewsletterForm").validate();
		$(".cancel").click(function(event) {
			event.preventDefault();
			window.location.href = $(this).attr('data-href');
		});
	});
</script>