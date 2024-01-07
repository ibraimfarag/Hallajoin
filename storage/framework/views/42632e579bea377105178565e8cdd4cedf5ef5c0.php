<div class="bravo-list-tour <?php echo e($style_list); ?>">
    <?php if(in_array($style_list,['normal','carousel','box_shadow'])): ?>
        <?php echo $__env->make("Tour::frontend.blocks.list-tour.style-normal", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if($style_list == "carousel_simple"): ?>
        <?php echo $__env->make("Tour::frontend.blocks.list-tour.style-carousel-simple", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</div><?php /**PATH F:\MAMP\htdocs\core\themes/BC/Tour/Views/frontend/blocks/list-tour/index.blade.php ENDPATH**/ ?>