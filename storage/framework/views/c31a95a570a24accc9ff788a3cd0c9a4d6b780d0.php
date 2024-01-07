<div class="bravo_topbar">
    <div class="container">
        <div class="content">
            <div class="topbar-left">

                <?php echo clean(setting_item_with_lang("topbar_left_text")); ?>



            </div>
            <div class="topbar-right">
                <ul class="topbar-items">
                    <?php echo $__env->make('Core::frontend.currency-switcher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('Language::frontend.switcher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php if(!Auth::check()): ?>
                        <li class="login-item">
                            <a href="#login" data-toggle="modal" data-target="#login" class="login"><?php echo e(__('Login')); ?></a>
                        </li>
                        <?php if(is_enable_registration()): ?>
                            <li class="signup-item">
                                <a href="#register" data-toggle="modal" data-target="#register" class="signup"><?php echo e(__('Sign Up')); ?></a>
                            </li>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php echo $__env->make('Layout::parts.notification', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <li class="login-item dropdown">
                            <a href="#" data-toggle="dropdown" class="login"><?php echo e(__("Hi, :name",['name'=>Auth::user()->getDisplayName()])); ?>

                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-user text-left">
                                <?php if(empty( setting_item('wallet_module_disable') )): ?>
                                    <li class="credit_amount">
                                        <a href="<?php echo e(route('user.wallet')); ?>"><i class="fa fa-money"></i> <?php echo e(__("Credit: :amount",['amount'=>auth()->user()->balance])); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if(is_vendor()): ?>
                                <li class="menu-hr"><a href="<?php echo e(route('vendor.dashboard')); ?>" class="menu-hr"><i class="icon ion-md-analytics"></i> <?php echo e(__("Vendor Dashboard")); ?></a></li>
                                <?php endif; ?>
                                <li class="<?php if(is_vendor()): ?> menu-hr <?php endif; ?>">
                                    <a href="<?php echo e(route('user.profile.index')); ?>"><i class="icon ion-md-construct"></i> <?php echo e(__("My profile")); ?></a>
                                </li>
                                <?php if(setting_item('inbox_enable')): ?>
                                <li class="menu-hr">
                                    <a href="<?php echo e(route('user.chat')); ?>"><i class="fa fa-comments"></i> <?php echo e(__("Messages")); ?>

                                        <?php if($count = auth()->user()->unseen_message_count): ?>
                                            <span class="badge badge-danger"><?php echo e($count); ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                                    <li class="menu-hr"><a href="<?php echo e(route('user.booking_history')); ?>"><i class="fa fa-clock-o"></i> <?php echo e(__("Booking History")); ?></a></li>
                                <li class="menu-hr"><a href="<?php echo e(route('user.change_password')); ?>"><i class="fa fa-lock"></i> <?php echo e(__("Change password")); ?></a></li>

                                <?php if(is_enable_plan() ): ?>
                                    <li class="menu-hr"><a href="<?php echo e(route('user.plan')); ?>"><i class="fa fa-list-alt"></i> <?php echo e(__("My plan")); ?></a></li>
                                <?php endif; ?>

                                <?php if(is_admin()): ?>
                                    <li class="menu-hr"><a href="<?php echo e(route('admin.index')); ?>"><i class="icon ion-ios-ribbon"></i> <?php echo e(__("Admin Dashboard")); ?></a></li>
                                <?php endif; ?>
                                <li class="menu-hr">
                                    <a  href="#" onclick="event.preventDefault(); document.getElementById('logout-form-topbar').submit();"><i class="fa fa-sign-out"></i> <?php echo e(__('Logout')); ?></a>
                                </li>
                            </ul>
                            <form id="logout-form-topbar" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                <?php echo e(csrf_field()); ?>

                            </form>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\modules/Layout/parts/topbar.blade.php ENDPATH**/ ?>