# League Manager · LaLiga 2012–2013

[![Build CI](https://github.com/sergi-troya/league-manager-laravel/actions/workflows/tests.yml/badge.svg?branch=main)](https://github.com/sergi-troya/league-manager-laravel/actions/workflows/tests.yml)
[![Laravel 13](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![MySQL 8.0](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Vite 8](https://img.shields.io/badge/Vite-8-646CFF?logo=vite&logoColor=white)](https://vite.dev/)
[![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![Docker Compose](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)](https://docs.docker.com/compose/)

Aplicación web para gestionar equipos, plantillas y resultados de fútbol, y consultar una clasificación calculada a partir de los partidos registrados. Desarrollada con Laravel 13, Eloquent, Blade y Bootstrap 5, con infraestructura Docker separada del código de la aplicación.

El proyecto concentra la lógica de clasificación en `StandingsService`, protege las relaciones entre entidades y permite reconstruir el entorno y sus datos mediante migraciones y seeders autónomos. La suite contiene **22 pruebas**; la ejecución local de referencia validada en Docker registra **340 aserciones**. El badge superior muestra el estado real del pipeline de GitHub Actions.

## Funcionalidades

- Dashboard con jornadas, partidos disputados y pendientes, goles y equipos mejor clasificados.
- Clasificación dinámica con partidos jugados, victorias, empates, derrotas, goles y puntos; filtro acumulado por número de jornada.
- Indicadores visuales de Champions League, Europa League y descenso.
- Gestión de ciudades, equipos y jugadores; ficha de equipo con su plantilla.
- Consulta y edición de resultados, con validación del marcador y del vínculo entre partido y jornada.
- Ranking de goleadores con estadísticas históricas y datos de porteros vinculados a sus jugadores.

## Datos de la temporada

Los JSON incluidos en [`src/database/seeders/data/laliga-2012-2013/`](src/database/seeders/data/laliga-2012-2013/) permiten poblar la aplicación con una única ejecución de `php artisan migrate:fresh --seed`.

| Entidad | Registros |
| --- | ---: |
| Ciudades | 58 |
| Equipos | 20 |
| Jugadores | 535 |
| Jornadas | 38 |
| Partidos | 380 |
| Registros de goleadores | 239 |
| Registros de porteros | 44 |

El calendario está completo. El conjunto de resultados corresponde al corte histórico del dataset: **351 partidos con marcador completo, 29 pendientes y 1.002 goles registrados**. Los resultados pendientes conservan valores `null`; la clasificación se calcula con los partidos que tienen ambos marcadores informados. Las estadísticas individuales de goleadores y porteros proceden del dataset histórico.

[`manifest.json`](src/database/seeders/data/laliga-2012-2013/manifest.json) documenta la procedencia, los recuentos, las correcciones de datos y sus hashes. Los seeders leen los JSON versionados, resuelven las claves foráneas por los identificadores actuales y utilizan `updateOrCreate` para permitir ejecuciones repetidas.

## Arquitectura y decisiones técnicas

| Capa | Responsabilidad |
| --- | --- |
| `app/Services/StandingsService.php` | Cálculo de clasificación y estadísticas agregadas del dashboard. |
| `app/Http/Controllers/` | Coordinación de peticiones, operaciones CRUD y datos entregados a las vistas. |
| `app/Http/Requests/StandingsRequest.php` | Validación del filtro de clasificación y redirección a una URL limpia ante errores. |
| `app/Models/` | Relaciones Eloquent y acceso a entidades del dominio. |
| `database/migrations/` | Esquema en inglés, tipos, restricciones y claves foráneas. |
| `database/seeders/` | Carga autónoma e idempotente del dataset versionado. |
| `resources/views/` | Presentación con Blade, componentes y Bootstrap. |
| `tests/` | Pruebas de integridad, comportamiento HTTP y lógica de dominio. |

`StandingsService::calculate()` realiza **dos consultas**: equipos y partidos con resultado completo. Procesa los resultados en memoria, asigna tres puntos por victoria y uno por empate, y ordena por puntos, diferencia de goles y goles a favor. Un empate exacto utiliza el código de equipo como criterio estable de presentación. El filtro usa `matchdays.number` y acumula todas las jornadas anteriores o iguales a la seleccionada.

La clasificación se deriva de `games` en cada consulta. Al editar un resultado, la tabla y el podio del dashboard reflejan los nuevos puntos. Los criterios de desempate implementados y el corte del dataset están explícitos para facilitar la evaluación del comportamiento.

## Estructura del repositorio

```text
league-manager-laravel/
├── .github/
│   └── workflows/tests.yml          # CI: dependencias, build, datos y pruebas
├── .gitattributes                   # Scripts de shell con finales de línea LF
├── docker-compose.yml               # Servicios, red y volumen de MySQL
├── docker/
│   ├── nginx/default.conf           # Document root: src/public
│   ├── php/
│   │   ├── Dockerfile               # PHP-FPM 8.3, extensiones y Composer
│   │   └── entrypoint.sh            # Preparación de directorios escribibles
│   └── scripts/                     # Material legado de referencia
├── src/                             # Aplicación Laravel
│   ├── .env.example                 # Configuración de ejemplo para Docker
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Http/Requests/
│   │   ├── Models/
│   │   └── Services/StandingsService.php
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/data/laliga-2012-2013/
│   ├── resources/
│   │   ├── css/custom.css
│   │   ├── js/app.js
│   │   └── views/
│   ├── routes/web.php
│   ├── tests/
│   │   ├── Feature/
│   │   └── Unit/
│   ├── composer.json
│   ├── composer.lock
│   ├── package.json
│   ├── package-lock.json
│   ├── phpunit.xml
│   └── vite.config.js
└── README.md
```

La raíz contiene la infraestructura y esta guía. `src/` contiene la aplicación y se monta en `/var/www/html` dentro de los contenedores de PHP y Node. Nginx sirve el directorio `public` de Laravel. La instalación utiliza exclusivamente las migraciones y los seeders de `src/`.

## Instalación local con Docker

### Requisitos

- Git.
- Docker Engine con Docker Compose v2, o Docker Desktop con contenedores Linux en Windows.
- Conexión a Internet para descargar imágenes, dependencias y los recursos Bootstrap del CDN.
- Puerto local `8080` disponible.

PHP, Composer, MySQL, Nginx y Node.js se ejecutan en sus contenedores. Los ejemplos del host están preparados para **CMD de Windows**; los comandos de Composer y Artisan se ejecutan dentro del contenedor PHP.

### 1. Clonar y crear el entorno

Desde CMD:

```cmd
git clone https://github.com/sergi-troya/league-manager-laravel.git
cd league-manager-laravel
copy src\.env.example src\.env
```

En Linux o macOS, el comando equivalente de copia es `cp src/.env.example src/.env`.

`src/.env` es la fuente de configuración de Laravel y de las variables interpoladas por Compose. Los valores de ejemplo conectan PHP al servicio `mysql`, con la base `futbol` y el usuario `league_user`. `APP_KEY` comienza vacía y se genera en el paso 3.

### 2. Construir e iniciar los servicios

Ejecuta todos los comandos Compose desde la raíz del repositorio:

```cmd
docker compose --env-file src/.env config --quiet
docker compose --env-file src/.env up -d --build
docker compose --env-file src/.env ps
```

El servicio PHP espera a que MySQL esté saludable. El volumen `dbdata` conserva la base de datos entre reinicios. El servicio Node pertenece al perfil `tools` y se inicia cuando se invoca explícitamente con `run`.

### 3. Instalar Laravel y poblar la base de datos

Desde CMD, entra al contenedor:

```cmd
docker compose --env-file src/.env exec php bash
```

Dentro del contenedor, en `/var/www/html`:

```bash
composer install --prefer-dist --no-interaction
composer check-platform-reqs
php artisan key:generate
php artisan config:clear
php artisan migrate:fresh --seed
php artisan migrate:status
exit
```

> `migrate:fresh` elimina las tablas existentes antes de reconstruirlas. Este paso corresponde a la instalación inicial o al restablecimiento deliberado de los datos de demo.

### 4. Instalar dependencias de frontend y compilar

De nuevo en CMD, desde la raíz:

```cmd
docker compose --env-file src/.env run --rm node npm ci
docker compose --env-file src/.env run --rm node npm run build
```

`composer install` y `npm ci` utilizan los lockfiles versionados. Vite genera el manifiesto y los assets en `src/public/build`; Blade carga `custom.css` y `app.js` mediante `@vite`.

### 5. Verificar vistas, rutas y pruebas

Desde CMD:

```cmd
docker compose --env-file src/.env exec php bash
```

Dentro del contenedor:

```bash
php artisan config:clear
php artisan route:list
php artisan view:cache
php artisan view:clear
php artisan test
exit
```

La instalación queda lista al completar las migraciones, el build de Vite y la suite de pruebas.

| Pantalla | Dirección local |
| --- | --- |
| Dashboard | [http://localhost:8080](http://localhost:8080) |
| Clasificación | [http://localhost:8080/standings](http://localhost:8080/standings) |
| Clasificación hasta la jornada 10 | [http://localhost:8080/standings?matchday=10](http://localhost:8080/standings?matchday=10) |
| Partidos de la jornada 38 | [http://localhost:8080/matchday?matchday=38](http://localhost:8080/matchday?matchday=38) |
| Equipos y acceso a plantillas | [http://localhost:8080/teams](http://localhost:8080/teams) |
| Ciudades | [http://localhost:8080/cities](http://localhost:8080/cities) |
| Goleadores | [http://localhost:8080/scorers](http://localhost:8080/scorers) |

## Pruebas automatizadas y CI

La suite utiliza **PHPUnit 12** y contiene 21 pruebas Feature y una prueba Unit.

| Archivo | Cobertura principal |
| --- | --- |
| `PhaseTwoTest.php` | Idempotencia, límites de strings, dorsal único por equipo y edición íntegra de partidos. |
| `LeagueSeasonSeedTest.php` | Calendario, recuentos, plantillas, estadísticas y claves foráneas con IDs desplazados. |
| `PhaseThreeTest.php` | Puntos, goles, desempates, resultados pendientes, filtros, enlaces, escape HTML y dos consultas del servicio. |
| `Feature/ExampleTest.php` | Respuesta HTTP correcta del dashboard. |
| `Unit/ExampleTest.php` | Prueba básica del runner unitario. |

Dentro del contenedor PHP, la suite completa se ejecuta con:

```bash
php artisan config:clear
php artisan test
```

Para ejecutar un bloque concreto:

```bash
php artisan test --filter=PhaseThreeTest
```

La configuración local de [`src/phpunit.xml`](src/phpunit.xml) utiliza **SQLite en memoria**, junto con `RefreshDatabase` en las pruebas Feature. GitHub Actions utiliza una base **MySQL 8.0 independiente**, `league_manager_test`, para comprobar el comportamiento sobre el mismo motor que la aplicación.

El workflow [`tests.yml`](.github/workflows/tests.yml) se activa con los pushes y las pull requests dirigidas a `main` o `master`. Prepara PHP 8.3, Node.js 22 y un servicio MySQL efímero; instala dependencias, compila Vite, genera la clave de aplicación, ejecuta migraciones y seeders, compila las vistas Blade y lanza la suite.

El paso de pruebas de CI debe declarar explícitamente `APP_ENV=testing`, `SESSION_DRIVER=array`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync` y `MAIL_MAILER=array`, además de las variables de su MySQL, al ejecutar:

```bash
php artisan config:clear
php artisan test --env=testing
```

El badge enlaza a las ejecuciones del workflow y permite comprobar el resultado del commit publicado. Las credenciales del servicio de CI son de uso exclusivo de esa base efímera.

## Integridad y seguridad del entorno

- **Mínimo privilegio en MySQL.** La aplicación conecta como `league_user`, con permisos sobre `futbol.*`. El usuario de aplicación y la cuenta administrativa están separados. MySQL se comunica por la red Docker; el servicio local carece de un puerto publicado en el host. Nginx escucha en `127.0.0.1:8080`.
- **Configuración por entorno.** `src/.env` está ignorado por Git. `.env.example` contiene valores de demo y una `APP_KEY` vacía. `APP_DEBUG=false` permite presentar errores sin mostrar trazas de depuración.
- **Validación antes de escribir.** Las reglas CRUD respetan las longitudes y los límites numéricos del esquema. Los jugadores tienen dorsal único dentro de su equipo, y la edición excluye el propio registro de la comprobación de duplicidad.
- **Alcance de los recursos.** Los jugadores se resuelven dentro del equipo de la ruta. La edición de un partido comprueba que pertenece a la jornada indicada y actualiza únicamente los goles validados; los identificadores de equipo y jornada están prohibidos en el payload.
- **Claves foráneas defensivas.** El borrado de ciudades comprueba si hay equipos asociados y maneja la excepción de integridad de MySQL con un mensaje de sesión. Las restricciones de la base de datos aportan una segunda protección.
- **Rutas y presentación.** Los endpoints `show` de ciudades y jugadores están excluidos; la ficha de equipo está implementada. `StandingsRequest` valida el filtro por número de jornada. Blade escapa las salidas `{{ ... }}` y los formularios de escritura incorporan `@csrf`.
- **Compatibilidad de PHP.** La configuración de PDO selecciona `PDO::MYSQL_ATTR_SSL_CA` en PHP 8.3 y `Pdo\Mysql::ATTR_SSL_CA` a partir de PHP 8.4.
- **Finales de línea.** `.gitattributes` conserva LF en los scripts `.sh`, facilitando su ejecución en los contenedores Linux desde clones realizados en Windows.

La configuración de ejemplo está destinada a la demostración local. La concesión inicial de permisos y las credenciales de MySQL se aplican al crear un volumen nuevo; modificar esas variables posteriormente requiere ajustar también la cuenta existente.

## Uso diario y diagnóstico

Desde CMD, en la raíz:

```cmd
docker compose --env-file src/.env up -d
docker compose --env-file src/.env logs --tail=100 php nginx mysql
docker compose --env-file src/.env down
```

`down` conserva el volumen de MySQL. Para recompilar cambios de frontend, repite `docker compose --env-file src/.env run --rm node npm run build`.

Dentro del contenedor PHP, se puede volver a ejecutar `php artisan db:seed` para restaurar los valores del dataset histórico mediante los seeders idempotentes. Para las tareas Laravel usa los comandos Artisan de esta guía; la compilación se realiza en el servicio Node. Los scripts `composer setup` y `composer dev` del esqueleto Laravel requieren PHP y Node disponibles en un mismo entorno.

En Linux, ajusta `LOCAL_UID` y `LOCAL_GID` en `src/.env` a los valores de `id -u` e `id -g` si aparecen problemas de escritura en los directorios montados. El contenedor prepara `storage` y `bootstrap/cache`, y Composer, npm y Tinker utilizan directorios temporales para su configuración o caché.

## Autor y procedencia de los datos

Desarrollado por **Sergi Troya** como proyecto de portfolio de desarrollo web y backend.

- [GitHub](https://github.com/sergi-troya)
- [LinkedIn](https://linkedin.com/in/sergitroya)

El manifiesto del dataset atribuye el material histórico original a **Abdó Garcia Burguera (2013)**. La documentación de procedencia y transformación se conserva en [`manifest.json`](src/database/seeders/data/laliga-2012-2013/manifest.json).
