<?php if(is_default_lang()): ?>
    <div class="row">
        <div class="col-sm-4">
            <h3 class="form-group-title"><?php echo e(__('Settings Enquiry for Service')); ?></h3>
            <p class="form-group-desc"><?php echo e(__('Change your enquiry options')); ?></p>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__("Enable enquiry for Hotel")); ?></label>
                                <div class="form-controls">
                                    <label><input type="checkbox" name="booking_enquiry_for_hotel" value="1" <?php if(!empty($settings['booking_enquiry_for_hotel'])): ?> checked <?php endif; ?> /> <?php echo e(__("Enable enquiry form")); ?> </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><?php echo e(__("Enquiry Type")); ?></label>
                            <div class="form-controls">
                                <select name="booking_enquiry_type_hotel" class="form-control">
                                    <option <?php echo e(($settings['booking_enquiry_type_hotel'] ?? '') == 'booking_and_enquiry' ? 'selected' : ''); ?> value="booking_and_enquiry"><?php echo e(__("Booking & Enquiry")); ?></option>
                                    <option <?php echo e(($settings['booking_enquiry_type_hotel'] ?? '') == 'only_enquiry' ? 'selected' : ''); ?> value="only_enquiry"><?php echo e(__("Only Enquiry")); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__("Enable enquiry for Tour")); ?></label>
                                <div class="form-controls">
                                    <label><input type="checkbox" name="booking_enquiry_for_tour" value="1" <?php if(!empty($settings['booking_enquiry_for_tour'])): ?> checked <?php endif; ?> /> <?php echo e(__("Enable enquiry form")); ?> </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><?php echo e(__("Enquiry Type")); ?></label>
                            <div class="form-controls">
                                <select name="booking_enquiry_type_tour" class="form-control">
                                    <option <?php echo e(($settings['booking_enquiry_type_tour'] ?? '') == 'booking_and_enquiry' ? 'selected' : ''); ?> value="booking_and_enquiry"><?php echo e(__("Booking & Enquiry")); ?></option>
                                    <option <?php echo e(($settings['booking_enquiry_type_tour'] ?? '') == 'only_enquiry' ? 'selected' : ''); ?> value="only_enquiry"><?php echo e(__("Only Enquiry")); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__("Enable enquiry for Space")); ?></label>
                                <div class="form-controls">
                                    <label><input type="checkbox" name="booking_enquiry_for_space" value="1" <?php if(!empty($settings['booking_enquiry_for_space'])): ?> checked <?php endif; ?> /> <?php echo e(__("Enable enquiry form")); ?> </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><?php echo e(__("Enquiry Type")); ?></label>
                            <div class="form-controls">
                                <select name="booking_enquiry_type_space" class="form-control">
                                    <option <?php echo e(($settings['booking_enquiry_type_space'] ?? '') == 'booking_and_enquiry' ? 'selected' : ''); ?> value="booking_and_enquiry"><?php echo e(__("Booking & Enquiry")); ?></option>
                                    <option <?php echo e(($settings['booking_enquiry_type_space'] ?? '') == 'only_enquiry' ? 'selected' : ''); ?> value="only_enquiry"><?php echo e(__("Only Enquiry")); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__("Enable enquiry for Car")); ?></label>
                                <div class="form-controls">
                                    <label><input type="checkbox" name="booking_enquiry_for_car" value="1" <?php if(!empty($settings['booking_enquiry_for_car'])): ?> checked <?php endif; ?> /> <?php echo e(__("Enable enquiry form")); ?> </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><?php echo e(__("Enquiry Type")); ?></label>
                            <div class="form-controls">
                                <select name="booking_enquiry_type_car" class="form-control">
                                    <option <?php echo e(($settings['booking_enquiry_type_car'] ?? '') == 'booking_and_enquiry' ? 'selected' : ''); ?> value="booking_and_enquiry"><?php echo e(__("Booking & Enquiry")); ?></option>
                                    <option <?php echo e(($settings['booking_enquiry_type_car'] ?? '') == 'only_enquiry' ? 'selected' : ''); ?> value="only_enquiry"><?php echo e(__("Only Enquiry")); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__("Enable enquiry for Event")); ?></label>
                                <div class="form-controls">
                                    <label><input type="checkbox" name="booking_enquiry_for_event" value="1" <?php if(!empty($settings['booking_enquiry_for_event'])): ?> checked <?php endif; ?> /> <?php echo e(__("Enable enquiry form")); ?> </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><?php echo e(__("Enquiry Type")); ?></label>
                            <div class="form-controls">
                                <select name="booking_enquiry_type_event" class="form-control">
                                    <option <?php echo e(($settings['booking_enquiry_type_event'] ?? '') == 'booking_and_enquiry' ? 'selected' : ''); ?> value="booking_and_enquiry"><?php echo e(__("Booking & Enquiry")); ?></option>
                                    <option <?php echo e(($settings['booking_enquiry_type_event'] ?? '') == 'only_enquiry' ? 'selected' : ''); ?> value="only_enquiry"><?php echo e(__("Only Enquiry")); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__("Enable enquiry for Boat")); ?></label>
                                <div class="form-controls">
                                    <label><input type="checkbox" name="booking_enquiry_for_boat" value="1" <?php if(!empty($settings['booking_enquiry_for_boat'])): ?> checked <?php endif; ?> /> <?php echo e(__("Enable enquiry form")); ?> </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label><?php echo e(__("Enquiry Type")); ?></label>
                            <div class="form-controls">
                                <select name="booking_enquiry_type_boat" class="form-control">
                                    <option <?php echo e(($settings['booking_enquiry_type_boat'] ?? '') == 'booking_and_enquiry' ? 'selected' : ''); ?> value="booking_and_enquiry"><?php echo e(__("Booking & Enquiry")); ?></option>
                                    <option <?php echo e(($settings['booking_enquiry_type_boat'] ?? '') == 'only_enquiry' ? 'selected' : ''); ?> value="only_enquiry"><?php echo e(__("Only Enquiry")); ?></option>
                                </select>
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
            <h3 class="form-group-title"><?php echo e(__('Settings Enquiry')); ?></h3>
            <p class="form-group-desc"><?php echo e(__('Change your enquiry options')); ?></p>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label><?php echo e(__("Enable re-catpcha for enquiry?")); ?></label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="booking_enquiry_enable_recaptcha" value="1" <?php if(!empty($settings['booking_enquiry_enable_recaptcha'])): ?> checked <?php endif; ?> /> <?php echo e(__("Enable re-captcha at enquiry form")); ?> </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h3 class="form-group-title"><?php echo e(__('Settings Email Enquiry')); ?></h3>
            <p class="form-group-desc"><?php echo e(__('Change your email enquiry options')); ?></p>
            <?php $__currentLoopData = \Modules\Booking\Listeners\EnquirySendListen::CODE; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div><code><?php echo e($value); ?></code></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-body">

                    <?php if(is_default_lang()): ?>
                        <div class="form-group">
                            <label> <input type="checkbox" <?php if($settings['booking_enquiry_enable_mail_to_vendor'] ?? '' == 1): ?> checked <?php endif; ?> name="booking_enquiry_enable_mail_to_vendor" value="1"> <?php echo e(__("Enable send email to Vendor")); ?></label>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label> <input type="checkbox" <?php if($settings['booking_enquiry_enable_mail_to_vendor'] ?? '' == 1): ?> checked <?php endif; ?> disabled name="booking_enquiry_enable_mail_to_vendor" value="1"> <?php echo e(__("Enable send email to Vendor")); ?></label>
                        </div>
                        <?php if($settings['booking_enquiry_enable_mail_to_vendor'] != 1): ?>
                            <p><?php echo e(__('You must enable on main lang.')); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="form-group" data-condition="booking_enquiry_enable_mail_to_vendor:is(1)">
                        <label><?php echo e(__("Email to Vendor content")); ?></label>
                        <div class="form-controls">
                            <textarea name="booking_enquiry_mail_to_vendor_content" class="d-none has-ckeditor" cols="30" rows="10"><?php echo e(setting_item_with_lang('booking_enquiry_mail_to_vendor_content',request()->query('lang'))?? ''); ?></textarea>
                        </div>
                    </div>

                    <?php if(is_default_lang()): ?>
                        <div class="form-group">
                            <label> <input type="checkbox" <?php if($settings['booking_enquiry_enable_mail_to_admin'] ?? '' == 1): ?> checked <?php endif; ?> name="booking_enquiry_enable_mail_to_admin" value="1"> <?php echo e(__("Enable send email to Administrator")); ?></label>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label> <input type="checkbox" <?php if($settings['booking_enquiry_enable_mail_to_admin'] ?? '' == 1): ?> checked <?php endif; ?> disabled name="booking_enquiry_enable_mail_to_admin" value="1"> <?php echo e(__("Enable send email to Administrator")); ?></label>
                        </div>
                        <?php if($settings['booking_enquiry_enable_mail_to_admin'] != 1): ?>
                            <p><?php echo e(__('You must enable on main lang.')); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="form-group" data-condition="booking_enquiry_enable_mail_to_admin:is(1)">
                        <label><?php echo e(__("Email to Administrator content")); ?></label>
                        <div class="form-controls">
                            <textarea name="booking_enquiry_mail_to_admin_content" class="d-none has-ckeditor" cols="30" rows="10"><?php echo e(setting_item_with_lang('booking_enquiry_mail_to_admin_content',request()->query('lang'))?? ''); ?></textarea>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH F:\MAMP\htdocs\core\modules/Booking/Views/admin/settings/enquiry.blade.php ENDPATH**/ ?>