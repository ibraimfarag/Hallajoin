<div class="panel">
    <div class="panel-title"><strong><?php echo e(__('Header Style')); ?></strong></div>
    <div class="panel-body">
        <select name="header_style" class="form-control" >
            <option value="normal" <?php echo e(( $row->header_style ?? '') == 'normal' ? 'selected' : ''); ?>><?php echo e(__("Normal")); ?></option>
            <option value="transparent" <?php echo e(( $row->header_style ?? '') == 'transparent' ? 'selected' : ''); ?>><?php echo e(__('Transparent')); ?></option>
        </select>
    </div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\modules/Page/Views/admin/advanced.blade.php ENDPATH**/ ?>