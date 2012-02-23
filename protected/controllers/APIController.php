<?php
/*
_____________________________________________________________________________
(C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
(C) OpenEyes Foundation, 2011
This file is part of OpenEyes.
OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
_____________________________________________________________________________
http://www.openeyes.org.uk	 info@openeyes.org.uk
--
*/

class APIController extends BaseController
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
 
 	private $errors = array();
	private $models = array(
		'user',
		'site',
		'service',
		'address',
		'patient',
		'country',
	);

	public function filters()
	{
		return array('accessControl');
	}

	public function accessRules()
	{
		return array(
			array('allow',
				'users'=>array('@','?')
			),
			// non-logged in can't view anything
			array('deny',
				'users'=>array('?')
			),
		);
	}

	public function beforeAction() {
		if (!isset($_REQUEST['username'])) {
			$this->error('Missing username');
		}
		if (!isset($_REQUEST['apikey'])) {
			$this->error('Missing API key');
		}
		if (!preg_match('/^[0-9a-f]{40}$/',$_REQUEST['apikey']) || !User::model()->find('username = :username and api_key = :api_key',array(':username'=>$_REQUEST['username'],':api_key'=>$_REQUEST['apikey']))) {
			$this->error('Authentication failed');
		}
	}

	public function missingAction($model) {
		$this->beforeAction();

		if (in_array($model,$this->models)) {
			$args = $this->getMethodArgs($model);

			return $this->api($model,$args);
		}

		$this->error("The method '$model' does not exist");
	}

	public function getMethodArgs($method) {
		$request = preg_replace('/\?.*$/','',$_SERVER['REQUEST_URI']);

		$args = array();
		$start = false;

		foreach (explode('/',$request) as $el) {
			if ($el == $method) {
				$start = true;
			} else if ($start) {
				$args[] = $el;
			}
		}

		return $args;
	}

	public function error($msg) {
		$this->send(array(
			'result' => 'error',
			'message' => $msg
		));
	}

	public function success($data) {
		$this->send(array(
			'result' => 'success',
			'data' => $data
		));
	}

	public function send($data) {
		die(json_encode($data));
	}

	public function to_array($object) {
		$data = array();

		foreach ($object as $key => $value) {
			$data[$key] = $value;
		}

		return $data;
	}

	public function api($model, $args) {
		if (count($args) <1) {
			$this->error('Missing parameter(s)');
		}

		$model = ucfirst($model);

		if (ctype_digit($args[0])) {
			$id = $args[0];

			if ($obj = $model::model()->findByPk($id)) {
				return $this->success($this->to_array($obj));
			} else {
				return $this->error($model.' not found');
			}
		}

		switch($args[0]) {
			case 'list':
				$objects = array();

				foreach ($model::model()->findAll('',array('order' => 'id asc')) as $obj) {
					$objects[] = $this->to_array($obj);
				}

				return $this->success($objects);
			case 'create':
				foreach ($model::Model()->getRequiredFields() as $field) {
					if (!isset($_POST[$field])) {
						$this->error("Missing required field '$field'");
					}
				}

				$obj = new $model;

				foreach ($_POST as $key => $value) {
					if ($obj->hasAttribute($key)) {
						$obj->{$key} = $value;
					} else {
						$this->error("Invalid field: $key");
					}
				}

				if ($obj->save()) {
					return $this->success($obj->id);
				}

				return $this->error("Failed to create $model object: ".print_r($obj->getErrors(),true));

			case 'update':
				if (!isset($args[1]) || !ctype_digit($args[1])) {
					$this->error("Missing or $model ID");
				}

				if (!$obj = $model::model()->findByPk((integer)$args[1])) {
					$this->error("$model not found");
				}

				foreach ($_POST as $key => $value) {
					if ($obj->hasAttribute($key)) {
						$munge_method = "munge{$model}".ucfirst($key);
						if (method_exists($this,$munge_method)) {
							$obj->{$key} = $this->{$munge_method}($value);
						} else {
							$obj->{$key} = $value;
						}
					} else {
						$this->error("Invalid field: $key");
					}
				}

				if ($obj->save()) {
					return $this->success($model." updated");
				}

				return $this->error("Failed to update $model object {$args[1]}: ".print_r($obj->getErrors(),true));

			case 'delete':
				if (!isset($args[1]) || !ctype_digit($args[1])) {
					$this->error("Missing or $model ID");
				}

				if (!$obj = $model::model()->findByPk((integer)$args[1])) {
					$this->error("$model not found");
				}

				if ($obj->delete()) {
					return $this->success($model." deleted");
				}

				return $this->error("Failed to delete $model object {$args[1]}: ".print_r($obj->getErrors(),true));

			default:
				$this->error("Unknown $model method '{$args[0]}'");
		}
	}
}
?>