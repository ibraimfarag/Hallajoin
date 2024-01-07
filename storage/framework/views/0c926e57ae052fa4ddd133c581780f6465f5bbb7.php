<div class="mb-3">
    <label class="d-block" for="exampleInputEmail1"><?php echo e(__("Vendor")); ?></label>
    <?php
    $user = !empty(Request()->vendor_id) ? App\User::find(Request()->vendor_id) : false;
    \App\Helpers\AdminForm::select2('vendor_id', [
        'configs' => [
            'ajax'        => [
                'url'      => route('user.admin.getForSelect2',['user_type'=>'vendor']),
                'dataType' => 'json',
            ],
            'allowClear'  => true,
            'placeholder' => __('-- Vendor --')
        ]
    ], !empty($user->id) ? [
        $user->id,
        $user->name_or_email . ' (#' . $user->id . ')'
    ] : false)
    ?>
</div>
<div class="mb-3">
    <label class="d-block" for="exampleInputEmail1"><?php echo e(__("Location")); ?></label>
    <?php
    $location = !empty(Request()->location_id) ? \Modules\Location\Models\Location::find(Request()->location_id) : false;
    \App\Helpers\AdminForm::select2('location_id', [
        'configs' => [
            'ajax'        => [
                'url'      => route('location.admin.getForSelect2'),
                'dataType' => 'json',
            ],
            'allowClear'  => true,
            'placeholder' => __('-- All Location --')
        ]
    ], !empty($location->id) ? [
        $location->id,
        $location->name
    ] : false)
    ?>
</div>
<div class="mb-0">
    <label class="d-block" for="exampleInputEmail1"><?php echo e(__("Featured")); ?></label>
    <select name="is_featured" class="form-control">
        <option value=""><?php echo e(__('-- All --')); ?> </option>
        <option value="1" <?php if(Request()->is_featured == 1): ?> selected <?php endif; ?>><?php echo e(__("Only Featured")); ?></option>
    </select>
</div><?php /**PATH F:\MAMP\htdocs\core\modules/Core/Views/admin/global/advanced-filter.blade.php ENDPATH**/ ?>