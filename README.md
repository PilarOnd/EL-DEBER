<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Instrucciones de Instalación

## Requisitos Previos

1. **PHP (versión 8.1 o superior)**
   - Descargar e instalar desde [php.net](https://www.php.net/downloads.php)
   - Asegurarse de que las extensiones necesarias estén habilitadas en php.ini:
     - OpenSSL
     - PDO
     - Mbstring
     - Tokenizer
     - XML
     - Ctype
     - JSON

2. **Composer**
   - Descargar e instalar desde [getcomposer.org](https://getcomposer.org/download/)
   - Verificar la instalación ejecutando `composer --version` en la terminal

3. **Node.js y npm**
   - Descargar e instalar desde [nodejs.org](https://nodejs.org/)
   - Verificar la instalación ejecutando:
     ```bash
     node --version
     npm --version
     ```

4. **Base de datos MySQL**
   - Descargar e instalar desde [mysql.com](https://dev.mysql.com/downloads/)
   - O usar XAMPP/WAMP que incluye MySQL

## Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd [NOMBRE_DEL_PROYECTO]
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Node.js**
   ```bash
   npm install
   ```

4. **Configurar el archivo .env**
   - Copiar el archivo `.env.example` a `.env`
   - Configurar las variables de entorno:
     - `DB_CONNECTION=mysql`
     - `DB_HOST=127.0.0.1`
     - `DB_PORT=3306`
     - `DB_DATABASE=nombre_de_la_base`
     - `DB_USERNAME=usuario`
     - `DB_PASSWORD=contraseña`

5. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

7. **Compilar assets**
   ```bash
   npm run build
   ```

## Ejecutar el Proyecto

1. **Iniciar el servidor de desarrollo**
   ```bash
   php artisan serve
   ```

2. **En otra terminal, iniciar Vite para desarrollo**
   ```bash
   npm run dev
   ```

El proyecto estará disponible en `http://localhost:8000`

## Notas Adicionales

- Asegúrate de tener los permisos correctos en los directorios `storage` y `bootstrap/cache`
- Si encuentras problemas con las dependencias, intenta:
  ```bash
  composer dump-autoload
  npm cache clean --force
  ```
- Para desarrollo, puedes usar:
  ```bash
  npm run dev
  ```
  Para producción:
  ```bash
  npm run build
  ```

## Solución de Problemas Comunes

1. **Error de permisos**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

2. **Error de dependencias**
   ```bash
   rm -rf vendor
   composer install
   ```

3. **Error de Node.js**
   ```bash
   rm -rf node_modules
   npm install
   ```

Si encuentras algún otro problema, por favor revisa los logs en `storage/logs` para más detalles.
