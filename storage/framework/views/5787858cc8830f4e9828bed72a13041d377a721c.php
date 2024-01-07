<div class="bravo-call-to-action <?php echo e($style); ?>">
    <?php switch($style):
        case ("style_2"): ?>
            <?php echo $__env->make("Tour::frontend.blocks.call-to-action.style-2", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php break; ?>
        <?php case ("style_3"): ?>
            <?php echo $__env->make("Tour::frontend.blocks.call-to-action.style-3", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php break; ?>
        <?php default: ?>
            <?php echo $__env->make("Tour::frontend.blocks.call-to-action.style-normal", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endswitch; ?>
</div>
<?php /**PATH F:\MAMP\htdocs\core\themes/BC/Tour/Views/frontend/blocks/call-to-action/index.blade.php ENDPATH**/ ?>