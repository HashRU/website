FROM pritunl/archlinux:latest

RUN pacman -Syu --noconfirm

ARG USER_ID=439
RUN groupadd ldap -g ${USER_ID}
RUN useradd ldap -u ${USER_ID} -g ldap 

#We don't have systemd so user and group ldap are not created here.
RUN pacman -S sudo openldap --noconfirm

RUN rm -rf /etc/openldap
RUN mkdir /run/openldap
RUN chown -R ldap:ldap /run/openldap

RUN mkdir -p /var/lib/openldap/slapd.d
RUN mkdir -p /var/lib/openldap/ssl
RUN mv /var/lib/openldap/openldap-data/DB_CONFIG.example /var/lib/openldap/openldap-data/DB_CONFIG
RUN openssl req -new -x509 -nodes -days 365 -newkey rsa:2048 -subj "/C=NL/CN=hashru.nl" -keyout /var/lib/openldap/ssl/slapdkey.pem -out /var/lib/openldap/ssl/slapdcert.pem
RUN chown -R ldap:ldap /var/lib/openldap
