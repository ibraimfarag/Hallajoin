
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar"><?php echo e(__("All Plugins")); ?></h1>
        </div>
        <?php echo $__env->make('admin.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                <?php if(!empty($rows)): ?>
                    <form method="post" action="<?php echo e(route('core.admin.plugins.bulkEdit')); ?>" class="filter-form filter-form-left d-flex justify-content-start">
                        <?php echo e(csrf_field()); ?>

                        <select name="action" class="form-control">
                            <option value=""><?php echo e(__(" Bulk Actions ")); ?></option>
                            
                        </select>
                        <button class="btn-info btn btn-icon dungdt-apply-form-btn" type="button"><?php echo e(__('Apply')); ?></button>
                    </form>
                <?php endif; ?>
            </div>
            
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th width="60px"><input type="checkbox" class="check-all"></th>
                            <th width="200px"> <?php echo e(__('Plugin name')); ?></th>
                            <th > <?php echo e(__('Description')); ?></th>
                            <th width="130px"> <?php echo e(__('Author')); ?></th>
                            <th width="100px"> <?php echo e(__('Version')); ?></th>
                            
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($rows)): ?>
                            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($row['module_name']); ?>">
                                    <td><input type="checkbox" name="ids[]" class="check-item" value="<?php echo e($row['module_name']); ?>">
                                    </td>
                                    <td class="title">
                                        <a href="#"><?php echo e($row['title']); ?></a>
                                    </td>
                                    <td>
                                        <?php echo e($row['desc']); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row['author']); ?>

                                    </td>
                                    <td>
                                        <?php echo e($row['version']); ?>

                                    </td>
                                    
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7"><?php echo e(__("No Plugin found")); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\modules/Core/Views/admin/plugins/index.blade.php ENDPATH**/ ?>