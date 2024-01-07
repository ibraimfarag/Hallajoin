<div class="form-group">
    <label><?php echo e(__("Name")); ?> <span class="text-danger">*</span></label>
    <input type="text" required value="<?php echo e(old('title',$translation->title)); ?>" placeholder="<?php echo e(__("name")); ?>" name="title" class="form-control">
</div>
<div class="form-group">
    <label><?php echo e(__("Description")); ?> </label>
    <textarea name="content" cols="30" rows="5" class="form-control"><?php echo e(old('content',$translation->content)); ?></textarea>
</div>
<div class="form-group">
    <label><?php echo e(__("For Role")); ?> <span class="text-danger">*</span></label>
    <select name="role_id" class="form-control">
        <option value=""><?php echo e(__("-- Please Select --")); ?></option>
        <?php $__currentLoopData = \Modules\User\Models\Role::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option <?php if(old('role_id',$row->role_id) == $role->id): ?> selected <?php endif; ?> value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<div class="form-group">
    <label class="control-label"><?php echo e(__("Price")); ?> </label>
    <input type="number" step="any" placeholder="<?php echo e(__("Free")); ?>" value="<?php echo e(old('price',$row->price)); ?>" name="price" class="form-control">
</div>
<div class="form-group">
    <label class="control-label"><?php echo e(__("Annual Price")); ?></label>
    <input type="number" step="any" value="<?php echo e(old('annual_price',$row->annual_price)); ?>" name="annual_price" class="form-control">
</div>
<div class="form-group">
    <label class="control-label"><?php echo e(__("Duration")); ?> <span class="text-danger">*</span></label>
    <input type="number" min="1" value="<?php echo e(old('duration',max(1,$row->duration))); ?>" name="duration" class="form-control">
</div>
<div class="form-group">
    <label class="control-label"><?php echo e(__("Duration Type")); ?> <span class="text-danger">*</span></label>
    <select name="duration_type" class="form-control" required>
        <option <?php if(old('duration_type',$row->duration_type) == 'day'): ?> selected <?php endif; ?> value="day"><?php echo e(__("Day")); ?></option>
        <option <?php if(old('duration_type',$row->duration_type) == 'week'): ?> selected <?php endif; ?> value="week"><?php echo e(__("Week")); ?></option>
        <option <?php if(old('duration_type',$row->duration_type) == 'month'): ?> selected <?php endif; ?> value="month"><?php echo e(__("Month")); ?></option>
        <option <?php if(old('duration_type',$row->duration_type) == 'year'): ?> selected <?php endif; ?> value="year"><?php echo e(__("Year")); ?></option>
    </select>
</div>
<div class="form-group">
    <label class="control-label"><?php echo e(__("Max Services")); ?> </label>
    <input type="number" min="0" value="<?php echo e(old('max_service',$row->max_service)); ?>" name="max_service" placeholder="<?php echo e(__("Unlimited")); ?>" class="form-control">
    <p><i><?php echo e(__("How many publish services user can post")); ?></i></p>
</div>

<div class="form-group">
    <label class="control-label"><?php echo e(__("Status")); ?></label>
    <select name="status" class="form-control">
        <option value="publish"><?php echo e(__("Publish")); ?></option>
        <option <?php if(old('status',$row->status) == 'draft'): ?> selected <?php endif; ?> value="draft"><?php echo e(__("Draft")); ?></option>
    </select>
</div>
<?php /**PATH F:\MAMP\htdocs\core\modules/User/Views/admin/plan/form.blade.php ENDPATH**/ ?>