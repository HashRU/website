FROM pritunl/archlinux:latest

RUN pacman -Syu --noconfirm

RUN useradd -U ldap

#We don't have systemd so user and group ldap are not created here.
RUN pacman -S sudo openldap --noconfirm

COPY slapd.conf /etc/openldap/slapd.conf

RUN mkdir -p /var/lib/openldap/openldap-data/

RUN cp /var/lib/openldap/openldap-data/DB_CONFIG.example /var/lib/openldap/openldap-data/DB_CONFIG

RUN chown -R ldap:ldap /var/lib/openldap/openldap-data

RUN /usr/bin/slapd -u ldap -g ldap -d255 || true

RUN slaptest -f /etc/openldap/slapd.conf -F /etc/openldap/slapd.d/ -d255 || true

COPY hashru.nl.ldif hashru.nl.ldif 

RUN /usr/bin/slapd -u ldap -g ldap -d255 && ldapadd -D "dc=hashru,dc=nl" -f hashru.nl.ldif -w hashru -x

RUN sudo -u ldap slapindex

RUN /usr/bin/slapd -u ldap -g ldap


