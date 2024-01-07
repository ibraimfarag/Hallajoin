<div class="panel">
    <div class="panel-title"><strong><?php echo e(__("Boat Content")); ?></strong></div>
    <div class="panel-body">
        <div class="form-group">
            <label><?php echo e(__("Title")); ?></label>
            <input type="text" value="<?php echo clean($translation->title); ?>" placeholder="<?php echo e(__("Name of the boat")); ?>" name="title" class="form-control">
        </div>
        <div class="form-group">
            <label class="control-label"><?php echo e(__("Content")); ?></label>
            <div class="">
                <textarea name="content" class="d-none has-ckeditor" cols="30" rows="10"><?php echo e($translation->content); ?></textarea>
            </div>
        </div>
        <?php if(is_default_lang()): ?>
            <div class="form-group">
                <label class="control-label"><?php echo e(__("Youtube Video")); ?></label>
                <input type="text" name="video" class="form-control" value="<?php echo e($row->video); ?>" placeholder="<?php echo e(__("Youtube link video")); ?>">
            </div>
        <?php endif; ?>
        <div class="form-group-item">
            <label class="control-label"><?php echo e(__('FAQs')); ?></label>
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-5"><?php echo e(__("Title")); ?></div>
                    <div class="col-md-5"><?php echo e(__('Content')); ?></div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                <?php if(!empty($translation->faqs)): ?>
                    <?php if(!is_array($translation->faqs)) $translation->faqs = json_decode($translation->faqs); ?>
                    <?php $__currentLoopData = $translation->faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item" data-number="<?php echo e($key); ?>">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="faqs[<?php echo e($key); ?>][title]" class="form-control" value="<?php echo e($faq['title']); ?>" placeholder="<?php echo e(__('Eg: When and where does the tour end?')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <textarea name="faqs[<?php echo e($key); ?>][content]" class="form-control" placeholder="..."><?php echo e($faq['content']); ?></textarea>
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
                        <div class="col-md-5">
                            <input type="text" __name__="faqs[__number__][title]" class="form-control" placeholder="<?php echo e(__('Eg: Can I bring my pet?')); ?>">
                        </div>
                        <div class="col-md-6">
                            <textarea __name__="faqs[__number__][content]" class="form-control" placeholder=""></textarea>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(is_default_lang()): ?>
            <div class="form-group">
                <label class="control-label"><?php echo e(__("Banner Image")); ?></label>
                <div class="form-group-image">
                    <?php echo \Modules\Media\Helpers\FileHelper::fieldUpload('banner_image_id',$row->banner_image_id); ?>

                </div>
            </div>
            <div class="form-group">
                <label class="control-label"><?php echo e(__("Gallery")); ?></label>
                <?php echo \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery',$row->gallery); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<div class="panel">
    <div class="panel-title"><strong><?php echo e(__("Extra Info")); ?></strong></div>
    <div class="panel-body">
        <?php if(is_default_lang()): ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo e(__("Guest")); ?></label>
                        <input type="number" value="<?php echo e($row->max_guest); ?>" placeholder="<?php echo e(__("Example: 3")); ?>" name="max_guest" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo e(__("Cabin")); ?></label>
                        <input type="text" value="<?php echo e($row->cabin); ?>" placeholder="<?php echo e(__("Example: 5")); ?>" name="cabin" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo e(__("Length")); ?></label>
                        <input type="number" value="<?php echo e($row->length); ?>" placeholder="<?php echo e(__("Example: 30m")); ?>" name="length" class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><?php echo e(__("Speed")); ?></label>
                        <input type="number" value="<?php echo e($row->speed); ?>" placeholder="<?php echo e(__("Example: 25km/h")); ?>" name="speed" class="form-control">
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group-item">
            <label class="control-label"><?php echo e(__('Specs')); ?></label>
            <div class="g-items-header">
                <div class="row">
                    <div class="col-md-5"><?php echo e(__("Title")); ?></div>
                    <div class="col-md-5"><?php echo e(__('Content')); ?></div>
                    <div class="col-md-1"></div>
                </div>
            </div>
            <div class="g-items">
                <?php if(!empty($translation->specs)): ?>
                    <?php if(!is_array($translation->specs)) $translation->faqs = json_decode($translation->specs); ?>
                    <?php $__currentLoopData = $translation->specs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item" data-number="<?php echo e($key); ?>">
                            <div class="row">
                                <div class="col-md-5">
                                    <input type="text" name="specs[<?php echo e($key); ?>][title]" class="form-control" value="<?php echo e($spec['title']); ?>" placeholder="<?php echo e(__('Eg: Range')); ?>">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="specs[<?php echo e($key); ?>][content]" class="form-control" value="<?php echo e($spec['content']); ?>" placeholder="<?php echo e(__('Eg: 6000km')); ?>">
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
                        <div class="col-md-5">
                            <input type="text" __name__="specs[__number__][title]" class="form-control" placeholder="<?php echo e(__('Eg: Range')); ?>">
                        </div>
                        <div class="col-md-6">
                            <input type="text" __name__="specs[__number__][content]" class="form-control" value="" placeholder="<?php echo e(__('Eg: 6000km')); ?>">
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label><?php echo e(__("Cancellation Policy")); ?></label>
            <textarea name="cancel_policy" class="form-control" rows="5" placeholder="<?php echo e(__("Full refund up to 4 days prior.")); ?>"><?php echo e($translation->cancel_policy); ?></textarea>
        </div>
        <div class="form-group">
            <label><?php echo e(__("Additional Terms & Information")); ?></label>
            <textarea name="terms_information" class="d-none has-ckeditor" rows="10" placeholder="<?php echo e(__("For Sanitary purposes ONLY, although there is a working toilet and shower, we've deactivated the shower and the toliet is for limited use (urine only..pardon the graphic detail!)...")); ?>"><?php echo e($translation->terms_information); ?></textarea>
        </div>
        <?php echo $__env->make('Boat::admin/boat/include-exclude', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\modules/Boat/Views/admin/boat/content.blade.php ENDPATH**/ ?>