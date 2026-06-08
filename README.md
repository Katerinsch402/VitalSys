
# Sistema HIS para Centros Asistenciales de la Ciudad de Obligado

Sistema HIS para Centros Asistenciales de la Ciudad de Obligado.



## Requisitos

Debes tener instalado

- Composer >= 2.5 -> https://getcomposer.org/download/

- PHP >= 8.2 -> https://www.php.net/downloads.php


## Instalación

Primero deberas clonar el repositorio en tu maquina con los siguientes comandos:

```bash
  git clone https://github.com/Katerinsch402/VitalSys

  cd VitalSys
```
Luego ejecuta el siguiente comando para realizar la instalacion de dependencias:
```bash
  composer install
```
Una vez terminada la instalacion deberas copiar el archivo **.env.example** y pegarlo en el mismo directorio pero cambiarle el nombre a **.env**. Luego de esto deberas ejecutar el siguiente comando:
```bash
  php artisan key:generate
```

## Iniciar servidor

Ahora podemos levantar el servidor con el siguinete comando:

```bash
  php artisan serve
```
El servidor se iniciara en http://127.0.0.1:8000/, en caso contrario podremos ver la URL en la que se inicio desde nuestra terminal de comandos.