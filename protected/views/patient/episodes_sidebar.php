<div id="episodes_sidebar">
	<?php if (is_array($episodes)) foreach ($episodes as $i => $episode) {
		if (!isset($current_episode) && $i == 0) $current_episode = $episode;
		?>
		<div class="episode <?php echo empty($episode->end_date) ? 'closed' : 'open' ?> clearfix">
			<div class="episode_nav">
				<input type="hidden" name="episode-id" value="<?php echo $episode->id?>" />
				<div class="small"><?php echo $episode->NHSDate('start_date'); ?><span style="float:right;"><a href="/patient/episode/<?php echo $episode->id?>" rel="<?php echo $episode->id?>" class="episode-details">(Episode) summary</a></span></div>
				<h4><?php echo CHtml::encode($episode->firm->serviceSubspecialtyAssignment->subspecialty->name)?></h4>
				<ul class="events">
					<?php foreach ($episode->events as $event) {
						$event_type = EventType::model()->findByPk($event->event_type_id);

						if ($event_type->class_name == 'OphTrOperation') {
							$event_elements = $this->getDefaultElements(false,$event);

							$scheduled = false;
							foreach ($event_elements as $element) {
								if (get_class($element) == 'ElementOperation' && in_array($element->status, array(ElementOperation::STATUS_SCHEDULED, ElementOperation::STATUS_RESCHEDULED))) {
									$scheduled = true;
								}
							}
						}

						$highlight = false;

						if ($event_type->class_name == 'OphTrOperation') {
							$event_path = '/patient/event/';
						} else {
							$event_path = '/'.$event_type->class_name.'/Default/view/';
						}
						?>
						<li id="eventLi<?php echo $event->id ?>"><a href="<?php echo $event_path.$event->id?>" rel="<?php echo $event->id?>" class="show-event-details"><?php if ($highlight) echo '<div class="viewing">'?><span class="type"><img src="/img/_elements/icons/event/small/treatment_operation<?php if (!$scheduled) { echo '_unscheduled'; } else { echo '_booked';}?>.png" alt="op" width="16" height="16" /></span><span class="date"> <?php echo $event->NHSDateAsHTML('datetime'); ?></span><?php if ($highlight) echo '</div>' ?></a></li>
				<?php
					}
				?>
				</ul>
			</div>
			<div class="episode_details hidden" id="episode-details-<?php echo $episode->id?>">
				<div class="row"><span class="label">Start date:</span><?php echo $episode->NHSDate('start_date'); ?></div>
				<div class="row"><span class="label">End date:</span><?php echo ($episode->end_date ? $episode->NHSDate('end_date') : '-')?></div>
				<?php $diagnosis = $episode->getPrincipalDiagnosis() ?>
				<div class="row"><span class="label">Principal eye:</span><?php echo !empty($diagnosis) ? $diagnosis->getEyeText() : 'No diagnosis' ?></div>
				<div class="row"><span class="label">Principal diagnosis:</span><?php echo !empty($diagnosis) ? $diagnosis->disorder->term : 'No diagnosis' ?></div>
				<div class="row"><span class="label">Subspecialty:</span><?php echo CHtml::encode($episode->firm->serviceSubspecialtyAssignment->subspecialty->name)?></div>
				<div class="row"><span class="label">Consultant firm:</span><?php echo CHtml::encode($episode->firm->name)?></div>
				<img class="folderIcon" src="/img/_elements/icons/folder_open.png" alt="folder open" />
			</div>
		</div> <!-- .episode -->
	<?php }?>
</div> <!-- #episodes_sidebar -->