<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title"><?php echo e(__("Permanently delete account")); ?></h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-title"><strong><?php echo e(__("Permanently delete account")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <?php if(is_default_lang()): ?>
                        <div class="form-controls">
                            <label><input type="checkbox" name="user_enable_permanently_delete" value="1" <?php if(setting_item('user_enable_permanently_delete')): ?> checked <?php endif; ?> > <?php echo e(__('Yes, please enable it')); ?></label>
                        </div>
                        <p><?php echo e(__('Permanently delete account will delete all services of that user and that user')); ?></p>
                    <?php else: ?>
                        <div class="form-group">
                            <label> <input type="checkbox" <?php if($settings['user_enable_permanently_delete'] ?? '' == 1): ?> checked <?php endif; ?> disabled name="user_enable_permanently_delete" value="1"> <?php echo e(__("Yes, please enable it")); ?></label>
                        </div>
                        <?php if($settings['user_enable_permanently_delete'] != 1): ?>
                            <p><?php echo e(__('You must enable on main lang.')); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="form-group" data-condition="user_enable_permanently_delete:is(1)">
                    <label><?php echo e(__("Content")); ?></label>
                    <div class="form-controls">
                        <textarea name="user_permanently_delete_content" class="d-none has-ckeditor" cols="30" rows="10"><?php echo e(setting_item_with_lang('user_permanently_delete_content',request()->query('lang')) ?? ''); ?></textarea>
                    </div>
                </div>
                <div class="form-group" data-condition="user_enable_permanently_delete:is(1)">
                    <label><?php echo e(__("Content confirm")); ?></label>
                    <div class="form-controls">
                        <textarea name="user_permanently_delete_content_confirm" class="d-none has-ckeditor" cols="30" rows="10"><?php echo e(setting_item_with_lang('user_permanently_delete_content_confirm',request()->query('lang')) ?? ''); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-sm-4">
        <h3 class="form-group-title"><?php echo e(__('Content Email Permanently delete account')); ?></h3>
        <div class="form-group-desc"><?php echo e(__('Content email verify send when user permanently deleted.')); ?>

            <?php $__currentLoopData = \Modules\User\Emails\UserPermanentlyDelete::CODE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><code><?php echo e($value); ?></code></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <?php if(is_default_lang()): ?>
                    <div class="form-group">
                        <label> <input type="checkbox" <?php if($settings['user_enable_permanently_delete_email'] ?? '' == 1): ?> checked <?php endif; ?> name="user_enable_permanently_delete_email" value="1"> <?php echo e(__("Enable?")); ?></label>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label> <input type="checkbox" <?php if($settings['user_enable_permanently_delete_email'] ?? '' == 1): ?> checked <?php endif; ?> disabled name="user_enable_permanently_delete_email" value="1"> <?php echo e(__("Enable?")); ?></label>
                    </div>
                    <?php if($settings['user_enable_permanently_delete_email'] != 1): ?>
                        <p><?php echo e(__('You must enable on main lang.')); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="panel" data-condition="user_enable_permanently_delete_email:is(1)">
            <div class="panel-title"><strong><?php echo e(__("To customer")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group" >
                    <label><?php echo e(__("Subject")); ?></label>
                    <div class="form-controls">
                        <input type="text" name="user_permanently_delete_subject_email" class="form-control"  value="<?php echo e(setting_item_with_lang('user_permanently_delete_subject_email',request()->query('lang')) ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group" >

                    <label><?php echo e(__("Content")); ?></label>
                    <div class="form-controls">
                        <textarea name="user_permanently_delete_content_email" class="d-none has-ckeditor" cols="30" rows="10"><?php echo e(setting_item_with_lang('user_permanently_delete_content_email',request()->query('lang')) ?? ''); ?></textarea>
                    </div>
                </div>


            </div>
        </div>
        <div class="panel" data-condition="user_enable_permanently_delete_email:is(1)">
            <div class="panel-title"><strong><?php echo e(__("To admin")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group" >
                    <label><?php echo e(__("Subject")); ?></label>
                    <div class="form-controls">
                        <input type="text" name="user_permanently_delete_subject_email_to_admin" class="form-control"  value="<?php echo e(setting_item_with_lang('user_permanently_delete_subject_email_to_admin',request()->query('lang')) ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group" >

                    <label><?php echo e(__("Content")); ?></label>
                    <div class="form-controls">
                        <textarea name="user_permanently_delete_content_email_to_admin" class="d-none has-ckeditor" cols="30" rows="10"><?php echo e(setting_item_with_lang('user_permanently_delete_content_email_to_admin',request()->query('lang')) ?? ''); ?></textarea>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\modules/User/Views/admin/settings/_permanently_delete.blade.php ENDPATH**/ ?>