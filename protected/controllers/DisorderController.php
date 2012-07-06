<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class DisorderController extends Controller {
	public $layout='column2';

	public function filters() {
		return array('accessControl');
	}

	public function accessRules() {
		return array(
				array('allow',
						'users'=>array('@')
				),
				// non-logged in can't view anything
				array('deny',
						'users'=>array('?')
				),
		);
	}

	/**
	 * Lists all disorders for a given search term.
	 */
	public function actionAutocomplete() {
		//if(Yii::app()->request->isAjaxRequest) {
			$criteria = new CDbCriteria();
			$params = array();
			if(isset($_GET['term']) && $term = $_GET['term']) {
				$criteria->addCondition('LOWER(term) LIKE :term');
				$params[':term'] = '%' . strtolower(strtr($term, array('%' => '\%'))) . '%';
			}
			$criteria->order = 'term';
			$criteria->params = $params;
			$disorders = Disorder::model()->findAll($criteria);
			$return = array();
			foreach($disorders as $disorder) {
				$return[] = array(
						'label' => $disorder->term,
						'value' => $disorder->term,
						'id' => $disorder->id,
				);
			}
			echo CJSON::encode($return);
		//}
	}
	public function actionDetails() {
		if (!isset($_REQUEST['name'])) {
			echo CJavaScript::jsonEncode(false);
			return;
		}

		$disorder = Disorder::model()->find('fully_specified_name = ? OR term = ?', array($_REQUEST['name'], $_REQUEST['name']));

		if (!isset($disorder)) {
			echo CJavaScript::jsonEncode(false);
			return;
		}

		echo $disorder->id;
	}

}
