#!/bin/bash

db_user="root"
db_pass="mac1778##"
db_server="localhost"
db_name="telefonos"

# Montar el directorio donde estan los tickets
#mount /home/martin/Escritorio/Importadores_cron/Tickets /mnt/tickets/

# Eliminar los tickets viejos
rm -vRf C:\xampp\htdocs\www\TESIS\Tarifacion\scripts\Tickets\*
# Copiamos los nuevos tickets
cp -v C:\Users\marti\Desktop\Tickets\*.xml C:\xampp\htdocs\www\TESIS\Tarifacion\scripts\Tickets\*
# Limpio las tablas de la DB
#mysql -u $db_user -p$db_pass -e "USE $db_name; TRUNCATE TABLE tickets_outgoing; TRUNCATE TABLE tickets_outgoing_transfer;"
#mysql -u $db_user -p$db_pass -e "USE $db_name; TRUNCATE TABLE tickets_incoming; TRUNCATE TABLE tickets_incoming_transfer;"
# Importamos los nuevos tickets
for x in C:\xampp\htdocs\www\TESIS\Tarifacion\scripts\Tickets\*.xml
do
	echo Importando: $x
	python C:\xampp\htdocs\www\TESIS\Tarifacion\scripts\alca-mysql.py $x
done
mysql -u $db_user -p$db_pass -e "USE $db_name; DELETE FROM tickets_outgoing WHERE id='1'; DELETE FROM tickets_outgoing WHERE id='2';"

#poner mv a otra carpeta para redundancia
