<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Crud\Action\Index;

class AdminController extends AppController
{
	use \Crud\Controller\ControllerTrait;

	public function isAuthorized($user = null) {
		if($user !== null) {
			if($user['employeeType'][0] === 'Admin') {
				return true;
			}
		}
		return false;
	}

	public function initialize() {
		parent::initialize();

		$this->loadComponent('Crud.Crud', [
			'actions' => [
				'index' => ['className' => '\App\Crud\Action\Index']
			]
		]);
	}

}
?>
