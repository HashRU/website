Installation Guide:
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
