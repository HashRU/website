<?php

namespace App\Auth;

use Cake\Auth\BaseAuthenticate;
use Cake\Network\Request;
use Cake\Network\Response;

class LdapAuthenticate extends BaseAuthenticate
{
    public function authenticate(Request $request, Response $response)
    {
		$username = $request->data['username'] ;
		$password = $request->data['password'] ;
		$ds = @ldap_connect('ldaps://localhost') ;
		if (!$ds) {
			throw \Cake\Error\FatalErrorException ('Unable to connect to LDAP host.') ;
		}
		ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);

		$basedn = "ou=users,dc=hashru,dc=nl";
		$dn = "cn=$username," . $basedn;
		$ldapbind = @ldap_bind($ds, $dn, $password);
		if (!$ldapbind) {
			if (ldap_get_option($ds, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error)) {
				//var_dump("Error Binding to LDAP: $extended_error");
			}
			return false;
		}

		$search_result = ldap_search($ds, $basedn, "(cn=$username)");
		$entry = ldap_first_entry ($ds, $search_result) ;
		$user = ldap_get_attributes ($ds, $entry) ;

		// Then close it and return the authenticated user
		ldap_unbind ($ds) ;
		return $user ;
	}

	public function afterIdentify() {

	}

	public function logout() {

	}

	public function implementedEvents()
	{
		return [
			'Auth.afterIdentify' => 'afterIdentify',
			'Auth.logout' => 'logout'
		];
	}
}

?>
