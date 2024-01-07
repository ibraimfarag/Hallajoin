<?php if($list_item): ?>
    <div class="bravo-client-feedback">
        <div class="row">
            <div class="col-md-6 d-flex align-content-center">
                <?php if(!empty($image_url)): ?>
                    <img class="i" src="<?php echo e($image_url); ?>" alt="xx">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <div class="list-item owl-carousel">
                    <?php $__currentLoopData = $list_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="item">
                            <i class="icofont-quote-right"></i>
                            <div class="title">
                                <?php echo e($item['title']); ?>

                            </div>
                            <div class="sub_title">
                                <?php echo e($item['sub_title']); ?>

                            </div>
                            <div class="desc">
                                <?php echo e($item['desc']); ?>

                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH F:\MAMP\htdocs\core\themes/Base/Template/Views/frontend/blocks/client-feedback/index.blade.php ENDPATH**/ ?>