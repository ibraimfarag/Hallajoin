<div class="context" <?php if(!empty($bg_image_url)): ?> style="background-image: url(<?php echo e($bg_image_url ?? ""); ?>) !important;" <?php endif; ?>>
    <div class="container">
        <div class="title">
            <?php echo e($title); ?>

        </div>
        <div class="sub_title">
            <?php echo e($sub_title); ?>

        </div>
        <?php if($link_title): ?>
            <a class="btn-more" href="<?php echo e($link_more); ?>">
                <?php echo e($link_title); ?>

            </a>
        <?php endif; ?>
    </div>
</div><?php /**PATH F:\MAMP\htdocs\core\themes/BC/Tour/Views/frontend/blocks/call-to-action/style-3.blade.php ENDPATH**/ ?>