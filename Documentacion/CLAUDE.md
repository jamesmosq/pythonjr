# PythonJr — Plataforma de aprendizaje Python para niños
## Documento maestro para Claude Code

---

## 1. CONTEXTO DEL PROYECTO

Aplicación web educativa gamificada para que un niño de 11 años (colegio STEM, Itagüí, Colombia)
aprenda Python desde cero. El padre es desarrollador full-stack (Laravel/PHP, Flutter, NestJS)
y mantiene el proyecto.

**Dispositivos del estudiante:**
- Tablet → consume el contenido (lectura, videos embebidos, teoría, instrucciones)
- PC → practica código en VS Code con Python 3.x instalado localmente
- Celular → solo comunicación familiar, NO para la app

**Flujo de uso esperado:**
1. Niño abre la tablet → lee el módulo, ve ejemplos animados, entiende el concepto
2. Abre VS Code en el PC → escribe el código del ejercicio
3. Vuelve a la tablet → ingresa la respuesta / muestra el resultado al padre
4. Padre valida en un panel de administración → aprueba y la billetera se actualiza

---

## 2. STACK TÉCNICO

### Backend
- **Framework:** Laravel 12 (PHP 8.3)
- **Base de datos:** PostgreSQL (Railway managed)
- **Auth:** Laravel Sanctum (SPA cookie-based, primera opción por simplicidad)
- **Queue:** Laravel queues con database driver (sin Redis para simplificar en Railway)
- **Cache:** Laravel file cache (sin Redis inicial)
- **Timezone:** America/Bogota en toda la app

### Frontend
- **Tablet/navegador:** React 19 + Vite (SPA desacoplada, servida como build estático)
- **UI:** Tailwind CSS 4 + shadcn/ui
- **Estado:** Zustand (liviano, sin Redux overhead)
- **HTTP:** Axios con interceptors para Sanctum CSRF
- **Editor de código inline:** Monaco Editor (el mismo de VS Code, para mostrar ejemplos)
- **Animaciones:** Framer Motion (celebraciones de logros)

### Deployment
- **Plataforma:** Railway (plan paid del padre)
- **Backend:** Railway service con Dockerfile Laravel
- **Frontend:** Railway static site (build de Vite)
- **DB:** Railway PostgreSQL plugin
- **Dominio:** dominio propio del padre apuntando a Railway
- **CI/CD:** GitHub Actions → push a main → Railway auto-deploy

### Dev local
- **Backend:** Laravel Herd (Windows) o `php artisan serve`
- **Frontend:** `npm run dev` (Vite dev server en puerto 5173)
- **DB local:** WAMP PostgreSQL o Railway DB en remoto durante dev
- **IDE:** JetBrains PhpStorm + WebStorm

---

## 3. ARQUITECTURA DE LA APP

```
/
├── backend/          ← Laravel 12 API
│   ├── app/
│   │   ├── Models/
│   │   │   ├── User.php
│   │   │   ├── Modulo.php
│   │   │   ├── Leccion.php
│   │   │   ├── Ejercicio.php
│   │   │   ├── ProgresoModulo.php
│   │   │   ├── EjercicioCompletado.php
│   │   │   ├── Billetera.php
│   │   │   ├── TransaccionBilletera.php
│   │   │   ├── Racha.php
│   │   │   ├── Logro.php
│   │   │   └── LogroUsuario.php
│   │   ├── Http/Controllers/Api/
│   │   │   ├── AuthController.php
│   │   │   ├── ModuloController.php
│   │   │   ├── EjercicioController.php
│   │   │   ├── BilleteraController.php
│   │   │   ├── RachaController.php
│   │   │   └── Admin/
│   │   │       ├── AdminDashboardController.php
│   │   │       └── ValidacionController.php
│   │   ├── Services/
│   │   │   ├── GamificacionService.php   ← lógica central de recompensas
│   │   │   ├── BilleteraService.php
│   │   │   └── RachaService.php
│   │   └── Events/ + Listeners/
│   │       ├── EjercicioCompletadoEvent.php
│   │       └── ModuloCompletadoEvent.php
│   └── database/migrations/
│
└── frontend/         ← React 19 SPA
    ├── src/
    │   ├── pages/
    │   │   ├── Login.jsx
    │   │   ├── Dashboard.jsx          ← pantalla principal del niño
    │   │   ├── Modulo.jsx             ← contenido + teoría
    │   │   ├── Ejercicio.jsx          ← ejercicio individual
    │   │   ├── Billetera.jsx          ← historial de ganancias
    │   │   ├── Logros.jsx             ← badges y achievements
    │   │   └── admin/
    │   │       ├── AdminDashboard.jsx
    │   │       └── Validaciones.jsx   ← padre aprueba ejercicios
    │   ├── components/
    │   │   ├── layout/
    │   │   ├── gamification/
    │   │   │   ├── XPBar.jsx
    │   │   │   ├── RachaCounter.jsx
    │   │   │   ├── BilleteraWidget.jsx
    │   │   │   ├── LogroBadge.jsx
    │   │   │   └── CelebracionModal.jsx   ← animación al ganar
    │   │   ├── contenido/
    │   │   │   ├── TeoriaBloque.jsx
    │   │   │   ├── CodigoEjemplo.jsx      ← Monaco Editor readonly
    │   │   │   ├── QuizOpcion.jsx
    │   │   │   └── DesafioDelDia.jsx
    │   │   └── ui/                        ← shadcn components
    │   └── stores/
    │       ├── authStore.js
    │       ├── progresoStore.js
    │       └── billeteraStore.js
```

---

## 4. ESQUEMA DE BASE DE DATOS

```sql
-- Usuarios (padre e hijo)
CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) DEFAULT 'estudiante', -- 'estudiante' | 'admin'
  avatar VARCHAR(255),
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Módulos del curso
CREATE TABLE modulos (
  id SERIAL PRIMARY KEY,
  nivel SMALLINT NOT NULL,           -- 1=Aprendiz, 2=Explorador, 3=Constructor, 4=Boss
  orden SMALLINT NOT NULL,           -- posición dentro del nivel
  slug VARCHAR(80) UNIQUE NOT NULL,
  titulo VARCHAR(150) NOT NULL,
  descripcion TEXT,
  icono VARCHAR(50),                 -- emoji o nombre de ícono
  dias_estimados SMALLINT DEFAULT 7,
  recompensa_base INTEGER NOT NULL,  -- en pesos COP
  activo BOOLEAN DEFAULT true,
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Lecciones dentro de cada módulo (bloques de teoría)
CREATE TABLE lecciones (
  id SERIAL PRIMARY KEY,
  modulo_id INTEGER REFERENCES modulos(id),
  orden SMALLINT NOT NULL,
  tipo VARCHAR(30) NOT NULL,  -- 'teoria' | 'ejemplo_codigo' | 'video' | 'tip'
  titulo VARCHAR(200),
  contenido TEXT NOT NULL,    -- Markdown con bloques de código
  lenguaje VARCHAR(20) DEFAULT 'python',
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Ejercicios de cada módulo
CREATE TABLE ejercicios (
  id SERIAL PRIMARY KEY,
  modulo_id INTEGER REFERENCES modulos(id),
  orden SMALLINT NOT NULL,
  tipo VARCHAR(30) NOT NULL,
    -- 'quiz_opcion'     → múltiple opción, se verifica automático
    -- 'quiz_texto'      → respuesta corta, se verifica automático
    -- 'codigo_libre'    → escribe código en PC, padre valida
    -- 'mini_proyecto'   → proyecto completo, padre valida
    -- 'desafio_dia'     → ejercicio especial diario
  titulo VARCHAR(200) NOT NULL,
  enunciado TEXT NOT NULL,     -- Markdown
  codigo_base TEXT,            -- código inicial que se le da al niño
  solucion TEXT,               -- solución oculta (solo admin ve)
  respuesta_correcta TEXT,     -- para quiz_opcion y quiz_texto
  es_obligatorio BOOLEAN DEFAULT true,
  recompensa_ejercicio INTEGER DEFAULT 2000,  -- COP
  recompensa_perfecto INTEGER DEFAULT 3000,   -- si lo hace al primer intento
  pista TEXT,                  -- hint desbloqueable
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Opciones de múltiple opción
CREATE TABLE ejercicio_opciones (
  id SERIAL PRIMARY KEY,
  ejercicio_id INTEGER REFERENCES ejercicios(id),
  texto TEXT NOT NULL,
  es_correcta BOOLEAN DEFAULT false,
  orden SMALLINT
);

-- Progreso por módulo del estudiante
CREATE TABLE progreso_modulos (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id),
  modulo_id INTEGER REFERENCES modulos(id),
  estado VARCHAR(20) DEFAULT 'bloqueado',
    -- 'bloqueado' | 'disponible' | 'en_progreso' | 'completado'
  iniciado_at TIMESTAMPTZ,
  completado_at TIMESTAMPTZ,
  bono_velocidad_aplicado BOOLEAN DEFAULT false,
  UNIQUE(user_id, modulo_id)
);

-- Ejercicios completados
CREATE TABLE ejercicios_completados (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id),
  ejercicio_id INTEGER REFERENCES ejercicios(id),
  modulo_id INTEGER REFERENCES modulos(id),
  intento SMALLINT DEFAULT 1,
  respuesta_dada TEXT,
  es_correcto BOOLEAN DEFAULT false,
  es_perfecto BOOLEAN DEFAULT false,   -- correcto al primer intento
  validado_por_padre BOOLEAN DEFAULT false,
  recompensa_ganada INTEGER DEFAULT 0,
  completado_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(user_id, ejercicio_id)        -- un registro por ejercicio
);

-- Billetera virtual
CREATE TABLE billetera (
  id SERIAL PRIMARY KEY,
  user_id INTEGER UNIQUE REFERENCES users(id),
  saldo_total INTEGER DEFAULT 0,       -- COP acumulado total (nunca baja)
  saldo_pendiente INTEGER DEFAULT 0,   -- ganado pero no pagado aún
  saldo_pagado INTEGER DEFAULT 0,      -- ya pagado por el padre
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Historial de transacciones de la billetera
CREATE TABLE transacciones_billetera (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id),
  tipo VARCHAR(40) NOT NULL,
    -- 'modulo_base' | 'ejercicio' | 'perfecto' | 'racha_3d' |
    -- 'racha_7d' | 'velocidad_modulo' | 'velocidad_nivel' |
    -- 'desafio_dia' | 'bono_nivel' | 'pago_padre'
  descripcion VARCHAR(255),
  monto INTEGER NOT NULL,              -- positivo=ganancia, negativo=pago
  referencia_id INTEGER,               -- id del módulo, ejercicio, etc.
  referencia_tipo VARCHAR(50),
  created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Control de rachas
CREATE TABLE rachas (
  id SERIAL PRIMARY KEY,
  user_id INTEGER UNIQUE REFERENCES users(id),
  dias_actuales SMALLINT DEFAULT 0,
  dias_maximos SMALLINT DEFAULT 0,
  ultima_actividad_at DATE,
  racha_3d_cobrada BOOLEAN DEFAULT false,   -- reset al romperse
  racha_7d_cobrada BOOLEAN DEFAULT false,
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Logros / Badges
CREATE TABLE logros (
  id SERIAL PRIMARY KEY,
  slug VARCHAR(80) UNIQUE NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  descripcion TEXT,
  icono VARCHAR(100),
  tipo VARCHAR(30),   -- 'progreso' | 'velocidad' | 'constancia' | 'perfeccion' | 'especial'
  condicion_valor INTEGER  -- ej: 5 para "5 ejercicios perfectos"
);

-- Logros desbloqueados por usuario
CREATE TABLE logros_usuario (
  id SERIAL PRIMARY KEY,
  user_id INTEGER REFERENCES users(id),
  logro_id INTEGER REFERENCES logros(id),
  desbloqueado_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(user_id, logro_id)
);

-- Desafío del día
CREATE TABLE desafios_dia (
  id SERIAL PRIMARY KEY,
  ejercicio_id INTEGER REFERENCES ejercicios(id),
  fecha DATE UNIQUE NOT NULL,
  recompensa INTEGER DEFAULT 5000
);
```

---

## 5. API ENDPOINTS

### Auth
```
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/me
```

### Estudiante
```
GET    /api/modulos                          ← lista con estado del usuario
GET    /api/modulos/{slug}                   ← detalle + lecciones + ejercicios
GET    /api/modulos/{slug}/ejercicios        ← ejercicios del módulo
POST   /api/ejercicios/{id}/intentar         ← enviar respuesta
GET    /api/billetera                        ← saldo + historial
GET    /api/racha                            ← estado de racha actual
GET    /api/logros                           ← logros desbloqueados y pendientes
GET    /api/desafio-del-dia                  ← ejercicio especial de hoy
POST   /api/desafio-del-dia/intentar
GET    /api/dashboard                        ← resumen completo para la home
```

### Admin (padre)
```
GET    /api/admin/dashboard                  ← progreso general del hijo
GET    /api/admin/pendientes                 ← ejercicios esperando validación
POST   /api/admin/validar/{completado_id}    ← aprobar ejercicio de código libre
POST   /api/admin/pagar                      ← registrar pago físico
GET    /api/admin/billetera                  ← historial completo
PUT    /api/admin/modulos/{id}               ← editar contenido de módulo
POST   /api/admin/modulos/{id}/publicar      ← desbloquear módulo para el niño
```

### Respuesta estándar de la API
```json
{
  "success": true,
  "data": { ... },
  "message": "Ejercicio completado",
  "meta": {
    "recompensa_ganada": 3000,
    "es_perfecto": true,
    "nuevo_logro": { "slug": "primer-perfecto", "nombre": "¡Tiro perfecto!" },
    "racha_actualizada": 4,
    "billetera_total": 48000
  }
}
```

---

## 6. SERVICIO DE GAMIFICACIÓN (GamificacionService)

Este es el corazón del sistema. Se llama cada vez que un ejercicio es completado.

```php
// Pseudocódigo de la lógica principal
class GamificacionService {

  public function procesarEjercicioCompletado(User $user, Ejercicio $ejercicio, bool $esPerfecto): array {
    $recompensas = [];

    // 1. Recompensa base del ejercicio
    $monto = $esPerfecto ? $ejercicio->recompensa_perfecto : $ejercicio->recompensa_ejercicio;
    $this->billeteraService->acreditar($user, $monto, 'ejercicio', $ejercicio->id);
    $recompensas[] = ['tipo' => 'ejercicio', 'monto' => $monto];

    // 2. Actualizar racha
    $rachaInfo = $this->rachaService->registrarActividad($user);
    if ($rachaInfo['bono']) {
      $this->billeteraService->acreditar($user, $rachaInfo['bono'], $rachaInfo['tipo']);
      $recompensas[] = ['tipo' => $rachaInfo['tipo'], 'monto' => $rachaInfo['bono']];
    }

    // 3. ¿Completó el módulo?
    if ($this->moduloCompleto($user, $ejercicio->modulo)) {
      $modulo = $ejercicio->modulo;
      $this->billeteraService->acreditar($user, $modulo->recompensa_base, 'modulo_base', $modulo->id);
      $recompensas[] = ['tipo' => 'modulo_base', 'monto' => $modulo->recompensa_base];

      // 3a. ¿Bono de velocidad de módulo?
      if ($this->enTiempoRapido($user, $modulo)) {
        $this->billeteraService->acreditar($user, 10000, 'velocidad_modulo', $modulo->id);
        $recompensas[] = ['tipo' => 'velocidad_modulo', 'monto' => 10000];
      }

      // 3b. ¿Completó el nivel completo rápido?
      if ($this->nivelCompletoRapido($user, $modulo->nivel)) {
        $this->billeteraService->acreditar($user, 25000, 'velocidad_nivel');
        $recompensas[] = ['tipo' => 'velocidad_nivel', 'monto' => 25000];
      }

      // 3c. Desbloquear siguiente módulo
      $this->desbloquearSiguienteModulo($user, $modulo);
    }

    // 4. Verificar logros nuevos
    $nuevosLogros = $this->verificarLogros($user);

    return ['recompensas' => $recompensas, 'nuevos_logros' => $nuevosLogros];
  }

  private function verificarLogros(User $user): array { ... }
}
```

### RachaService — reglas de racha
```php
public function registrarActividad(User $user): array {
  $racha = $user->racha;
  $hoy = now()->toDateString();

  if ($racha->ultima_actividad_at === $hoy) {
    return ['bono' => 0]; // ya registró actividad hoy
  }

  $ayer = now()->subDay()->toDateString();
  if ($racha->ultima_actividad_at === $ayer) {
    $racha->dias_actuales++;
  } else {
    // Racha rota — reiniciar sin penalización de dinero
    $racha->dias_actuales = 1;
    $racha->racha_3d_cobrada = false;
    $racha->racha_7d_cobrada = false;
  }

  $racha->ultima_actividad_at = $hoy;
  $racha->dias_maximos = max($racha->dias_maximos, $racha->dias_actuales);
  $racha->save();

  // Bonos de racha
  if ($racha->dias_actuales >= 3 && !$racha->racha_3d_cobrada) {
    $racha->racha_3d_cobrada = true;
    $racha->save();
    return ['bono' => 8000, 'tipo' => 'racha_3d'];
  }

  if ($racha->dias_actuales >= 7 && !$racha->racha_7d_cobrada) {
    $racha->racha_7d_cobrada = true;
    $racha->save();
    return ['bono' => 20000, 'tipo' => 'racha_7d'];
  }

  return ['bono' => 0];
}
```

---

## 7. CURRICULUM COMPLETO — 9 MÓDULOS + PROYECTO FINAL

Basado en estándares PyCrafters (OpenEDG Python Institute) adaptados para niños 11-14 años
latinoamericanos. Tiempo estimado total: 4-5 meses a ritmo normal.

---

### NIVEL 1 — APRENDIZ ($15.000 por módulo)

#### Módulo 1 — "¡Hola, Python!" (7 días estimados)
**Conceptos:** variables, tipos de datos, print(), input(), comentarios, f-strings básicos
**Proyecto:** Calculadora de edad — el niño ingresa su año de nacimiento y el programa
             calcula cuántos años tiene y en qué año cumple 18 y 21.

**Lecciones:**
1. ¿Qué es Python y por qué es cool? (historia, quién lo usa: Google, NASA, Netflix)
2. Tu primer programa: `print("¡Hola, me llamo Santiago!")`
3. Variables — las cajas de guardar cosas
4. Tipos de datos: números, texto (strings), verdadero/falso
5. Pedirle información al usuario con `input()`
6. F-strings: mezclar texto con variables

**Ejercicios obligatorios (3):**
```
E1.1 [quiz_opcion] ¿Qué hace este código?
     print("Hola" + " " + "mundo")
     a) Error   b) "Hola mundo"   c) "Hola+mundo"   d) nada
     → Respuesta: b

E1.2 [quiz_texto] ¿Cómo se llama la función para mostrar texto en pantalla?
     → Respuesta: print

E1.3 [codigo_libre] Crea un programa que pregunte el nombre del usuario
     y lo salude diciendo: "¡Hola, [nombre]! Bienvenido a Python."
     Usa input() y print() con f-string.
     → padre valida
```

**Ejercicios opcionales (5) — cada uno $2.000:**
```
E1.4 [quiz_opcion] ¿Qué tipo de dato es: edad = 11 ?
     a) string  b) float  c) int  d) bool

E1.5 [codigo_libre] Crea variables con tu nombre, edad y ciudad favorita.
     Imprime una presentación completa.

E1.6 [quiz_texto] ¿Qué símbolo se usa para comentarios en Python?
     → #

E1.7 [codigo_libre] Programa que pida nombre y año de nacimiento
     y calcule la edad aproximada.

E1.8 [mini_proyecto] PROYECTO DEL MÓDULO: Calculadora de edad completa.
     Input: año nacimiento. Output: edad actual, año que cumple 18,
     año que cumple 21, mensaje motivador personalizado.
```

---

#### Módulo 2 — "Tomando decisiones" (7 días estimados)
**Conceptos:** if, elif, else, operadores de comparación, operadores lógicos (and, or, not)
**Proyecto:** Juego "Adivina el número" — el programa genera un número y el jugador
              debe adivinarlo con pistas de "más alto" / "más bajo".

**Lecciones:**
1. Si esto... entonces aquello: el if
2. Pero ¿y si no? El else
3. Múltiples opciones: elif
4. Comparando cosas: ==, !=, >, <, >=, <=
5. Combinando condiciones: and, or, not
6. Introducción a random: `import random; random.randint(1, 100)`

**Ejercicios obligatorios (3):**
```
E2.1 [quiz_opcion] ¿Qué imprime este código?
     x = 10
     if x > 5:
         print("grande")
     else:
         print("pequeño")
     → "grande"

E2.2 [quiz_texto] ¿Qué palabra se usa para agregar una condición adicional al if?
     → elif

E2.3 [codigo_libre] Programa que pida una nota (0-100) e imprima:
     90-100: "¡Excelente!"
     70-89:  "Bien hecho"
     50-69:  "Puedes mejorar"
     0-49:   "A estudiar más"
```

**Ejercicios opcionales (5):**
```
E2.4 [quiz_opcion] ¿Qué significa el operador != ?
E2.5 [codigo_libre] Calculadora de par/impar
E2.6 [quiz_texto] ¿Cómo se importa el módulo random?
E2.7 [codigo_libre] Programa "¿Puedes entrar?" — pide edad, si tiene 18+ puede entrar
E2.8 [mini_proyecto] Juego completo "Adivina el número" con pistas y contador de intentos
```

---

#### Módulo 3 — "Repitiendo cosas" (7 días estimados)
**Conceptos:** for, while, range(), break, continue, bucles anidados básicos
**Proyecto:** Tablas de multiplicar interactivas + mini juego de números

**Lecciones:**
1. El bucle for — repetir un número fijo de veces
2. range() — generando secuencias de números
3. El bucle while — repetir mientras algo sea verdad
4. Saliendo del bucle: break
5. Saltando una vuelta: continue
6. Bucles dentro de bucles (anidados simples)

**Ejercicios obligatorios (3):**
```
E3.1 [quiz_opcion] ¿Cuántas veces imprime "hola" este código?
     for i in range(5):
         print("hola")
     → 5 veces

E3.2 [quiz_texto] ¿Qué función genera una secuencia de números del 0 al 9?
     → range(10)

E3.3 [codigo_libre] Programa que imprima la tabla de multiplicar del número
     que el usuario ingrese, del 1 al 10.
```

**Ejercicios opcionales (5):**
```
E3.4 [quiz_opcion] ¿Qué hace break dentro de un bucle?
E3.5 [codigo_libre] Cuenta regresiva del 10 al 0 con mensaje "¡Despegue!"
E3.6 [codigo_libre] Suma de todos los números del 1 al 100
E3.7 [mini_proyecto] Tablas de multiplicar del 1 al 10 en formato tabla bonita
E3.8 [desafio_especial] FizzBuzz — imprime números 1-50, múltiplos de 3="Fizz",
     múltiplos de 5="Buzz", ambos="FizzBuzz" (clásico de entrevistas de programación)
```

---

### NIVEL 2 — EXPLORADOR ($25.000 por módulo)

#### Módulo 4 — "Funciones mágicas" (7 días estimados)
**Conceptos:** def, parámetros, return, scope (local vs global), funciones con valores por defecto
**Proyecto:** Librería personal de utilidades — colección de funciones útiles que el niño
              diseña él mismo (calculadora, conversor de unidades, etc.)

**Lecciones:**
1. ¿Qué es una función y por qué usarla?
2. Definiendo funciones con def
3. Parámetros — la función recibe información
4. return — la función devuelve un resultado
5. Variables locales vs globales
6. Parámetros con valores por defecto

**Ejercicios obligatorios (3):**
```
E4.1 [quiz_opcion] ¿Qué hace return en una función?
E4.2 [quiz_texto] ¿Qué palabra se usa para definir una función?
     → def
E4.3 [codigo_libre] Crea 3 funciones:
     - saludar(nombre) → imprime saludo personalizado
     - area_rectangulo(base, altura) → retorna el área
     - es_par(numero) → retorna True o False
```

**Ejercicios opcionales (5):**
```
E4.4 Función que convierte Celsius a Fahrenheit
E4.5 Función que calcula el factorial de un número
E4.6 Función con parámetro por defecto: saludar(nombre, saludo="Hola")
E4.7 Mini calculadora con funciones: sumar, restar, multiplicar, dividir
E4.8 [mini_proyecto] Librería de funciones útiles — mínimo 6 funciones propias
     documentadas con comentarios
```

---

#### Módulo 5 — "Colecciones de cosas" (7 días estimados)
**Conceptos:** listas, índices, métodos de lista (.append, .remove, .sort, len()),
               diccionarios, tuplas, iteración sobre colecciones
**Proyecto:** Inventario de videojuegos — el niño construye un programa para gestionar
              su colección de juegos con agregar, eliminar y buscar.

**Lecciones:**
1. Listas — guardar muchas cosas en una variable
2. Accediendo a elementos: índices (0, 1, 2...)
3. Modificando listas: append, remove, pop, sort
4. Iterando sobre listas con for
5. Diccionarios — pares clave:valor
6. Tuplas — listas que no cambian

**Ejercicios obligatorios (3):**
```
E5.1 [quiz_opcion] ¿Qué índice tiene el primer elemento de una lista?
     → 0
E5.2 [quiz_texto] ¿Qué método se usa para agregar un elemento al final de una lista?
     → append
E5.3 [codigo_libre] Programa con lista de frutas favoritas:
     - Agregar 3 frutas
     - Imprimir cuántas hay (len)
     - Imprimir la segunda fruta
     - Eliminar la primera
     - Imprimir la lista ordenada
```

**Ejercicios opcionales (5):**
```
E5.4 Diccionario con información personal (nombre, edad, ciudad, hobby)
E5.5 Programa que busca si un elemento está en la lista
E5.6 Lista de notas y cálculo de promedio
E5.7 Diccionario de países y capitales — 5 países latinoamericanos
E5.8 [mini_proyecto] Inventario de videojuegos completo con menú:
     1) Ver juegos  2) Agregar juego  3) Buscar juego  4) Eliminar juego  5) Salir
```

---

#### Módulo 6 — "Guardando información" (7 días estimados)
**Conceptos:** manejo de archivos (open, read, write, append), with statement,
               archivos .txt, introducción a archivos .json, manejo de errores básico (try/except)
**Proyecto:** Diario de puntajes — el programa guarda el historial de puntajes del juego
              de adivinar el número (módulo 2) en un archivo .txt

**Lecciones:**
1. ¿Por qué guardar información en archivos?
2. Abrir y leer un archivo: open() y read()
3. Escribir en archivos: write() y append()
4. El operador with — la forma correcta
5. Archivos JSON — guardar datos estructurados
6. Manejo de errores: try / except

**Ejercicios obligatorios (3):**
```
E6.1 [quiz_opcion] ¿Qué modo abre un archivo para agregar texto sin borrar lo anterior?
     → "a" (append)
E6.2 [quiz_texto] ¿Qué librería de Python se usa para leer archivos JSON?
     → json
E6.3 [codigo_libre] Programa que:
     - Pida nombre y puntuación al usuario
     - Guarde el registro en "puntajes.txt"
     - Al abrir de nuevo, muestre todos los puntajes guardados
```

**Ejercicios opcionales (5):**
```
E6.4 Leer un archivo .txt e imprimir cuántas líneas tiene
E6.5 Guardar y cargar lista de tareas en JSON
E6.6 try/except — manejar el error cuando un archivo no existe
E6.7 Programa que busca una palabra en un archivo de texto
E6.8 [mini_proyecto] Sistema de puntajes del juego completo:
     guarda nombre, puntaje, fecha y número de intentos.
     Muestra el ranking top 5 al iniciar.
```

---

### NIVEL 3 — CONSTRUCTOR ($35.000 por módulo)

#### Módulo 7 — "Objetos y clases" (10 días estimados)
**Conceptos:** clases, objetos, __init__, self, atributos, métodos, herencia básica
**Proyecto:** Equipo de fútbol — clase Jugador con nombre, posición, goles.
              Clase Equipo con lista de jugadores y estadísticas.

**Lecciones:**
1. ¿Qué es la programación orientada a objetos? (analogía con el mundo real)
2. Clases — el molde; objetos — las copias
3. El método __init__ y self
4. Atributos — las características del objeto
5. Métodos — lo que el objeto puede hacer
6. Herencia — una clase que hereda de otra

**Ejercicios obligatorios (3):**
```
E7.1 [quiz_opcion] ¿Qué es self en Python?
E7.2 [quiz_texto] ¿Qué método se llama automáticamente al crear un objeto?
     → __init__
E7.3 [codigo_libre] Clase Mascota con:
     - Atributos: nombre, especie, edad
     - Métodos: hablar(), cumpleanos() (aumenta edad en 1), presentarse()
     - Crear 2 objetos distintos
```

**Ejercicios opcionales (5):**
```
E7.4 Clase Rectangulo con métodos area() y perimetro()
E7.5 Clase CuentaBancaria con depositar() y retirar()
E7.6 Herencia: Clase Animal → Perro y Gato con sonidos diferentes
E7.7 Lista de objetos — 5 mascotas en una lista, imprimir todas
E7.8 [mini_proyecto] Equipo de fútbol completo:
     Clase Jugador + Clase Equipo + estadísticas de temporada
```

---

#### Módulo 8 — "Python y el internet" (10 días estimados)
**Conceptos:** módulo requests, JSON de APIs, parsing de respuestas,
               manejo de errores de red, introducción a APIs públicas
**Proyecto:** App del clima — consulta la API de Open-Meteo (gratuita, sin API key)
              para mostrar el clima actual de Medellín y otras ciudades.

**APIs gratuitas recomendadas para niños (sin registro):**
- Open-Meteo: clima (https://open-meteo.com) ← USAR ESTA
- PokeAPI: datos de Pokémon (https://pokeapi.co)
- Numbers API: datos curiosos de números (http://numbersapi.com)
- Chuck Norris jokes API: chistes (https://api.chucknorris.io)

**Lecciones:**
1. ¿Qué es una API? (analogía con el menú de un restaurante)
2. El módulo requests: pip install requests
3. requests.get() y la respuesta
4. Parseando JSON — response.json()
5. Manejo de errores de red
6. Proyecto: consumir Open-Meteo

**Ejercicios obligatorios (3):**
```
E8.1 [quiz_opcion] ¿Qué función de requests hace una petición GET?
E8.2 [quiz_texto] ¿Qué método convierte la respuesta a diccionario Python?
     → .json()
E8.3 [codigo_libre] Programa que consulte la PokeAPI y muestre:
     nombre, altura, peso y tipos del Pokémon que el usuario elija.
     URL: https://pokeapi.co/api/v2/pokemon/{nombre}
```

**Ejercicios opcionales (5):**
```
E8.4 Consultar chiste random de Chuck Norris API
E8.5 Dato curioso del número de tu edad usando Numbers API
E8.6 Programa que compare el clima de 3 ciudades colombianas
E8.7 Guardar resultado de API en archivo JSON local
E8.8 [mini_proyecto] App del clima de Medellín:
     temperatura actual, máxima, mínima, probabilidad de lluvia.
     Usar Open-Meteo (lat: 6.2442, lon: -75.5812)
```

---

#### Módulo 9 — "¡Hagamos un juego visual!" (10 días estimados)
**Conceptos:** módulo turtle (dibujado, movimiento, colores, eventos de teclado),
               introducción a pygame (ventana, bucle del juego, colisiones básicas)
**Proyecto:** Juego de la serpiente (Snake) simplificado con turtle
              O: juego de esquivar obstáculos con pygame

**Lecciones:**
1. Python Turtle — dibujando con código
2. Bucles y formas geométricas en Turtle
3. Eventos de teclado en Turtle
4. Introducción a Pygame — la ventana del juego
5. El bucle del juego (game loop)
6. Colisiones básicas

**Ejercicios obligatorios (3):**
```
E9.1 [codigo_libre] Con Turtle, dibujar:
     - Un cuadrado
     - Un triángulo
     - Una estrella de 5 puntas
     Usando bucles for.

E9.2 [codigo_libre] Con Turtle, hacer que una tortuga se mueva
     con las flechas del teclado.
     (onkey, listen, mainloop)

E9.3 [codigo_libre] Con Pygame, crear una ventana con:
     - Fondo de color
     - Un rectángulo que se mueve con las flechas
     - Que no salga de la pantalla
```

**Ejercicios opcionales (5):**
```
E9.4 Dibujar la bandera de Colombia con Turtle
E9.5 Espiral de colores con Turtle (bucle + cambio de color)
E9.6 Agregar puntuación en pantalla al juego de Pygame
E9.7 Agregar obstáculos que caen desde arriba
E9.8 [mini_proyecto] Juego completo — elige entre:
     Opción A: Snake con Turtle
     Opción B: Esquiva obstáculos con Pygame
     Debe tener: menú, puntuación, game over, reiniciar.
```

---

### NIVEL 4 — BOSS ($70.000 fijo)

#### Módulo 10 — Proyecto Final Personal
**Sin estructura fija — el niño elige su problema real y lo resuelve.**

**Ideas sugeridas según intereses:**
- Quiz de historia para estudiar para el colegio
- App que organiza sus tareas escolares (usa archivos JSON)
- Juego de trivia de fútbol
- Programa que analiza sus notas del colegio
- Bot simple que responde preguntas sobre un tema

**Requisitos mínimos del proyecto:**
- Mínimo 3 funciones propias
- Uso de al menos una colección (lista o diccionario)
- Guardar/cargar datos en archivo
- Manejo de errores con try/except
- Comentarios explicando el código
- El niño presenta el proyecto como si fuera a un cliente real

**Recompensas del proyecto final:**
- $70.000 en efectivo al aprobar
- Videojuego o periférico a elección (presupuesto acordado aparte)
- Certificado "Desarrollador Junior" impreso y firmado por el padre
- Subir el código a GitHub con su nombre real

---

## 8. SISTEMA DE LOGROS (BADGES)

```
LOGROS DE INICIO
slug: primer-paso        → Completar el primer ejercicio
slug: bienvenido-nivel1  → Completar el Módulo 1

LOGROS DE PERFECCIÓN
slug: tiro-perfecto      → 1 ejercicio perfecto (al primer intento)
slug: francotirador      → 5 ejercicios perfectos
slug: ojo-de-aguila      → 15 ejercicios perfectos

LOGROS DE CONSTANCIA (RACHA)
slug: racha-3            → Primera racha de 3 días
slug: semana-completa    → Primera racha de 7 días
slug: imparable          → Racha de 14 días

LOGROS DE VELOCIDAD
slug: rapido             → Completar 1 módulo en menos de 5 días
slug: rayo               → Completar un nivel completo en menos de 2 semanas

LOGROS DE PROGRESO
slug: explorer           → Completar el Nivel 1 (los 3 módulos)
slug: constructor        → Completar el Nivel 2
slug: arquitecto         → Completar el Nivel 3
slug: master             → Completar el Proyecto Final

LOGROS ESPECIALES
slug: fizzbuzz-hero      → Completar el desafío FizzBuzz (módulo 3)
slug: api-caller         → Primera consulta a una API real
slug: game-creator       → Crear su primer juego visual
slug: github-star        → Subir el proyecto a GitHub
```

---

## 9. DISEÑO UI/UX — FILOSOFÍA

**Tema visual:** Oscuro (dark mode) con acentos en verde lima y morado.
Inspirado en VS Code + videojuegos de rol. El niño ya usa VS Code y se sentirá familiar.

**Principios:**
- Tablet-first (viewport principal es una tablet de ~768px)
- Sin distracciones — una sola tarea visible a la vez
- Feedback inmediato y exagerado — animaciones de celebración al ganar
- Progreso siempre visible — XP bar y billetera en el header
- Nunca mostrar lo que no puede hacer aún (módulos bloqueados se ven pero opacados)

**Pantalla Dashboard (tablet):**
```
┌──────────────────────────────────────────────┐
│  🐍 PythonJr        XP: ████░░  💰 $48.000   │
│                     🔥 Racha: 4 días          │
├──────────────────────────────────────────────┤
│                                               │
│  ¡Hola, Santiago! 👋                         │
│  Llevas 4 días seguidos. ¡Sigue así!         │
│                                               │
│  ⚡ DESAFÍO DE HOY — caduca en 6h            │
│  ┌──────────────────────────────────────┐    │
│  │  FizzBuzz Express          +$5.000   │    │
│  │  [HACER DESAFÍO →]                   │    │
│  └──────────────────────────────────────┘    │
│                                               │
│  📍 CONTINÚA DONDE IBAS                      │
│  Módulo 3 — Repitiendo cosas                  │
│  Ejercicio 2 de 8                            │
│  [CONTINUAR →]                               │
│                                               │
│  🏆 MÓDULOS                                  │
│  [M1 ✓] [M2 ✓] [M3 ▶] [M4 🔒] [M5 🔒]     │
│                                               │
│  💰 BILLETERA: $48.000 acumulados            │
│  $23.000 pendientes de cobro                 │
│                                               │
└──────────────────────────────────────────────┘
```

**Pantalla de Módulo:**
- Sidebar izquierda: lista de lecciones y ejercicios con estado (✓/▶/○)
- Área principal: contenido renderizado desde Markdown
- Bloques de código: Monaco Editor en modo readonly con syntax highlight
- Botón "Ya lo hice en el PC" para ejercicios de código libre → envía a validación del padre

**Pantalla de Ejercicio:**
- Enunciado claro con ejemplos
- Para quiz_opcion: botones grandes, fáciles de tocar en tablet
- Para quiz_texto: input grande y botón de verificar
- Para codigo_libre: instrucciones + botón "Enviar al papá para revisar"
- Resultado: animación de confetti si es correcto, mensaje de aliento si no

**Panel Admin (padre — desde PC preferiblemente):**
- Lista de ejercicios pendientes de validación
- Vista del código que envió el niño (foto o texto pegado)
- Botón "Aprobar" y "Pedir corrección" con campo de feedback
- Historial de pagos realizados
- Gráfica de progreso semanal

---

## 10. TABLA DE RECOMPENSAS CONSOLIDADA

```
TIPO                    MONTO       CONDICIÓN
─────────────────────────────────────────────────────
Módulo 1-3 (base)       $15.000    Al completar el módulo
Módulo 4-6 (base)       $25.000    Al completar el módulo
Módulo 7-9 (base)       $35.000    Al completar el módulo
Proyecto final          $70.000    Al aprobar el proyecto

Ejercicio opcional       $2.000    Por ejercicio opcional correcto
Ejercicio perfecto       $3.000    Si lo hace correcto al 1er intento
Desafío del día          $5.000    Solo si lo hace el día que aparece

Racha 3 días             $8.000    Primera vez que llega a 3 días seguidos
Racha 7 días            $20.000    Primera vez que llega a 7 días seguidos

Velocidad módulo        $10.000    Completar un módulo en ≤5 días
Velocidad nivel         $25.000    Completar un nivel (3 módulos) en ≤14 días

MÁXIMO TEÓRICO TOTAL:  ~$490.000 (si hace todo perfecto con todas las velocidades)
MÍNIMO GARANTIZADO:    ~$225.000 (solo módulos base, sin bonos)
PROMEDIO REALISTA:     ~$320.000 (algunos bonos, ritmo normal)
```

---

## 11. SEEDS DE BASE DE DATOS

El proyecto debe tener seeders completos en Laravel para:

1. `UserSeeder` — crea usuario admin (padre) y estudiante (hijo)
2. `ModuloSeeder` — los 10 módulos con sus datos
3. `LeccionSeeder` — contenido de lecciones en Markdown
4. `EjercicioSeeder` — todos los ejercicios con respuestas y recompensas
5. `LogroSeeder` — todos los logros definidos en la sección 8
6. `DesafioDiaSeeder` — genera desafíos para los próximos 30 días
7. `ProgresoSeeder` — estado inicial: Módulo 1 disponible, resto bloqueados

---

## 12. VARIABLES DE ENTORNO (.env)

```env
# App
APP_NAME=PythonJr
APP_ENV=production
APP_KEY=
APP_URL=https://pythonjr.tudominio.com
APP_TIMEZONE=America/Bogota

# Database (Railway PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${RAILWAY_TCP_PROXY_DOMAIN}
DB_PORT=${RAILWAY_TCP_PROXY_PORT}
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=${PGPASSWORD}

# Auth
SANCTUM_STATEFUL_DOMAINS=pythonjr.tudominio.com,localhost:5173
SESSION_DOMAIN=.pythonjr.tudominio.com

# Frontend URL (para CORS)
FRONTEND_URL=https://pythonjr.tudominio.com

# Queue
QUEUE_CONNECTION=database

# Mail (opcional para futuras notificaciones)
MAIL_MAILER=smtp
```

---

## 13. COMANDOS DE SETUP INICIAL

```bash
# Backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Frontend
cd frontend
npm install
npm run build

# Railway deploy
# El Dockerfile maneja: migrate, seed, serve
```

---

## 14. CONSIDERACIONES DE SEGURIDAD

- El padre tiene role='admin', el hijo role='estudiante'
- Middleware `role:admin` protege todas las rutas /api/admin/*
- El niño NUNCA puede ver las respuestas correctas directamente (solo el admin)
- Los montos de recompensa están en el backend, nunca en el frontend
- El frontend solo puede consultar su propio progreso (policy de Laravel)
- Rate limiting en endpoints de ejercicios: máximo 10 intentos por minuto

---

## 15. FASES DE DESARROLLO (ORDEN SUGERIDO A CLAUDE CODE)

**Fase 1 — Fundamentos (1-2 días)**
1. Setup Laravel 12 + React 19 + PostgreSQL
2. Migraciones completas
3. Seeders con Módulo 1 completo y usuario de prueba
4. Auth con Sanctum (login/logout/me)

**Fase 2 — Core del estudiante (2-3 días)**
5. API de módulos y lecciones
6. API de ejercicios + GamificacionService
7. API de billetera y racha
8. Dashboard endpoint

**Fase 3 — Frontend tablet (3-4 días)**
9. Layout base + routing React
10. Dashboard con estado del niño
11. Vista de módulo con lecciones
12. Vista de ejercicio (quiz + código libre)
13. Animaciones de celebración (Framer Motion)

**Fase 4 — Panel admin (1-2 días)**
14. Dashboard admin
15. Validación de ejercicios
16. Registro de pagos

**Fase 5 — Gamificación completa (1-2 días)**
17. Sistema de logros
18. Desafío del día
19. Notificaciones en la UI

**Fase 6 — Deploy (1 día)**
20. Dockerfile para Railway
21. GitHub Actions pipeline
22. Configuración de dominio

---

## 16. NOTAS IMPORTANTES PARA CLAUDE CODE

- Todo el contenido educativo va en los seeders en español colombiano informal
- Los ejemplos de código deben usar contextos cercanos al niño: fútbol, videojuegos,
  Medellín, Colombia, nombres colombianos
- Las lecciones en Markdown soportan bloques ```python que Monaco renderiza
- El Monaco Editor en el frontend es SOLO para mostrar ejemplos (readonly)
  El niño escribe código real en VS Code en su PC
- La validación de ejercicios quiz_opcion y quiz_texto es automática en el backend
- Los ejercicios codigo_libre y mini_proyecto requieren aprobación del padre
- Nunca hacer que el niño instale dependencias — todo funciona con Python stdlib
  hasta el módulo 8 donde instala requests (único pip install del curso)
- El módulo 9 usa turtle (stdlib) y opcionalmente pygame (pip install pygame)
```
