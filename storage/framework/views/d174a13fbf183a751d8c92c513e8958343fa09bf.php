

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar"><?php echo e(__('All Page')); ?></h1>
            <div class="title-actions">
                <a href="<?php echo e(route('page.admin.create')); ?>" class="btn btn-primary"><?php echo e(__('Add new page')); ?></a>
            </div>
        </div>
        <?php echo $__env->make('admin.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                <?php if(!empty($rows)): ?>
                <form method="post" action="<?php echo e(route('page.admin.bulkEdit')); ?>" class="filter-form filter-form-left d-flex justify-content-start">
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
            <div class="col-left">
               <form method="get" action="<?php echo e(route('page.admin.index')); ?> " class="filter-form filter-form-right d-flex justify-content-end" role="search">
                    <input  type="text" name="page_name" value="<?php echo e(Request()->page_name); ?>" placeholder="<?php echo e(__('Search by name')); ?>" class="form-control">
                    <button class="btn-info btn btn-icon btn_search"  type="submit"><?php echo e(__('Search Page')); ?></button>
                </form>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="60px"><input type="checkbox" class="check-all"></th>
                                <th ><?php echo e(__('Title')); ?></th>
                                <th width="130px"><?php echo e(__('Author')); ?> </th>
                                <th width="100px"><?php echo e(__('Date')); ?> </th>
                                <th width="100px"><?php echo e(__('Status')); ?> </th>
                                <th width="100px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($rows->total() > 0): ?>
                                <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" class="check-item" value="<?php echo e($row->id); ?>"></td>
                                        <td class="title">
                                            <a href="<?php echo e(route('page.admin.edit',['id'=>$row->id])); ?>"> <?php echo e($row->title); ?>  </a>
                                            <?php if(setting_item('home_page_id') == $row->id): ?>
                                                <div class="badge badge-info"><?php echo e(__("Homepage")); ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="author">
                                            <?php if(!empty($row->author)): ?>
                                                <?php echo e($row->author->getDisplayName()); ?>

                                            <?php else: ?>
                                                <?php echo e(__("[Author Deleted]")); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td class="date"><?php echo e(display_date($row->updated_at)); ?></td>
                                        <td> <span class="badge badge-<?php echo e($row->status); ?>"><?php echo e($row->status); ?></span> </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                                    <?php echo e(__("Actions")); ?>

                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a href="<?php echo e(route('page.admin.edit',['id'=>$row->id])); ?>" class="dropdown-item"><i class="fa fa-edit"></i> <?php echo e(__('Edit')); ?></a>
                                                    <a href="<?php echo e(route('page.admin.builder',['id'=>$row->id])); ?>" class="dropdown-item"><i class="fa fa-paint-brush"></i> <?php echo e(__('Template Builder')); ?></a>
                                                    <div class="dropdown-divider"></div>
                                                    <a href="<?php echo e(route('page.detail',['slug'=>$row->slug])); ?>" class="dropdown-item"><i class="fa fa-eye"></i> <?php echo e(__('View')); ?></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5"><?php echo e(__("No data")); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </form>
                <?php echo e($rows->appends(request()->query())->links()); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\modules/Page/Views/admin/index.blade.php ENDPATH**/ ?>