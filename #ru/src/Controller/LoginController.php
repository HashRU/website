<?php

namespace App\Controller;

class LoginController extends AppController {

	public function login() {
		if ($this->request->is('post')){
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
				return $this->redirect($this->Auth->redirectUrl());
			}
			// user is not identified
			$this->Flash->error('Your username or password is not correct');
		}
	}

}

 ?>
