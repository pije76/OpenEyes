<?php
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerCSSFile('/css/theatre.css', 'all');
?>
<h3 class="title">Theatre Schedules</h3>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'theatre-filter',
	'action'=>Yii::app()->createUrl('theatre/search'),
    'enableAjaxValidation'=>false,
)); ?>
<div id="search-options">
	<div id="main-search">
	<div id="title">Show schedules by:</div>
	<table>
	<tr>
		<th>Site:</th>
		<th>Service:</th>
		<th>Firm:</th>
		<th>Theatre:</th>
		<th>Ward:</th>
	</tr>
	<tr>
		<td><?php
	echo CHtml::dropDownList('site-id', '', Site::model()->getList(),
		array('empty'=>'All sites', 'onChange' => "js:loadTheatres(this.value); loadWards(this.value);")); ?></td>
		<td><?php
	echo CHtml::dropDownList('service-id', '', Service::model()->getList(),
		array('empty'=>'All services', 'ajax'=>array(
			'type'=>'POST',
			'data'=>array('service_id'=>'js:this.value'),
			'url'=>Yii::app()->createUrl('theatre/filterFirms'),
			'success'=>"js:function(data) {
				$('#firm-id').attr('disabled', false);
				$('#firm-id').html(data);
			}",
		))); ?></td>
		<td><?php
	echo CHtml::dropDownList('firm-id', '', array(),
		array('empty'=>'All firms', 'disabled'=>(empty($firmId)))); ?></td>
		<td><?php
	echo CHtml::dropDownList('theatre-id', '', array(),
		array('empty'=>'All theatres')); ?></td>
		<td><?php
	echo CHtml::dropDownList('ward-id', '', array(),
		array('empty'=>'All wards')); ?></td>
	</tr>
	</table>
	</div>
	<div id="extra-search">
<?php
	echo CHtml::radioButtonList('date-filter', '', Theatre::getDateFilterOptions(),
		array('separator' => '&nbsp;')); ?>
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'date-start',
	'id'=>'date-start',
    // additional javascript options for the date picker plugin
    'options'=>array(
		'changeMonth'=>true,
		'changeYear'=>true,
		'showOtherMonths'=>true,
        'showAnim'=>'fold',
		'dateFormat'=>'yy-mm-dd',
		'onSelect'=>"js:function(selectedDate) {
			var option = this.id == 'date-start' ? 'minDate' : 'maxDate',
				instance = $(this).data('datepicker'),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			if (this.id == 'date-start') {
				$('#date-end').datepicker('option', option, date);
			} else {
				$('#date-start').datepicker('option', option, date);
			}
		}",
		'onClose'=>"js:function(dateText, inst) {
			if (dateText != '') {
				$('input[name=date-filter][value=custom]').attr('checked', true);
			}
		}",
    ),
	'htmlOptions'=>array('size'=>10),
));
?> to
<?php
$this->widget('zii.widgets.jui.CJuiDatePicker', array(
    'name'=>'date-end',
	'id'=>'date-end',
    // additional javascript options for the date picker plugin
    'options'=>array(
		'changeMonth'=>true,
		'changeYear'=>true,
		'showOtherMonths'=>true,
        'showAnim'=>'fold',
		'dateFormat'=>'yy-mm-dd',
		'onSelect'=>"js:function(selectedDate) {
			var option = this.id == 'date-start' ? 'minDate' : 'maxDate',
				instance = $(this).data('datepicker'),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			if (this.id == 'date-start') {
				$('#date-end').datepicker('option', option, date);
			} else {
				$('#date-start').datepicker('option', option, date);
			}
		}",
		'onClose'=>"js:function(dateText, inst) {
			if (dateText != '') {
				$('input[name=date-filter][value=custom]').attr('checked', true);
			}
		}"
    ),
	'htmlOptions'=>array('size'=>10),
));
?>
<button type="submit" value="submit" class="shinybutton highlighted"><span>Search</span></button>
<?php $this->endWidget(); ?>
	</div>
</div>

<div class="search-options">
</div>
<div class="main-search">
</div>
<div class="cleartall"></div>
<div id="searchResults"></div>
<div class="cleartall"></div>
<script type="text/javascript">
	$('#theatre-filter button[type="submit"]').click(function() {
		$.ajax({
			'url': '<?php echo Yii::app()->createUrl('theatre/search'); ?>',
			'type': 'POST',
			'data': $('#theatre-filter').serialize(),
			'success': function(data) {
				$('#searchResults').html(data);
				return false;
			}
		});
		return false;
	});
	$('input[name=date-filter]').change(function() {
		if ($(this).val() != 'custom') {
			$('input[id=date-start]').val('');
			$('input[id=date-end]').val('');
		}
	});
	function loadTheatres(siteId) {
		$.ajax({
			'type': 'POST',
			'data': {'site_id': siteId},
			'url': '<?php echo Yii::app()->createUrl('theatre/filterTheatres'); ?>',
			'success':function(data) {
				$('#theatre-id').html(data);
			}
		});
	}
	function loadWards(siteId) {
		$.ajax({
			'type': 'POST',
			'data': {'site_id': siteId},
			'url': '<?php echo Yii::app()->createUrl('theatre/filterWards'); ?>',
			'success':function(data) {
				$('#ward-id').html(data);
			}
		});
	}
</script>