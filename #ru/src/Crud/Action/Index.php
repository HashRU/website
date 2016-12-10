<?php
namespace App\Crud\Action;

use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class Index extends LdapBase
{
	/**
	 * Default settings
	 *
	 * @var array
	 */
	protected $_defaultConfig = [
		'enabled' => true,
		'scope' => 'table',
		'findMethod' => 'all',
		'view' => 'index',
		'viewVar' => 'items',
		'serialize' => [],
		'api' => [
			'success' => [
				'code' => 200
			],
			'error' => [
				'code' => 400
			]
		]
	];

	/**
	 * handler for HTTP GET requests
	 *
	 * @return void
	 */
	protected function _get()
	{
		$subject = $this->_subject();
		$subject->set(['success' => true, 'viewVar' => $this->_defaultConfig['viewVar']]);

		$ds = $this->ldap_login();

		$searchdn = "ou=users,dc=hashru,dc=nl";
		$search_result = ldap_search($ds, $searchdn, "(objectclass=posixAccount)");
		$entries = ldap_get_entries ($ds, $search_result);
		if(!$entries) {
			throw \Cake\Error\FatalErrorException ('Failed to get relevant ldap entries') ;
		}

		$this->ldap_logout($ds);

		$entries = $this->remove_counts($entries);

		$controller = $this->_controller();
		$subject->set([$this->_defaultConfig['viewVar'] => $entries]);

		$controller->set(['success' => $subject->success, $subject->viewVar => $subject->items]);
		$this->_trigger('beforeRender', $subject);
	}

	/**
	 * Generic handler for all HTTP verbs
	 *
	 * @return void
	 */
	protected function _handle()
	{
	}
}

?>
