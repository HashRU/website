<?php
namespace App\Crud\Action;

use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;

class LdapBase extends \Crud\Action\BaseAction
{
	protected function remove_counts($arr) {
		 foreach($arr as $key=>$val) {
			 if($key === "count") {
			 	unset($arr[$key]);
			} elseif(is_array($val)) {
				$arr[$key] = $this->remove_counts($arr[$key]);
			}
		}
		return $arr;
	}

	protected function ldap_login() {
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
		return $ds;
	}

	protected function ldap_logout($ds) {
		ldap_unbind ($ds) ;
	}
}
?>
