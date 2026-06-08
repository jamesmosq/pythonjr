<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloBDSeeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'bases-de-datos')->firstOrFail();

        $lecciones = [
            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 1 — ¿Por qué bases de datos?
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => '¿Por qué bases de datos? Del archivo al orden',
                'contenido' => <<<'MD'
# ¿Por qué bases de datos? 🗄️

Ya sabes guardar información en archivos `.txt` y `.json`. Entonces, ¿para qué existe algo más?

## El problema del archivo

Imagina que eres el director técnico de la **Liga BetPlay** y tienes que manejar:
- 20 equipos
- 600 jugadores
- 380 partidos por temporada
- 1.200 goles registrados

Con archivos `.json` tendrías algo así:

```
jugadores.json  ← 600 jugadores
equipos.json    ← 20 equipos
partidos.json   ← 380 partidos
goles.json      ← 1.200 goles
```

**Problemas:**

1. **Buscar es lento** — para encontrar todos los goles de Falcao tienes que leer los 1.200 uno a uno
2. **Datos repetidos** — en cada gol guardas `"equipo": "Nacional"`. Si cambian el nombre, tienes que cambiar 300 registros
3. **Relaciones difíciles** — ¿cómo sabes qué jugadores pertenecen a qué equipo? Tienes que cruzar dos archivos manualmente
4. **Sin control** — nada te impide guardar `"goles": "muchos"` (texto en vez de número)

## La solución: una base de datos

Una **base de datos** es un sistema organizado para guardar información relacionada de forma eficiente y segura.

```
                    BASE DE DATOS
            ┌──────────────────────────┐
            │  equipos    │  jugadores  │
            │  partidos   │  goles      │
            └─────────────┴────────────┘
                    ↑ todo conectado
```

## SQLite vs MySQL — ¿cuál usar?

Existen muchas bases de datos. Las dos más comunes para aprender son:

| | SQLite | MySQL |
|-|--------|-------|
| Instalación | Viene en Python | Servidor aparte |
| Archivo | Un `.db` en tu carpeta | Servidor escuchando en puerto 3306 |
| Para qué sirve | Apps móviles, prototipado | Webs reales, apps con usuarios |
| Quién lo usa | iOS, Android, Firefox | WordPress, Laravel, Instagram |

**Tú vas a aprender MySQL** porque ya tienes el servidor instalado en tu PC (el mismo que usa tu papá para Laravel). Eso significa que estás aprendiendo la herramienta real, no la de juguete. 💪

## Apps que usan MySQL

| App / Proyecto | Base de datos |
|----------------|--------------|
| WordPress | MySQL |
| Laravel (el framework de tu papá) | MySQL / PostgreSQL |
| Twitter (en sus inicios) | MySQL |
| YouTube (en sus inicios) | MySQL |
| Tu colegio seguramente | MySQL o SQL Server |

## MySQL Workbench — tu herramienta visual

MySQL Workbench es una aplicación para ver y manejar tus bases de datos visualmente. Es como el "explorador de archivos" pero para bases de datos.

Puedes crear bases de datos desde ahí antes de empezar a programar.

> 💡 Cuando termines este módulo, sabrás el lenguaje que usan programadores de Google, Netflix y Spotify para hablar con sus bases de datos.
MD,
            ],

            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 2 — Entidades y atributos
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 2, 'tipo' => 'teoria',
                'titulo' => 'Entidades y atributos — las piezas del rompecabezas',
                'contenido' => <<<'MD'
# Entidades y atributos 🧩

Antes de escribir una sola línea de código, debes **analizar** qué información necesitas guardar.

## ¿Qué es una entidad?

Una **entidad** es cualquier "cosa" del mundo real sobre la que quieres guardar información.

En la Liga BetPlay, las entidades principales son:

```
EQUIPO        → Nacional, América, Junior, Millos...
JUGADOR       → Falcao, James, Ospina, Cuadrado...
PARTIDO       → Nacional vs Millos el 15-marzo-2024
GOL           → El gol #47 de Falcao en el minuto 73
```

## ¿Qué son los atributos?

Los **atributos** son las características de cada entidad. Es lo que queremos saber sobre ella.

### Entidad: EQUIPO
| Atributo | Tipo MySQL | Ejemplo |
|----------|-----------|---------|
| id | INT | 1 |
| nombre | VARCHAR(100) | "Atlético Nacional" |
| ciudad | VARCHAR(100) | "Medellín" |
| estadio | VARCHAR(150) | "Atanasio Girardot" |
| año_fundacion | YEAR | 1947 |
| titulos_liga | INT | 16 |

### Entidad: JUGADOR
| Atributo | Tipo MySQL | Ejemplo |
|----------|-----------|---------|
| id | INT | 1 |
| nombre | VARCHAR(150) | "Radamel Falcao García" |
| posicion | VARCHAR(50) | "Delantero" |
| numero_camiseta | TINYINT | 9 |
| fecha_nacimiento | DATE | 1986-02-10 |
| nacionalidad | VARCHAR(80) | "Colombiana" |

## La Llave Primaria (PK) 🔑

Cada fila de una tabla necesita un **identificador único**. Eso es la **llave primaria** (`PRIMARY KEY` o `PK`).

Piénsalo como el número de cédula de cada registro — nunca se repite y nunca cambia.

```
Equipo "Nacional"  → id = 1
Equipo "América"   → id = 2
Equipo "Junior"    → id = 3
```

Aunque dos equipos tuvieran el mismo nombre, sus `id` serían diferentes. Por eso el `id` es perfecto como llave primaria.

## Tipos de datos en MySQL

| Tipo | Qué guarda | Cuándo usarlo |
|------|-----------|--------------|
| `INT` | Números enteros | ids, goles, años |
| `TINYINT` | Enteros pequeños (0–255) | número de camiseta |
| `VARCHAR(n)` | Texto variable hasta n caracteres | nombres, ciudades |
| `TEXT` | Texto largo sin límite fijo | descripciones |
| `DECIMAL(m,d)` | Números decimales exactos | precios, salarios |
| `DATE` | Fecha (YYYY-MM-DD) | nacimiento, partido |
| `DATETIME` | Fecha + hora | timestamps |
| `BOOLEAN` | Verdadero/falso (en MySQL es TINYINT(1)) | activo, titular |
MD,
            ],

            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 3 — Relaciones entre entidades
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 3, 'tipo' => 'teoria',
                'titulo' => 'Relaciones — cómo las entidades se conectan',
                'contenido' => <<<'MD'
# Relaciones entre entidades 🔗

Las bases de datos no son solo tablas aisladas — la magia está en cómo se **relacionan** entre sí.

## Los 3 tipos de relación

### 1:1 — Uno a uno
*Un registro de A se relaciona con exactamente uno de B.*

```
EQUIPO ──────── ESTADIO
Nacional         Atanasio Girardot
América          Pascual Guerrero
Junior           Metropolitano
```
Cada equipo tiene un estadio propio. Cada estadio pertenece a un equipo.

### 1:N — Uno a muchos ⭐ (la más común)
*Un registro de A se relaciona con muchos de B.*

```
EQUIPO          JUGADORES
                ┌── Falcao
Nacional ───────┤── Ospina
                └── Andrade...

                ┌── Vidal
América ────────┤── Paz
                └── ...
```
Un equipo tiene **muchos** jugadores. Pero cada jugador pertenece a **un solo** equipo.

### N:M — Muchos a muchos
*Muchos de A se relacionan con muchos de B.*

```
JUGADOR              PARTIDO
Falcao ─────────── Nacional vs Millos
Falcao ─────────── Nacional vs Junior
Ospina ─────────── Nacional vs Millos  ← mismo partido, otro jugador
Vidal ──────────── América vs Junior
```
Un jugador participa en muchos partidos. Un partido tiene muchos jugadores.

> 💡 Las relaciones N:M necesitan una **tabla intermedia** (tabla de unión). En la Liga BetPlay, los goles sirven como esa tabla entre JUGADOR y PARTIDO.

## La Llave Foránea (FK) 🗝️

Para conectar dos tablas usas una **llave foránea** (`FOREIGN KEY` o `FK`): guardas el `id` de otra tabla como columna.

```
Tabla JUGADOR:
┌─────┬────────────┬────────────┬───────────────┐
│ id  │ nombre     │ posicion   │ equipo_id     │ ← FK al equipo
├─────┼────────────┼────────────┼───────────────┤
│  1  │ Falcao     │ Delantero  │  1  (Nacional)│
│  2  │ Ospina     │ Portero    │  1  (Nacional)│
│  3  │ Vidal      │ Mediocampo │  2  (América) │
└─────┴────────────┴────────────┴───────────────┘
```

El campo `equipo_id` apunta al `id` de la tabla EQUIPOS. Así sabemos que Falcao y Ospina son del Nacional (id=1) y Vidal es del América (id=2).

**Beneficio clave:** si el Nacional cambia de nombre, solo cambias **una fila** en la tabla EQUIPOS. Los 30 jugadores siguen apuntando al id=1 correctamente.
MD,
            ],

            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 4 — DER/MER
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'El DER — el plano de tu base de datos',
                'contenido' => <<<'MD'
# El DER — el plano de tu base de datos 📐

Un arquitecto dibuja los planos antes de construir un edificio. Un programador dibuja el **DER** antes de crear una base de datos.

## ¿Qué es un DER?

**DER** = Diagrama Entidad-Relación (también llamado **MER** — Modelo Entidad-Relación).

Es un dibujo que muestra:
- Las **entidades** (tablas) que vas a crear
- Los **atributos** de cada una
- Las **relaciones** entre ellas

## Notación simplificada

```
[ENTIDAD]
  ├── atributo1 (PK)
  ├── atributo2
  └── atributo3

[ENTIDAD_A] ──1──── N──[ENTIDAD_B]
```

## DER de la Liga BetPlay

```
[EQUIPO]                    [JUGADOR]
  ├── id (PK)                 ├── id (PK)
  ├── nombre                  ├── nombre
  ├── ciudad                  ├── posicion
  ├── estadio                 ├── numero_camiseta
  └── titulos_liga     1:N    ├── fecha_nacimiento
            └──────────────── └── equipo_id (FK → EQUIPO)


[PARTIDO]                   [GOL]
  ├── id (PK)                 ├── id (PK)
  ├── fecha                   ├── minuto
  ├── equipo_local_id (FK)    ├── tipo ('normal','penalti','tiro libre')
  ├── equipo_visitante_id(FK) ├── jugador_id (FK → JUGADOR)
  └── estado                  └── partido_id (FK → PARTIDO)

EQUIPO 1:N PARTIDO (como local)
EQUIPO 1:N PARTIDO (como visitante)
JUGADOR 1:N GOL
PARTIDO 1:N GOL
```

## Pasos para crear un DER

**Paso 1 — Identificar entidades:**
¿Qué cosas importantes necesito guardar?
→ Equipo, Jugador, Partido, Gol

**Paso 2 — Definir atributos:**
¿Qué sé sobre cada entidad?
→ Un equipo tiene: nombre, ciudad, estadio...

**Paso 3 — Identificar relaciones:**
¿Cómo se conectan?
→ Un equipo *tiene* muchos jugadores (1:N)

**Paso 4 — Definir llaves:**
¿Cuál es la PK de cada entidad?
¿Qué FKs necesito para conectar las tablas?

## ¿Por qué hacer el DER primero?

Porque un error de diseño **antes de programar** cuesta 5 minutos corregir. El mismo error **después de tener 10.000 registros** puede costar días y mucha frustración. 😅

> 🏗️ En la empresa donde trabaja tu papá, los programadores hacen el DER antes de escribir una sola línea de código.
MD,
            ],

            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 5 — Del DER al código MySQL
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 5, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Del DER al código MySQL — CREATE TABLE',
                'contenido' => <<<'MD'
# Del DER al código MySQL — CREATE TABLE ⚙️

Ahora que tienes el DER dibujado, es hora de convertirlo en código SQL para MySQL.

## Paso 0 — Crear la base de datos

Antes de crear tablas, necesitas una base de datos. En MySQL Workbench o en la terminal:

```sql
CREATE DATABASE IF NOT EXISTS liga_betplay
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE liga_betplay;
```

`utf8mb4` permite emojis y caracteres especiales como tildes. 🔤

## Crear la tabla `equipos`

```sql
CREATE TABLE equipos (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nombre       VARCHAR(100) NOT NULL,
    ciudad       VARCHAR(100) NOT NULL,
    estadio      VARCHAR(150),
    titulos_liga INT          DEFAULT 0
) ENGINE=InnoDB;
```

- `AUTO_INCREMENT` → MySQL asigna el número automáticamente (1, 2, 3…)
- `PRIMARY KEY` → identificador único de cada fila
- `NOT NULL` → ese campo es obligatorio
- `DEFAULT 0` → si no especificas el valor, usa 0
- `ENGINE=InnoDB` → el motor que soporta llaves foráneas en MySQL

## Crear la tabla `jugadores`

```sql
CREATE TABLE jugadores (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    nombre           VARCHAR(150) NOT NULL,
    posicion         VARCHAR(50)  NOT NULL,
    numero_camiseta  TINYINT UNSIGNED,
    fecha_nacimiento DATE,
    nacionalidad     VARCHAR(80) DEFAULT 'Colombiana',
    equipo_id        INT,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id)
) ENGINE=InnoDB;
```

`FOREIGN KEY (equipo_id) REFERENCES equipos(id)` es la sintaxis MySQL para crear una llave foránea.

## Crear la tabla `partidos`

```sql
CREATE TABLE partidos (
    id                    INT AUTO_INCREMENT PRIMARY KEY,
    fecha                 DATE        NOT NULL,
    equipo_local_id       INT,
    equipo_visitante_id   INT,
    goles_local           TINYINT     DEFAULT 0,
    goles_visitante       TINYINT     DEFAULT 0,
    estado                VARCHAR(20) DEFAULT 'programado',
    FOREIGN KEY (equipo_local_id)     REFERENCES equipos(id),
    FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(id)
) ENGINE=InnoDB;
```

## Crear la tabla `goles`

```sql
CREATE TABLE goles (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    minuto      TINYINT UNSIGNED NOT NULL,
    tipo        VARCHAR(20) DEFAULT 'normal',
    jugador_id  INT,
    partido_id  INT,
    FOREIGN KEY (jugador_id) REFERENCES jugadores(id),
    FOREIGN KEY (partido_id) REFERENCES partidos(id)
) ENGINE=InnoDB;
```

## ⚠️ Orden de creación

Debes crear las tablas en orden: **primero las que no tienen FK, luego las que dependen de otras**.

```
1. equipos          ← no depende de nadie
2. jugadores        ← depende de equipos
3. partidos         ← depende de equipos
4. goles            ← depende de jugadores y partidos
```

## Diferencias clave MySQL vs SQLite

| | MySQL | SQLite |
|-|-------|--------|
| Auto incremento | `INT AUTO_INCREMENT` | `INTEGER PRIMARY KEY AUTOINCREMENT` |
| Tipos de texto | `VARCHAR(n)`, `TEXT` | solo `TEXT` |
| Llaves foráneas | `FOREIGN KEY (...) REFERENCES ...` | `REFERENCES ...` inline |
| Motor | `ENGINE=InnoDB` | no aplica |
| Servidor | Puerto 3306 siempre activo | archivo `.db` |
MD,
            ],

            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 6 — CRUD
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 6, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'CRUD — las 4 operaciones de toda base de datos',
                'contenido' => <<<'MD'
# CRUD — las 4 operaciones fundamentales ✏️

**CRUD** son las 4 operaciones que toda base de datos soporta:

| Letra | Inglés | SQL | ¿Qué hace? |
|-------|--------|-----|-----------|
| C | Create | `INSERT INTO` | Agregar nuevos datos |
| R | Read | `SELECT` | Consultar datos |
| U | Update | `UPDATE` | Modificar datos |
| D | Delete | `DELETE` | Eliminar datos |

## C — INSERT INTO (Crear)

```sql
-- Agregar un equipo
INSERT INTO equipos (nombre, ciudad, estadio, titulos_liga)
VALUES ('Atlético Nacional', 'Medellín', 'Atanasio Girardot', 16);

-- Agregar otro equipo
INSERT INTO equipos (nombre, ciudad, estadio, titulos_liga)
VALUES ('Deportivo Independiente Medellín', 'Medellín', 'Atanasio Girardot', 4);

-- Agregar un jugador (el equipo_id=1 es Nacional)
INSERT INTO jugadores (nombre, posicion, numero_camiseta, equipo_id)
VALUES ('Radamel Falcao García', 'Delantero', 9, 1);
```

## R — SELECT (Leer/Consultar)

```sql
-- Ver todos los equipos
SELECT * FROM equipos;

-- Ver solo nombre y ciudad
SELECT nombre, ciudad FROM equipos;

-- Ver equipos con más de 10 títulos
SELECT nombre, titulos_liga
FROM equipos
WHERE titulos_liga > 10;

-- Ver jugadores del Nacional (equipo_id = 1)
SELECT nombre, posicion, numero_camiseta
FROM jugadores
WHERE equipo_id = 1;
```

## U — UPDATE (Actualizar)

```sql
-- Falcao fue transferido al DIM (equipo_id=2)
UPDATE jugadores
SET equipo_id = 2
WHERE nombre = 'Radamel Falcao García';

-- El Nacional ganó un nuevo título
UPDATE equipos
SET titulos_liga = 17
WHERE nombre = 'Atlético Nacional';
```

## D — DELETE (Eliminar)

```sql
-- Eliminar un jugador por su id
DELETE FROM jugadores WHERE id = 5;

-- Eliminar todos los partidos cancelados
DELETE FROM partidos WHERE estado = 'cancelado';
```

## ⚠️ ¡Siempre usa WHERE en UPDATE y DELETE!

```sql
-- ❌ PELIGROSO: actualiza TODOS los jugadores
UPDATE jugadores SET equipo_id = 1;

-- ✅ CORRECTO: actualiza solo el jugador específico
UPDATE jugadores SET equipo_id = 1 WHERE id = 3;
```

Sin `WHERE`, el `UPDATE` o `DELETE` afecta **todas las filas** de la tabla. Es el error más común y más costoso en bases de datos.
MD,
            ],

            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 7 — Consultas avanzadas
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 7, 'tipo' => 'teoria',
                'titulo' => 'Consultas avanzadas — WHERE, ORDER BY y JOIN',
                'contenido' => <<<'MD'
# Consultas avanzadas 🔍

## WHERE — filtrar filas

```sql
-- Jugadores delanteros
SELECT nombre FROM jugadores WHERE posicion = 'Delantero';

-- Partidos de marzo 2024
SELECT * FROM partidos WHERE fecha LIKE '2024-03%';

-- Equipos de Medellín o Cali
SELECT nombre, ciudad FROM equipos
WHERE ciudad = 'Medellín' OR ciudad = 'Cali';

-- Jugadores colombianos delanteros
SELECT nombre FROM jugadores
WHERE posicion = 'Delantero' AND nacionalidad = 'Colombiana';
```

## ORDER BY — ordenar resultados

```sql
-- Equipos ordenados por títulos (mayor a menor)
SELECT nombre, titulos_liga FROM equipos
ORDER BY titulos_liga DESC;

-- Jugadores por número de camiseta
SELECT nombre, numero_camiseta FROM jugadores
ORDER BY numero_camiseta ASC;
```

`ASC` = ascendente (de menor a mayor), `DESC` = descendente (de mayor a menor).

## COUNT, SUM, AVG — agregar datos

```sql
-- ¿Cuántos jugadores tiene cada equipo?
SELECT equipo_id, COUNT(*) AS total_jugadores
FROM jugadores
GROUP BY equipo_id;

-- ¿Cuántos goles tiene cada jugador?
SELECT jugador_id, COUNT(*) AS total_goles
FROM goles
GROUP BY jugador_id
ORDER BY total_goles DESC;
```

## JOIN — combinar tablas ⭐

`JOIN` es el superpoder de SQL: combina dos tablas usando sus llaves.

```sql
-- Jugadores con el nombre de su equipo (no solo el equipo_id)
SELECT jugadores.nombre, equipos.nombre AS equipo
FROM jugadores
JOIN equipos ON jugadores.equipo_id = equipos.id;
```

Resultado:
```
Radamel Falcao García  |  Atlético Nacional
David Ospina           |  Atlético Nacional
Adrián Ramos           |  América de Cali
...
```

## JOIN con múltiples tablas — tabla de goleadores

```sql
-- Tabla de goleadores: nombre del jugador, equipo y total de goles
SELECT
    jugadores.nombre       AS goleador,
    equipos.nombre         AS equipo,
    COUNT(goles.id)        AS total_goles
FROM goles
JOIN jugadores ON goles.jugador_id = jugadores.id
JOIN equipos   ON jugadores.equipo_id = equipos.id
GROUP BY jugadores.id
ORDER BY total_goles DESC
LIMIT 10;
```

Este query combina 3 tablas y te da los **top 10 goleadores de la Liga** con su equipo. Exactamente lo que ves en los noticieros de fútbol. 🏆
MD,
            ],

            // ─────────────────────────────────────────────────────────────
            // LECCIÓN 8 — Python + MySQL
            // ─────────────────────────────────────────────────────────────
            [
                'orden' => 8, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Python + MySQL — todo junto desde el código',
                'contenido' => <<<'MD'
# Python + MySQL — todo junto 🐍🗄️

Ya sabes diseñar (DER) y escribir SQL. Ahora lo controlas todo desde Python usando el conector oficial de MySQL.

## Instalar el conector

Abre la terminal y escribe:

```bash
pip install mysql-connector-python
```

Solo necesitas hacerlo una vez.

## Conectar a MySQL

```python
import mysql.connector

con = mysql.connector.connect(
    host='localhost',     # el servidor MySQL está en tu mismo PC
    user='root',          # usuario (o el que tengas configurado)
    password='',          # tu contraseña de MySQL
    database='liga_betplay'
)

# cursor(dictionary=True) permite acceder por nombre de columna: fila['nombre']
cur = con.cursor(dictionary=True)
```

## Crear la base de datos (primera vez)

```python
import mysql.connector

# Primero conectamos SIN base de datos
con = mysql.connector.connect(host='localhost', user='root', password='')
cur = con.cursor()

cur.execute("CREATE DATABASE IF NOT EXISTS liga_betplay CHARACTER SET utf8mb4")
con.database = 'liga_betplay'
print("Base de datos lista")
```

## Crear las tablas

```python
cur.execute("""
    CREATE TABLE IF NOT EXISTS equipos (
        id           INT AUTO_INCREMENT PRIMARY KEY,
        nombre       VARCHAR(100) NOT NULL,
        ciudad       VARCHAR(100) NOT NULL,
        titulos_liga INT DEFAULT 0
    ) ENGINE=InnoDB
""")

cur.execute("""
    CREATE TABLE IF NOT EXISTS jugadores (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        nombre    VARCHAR(150) NOT NULL,
        posicion  VARCHAR(50)  NOT NULL,
        equipo_id INT,
        FOREIGN KEY (equipo_id) REFERENCES equipos(id)
    ) ENGINE=InnoDB
""")

con.commit()
print("Tablas creadas")
```

## Insertar datos — placeholder `%s`

En MySQL usamos `%s` (no `?` como en SQLite):

```python
# Insertar un equipo
cur.execute(
    "INSERT INTO equipos (nombre, ciudad, titulos_liga) VALUES (%s, %s, %s)",
    ("Atlético Nacional", "Medellín", 16)
)

# Insertar varios a la vez
equipos_data = [
    ("Deportivo Independiente Medellín", "Medellín", 4),
    ("América de Cali",                  "Cali",     13),
    ("Junior F.C.",                       "Barranquilla", 9),
]
cur.executemany(
    "INSERT INTO equipos (nombre, ciudad, titulos_liga) VALUES (%s, %s, %s)",
    equipos_data
)

con.commit()
```

> 💡 Los `%s` evitan **inyección SQL**, el hackeo más común de bases de datos. Siempre pasa los datos como parámetros separados, nunca los pegues directamente en el string.

## Consultar datos

```python
# Todos los equipos
cur.execute("SELECT * FROM equipos")
todos = cur.fetchall()
for equipo in todos:
    print(equipo['nombre'], '-', equipo['titulos_liga'], 'títulos')

# Con filtro
cur.execute(
    "SELECT nombre, titulos_liga FROM equipos WHERE ciudad = %s",
    ("Medellín",)
)
medellin = cur.fetchall()

# Un solo registro
cur.execute("SELECT * FROM equipos WHERE id = %s", (1,))
nacional = cur.fetchone()
print("Nacional:", nacional['nombre'])
```

## Programa completo: Liga BetPlay en MySQL

```python
import mysql.connector

def conectar():
    return mysql.connector.connect(
        host='localhost', user='root',
        password='', database='liga_betplay'
    )

con = conectar()
cur = con.cursor(dictionary=True)

# Crear tablas
cur.execute("""CREATE TABLE IF NOT EXISTS equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL, ciudad VARCHAR(100) NOT NULL,
    titulos_liga INT DEFAULT 0
) ENGINE=InnoDB""")

cur.execute("""CREATE TABLE IF NOT EXISTS jugadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL, posicion VARCHAR(50) NOT NULL,
    equipo_id INT, FOREIGN KEY (equipo_id) REFERENCES equipos(id)
) ENGINE=InnoDB""")

con.commit()

# Insertar si la tabla está vacía
cur.execute("SELECT COUNT(*) AS total FROM equipos")
if cur.fetchone()['total'] == 0:
    cur.executemany(
        "INSERT INTO equipos (nombre, ciudad, titulos_liga) VALUES (%s, %s, %s)",
        [("Atlético Nacional","Medellín",16), ("América de Cali","Cali",13)]
    )
    con.commit()

# Consultar con JOIN
cur.execute("""
    SELECT j.nombre AS jugador, e.nombre AS equipo
    FROM jugadores j
    JOIN equipos e ON j.equipo_id = e.id
    ORDER BY e.nombre
""")

print("=== JUGADORES POR EQUIPO ===")
for fila in cur.fetchall():
    print(f"  {fila['equipo']} | {fila['jugador']}")

con.close()
```

## Cerrar la conexión

```python
con.close()  # siempre al terminar
```
MD,
            ],
        ];

        foreach ($lecciones as $data) {
            Leccion::create(array_merge(['modulo_id' => $modulo->id, 'lenguaje' => 'python'], $data));
        }

        // ═══════════════════════════════════════════════════════════════
        // EJERCICIOS
        // ═══════════════════════════════════════════════════════════════
        $ejercicios = [
            // ── OBLIGATORIOS ──────────────────────────────────────────
            [
                'orden' => 1, 'tipo' => 'quiz_opcion', 'es_obligatorio' => true,
                'titulo' => '¿Cuándo usar MySQL en vez de SQLite?',
                'enunciado' => "¿Cuál de estas afirmaciones describe mejor **por qué usar MySQL en vez de SQLite**?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Piensa en qué tipo de proyectos usan cada uno: apps móviles simples vs. webs con muchos usuarios.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) SQLite es más rápido que MySQL en todos los casos', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) MySQL es un servidor que permite múltiples apps conectadas al mismo tiempo y es lo que usan WordPress y Laravel', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) MySQL no necesita instalación y viene incluido en Python', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) SQLite y MySQL usan exactamente el mismo código Python para conectarse', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué es una llave primaria?',
                'enunciado' => "En diseño de bases de datos, ¿cómo se llama el campo que **identifica de forma única** cada fila de una tabla y nunca se repite?\n\nEscribe el término en inglés tal como se usa en SQL (dos palabras).",
                'respuesta_correcta' => 'PRIMARY KEY',
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'En inglés: "llave" = KEY, "primaria/principal" = PRIMARY.',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Diseña el DER de la Liga BetPlay',
                'enunciado' => "**En papel o en VS Code como comentario**, diseña el **DER (Diagrama Entidad-Relación)** de la Liga BetPlay con estas 4 entidades:\n\n- **EQUIPO**: id, nombre, ciudad, estadio, titulos_liga\n- **JUGADOR**: id, nombre, posicion, numero_camiseta, equipo_id (FK)\n- **PARTIDO**: id, fecha, equipo_local_id (FK), equipo_visitante_id (FK), goles_local, goles_visitante\n- **GOL**: id, minuto, tipo, jugador_id (FK), partido_id (FK)\n\n**Tu entrega debe incluir:**\n1. Las 4 entidades con sus atributos listados\n2. Las relaciones entre ellas (1:N o N:M) identificadas\n3. Las llaves primarias (PK) y foráneas (FK) marcadas\n\n**Ejemplo de formato:**\n```\n[EQUIPO] ─1:N─ [JUGADOR] via jugador.equipo_id\n[JUGADOR] ─1:N─ [GOL] via gol.jugador_id\n```",
                'codigo_base' => "# DER — Liga BetPlay\n# Escribe aquí tu diagrama en formato texto\n\n# ENTIDADES:\n# [EQUIPO]\n#   ├── id (PK)\n#   ├── nombre\n#   └── ...\n\n# RELACIONES:\n# EQUIPO 1:N JUGADOR porque...\n",
                'solucion' => "# DER — Liga BetPlay\n\n# [EQUIPO]\n#   ├── id (PK)\n#   ├── nombre\n#   ├── ciudad\n#   ├── estadio\n#   └── titulos_liga\n\n# [JUGADOR]\n#   ├── id (PK)\n#   ├── nombre\n#   ├── posicion\n#   ├── numero_camiseta\n#   └── equipo_id (FK -> EQUIPO)\n\n# [PARTIDO]\n#   ├── id (PK)\n#   ├── fecha\n#   ├── equipo_local_id (FK -> EQUIPO)\n#   ├── equipo_visitante_id (FK -> EQUIPO)\n#   ├── goles_local\n#   └── goles_visitante\n\n# [GOL]\n#   ├── id (PK)\n#   ├── minuto\n#   ├── tipo\n#   ├── jugador_id (FK -> JUGADOR)\n#   └── partido_id (FK -> PARTIDO)\n\n# RELACIONES:\n# EQUIPO 1:N JUGADOR     -> un equipo tiene muchos jugadores\n# EQUIPO 1:N PARTIDO     -> un equipo juega muchos partidos (local y visitante)\n# JUGADOR 1:N GOL        -> un jugador hace muchos goles\n# PARTIDO 1:N GOL        -> un partido tiene muchos goles\n",
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Empieza por las entidades más independientes (EQUIPO) y ve hacia las que dependen de otras (GOL depende de JUGADOR y PARTIDO).',
            ],

            // ── OPCIONALES ────────────────────────────────────────────
            [
                'orden' => 4, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Crea la base de datos y tablas MySQL desde Python',
                'enunciado' => "Usando Python y `mysql-connector-python`, crea la base de datos `liga_betplay` y las 4 tablas del DER.\n\n**Pasos:**\n1. Conecta a MySQL sin especificar base de datos\n2. Crea la base de datos `liga_betplay` con `utf8mb4`\n3. Cambia la base de datos activa con `con.database = 'liga_betplay'`\n4. Crea las 4 tablas en orden: equipos → jugadores → partidos → goles\n5. Usa `CREATE TABLE IF NOT EXISTS` y `ENGINE=InnoDB`\n\nAl final imprime: `✅ Base de datos liga_betplay creada con 4 tablas`\n\n**Recuerda instalar primero:**\n```\npip install mysql-connector-python\n```",
                'codigo_base' => "import mysql.connector\n\n# Conectar sin base de datos\ncon = mysql.connector.connect(\n    host='localhost',\n    user='root',\n    password=''  # tu contraseña aqui\n)\ncur = con.cursor()\n\n# Crear la base de datos\ncur.execute(\"CREATE DATABASE IF NOT EXISTS liga_betplay CHARACTER SET utf8mb4\")\ncon.database = 'liga_betplay'\n\n# Crear tabla equipos\ncur.execute(\"\"\"\n    CREATE TABLE IF NOT EXISTS equipos (\n        id           INT AUTO_INCREMENT PRIMARY KEY,\n        nombre       VARCHAR(100) NOT NULL,\n        ciudad       VARCHAR(100) NOT NULL,\n        titulos_liga INT DEFAULT 0\n    ) ENGINE=InnoDB\n\"\"\")\n\n# Crear tabla jugadores (con FK)\n# ...\n\n# Crear tabla partidos\n# ...\n\n# Crear tabla goles\n# ...\n\ncon.commit()\ncon.close()\nprint('Listo!')\n",
                'solucion' => "import mysql.connector\n\ncon = mysql.connector.connect(host='localhost', user='root', password='')\ncur = con.cursor()\n\ncur.execute('CREATE DATABASE IF NOT EXISTS liga_betplay CHARACTER SET utf8mb4')\ncon.database = 'liga_betplay'\n\ncur.execute(\"\"\"\n    CREATE TABLE IF NOT EXISTS equipos (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        nombre VARCHAR(100) NOT NULL,\n        ciudad VARCHAR(100) NOT NULL,\n        titulos_liga INT DEFAULT 0\n    ) ENGINE=InnoDB\n\"\"\")\n\ncur.execute(\"\"\"\n    CREATE TABLE IF NOT EXISTS jugadores (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        nombre VARCHAR(150) NOT NULL,\n        posicion VARCHAR(50) NOT NULL,\n        numero_camiseta TINYINT UNSIGNED,\n        equipo_id INT,\n        FOREIGN KEY (equipo_id) REFERENCES equipos(id)\n    ) ENGINE=InnoDB\n\"\"\")\n\ncur.execute(\"\"\"\n    CREATE TABLE IF NOT EXISTS partidos (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        fecha DATE NOT NULL,\n        equipo_local_id INT,\n        equipo_visitante_id INT,\n        goles_local TINYINT DEFAULT 0,\n        goles_visitante TINYINT DEFAULT 0,\n        FOREIGN KEY (equipo_local_id) REFERENCES equipos(id),\n        FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(id)\n    ) ENGINE=InnoDB\n\"\"\")\n\ncur.execute(\"\"\"\n    CREATE TABLE IF NOT EXISTS goles (\n        id INT AUTO_INCREMENT PRIMARY KEY,\n        minuto TINYINT UNSIGNED NOT NULL,\n        tipo VARCHAR(20) DEFAULT 'normal',\n        jugador_id INT,\n        partido_id INT,\n        FOREIGN KEY (jugador_id) REFERENCES jugadores(id),\n        FOREIGN KEY (partido_id) REFERENCES partidos(id)\n    ) ENGINE=InnoDB\n\"\"\")\n\ncon.commit()\ncon.close()\nprint('Base de datos liga_betplay creada con 4 tablas')\n",
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Puedes cambiar la base de datos activa con con.database = "liga_betplay" después de crearla. Recuerda el orden: equipos primero, goles último.',
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'INSERT — Puebla la Liga BetPlay en MySQL',
                'enunciado' => "Con las tablas ya creadas en `liga_betplay`, **inserta datos reales** de la Liga BetPlay:\n\n**Equipos (mínimo 4):**\n- Atlético Nacional, Medellín, 16 títulos\n- DIM (Deportivo Independiente Medellín), Medellín, 4 títulos\n- América de Cali, Cali, 13 títulos\n- Junior F.C., Barranquilla, 9 títulos\n\n**Jugadores (mínimo 6, repartidos entre los equipos)**\n\n**Al final:** muestra todos los jugadores con su equipo usando un `JOIN`.\n\nRecuerda: en MySQL el placeholder es `%s` (no `?` como en SQLite).",
                'solucion' => "import mysql.connector\n\ncon = mysql.connector.connect(host='localhost', user='root', password='', database='liga_betplay')\ncur = con.cursor(dictionary=True)\n\nequipos = [\n    ('Atletico Nacional', 'Medellin', 16),\n    ('DIM', 'Medellin', 4),\n    ('America de Cali', 'Cali', 13),\n    ('Junior FC', 'Barranquilla', 9),\n]\ncur.executemany('INSERT INTO equipos (nombre, ciudad, titulos_liga) VALUES (%s, %s, %s)', equipos)\n\njugadores = [\n    ('Radamel Falcao', 'Delantero', 9, 1),\n    ('David Ospina', 'Portero', 1, 1),\n    ('Edwin Cardona', 'Mediocampo', 10, 2),\n    ('Adrian Ramos', 'Delantero', 9, 3),\n    ('Freddy Hinestroza', 'Delantero', 11, 4),\n    ('Carlos Bacca', 'Delantero', 9, 4),\n]\ncur.executemany('INSERT INTO jugadores (nombre, posicion, numero_camiseta, equipo_id) VALUES (%s, %s, %s, %s)', jugadores)\n\ncon.commit()\n\ncur.execute(\"\"\"\n    SELECT j.nombre AS jugador, j.posicion, e.nombre AS equipo\n    FROM jugadores j\n    JOIN equipos e ON j.equipo_id = e.id\n    ORDER BY e.nombre, j.posicion\n\"\"\")\n\nprint('=== JUGADORES POR EQUIPO ===')\nfor j in cur.fetchall():\n    print(f'  {j[\"equipo\"]} | {j[\"posicion\"]:12} | {j[\"jugador\"]}')\n\ncon.close()\n",
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Usa executemany() para insertar varios registros a la vez. Los equipo_id son asignados por AUTO_INCREMENT (1=Nacional, 2=DIM, etc.).',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'SELECT con WHERE — consultas en MySQL',
                'enunciado' => "Con la base de datos `liga_betplay` ya poblada, escribe las siguientes **5 consultas** desde Python:\n\n1. Todos los equipos ordenados por títulos (mayor a menor)\n2. Jugadores delanteros\n3. Equipos de Medellín\n4. Cuántos jugadores hay en total (usa `COUNT(*)`)\n5. El equipo con más títulos (usa `ORDER BY … LIMIT 1`)\n\nImprime los resultados de forma legible.",
                'solucion' => "import mysql.connector\n\ncon = mysql.connector.connect(host='localhost', user='root', password='', database='liga_betplay')\ncur = con.cursor(dictionary=True)\n\nprint('1. EQUIPOS POR TITULOS:')\ncur.execute('SELECT nombre, titulos_liga FROM equipos ORDER BY titulos_liga DESC')\nfor e in cur.fetchall(): print(f'   {e[\"nombre\"]}: {e[\"titulos_liga\"]}')\n\nprint('\\n2. DELANTEROS:')\ncur.execute(\"SELECT nombre FROM jugadores WHERE posicion = 'Delantero'\")\nfor j in cur.fetchall(): print(f'   {j[\"nombre\"]}')\n\nprint('\\n3. EQUIPOS DE MEDELLIN:')\ncur.execute(\"SELECT nombre FROM equipos WHERE ciudad = 'Medellin'\")\nfor e in cur.fetchall(): print(f'   {e[\"nombre\"]}')\n\nprint('\\n4. TOTAL JUGADORES:')\ncur.execute('SELECT COUNT(*) AS total FROM jugadores')\nprint(f'   {cur.fetchone()[\"total\"]} jugadores')\n\nprint('\\n5. CAMPEON HISTORICO:')\ncur.execute('SELECT nombre, titulos_liga FROM equipos ORDER BY titulos_liga DESC LIMIT 1')\ne = cur.fetchone()\nprint(f'   {e[\"nombre\"]} con {e[\"titulos_liga\"]} titulos')\n\ncon.close()\n",
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Con cursor(dictionary=True) accedes a las columnas por nombre: fila["nombre"]. Para COUNT usa SELECT COUNT(*) AS total FROM tabla.',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'JOIN — Tabla de goleadores de la Liga',
                'enunciado' => "Agrega al menos **6 goles** a la base de datos (primero necesitas al menos 1 partido) y genera la **tabla de goleadores** usando un `JOIN` entre `goles`, `jugadores` y `equipos`.\n\n**Pasos:**\n1. Inserta 1 o 2 partidos en `partidos`\n2. Inserta varios goles referenciando jugadores y partidos\n3. Consulta con JOIN + GROUP BY + COUNT para obtener la tabla de goleadores\n\n**En MySQL usa `cur.lastrowid`** para obtener el id del partido recién insertado.",
                'solucion' => "import mysql.connector\n\ncon = mysql.connector.connect(host='localhost', user='root', password='', database='liga_betplay')\ncur = con.cursor(dictionary=True)\n\n# Insertar un partido\ncur.execute(\n    \"INSERT INTO partidos (fecha, equipo_local_id, equipo_visitante_id, goles_local, goles_visitante) VALUES (%s, %s, %s, %s, %s)\",\n    ('2024-03-15', 1, 4, 3, 2)\n)\np_id = cur.lastrowid\n\n# Insertar goles (jugador_id: 1=Falcao, 5=Hinestroza, 6=Bacca)\ngoles = [\n    (23, 'normal', 1, p_id),\n    (67, 'normal', 1, p_id),\n    (89, 'normal', 1, p_id),\n    (34, 'penalti', 5, p_id),\n    (71, 'normal', 6, p_id),\n]\ncur.executemany('INSERT INTO goles (minuto, tipo, jugador_id, partido_id) VALUES (%s, %s, %s, %s)', goles)\ncon.commit()\n\n# Tabla de goleadores con JOIN 3 tablas\ncur.execute(\"\"\"\n    SELECT\n        j.nombre    AS goleador,\n        e.nombre    AS equipo,\n        COUNT(g.id) AS total_goles\n    FROM goles g\n    JOIN jugadores j ON g.jugador_id = j.id\n    JOIN equipos   e ON j.equipo_id  = e.id\n    GROUP BY j.id\n    ORDER BY total_goles DESC\n\"\"\")\n\nprint('=== TABLA DE GOLEADORES ===')\nfor i, g in enumerate(cur.fetchall(), 1):\n    print(f'{i}. {g[\"goleador\"]} ({g[\"equipo\"]}) - {g[\"total_goles\"]} goles')\n\ncon.close()\n",
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Usa cur.lastrowid DESPUÉS del INSERT del partido para obtener su id. En MySQL el placeholder es %s, no ?.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Sistema completo Liga BetPlay con MySQL',
                'enunciado' => "## Proyecto del Módulo — Liga BetPlay Digital\n\nCrea un sistema completo de gestión de la Liga BetPlay desde Python con menú interactivo, usando **MySQL** como base de datos.\n\n### Flujo del programa:\n\n```\n=== LIGA BETPLAY DIGITAL ===\n1) Ver equipos y títulos\n2) Ver jugadores por equipo\n3) Registrar nuevo jugador\n4) Registrar resultado de partido\n5) Ver tabla de goleadores\n6) Salir\n```\n\n### Requisitos técnicos:\n\n1. **Base de datos MySQL** `liga_betplay` con las 4 tablas del DER\n2. **`mysql-connector-python`** instalado con `pip`\n3. **`cursor(dictionary=True)`** para acceder por nombre de columna\n4. **Datos iniciales**: mínimo 4 equipos y 8 jugadores al arrancar (si las tablas están vacías)\n5. **Opción 1**: SELECT con ORDER BY titulos_liga DESC\n6. **Opción 2**: pide nombre del equipo y muestra sus jugadores con JOIN\n7. **Opción 3**: INSERT con validación (equipo debe existir)\n8. **Opción 4**: INSERT partido + goles de ese partido usando `cur.lastrowid`\n9. **Opción 5**: tabla de goleadores con JOIN de 3 tablas + COUNT\n10. **Persistencia**: los datos se guardan en MySQL entre ejecuciones\n\n### Diferencias con SQLite que debes recordar:\n- Placeholder: `%s` en vez de `?`\n- `cursor(dictionary=True)` para acceso por nombre\n- `cur.lastrowid` para obtener el id del último INSERT\n- Conexión con host/user/password en vez de nombre de archivo",
                'codigo_base' => "import mysql.connector\n\nDB_CONFIG = {\n    'host': 'localhost',\n    'user': 'root',\n    'password': '',  # cambia si tienes contrasena\n    'database': 'liga_betplay'\n}\n\ndef conectar():\n    return mysql.connector.connect(**DB_CONFIG)\n\ndef inicializar(con):\n    cur = con.cursor()\n    cur.execute(\"\"\"\n        CREATE TABLE IF NOT EXISTS equipos (\n            id INT AUTO_INCREMENT PRIMARY KEY,\n            nombre VARCHAR(100) NOT NULL, ciudad VARCHAR(100) NOT NULL,\n            titulos_liga INT DEFAULT 0\n        ) ENGINE=InnoDB\n    \"\"\")\n    # Crea las demás tablas...\n    con.commit()\n\n# Implementa las funciones del menú y el loop principal\n",
                'solucion' => "import mysql.connector\n\nCFG = {'host':'localhost','user':'root','password':'','database':'liga_betplay'}\n\ndef conectar():\n    return mysql.connector.connect(**CFG)\n\ndef inicializar(con):\n    cur = con.cursor()\n    cur.execute(\"\"\"CREATE TABLE IF NOT EXISTS equipos (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(100) NOT NULL, ciudad VARCHAR(100) NOT NULL, titulos_liga INT DEFAULT 0) ENGINE=InnoDB\"\"\")\n    cur.execute(\"\"\"CREATE TABLE IF NOT EXISTS jugadores (id INT AUTO_INCREMENT PRIMARY KEY, nombre VARCHAR(150) NOT NULL, posicion VARCHAR(50) NOT NULL, equipo_id INT, FOREIGN KEY (equipo_id) REFERENCES equipos(id)) ENGINE=InnoDB\"\"\")\n    cur.execute(\"\"\"CREATE TABLE IF NOT EXISTS partidos (id INT AUTO_INCREMENT PRIMARY KEY, fecha DATE NOT NULL, equipo_local_id INT, equipo_visitante_id INT, goles_local TINYINT DEFAULT 0, goles_visitante TINYINT DEFAULT 0, FOREIGN KEY (equipo_local_id) REFERENCES equipos(id), FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(id)) ENGINE=InnoDB\"\"\")\n    cur.execute(\"\"\"CREATE TABLE IF NOT EXISTS goles (id INT AUTO_INCREMENT PRIMARY KEY, minuto TINYINT UNSIGNED, tipo VARCHAR(20) DEFAULT 'normal', jugador_id INT, partido_id INT, FOREIGN KEY (jugador_id) REFERENCES jugadores(id), FOREIGN KEY (partido_id) REFERENCES partidos(id)) ENGINE=InnoDB\"\"\")\n    cur.execute('SELECT COUNT(*) FROM equipos')\n    if cur.fetchone()[0] == 0:\n        cur.executemany('INSERT INTO equipos (nombre,ciudad,titulos_liga) VALUES (%s,%s,%s)', [('Atletico Nacional','Medellin',16),('DIM','Medellin',4),('America de Cali','Cali',13),('Junior FC','Barranquilla',9)])\n        cur.executemany('INSERT INTO jugadores (nombre,posicion,equipo_id) VALUES (%s,%s,%s)', [('Falcao','Delantero',1),('Ospina','Portero',1),('Cardona','Mediocampo',2),('Ramos','Delantero',3),('Bacca','Delantero',4),('Hinestroza','Delantero',4),('Morelo','Delantero',1),('Gio','Mediocampo',1)])\n    con.commit()\n\ncon = conectar()\ninicializar(con)\n\nwhile True:\n    print('\\n=== LIGA BETPLAY DIGITAL ===')\n    print('1)Equipos  2)Jugadores  3)Nuevo jugador  4)Partido  5)Goleadores  6)Salir')\n    op = input('Opcion: ')\n    cur = con.cursor(dictionary=True)\n    if op=='1':\n        cur.execute('SELECT nombre,ciudad,titulos_liga FROM equipos ORDER BY titulos_liga DESC')\n        for e in cur.fetchall(): print(f'  {e[\"nombre\"]} ({e[\"ciudad\"]}) - {e[\"titulos_liga\"]} titulos')\n    elif op=='2':\n        nombre = input('Nombre del equipo: ')\n        cur.execute('SELECT id FROM equipos WHERE nombre LIKE %s', (f'%{nombre}%',))\n        e = cur.fetchone()\n        if not e: print('Equipo no encontrado')\n        else:\n            cur.execute('SELECT nombre,posicion FROM jugadores WHERE equipo_id=%s',(e['id'],))\n            for j in cur.fetchall(): print(f'  {j[\"posicion\"]:12} {j[\"nombre\"]}')\n    elif op=='3':\n        n=input('Nombre: '); pos=input('Posicion: '); eid=int(input('ID equipo: '))\n        cur.execute('INSERT INTO jugadores (nombre,posicion,equipo_id) VALUES (%s,%s,%s)',(n,pos,eid))\n        con.commit(); print('Jugador registrado')\n    elif op=='4':\n        f=input('Fecha (YYYY-MM-DD): '); lid=int(input('ID local: ')); vid=int(input('ID visitante: '))\n        cur.execute('INSERT INTO partidos (fecha,equipo_local_id,equipo_visitante_id) VALUES (%s,%s,%s)',(f,lid,vid))\n        con.commit(); pid=cur.lastrowid\n        while True:\n            jid=input('ID jugador que anoto (0=terminar): ')\n            if jid=='0': break\n            mn=input('Minuto: ')\n            cur.execute('INSERT INTO goles (minuto,jugador_id,partido_id) VALUES (%s,%s,%s)',(mn,jid,pid))\n        con.commit(); print('Partido registrado')\n    elif op=='5':\n        cur.execute(\"\"\"\n            SELECT j.nombre, e.nombre AS eq, COUNT(g.id) AS c\n            FROM goles g\n            JOIN jugadores j ON g.jugador_id=j.id\n            JOIN equipos e ON j.equipo_id=e.id\n            GROUP BY j.id ORDER BY c DESC LIMIT 10\"\"\")\n        for i,g in enumerate(cur.fetchall(),1):\n            print(f'  {i}. {g[\"nombre\"]} ({g[\"eq\"]}) - {g[\"c\"]} goles')\n    elif op=='6': print('Hasta luego!'); break\n    cur.close()\n\ncon.close()\n",
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Divide el programa en funciones: una por cada opción del menú. Usa cursor(dictionary=True) para acceder por nombre de columna. En MySQL el placeholder es %s, no ?.',
            ],
        ];

        foreach ($ejercicios as $data) {
            $opciones = $data['opciones'] ?? [];
            unset($data['opciones']);
            $ejercicio = Ejercicio::create(array_merge(['modulo_id' => $modulo->id], $data));
            foreach ($opciones as $op) {
                EjercicioOpcion::create(array_merge($op, ['ejercicio_id' => $ejercicio->id]));
            }
        }
    }
}
