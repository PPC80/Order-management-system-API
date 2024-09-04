# SISTEMA WEB DE GESTIÓN DE PEDIDOS EN MINIMARKETS

## Requisitos Previos

Asegurarse de tener instalados los siguientes componentes en el sistema:

- PHP >= 8.x
- Composer
- MySQL o cualquier otro sistema de gestión de bases de datos compatible
- Thunder Client o Postman

## Instalación en local

1. Clonar este repositorio en la máquina local:

   ```bash
   git clone https://github.com/PPC80/Order-management-system-API.git
   cd Order-management-system-API

2. Instalar las dependencias utilizando Composer:

    ```bash
    composer install
    
3. Copiar el archivo de configuración .env.example y renombrarlo a .env:

    ```bash
    cp .env.example .env
    
4. Generar la clave de aplicación:

    ```bash
    php artisan key:generate
    
5. Configurar tu base de datos en el archivo .env.

6. Ejecutar las migraciones para crear las tablas en la base de datos:

    ```bash
    php artisan migrate
    
7. Ejecutar los seeders para poblar la base de datos con datos de ejemplo:

    ```bash
    php artisan db:seed
    
8. Iniciar el servidor de desarrollo:

    ```bash
    php artisan serve

El proyecto estará disponible en http://localhost:8000/


## Uso

Abrir Thunder Client o Postman y navegar a cualquiera de las rutas del proyecto.
Ingresar con las credenciales de usuario de prueba proporcionadas en los seeders.

## Video Explicativo

https://youtu.be/45Pgv7Z5WbA
