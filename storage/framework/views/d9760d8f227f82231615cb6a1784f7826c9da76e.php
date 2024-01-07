<?php if(!empty($style) and $style == "carousel" and !empty($list_slider)): ?>
    <div class="effect">
        <div class="owl-carousel">
            <?php $__currentLoopData = $list_slider; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $img = get_file_url($item['bg_image'],'full') ?>
                <div class="item">
                    <div class="item-bg" style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.2)),url('<?php echo e($img); ?>') !important"></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php endif; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="text-heading text-center"><?php echo e($title); ?></h1>
            <h2 class="sub-heading text-center"><?php echo e($sub_title); ?></h2>
            <div class="g-form-control">
                <?php echo $__env->make('Tour::frontend.layouts.search.form-search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div><?php /**PATH F:\MAMP\htdocs\core\themes/BC/Tour/Views/frontend/blocks/form-search-tour/style-normal.blade.php ENDPATH**/ ?>