FROM pritunl/archlinux:latest

RUN pacman -Syu --noconfirm

ARG USER_ID=439
RUN groupadd ldap -g ${USER_ID}
RUN useradd ldap -u ${USER_ID} -g ldap

#We don't have systemd so user and group ldap are not created here.
RUN pacman -S sudo openldap --noconfirm

RUN mkdir -p /var/lib/openldap/openldap-data/

RUN cp /var/lib/openldap/openldap-data/DB_CONFIG.example /var/lib/openldap/openldap-data/DB_CONFIG

RUN chown -R ldap:ldap /var/lib/openldap/openldap-data

RUN /usr/bin/slapd -u ldap -g ldap -d255 || true

# RUN chown -R ldap:ldap /etc/openldap/slapd.d

RUN slaptest -f /etc/openldap/slapd.conf -F /etc/openldap/slapd.d/ -d255 || true

RUN chown -R ldap:ldap /etc/openldap/slapd.d

# RUN /usr/bin/slapd -u ldap -g ldap -d255 && ldapadd -D "dc=hashru,dc=nl" -f /etc/openldap/hashru.nl.ldif -w hashru -x

# RUN sudo -u ldap slapindex
