# Prueba de backend para Clickbus
Esta es la implementación del ejercicio **Cash Machine** para **Clickbus**.

El proyecto está realizado con PHP7 y Symfony4.

## Directorios
* **docker**: Es el directorio para las imágenes/contenedores docker.
* **service-api**: Es el servicio REST API del ejercicio.
* **scripts**: Directorio con scripts utilitarios.

## Docker
Este proyecto utiliza imágenes docker para diferentes ambientes del proyecto. Para mayor detalle de cómo se utilizan estas visitar [contenedores docker](docker/README.md).

Para determinar el ambiente del proyecto se utiliza la variable de entorno *ENV_APP*. Los valores permitidos de *ENV_APP* son:
* *prod*
* *staging*
* *development* (por defecto en caso de no existir alguno de los valores anteriores)

Los contenedores docker se pueden crear con el siguiente comando:
```bash
sh scripts/build_docker.sh
```
El anterior script crea una red para los los contenedores docker. Para este proyecto se toman las siguientes IPs por servicio:
* Base de datos: 6.6.6.7
* Applicación: 6.6.6.11

## Desarrollo del proyecto
Debe de agregarse un archivo *.env* a la carpeta de *service-api* con la configuración de los parámetros para el proyecto.

Agregar al archivo */etc/hosts* la siguiente línea para trabajar con un nombre en lugar de la IP:
```
6.6.6.11 clickbus.local
```

Ya sea con el contenedor de desarrollo o en el ambiente local, la forma de inicializar el proyecto es con los siguientes comandos dentro del directorio *service-resapi*:
```bash
# Instalar dependencia del proyecto
composer install
```

```bash
# Iniciar la base de datos
php bin/console doctrine:migrations:migrate
```

### Pruebas
El proyecto viene con una lista de pruebas (unitarias y funcionales). Para llevarlas a cabo se ejecuta la siguiente instrucción:
```bash
# Ejecutión de las pruebas
php bin/phpunit tests
```
