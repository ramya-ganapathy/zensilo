<?= $this->Html->script(array('../assets/widgets/wizard/wizard', '../assets/widgets/wizard/wizard-demo', '../assets/widgets/tabs/tabs', '../assets/widgets/chosen/chosen', '../assets/widgets/chosen/chosen-demo','../assets/widgets/parsley/parsley')) ?>

   <style>
        .tasks-table{ position: relative; } 
        .tasks-table table tr th{font-weight: 600;}
        .setting{ font-size: 16px; padding-left:14px; cursor: pointer;}
         .table-menu{ width:120px; background: #ededed;  position: absolute; right: 0; top:77px;  display: none;}
        .table-menu ul{ padding: 0px 10px;}
        .table-menu ul li{ list-style-type: none; padding: 5px 5px; border-bottom: 1px solid #cccccc;}
        .table-menu ul li:last-child{ border-bottom:none; }
        .table-menu ul li:hover{ background:#E91E63; color:#fff; }
        </style>
        <script>
        $(".setting").click(function(){
           $(".table-menu").toggle();
         });
        </script>

             <div class="panel">
          <div class="panel-body content-box">
            <h3 class="title-hero bg-primary">Task</h3>
            <div class="example-box-wrapper">

            <div class="panel">
        <div class="panel-body">
        <h3 class="title-hero"> <button id="addclient" class="btn btn-alt btn-hover btn-primary0 float-right"  data-toggle="modal" data-target=".bs-example-modal-lg" ><span>Add New</span> <i class="glyph-icon icon-arrow-right"></i><div class="ripple-wrapper"></div></button></h3>


        <div class="example-box-wrapper">
        <div id="datatable-example_wrapper" class="dataTables_wrapper form-inline no-footer">
      
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered dataTable no-footer" id="datatable-example" role="grid" aria-describedby="datatable-example_info">
        <thead>
        <tr role="row">
        <th class="sorting_asc" tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending" aria-sort="ascending">
        #
        </th>
        <th class="sorting" tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" > Task Name</th>
        <th class="sorting" tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" > Assigned To</th>
        <th class="sorting" tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" > Project Name</th>
        <th class="sorting" tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending" > Due Date</th>
        <th class="sorting" tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1" aria-label="Rendering engine: activate to sort column ascending">
        Estimation
        </th>
        <th class="sorting" tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending" > Status </th>
      
        <th tabindex="0" aria-controls="datatable-example" rowspan="1" colspan="1">Actions</th>

        </tr>
        </thead>
        <tbody>
          <?php foreach($tasks as $k=>$tas){ ?>

            <tr class="gradeA <?php if($k%2 == 0) {?> odd <?php } else { ?> even <?php } ?>" role="row">
              <td><?= $k+1?></td>
              <td><?= $tas->task_name ?></td>
              <td><?=  $this->Custom->get_task_teams($tas->id) ?></td>
              <td><?= $this->Custom->get_projectname($tas->project_id) ?></td>
              <td><?= $tas->due_date ?></td>
              <td class="center"><?= $tas->estimated_effort ?> Hrs</td>
              <td class="center"><?= $tas->status ?></td>
              <td class="center">
                <div class="input-group-btn ">
                    <span class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="glyph-icon icon-cog setting"></i></span>
                    <ul class="dropdown-menu " role="menu">
                      <li class="ms-hover"><a href="#"  onclick="gettask('<?= $tas->id ?>');">View</a></li>
                      <?php if($tas->parent_task_id == 0) { ?>
                      <li class="ms-hover"><a href="#" onclick="settaskid('<?= $tas->id ?>','<?= $tas->project_id ?>');">Sub Task</a></li>
                      <?php } ?>
                      <li class="ms-hover"><a href="<?= $this->Url->build(array("action" => "tasks", $tas->id,"copy"));?>">Copy</a></li>
                      <li class="ms-hover"><a href="#" data-toggle="modal" data-target=".bs-document-modal-lg" onclick="setprojectid('<?= $tas->project_id ?>','<?= $tas->id ?>');" >Documents</a></li>
                      <li class="ms-hover"><a href="<?= $this->Url->build(array("controller"=>"tasks","action" => "defect", $tas->id, "add_task"));?>" >Defects</a></li>
                     <?php if($tas->status !='Completed' && $tas->status !='Cancelled') { ?>
                      <li class="ms-hover"><a href="<?= $this->Url->build(array("action" => "tasks", $tas->id));?>">Edit</a></li>
                      <li class="ms-hover"><a onclick="return confirm('Are you sure want to delete this Client?')" href="<?= $this->Url->build(array("action" => "tasks", $tas->id,"delete"));?>">Delete</a></li>
                      <?php } ?>
                    </ul>
                  </div>

              </td>
            </tr>
          <?php } ?>
        </tbody>
        </table>

        <div class="row">
        <div class="col-sm-6">
        <div class="dataTables_info" id="datatable-example_info" role="status" aria-live="polite"></div>
        </div>
        <div class="col-sm-6">
        <div class="dataTables_paginate paging_bootstrap" id="datatable-example_paginate">
        
        </div>
        </div>
        </div>

        </div>
        </div>    
        
        
        </div>
        </div>

            </div>
          </div>
        </div>
        
<?php if(isset($task)){ ?> 
<button id="editclient" class="btn btn-default" style="display:none;" data-toggle="modal" data-target=".bs-edit-modal-lg">Edit Task</button>
<script type="text/javascript">
  $(window).load(function(){
    $("#editclient").trigger("click");
  });
 
</script>  

 
    
<div class="modal fade bs-edit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" class="form-horizontal bordered-row" data-parsley-validate=""> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Edit Task</h4>
        </div>
        <div class="modal-body">
          <div class="content-box-wrapper">

              <div class="row">
                  <div >

                <div class="form-group">
                  <label class="col-sm-3 control-label">Task Name</label>
                  <div class="col-sm-6">
                    <input name="task_name" class="form-control" id="" placeholder="Task Name" type="text" value="<?= $task->task_name ?>" required="">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                    <textarea name="description" class="form-control" id="" placeholder="Description"  required=""><?= $task->description ?></textarea>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Choose Project</label>
                  <div class="col-sm-6">
                    <select  class="form-control" name="project_id"  required="">
                      <option value="">Select Project</option>
                    <?php foreach($projects as $key => $value) { ?>
                      <option value="<?php echo $value['id']; ?>" <?= $task->project_id == $value['id'] ? 'selected' : '';?> ><?php echo $value['project_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Estimated Effort(Hours)</label>
                  <div class="col-sm-6">
                    <input name="estimated_effort" class="form-control" id="" placeholder="Estimated Effort"  required="" value="<?= $task->estimated_effort ?>"/>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Priority</label>
                  <div class="col-sm-6">
                    <select  class="form-control" name="priority"  required="">
                      <option value="">Select Priority</option>
                      <option value="High" <?= $task->priority == "High" ? 'selected' : '';?>>High</option>
                      <option value="Low" <?= $task->priority == "Low" ? 'selected' : '';?>>Low</option>
                      <option value="Medium" <?= $task->priority == "Medium" ? 'selected' : '';?>>Medium</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-6">
                    <select  class="form-control" name="status"  required="">
                      <option value="">Select Status</option>
                      <option value="New" <?= $task->status == "New" ? 'selected' : '';?>>New</option>
                      <option value="In Progress" <?= $task->status == "In Progress" ? 'selected' : '';?>>In Progress</option>
                      <option value="Ready To Test" <?= $task->status == "Ready To Test" ? 'selected' : '';?>>Ready To Test</option>
                      <option value="Rejected" <?= $task->status == "Rejected" ? 'selected' : '';?>>Rejected</option>
                      <option value="Closed" <?= $task->status == "Closed" ? 'selected' : '';?>>Closed</option>
                      <option value="Completed" <?= $task->status == "Completed" ? 'selected' : '';?>>Completed</option>
                      <option value="Cancelled" <?= $task->status == "Cancelled" ? 'selected' : '';?>>Cancelled</option>
                    </select>
                  </div>
                </div> 

                <div class="form-group .bordered-row">
                  <label class="col-sm-3 control-label">Due Date</label>
                  <div class="col-sm-6">
                    <input name="due_date"  id="" type="text" class="bootstrap-datepicker2_end_date form-control"  data-date-format="yyyy-mm-dd" required="" value="<?= $task->due_date->format('Y-m-d'); ?>">
                  </div>
                </div>
                  
                <input type="hidden" name="id" value="<?= $task->id ?>">

                </div>
                </div>

            </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Close</button> 
          <button type="submit" class="btn btn-hover btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>

<?php if(isset($copy_task)){ ?> 
  <script type="text/javascript">

  $(window).load(function(){
     $(".bs-example-modal-lg").modal("show");  
   });
  </script>  <?php } ?>
<div class="modal fade bs-view-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" class="form-horizontal bordered-row" data-parsley-validate=""> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">View Task</h4>
        </div>
        <div class="modal-body">
          <div class="content-box-wrapper">
              <div class="row">
                  <div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Task Name</label>
                  <div class="col-sm-6">
                    <span id="viewtask_name"></span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                    <span id="viewdescription"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Choose Project</label>
                  <div class="col-sm-6">
                    <?php foreach($projects as $key => $value) { ?>
                      <span class="project_id" id="project_id<?php echo $value['id']; ?>" style="display:none;"><?php echo $value['project_name']; ?></span>
                      <?php } ?>
                   
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Estimated Effort(Hours)</label>
                  <div class="col-sm-6">
                    <span id="viewestimated_effort"></span> Hrs
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Priority</label>
                  <div class="col-sm-6">
                    <span id="viewpriority"></span>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-6">
                    <span id="viewstatus"></span>
                  </div>
                </div> 

                <div class="form-group .bordered-row">
                  <label class="col-sm-3 control-label">Due Date</label>
                  <div class="col-sm-6">
                    <span id="viewdue_date"></span>
                  </div>
                </div>
                  
               
                </div>
                </div>

            </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Close</button> 
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form method="post" enctype="multipart/form-data" class="form-horizontal bordered-row" data-parsley-validate=""> 
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">New Task</h4>
        </div>
        <div class="modal-body">
          <div class="content-box-wrapper">

              <div class="row">
                  <div >

                <div class="form-group">
                  <label class="col-sm-3 control-label">Task Name</label>
                  <div class="col-sm-6">
                    <input name="task_name" class="form-control" id="" placeholder="Task Name" type="text" required="" value="<?= isset($copy_task) ? $copy_task->task_name: ''?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Description</label>
                  <div class="col-sm-6">
                    <textarea name="description" class="form-control" id="" placeholder="Description"  required=""><?= isset($copy_task) ? $copy_task->description: ''?></textarea>
                  </div>
                </div>

                <div class="form-group" id="project_id_add_div">
                  <label class="col-sm-3 control-label">Choose Project</label>
                  <div class="col-sm-6">
                    <select  class="form-control" name="project_id"  required="" id="project_id_add" >
                      <option value="">Select Project</option>
                    <?php foreach($projects as $key => $value) { ?>
                      <option value="<?php echo $value['id']; ?>" <?= isset($copy_task) ? $copy_task->project_id == $value['id'] ? 'selected' : '' : '';?> ><?php echo $value['project_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Estimated Effort(Hours)</label>
                  <div class="col-sm-6">
                    <input name="estimated_effort" class="form-control" id="" placeholder="Estimated Effort"  required="" value="<?= isset($copy_task) ? $copy_task->estimated_effort: ''?>"/>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Priority</label>
                  <div class="col-sm-6">
                    <select  class="form-control" name="priority"  required="">
                      <option value="">Select Priority</option>
                      <option value="High" <?= isset($copy_task) ? $copy_task->priority == "High" ? 'selected' : '' : '';?>>High</option>
                      <option value="Low" <?= isset($copy_task) ? $copy_task->priority == "Low" ? 'selected' : '' : '';?>>Low</option>
                      <option value="Medium" <?= isset($copy_task) ? $copy_task->priority == "Medium" ? 'selected' : '' : '';?>>Medium</option>
                    </select>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Status</label>
                  <div class="col-sm-6">
                    <select  class="form-control" name="status"  required="">
                      <option value="">Select Status</option>
                      <option value="New" <?= isset($copy_task) ? $copy_task->status == "New" ? 'selected' : '' : '';?>>New</option>
                      <option value="In Progress" <?= isset($copy_task) ? $copy_task->status == "In Progress" ? 'selected' : '' : '';?>>In Progress</option>
                      <option value="Ready To Test" <?= isset($copy_task) ? $copy_task->status == "Ready To Test" ? 'selected' : '' : '';?>>Ready To Test</option>
                       <option value="Rejected" <?= isset($copy_task) ? $copy_task->status == "Rejected" ? 'selected' : '': '';?>>Rejected</option>
                      <option value="Closed" <?= isset($copy_task) ? $copy_task->status == "Closed" ? 'selected' : '': '';?>>Closed</option>
                       <option value="Cancelled" <?= isset($copy_task) ? $copy_task->status == "Cancelled" ? 'selected' : '': '';?>>Cancelled</option>
                      <option value="Completed" <?= isset($copy_task) ? $copy_task->status == "Completed" ? 'selected' : '' : '';?>>Completed</option>                      
                    </select>
                  </div>
                </div> 

                <div class="form-group .bordered-row">
                  <label class="col-sm-3 control-label">Due Date</label>
                  <div class="col-sm-6">
                    <input name="due_date"  id="" type="text" class="bootstrap-datepicker2 form-control"  data-date-format="yyyy-mm-dd" required="" value="<?= isset($copy_task) ? $copy_task->due_date->format('Y-m-d'): ''?>">
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-3 control-label">Assign Team</label>
                  <div class="col-sm-6"> <?php $teams = array(); if(isset($copy_task_teams)) { foreach($copy_task_teams as $key => $value) {  $teams[] = $value->user_id; ?> <?php } } ?>
                    <select multiple="multiple" class="multi-select" name="assigned_to[]" id="14multiselect" style="position: absolute; left: -9999px;">
                    <?php foreach($team_members as $key => $value) { ?>
                      <option value="<?php echo $value['user_id']; ?>" <?php if(in_array($value['user_id'], $teams)) { ?> selected <?php } ?>><?php echo $value['client_name']; ?></option>
                      <?php } ?>
                    </select>

                    <input type="hidden" name="parent_task_id" value="" id="parent_task_id" />
                  </div>
                </div>

                </div>
                </div>
            </div>         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default " data-dismiss="modal">Close</button> 
          <button type="submit" class="btn btn-hover btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade bs-document-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="fileupload" method="post" enctype="multipart/form-data" class="form-horizontal bordered-row" data-parsley-validate=""> 

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title">Add Documents</h4>
        </div>
        <div class="modal-body">
          <div class="panel">
          <div class="panel-body">
            <h3 class="title-hero">Add Documents</h3>
            <div class="example-box-wrapper">
              <form id="fileupload" action="<?= $this->Url->build(array("controller" => "tasks","action" => "server")) ?>" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="project_doc_id" value="" id="project_doc_id" />
                      <input type="hidden" name="task_doc_id" value="" id="task_doc_id" />
                <div class="row fileupload-buttonbar">
                  <div class="col-lg-12">
                    <div class="float-left"><span class="btn btn-md btn-success fileinput-button"><i class="glyph-icon icon-plus"></i> Add files...
                      <input type="file" name="files[]" multiple="multiple">
                      </span>
                      <button type="submit" class="btn btn-md btn-default start"><i class="glyph-icon icon-upload"></i> Start upload</button>
                      <button type="reset" class="btn btn-md btn-warning cancel"><i class="glyph-icon icon-ban"></i> Cancel upload</button>
                      <button type="button" class="btn btn-md btn-danger delete"><i class="glyph-icon icon-trash-o"></i> Delete</button>
                    </div>
                    <input type="checkbox" class="toggle width-reset float-left">
                    <span class="fileupload-process"></span></div>
                  <div class="col-lg-6 fileupload-progress fade">
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                      <div class="progress-bar progress-bar-success bg-green">
                        <div class="progressbar-overlay"></div>
                      </div>
                    </div>
                    <div class="progress-extended">&nbsp;</div>
                  </div>
                </div>
                <table role="presentation" class="table table-striped">
                  <tbody class="files">
                  </tbody>
                </table>
              </form>
              <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
                <div class="slides"></div>
                <h3 class="title"></h3>
                <a class="prev">‹</a> <a class="next">›</a> <a class="close">×</a> <a class="play-pause"></a>
                <ol class="indicator">
                </ol>
              </div>
              <script id="template-upload" type="text/x-tmpl">{% for (var i=0, file; file=o.files[i]; i++) { %}
                  <tr class="template-upload fade">
                      <td>
                          <span class="preview"></span>
                      </td>
                      <td>
                          <p class="name">{%=file.name%}</p>
                          <strong class="error text-danger"></strong>
                      </td>
                      <td>
                          <p class="size">Processing...</p>
                          <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success bg-green" style="width:0%;"></div></div>
                      </td>
                      <td>
                          {% if (!i && !o.options.autoUpload) { %}
                              <button class="btn btn-md btn-default start" disabled>
                                <span class="button-content">
                                  <i class="glyph-icon icon-upload"></i>
                                  Start
                                </span>
                              </button>
                          {% } %}
                          {% if (!i) { %}
                              <button class="btn btn-md btn-warning cancel">
                                  <span class="button-content">
                                    <i class="glyph-icon icon-ban-circle"></i>
                                    Cancel
                                  </span>
                              </button>
                          {% } %}
                      </td>
                  </tr>
              {% } %}</script>
              <script id="template-download" type="text/x-tmpl">{% for (var i=0, file; file=o.files[i]; i++) { %}
                  <tr class="template-download fade">
                      <td>
                          <span class="preview">
                              {% if (file.thumbnailUrl) { %}
                                  <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}" style="width:100px;height:100px;"></a>
                              {% } %}
                          </span>
                      </td>
                      <td>
                          <p class="name">
                              {% if (file.url) { %}
                                  <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                              {% } else { %}
                                  <span>{%=file.name%}</span>
                              {% } %}
                          </p>
                          {% if (file.error) { %}
                              <div><span class="label label-danger">Error</span> {%=file.error%}</div>
                          {% } %}
                      </td>
                      <td>
                          <span class="size">{%=o.formatFileSize(file.size)%}</span>
                      </td>
                      <td>
                          {% if (file.deleteUrl) { %}
                              <button class="btn btn-md btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                  <span class="button-content">
                                    <i class="glyph-icon icon-trash"></i>
                                    Delete
                                  </span>
                              </button>
                              <input type="checkbox" name="delete" value="1" class="toggle width-reset float-left">
                          {% } else { %}
                              <button class="btn btn-md btn-warning cancel">
                                  <span class="button-content">
                                    <i class="glyph-icon icon-ban-circle"></i>
                                    Cancel
                                  </span>
                              </button>
                          {% } %}
                      </td>
                  </tr>
              {% } %}</script></div>
          </div>
        </div>
        </div>

    </form>
    </div>
    </div>
    </div>


<script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>

<?= $this->Html->script(array('../assets/widgets/multi-upload/jquery.iframe-transport', '../assets/widgets/multi-upload/jquery.fileupload', '../assets/widgets/multi-upload/jquery.fileupload-process', '../assets/widgets/multi-upload/jquery.fileupload-image', '../assets/widgets/multi-upload/jquery.fileupload-audio', '../assets/widgets/multi-upload/jquery.fileupload-video', '../assets/widgets/multi-upload/jquery.fileupload-validate', '../assets/widgets/multi-upload/jquery.fileupload-ui', '../assets/widgets/multi-upload/main')); ?>
<!--[if (gte IE 8)&(lt IE 10)]>
<?= $this->Html->script(array('../assets/widgets/multi-upload/cors/jquery.xdr-transport')); ?>
<![endif]-->

<script>
function setprojectid(pid, tid ){
  $('#project_doc_id').val(pid);
  $('#task_doc_id').val(tid);
}

function settaskid(tid,pid){
  $('#parent_task_id').val(tid);
  $('#project_id_add').val(pid);
  $('#project_id_add_div').css("display", "none"); 
  $('#addclient').trigger('click');
}

function gettask(id){
  $.get("<?= $this->Url->build(['controller' => 'ajax', 'action' => 'gettask']) ?>/"+id, function(response){
    response = JSON.parse(response);
    if(response){
      $('.bs-view-modal-lg').modal();
      $('#viewtask_name').text(response.task_name);
      $('#viewdescription').text(response.description);
      $('.project_id').css('display','none');
      $('#project_id'+response.project_id).css('display','block');
      $('#viewestimated_effort').text(response.estimated_effort);
      $('#viewpriority').text(response.priority);
      $('#viewstatus').text(response.status);
      $('#viewdue_date').text(response.due_date);
    }
  });
}
</script>