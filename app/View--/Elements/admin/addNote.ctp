<?php //$this->Html->css('bootstrap/form_validaion',null, array('inline' => false)); ?>
<?php $this->Html->script('jquery.validate', array('inline' => false)); ?>
<style>.star {color:red;}</style>
  
   
    
    <div class="modal fade" id="AddNoteModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-focus-on="#UserFirstName">
        <div class="modal-dialog">
		 <?php echo $this->Form->create('notes', array('type' => 'POST', 'action' => 'addnote', 'name' => 'addNewNote', 'id' => 'addNewNote', 'class' => 'form-horizontal', 'role' => 'form')); ?>	
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Add Note</h4>
                </div>
    <div class="modal-body">
	<center>
        <div class="input-group">
            <span class="input-group-addon">Title<span class="star">*</span></span>            
                <?php echo $this->Form->input('Note.note_title', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Title')); ?> 
        </div><br>
		<div class="input-group">
            <span class="input-group-addon">Subject<span class="star">*</span></span>            
                <?php echo $this->Form->input('Note.note_subject', array('type' => 'text', 'label' => false, 'div' => false, 'maxlength' => 50, 'required' => true, 'class' => 'form-control', 'placeholder' => 'Subject')); ?> 
        </div><br>
        
        <div class="input-group">
            <span class="input-group-addon">Associated Client</span>            
                 <select class="form-control" id="selectClienttBox" name="data[Note][associated_with]">
                    <option value="">Personal Note</option>
                    <?php foreach(@$client_res as $K => $V) { 
                        echo '<option value="'.$K.'">'.$V.'</option>';
                    } ?>
                </select>
        </div><br>
        
        <div class="input-group">
            <span class="input-group-addon">Note<span class="star">*</span></span>            
                <?php echo $this->Form->input('Note.note_body', array('type' => 'textarea', 'label' => false, 'div' => false, 'rows' => 5, 'required' => true, 'class' => 'form-control', 'placeholder' => '')); ?> 
        </div><br>
        
		
       
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
        <button type="submit" id="singlebutton" class="btn btn-primary">Save</button>
    </div>
	</div>
	 <?php echo $this->Form->end(); ?>
</div>
</div>
   
</div>

<script type="text/javascript">

$(document).ready(function(){
    
    $.validator.addMethod("loginRegex", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-\s]+$/i.test(value);
    }, "Username must contain only letters, numbers, or dashes.");

    $("#addNewNote").validate();
});
        


</script>