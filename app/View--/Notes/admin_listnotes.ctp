<style>
    .block-icon-default { color: #E34C3B !important; }
</style>

<div><br>

    <div class="col-lg-12 ch-heading">
        <div class="row">
            <div class="pull-left"><strong class="lead"><i class="glyphicon glyphicon-file"></i>My Notes</strong></div>
            <div class="pull-right">
                <?php echo $this->Html->link('<i class="glyphicon glyphicon-plus"></i> New Note', array('controller' => 'notes', 'action' => 'addnote', 'admin' => true), array('escape' => false, 'class' => 'btn btn-primary', 'title' => 'Add Note', 'id' => 'addNote')); ?>
            </div>
        </div>
    </div>
    <br><br>


    <?php echo $this->Form->create('notes', array('action' => 'listnotes', 'type' => "GET"), array('class' => "control-group")); ?>
    <div class="input-group">
        <?php
        if (isset($_GET['search']) && trim($_GET['search']) != '') {
            $val = $_GET['search'];
        } else {
            $val = '';
        }
        echo $this->Form->input('User.search', array('type' => 'text', 'value' => $val, 'placeholder' => "Search Note", 'class' => 'form-control', 'maxlength' => 100, 'label' => false, 'div' => false));
        ?>
        <span class="input-group-btn">
            <button class="btn btn-primary" type="submit">
                Search!</button>
        </span>
    </div>
    <?php echo $this->Form->end(); ?>

    <br/>
    <div class="span7">   
        <div class="widget stacked widget-table action-table">


            <div class="widget-content">



                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><?php echo $this->Paginator->sort('note_title', 'Title'); ?></th>
                            <th><?php echo $this->Paginator->sort('note_subject', 'Subject'); ?></th>                            
                            <th><?php echo $this->Paginator->sort('associated_with', 'Associate Client'); ?></th>
                            <th><?php echo $this->Paginator->sort('modified', 'Last Modified'); ?></th>
                            <th>Actions</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($data) && !empty($data)) {
                            foreach ($data as $notedata) {
                                ?>
                                <tr>
                                    <td><?php echo $notedata['Note']['note_title']; ?></td>
                                    <td><?php echo $notedata['Note']['note_subject']; ?></td>
                                    <td><?php if($notedata['Note']['associated_with']=='') { echo "<b>PERSONAL</b>";} else { echo $notedata['User']['user_name'];} ?></td>
                                    <td><?php echo $notedata['Note']['ago']; ?></td>
                                    <td>
                                        
                                        <?php echo $this->Html->link('<i class="glyphicon glyphicon-pencil"></i>', array('controller' => 'notes', 'action' => 'editnote', base64_encode($notedata['Note']['id'])), array('escape' => false, 'class' => 'btn btn-sm btn-default editRowNote', 'title' => 'Edit Note')); ?>
                                        <?php echo $this->Html->link('<i class="glyphicon glyphicon-trash"></i>', 'javascript:void(0)', array('data-name' => $notedata['Note']['note_title'], 'escape' => false, 'class' => 'btn btn-sm btn-default delete', 'title' => 'Delete Note', 'data-href' => $this->Html->url(array('controller' => 'notes', 'action' => 'deletenote', base64_encode($notedata['Note']['id']))))); ?>
                                        <?php echo $this->Html->link('<i class="glyphicon glyphicon-eye-open"></i>', 'javascript:void(0)', array('data-title' => htmlentities($notedata['Note']['note_title'],ENT_QUOTES),'data-subject' => htmlentities($notedata['Note']['note_subject'],ENT_QUOTES),'data-content' => htmlentities($notedata['Note']['note_body'],ENT_QUOTES),'data-note-on'=>$notedata['Note']['ago'], 'escape' => false, 'class' => 'btn btn-sm btn-default view', 'title' => 'View Note')); ?>
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
    <center><?php if ($this->Paginator->counter('{:pages}') > 1) { ?>
            <ul class="pagination">
                <?php
                echo $this->Paginator->prev(__('<<'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                echo $this->Paginator->numbers(array('separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li', 'first' => 1));
                echo $this->Paginator->next(__('>>'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
                ?>
            </ul>
        <?php } ?></center>

</div>



        <!-- Modal Delete -->
        <div id="DeleteModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Delete Note</h3>
                    </div>
                    <div class="modal-body">
                        <center>
                            Are you sure want to delete this note : "<strong>selected user</strong>"?
                        </center>	
                    </div>
                    <div class="modal-footer">
                        <center>
                            <a href="#" class="btn btn-default" data-dismiss="modal">NO</a>
                            <a href="#" class="btn btn-primary">Yes</a>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal View -->
        <div id="ViewModel" tabindex="-1" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Note</h3>
                    </div>
                    <div class="modal-body">
                        
                            <p id="notemodified">Last Modified : <strong>note modified</strong></p>
                            <p id="notetitle">Title : <strong>note subject</strong></p>
                            <p id="notesubject">Subject :  <strong>note subject</strong></p>
                            <p id="notebody">Note : <strong>note body</strong></p>
                            
                       
                    </div>
                    <div class="modal-footer">
                        <center>
                            <a href="#" class="btn btn-default" data-dismiss="modal">OK</a>
                        </center>
                    </div>
                </div>
            </div>
        </div>

                            <!-- Modal Add Client -->
                            <?php echo $this->Element('admin/addNote'); ?>
                            <?php echo $this->Element('admin/editNote'); ?>
                            <!-- Modal Edit Client -->
                            

                            <script type="text/javascript">
                                $(document).ready(function() {
                                    
                                    $(".delete").click(function(event) {
                                        event.preventDefault();
                                        $('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-body > center > strong').html($(this).attr('data-name'));
                                        $('#DeleteModel > div.modal-dialog > div.modal-content > div.modal-footer > center > a.btn-primary').attr('href', $(this).attr('data-href'));
                                        $('#DeleteModel').modal('show');
                                    });
                                    
                                    $(".view").click(function(event) {
                                        event.preventDefault();
                                        $('#ViewModel > div.modal-dialog > div.modal-content > div.modal-body  > p#notemodified >strong').html($(this).attr('data-note-on'));
                                        $('#ViewModel > div.modal-dialog > div.modal-content > div.modal-body  > p#notetitle >strong').html($(this).attr('data-title'));
                                        $('#ViewModel > div.modal-dialog > div.modal-content > div.modal-body  > p#notesubject >strong').html($(this).attr('data-subject'));
                                       
                                        var strcontent=nl2br($(this).attr('data-content'));
                                        $('#ViewModel > div.modal-dialog > div.modal-content > div.modal-body > p#notebody >strong').html(strcontent);
                                        $('#ViewModel').modal('show');
                                    });

                                    $("#addNote").click(function(event) {
                                        event.preventDefault();
                                        $('#AddNoteModel').modal('show');
                                    });

                                });

                            </script>
