

<?php $__env->startSection('template_title'); ?>
    <?php echo e(trans('installer_messages.final.templateTitle')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    <?php echo e(trans('installer_messages.final.title')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('container'); ?>

	<p><strong><small><?php echo e(trans('installer_messages.final.log')); ?></small></strong></p>
	<pre><code><?php echo e($finalStatusMessage); ?></code></pre>
    <div class="buttons">
        <a href="<?php echo e(url('/')); ?>" class="button"><?php echo e(trans('installer_messages.final.exit')); ?></a>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('vendor.installer.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\resources\views/vendor/installer/finished.blade.php ENDPATH**/ ?>