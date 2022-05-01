# prueba-epayco
Prueba técnica Desarrollador Backend

# Paso 1 (BD)
Crear base de datos llamada prueba_epayco, importar el SQL File prueba_epayco.sql

# Paso 2 (SOAP)
- 2.1 Ingrese a la carpeta /soap y ejecute `composer install`
- 2.2 (Opcional) Puede cambiar la configuracion del servidor SMTP o de conexión a la base de datos en la carpeta soap/config/xxxxx.php
- 2.3 Servir el soap, dentro de la carpeta soap ejecute: `php -S localhost:8080`

# Paso 3 (REST)
## Laravel
- 3.1 Ingrese a la carpeta /rest/LaravelRest y ejecute `composer install`
- 3.2 Configure el archivo .env, o sencillamente copie el contenido de .env.example en .env
- 3.3 (Opcional) Si el soap se está ejecutando en un host diferente al paso 2.3 entonces debes modificar la variable de entorno URL_SERVICES=
- 3.4 Dentro de la carpeta /rest/LaravelRest ejecute `php artisan serve`, si cambias el host y/o el puerto debes cambiarlo en los archivos de configuracion del SOAP (por el SMTP)

Puedes visitar la documentación de la API Rest Laravel en http://localhost:8000/docs
