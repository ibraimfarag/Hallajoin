
<?php $__env->startSection('content'); ?>
    <div class="b-container">
        <div class="b-panel">
            <h1><?php echo e(__("Hello :name",['name'=>$user->first_name])); ?></h1>

            <p><?php echo e(__('You are receiving this email because we approved your vendor registration request.')); ?></p>
            <p><?php echo e(__('You can check your dashboard here:')); ?> <a href="<?php echo e(url('user/dashboard')); ?>"><?php echo e(__('View dashboard')); ?></a></p>

            <br>
            <p><?php echo e(__('Regards')); ?>,<br><?php echo e(setting_item('site_title')); ?></p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Email::layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\modules/User/Views/emails/vendor-approved.blade.php ENDPATH**/ ?>