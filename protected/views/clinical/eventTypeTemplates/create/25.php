<p><strong>Patient:</strong> <?php echo $patient->first_name . ' ' . $patient->last_name . ' (' . $patient->hos_num . ')'; ?></p>

<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($baseUrl.'/js/phrase.js');
Yii::app()->clientScript->registerCoreScript('jquery');

$form = $this->beginWidget('CActiveForm', array(
    'id'=>'clinical-create',
    'enableAjaxValidation'=>true,
	'htmlOptions' => array('class'=>'sliding')
));

echo CHtml::hiddenField('action', 'create');
echo CHtml::hiddenField('event_type_id', $eventTypeId);

echo $form->errorSummary($elements);

/**
 * Loop through all the possible element types and display
 */

foreach ($elements as $element) {
	$elementClassName = get_class($element);

	echo $this->renderPartial(
		'/elements/' .
			$elementClassName .
			'/_form/' .
			$element->viewNumber,
		array('model' => $element, 'form' => $form, 'specialties' => $specialties, 
			'patient' => $patient)
	);
}

// Display referral select box if required
if (isset($referrals) && is_array($referrals)) {
	// There is at least on referral, so include it/them
	if (count($referrals) > 1) {
		// Display a list of referrals for the user to choose from
?>
<div class="box_grey_big_gradient_top"></div>
<div class="box_grey_big_gradient_bottom">
	<span class="referral_red">There is more than one open referral that could apply to this event.</span><p />
	<label for="referral_id">Select the referral that applies to this event:</label>
<?php echo CHtml::dropDownList('referral_id', '', CHtml::listData($referrals, 'id', 'id')); ?>
</div>
<?php
	}
} ?>
<div class="cleartall"></div>
<button type="submit" value="submit" class="shinybutton highlighted" id="scheduleLater"><span>Save and schedule later</span></button>
<?php
// @todo: get this js to work with the pretty button above, since you can't style submit input buttons easily
echo CHtml::ajaxSubmitButton('Save and schedule later 2', array('clinical/create', 'event_type_id'=>$eventTypeId), array(
	'success' => "js: function(data) {
		try {
			arr = $.parseJSON(data);
			if (!$.isEmptyObject(arr)) {
				$('#clinical-create_es_ ul').html('');

				$.each(arr, function(index, value) {
					element = index.replace('Element', '');
					element = element.substr(0, element.indexOf('_'));
					list = '<li>' + element + ': ' + value + '</li>';
					$('#clinical-create_es_ ul').append(list);
				});
				$('#clinical-create_es_').show();
				return false;
			} else {
				$('#clinical-create_es_ ul').html('');
				$('#clinical-create_es_').hide();
			}
		} catch (e) {
			$.fancybox.close();
			document.open();
			document.write(data);
			document.close();
		}
	}"
	)
);
$this->endWidget();
