<?php if($list_item): ?>
    <div class="bravo-box-category-tour">
        <div class="container">
            <?php if($title): ?>
                <div class="title">
                    <?php echo e($title); ?>

                </div>
            <?php endif; ?>
            <?php if(!empty($desc)): ?>
                <div class="desc">
                    <?php echo e($desc); ?>

                </div>
            <?php endif; ?>
            <div class="list-item owl-carousel">
                <?php $__currentLoopData = $list_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $image_url = get_file_url($item['image_id'], 'full'); ?>
                        <?php if( !empty( $item_cat =  $categories->firstWhere('id',$item['category_id']) )): ?>
                            <?php
                                $translate = $item_cat->translate();
                                $page_search = $item_cat->getLinkForPageSearch(false , [ 'cat_id[]' =>  $item_cat->id] );
                            ?>
                            <div class="item">
                                <a href="<?php echo e($page_search); ?>">
                                    <img src="<?php echo e($image_url); ?>" alt="<?php echo e($translate->name); ?>">
                                    <span class="text-title"><?php echo e($translate->name); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH F:\MAMP\htdocs\core\themes/BC/Tour/Views/frontend/blocks/box-category-tour/index.blade.php ENDPATH**/ ?>