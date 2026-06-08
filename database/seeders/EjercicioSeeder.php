<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class EjercicioSeeder extends Seeder
{
    public function run(): void
    {
        $modulo1 = Modulo::where('slug', 'hola-python')->first();

        $ejercicios = [
            // --- OBLIGATORIOS ---
            [
                'modulo_id' => $modulo1->id,
                'orden' => 1,
                'tipo' => 'quiz_opcion',
                'titulo' => '¿Qué hace este código?',
                'enunciado' => <<<'MD'
Mira este código Python y dinos qué imprime cuando se ejecuta:

```python
print("Hola" + " " + "mundo")
```

¿Cuál es el resultado?
MD,
                'respuesta_correcta' => 'b',
                'es_obligatorio' => true,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'El operador + entre strings los une (concatena). Cuenta los espacios.',
                'opciones' => [
                    ['texto' => 'a) Error — no se puede sumar texto', 'es_correcta' => false, 'orden' => 1],
                    ['texto' => 'b) Hola mundo', 'es_correcta' => true, 'orden' => 2],
                    ['texto' => 'c) Hola+mundo', 'es_correcta' => false, 'orden' => 3],
                    ['texto' => 'd) No imprime nada', 'es_correcta' => false, 'orden' => 4],
                ],
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 2,
                'tipo' => 'quiz_texto',
                'titulo' => '¿Cómo se llama la función para mostrar texto?',
                'enunciado' => <<<'MD'
¿Cómo se llama la función de Python que sirve para **mostrar texto en la pantalla**?

Escribe solo el nombre de la función, sin paréntesis ni mayúsculas extras.
MD,
                'respuesta_correcta' => 'print',
                'es_obligatorio' => true,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'Es la primera función que aprendiste en este módulo. Empieza con "p".',
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 3,
                'tipo' => 'codigo_libre',
                'titulo' => 'Saludo personalizado con input() y f-string',
                'enunciado' => <<<'MD'
Crea un programa en Python que:

1. Le pregunte al usuario su nombre usando `input()`
2. Lo salude diciendo exactamente: **"¡Hola, [nombre]! Bienvenido a Python."**

**Ejemplo de ejecución:**
```
¿Cuál es tu nombre? Santiago
¡Hola, Santiago! Bienvenido a Python.
```

**Requisitos:**
- Usa `input()` para pedir el nombre
- Usa `print()` con un **f-string** para el saludo
- El mensaje debe ser exactamente como el ejemplo (con los signos ¡!)

Escribe el código en VS Code, ejecútalo y muéstrale el resultado a papá.
MD,
                'codigo_base' => <<<'PY'
# Tu código aquí
nombre = input("¿Cuál es tu nombre? ")
# Completa el print usando un f-string
PY,
                'solucion' => <<<'PY'
nombre = input("¿Cuál es tu nombre? ")
print(f"¡Hola, {nombre}! Bienvenido a Python.")
PY,
                'es_obligatorio' => true,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'Recuerda: f-string se escribe con f antes de las comillas: f"texto {variable}"',
            ],
            // --- OPCIONALES ---
            [
                'modulo_id' => $modulo1->id,
                'orden' => 4,
                'tipo' => 'quiz_opcion',
                'titulo' => '¿Qué tipo de dato es edad = 11?',
                'enunciado' => <<<'MD'
¿Cuál es el tipo de dato de la variable `edad` en este código?

```python
edad = 11
```
MD,
                'respuesta_correcta' => 'c',
                'es_obligatorio' => false,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'Los números enteros (sin punto decimal) son de un tipo específico en Python.',
                'opciones' => [
                    ['texto' => 'a) str (texto)', 'es_correcta' => false, 'orden' => 1],
                    ['texto' => 'b) float (decimal)', 'es_correcta' => false, 'orden' => 2],
                    ['texto' => 'c) int (entero)', 'es_correcta' => true, 'orden' => 3],
                    ['texto' => 'd) bool (verdadero/falso)', 'es_correcta' => false, 'orden' => 4],
                ],
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 5,
                'tipo' => 'codigo_libre',
                'titulo' => 'Presentación completa con variables',
                'enunciado' => <<<'MD'
Crea variables con tu información personal y luego imprime una presentación.

**Requisitos:**
- Variable `nombre` con tu nombre
- Variable `edad` con tu edad (número entero)
- Variable `ciudad` con tu ciudad favorita
- Variable `equipo` con tu equipo de fútbol favorito
- Un `print()` que muestre todo en una sola frase usando f-string

**Ejemplo de salida:**
```
Me llamo Santiago, tengo 11 años, vivo en Medellín y soy del Nacional. ¡Arriba el Verde! 🟢
```
MD,
                'solucion' => <<<'PY'
nombre = "Santiago"
edad = 11
ciudad = "Medellín"
equipo = "Nacional"

print(f"Me llamo {nombre}, tengo {edad} años, vivo en {ciudad} y soy del {equipo}. ¡Arriba el Verde! 🟢")
PY,
                'es_obligatorio' => false,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'Define cada variable primero, luego úsalas todas en el f-string.',
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 6,
                'tipo' => 'quiz_texto',
                'titulo' => '¿Qué símbolo se usa para comentarios?',
                'enunciado' => <<<'MD'
En Python, los **comentarios** son líneas que Python ignora al ejecutar el código. Sirven para explicar qué hace el código.

¿Qué símbolo se escribe al principio de una línea para que sea un comentario?

Escribe solo el símbolo.
MD,
                'respuesta_correcta' => '#',
                'es_obligatorio' => false,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'Lo encontrarás en tu teclado, es el mismo símbolo que se usa en los hashtags de redes sociales.',
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 7,
                'tipo' => 'codigo_libre',
                'titulo' => 'Calculadora de edad básica',
                'enunciado' => <<<'MD'
Crea un programa que:

1. Le pida al usuario su **año de nacimiento**
2. Calcule su **edad aproximada** (usando el año 2024)
3. Imprima el resultado con un mensaje bonito

**Ejemplo:**
```
¿En qué año naciste? 2013
Tienes aproximadamente 11 años 🎂
```

**Hint:** Recuerda que `input()` devuelve texto. Debes convertir el año a número con `int()`.
MD,
                'codigo_base' => <<<'PY'
# Pide el año de nacimiento y conviértelo a número entero
año_nacimiento = int(input("¿En qué año naciste? "))

# Calcula la edad
año_actual = 2024
edad = año_actual - año_nacimiento

# Imprime el resultado con f-string
PY,
                'solucion' => <<<'PY'
año_nacimiento = int(input("¿En qué año naciste? "))
año_actual = 2024
edad = año_actual - año_nacimiento
print(f"Tienes aproximadamente {edad} años 🎂")
PY,
                'es_obligatorio' => false,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'Edad = año actual - año de nacimiento. No olvides convertir el input a int.',
            ],
            [
                'modulo_id' => $modulo1->id,
                'orden' => 8,
                'tipo' => 'mini_proyecto',
                'titulo' => '🏆 PROYECTO: Calculadora de Edad Completa',
                'enunciado' => <<<'MD'
## Proyecto del Módulo 1 — Calculadora de Edad

¡Este es el proyecto final del módulo! Vas a crear una calculadora de edad completa.

### Tu programa debe:

1. **Pedir** el nombre del usuario
2. **Pedir** el año de nacimiento
3. **Calcular y mostrar:**
   - La edad actual (aproximada con año 2024)
   - En qué año cumple 18
   - En qué año cumple 21
4. **Mostrar un mensaje motivador** personalizado con el nombre

### Ejemplo de ejecución:
```
=== Calculadora de Edad ===
¿Cómo te llamas? Santiago
¿En qué año naciste? 2013

--- Resultados ---
¡Hola, Santiago! 👋
Tienes aproximadamente 11 años.
Cumples 18 en el año 2031. ¡Faltan 7 años!
Cumples 21 en el año 2034. ¡Faltan 10 años!

¡Sigue así, Santiago! Cuando tengas 18, serás todo un adulto. 💪
```

### Requisitos:
- Usa `input()` para pedir datos
- Usa variables para todo
- Usa f-strings en los prints
- Calcula correctamente los años
- El mensaje motivador debe incluir el nombre

Cuando termines, ejecuta el programa y muéstrale la salida a papá.
MD,
                'solucion' => <<<'PY'
print("=== Calculadora de Edad ===")
nombre = input("¿Cómo te llamas? ")
año_nacimiento = int(input("¿En qué año naciste? "))

año_actual = 2024
edad = año_actual - año_nacimiento
año_18 = año_nacimiento + 18
año_21 = año_nacimiento + 21
faltan_18 = año_18 - año_actual
faltan_21 = año_21 - año_actual

print("\n--- Resultados ---")
print(f"¡Hola, {nombre}! 👋")
print(f"Tienes aproximadamente {edad} años.")
print(f"Cumples 18 en el año {año_18}. ¡Faltan {faltan_18} años!")
print(f"Cumples 21 en el año {año_21}. ¡Faltan {faltan_21} años!")
print(f"\n¡Sigue así, {nombre}! Cuando tengas 18, serás todo un adulto. 💪")
PY,
                'es_obligatorio' => false,
                'recompensa_ejercicio' => 2000,
                'recompensa_perfecto' => 3000,
                'pista' => 'Primero calcula todas las variables, luego imprime todo. Año que cumple 18 = año_nacimiento + 18.',
            ],
        ];

        foreach ($ejercicios as $data) {
            $opciones = $data['opciones'] ?? [];
            unset($data['opciones']);

            $ejercicio = Ejercicio::create($data);

            foreach ($opciones as $opcion) {
                EjercicioOpcion::create(array_merge($opcion, ['ejercicio_id' => $ejercicio->id]));
            }
        }
    }
}
