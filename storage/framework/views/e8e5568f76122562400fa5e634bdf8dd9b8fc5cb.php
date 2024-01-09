<div class="sec-title text-center">
    <h2><?php echo e(setting_item_with_lang('user_plans_page_title', app()->getLocale()) ?? __("Pricing Packages")); ?></h2>
    <div class="text"><?php echo e(setting_item_with_lang('user_plans_page_sub_title', app()->getLocale()) ?? __("Choose your pricing plan")); ?></div>
</div>
<div class="pricing-tabs tabs-box">
    <div class="tab-buttons">
        <h4><?php echo e(setting_item_with_lang('user_plans_sale_text', app()->getLocale()) ?? __('Save up to 10%')); ?></h4>
        <ul class="tab-btns">
            <li data-tab="#monthly" class="tab-btn active-btn"><?php echo e(__('Monthly')); ?></li>
            <li data-tab="#annual" class="tab-btn"><?php echo e(__('Annual')); ?></li>
        </ul>
    </div>
    <div class="tabs-content">
        <div class="tab active-tab" id="monthly">
            <div class="content">
                <div class="row">
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $translate = $plan->translate();
                        ?>
                        <div class="pricing-table col-lg-4 col-md-6 col-sm-12">
                            <div class="inner-box">
                                <?php if($plan->is_recommended): ?>
                                    <span class="tag"><?php echo e(__('Recommended')); ?></span>
                                <?php endif; ?>
                                <div class="title"><?php echo e($translate->title); ?></div>
                                <div class="price"><?php echo e($plan->price ? format_money($plan->price) : __('Free')); ?>

                                    <?php if($plan->price): ?>
                                    <span class="duration">/ <?php echo e($plan->duration > 1 ? $plan->duration : ''); ?> <?php echo e($plan->duration_type_text); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="table-content">
                                    <?php echo clean($translate->content); ?>

                                </div>
                                <div class="table-footer">
                                    <?php if($user and $user_plan = $user->user_plan and $user_plan->plan_id == $plan->id): ?>
                                        <?php if($user_plan->is_valid): ?>
                                            <div class="d-flex text-center">
                                                <a href="<?php echo e(route('user.plan')); ?>" class="theme-btn btn-style-one mr-2"><?php echo e(__("Current Plan")); ?></a>
                                                <?php if(setting_item_with_lang('enable_multi_user_plans')): ?>
                                                    <a href="<?php echo e(route('user.plan.buy',['id'=>$plan->id])); ?>" class="btn btn-warning"><?php echo e(__('Repurchase')); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('user.plan.buy',['id'=>$plan->id])); ?>" class="btn btn-warning"><?php echo e(__('Repurchase')); ?></a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('user.plan.buy',['id'=>$plan->id])); ?>" class="btn btn-primary"><?php echo e(__('Select')); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
        <div class="tab" id="annual">
            <div class="content">
                <div class="row">
                    <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(!$plan->annual_price) continue;?>
                        <div class="pricing-table col-lg-4 col-md-6 col-sm-12">
                            <div class="inner-box">
                                <?php if($plan->is_recommended): ?>
                                    <span class="tag"><?php echo e(__('Recommended')); ?></span>
                                <?php endif; ?>
                                <div class="title"><?php echo e($plan->title); ?></div>
                                <div class="price"><?php echo e(format_money($plan->annual_price)); ?> <span class="duration">/ <?php echo e(__("year")); ?></span></div>
                                <div class="table-content">
                                    <?php echo clean($plan->content); ?>

                                </div>
                                <div class="table-footer">
                                    <?php if($user and $user_plan = $user->user_plan and $user_plan->plan_id == $plan->id): ?>
                                        <?php if($user_plan->is_valid): ?>
                                            <div class="d-flex text-center">
                                                <a href="<?php echo e(route('user.plan')); ?>" class="theme-btn btn-style-one mr-2"><?php echo e(__("Current Plan")); ?></a>
                                                <?php if(setting_item_with_lang('enable_multi_user_plans')): ?>
                                                    <a href="<?php echo e(route('user.plan.buy',['id'=>$plan->id])); ?>" class="btn btn-warning"><?php echo e(__('Repurchase')); ?></a>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('user.plan.buy',['id'=>$plan->id,'annual'=>1])); ?>" class="btn btn-warning"><?php echo e(__('Repurchase')); ?></a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('user.plan.buy',['id'=>$plan->id,'annual'=>1])); ?>" class="btn btn-primary"><?php echo e(__('Select')); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\themes/Base/User/Views/frontend/plan/list.blade.php ENDPATH**/ ?>