<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo2Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'tomando-decisiones')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => 'Si esto... entonces aquello: el if',
                'contenido' => <<<'MD'
# Si esto... entonces aquello: el `if` 🤔

En la vida real tomas decisiones todo el tiempo:
- **Si** llueve → llevas paraguas
- **Si** tienes tarea → no puedes jugar

En Python funciona igual. El `if` ejecuta un bloque de código **solo cuando una condición es verdadera**.

## Estructura

```python
if condición:
    # esto solo se ejecuta si la condición es True
    print("¡La condición se cumplió!")
```

> ⚠️ La sangría (4 espacios o 1 tab) es **obligatoria**. Python usa la sangría para saber qué código pertenece al `if`.

## Ejemplo real

```python
goles = 3

if goles > 0:
    print("¡Ganamos el partido! 🎉")

print("Este print siempre se ejecuta")
```

Resultado:
```
¡Ganamos el partido! 🎉
Este print siempre se ejecuta
```

## Con variables del usuario

```python
edad = int(input("¿Cuántos años tienes? "))

if edad >= 18:
    print("Puedes sacar la libreta de conducción")

if edad < 18:
    print(f"Te faltan {18 - edad} años para sacar la libreta")
```
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'teoria',
                'titulo' => 'Pero ¿y si no? El else',
                'contenido' => <<<'MD'
# Pero ¿y si no? El `else` 🔄

El `if` solo hace algo si la condición es verdadera. Pero ¿qué pasa si es falsa? Ahí entra el `else`.

## Estructura

```python
if condición:
    # se ejecuta si la condición es True
else:
    # se ejecuta si la condición es False
```

## Ejemplo con fútbol

```python
goles_nacional = int(input("Goles del Nacional: "))
goles_rival    = int(input("Goles del rival: "))

if goles_nacional > goles_rival:
    print("¡El Verde ganó! 🟢🏆")
else:
    print("No fue el día... habrá revancha 💪")
```

## Ejemplo con notas

```python
nota = float(input("¿Cuál fue tu nota? "))

if nota >= 3.5:
    print("¡Aprobaste! 🎉")
else:
    print("Hay que estudiar más para la próxima 📚")
```

## Importante

Con `if / else` siempre se ejecuta **uno de los dos** bloques, nunca los dos al mismo tiempo.

```python
x = 10
if x > 5:
    print("mayor")   # ← esto se ejecuta
else:
    print("menor")   # ← esto NO se ejecuta
```
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'teoria',
                'titulo' => 'Múltiples opciones: elif',
                'contenido' => <<<'MD'
# Múltiples opciones: `elif` 🎯

¿Y si tienes más de dos opciones? Por ejemplo, las notas tienen rangos: excelente, bien, regular, malo.

Para eso existe `elif` (abreviación de "else if"):

## Estructura

```python
if condición1:
    # si condición1 es True
elif condición2:
    # si condición1 es False Y condición2 es True
elif condición3:
    # si condición1 y 2 son False Y condición3 es True
else:
    # si ninguna condición fue True
```

## Ejemplo: sistema de notas

```python
nota = float(input("Ingresa tu nota (0.0 a 5.0): "))

if nota >= 4.5:
    print("⭐ ¡Excelente! Eres un crack del estudio.")
elif nota >= 3.5:
    print("✅ Bien hecho. Aprobaste con buena nota.")
elif nota >= 3.0:
    print("⚠️ Pasaste raspando... puedes mejorar.")
else:
    print("❌ Reprobaste. ¡A estudiar más para la próxima!")
```

## Ejemplo: menú de opciones

```python
opcion = int(input("¿Qué quieres? 1=Pizza 2=Hamburguesa 3=Bandeja Paisa: "))

if opcion == 1:
    print("🍕 ¡Pediste pizza!")
elif opcion == 2:
    print("🍔 ¡Pediste hamburguesa!")
elif opcion == 3:
    print("🍽️ ¡Pediste bandeja paisa! La mejor comida de Medellín.")
else:
    print("Opción no válida")
```

## 💡 Truco

Python revisa las condiciones **en orden**, de arriba a abajo. En cuanto encuentra una verdadera, ejecuta ese bloque y **no revisa las demás**.
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'Comparando cosas: ==, !=, >, <, >=, <=',
                'contenido' => <<<'MD'
# Comparando cosas: operadores de comparación ⚖️

Para que el `if` funcione necesitas **condiciones**. Las condiciones se crean con operadores de comparación.

## Los 6 operadores

| Operador | Significado | Ejemplo | Resultado |
|----------|-------------|---------|-----------|
| `==` | igual a | `5 == 5` | `True` |
| `!=` | diferente de | `5 != 3` | `True` |
| `>` | mayor que | `10 > 3` | `True` |
| `<` | menor que | `3 < 10` | `True` |
| `>=` | mayor o igual | `5 >= 5` | `True` |
| `<=` | menor o igual | `4 <= 3` | `False` |

## ⚠️ Cuidado: = vs ==

```python
edad = 11      # ← asignación: guarda el valor 11 en edad
edad == 11     # ← comparación: pregunta si edad es igual a 11
```

Es el error más común en Python. `=` guarda, `==` compara.

## Comparando texto

```python
equipo = "Nacional"

if equipo == "Nacional":
    print("¡Arriba el Verde! 🟢")

if equipo != "Millonarios":
    print("Definitivamente no eres del equipo azul 😄")
```

## Ejemplos prácticos

```python
puntaje = 85

if puntaje >= 90:
    print("Nota: A")
elif puntaje >= 80:
    print("Nota: B")   # ← este se ejecuta (85 >= 80 es True)
elif puntaje >= 70:
    print("Nota: C")
else:
    print("Nota: D")
```
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'teoria',
                'titulo' => 'Combinando condiciones: and, or, not',
                'contenido' => <<<'MD'
# Combinando condiciones: `and`, `or`, `not` 🔗

A veces necesitas verificar **varias condiciones al mismo tiempo**. Para eso existen los operadores lógicos.

## `and` — ambas deben ser verdaderas

```python
edad = 15
tiene_permiso = True

if edad >= 13 and tiene_permiso:
    print("Puedes crear una cuenta en esta app")
else:
    print("No cumples los requisitos")
```

`and` es `True` solo si **las dos** condiciones son `True`.

## `or` — al menos una debe ser verdadera

```python
dia = "sábado"

if dia == "sábado" or dia == "domingo":
    print("¡No hay colegio! 🎉")
else:
    print("Toca madrugar... 😴")
```

`or` es `True` si **al menos una** condición es `True`.

## `not` — invierte el valor

```python
lloviendo = False

if not lloviendo:
    print("¡Podemos jugar fútbol afuera! ⚽")
```

`not True` = `False` y `not False` = `True`.

## Tabla de verdad rápida

```
True  and True  = True
True  and False = False
False and True  = False
False and False = False

True  or  True  = True
True  or  False = True
False or  True  = True
False or  False = False
```

## Ejemplo combinado

```python
nota = 4.2
asistencia = 85  # porcentaje

if nota >= 3.5 and asistencia >= 80:
    print("✅ Promovido al siguiente grado")
elif nota >= 3.5 and asistencia < 80:
    print("⚠️ Nota bien, pero faltaste mucho")
else:
    print("❌ Debes recuperar materias")
```
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Introducción a random — números al azar',
                'contenido' => <<<'MD'
# Introducción a `random` — números al azar 🎲

Python tiene una librería llamada `random` que genera números aleatorios. Es perfecta para juegos.

## Importar la librería

```python
import random
```

La palabra `import` le dice a Python "necesito usar esta caja de herramientas".

## Generar un número entero aleatorio

```python
import random

numero = random.randint(1, 10)
print(f"El número secreto es: {numero}")
```

`randint(1, 10)` genera un número entre 1 y 10, **incluyendo** el 1 y el 10.

## Ejemplo: ¿Adivina el número?

```python
import random

secreto = random.randint(1, 100)
intento = int(input("Adivina el número del 1 al 100: "))

if intento == secreto:
    print("🎉 ¡Lo adivinaste!")
elif intento < secreto:
    print(f"⬆️ El número es mayor que {intento}")
else:
    print(f"⬇️ El número es menor que {intento}")

print(f"(El número era: {secreto})")
```

## Otros usos de random

```python
import random

# Elegir un elemento al azar de una lista
equipos = ["Nacional", "América", "Junior", "Millonarios"]
print(random.choice(equipos))  # ← elige uno al azar

# Número decimal entre 0.0 y 1.0
probabilidad = random.random()
print(f"Probabilidad de lluvia: {probabilidad:.0%}")
```

## 💡 Dato curioso

Los números de `random` no son **realmente** aleatorios — el computador usa una fórmula matemática muy complicada para parecer aleatorio. Por eso se llaman *pseudoaleatorios*.
MD,
            ],
        ];

        foreach ($lecciones as $data) {
            Leccion::create(array_merge(['modulo_id' => $modulo->id, 'lenguaje' => 'python'], $data));
        }

        // ——— EJERCICIOS ———
        $ejercicios = [
            // Obligatorios
            [
                'orden' => 1, 'tipo' => 'quiz_opcion', 'es_obligatorio' => true,
                'titulo' => '¿Qué imprime el if/else?',
                'enunciado' => "¿Qué imprime este código?\n\n```python\nx = 10\nif x > 5:\n    print(\"grande\")\nelse:\n    print(\"pequeño\")\n```",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => '10 > 5 es True, entonces se ejecuta el bloque del if.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) pequeño', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) grande', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) grande y pequeño', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) No imprime nada', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué palabra agrega condición adicional al if?',
                'enunciado' => "Cuando necesitas agregar **más de una condición** a un `if`, usas esta palabra clave.\n\n¿Cuál es? (escribe solo la palabra)",
                'respuesta_correcta' => 'elif',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Es una contracción de "else if". Empieza con "el".',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Sistema de notas colombiano',
                'enunciado' => "Crea un programa que pida una **nota de 0 a 100** e imprima:\n\n- 90–100: `\"🌟 ¡Excelente! Sacaste un 10.\"`\n- 70–89: `\"✅ Bien hecho, aprobaste.\"`\n- 50–69: `\"⚠️ Puedes mejorar.\"`\n- 0–49: `\"❌ A estudiar más para la próxima.\"`\n\n**Ejemplo:**\n```\n¿Cuál es tu nota? 85\n✅ Bien hecho, aprobaste.\n```",
                'codigo_base' => "nota = int(input(\"¿Cuál es tu nota? \"))\n\n# Usa if / elif / else\n",
                'solucion' => "nota = int(input(\"¿Cuál es tu nota? \"))\n\nif nota >= 90:\n    print(\"🌟 ¡Excelente! Sacaste un 10.\")\nelif nota >= 70:\n    print(\"✅ Bien hecho, aprobaste.\")\nelif nota >= 50:\n    print(\"⚠️ Puedes mejorar.\")\nelse:\n    print(\"❌ A estudiar más para la próxima.\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Empieza con la nota más alta (>= 90) y baja. Si inviertes el orden puede fallar.',
            ],
            // Opcionales
            [
                'orden' => 4, 'tipo' => 'quiz_opcion', 'es_obligatorio' => false,
                'titulo' => '¿Qué significa el operador !=?',
                'enunciado' => "¿Qué significa el operador `!=` en Python?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'El símbolo ! en inglés significa "no". Entonces != significa "no igual".',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Mayor que', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Diferente de (no igual)', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) Menor o igual', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) Asignación', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Calculadora par/impar',
                'enunciado' => "Crea un programa que pida un número y diga si es **par** o **impar**.\n\n**Pista:** Un número es par si el residuo de dividirlo entre 2 es 0. En Python, el operador `%` calcula el residuo.\n\n```python\n10 % 2 == 0  # True (par)\n7  % 2 == 1  # True (impar)\n```\n\n**Ejemplo:**\n```\n¿Qué número? 7\n7 es impar\n```",
                'codigo_base' => "numero = int(input(\"¿Qué número? \"))\n\n# El operador % da el residuo de la división\n# Si numero % 2 == 0, es par\n",
                'solucion' => "numero = int(input(\"¿Qué número? \"))\n\nif numero % 2 == 0:\n    print(f\"{numero} es par\")\nelse:\n    print(f\"{numero} es impar\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa el operador % (módulo). Si numero % 2 es igual a 0, es par.',
            ],
            [
                'orden' => 6, 'tipo' => 'quiz_texto', 'es_obligatorio' => false,
                'titulo' => '¿Cómo se importa el módulo random?',
                'enunciado' => "Para usar números aleatorios en Python necesitas importar la librería `random`.\n\n¿Cómo se escribe esa línea de importación?",
                'respuesta_correcta' => 'import random',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Empieza con la palabra "import" seguida del nombre de la librería.',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => '¿Puedes entrar al concierto?',
                'enunciado' => "Crea un programa que pida la **edad** del usuario y diga:\n\n- Si tiene **18 o más**: `\"✅ Puedes entrar al concierto.\"`\n- Si tiene **entre 13 y 17**: `\"⚠️ Puedes entrar con un adulto.\"`\n- Si tiene **menos de 13**: `\"❌ Lo siento, no puedes entrar.\"`",
                'solucion' => "edad = int(input(\"¿Cuántos años tienes? \"))\n\nif edad >= 18:\n    print(\"✅ Puedes entrar al concierto.\")\nelif edad >= 13:\n    print(\"⚠️ Puedes entrar con un adulto.\")\nelse:\n    print(\"❌ Lo siento, no puedes entrar.\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa elif para el rango de 13-17. Recuerda que Python revisa las condiciones en orden de arriba a abajo.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Juego Adivina el Número',
                'enunciado' => "## Proyecto del Módulo 2 — Adivina el Número\n\nCrea el juego completo de adivinar un número secreto.\n\n### Tu programa debe:\n\n1. Generar un número aleatorio entre 1 y 100 (`random.randint`)\n2. Pedir al usuario que adivine\n3. Dar pistas: \"⬆️ Más alto\" o \"⬇️ Más bajo\"\n4. **Contar los intentos** y mostrarlo al final\n5. Al adivinar, mostrar: `\"🎉 ¡Lo lograste en X intentos!\"`\n\n### Ejemplo:\n```\n=== Adivina el número (1-100) ===\nIntento 1: 50\n⬆️ Más alto\nIntento 2: 75\n⬇️ Más bajo\nIntento 3: 63\n🎉 ¡Lo lograste en 3 intentos!\n```\n\n**Nota:** Por ahora solo necesitas un intento (el while lo aprenderás en el módulo 3). Haz la versión con 1 intento y la pista.",
                'codigo_base' => "import random\n\nsecreto = random.randint(1, 100)\nprint(\"=== Adivina el número (1-100) ===\")\n\n# Pide el número y da la pista\n",
                'solucion' => "import random\n\nsecreto = random.randint(1, 100)\nprint(\"=== Adivina el número (1-100) ===\")\n\nintento = int(input(\"Tu número: \"))\n\nif intento == secreto:\n    print(\"🎉 ¡Lo adivinaste al primer intento! ¡Eres un genio!\")\nelif intento < secreto:\n    print(f\"⬆️ Más alto. El número era {secreto}.\")\nelse:\n    print(f\"⬇️ Más bajo. El número era {secreto}.\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Genera el número con random.randint(1, 100), pide el intento, y usa if/elif/else para dar la pista.',
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
