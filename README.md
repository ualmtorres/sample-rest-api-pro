# API REST básica con Slim y MySQL

Este proyecto es una API REST simple construida usando Slim Framework y MySQL. Proporciona operaciones CRUD básicas para la gestión de productos.

## Instalación

1. Clona el repositorio:
    ```bash
    git clone https://github.com/yourusername/sample-rest-api.git
    ```

2. Navega al directorio del proyecto:
    ```bash
    cd sample-rest-api
    ```

3. Instala las dependencias usando Composer:
    ```bash
    composer install
    ```

4. Crea una base de datos MySQL e importa el archivo SQL proporcionado:
    ```sql
    CREATE DATABASE sample_api;
    USE sample_api;
    SOURCE path/to/your/sql/sample_api.sql
    ```

> NOTE
>
> El proyecto cuenta con un archivo `docker-compose.yml` que despliega toda la infraestructura necesaria para ejecutar la API. Si prefieres usar Docker, puedes omitir los pasos de instalación manual y simplemente ejecutar:
> ```bash
> docker-compose up -d
> ```
> Esto levantará un grupo de contenedores con PHP, MySQL y Nginx, y podrás acceder a la API en `http://localhost`.

5. Actualiza la configuración de la base de datos en `config.php`:

    ```php
    $config = [
    'host' => 'localhost',
    'username' => 'example',
    'password' => 'example',
    'database' => 'sample_api'
];
    ```
> NOTE
>
> Si estás usando Docker, el host debe ser `db` en lugar de `localhost`, que es el nombre del contenedor en el docker-compose.

## Uso

1. Inicia el servidor PHP integrado si no estás ejecutando el proyecto en XAMPP o Docker:
    ```bash
    php -S localhost:8000 -t public
    ```

2. Usa un cliente API como Postman o cURL para interactuar con la API.

>NOTE
>
> Si estás utilizando Docker o XAMPP, la API estará disponible en `http://localhost` y no necesitarás iniciar el servidor PHP manualmente.

### Nota

Si prefieres utilizar otro servidor como XAMPP, puedes eliminar la configuración del servidor PHP integrado del archivo `composer.json`. Simplemente elimina o comenta la sección correspondiente.

### Endpoints

La API tiene los siguientes endpoints:

- **GET /** o **GET /docs**: Obtener la documentación de la API.

- **GET /products**: Obtener todos los productos.
- **GET /products/{id}**: Obtener un producto por ID.
- **POST /products**: Crear un nuevo producto.
- **PUT /products/{id}**: Actualizar un producto por ID.
- **DELETE /products/{id}**: Eliminar un producto por ID.

### Ejemplos de Peticiones

- **GET /products**
    ```bash
    curl -X GET http://localhost:8000/sample-rest-api/product
    ```

- **POST /products**
    ```bash
    curl -X POST http://localhost:8000/sample-rest-api/product \
    -H "Content-Type: application/json" \
    -d '{"name": "Nuevo Producto", "price": 99.99}'
    ```

- **PUT /products/{id}**
    ```bash
    curl -X PUT http://localhost:8000/sample-rest-api/product/1 \
    -H "Content-Type: application/json" \
    -d '{"name": "Producto Actualizado", "price": 79.99}'
    ```

- **DELETE /products/{id}**
    ```bash
    curl -X DELETE http://localhost:8000/sample-rest-api/product/1
    ```

## Licencia

Este proyecto está licenciado bajo la Licencia CC BY-NC-ND 4.0. Esto significa que puedes compartir el proyecto siempre que cites al autor, no lo uses para fines comerciales y no realices obras derivadas.
