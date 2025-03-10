Configuración para levantar proyecto en local en ubuntu:

requisito tener instalado lo siguiente:

- php 8.1
- mySQL 8.0
- extensiones de php
- apache2
- activar modulo modprove:
  - sudo a2enmod rewrite
  - sudo systemctl restart apache2
- pgadmin

para levantar el proyecto primero hay que clonarlo en la carpeta compartida con el servidor web que se indica en la configuración de apache:

después hay que crear una configuración para para apache:

/etc/apache2/sites-available/nombre-de -la configuración.conf

debe quedar parecido a esto cambiando los directorio a los que corresponde y el servername puede ser localhost si no se cambia hosts:

```
<VirtualHost *:80>
    ServerAdmin pabloskiquiroz@gmail.com
    DocumentRoot /home/pablosky-cl/pruebas_tecnicas/desisproducts
    ServerName desisproducts.local

    <Directory /home/pablosky-cl/pruebas_tecnicas/desisproducts>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>

```

- reiniciar apache sudo systemctl restart apache2

/etc/hosts:

```
127.0.0.1       localhost
::1             localhost
127.0.1.1       pop-os.localdomain      pop-os
127.0.0.1       desisproducts.local
```

- reiniciar apache sudo systemctl restart apache2

agregar el usuario actual al grupo www-data:

sudo usermod -aG www-data tu-usuario

en caso de tener problema de permisos:

sudo chmod -R g+w /home/tu-carpeta-home/tu ruta raiz del proyecto

recordar agregar la pagina a apache2

modificar archivo Database.php con la información correspondiente a su usuario de mysql que va a utilizar

ingresar a pgadmin y crear la base de datos products hacer click derecho en la base de dato y restaurar desde el archivo backup-1

si hay problemas en buscar el archivo desde pgadmin, favor de mover el archivo a la ubicacion por defecto de almacenamiento de backups de pg admin, que es en linux ubuntu la ruta `/var/lib/pgadmin/storage/'usuario con el que se creo la cuenta'/` una vez agregado el archivo debiese poder hacerse el restore de la base de datos.
