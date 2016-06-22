<?php

namespace App\Controller;

class SecretController extends AppController {

	public function initialize() {
		parent::initialize();
	}

	public function isAuthorized($user = null) {
		return $user != null;
	}

	public function index() {
		$user = $this->request->session()->read('Auth.User');
		var_dump($user);
		$this->set('user', $user['uid'][0]);
		$this->render('index');
	}

}

 ?>
