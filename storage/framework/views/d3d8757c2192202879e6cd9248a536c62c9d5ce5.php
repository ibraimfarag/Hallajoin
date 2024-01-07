<?php if(is_default_lang()): ?>
<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title"><?php echo e(__("General Options")); ?></h3>
    </div>
    <div class="col-md-8">
        <div class="panel">
            <div class="panel-title"><strong><?php echo e(__("General Options")); ?></strong></div>
            <div class="panel-body">
                <div class="form-group" >
                    <label class="" ><?php echo e(__("Allow customer upload picture to review")); ?></label>
                    <div class="form-controls">
                        <label><input type="checkbox" name="review_upload_picture" value="1"  <?php if(!empty(setting_item('review_upload_picture'))): ?> checked <?php endif; ?> /> <?php echo e(__("Yes please")); ?> </label>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php /**PATH F:\MAMP\htdocs\core\modules/Review/Views/admin/settings/review.blade.php ENDPATH**/ ?>