<?php if(!empty($breadcrumbs)): ?>
    <div class="blog-breadcrumb hidden-xs">
        <div class="container">
            <ol class="ul" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li class="" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="<?php echo e(url('/')); ?>" itemprop="item"><span itemprop="name"><?php echo e(__('Home')); ?></span></a>
                    <meta itemprop="position" content="1" /></li>
                <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$breadcrumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class=" <?php echo e($breadcrumb['class'] ?? ''); ?>" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <?php if(!empty($breadcrumb['url'])): ?>
                            <a href="<?php echo e(url($breadcrumb['url'])); ?>" itemscope itemtype="https://schema.org/WebPage" itemprop="item" itemid="<?php echo e(url($breadcrumb['url'])); ?>"><span itemprop="name"><?php echo e($breadcrumb['name']); ?></span></a>
                        <?php else: ?>
                            <span itemprop="name"><?php echo e($breadcrumb['name']); ?></span>
                        <?php endif; ?>
                        <meta itemprop="position" content="<?php echo e($k + 2); ?>" />
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH F:\MAMP\htdocs\core\modules/Layout/parts/bc.blade.php ENDPATH**/ ?>