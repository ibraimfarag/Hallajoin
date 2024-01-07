<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title"><?php echo e(__("Cloud Storage Configs")); ?></h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label><?php echo e(__('Select Cloud Driver')); ?></label>
                    <div class="form-controls">
                        <select name="filesystem_default" class="form-control">
                            <option value="uploads" <?php echo e(setting_item('filesystem_default') == 'uploads' ? 'selected' : ''); ?>><?php echo e(__('-- Local Storage --')); ?></option>
                            <option value="s3" <?php echo e(setting_item('filesystem_default') == 's3' ? 'selected' : ''); ?>><?php echo e(__('AWS S3')); ?></option>
                            <option value="gcs" <?php echo e(setting_item('filesystem_default') == 'gcs' ? 'selected' : ''); ?>><?php echo e(__('Google Cloud Storage')); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel" data-condition="filesystem_default:is(s3)">
            <div class="panel-title"><strong><?php echo e(__("Amazon S3")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class=""><?php echo e(__("Key")); ?></label>
                    <input type="text" class="form-control" autocomplete="none" name="filesystem_s3_key" value="<?php echo e(setting_item('filesystem_s3_key')); ?>" />
                </div>
                <div class="form-group">
                    <label class=""><?php echo e(__("Secret access key")); ?></label>
                    <input type="text" class="form-control" autocomplete="none" name="filesystem_s3_secret_access_key" value="<?php echo e(setting_item('filesystem_s3_secret_access_key')); ?>" />
                </div>
                <div class="form-group">
                    <label class=""><?php echo e(__("Default region")); ?></label>
                    <input type="text" class="form-control" autocomplete="none" name="filesystem_s3_region" value="<?php echo e(setting_item('filesystem_s3_region')); ?>" />
                </div>
                <div class="form-group">
                    <label class=""><?php echo e(__("Bucket")); ?></label>
                    <input type="text" class="form-control" autocomplete="none" name="filesystem_s3_bucket" value="<?php echo e(setting_item('filesystem_s3_bucket')); ?>" />
                </div>
            </div>
        </div>
        <div class="panel" data-condition="filesystem_default:is(gcs)">
            <div class="panel-title"><strong><?php echo e(__("Google Cloud Storage")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class=""><?php echo e(__("Project ID")); ?></label>
                    <input type="text" class="form-control" autocomplete="none" name="gcs_project_id" value="<?php echo e(setting_item('gcs_project_id')); ?>" />
                </div>
                <div class="form-group">
                    <label class=""><?php echo e(__("Bucket")); ?></label>
                    <input type="text" class="form-control" autocomplete="none" name="gcs_bucket" value="<?php echo e(setting_item('gcs_bucket')); ?>" />
                </div>
                <div class="form-group">
                    <label class=""><?php echo e(__("Service Account Key File Name")); ?></label>
                    <input type="text" class="form-control" autocomplete="none" name="gcs_key_file" value="<?php echo e(setting_item('gcs_key_file')); ?>" />
                </div>

                <p>
                    * If your system does not hosted on Google Cloud, you need to upload your Service Account Key File to folder:
                    <code><?php echo e(storage_path('app/gcs')); ?></code>, then copy file name to input above, Example: project-name-xxx-xxx.json <br>

                    * If running in Google App Engine, the built-in service account associated with the application will be used.
                    <br>
                    * If running in Google Compute Engine, the built-in service account associated with the virtual machine instance will be used.
                </p>
            </div>
        </div>
    </div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\modules/Media/Views/admin/settings/file-system.blade.php ENDPATH**/ ?>