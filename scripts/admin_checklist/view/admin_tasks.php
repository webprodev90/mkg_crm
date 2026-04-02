

<?php foreach($admin_tasks as $row): ?>
    <div class="admin-task" data-id="<?= $row['id']; ?>">
        <div class="wrapper-admin-task d-flex align-items-start">
            <div style="margin-top: 3px;">
                <input name="is_completed" type="checkbox" class="form-control" <?= $row['is_checked'] ? ' checked' : ''; ?>/>
            </div>
            <div class="task_description"><?= $row['task_description']; ?></div>
            <button type="button" class="update-admin-task" style="">          
                <i class="mdi mdi-border-color" style="cursor: pointer;"></i>
            </button>  
            <button type="button" class="delete-admin-task">  
                <i class="mdi mdi-delete" style="cursor: pointer;"></i>
            </button>
        </div>
        <form class="form-horizontal form-updated-task d-none">
            <div class="form-group mb-0">
                <div class="row">
                    <div class="col-md-12">
                        <textarea id="updated-admin-task" type="text" name="updated-admin-task" class="form-control"><?= $row['task_description']; ?></textarea>
                    </div>
                </div> 
                <div class="row mt-2">
                    <button type="button" class="save-admin-task btn btn-sm btn-success ml-3">Сохранить</button>
                    <button type="button" class="update-admin-task-cancel btn btn-sm btn-danger ml-2">Отменить</button>
                </div>                
            </div>
        </form>                              
    </div>
<?php endforeach; ?>