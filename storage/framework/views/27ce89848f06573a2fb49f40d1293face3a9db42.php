<?php
	if (empty($inputName)){
	    $inputName = 'location_id';
	}
?>
<div class="form-group">
	<i class="field-icon icofont-paper-plane"></i>
	<div class="form-content">
		<label><?php echo e($field['title'] ?? ""); ?></label>
		<?php
		$location_name = "";
		$list_json = [];
		$traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name,$inputName) {
			foreach ($locations as $location) {
				$translate = $location->translate();
				if (Request::query($inputName) == $location->id) {
					$location_name = $translate->name;
				}
				$list_json[] = [
						'id'    => $location->id,
						'title' => $prefix.' '.$translate->name,
				];
				$traverse($location->children, $prefix.'-');
			}
		};
		$traverse($list_location);
		?>
		<div class="smart-search">
			<input type="text" class="smart-search-location parent_text form-control font-size-14" <?php echo e(( empty(setting_item("flight_location_search_style")) or setting_item("flight_location_search_style") == "normal" ) ? "readonly" : ""); ?> placeholder="<?php echo e(__("City or airport")); ?>" value="<?php echo e($location_name); ?>" data-onLoad="<?php echo e(__("Loading...")); ?>"
				   data-default="<?php echo e(json_encode($list_json)); ?>">
			<input type="hidden" class="child_id" name="<?php echo e($inputName); ?>" value="<?php echo e(Request::query('location_id')); ?>">
		</div>
	</div>
</div>
<?php /**PATH F:\MAMP\htdocs\core\themes/BC/Flight/Views/frontend/layouts/search/fields/location.blade.php ENDPATH**/ ?>