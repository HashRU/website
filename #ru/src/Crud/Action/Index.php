<?php
namespace App\Crud\Action;

use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class Index extends \Crud\Action\BaseAction
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

	 private function remove_counts($arr) {
		 foreach($arr as $key=>$val) {
			 if($key === "count") {
			 	unset($arr[$key]);
			} elseif(is_array($val)) {
				$arr[$key] = $this->remove_counts($arr[$key]);
			}
		}
		return $arr;
	}

	/**
	 * handler for HTTP GET requests
	 *
	 * @return void
	 */
	protected function _get()
	{
		$subject = $this->_subject();
		$subject->set(['success' => true, 'viewVar' => $this->_defaultConfig['viewVar']]);


		Configure::config('default', new PhpConfig());
		Configure::load('ldapconf', 'default');

		$rootcn = Configure::read('rootcn');
		$password = Configure::read('rootpass');

		$ds = @ldap_connect('ldaps://localhost') ;
		if (!$ds) {
			throw \Cake\Error\FatalErrorException ('Unable to connect to LDAP host.') ;
		}
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		$basedn = "dc=hashru,dc=nl";
		$dn = $rootcn . ',' . $basedn;
		$ldapbind = @ldap_bind($ds, $dn, $password);
		if (!$ldapbind) {
			if (ldap_get_option($ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) {
				var_dump("Error Binding to LDAP: $extended_error");
			}
			return false;
		}

		$searchdn = "ou=users,dc=hashru,dc=nl";
		$search_result = ldap_search($ds, $searchdn, "(objectclass=posixAccount)");
		$entries = ldap_get_entries ($ds, $search_result);
		if(!$entries) {
			throw \Cake\Error\FatalErrorException ('Failed to get relevant ldap entries') ;
		}

		$entries = $this->remove_counts($entries);

		// Then close it and return the authenticated user
		ldap_unbind ($ds) ;

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
