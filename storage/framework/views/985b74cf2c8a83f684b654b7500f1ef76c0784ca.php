<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($page_title ?? 'Dashboard'); ?> - <?php echo e(setting_item('site_title') ?? 'HallaJoin'); ?></title>

    <?php
        $favicon = setting_item('site_favicon');
    ?>
    <?php if($favicon): ?>
        <?php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        ?>
        <?php if(!empty($file)): ?>
            <link rel="icon" type="<?php echo e($file['file_type']); ?>" href="<?php echo e(asset('uploads/'.$file['file_path'])); ?>" />
        <?php else: ?>:
        <link rel="icon" type="image/png" href="<?php echo e(url('images/favicon.png')); ?>" />
        <?php endif; ?>
    <?php endif; ?>

    <meta name="robots" content="noindex, nofollow" />
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="<?php echo e(asset('libs/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('libs/flags/css/flag-icon.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(url('libs/daterange/daterangepicker.css')); ?>"/>
    <link href="<?php echo e(asset('themes/admin/libs/bootstrap-4.6.2-dist/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('themes/admin/libs/font-awesome/css/font-awesome.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('dist/admin/css/app.css')); ?>" rel="stylesheet">
    <?php echo \App\Helpers\Assets::css(); ?>

    <?php echo \App\Helpers\Assets::js(); ?>

    <script>
        var bookingCore  = {
            url:'<?php echo e(url('/')); ?>',
            admin_url:'<?php echo e(route('admin.index')); ?>',
            map_provider:'<?php echo e(setting_item('map_provider')); ?>',
            map_gmap_key:'<?php echo e(setting_item('map_gmap_key')); ?>',
            csrf:'<?php echo e(csrf_token()); ?>',
            date_format:'<?php echo e(get_moment_date_format()); ?>',
            markAsRead:'<?php echo e(route('core.admin.notification.markAsRead')); ?>',
            markAllAsRead:'<?php echo e(route('core.admin.notification.markAllAsRead')); ?>',
            loadNotify : '<?php echo e(route('core.admin.notification.loadNotify')); ?>',
            pusher_api_key : '<?php echo e(setting_item("pusher_api_key")); ?>',
            pusher_cluster : '<?php echo e(setting_item("pusher_cluster")); ?>',
            isAdmin : <?php echo e(is_admin() ? 1 : 0); ?>,
            currentUser: <?php echo e((int)Auth::id()); ?>,
            media:{
                groups:<?php echo json_encode(config('bc.media.groups')); ?>,
            },
            language: '<?php echo e(app()->getLocale()); ?>',
        };
        var i18n = {
            warning:"<?php echo e(__("Warning")); ?>",
            success:"<?php echo e(__("Success")); ?>",
            confirm_delete:"<?php echo e(__("Do you want to delete?")); ?>",
            confirm_recovery:"<?php echo e(__("Do you want to restore?")); ?>",
            confirm:"<?php echo e(__("Confirm")); ?>",
            cancel:"<?php echo e(__("Cancel")); ?>",
        };
        var daterangepickerLocale = {
            "applyLabel": "<?php echo e(__('Apply')); ?>",
            "cancelLabel": "<?php echo e(__('Cancel')); ?>",
            "fromLabel": "<?php echo e(__('From')); ?>",
            "toLabel": "<?php echo e(__('To')); ?>",
            "customRangeLabel": "<?php echo e(__('Custom')); ?>",
            "weekLabel": "<?php echo e(__('W')); ?>",
            "first_day_of_week": <?php echo e(setting_item("site_first_day_of_the_weekin_calendar","1")); ?>,
            "daysOfWeek": [
                "<?php echo e(__('Su')); ?>",
                "<?php echo e(__('Mo')); ?>",
                "<?php echo e(__('Tu')); ?>",
                "<?php echo e(__('We')); ?>",
                "<?php echo e(__('Th')); ?>",
                "<?php echo e(__('Fr')); ?>",
                "<?php echo e(__('Sa')); ?>"
            ],
            "monthNames": [
                "<?php echo e(__('January')); ?>",
                "<?php echo e(__('February')); ?>",
                "<?php echo e(__('March')); ?>",
                "<?php echo e(__('April')); ?>",
                "<?php echo e(__('May')); ?>",
                "<?php echo e(__('June')); ?>",
                "<?php echo e(__('July')); ?>",
                "<?php echo e(__('August')); ?>",
                "<?php echo e(__('September')); ?>",
                "<?php echo e(__('October')); ?>",
                "<?php echo e(__('November')); ?>",
                "<?php echo e(__('December')); ?>"
            ],
        };

        var image_editer = {
            language: '<?php echo e(app()->getLocale()); ?>',
            translations: {
                <?php echo e(app()->getLocale()); ?>: {
                    'header.image_editor_title': '<?php echo e(__('Image Editor')); ?>',
                    'header.toggle_fullscreen': '<?php echo e(__('Toggle fullscreen')); ?>',
                    'header.close': '<?php echo e(__('Close')); ?>',
                    'header.close_modal': '<?php echo e(__('Close window')); ?>',
                    'toolbar.download': '<?php echo e(__('Save Change')); ?>',
                    'toolbar.save': '<?php echo e(__('Save')); ?>',
                    'toolbar.apply': '<?php echo e(__('Apply')); ?>',
                    'toolbar.saveAsNewImage': '<?php echo e(__('Save As New Image')); ?>',
                    'toolbar.cancel': '<?php echo e(__('Cancel')); ?>',
                    'toolbar.go_back': '<?php echo e(__('Go Back')); ?>',
                    'toolbar.adjust': '<?php echo e(__('Adjust')); ?>',
                    'toolbar.effects': '<?php echo e(__('Effects')); ?>',
                    'toolbar.filters': '<?php echo e(__('Filters')); ?>',
                    'toolbar.orientation': '<?php echo e(__('Orientation')); ?>',
                    'toolbar.crop': '<?php echo e(__('Crop')); ?>',
                    'toolbar.resize': '<?php echo e(__('Resize')); ?>',
                    'toolbar.watermark': '<?php echo e(__('Watermark')); ?>',
                    'toolbar.focus_point': '<?php echo e(__('Focus point')); ?>',
                    'toolbar.shapes': '<?php echo e(__('Shapes')); ?>',
                    'toolbar.image': '<?php echo e(__('Image')); ?>',
                    'toolbar.text': '<?php echo e(__('Text')); ?>',
                    'adjust.brightness': '<?php echo e(__('Brightness')); ?>',
                    'adjust.contrast': '<?php echo e(__('Contrast')); ?>',
                    'adjust.exposure': '<?php echo e(__('Exposure')); ?>',
                    'adjust.saturation': '<?php echo e(__('Saturation')); ?>',
                    'orientation.rotate_l': '<?php echo e(__('Rotate Left')); ?>',
                    'orientation.rotate_r': '<?php echo e(__('Rotate Right')); ?>',
                    'orientation.flip_h': '<?php echo e(__('Flip Horizontally')); ?>',
                    'orientation.flip_v': '<?php echo e(__('Flip Vertically')); ?>',
                    'pre_resize.title': '<?php echo e(__('Would you like to reduce resolution before editing the image?')); ?>',
                    'pre_resize.keep_original_resolution': '<?php echo e(__('Keep original resolution')); ?>',
                    'pre_resize.resize_n_continue': '<?php echo e(__('Resize & Continue')); ?>',
                    'footer.reset': '<?php echo e(__('Reset')); ?>',
                    'footer.undo': '<?php echo e(__('Undo')); ?>',
                    'footer.redo': '<?php echo e(__('Redo')); ?>',
                    'spinner.label': '<?php echo e(__('Processing...')); ?>',
                    'warning.too_big_resolution': '<?php echo e(__('The resolution of the image is too big for the web. It can cause problems with Image Editor performance.')); ?>',
                    'common.x': '<?php echo e(__('x')); ?>',
                    'common.y': '<?php echo e(__('y')); ?>',
                    'common.width': '<?php echo e(__('width')); ?>',
                    'common.height': '<?php echo e(__('height')); ?>',
                    'common.custom': '<?php echo e(__('custom')); ?>',
                    'common.original': '<?php echo e(__('original')); ?>',
                    'common.square': '<?php echo e(__('square')); ?>',
                    'common.opacity': '<?php echo e(__('Opacity')); ?>',
                    'common.apply_watermark': '<?php echo e(__('Apply watermark')); ?>',
                    'common.url': '<?php echo e(__('URL')); ?>',
                    'common.upload': '<?php echo e(__('Upload')); ?>',
                    'common.gallery': '<?php echo e(__('Gallery')); ?>',
                    'common.text': '<?php echo e(__('Text')); ?>',
                }
            }
        };
    </script>
    <script src="<?php echo e(asset('libs/tinymce/js/tinymce/tinymce.min.js')); ?>" ></script>
    <?php echo $__env->yieldPushContent('css'); ?>

</head>
<body class="<?php echo e(($enable_multi_lang ?? '') ? 'enable_multi_lang' : ''); ?> <?php if(setting_item('site_enable_multi_lang')): ?> site_enable_multi_lang <?php endif; ?>">
<div id="app">
    <div class="main-header d-flex">
        <?php echo $__env->make('Layout::admin.parts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="main-sidebar">
        <?php echo $__env->make('Layout::admin.parts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="main-content">
        <?php echo $__env->make('Layout::admin.parts.bc', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 copy-right" >
                        <?php echo e(date('Y')); ?> &copy; <?php echo e(__('HallaJoin by')); ?> <a href="<?php echo e(__('https://www.bookingcore.org')); ?>" target="_blank"><?php echo e(__('BookingCore Team')); ?></a>
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-right footer-links d-none d-sm-block">
                            <a href="<?php echo e(__('https://www.bookingcore.org')); ?>" target="_blank"><?php echo e(__('About Us')); ?></a>
                            <a href="<?php echo e(__('https://m.me/bookingcore')); ?>" target="_blank"><?php echo e(__('Contact Us')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <div class="backdrop-sidebar-mobile"></div>
</div>

<?php echo $__env->make('Media::browser', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- Scripts -->
<?php echo \App\Helpers\Assets::css(true); ?>

<script src="<?php echo e(asset('libs/pusher.min.js')); ?>"></script>
<script src="<?php echo e(asset('dist/admin/js/manifest.js?_ver='.config('app.asset_version'))); ?>" ></script>
<script src="<?php echo e(asset('libs/jquery-3.6.3.min.js?_ver='.config('app.asset_version'))); ?>" ></script>
<script src="<?php echo e(asset('themes/admin/libs/bootstrap-4.6.2-dist/js/bootstrap.bundle.min.js?_ver='.config('app.asset_version'))); ?>" ></script>
<script src="<?php echo e(asset('dist/admin/js/vendor.js?_ver='.config('app.asset_version'))); ?>" ></script>
<script src="<?php echo e(asset('libs/filerobot-image-editor/filerobot-image-editor.min.js?_ver='.config('app.asset_version'))); ?>"></script>

<script src="<?php echo e(asset('dist/admin/js/app.js?_ver='.config('app.asset_version'))); ?>" ></script>
<script src="<?php echo e(asset('libs/vue/vue'.(!env('APP_DEBUG') ? '.min':'').'.js')); ?>"></script>

<script src="<?php echo e(asset('libs/select2/js/select2.min.js')); ?>" ></script>
<script src="<?php echo e(asset('libs/bootbox/bootbox.min.js')); ?>"></script>

<script src="<?php echo e(url('libs/daterange/moment.min.js')); ?>"></script>
<script src="<?php echo e(url('libs/daterange/daterangepicker.min.js?_ver='.config('app.asset_version'))); ?>"></script>

<?php echo \App\Helpers\Assets::js(true); ?>


<?php echo $__env->yieldPushContent('js'); ?>

</body>
</html>
<?php /**PATH F:\MAMP\htdocs\core\modules/Layout/admin/app.blade.php ENDPATH**/ ?>