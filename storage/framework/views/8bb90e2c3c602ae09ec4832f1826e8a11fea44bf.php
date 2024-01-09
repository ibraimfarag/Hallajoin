

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('user.admin.wallet.store',['id'=>$row->id])); ?>" method="post" >
        <?php echo csrf_field(); ?>
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="d-flex justify-content-between mb20">
                        <div class="">
                            <h1 class="title-bar"><?php echo e(__('Add credit for :name',['name'=>$row->display_name])); ?></h1>
                        </div>
                    </div>
                    <?php echo $__env->make('admin.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="panel">
                        <div class="panel-title"><strong><?php echo e(__('Add credit')); ?></strong></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo e(__("Balance")); ?></label>
                                        <input type="number" readonly value="<?php echo e($row->balance); ?>" step="0.1" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label><?php echo e(__("Credit Amount")); ?></label>
                                        <input type="number" name="credit_amount" value="0" step="0.1" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="submit"><?php echo e(__('Add now')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\modules/User/Views/admin/wallet/add-credit.blade.php ENDPATH**/ ?>