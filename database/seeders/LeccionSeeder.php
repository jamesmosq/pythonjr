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
    }
}
