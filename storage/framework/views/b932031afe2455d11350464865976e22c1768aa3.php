<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title"><?php echo e(__("User Plans Options")); ?></h3>
        <p class="form-group-desc"><?php echo e(__('Config user plans page')); ?></p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">

                <?php if(is_default_lang()): ?>
                    <div class="form-group">
                        <label><?php echo e(__("Enable User Plans")); ?></label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="user_plans_enable" value="1" <?php if(!empty($settings['user_plans_enable'])): ?> checked <?php endif; ?> /> <?php echo e(__("On")); ?> </label>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="form-group">
                        <label> <input type="checkbox" <?php if(setting_item('user_plans_enable') ?? '' == 1): ?> checked <?php endif; ?> name="user_plans_enable" disabled value="1"> <?php echo e(__("On")); ?></label>
                    </div>
                    <?php if(setting_item('user_plans_enable')!= 1): ?>
                        <p><?php echo e(__('You must enable on main lang.')); ?></p>
                    <?php endif; ?>
                <?php endif; ?>


                <div data-condition="user_plans_enable:is(1)">
                    <div class="form-group">
                        <label><?php echo e(__("Page Title")); ?></label>
                        <div class="form-controls">
                            <input type="text" name="user_plans_page_title" class="form-control" value="<?php echo e(setting_item_with_lang('user_plans_page_title',request()->query('lang')) ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo e(__("Page Sub Title")); ?></label>
                        <div class="form-controls">
                            <input type="text" name="user_plans_page_sub_title" class="form-control" value="<?php echo e(setting_item_with_lang('user_plans_page_sub_title',request()->query('lang')) ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo e(__("Sale Of Text")); ?></label>
                        <div class="form-controls">
                            <input type="text" name="user_plans_sale_text" class="form-control" value="<?php echo e(setting_item_with_lang('user_plans_sale_text',request()->query('lang')) ?? ''); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo e(__("Enable Multi User Plans")); ?></label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="user_plans_multiple_buy" value="1" <?php if(!empty($settings['enable_multi_user_plans'])): ?> checked <?php endif; ?> /> <?php echo e(__("On")); ?> </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title"><?php echo e(__("Plan Request options")); ?></h3>
        <div class="form-group-desc"><?php echo e(__('Content email send to Customer or Administrator.')); ?>

            <?php $__currentLoopData = \Modules\User\Emails\PlanPaymentEmail::CODE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><code><?php echo e($value); ?></code></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="panel" data-condition="user_plans_enable:is(1)">
            <div class="panel-title"><strong><?php echo e(__("New request plan")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#NewRequestPlanAdmin"><?php echo e(__("Administrator")); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#NewRequestPlanUser"><?php echo e(__("Customer")); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="NewRequestPlanAdmin">
                            <?php if(is_default_lang()): ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_new_payment_admin_enable')?? '' == 1): ?> checked <?php endif; ?> name="plan_new_payment_admin_enable" value="1"> <?php echo e(__("Enable send email to Administrator?")); ?></label>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_new_payment_admin_enable') ?? '' == 1): ?> checked <?php endif; ?> name="plan_new_payment_admin_enable" disabled value="1"> <?php echo e(__("Enable send email to Administrator?")); ?></label>
                                </div>
                                <?php if(setting_item('plan_new_payment_admin_enable')!= 1): ?>
                                    <p><?php echo e(__('You must enable on main lang.')); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div data-condition="plan_new_payment_admin_enable:is(1)">
                                <div class="form-group">
                                    <label><?php echo e(__("Subject")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_new_payment_admin_subject" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_new_payment_admin_subject',request()->query('lang')) ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(__("Message")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_new_payment_admin_content" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_new_payment_admin_content',request()->query('lang')) ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="NewRequestPlanUser">
                            <?php if(is_default_lang()): ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_new_payment_user_enable')?? '' == 1): ?> checked <?php endif; ?> name="plan_new_payment_user_enable" value="1"> <?php echo e(__("Enable send email to customer?")); ?></label>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_new_payment_user_enable') ?? '' == 1): ?> checked <?php endif; ?> name="plan_new_payment_user_enable" disabled value="1"> <?php echo e(__("Enable send email to customer?")); ?></label>
                                </div>
                                <?php if(setting_item('plan_new_payment_user_enable')!= 1): ?>
                                    <p><?php echo e(__('You must enable on main lang.')); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div data-condition="plan_new_payment_user_enable:is(1)">
                                <div class="form-group">
                                    <label><?php echo e(__("Subject")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_new_payment_user_subject" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_new_payment_user_subject',request()->query('lang')) ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(__("Message")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_new_payment_user_content" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_new_payment_user_content',request()->query('lang')) ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel" data-condition="user_plans_enable:is(1)">
            <div class="panel-title"><strong><?php echo e(__("Update request plan")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#UpdateRequestPlanAdmin"><?php echo e(__("Administrator")); ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " data-toggle="tab" href="#UpdateRequestPlanUser"><?php echo e(__("Customer")); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="UpdateRequestPlanAdmin">
                            <?php if(is_default_lang()): ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_update_payment_admin_enable')?? '' == 1): ?> checked <?php endif; ?> name="plan_update_payment_admin_enable" value="1"> <?php echo e(__("Enable send email to Administrator?")); ?></label>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_update_payment_admin_enable') ?? '' == 1): ?> checked <?php endif; ?> name="plan_update_payment_admin_enable" disabled value="1"> <?php echo e(__("Enable send email to Administrator?")); ?></label>
                                </div>
                                <?php if(setting_item('plan_update_payment_admin_enable')!= 1): ?>
                                    <p><?php echo e(__('You must enable on main lang.')); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div data-condition="plan_update_payment_admin_enable:is(1)">
                                <div class="form-group">
                                    <label><?php echo e(__("Subject")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_update_payment_admin_subject" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_update_payment_admin_subject',request()->query('lang')) ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(__("Message")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_update_payment_admin_content" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_update_payment_admin_content',request()->query('lang')) ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="UpdateRequestPlanUser">
                            <?php if(is_default_lang()): ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_update_payment_user_enable')?? '' == 1): ?> checked <?php endif; ?> name="plan_update_payment_user_enable" value="1"> <?php echo e(__("Enable send email to customer?")); ?></label>
                                </div>
                            <?php else: ?>
                                <div class="form-group">
                                    <label> <input type="checkbox" <?php if(setting_item('plan_update_payment_user_enable') ?? '' == 1): ?> checked <?php endif; ?> name="plan_update_payment_user_enable" disabled value="1"> <?php echo e(__("Enable send email to customer?")); ?></label>
                                </div>
                                <?php if(setting_item('plan_update_payment_user_enable')!= 1): ?>
                                    <p><?php echo e(__('You must enable on main lang.')); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div data-condition="plan_update_payment_user_enable:is(1)">
                                <div class="form-group">
                                    <label><?php echo e(__("Subject")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_update_payment_user_subject" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_update_payment_user_subject',request()->query('lang')) ?? ''); ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?php echo e(__("Message")); ?></label>
                                    <div class="form-controls">
                                        <textarea name="plan_update_payment_user_content" rows="8" class="form-control"><?php echo e(setting_item_with_lang('plan_update_payment_user_content',request()->query('lang')) ?? ''); ?></textarea>
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
<?php /**PATH F:\MAMP\htdocs\core\modules/User/Views/admin/settings/plan.blade.php ENDPATH**/ ?>