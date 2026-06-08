# PythonJr — Apuntes de Implementación: Contenido Curricular

> Documento generado al finalizar la fase de seeders del currículo completo.  
> Última actualización: 2026-06-08  
> Estado: **12 módulos seeded, 73 lecciones, 95 ejercicios**

---

## 1. Inventario final del currículo

| Nivel | Nombre | Slug | Lecciones | Ejercicios | Seeder |
|-------|--------|------|-----------|------------|--------|
| 1 | ¡Hola, Python! | `hola-python` | 6 | 8 | `LeccionSeeder` + `EjercicioSeeder` |
| 1 | Tomando decisiones | `tomando-decisiones` | 6 | 8 | `Modulo2Seeder` |
| 1 | Repitiendo cosas | `repitiendo-cosas` | 6 | 8 | `Modulo3Seeder` |
| 2 | Funciones mágicas | `funciones-magicas` | 6 | 8 | `Modulo4Seeder` |
| 2 | Colecciones de cosas | `colecciones-de-cosas` | 6 | 8 | `Modulo5Seeder` |
| 2 | Guardando información | `guardando-informacion` | 6 | 8 | `Modulo6Seeder` |
| 2 | Git y GitHub | `git-github` | 7 | 7 | `LeccionSeeder` + `EjercicioSeeder` |
| 3 | Bases de datos | `bases-de-datos` | 8 | 8 | `ModuloBDSeeder` |
| 3 | Objetos y clases | `objetos-y-clases` | 10 | 16 | `Modulo7Seeder` |
| 3 | Python y el internet | `python-y-el-internet` | 6 | 8 | `Modulo8Seeder` |
| 3 | ¡Hagamos un juego visual! | `hagamos-un-juego-visual` | 6 | 8 | `Modulo9Seeder` |
| 4 | Proyecto Final Personal | `proyecto-final` | 0 | 0 | pendiente |

**Total:** 73 lecciones · 95 ejercicios · 6 obligatorios por módulo promedio

---

## 2. Arquitectura de seeders

### Orden de ejecución en `DatabaseSeeder.php`

```php
$this->call([
    UserSeeder::class,
    ModuloSeeder::class,       // crea los 12 registros de módulos
    LeccionSeeder::class,      // hola-python + git-github (lecciones)
    EjercicioSeeder::class,    // hola-python + git-github (ejercicios)
    Modulo2Seeder::class,      // tomando-decisiones
    Modulo3Seeder::class,      // repitiendo-cosas
    Modulo4Seeder::class,      // funciones-magicas
    Modulo5Seeder::class,      // colecciones-de-cosas
    Modulo6Seeder::class,      // guardando-informacion
    Modulo7Seeder::class,      // objetos-y-clases (POO + 4 pilares)
    Modulo8Seeder::class,      // python-y-el-internet
    Modulo9Seeder::class,      // hagamos-un-juego-visual
    ModuloBDSeeder::class,     // bases-de-datos (MySQL)
    LogroSeeder::class,
    ProgresoSeeder::class,
    ConfiguracionSeeder::class,
]);
```

### Patrón estándar de cada seeder

```php
// 1. Buscar el módulo por slug
$modulo = Modulo::where('slug', 'mi-slug')->firstOrFail();

// 2. Array de lecciones
$lecciones = [
    [
        'orden' => 1, 'tipo' => 'teoria',
        'titulo' => '...',
        'contenido' => <<<'MD'
# Título
Contenido en markdown...
MD,
    ],
    // ...
];

foreach ($lecciones as $data) {
    Leccion::create(array_merge(['modulo_id' => $modulo->id, 'lenguaje' => 'python'], $data));
}

// 3. Array de ejercicios
$ejercicios = [
    [
        'orden' => 1, 'tipo' => 'quiz_opcion', 'es_obligatorio' => true,
        'titulo' => '...', 'enunciado' => '...',
        'respuesta_correcta' => null,
        'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
        'pista' => '...',
        'opciones' => [  // solo en quiz_opcion
            ['orden' => 1, 'texto' => 'a) ...', 'es_correcta' => false],
            ['orden' => 2, 'texto' => 'b) ...', 'es_correcta' => true],
        ],
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
```

---

## 3. Tipos de lección (`tipo`)

| Valor | Descripción |
|-------|-------------|
| `teoria` | Explicación conceptual con analogías |
| `ejemplo_codigo` | Código funcionando con explicación línea a línea |

---

## 4. Tipos de ejercicio (`tipo`)

| Valor | Descripción | `respuesta_correcta` | `opciones` |
|-------|-------------|---------------------|------------|
| `quiz_opcion` | Selección múltiple | `null` | Sí — tabla `ejercicio_opciones` |
| `quiz_texto` | Respuesta escrita exacta | String exacto | No |
| `codigo_libre` | El estudiante escribe código | `null` | No — tiene `codigo_base` y `solucion` |
| `mini_proyecto` | Proyecto al final del módulo | `null` | No — tiene `codigo_base` y `solucion` |
| `terminal_git` | Comandos de terminal Git | `null` | No |

---

## 5. Reglas de escaping PHP/Python

### Contenido de lecciones → heredoc single-quote (SEGURO)

```php
'contenido' => <<<'MD'
# Título
```python
f"Hola {nombre}"
x = f"{salario:,} COP"    # ✅ format specifiers son seguros aquí
${variable}                # ✅ $ en markdown también es seguro
```
MD,
```

PHP **no interpola** nada dentro de `<<<'MD'...MD`. Todo el Python aparece literal.

### `solucion` y `codigo_base` → string con `\n` (PRECAUCIÓN)

```php
'solucion' => "class Foo:\n    def bar(self):\n        print(f'{self.x:,}')\n",
```

**Reglas obligatorias:**
- `\n` → salto de línea ✅
- `\"` → comilla doble ✅  
- `{var:,}` → format specifier ✅ (sin `$` adelante)
- `${var:,}` → ❌ **ROMPE PHP** con `ParseError: unexpected token ":"`
- `${var}` → ❌ **ROMPE PHP** (PHP lo interpreta como variable compleja)

**Solución al bug histórico (Modulo7Seeder línea 465):**  
Python `f"${monto:,}"` dentro de PHP double-quoted → cambiado a `f"{monto:,} COP"`.

---

## 6. Módulo: Bases de datos (`bases-de-datos`)

**Seeder:** `ModuloBDSeeder.php`  
**Nivel:** 3, orden 1  
**Tecnología enseñada:** MySQL (no SQLite) — decisión tomada porque Nikolas hereda un PC de desarrollador con MySQL ya instalado, igual al stack del papá (Laravel).

### Lecciones

| # | Título | Tipo |
|---|--------|------|
| 1 | ¿Por qué bases de datos? SQLite vs MySQL | teoria |
| 2 | Entidades y atributos — tipos MySQL (`VARCHAR`, `INT`, `DATE`) | teoria |
| 3 | Relaciones 1:1, 1:N, N:M — llaves foráneas | teoria |
| 4 | El DER — el plano de tu base de datos | teoria |
| 5 | Del DER al código MySQL — `CREATE TABLE`, `ENGINE=InnoDB` | ejemplo_codigo |
| 6 | CRUD — `INSERT`, `SELECT`, `UPDATE`, `DELETE` | ejemplo_codigo |
| 7 | Consultas avanzadas — `WHERE`, `ORDER BY`, `JOIN`, `COUNT` | teoria |
| 8 | Python + MySQL — `mysql-connector-python`, `%s`, `cursor(dictionary=True)` | ejemplo_codigo |

### Diferencias MySQL vs SQLite enseñadas

| Concepto | SQLite | MySQL |
|----------|--------|-------|
| Auto incremento | `INTEGER PRIMARY KEY AUTOINCREMENT` | `INT AUTO_INCREMENT PRIMARY KEY` |
| Motor | no aplica | `ENGINE=InnoDB` |
| Texto | `TEXT` | `VARCHAR(n)` o `TEXT` |
| FK | inline `REFERENCES` | `FOREIGN KEY (...) REFERENCES ...` |
| Placeholder Python | `?` | `%s` |
| Conexión Python | `sqlite3.connect('archivo.db')` | `mysql.connector.connect(host=...)` |
| Acceso por nombre | `con.row_factory = sqlite3.Row` | `cursor(dictionary=True)` |
| Id último INSERT | `cur.lastrowid` (igual) | `cur.lastrowid` |

### Ejercicios

| # | Título | Obligatorio |
|---|--------|-------------|
| 1 | Quiz: ¿cuándo MySQL vs SQLite? | ✅ |
| 2 | Quiz texto: PRIMARY KEY | ✅ |
| 3 | Diseña el DER de la Liga BetPlay (texto) | ✅ |
| 4 | Crea tablas MySQL desde Python | opcional |
| 5 | INSERT — puebla la Liga BetPlay | opcional |
| 6 | SELECT con WHERE — 5 consultas | opcional |
| 7 | JOIN — tabla de goleadores (3 tablas) | opcional |
| 8 | PROYECTO: sistema completo con menú interactivo | opcional |

**Contexto temático:** Liga BetPlay — entidades `equipos`, `jugadores`, `partidos`, `goles`. Falcao, Ospina, DIM, Nacional, América, Junior.

---

## 7. Módulo: Objetos y clases (`objetos-y-clases`) — POO + 4 Pilares

**Seeder:** `Modulo7Seeder.php`  
**Nivel:** 3, orden 2  
**Es el módulo más grande:** 10 lecciones, 16 ejercicios, 6 obligatorios.

### Lecciones

| # | Título | Tipo |
|---|--------|------|
| 1 | ¿Qué es la POO? — analogía jugador de fútbol | teoria |
| 2 | Clases y objetos — `class`, instancias, PascalCase | ejemplo_codigo |
| 3 | `__init__` y `self` — el constructor | teoria |
| 4 | Atributos — de instancia vs de clase, `__str__` | teoria |
| 5 | Métodos — con parámetros, `CuentaBancaria` | ejemplo_codigo |
| 6 | Herencia básica — clase padre/hija, override, `super()` | teoria |
| 7 | **Pilar 1: Encapsulamiento** — `_`, `__`, `@property`, `@setter` | teoria |
| 8 | **Pilar 2: Abstracción** — interfaz pública vs. implementación privada | teoria |
| 9 | **Pilar 3: Herencia profunda** — 3 niveles, `isinstance()`, `issubclass()` | teoria |
| 10 | **Pilar 4: Polimorfismo** — duck typing, override, `__str__`/`__eq__`/`__lt__` | teoria |

### Estrategia de evaluación por pilar

```
Lección del pilar
      ↓
Quiz de validación conceptual (¿entendió?)   ← OBLIGATORIO
      ↓
Pequeño proyecto aplicado (¿puede usarlo?)   ← opcional, recompensa mayor
      ↓
      ...  (los 4 pilares)
      ↓
Proyecto integrador (¿los usa juntos?)       ← opcional, recompensa máxima
```

### Ejercicios

| # | Título | Pilar | Tipo | Obligatorio |
|---|--------|-------|------|-------------|
| 1 | Quiz: ¿qué es `self`? | Bases | quiz_opcion | ✅ |
| 2 | Quiz texto: `__init__` | Bases | quiz_texto | ✅ |
| 3 | Clase `Mascota` | Bases | codigo_libre | ✅ |
| 4 | Clase `Rectangulo` | Bases | codigo_libre | — |
| 5 | Clase `CuentaBancaria` | Bases | codigo_libre | — |
| 6 | Herencia Animal → Perro/Gato | Herencia | codigo_libre | — |
| 7 | Lista de 5 objetos + `max()` + filter | Colecciones | codigo_libre | — |
| 8 | PROYECTO: Clase `Jugador` + Clase `Equipo` | Integración | mini_proyecto | — |
| 9 | **Quiz: Encapsulamiento** — `_` vs `__` | Pilar 1 | quiz_opcion | ✅ |
| 10 | **Proyecto: `Jugador` con `__salario`** | Pilar 1 | codigo_libre | — |
| 11 | **Quiz: Abstracción** | Pilar 2 | quiz_opcion | ✅ |
| 12 | **Proyecto: `CajeroLiga`** (interfaz pública/privada) | Pilar 2 | codigo_libre | — |
| 13 | **Proyecto: herencia 3 niveles** Persona→MiembroEquipo→Jugador | Pilar 3 | codigo_libre | — |
| 14 | **Quiz: Polimorfismo** — duck typing | Pilar 4 | quiz_opcion | ✅ |
| 15 | **Proyecto: once ideal** con `habilidad_especial()` | Pilar 4 | codigo_libre | — |
| 16 | **🏆 PROYECTO FINAL POO** — Liga BetPlay con los 4 pilares | Integración | mini_proyecto | — |

### Proyecto final POO (E16) — resumen técnico

Aplica los 4 pilares con comentarios explícitos en el código base:

```python
# PILAR 1: ENCAPSULAMIENTO — Jugador.__salario con @property/@setter
# PILAR 2: ABSTRACCIÓN — PartidoLiga con interfaz simple, lógica en __privados
# PILAR 3: HERENCIA — Persona → MiembroEquipo → Jugador → Portero/Delantero
# PILAR 4: POLIMORFISMO — presentarse(), habilidad_especial(), __str__ diferente en cada clase
```

Recompensa: 5.000 pts normal / **8.000 pts perfecto** (la más alta del módulo).

---

## 8. Otros módulos (resumen)

### Modulo2Seeder — `tomando-decisiones`
**Temas:** `if/elif/else`, operadores de comparación, `and/or/not`, `random`  
**Proyecto:** piedra-papel-tijera vs la máquina  
**Contexto:** apuestas de fútbol, partido Nacional vs DIM

### Modulo3Seeder — `repitiendo-cosas`
**Temas:** `for`, `while`, `range()`, `break`, `continue`, loops anidados  
**Proyecto:** tabla de posiciones Liga BetPlay  
**Incluye:** FizzBuzz clásico como ejercicio libre

### Modulo4Seeder — `funciones-magicas`
**Temas:** `def`, parámetros, `return`, scope local/global, parámetros por defecto  
**Proyecto:** biblioteca personal de funciones utilitarias (mínimo 6)

### Modulo5Seeder — `colecciones-de-cosas`
**Temas:** listas, índices, `append/remove/pop/sort`, `for` sobre listas, diccionarios, tuplas  
**Proyecto:** inventario de videojuego con menú CRUD (`while True` + `break`)

### Modulo6Seeder — `guardando-informacion`
**Temas:** `open()/read()/write()`, `with`, JSON, `try/except`  
**Proyecto:** ranking de puntajes con JSON ordenado (`sorted(..., key=lambda x: x["puntaje"], reverse=True)`)

### Modulo8Seeder — `python-y-el-internet`
**Temas:** APIs REST, `requests`, `.json()`, manejo de errores HTTP  
**APIs usadas:** PokéAPI, Chuck Norris, Numbers API, Open-Meteo  
**Proyecto:** app del clima de Medellín (lat=6.2442, lon=-75.5812)

### Modulo9Seeder — `hagamos-un-juego-visual`
**Temas:** Python Turtle, figuras geométricas, eventos de teclado, Pygame, game loop, colisiones  
**Proyecto:** juego completo Pygame con pantalla inicio, gameplay, game over, restart

---

## 9. Comandos de mantenimiento

```bash
# Regenerar todo desde cero
php artisan migrate:fresh --seed

# Solo correr un seeder específico
php artisan db:seed --class=Modulo7Seeder

# Verificar conteos desde tinker
php artisan tinker --execute="
App\Models\Modulo::orderBy('nivel')->orderBy('orden')->get()
    ->each(fn(\$m) => print(\$m->slug.' | '.\$m->lecciones()->count().' lec | '.\$m->ejercicios()->count().' ej\n'));
"
```

---

## 10. Pendientes

| Tarea | Prioridad | Notas |
|-------|-----------|-------|
| Contenido `proyecto-final` (Nivel 4) | Media | El módulo existe en `ModuloSeeder` pero sin lecciones ni ejercicios |
| `DatabaseSeeder` — agregar `ProyectoFinalSeeder` | Media | Cuando se cree el contenido |
| Fase 6: Railway deployment | Baja | Dockerfile + GitHub Actions (ver CLAUDE.md) |

---

## 11. Decisiones de diseño tomadas

### Por qué MySQL y no SQLite en el módulo de bases de datos
Nikolas hereda un PC de desarrollador (su papá James) con MySQL ya instalado. Aprender MySQL directamente — la misma herramienta que usa Laravel — es más valioso que aprender SQLite y luego migrar. La diferencia clave para enseñar: placeholder `%s` vs `?`, `cursor(dictionary=True)`, `ENGINE=InnoDB`.

### Por qué seeders por módulo y no un único seeder
`LeccionSeeder.php` y `EjercicioSeeder.php` ya tenían 800+ líneas con los dos módulos originales. Agregar los 9 módulos restantes habría hecho el archivo inmanejable. Un seeder por módulo permite:
- Correr solo `php artisan db:seed --class=Modulo7Seeder` para probar cambios
- Localizar contenido fácilmente
- Sin riesgo de romper otros módulos al editar

### Por qué los 4 pilares de POO en el mismo módulo y no en uno aparte
El módulo `objetos-y-clases` ya cubre bases de POO (clases, objetos, `__init__`, herencia). Los 4 pilares son una profundización natural del mismo tema, no un módulo independiente. Separarlos crearía un módulo de "POO básica" sin cierre satisfactorio. Un solo módulo más completo (10 lecciones, 16 ejercicios) es mejor experiencia que dos módulos de 6 lecciones.

### Contexto temático consistente
Todo el currículo usa contexto colombiano para Nikolas (11 años, STEM, Itagüí):
- **Fútbol:** Liga BetPlay, Nacional, DIM, América, Junior, Falcao, Ospina, James
- **Geografía:** Medellín, Cali, Barranquilla, Bogotá
- **Moneda:** pesos colombianos (COP), formato `{valor:,}`
- **Lenguaje:** español informal colombiano, sin tecnicismos innecesarios
