# Blog Api

### Requerimientos del proyecto

El proyecto únicamente utiliza la interfaz de [PSR-11](https://www.php-fig.org/psr/psr-11)

- `"psr/container": "^2.0"`
- PHP 7.4
- MySQL 8

### Inicializar

Puedes montar el proyecto de manera tradicional en tu carpeta de proyectos en donde tengas configurado algún vhost o puedes inicializarlo usando docker, docker compose ya tiene todas las configuraciones necesarias para inicializar el proyecto, no deberás hacer ninguna configuración de infraestructura.

El proyecto utiliza la función [getenv](https://www.php.net/manual/es/function.getenv.php) de PHP la cual le permite acceder a las variables de entorno donde se encuentre montado, si no correras el proyecto en un contenedor te sugiero que entres a la carpeta "config" y en el archivo local modifiques los siguientes valores.

```php
getenv('MYSQL_HOST')
getenv('MYSQL_PORT')
getenv('MYSQL_USER')
getenv('MYSQL_PASSWORD')
getenv('MYSQL_DB')
getenv('PWD_SECRET')
```

En caso de que de que si corras el proyecto desde docker únicamente clona el archivo "project.env.template" y renombralo como "project.env", en el archivo están las variables de entorno que requiere el proyecto.

```bash
MYSQL_USER=tuusuario
MYSQL_PASSWORD=tupassword
MYSQL_HOST=tuhost
MYSQL_PORT=tupuerto
MYSQL_DB=tudb
PWD_SECRET=secretoparaalmacenarcontraseñas
```

La variable "PWD_SECRET" únicamente es un "salt" que se utiliza para generar contraseñas o tokens puede ser el valor que sea.

La variable "MYSQL_HOST" no puede llevar el valor "0.0.0.0" o localhost debe llevar la ip de tu pc a nivel red local.

Antes de inicializar el proyecto con docker es importante que verifiques que tu puerto 8090 esta disponible, en caso de que no este disponible y no puedas cambiarlo, puedes cambiar el puerto del proyecto en el archivo ".env" en la variable "*`NGINX_HOST_HTTP_PORT`*" ahí podrás cambiar el valor del puerto del proyecto.

Para correr el proyecto en tu computadora únicamente debes acceder a la carpeta docker y deberás correr el siguiente comando en tu consola.

```bash
docker-compose up -d
```

Al inicializar el proyecto tendras acceso a 7 diferentes rutas las cuales son las siguientes

### HealthCheck

Es un endpoint el cual unicamente provee información de conexión con el microservicio.

```bash
GET /healthcheck
```

- Ejemplo de respuesta.

```json
{
  "php": "7.4.21"
}
```

### Register

Es un endpoint con el cual se puede registrar a un usuario.

```json
GET /register
```

- Ejemplo de request.

```json
{
    "name": "Hugo",
    "lastName": "Checo",
    "email": "perez@hugo.com",
    "password": "password",
    "roleId": 5
}
```

- Ejemplo de respuesta.

```json
{
    "message": "User created successfully.",
    "data": {
        "userId": 22,
        "name": "Hugo",
        "lastName": "Checo",
        "email": "perez@hugos.com",
        "role": {
            "roleId": 5,
            "name": "Alto"
        }
    }
}
```

### Login

```json
GET /login
```

- Ejemplo de request

```json
{
    "name": "Hugo",
    "password": "password"
}
```

- Ejemplo de respuesta

```json
{
    "message": "Login successfully.",
    "data": {
        "jwt": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoyMCwicm9sZSI6NCwiZXhwIjoxNjM4OTA3MDA1fQ.ybETXPvkf90Q87dMf3NqzUc7jrGl9mMikdMnWPDD8aU"
    }
}
```

### Posts

Es importante que para cualquiera de los siguientes endpoints se utilice el token generado en el endpoint login como un header, de lo contrario no se podrá realizar ninguna acción.

```bash
--header 'Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjoyMCwicm9sZSI6NCwiZXhwIjoxNjM4OTA3MDA1fQ.ybETXPvkf90Q87dMf3NqzUc7jrGl9mMikdMnWPDD8aU'
```

- Obtener un listado de posts

  Para ingresar a este endpoint el usuario debe tener uno de los siguientes roles 2,4,5

  Como opcionales se le pueden enviar los queryParams "page" y "pageSize"


```json
GET /posts
```

- Ejemplo de respuesta

```json
{
    "message": "Posts found.",
    "data": [
        {
            "postId": "1",
            "title": "Nuevo post",
            "description": "Esto es una descripcion",
            "createdAt": "2021-11-07 13:55:38",
            "user": {
                "userId": "20",
                "name": "Hugo",
                "role": "Alto"
            }
        },
        {
            "postId": "2",
            "title": "Actualización",
            "description": "Esto es una descripcion",
            "createdAt": "2021-11-07 13:55:55",
            "user": {
                "userId": "20",
                "name": "Hugo",
                "role": "Alto"
            }
        }
    ],
    "paginate": {
        "pageCount": 1,
        "itemCountPerPage": 20,
        "itemInPage": 2,
        "pagesInRange": [
            1
        ],
        "first": 1,
        "current": 1,
        "last": 1,
        "next": 1,
        "totalItemCount": 2
    }
}
```

- Agregar un post

  Para ingresar a este endpoint el usuario debe tener uno de los siguientes roles 3,4,5


```json
POST /posts
```

- Ejemplo de request

```json
{
    "title": "Nuevo post",
    "description": "Esta es una descripcion"
}
```

- Ejemplo de respuesta

```json
{
    "message": "Post created successfully.",
    "data": {
        "postId": 1,
        "title": "Nuevo post",
        "description": "Esta es una descripcion"
        "createdAt": "2021-11-07 12:29:03"
    },
    "paginate": []
}
```

- Actualizar un post

  Para ingresar a este endpoint el usuario debe tener uno de los siguientes roles 4,5


```json
PUT /posts/{id}
```

- Ejemplo de request

```json
{
    "title": "Actualización",
    "description": "Actualización de descripción"
}
```

- Ejemplo de respuesta

```json
{
    "message": "Post updated successfully.",
    "data": {
        "postId": 1,
        "title": "Actualización",
        "description": "Actualización de descripción",
        "createdAt": "2021-11-07 13:55:38"
    },
    "paginate": []
}
```

- Eliminar un post

  Para ingresar a este endpoint el usuario debe tener el siguiente rol 5


```json
PUT /posts/{id}
```

- Ejemplo de respuesta

```json
{
    "message": "Post deleted successfully.",
    "data": [],
    "paginate": []
}
```
