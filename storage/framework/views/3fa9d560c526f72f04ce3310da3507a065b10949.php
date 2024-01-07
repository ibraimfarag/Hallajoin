
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar"><?php echo e(__("User Plans")); ?></h1>
        </div>
        <?php echo $__env->make('admin.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row">
            <div class="col-md-4 mb40">
                <div class="panel">
                    <div class="panel-title"><?php echo e(__("Add Plan")); ?></div>
                    <div class="panel-body">
                        <form action="<?php echo e(route('user.admin.plan.store',['id'=>($row->id) ? $row->id : '-1','lang'=>request()->query('lang')])); ?>" method="post">
                            <?php echo csrf_field(); ?>
                            <?php echo $__env->make('User::admin.plan.form',['parents'=>$rows], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            <div class="">
                                <button class="btn btn-primary" type="submit"><?php echo e(__("Add new")); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="filter-div d-flex justify-content-between ">
                    <div class="col-left">
                        <?php if(!empty($rows)): ?>
                            <form method="post" action="<?php echo e(route('user.admin.plan.bulkEdit')); ?>" class="filter-form filter-form-left d-flex justify-content-start">
                                <?php echo e(csrf_field()); ?>

                                <select name="action" class="form-control">
                                    <option value=""><?php echo e(__(" Bulk Action ")); ?></option>
                                    <option value="publish"><?php echo e(__(" Publish ")); ?></option>
                                    <option value="draft"><?php echo e(__(" Move to Draft ")); ?></option>
                                    <option value="delete"><?php echo e(__(" Delete ")); ?></option>
                                </select>
                                <button data-confirm="<?php echo e(__("Do you want to delete?")); ?>" class="btn-info btn btn-icon dungdt-apply-form-btn" type="button"><?php echo e(__('Apply')); ?></button>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="col-left">
                        <form method="get" action="" class="filter-form filter-form-right d-flex justify-content-end" role="search">
                            <input type="text" name="s" value="<?php echo e(Request()->s); ?>" class="form-control" placeholder="<?php echo e(__("Search by name")); ?>">
                            <button class="btn-info btn btn-icon btn_search" id="search-submit" type="submit"><?php echo e(__('Search')); ?></button>
                        </form>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body">
                        <form class="bravo-form-item">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="60px"><input type="checkbox" class="check-all"></th>
                                    <th width="60px"><?php echo e(__("ID")); ?></th>
                                    <th><?php echo e(__("Name")); ?></th>
                                    <th><?php echo e(__("For Role")); ?></th>
                                    <th width="60px"><?php echo e(__("Price")); ?></th>
                                    <th width="60px"><?php echo e(__("Annual Price")); ?></th>
                                    <th width="60px"><?php echo e(__("Duration")); ?></th>
                                    <th width="60px"><?php echo e(__("Max Services")); ?></th>
                                    <th width="60px"><?php echo e(__("Status")); ?></th>
                                    <th width="60px"><?php echo e(__("Date")); ?></th>
                                    <th width="100px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="<?php echo e($row->id); ?>" class="check-item">
                                        <td>#<?php echo e($row->id); ?></td>
                                        <td class="title">
                                            <a href="<?php echo e(route('user.admin.plan.edit',['id'=>$row->id])); ?>"><?php echo e($row->title); ?></a>
                                        </td>
                                        <td><?php echo e($row->role->name ?? ''); ?></td>
                                        <td class=""><?php echo e($row->price ? format_money($row->price) : __("Free")); ?></td>
                                        <td class=""><?php echo e($row->annual_price ? format_money($row->annual_price) : ''); ?></td>
                                        <td class=""><?php echo e($row->duration_text); ?></td>
                                        <td class=""><?php echo e($row->max_service ? $row->max_service : __('Unlimited')); ?></td>
                                        <td><span class="badge badge-<?php echo e($row->status); ?>"><?php echo e($row->status); ?></span></td>
                                        <td class=""><?php echo e(display_date($row->updated_at)); ?></td>
                                        <td class="title">
                                            <a href="<?php echo e(route('user.admin.plan.edit',['id'=>$row->id])); ?>" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> <?php echo e(__("Edit")); ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                            <?php echo e($rows->appends(request()->query())->links()); ?>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\modules/User/Views/admin/plan/index.blade.php ENDPATH**/ ?>