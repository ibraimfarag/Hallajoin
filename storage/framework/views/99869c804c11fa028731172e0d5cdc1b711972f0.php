<?php
$popup = \Modules\Popup\Models\Popup::getActive(request());
if(!$popup) return;
$translation = $popup->translate(request('lang',app()->getLocale()));
?>
<div class="modal bc_popup" tabindex="-1" id="bc_popup_<?php echo e($popup->id); ?>" data-days="<?php echo e($popup->expired_days); ?>">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="modal-body">
                <?php if(!empty($translation->title)): ?>
                    <h5 class="modal-title mb-2"><?php echo e($translation->title); ?></h5>
                <?php endif; ?>
                <?php echo clean($translation->content); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\themes/Base/Popup/Views/frontend/popup.blade.php ENDPATH**/ ?>