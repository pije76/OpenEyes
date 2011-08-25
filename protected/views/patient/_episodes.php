<?php
Yii::app()->clientScript->scriptMap['jquery.js'] = false; ?>
<div id="box_gradient_top"></div>
<div id="box_gradient_bottom">
<div style="height: 20px; float: left;"></div>
<div id="add_episode">
	<img src="/images/add_event_button.png" alt="Add an event to this episode" />
	<ul id="episode_types">
<?php
	foreach ($eventTypeGroups as $group => $eventTypes) { ?>
		<li class="header"><?php echo $group; ?></li>
<?php	foreach ($eventTypes as $type) {
			$name = ucfirst($type->name); ?>
		<li><img src="/images/<?php echo $type->name; ?>.gif" alt="<?php 
		echo $name; ?>" /><?php
		echo CHtml::link($name, array('clinical/create', 'event_type_id'=>$type->id),
			array('class'=>'fancybox2', 'encode'=>false)); ?></li>
<?php
		}
	} ?>
	</ul>
</div>
<div class="clear"></div>
<div id="episodes_sidebar">
<?php
	$this->renderPartial('/clinical/_episodeList',
		array('episodes' => $episodes)
	); ?>
</div>
<div id="episodes_details"><?php
	if ($event === false) {
		$episode = end($episodes);

		// View the open episode for this firm's specialty, if any
		foreach ($episodes as $ep) {
			if ($ep->firm->serviceSpecialtyAssignment->specialty_id == $firm->serviceSpecialtyAssignment->specialty_id) {
				$episode = $ep;
			}
		}
		$this->renderPartial('/clinical/episodeSummary',
			array('episode' => $episode)
		);
	} ?></div>
</div>
<script type="text/javascript">
	$(function() {
		if ($('#episodes_details').text() == '') {
			var link = $('a[href="<?php echo Yii::app()->createUrl('clinical/view', array('id'=>$event)); ?>"]');
			$.ajax({
				url: '<?php echo Yii::app()->createUrl('clinical/view', array('id'=>$event)); ?>',
				success: function(data) {
					link.parent().addClass('shown');
					$('#episodes_details').show();
					$('#episodes_details').html(data);
				}
			});
		}
	});
	$('#add_episode').click(function() {
		if ($('#episode_types').is(':visible')) {
			$('#episode_types').hide();
		} else {
			$('#episode_types').slideDown({'duration':75});
		}
	});
	$('#episode_types li a').click(function() {
		$('ul.events li.shown').removeClass('shown');
	});
	$('ul.events li a').live('click', function() {
		$('ul.events li.shown').removeClass('shown');
		$(this).parent().addClass('shown');
		$.ajax({
			url: $(this).attr('href'),
			success: function(data) {
				$('#episodes_details').show();
				$('#episodes_details').html(data);
			}
		});
		return false;
	});
	$('.episode div.title').live('click', function() {
		var id = $(this).children('input').val();
		$('ul.events li.shown').removeClass('shown');
		$.ajax({
			url: '<?php echo Yii::app()->createUrl('clinical/episodeSummary'); ?>',
			type: 'GET',
			data: {'id': id},
			success: function(data) {
				$('#episodes_details').show();
				$('#episodes_details').html(data);
			}
		});
		return false;
	});
	$('a.fancybox2').fancybox({'onStart':function() { $('ul.events li.shown').removeClass('shown'); }});
</script>