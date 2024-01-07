<?php  $languages = \Modules\Language\Models\Language::getActive();  ?>
<?php if(is_default_lang()): ?>
<div class="panel">
    <div class="panel-title"><strong><?php echo e(__("Pricing")); ?></strong></div>
    <div class="panel-body">
        <?php if(is_default_lang()): ?>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label"><?php echo e(__("Price per hour")); ?></label>
                        <input type="number" step="any" min="0" name="price_per_hour" class="form-control" value="<?php echo e($row->price_per_hour); ?>" placeholder="<?php echo e(__("Price per hour")); ?>">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label"><?php echo e(__("Price per day")); ?></label>
                        <input type="number" step="any" name="price_per_day" class="form-control" value="<?php echo e($row->price_per_day); ?>" placeholder="<?php echo e(__("Price per day")); ?>">
                    </div>
                </div>

            </div>
        <?php endif; ?>
        <?php if(is_default_lang()): ?>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="control-label"><?php echo e(__("Minimum advance reservations")); ?></label>
                        <input type="number" name="min_day_before_booking" class="form-control" value="<?php echo e($row->min_day_before_booking); ?>" placeholder="<?php echo e(__("Ex: 3")); ?>">
                        <i><?php echo e(__("Leave blank if you dont need to use the min day option")); ?></i>
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php
                        $startTime = strtotime( '00:00' );
                        $endTime = strtotime( '23:30' );
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label"><?php echo e(__("Start time booking")); ?></label>
                            <select name="start_time_booking" class="form-control">
                                <option value=""><?php echo e(__("-- Select --")); ?></option>
                                <?php for( $i = $startTime ; $i <= $endTime ; $i = $i + 1800): ?>
                                    <option <?php if($row->start_time_booking == date( 'H:i', $i )): ?> selected <?php endif; ?> value="<?php echo e(date( 'H:i', $i )); ?>"><?php echo e(date( 'H:i', $i )); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label"><?php echo e(__("End time booking")); ?></label>
                            <select name="end_time_booking" class="form-control">
                                <option value=""><?php echo e(__("-- Select --")); ?></option>
                                <?php for( $i = $startTime ; $i <= $endTime ; $i = $i + 1800): ?>
                                    <option <?php if($row->end_time_booking == date( 'H:i', $i )): ?> selected <?php endif; ?> value="<?php echo e(date( 'H:i', $i )); ?>"><?php echo e(date( 'H:i', $i )); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <i><?php echo e(__("*Leave it blank if don't use these fields. The end-time must be larger than start-time")); ?></i>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group <?php if(!is_default_lang()): ?> d-none <?php endif; ?>">
            <label><input type="checkbox" name="enable_extra_price" <?php if(!empty($row->enable_extra_price)): ?> checked <?php endif; ?> value="1"> <?php echo e(__('Enable extra price')); ?>

            </label>
        </div>
        <div class="form-group-item <?php if(!is_default_lang()): ?> d-none <?php endif; ?>" data-condition="enable_extra_price:is(1)">
            <label class="control-label"><?php echo e(__('Extra Price')); ?></label>
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-5"><?php echo e(__("Name")); ?></div>
                    <div class="col-md-3"><?php echo e(__('Price')); ?></div>
                    <div class="col-md-3"><?php echo e(__('Type')); ?></div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                <?php if(!empty($row->extra_price)): ?>
                    <?php $__currentLoopData = $row->extra_price; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$extra_price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item" data-number="<?php echo e($key); ?>">
                            <div class="row">
                                <div class="col-md-5">
                                    <?php if(!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale')): ?>
                                        <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $key_lang = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>
                                            <div class="g-lang">
                                                <div class="title-lang"><?php echo e($language->name); ?></div>
                                                <input type="text" name="extra_price[<?php echo e($key); ?>][name<?php echo e($key_lang); ?>]" class="form-control" value="<?php echo e($extra_price['name'.$key_lang] ?? ''); ?>" placeholder="<?php echo e(__('Extra price name')); ?>">
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <input type="text" name="extra_price[<?php echo e($key); ?>][name]" class="form-control" value="<?php echo e($extra_price['name'] ?? ''); ?>" placeholder="<?php echo e(__('Extra price name')); ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" <?php if(!is_default_lang()): ?> disabled <?php endif; ?> min="0" name="extra_price[<?php echo e($key); ?>][price]" class="form-control" value="<?php echo e($extra_price['price']); ?>">
                                </div>
                                <div class="col-md-3">
                                    <select name="extra_price[<?php echo e($key); ?>][type]" class="form-control" <?php if(!is_default_lang()): ?> disabled <?php endif; ?>>
                                        <option <?php if($extra_price['type'] ==  'one_time'): ?> selected <?php endif; ?> value="one_time"><?php echo e(__("One-time")); ?></option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <?php if(is_default_lang()): ?>
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <?php if(is_default_lang()): ?>
                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> <?php echo e(__('Add item')); ?></span>
                <?php endif; ?>
            </div>
            <div class="g-more hide">
                <div class="item" data-number="__number__">
                    <div class="row">
                        <div class="col-md-5">
                            <?php if(!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale')): ?>
                                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $key = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>
                                    <div class="g-lang">
                                        <div class="title-lang"><?php echo e($language->name); ?></div>
                                        <input type="text" __name__="extra_price[__number__][name<?php echo e($key); ?>]" class="form-control" value="" placeholder="<?php echo e(__('Extra price name')); ?>">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <input type="text" __name__="extra_price[__number__][name]" class="form-control" value="" placeholder="<?php echo e(__('Extra price name')); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-3">
                            <input type="number" min="0" __name__="extra_price[__number__][price]" class="form-control" value="">
                        </div>
                        <div class="col-md-3">
                            <select __name__="extra_price[__number__][type]" class="form-control">
                                <option value="one_time"><?php echo e(__("One-time")); ?></option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(is_default_lang() and (!empty(setting_item("boat_allow_vendor_can_add_service_fee")) or is_admin())): ?>
            <hr>
            <h3 class="panel-body-title app_get_locale"><?php echo e(__('Service fee')); ?></h3>
            <div class="form-group app_get_locale">
                <label><input type="checkbox" name="enable_service_fee" <?php if(!empty($row->enable_service_fee)): ?> checked <?php endif; ?> value="1"> <?php echo e(__('Enable service fee')); ?>

                </label>
            </div>
            <div class="form-group-item" data-condition="enable_service_fee:is(1)">
                <label class="control-label"><?php echo e(__('Buyer Fees')); ?></label>
                <div class="g-items-header">
                    <div class="row">
                        <div class="col-md-7"><?php echo e(__("Name")); ?></div>
                        <div class="col-md-4"><?php echo e(__('Price')); ?></div>
                        <div class="col-md-3 d-none"><?php echo e(__('Type')); ?></div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="g-items">
                    <?php  $languages = \Modules\Language\Models\Language::getActive();?>
                    <?php if(!empty($service_fee = $row->service_fee)): ?>
                        <?php $__currentLoopData = $service_fee; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="item" data-number="<?php echo e($key); ?>">
                                <div class="row">
                                    <div class="col-md-7">
                                        <?php if(!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale')): ?>
                                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $key_lang = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>
                                                <div class="g-lang">
                                                    <div class="title-lang"><?php echo e($language->name); ?></div>
                                                    <input type="text" name="service_fee[<?php echo e($key); ?>][name<?php echo e($key_lang); ?>]" class="form-control" value="<?php echo e($item['name'.$key_lang] ?? ''); ?>" placeholder="<?php echo e(__('Fee name')); ?>">
                                                    <input type="text" name="service_fee[<?php echo e($key); ?>][desc<?php echo e($key_lang); ?>]" class="form-control" value="<?php echo e($item['desc'.$key_lang] ?? ''); ?>" placeholder="<?php echo e(__('Fee desc')); ?>">
                                                </div>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>
                                            <input type="text" name="service_fee[<?php echo e($key); ?>][name]" class="form-control" value="<?php echo e($item['name'] ?? ''); ?>" placeholder="<?php echo e(__('Fee name')); ?>">
                                            <input type="text" name="service_fee[<?php echo e($key); ?>][desc]" class="form-control" value="<?php echo e($item['desc'] ?? ''); ?>" placeholder="<?php echo e(__('Fee desc')); ?>">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" min="0"  step="0.1"  name="service_fee[<?php echo e($key); ?>][price]" class="form-control" value="<?php echo e($item['price'] ?? ""); ?>">
                                        <select name="service_fee[<?php echo e($key); ?>][unit]" class="form-control">
                                            <option <?php if(($item['unit'] ?? "") ==  'fixed'): ?> selected <?php endif; ?> value="fixed"><?php echo e(__("Fixed")); ?></option>
                                            <option <?php if(($item['unit'] ?? "") ==  'percent'): ?> selected <?php endif; ?> value="percent"><?php echo e(__("Percent")); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-none">
                                        <select name="service_fee[<?php echo e($key); ?>][type]" class="form-control d-none">
                                            <option <?php if($item['type'] ?? "" ==  'one_time'): ?> selected <?php endif; ?> value="one_time"><?php echo e(__("One-time")); ?></option>
                                        </select>
                                        <label>
                                            <input type="checkbox" min="0" name="service_fee[<?php echo e($key); ?>][per_person]" value="on" <?php if($item['per_person'] ?? ''): ?> checked <?php endif; ?> >
                                            <?php echo e(__("Price per person")); ?>

                                        </label>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
                <div class="text-right">
                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> <?php echo e(__('Add item')); ?></span>
                </div>
                <div class="g-more hide">
                    <div class="item" data-number="__number__">
                        <div class="row">
                            <div class="col-md-7">
                                <?php if(!empty($languages) && setting_item('site_enable_multi_lang') && setting_item('site_locale')): ?>
                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $key = setting_item('site_locale') != $language->locale ? "_".$language->locale : ""   ?>
                                        <div class="g-lang">
                                            <div class="title-lang"><?php echo e($language->name); ?></div>
                                            <input type="text" __name__="service_fee[__number__][name<?php echo e($key); ?>]" class="form-control" value="" placeholder="<?php echo e(__('Fee name')); ?>">
                                            <input type="text" __name__="service_fee[__number__][desc<?php echo e($key); ?>]" class="form-control" value="" placeholder="<?php echo e(__('Fee desc')); ?>">
                                        </div>

                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <input type="text" __name__="service_fee[__number__][name]" class="form-control" value="" placeholder="<?php echo e(__('Fee name')); ?>">
                                    <input type="text" __name__="service_fee[__number__][desc]" class="form-control" value="" placeholder="<?php echo e(__('Fee desc')); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4">
                                <input type="number" min="0" step="0.1"  __name__="service_fee[__number__][price]" class="form-control" value="">
                                <select __name__="service_fee[__number__][unit]" class="form-control">
                                    <option value="fixed"><?php echo e(__("Fixed")); ?></option>
                                    <option value="percent"><?php echo e(__("Percent")); ?></option>
                                </select>
                            </div>
                            <div class="col-md-3 d-none">
                                <select __name__="service_fee[__number__][type]" class="form-control">
                                    <option value="one_time"><?php echo e(__("One-time")); ?></option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php /**PATH F:\MAMP\htdocs\core\modules/Boat/Views/admin/boat/pricing.blade.php ENDPATH**/ ?>