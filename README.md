# ⚽ LaLiga 2012/2013 Manager — Laravel & Docker

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)

Sistema integral de gestión y visualización estadística de competiciones de fútbol basado en datos históricos reales de LaLiga (Temporada 2012/2013). 

El proyecto resuelve la ingesta y transformación de datos relacionales desde volcados SQL heredados hacia una arquitectura moderna orientada a objetos en Laravel, priorizando la normalización de esquemas, la mitigación de consultas $N+1$ y la reproducibilidad en entornos de desarrollo contenerizados con Docker.

---

## 🚀 Puntos Fuertes de Ingeniería

* **Pipeline ETL & Migración Idempotente:** Scripting de extracción, transformación y carga para migrar esquemas heredados (`ciutats`, `equips`, `jugadors`, `golejadors`) hacia modelos de dominio en inglés normalizados (`cities`, `teams`, `players`, `scorers`).
* **Integridad Relacional & Codificación:** Resolución de problemas de Mojibake mediante imposición de codificación `utf8mb4` y gestión de claves foráneas con restricciones de integridad referencial.
* **Optimización de Consultas (Anti N+1):** Carga impaciente (*Eager Loading* con `with()`) en controladores y métodos de dominio (`Scorer::with('player')`, `Player::with('teamData')`) para minimizar el impacto sobre el motor de base de datos.
* **Componentes Reutilizables Blade:** Arquitectura de interfaz basada en componentes independientes (`<x-main-card>`) para el Dashboard analítico.
* **Entorno 100% Contenerizado:** Configuración multi-contenedor con Docker Compose (PHP-FPM + Nginx/Apache + MySQL 8.0) desacoplada del sistema operativo anfitrión.

---

## 🏗️ Arquitectura y Modelo de Dominio

El sistema gestiona la competición liguera mediante las siguientes entidades interconectadas:

* **Teams & Cities:** Clubes de Primera División vinculados a sus respectivas sedes geográficas (`belongsTo(City)`).
* **Players & Rosters:** 535 futbolistas asignados a sus plantillas bajo un esquema de rutas anidadas (`teams/{team}/players`).
* **Matchdays & Games:** 38 jornadas y 380 partidos disputados con control de estados (jugados/pendientes) y cómputo de goles de local y visitante.
* **Leaderboards (Scorers):** 239 anotadores registrados con estadísticas avanzadas (penaltis, minutos por gol, goles de suplente) calculados dinámicamente para el podio de máximos realizadores (Trofeo Pichichi).

---

## 🛠️ Instalación y Puesta en Marcha

### Prerrequisitos
* Docker y Docker Compose instalados en el sistema.
* Git.

### 1. Clonar el repositorio y configurar variables de entorno
```bash
git clone [https://github.com/sergi-troya/league-manager-laravel.git](https://github.com/sergi-troya/league-manager-laravel.git)
cd league-manager-laravel
cp .env.example .env
```

### 2. Levantar el entorno Docker
```bash
docker compose up -d --build
```

### 3. Instalar dependencias y generar clave de aplicación
```bash
docker compose exec php composer install
docker compose exec php php artisan key:generate
```

### 4. Pipeline de Base de Datos (Migraciones + Ingesta ETL + Seeders)
```bash
# 1. Crear el esquema relacional
docker compose exec php php artisan migrate:fresh

# 2. Cargar el volcado SQL heredado con codificación forzada utf8mb4
docker compose exec -T mysql mysql --default-character-set=utf8mb4 -u alumno -palumno futbol < docker/scripts/lliga1213.sql

# 3. Ejecutar los seeders idempotentes para transformar y poblar los datos
docker compose exec php php artisan db:seed
```

Acceso a la aplicación: **`http://localhost:8080`**

---

## 📂 Estructura del Proyecto

```text
├── app/
│   ├── Http/Controllers/    # Controladores RESTful (Player, Team, Scorer, Matchday)
│   ├── Models/              # Modelos Eloquent con relaciones y agregaciones de dominio
│   └── Providers/           # Configuración global del paginador (Bootstrap 5)
├── database/
│   ├── migrations/          # Definición del esquema DDL normalizado
│   └── seeders/             # Pipeline de transformación e inserción de datos
├── docker/                  # Configuración de contenedores y scripts SQL
├── resources/views/         # Plantillas Blade y componentes UI
└── routes/web.php           # Enrutamiento semántico y recursos anidados
```

---

## 👤 Autor

**Sergi** — Desarrollador Web  
* [LinkedIn](https://linkedin.com/in/sergitroya)
* [GitHub](https://github.com/sergi-troya)
