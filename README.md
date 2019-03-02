# Prueba de backend para Clickbus
Esta es la implementación del ejercicio **Cash Machine** para **Clickbus**.

El proyecto es una REST API realizada con [Symfony 4](https://symfony.com/). Esta REST API proporciona el acceso y operación a los siguientes recursos:
* usuarios
* cuentas
* transacciones

## Directorios
* **docker**: Imágenes docker para diferentes etapas del proyecto.
* **service-api**: Es el servicio REST API del ejercicio.
* **scripts**: Scripts utilitarios para el proyecto.

## Docker
Los ambientes considerados para este proyecto son:
* develop
* staging
* prod
cada uno de estos ambientes está constituido por imágenes docker.

Para más detalle de cómo utilizar estas imágenes y sus contenedores revisar [docker/README.me](docker/README.md).

## Desarrollo
Ya sea utilizando el ambiente docker para desarrollo o el ambiente del sistema opreativo, para iniciar el proyecto se requiere:
* archivo *.env*
* generación de las tablas en la base de datos
* iniciar el servidor de desarrollo

El archivo *.env* debe estar dentro de la carpeta *service-restapi*. El archivo *service-restapi/.env* se puede tomar de referencia para configurar el archivo *.env*.

La generación de las tablas se hace con el siguiente comando:
```bash
php bin/console doctrine:migrations:migrate
```

Si se está en un ambiente docker, con iniciar las imaǵenes de desarrollo se puede acceder a [http://clickbus.local][^1] para operar con la REST API. En otro caso acceder a la IP proporcionada por su ambiente.

[^1]: se necesita configurar el archivo */etc/hosts* agregando la línea ```6.6.6.11 clickbus.local```. La IP *6.6.6.11* ha sido configurada como una red para las imágenes docker. La base de datos tiene la IP *6.6.6.7*.

**Pruebas**
Para ejecutar las pruebas del proyecto ejecutar el siguiente comando:
```bash
php bin/phpunit tests
```

## Staging
**TODO**

## Producción
Este proyecto no contempla una infraestructura especifíca para el despliegue en producción. Para construir el proyecto especificamente para este ambiente se debe ejecutar:

El archivo *.env* debe contener en la variable 

```bash
# Instalar paquetes
APP_ENV=prod SYMFONY_ENV=prod composer install --no-dev --optimize-autoloader

# Limpiar cache
APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
```
