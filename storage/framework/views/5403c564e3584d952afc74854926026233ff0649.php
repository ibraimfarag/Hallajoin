
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar"><?php echo e(__("Coupon Management")); ?></h1>
            <div class="title-actions">
                <?php if(empty($recovery)): ?>
                    <a href="<?php echo e(route("coupon.admin.create")); ?>" class="btn btn-primary"><?php echo e(__("Add new coupon")); ?></a>
                <?php endif; ?>
            </div>
        </div>

        <?php echo $__env->make('admin.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row">
            <div class="col-md-12">
                <div class="filter-div d-flex justify-content-between ">
                    <div class="col-left">
                        <?php if(!empty($rows)): ?>
                            <form method="post" action="<?php echo e(route('coupon.admin.bulkEdit')); ?>" class="filter-form filter-form-left d-flex justify-content-start">
                                <?php echo e(csrf_field()); ?>

                                <select name="action" class="form-control">
                                    <option value=""><?php echo e(__(" Bulk Actions ")); ?></option>
                                    <option value="publish"><?php echo e(__(" Publish ")); ?></option>
                                    <option value="draft"><?php echo e(__(" Move to Draft ")); ?></option>
                                    <option value="delete"><?php echo e(__(" Delete ")); ?></option>
                                </select>
                                <button data-confirm="<?php echo e(__("Do you want to delete?")); ?>" class="btn-info btn btn-icon dungdt-apply-form-btn" type="button"><?php echo e(__('Apply')); ?></button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="col-right">
                        <form method="get" action="" class="filter-form filter-form-left d-flex justify-content-start">
                           
                        </form>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body">
                        <form action="" class="bravo-form-item">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th width="45px"><input type="checkbox" class="check-all"></th>
                                        <th> <?php echo e(__('Code')); ?></th>
                                        <th> <?php echo e(__('Name')); ?></th>
                                        <th> <?php echo e(__('Amount')); ?></th>
                                        <th> <?php echo e(__('Discount Type')); ?></th>
                                        <th> <?php echo e(__('End Date')); ?></th>
                                        <th width="100px"> <?php echo e(__('Status')); ?></th>
                                        <th width="100px"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if($rows->total() > 0): ?>
                                        <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="<?php echo e($row->status); ?>">
                                                <td><input type="checkbox" name="ids[]" class="check-item" value="<?php echo e($row->id); ?>">
                                                </td>
                                                <td class="title">
                                                    <strong><?php echo e($row->code); ?></strong>
                                                </td>
                                                <td><?php echo e($row->name); ?></td>
                                                <td><?php echo e($row->amount); ?></td>
                                                <td><?php echo e($row->discount_type == 'percent' ? __("Percent") : __("Amount")); ?></td>
                                                <td><?php echo e(($row->end_date)); ?></td>
                                                <td><span class="badge badge-<?php echo e($row->status); ?>"><?php echo e($row->status); ?></span></td>
                                                <td>
                                                    <a href="<?php echo e(route('coupon.admin.edit',['id'=>$row->id])); ?>" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> <?php echo e(__('Edit')); ?>

                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7"><?php echo e(__("No coupon found")); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <div class="d-flex justify-content-between">
                            <?php echo e($rows->appends(request()->query())->links()); ?>

                            <p><i><?php echo e(__('Found :total items',['total'=>$rows->total()])); ?></i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\modules/Coupon/Views/admin/index.blade.php ENDPATH**/ ?>