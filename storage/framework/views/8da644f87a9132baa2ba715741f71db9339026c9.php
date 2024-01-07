
<?php $__env->startSection("content"); ?>
    <div class="container">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar"><?php echo e(__('All Themes')); ?></h1>
            <div class="title-actions">
            </div>
        </div>
        <?php echo $__env->make('admin.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row">
            <?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme_id=>$themeClass): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <img class="card-img-top" src="<?php echo e(asset("themes/".$theme_id)); ?><?php echo e($themeClass::$screenshot); ?>" alt="">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title"><?php echo e($themeClass::$name); ?></h5>
                                <span class="badge badge-secondary"><?php echo e($themeClass::$version); ?></span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if(\Modules\Theme\ThemeManager::current() == $theme_id): ?>
                                        <form onsubmit="return confirm('<?php echo e(__("Do you want to import all demo data?")); ?>')" action="<?php echo e(route('theme.admin.seeding',['theme'=>$theme_id])); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn-warning"><i class="fa fa-magic"></i> <?php echo e(__("Import Demo Data")); ?></button>
                                            <?php if($time = $themeClass::lastSeederRun()): ?>
                                                <div>
                                                    <i><?php echo e(__('Last run: :date',['date'=>display_datetime($time)])); ?></i>
                                                </div>
                                            <?php endif; ?>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?php echo e(route('theme.admin.activate',['theme'=>$theme_id])); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn btn-primary"><?php echo e(__("Activate")); ?></button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layout::admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\MAMP\htdocs\core\modules/Theme/Views/admin/index.blade.php ENDPATH**/ ?>