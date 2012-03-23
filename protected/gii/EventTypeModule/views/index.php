<script type="text/javascript" src="/js/gii.js"></script>

<h1>Event type module Generator</h1>

<p>This generator helps you to generate the skeleton code needed by an OpenEyes event type module.</p>

<?php $form=$this->beginWidget('BaseGiiEventTypeCActiveForm', array('model'=>$model)); ?>

	<div class="row">
		<h3>Describe your event type:</h3>
		<label>Specialty: </label><?php echo CHtml::dropDownList('Specialty[id]',@$_REQUEST['Specialty']['id'], CHtml::listData(Specialty::model()->findAll(array('order' => 'name')), 'id', 'name'))?><br />
		<label>Event group: </label><?php echo CHtml::dropDownList('EventGroup[id]', @$_REQUEST['EventGroup']['id'], CHtml::listData(EventGroup::model()->findAll(array('order' => 'name')), 'id', 'name'))?><br />
		<label>Name of event type: </label> <?php echo $form->textField($model,'moduleSuffix',array('size'=>65)); ?><br />

		<h3>Describe your element types:</h3>

		<div id="elements">
			<?php foreach ($_POST as $key => $value) {
				if (preg_match('/^elementName([0-9]+)$/',$key,$m)) {
					echo $this->renderPartial('element',array('element_num'=>$m[1]));
				}
			}
			?>
		</div>

		<input type="submit" class="add_element" name="add" value="add element" /><br />
		<br/>

		<div class="tooltip">
			The name should only contain word characters and spaces.	The generated module class will be named based on the specialty, event group, and name of the event type.  EG: 'Ophthalmology', 'Treatment', and 'Operation note' will take the short codes for the specialty and event group to create <code>OphTrOperationnote</code>.
		</div>
		<?php echo $form->error($model,'moduleID'); ?>
	</div>
<?php $this->endWidget(); ?>