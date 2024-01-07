<div class="form-group">
    <i class="field-icon icofont-wall-clock"></i>
    <div class="form-content">
        <div class="form-date-search is_single_picker">
            <div class="date-wrapper">
                <div class="check-in-wrapper">
                    <label><?php echo e($field['title']); ?></label>
                    <div class="render check-in-render"><?php echo e(Request::query('start',display_date(strtotime("today")))); ?></div>
                </div>
            </div>
            <input type="hidden" class="check-in-input" value="<?php echo e(Request::query('start',display_date(strtotime("today")))); ?>" name="start">
            <input type="text" class="check-in-out" name="date" value="<?php echo e(Request::query('date',date("Y-m-d"))); ?>">
        </div>
    </div>
</div><?php /**PATH F:\MAMP\htdocs\core\themes/BC/Boat/Views/frontend/layouts/search/fields/date.blade.php ENDPATH**/ ?>