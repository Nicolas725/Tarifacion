#!/bin/bash

db_user="root"
db_pass=""
db_server="localhost"
db_name="telefonos"

# Montar el directorio donde estan los tickets
#mount /home/martin/Escritorio/Importadores_cron/Tickets /mnt/tickets/

# Eliminar los tickets viejos

mkdir -pv /c/xampp/htdocs/Tarifacion/scripts/Tickets_Backup/

mv -v /c/xampp/htdocs/Tarifacion/scripts/Tickets/*.xml /c/xampp/htdocs/Tarifacion/scripts/Tickets_Backup/

rm -vRf /c/xampp/htdocs/Tarifacion/scripts/Tickets/*.xml

mkdir -pv /c/xampp/htdocs/Tarifacion/scripts/Tickets/

# Copiamos los nuevos tickets
cp -v /c/Users/nico_/Desktop/Tickets/*.xml /c/xampp/htdocs/Tarifacion/scripts/Tickets/
# Limpio las tablas de la DB
#mysql -u $db_user -p$db_pass -e "USE $db_name; TRUNCATE TABLE tickets_outgoing; TRUNCATE TABLE tickets_outgoing_transfer;"
#mysql -u $db_user -p$db_pass -e "USE $db_name; TRUNCATE TABLE tickets_incoming; TRUNCATE TABLE tickets_incoming_transfer;"
# Importamos los nuevos tickets
for x in /c/xampp/htdocs/Tarifacion/scripts/Tickets/*.xml
do
	echo Importando: $x
	python /c/xampp/htdocs/Tarifacion/scripts/alca-mysql.py $x
done
#mysql -u $db_user -p$db_pass -e "USE $db_name; DELETE FROM tickets_outgoing WHERE id='1'; DELETE FROM tickets_outgoing WHERE id='2';"

#poner mv a otra carpeta para redundancia
