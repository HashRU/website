<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link	  http://cakephp.org CakePHP(tm) Project
 * @since	 0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Controller\Component\AuthComponent;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadComponent('Security');`
	 *
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');

		$this->loadComponent('Auth', ['authenticate' =>
			['Ldap' => ['fields' =>
				['username' => 'username',
				'password' => 'password']
			]
			], 'loginAction' => [
				'controller' => 'Login',
				'action' => 'login'
			]
		]);

		$this->Auth->config('authorize', ['Controller']);

		$this->Auth->config('authorize', [
			AuthComponent::ALL => ['actionPath' => 'controllers/'],
			'Controller'
		]);
	}

	public function isAuthorized($user = null) {
		return true;
	}

	/**
	 * Before render callback.
	 *
	 * @param \Cake\Event\Event $event The beforeRender event.
	 * @return void
	 */
	public function beforeRender(Event $event)
	{
		if (!array_key_exists('_serialize', $this->viewVars) &&
			in_array($this->response->type(), ['application/json', 'application/xml'])
		) {
			$this->set('_serialize', true);
		}

		$user = $this->Auth->user();
		$isAdmin = false;
		if($user !== null) {
			if($user['employeeType'][0] === 'Admin') {
				$isAdmin = true;
			}
		}
		$this->set('isAdmin', $isAdmin);
	}
}
