<?php
$minValue = 0;
?>
<div class="form-select-seat-type">
	<div class="form-group">
		<i class="field-icon icofont-ticket"></i>
		<div class="form-content dropdown-toggle" data-toggle="dropdown">
			<div class="wrapper-more">
				<label> <?php echo e($field['title']); ?> </label>
				<?php
					$seatTypeGet = request()->query('seat_type',[]);
				?>
				<div class="render font-size-14">
					<?php $__currentLoopData = $seatType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
						<?php
						$inputRender = 'seat_type_'.$type->code.'_render';
						$inputValue = $seatTypeGet[$type->code] ?? $minValue;
						;?>
						<span class="" id="<?php echo e($inputRender); ?>">
                            <span class="one <?php if($inputValue > $minValue): ?> d-none <?php endif; ?>"><?php echo e(__( ':min :name',['min'=>$minValue,'name'=>$type->name])); ?></span>
                            <span class="<?php if($inputValue <= $minValue): ?> d-none <?php endif; ?> multi" data-html="<?php echo e(__(':count '.$type->name)); ?>"><?php echo e(__(':count'.$type->name,['count'=>$inputValue??$minValue])); ?></span>
                        </span>
					<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
				</div>
			</div>
		</div>
		<div class="dropdown-menu select-seat-type-dropdown" >
			<?php $__currentLoopData = $seatType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<?php
				$inputName = 'seat_type_'.$type->code;
				$inputValue = $seatTypeGet[$type->code] ?? $minValue;
				;?>

				<div class="dropdown-item-row">
					<div class="label"><?php echo e(__('Adults :type',['type'=>$type->name])); ?></div>
					<div class="val">
						<span class="btn-minus" data-input="<?php echo e($inputName); ?>" data-input-attr="id"><i class="icon ion-md-remove"></i></span>
						<span class="count-display"><input id="<?php echo e($inputName); ?>" type="number" name="seat_type[<?php echo e($type->code); ?>]" value="<?php echo e($inputValue); ?>" min="<?php echo e($minValue); ?>"></span>
						<span class="btn-add" data-input="<?php echo e($inputName); ?>" data-input-attr="id"><i class="icon ion-ios-add"></i></span>
					</div>
				</div>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
	</div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\themes/BC/Flight/Views/frontend/layouts/search/fields/seat_type.blade.php ENDPATH**/ ?>