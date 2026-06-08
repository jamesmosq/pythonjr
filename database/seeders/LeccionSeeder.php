<?php

namespace Database\Seeders;

use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class LeccionSeeder extends Seeder
{
    public function run(): void
    {
        $modulo1 = Modulo::where('slug', 'hola-python')->first();

        $lecciones = [
            [
                'modulo_id' => $modulo1->id,
                'orden' => 1,
                'tipo' => 'teoria',
                'titulo' => '¿Qué es Python y por qué es cool?',
                'contenido' => <<<'MD'
# ¿Qué es Python y por qué es cool? 🐍

Python es un lenguaje de programación creado en 1991 por Guido van Rossum. Su nombre no viene de la serpiente — ¡viene del programa de comedia inglés *Monty Python's Flying Circus*! 🎭

## ¿Quién usa Python?

Python lo usan algunas de las empresas más grandes del mundo:

- **Google** → para búsquedas y YouTube
- **Netflix** → para recomendarte series
- **NASA** → para controlar robots en Marte 🚀
- **Instagram** → el backend completo está en Python
- **Spotify** → para analizar qué música te gusta

## ¿Por qué aprender Python?

1. **Es fácil de leer** — casi parece español/inglés normal
2. **Hace de todo** — web, ciencia de datos, inteligencia artificial, juegos, robots
3. **Es gratis** — puedes descargarlo en python.org
4. **Tiene una comunidad enorme** — millones de programadores te pueden ayudar

## Python vs otros lenguajes

Mira qué tan simple es Python comparado con otros:

```python
# Python — así de simple
print("¡Hola, Santiago!")
```

```java
// Java — mucho más complicado para lo mismo
public class HolaMundo {
    public static void main(String[] args) {
        System.out.println("¡Hola, Santiago!");
    }
}
```

¿Ves la diferencia? Python va al grano. 😎

## Tu misión

En este módulo vas a aprender las bases absolutas de Python. Al final, vas a crear una **Calculadora de Edad** que calcula cuántos años tienes y cuándo cumples 18 y 21.

¡Vamos! 💪
MD,
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 2,
                'tipo' => 'ejemplo_codigo',
                'titulo' => 'Tu primer programa: print()',
                'contenido' => <<<'MD'
# Tu primer programa 🎉

La función más importante que vas a aprender hoy se llama `print()`. Sirve para **mostrar texto en la pantalla**.

## Cómo funciona

```python
print("¡Hola, me llamo Santiago!")
```

Cuando ejecutas este código, Python muestra:
```
¡Hola, me llamo Santiago!
```

## Reglas importantes

- El texto va dentro de comillas: `"texto"` o `'texto'`
- No olvides los paréntesis `()`
- Python distingue mayúsculas: `Print` ≠ `print`

## Puedes imprimir varias cosas

```python
print("¡Hola!")
print("Me llamo Santiago")
print("Tengo 11 años")
print("Vivo en Medellín 🏙️")
```

Resultado:
```
¡Hola!
Me llamo Santiago
Tengo 11 años
Vivo en Medellín 🏙️
```

## También puedes imprimir números

```python
print(2024)
print(11 + 5)
print(100 - 37)
```

Resultado:
```
2024
16
63
```

## 💡 Tip

Para ejecutar tu código en VS Code:
1. Abre la terminal (Ctrl + ñ)
2. Escribe: `python nombre_del_archivo.py`
3. Presiona Enter
MD,
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 3,
                'tipo' => 'teoria',
                'titulo' => 'Variables — las cajas de guardar cosas',
                'contenido' => <<<'MD'
# Variables — las cajas de guardar cosas 📦

Imagina que tienes cajas con nombres escritos encima. En cada caja puedes guardar información. Eso son las **variables** en Python.

## Crear una variable

```python
nombre = "Santiago"
edad = 11
ciudad = "Medellín"
```

- `nombre` es el nombre de la caja
- `=` significa "guarda esto en la caja"
- `"Santiago"` es lo que guardas

## Usar las variables

```python
nombre = "Santiago"
edad = 11

print(nombre)   # muestra: Santiago
print(edad)     # muestra: 11
```

## Cambiar el contenido

```python
puntaje = 0
print(puntaje)  # 0

puntaje = 100
print(puntaje)  # 100

puntaje = puntaje + 50
print(puntaje)  # 150
```

## Reglas para los nombres

✅ Correcto:
```python
nombre = "Santiago"
mi_ciudad = "Medellín"
edad2024 = 11
goles_james = 100
```

❌ Incorrecto:
```python
2nombre = "error"      # no puede empezar con número
mi ciudad = "error"    # no puede tener espacios
class = "error"        # 'class' es una palabra reservada
```

## 💡 Buena práctica

Usa nombres descriptivos. En vez de `x = 11`, escribe `edad = 11`. Así tu código se entiende solo.
MD,
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 4,
                'tipo' => 'teoria',
                'titulo' => 'Tipos de datos: números, texto y booleanos',
                'contenido' => <<<'MD'
# Tipos de datos 🔢📝✅

Python tiene diferentes "tipos" de información que puede guardar. Los más importantes son:

## 1. Enteros (int) — números sin decimales

```python
edad = 11
goles = 47
año = 2024
temperatura = -5
```

## 2. Decimales (float) — números con punto decimal

```python
estatura = 1.52
nota = 9.5
pi = 3.14159
precio = 2500.50
```

> ⚠️ En Python se usa punto `.` no coma `,` para los decimales

## 3. Texto (str) — cadenas de caracteres

```python
nombre = "Santiago"
equipo = 'Nacional'
frase = "¡Arriba el Verde!"
emoji = "🏆"
```

## 4. Booleanos (bool) — verdadero o falso

```python
es_mayor = False
tiene_tarea = True
gano_nacional = True
perdio_millonarios = True  # 😄
```

## ¿Cómo saber el tipo?

Usa la función `type()`:

```python
print(type(11))          # <class 'int'>
print(type(3.14))        # <class 'float'>
print(type("hola"))      # <class 'str'>
print(type(True))        # <class 'bool'>
```

## Convertir entre tipos

```python
edad_texto = "11"
edad_numero = int(edad_texto)   # convierte "11" → 11

numero = 42
texto = str(numero)             # convierte 42 → "42"

precio = float("9.99")         # convierte "9.99" → 9.99
```
MD,
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 5,
                'tipo' => 'ejemplo_codigo',
                'titulo' => 'Pidiendo información con input()',
                'contenido' => <<<'MD'
# Pidiendo información al usuario con input() ⌨️

La función `input()` sirve para pedirle al usuario que escriba algo. El programa **se pausa** y espera que el usuario escriba y presione Enter.

## Uso básico

```python
nombre = input("¿Cómo te llamas? ")
print("¡Hola,", nombre, "!")
```

Ejecución:
```
¿Cómo te llamas? Santiago
¡Hola, Santiago !
```

## ¡Importante! input() siempre devuelve texto

```python
año_nacimiento = input("¿En qué año naciste? ")
print(type(año_nacimiento))  # <class 'str'> ← es texto, no número!
```

Para usarlo como número debes convertirlo:

```python
año_nacimiento = int(input("¿En qué año naciste? "))
edad = 2024 - año_nacimiento
print("Tienes aproximadamente", edad, "años")
```

## Ejemplo completo

```python
nombre = input("Tu nombre: ")
equipo = input("¿De qué equipo eres? ")
goles = int(input("¿Cuántos goles le marcó tu equipo hoy? "))

print("---")
print(nombre, "es del", equipo)
if goles > 0:
    print("¡Ganamos con", goles, "goles! 🎉")
else:
    print("Hoy no fue el día... 😅")
```

## 💡 Tips

- Pon un espacio al final del mensaje: `"Tu nombre: "` (el espacio queda bonito)
- Si el usuario va a escribir un número, convierte con `int()` o `float()`
- Si el usuario escribe texto donde se espera número, el programa dará error — por ahora no te preocupes, eso se maneja en módulos futuros
MD,
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 6,
                'tipo' => 'ejemplo_codigo',
                'titulo' => 'F-strings: mezclar texto con variables',
                'contenido' => <<<'MD'
# F-strings — la forma más elegante de mezclar texto 🎨

Un **f-string** es una forma de crear texto que incluye variables directamente dentro. Es la forma más moderna y recomendada en Python.

## Cómo funciona

Pon una `f` antes de las comillas, y las variables van dentro de llaves `{}`:

```python
nombre = "Santiago"
edad = 11

# Sin f-string (más difícil de leer)
print("Hola " + nombre + ", tienes " + str(edad) + " años")

# Con f-string (mucho más limpio)
print(f"Hola {nombre}, tienes {edad} años")
```

Ambos producen el mismo resultado:
```
Hola Santiago, tienes 11 años
```

## Puedes hacer operaciones dentro

```python
año_nacimiento = 2013
año_actual = 2024

print(f"Naciste en {año_nacimiento}")
print(f"Tienes {año_actual - año_nacimiento} años")
print(f"En 2030 tendrás {2030 - año_nacimiento} años")
```

## Ejemplo real

```python
nombre = input("¿Cómo te llamas? ")
equipo = input("¿De qué equipo eres? ")
goles = int(input("¿Cuántos goles marcó tu equipo? "))

print(f"¡Hola {nombre}! 👋")
print(f"El {equipo} marcó {goles} goles hoy.")

if goles >= 3:
    print(f"¡{goles} goles! ¡Golazo de partido! 🔥")
elif goles > 0:
    print(f"Bien, {goles} gol(es). ¡A seguir mejorando!")
else:
    print("Hoy no fue el día... pero habrá revancha 💪")
```

## 💡 Dato curioso

La `f` viene de "formatted string" (cadena formateada). Apareció en Python 3.6 y desde entonces es la forma favorita de todos los programadores.
MD,
            ],
        ];

        foreach ($lecciones as $data) {
            Leccion::create($data);
        }

        $moduloGit = Modulo::where('slug', 'git-github')->first();
        if (! $moduloGit) {
            return;
        }

        $leccionesGit = [
            [
                'modulo_id' => $moduloGit->id,
                'orden' => 1,
                'tipo' => 'teoria',
                'titulo' => '¿Por qué guardar el tiempo?',
                'contenido' => <<<'MD'
# ¿Por qué guardar el tiempo? ⏳

Imagina que estás construyendo el castillo de Minecraft de tu vida. Llevas 5 horas trabajando y de repente... **¡lo arruinas todo sin querer!** 😱

Si el juego no tuviera guardado automático, perderías TODO.

Con tu código pasa lo mismo. Cuando escribes un programa, puedes:
- Borrar algo que funcionaba sin querer
- Probar algo nuevo y arruinar lo anterior
- Querer volver a una versión de hace 3 días

## Git es tu máquina del tiempo 🕰️

**Git** es un programa que guarda "fotos" de tu código en cualquier momento. Cada foto se llama **commit**.

Así funciona:

```
Versión 1   →   Versión 2   →   Versión 3
(commit 1)     (commit 2)     (commit 3)
   📸              📸              📸
```

Si algo sale mal en la versión 3, puedes volver a la versión 1 o 2. **¡El tiempo es tuyo!**

## ¿Y GitHub?

**GitHub** es como Google Drive pero para código. Subes tus commits ahí y:
- Tu código está seguro en internet (aunque se rompa tu PC)
- Cualquier persona en el mundo puede ver tu trabajo
- Es lo que usan TODOS los programadores profesionales

> 💡 Dato curioso: Más de **100 millones de programadores** usan GitHub. Cuando termines este módulo, ¡serás uno de ellos!
MD,
            ],
            [
                'modulo_id' => $moduloGit->id,
                'orden' => 2,
                'tipo' => 'teoria',
                'titulo' => 'Instalación y tu primera configuración',
                'contenido' => <<<'MD'
# Instalación y configuración ⚙️

## 1. Instalar Git

Descarga Git desde **git-scm.com** → "Download for Windows".

Instala con todas las opciones por defecto. Cuando termine, abre la terminal (CMD o PowerShell) y escribe:

```bash
git --version
```

Si ves algo como `git version 2.44.0`, ¡listo! ✅

## 2. Decirle a Git quién eres

Git necesita saber tu nombre y correo para firmar tus commits. Escribe esto en la terminal (cambia los datos por los tuyos):

```bash
git config --global user.name "Tu Nombre"
git config --global user.email "tucorreo@gmail.com"
```

Verifica que quedó bien:

```bash
git config --global user.name
git config --global user.email
```

## 3. El flujo básico de Git

Todo en Git sigue este ciclo:

```
1. Trabajas en tu código
       ↓
2. git add (dices qué archivos guardar)
       ↓
3. git commit (tomas la "foto")
       ↓
4. git push (subes a GitHub)
```

¡En las siguientes lecciones harás cada uno de estos pasos!
MD,
            ],
            [
                'modulo_id' => $moduloGit->id,
                'orden' => 3,
                'tipo' => 'ejemplo_codigo',
                'titulo' => 'Tu primer repositorio',
                'contenido' => <<<'MD'
# Tu primer repositorio 📁

Un **repositorio** (o "repo") es una carpeta especial donde Git guarda todo el historial de tu código.

## Crear un repositorio desde cero

```bash
# 1. Crea una carpeta para tu proyecto
mkdir mi-primer-repo
cd mi-primer-repo

# 2. Inicializa Git en esa carpeta
git init
```

Verás algo como: `Initialized empty Git repository in .../mi-primer-repo/.git/`

¡Git ya está vigilando esa carpeta! 👀

## Ver el estado de tus archivos

```bash
git status
```

Este comando es tu mejor amigo. Te dice:
- Qué archivos cambiaron
- Qué archivos están listos para guardar
- Qué archivos son nuevos

## Tu primer commit

```bash
# 1. Crea un archivo
echo "# Mi primer repo con Git" > README.md

# 2. Dile a Git que quieres guardar ese archivo
git add README.md

# 3. Toma la "foto" (commit)
git commit -m "primer commit: agrego README"
```

La parte `-m "..."` es el **mensaje del commit**. Es como el nombre de la foto.

> 💡 **Consejo pro**: Los buenos mensajes de commit explican QUÉ hiciste, no cómo. Ejemplo:
> - ❌ `git commit -m "cambios"`
> - ✅ `git commit -m "agrego función para calcular el área del círculo"`
MD,
            ],
            [
                'modulo_id' => $moduloGit->id,
                'orden' => 4,
                'tipo' => 'teoria',
                'titulo' => 'Viajando por el historial',
                'contenido' => <<<'MD'
# Viajando por el historial 🗺️

Una vez que tienes varios commits, puedes explorar el historial de tu código.

## Ver todos los commits

```bash
git log
```

Muestra algo como:
```
commit a3f2c1d (HEAD -> main)
Author: Tu Nombre <tucorreo@gmail.com>
Date:   Sat Jun 7 2026

    agrego función suma

commit b1e9f3a
Author: Tu Nombre <tucorreo@gmail.com>
Date:   Fri Jun 6 2026

    primer commit: agrego README
```

## Versión resumida (la más útil)

```bash
git log --oneline
```

Muestra:
```
a3f2c1d agrego función suma
b1e9f3a primer commit: agrego README
```

## Ver qué cambió entre versiones

```bash
git diff
```

Muestra las líneas que agregaste (verde `+`) y eliminaste (rojo `-`).

## Tip: ¿Cuántos commits hacer?

Haz un commit cada vez que termines algo pequeño y que funcione. Piénsalo como guardar en un videojuego: **guarda seguido** para no perder progreso.
MD,
            ],
            [
                'modulo_id' => $moduloGit->id,
                'orden' => 5,
                'tipo' => 'ejemplo_codigo',
                'titulo' => 'GitHub — tu código en el universo',
                'contenido' => <<<'MD'
# GitHub — tu código en el universo 🌌

## Crear tu cuenta

Ve a **github.com** y crea una cuenta gratuita. El nombre de usuario es importante — ¡es tu identidad como programador!

## Conectar tu repo local con GitHub

```bash
# 1. Agrega GitHub como destino remoto
git remote add origin https://github.com/TU_USUARIO/mi-primer-repo.git

# 2. Sube tu código por primera vez
git push -u origin main
```

Después del primer push, solo necesitas:
```bash
git push
```

## El ciclo completo del día a día

Así trabajan los programadores todos los días:

```bash
# 1. Escribir código
# 2. Ver qué cambió
git status

# 3. Agregar los archivos que quieres guardar
git add .          # agrega TODOS los archivos
git add archivo.py # agrega solo uno

# 4. Commit con mensaje descriptivo
git commit -m "agrego función para validar edad"

# 5. Subir a GitHub
git push
```

## Ver tu perfil de GitHub

Entra a `github.com/TU_USUARIO` — ahí aparecen todos tus repositorios públicos. ¡Es tu portafolio de programador!

> 🌟 Los programadores usan su GitHub como tarjeta de presentación cuando buscan trabajo.
MD,
            ],
            [
                'modulo_id' => $moduloGit->id,
                'orden' => 6,
                'tipo' => 'teoria',
                'titulo' => 'Ramas — universos paralelos',
                'contenido' => <<<'MD'
# Ramas — universos paralelos 🌿

Imagina que puedes crear una copia de tu código para probar algo nuevo, sin arriesgar lo que ya funciona. Eso son las **ramas** (branches).

## La rama principal: `main`

Por defecto, todo tu trabajo está en la rama `main`. Es la versión "oficial" de tu código.

## Crear una rama nueva

```bash
git branch nueva-funcion
git checkout nueva-funcion
# O en un solo comando:
git checkout -b nueva-funcion
```

## Trabajar en la rama y volver a main

```bash
# Estás en "nueva-funcion", haces cambios y commits
git add .
git commit -m "experimento: agrego modo oscuro"

# Si funcionó, vuelves a main y fusionas
git checkout main
git merge nueva-funcion
```

## Ver todas las ramas

```bash
git branch
```

La rama activa tiene un `*` al lado.

## ¿Cuándo usar ramas?

Por ahora, para proyectos personales pequeños, una sola rama `main` está bien. Las ramas se usan mucho cuando hay varios programadores trabajando en el mismo proyecto al mismo tiempo.

> 💡 Para este módulo no es obligatorio usar ramas — es un concepto que conocerás en proyectos más grandes.
MD,
            ],
            [
                'modulo_id' => $moduloGit->id,
                'orden' => 7,
                'tipo' => 'ejemplo_codigo',
                'titulo' => 'Proyecto: tu portafolio en GitHub',
                'contenido' => <<<'MD'
# Tu portafolio público 🌐

¡Este es el reto final del módulo! Vas a subir todos tus ejercicios de Python a GitHub para tener tu primer portafolio público.

## Qué vas a hacer

```bash
# 1. Crea una carpeta llamada "pythonjr-ejercicios"
mkdir pythonjr-ejercicios
cd pythonjr-ejercicios
git init

# 2. Copia tus mejores programas de Python aquí
# (Los que hiciste en los módulos anteriores)

# 3. Crea un README.md presentando tu portafolio
echo "# Mis ejercicios de Python 🐍" > README.md
echo "Soy estudiante de Python. Aquí están mis programas." >> README.md

# 4. Primer commit
git add .
git commit -m "inicio: agrego mis ejercicios de Python"

# 5. Crea el repositorio en GitHub con el mismo nombre
# Ve a github.com → New repository → "pythonjr-ejercicios"

# 6. Conecta y sube
git remote add origin https://github.com/TU_USUARIO/pythonjr-ejercicios.git
git push -u origin main
```

## Resultado

Tendrás una URL así:
```
github.com/TU_USUARIO/pythonjr-ejercicios
```

¡Cualquier persona en el mundo puede ver tu código! 🌍

## Agrega un README bonito

Un buen README tiene:
- Tu nombre y una presentación corta
- Lista de los programas que incluiste
- Qué aprendiste en cada uno

```markdown
# Mis ejercicios de Python 🐍

Hola, soy [Tu Nombre], estudiante de programación.

## Programas incluidos
- `calculadora.py` - suma, resta, multiplicación y división
- `adivina_numero.py` - juego para adivinar un número secreto
- `conversor.py` - convierte temperaturas entre Celsius y Fahrenheit
```

¡Esto es lo que ven los programadores profesionales cuando buscan trabajo!
MD,
            ],
        ];

        foreach ($leccionesGit as $data) {
            Leccion::create($data);
        }
    }
}
