# Imágenes docker
La carpeta *base* contiene la definición básica para proporcionar los elementos básicos para que el proyecto sea ejecutado.

Se crearon scripts para construir las diferentes imáges por ambiente. Los ambientes considerados son:
* *desarrollo*
* *staging*
* *producción*

## Desarrollo
El objetivo de este ambiente es proporcionar las herramientas necesarias para implementar las características que requiere el proyecto.

Las carpetas con las imágenes para este ambiente son:
* **dev.database**: Imagen para proporcionar el servicio de MariaDB.
* **dev.server**: Imagen con con un servidor web (nginx) con comunicación con el proyecto Symfony.

Para construir las imágenes se utiliza el script *project/path/scripts/build_docker.sh* y la variable de entorno *ENV_APP=dev*. Se ejecuta de la siguiente manera:
```bash
ENV_APP=dev sh ./scripts/build_docker.sh
```

Para iniciar el contenedor docker se ejecuta en la raíz del proyecto:
```bash
# Iniciar el contenedor de base de datos
docker run \
  -p 3306:3306 \
  --name clickbus_backend_db \
  --net clickbus_backend_network \
  --ip 6.6.6.7 \
  -t clickbus_backend_db

# Iniciar el contenedor de la aplicación
docker run \
  -v $(pwd)/service-restapi:/home/clickbus-backend/service-restapi \
  -p 80:80 \
  --link clickbus_backend_db \
  --net clickbus_backend_network \
  --ip 6.6.6.11 \
  --name clickbus_backend_app \
  -t clickbus_backend_app
```
**TODO**: Poder trabajar con el usuario host *-u $(id -u ${USER}):$(id -g ${USER})* sin problemas con los permisos.
**TODO**: Configurar que php-fpm se inicie como nginx. Por el momento hay que ejecutar dentro del contenedor
```sh
php-fpm7
```
cuando el servidor marque un error de gateway.

Para ingresar al contenedor se ejecuta:
```bash
# Conectarse al contenedor de la base de datos
docker exec -it clickbus_backend_db /bin/sh

# Conectarse al contenedor de la aplicación
docker exec -it clickbus_backend_app /bin/sh
```

**[NOTAS]**
Por el momento al trabajador en el contenedor los archivos creados pertenecen al usuario *root* y grupo *root*. Por el momento hay que hacer ejecutar en el contenedor *clickbus_backend_app*:
```bash
# Por lo general se ejecuta en el directorio de trabajo /app/
chown -R 1000:1000 *
```

## Staging
**TODO**
El objetivo de este ambiente es proporcionar acceso a las últimas características del proyecto a un equipo y ejecutar las pruebas automatizadas del proyecto.

Las carpetas con las imágenes para este ambiente son:
* **staging.database**: Imagen para proporcionar el servicio de MariaDB.
* **staging.server**: Imagen con un servidor web (nginx) con comunicación con el proyecto de Symfony.

## Producción
El objetivo de este ambiente es proporcionar las características al público.

Las carpetas con las imágenes para este ambiente son:
* **prod.build**: Imagen que sólo tiene como objetivo construir el proyecto.

Para construir la imagen se ejecuta en la carpeta raíz del proyecto:
```sh
ENV_APP=production sh ./scripts/build_docker.sh
```

En la carpeta raíz del proyecto se inicia el contenedor con:
```bash
docker run \
  -v $(pwd)/service-restapi:/home/clickbus-backend/service-restapi \
  --name clickbus_backend_prod_build \
  -t clickbus_backend_prod_build
```

```bash
# Conectarse al contenedor de la aplicación
docker exec -it clickbus_backend_prod_build /build.sh
```
