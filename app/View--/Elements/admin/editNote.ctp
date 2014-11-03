<div id="editNoteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <?php echo $this->Form->create('Note', array('type' => 'POST', 'action' => 'editnote', 'name' => 'editNote', 'id' => 'editNote', 'class' => 'form-horizontal styleThese', 'role' => 'form', '')); ?>	
    <?php echo $this->Form->end(); ?>
</div>

<script type="text/html" id='editTemplateNote'>
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Edit Note</h3>
    </div>
    <div class="modal-body">
     <input type="hidden" id="NoteId" name="data[Note][id]"  value="<%= note.id %>" /> 
	<center>
        <div class="input-group">
            <span class="input-group-addon">Title<span class="star">*</span></span>            
                <input type="text" id="NoteTitle" placeholder="Title" class="form-control" required="required" maxlength="50" name="data[Note][note_title]" value="<%= note.note_title %>"> 
        </div><br>
		<div class="input-group">
            <span class="input-group-addon">Subject<span class="star">*</span></span>            
                <input type="text" id="NoteSubject" placeholder="Title" class="form-control" required="required" maxlength="50" name="data[Note][note_subject]" value="<%= note.note_subject %>"> 
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
                <textarea id="NoteNoteBody" cols="30" placeholder="" class="form-control" required="required" rows="5" name="data[Note][note_body]"><%= note.note_body %></textarea> 
        </div><br>
       
    </center>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
        <button type="submit" id="singlebutton" class="btn btn-primary">Save</button>
    </div>
    </div>
    </div>
</script>

<script type="text/javascript">
    $(document).ready(function() {  
                       
        
        $("#editNote").validate();
        
        
        var editUrl = $('#editNote').attr('action');
        $(".editRowNote").click(function(event) {
            event.preventDefault();
            var url = $(this).attr('href');
            $('body').modalmanager('loading');
            $.getJSON(url, function(data) {
                $('#editNote').attr('action', editUrl + '/' + data.notes.Note.id);
                var template = $("#editTemplateNote").html();
                $("#editNote").html(_.template(template, {note:data.notes.Note,PopupTitle:data.PopupTitle}));
                $(".input-group select").val(data.notes.Note.associated_with);
                $('#editNoteModel').modal('show');
            });
        });

    });
</script>