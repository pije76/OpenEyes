<div id="<?php echo get_class($element). '_' . $field?>" class="eventDetail"<?php if ($hidden) {?> style="display: none;"<?php }?>>
	<?php	// Added hidden input below to enforce posting of current form element name. 
			// When using radio or checkboxes if no value is selected then nothing is posted
			// not triggereing server side validation.
	?>
	<input type="hidden" value="" name="<?php echo get_class($element)?>[<?php echo $field?>]">
	<div class="label"><?php echo CHtml::encode($element->getAttributeLabel($field)); ?>:</div>
	<div class="data">
		<?php $i=0; ?>
		<?php foreach ($data as $id => $value) {?>
			<span class="group">
				<?php echo CHtml::radioButton($name, $element->$field == $id,array('value' => $id, "id" => get_class($element). '_' . $field . '_' . $id))?>
				<label for="<?php echo get_class($element)?>_<?php echo $field?>_<?php echo $id?>"><?php echo $value?></label>
			</span>
			<?php
			if ($maxwidth) {
				$i++;
				if ($i >= $maxwidth) {
					echo "<br />";
					$i=0;
				}
			}
			?>
		<?php }?>
	</div>
</div>
