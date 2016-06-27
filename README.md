Installation Guide:

Postgres:
	Enable the php intl extension in your php.ini.

	Install postgres
	As user "postgres"
		execute "initdb --locale $LANG -E UTF8 -D '/var/lib/postgres/data'"
		"createuser --interactive"
			Enter name of role to add: cakeuser
			Shall the new role be a superuser? (y/n) n
			Shall the new role be allowed to create databases? (y/n) y
			Shall the new role be allowed to create more new roles? (y/n) n
		"createdb --username=cake cakedb
		"psql -U cake -d cakedb"
			cakedb=> \password cakeuser
			Enter new password: secret
			Enter it again: secret

	install php-pgsql extension
	enable pdo-pgsql extension in php.ini

	copy app.default.php to app.php and change database parameters.
	change $cake_root/config/app.php to connect to postgres.

ldap:
	The openldap article on wiki.archlinux is a nice start: https://wiki.archlinux.org/index.php/OpenLDAP

	use "dc=hashru,dc=nl" as root suffix
	your root dn will be the ldap administrator, the password of which will be stored in a config file.

	Also follow the instructions for ssl, and host an ldaps server instead of an ssl over ldap. So there is no way to have unsecure connections to the ldap server.

	now you can configure your ldap directory: following this guide: https://www.digitalocean.com/community/tutorials/how-to-install-and-configure-a-basic-ldap-server-on-an-ubuntu-12-04-vps
	it show how to create an organisation unit (or just group) of users and how to add entries, until we have some CRUD interface in our website which handles this for us, we should use phpldapadmin.

	your CN will be your LoginName and your password your password obviously.

	The login controller will handle authentication with help of the LdapAuthenticate.php Auth Module. you can take a look at how we achieve authentication.
