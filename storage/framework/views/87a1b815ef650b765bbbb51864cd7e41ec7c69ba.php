<?php if(is_default_lang()): ?>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h3 class="form-group-title"><?php echo e(__('Config Broadcast')); ?></h3>
            <p class="form-group-desc"><?php echo e(__('Change your config broadcast site')); ?></p>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-title"><strong><?php echo e(__("Broadcast Driver")); ?></strong></div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-controls">
                            <select name="broadcast_driver" class="form-control">
                                <?php $__currentLoopData = \Modules\Core\SettingClass::BROADCAST_DRIVER; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(setting_item('broadcast_driver') == $value ? 'selected' : ''); ?>><?php echo e(__(strtoupper($value))); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h3 class="form-group-title"><?php echo e(__('Pusher API')); ?></h3>
            <p class="form-group-desc"><?php echo e(__('Change your API for pusher here. It will use for chat plugin and notification')); ?></p>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-title"><strong><?php echo e(__("Pusher API Information")); ?></strong></div>
                <div class="panel-body">
                    <div class="form-group" >
                        <label><?php echo e(__('API KEY')); ?></label>
                        <div class="form-controls">
                            <input type="text" name="pusher_api_key" value="<?php echo e(setting_item('pusher_api_key')); ?>" class="form-control">

                        </div>
                    </div>
                    <div class="form-group" >
                        <label><?php echo e(__('API Secret')); ?></label>
                        <div class="form-controls">
                            <input type="text" name="pusher_api_secret" value="<?php echo e(setting_item('pusher_api_secret')); ?>" class="form-control">

                        </div>
                    </div>
                    <div class="form-group" >
                        <label><?php echo e(__('APP ID')); ?></label>
                        <div class="form-controls">
                            <input type="text" name="pusher_app_id" value="<?php echo e(setting_item('pusher_app_id')); ?>" class="form-control">

                        </div>
                    </div>
                    <div class="form-group" >
                        <label><?php echo e(__('Cluster')); ?></label>
                        <div class="form-controls">
                            <input type="text" name="pusher_cluster" value="<?php echo e(setting_item('pusher_cluster')); ?>" class="form-control">

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php endif; ?>
<?php /**PATH F:\MAMP\htdocs\core\modules/Core/Views/admin/settings/groups/parts/pusher.blade.php ENDPATH**/ ?>