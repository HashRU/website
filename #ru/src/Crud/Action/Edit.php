<?php
namespace App\Crud\Action;

use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class Edit extends LdapBase
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
		'view' => 'edit',
		'viewVar' => 'item',
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
		$username = $this->_request()->query('username');

		$subject = $this->_subject();
		$subject->set(['success' => true, 'viewVar' => $this->_defaultConfig['viewVar']]);

		$ds = $this->ldap_login();

		$searchdn = "ou=users,dc=hashru,dc=nl";
		$search_result = ldap_search($ds, $searchdn, "(&(objectclass=posixAccount)(uid=$username))");
		$entries = ldap_get_entries ($ds, $search_result);
		if(!$entries) {
			throw \Cake\Error\FatalErrorException ('Failed to get relevant ldap entries') ;
		}

		$this->ldap_logout($ds);

		$entries = $this->remove_counts($entries);
		$entry = $entries[0];

		$controller = $this->_controller();
		$subject->set([$this->_defaultConfig['viewVar'] => $entry]);

		$controller->set(['success' => $subject->success, $subject->viewVar => $subject->item]);
		$this->_trigger('beforeRender', $subject);
	}

	/**
	 * Generic handler for all HTTP verbs
	 *
	 * @return void
	 */
	protected function _post()
	{
		$username = $this->_request()->data()['old_uid'];

		$subject = $this->_subject();
		$subject->set(['success' => true, 'viewVar' => $this->_defaultConfig['viewVar']]);

		$ds = $this->ldap_login();

		$searchdn = "uid=$username,ou=users,dc=hashru,dc=nl";
		$search_result = ldap_search($ds, $searchdn, "(objectclass=posixAccount)");
		$entries = ldap_get_entries ($ds, $search_result);
		if(!$entries) {
			throw \Cake\Error\FatalErrorException ('Failed to get relevant ldap entries') ;
		}
		$entries = $this->remove_counts($entries);
		$entry = $entries[0];

		//Update entry with all the values.
		foreach($this->_request()->data() as $key => $value) {
			if($key === 'old_uid') {
				continue;
			}
			if(count($value) != 1) {
				foreach($value as $key2 => $value2) {
					$entry[$key][$key2] = $value2;
				}
			} else {
				$entry[$key] = $value;
 			}
		}
		//Unset all the Int => "Key" entries.
		foreach($entry as $key => $value) {
			if(is_int($key)) {
				unset($entry[$key]);
			}
		}
		//We cannot modify DN;
		unset($entry["dn"]);

		ldap_modify($ds, $searchdn, $entry);
		if (ldap_get_option($ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) {
			var_dump("Error Binding to LDAP: $extended_error");
		}

		$this->ldap_logout($ds);

		$entry = $this->remove_counts($entry);

		$controller = $this->_controller();
		$subject->set([$this->_defaultConfig['viewVar'] => $entry]);

		$controller->set(['success' => $subject->success, $subject->viewVar => $subject->item]);
		$this->_trigger('beforeRender', $subject);
	}
}

?>
