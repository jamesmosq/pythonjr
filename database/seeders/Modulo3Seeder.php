<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo3Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'repitiendo-cosas')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => 'El bucle for — repetir un número fijo de veces',
                'contenido' => <<<'MD'
# El bucle `for` — repetir sin cansarse 🔁

Imagina que tienes que imprimir "¡Arriba el Verde!" 100 veces. ¿Lo harías con 100 `print()`? Claro que no. Para eso existe el bucle `for`.

## Estructura básica

```python
for i in range(5):
    print("¡Arriba el Verde!")
```

Resultado:
```
¡Arriba el Verde!
¡Arriba el Verde!
¡Arriba el Verde!
¡Arriba el Verde!
¡Arriba el Verde!
```

## ¿Qué es la variable `i`?

`i` es un contador que cambia en cada vuelta (iteración). Puedes usar cualquier nombre.

```python
for vuelta in range(4):
    print(f"Vuelta {vuelta}")
```

Resultado:
```
Vuelta 0
Vuelta 1
Vuelta 2
Vuelta 3
```

> 💡 El contador empieza en **0**, no en 1. Eso es importante en Python.

## Usando el contador en el código

```python
for numero in range(1, 6):
    cuadrado = numero * numero
    print(f"{numero} al cuadrado es {cuadrado}")
```

Resultado:
```
1 al cuadrado es 1
2 al cuadrado es 4
3 al cuadrado es 9
4 al cuadrado es 16
5 al cuadrado es 25
```
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'teoria',
                'titulo' => 'range() — generando secuencias de números',
                'contenido' => <<<'MD'
# `range()` — generando secuencias de números 🔢

`range()` genera una secuencia de números. Tiene tres formas de uso:

## Forma 1: solo el final

```python
range(5)  # genera: 0, 1, 2, 3, 4
```

```python
for i in range(5):
    print(i)  # 0 1 2 3 4
```

## Forma 2: inicio y final

```python
range(1, 6)  # genera: 1, 2, 3, 4, 5
```

```python
for i in range(1, 11):
    print(i)  # 1 2 3 4 5 6 7 8 9 10
```

## Forma 3: inicio, final y paso

```python
range(0, 20, 5)  # genera: 0, 5, 10, 15
```

```python
for i in range(0, 101, 10):
    print(i)  # 0 10 20 30 40 50 60 70 80 90 100
```

## Contando hacia atrás

```python
for i in range(10, 0, -1):
    print(i)  # 10 9 8 7 6 5 4 3 2 1
print("¡Despegue! 🚀")
```

## Ejemplos prácticos

```python
# Suma de los primeros 10 números
total = 0
for i in range(1, 11):
    total = total + i
print(f"La suma es: {total}")  # La suma es: 55

# Números pares del 2 al 20
for par in range(2, 21, 2):
    print(par)  # 2 4 6 8 10 12 14 16 18 20
```
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'teoria',
                'titulo' => 'El bucle while — repetir mientras algo sea verdad',
                'contenido' => <<<'MD'
# El bucle `while` — repetir mientras algo sea verdad ⏳

El `for` repite **un número fijo** de veces. El `while` repite **mientras una condición sea True**.

## Estructura

```python
while condición:
    # se repite mientras condición sea True
```

## Ejemplo básico

```python
vida = 3

while vida > 0:
    print(f"Tienes {vida} vidas")
    vida = vida - 1

print("Game over 💀")
```

Resultado:
```
Tienes 3 vidas
Tienes 2 vidas
Tienes 1 vidas
Game over 💀
```

## Pedir datos hasta que el usuario acierte

```python
import random

secreto = random.randint(1, 10)
intento = 0

while intento != secreto:
    intento = int(input("Adivina el número (1-10): "))
    if intento < secreto:
        print("⬆️ Más alto")
    elif intento > secreto:
        print("⬇️ Más bajo")

print("🎉 ¡Lo adivinaste!")
```

## ⚠️ Bucle infinito — el bug más común

```python
# ❌ NUNCA hagas esto — nunca termina
while True:
    print("Esto corre para siempre...")
```

Si accidentalmente creas un bucle infinito en tu terminal, presiona **Ctrl + C** para detenerlo.

## `while True` con `break` (la forma correcta)

```python
while True:
    respuesta = input("¿Continuar? (s/n): ")
    if respuesta == "n":
        break  # salir del bucle
    print("¡Seguimos!")
```
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'Saliendo del bucle: break',
                'contenido' => <<<'MD'
# Saliendo del bucle: `break` 🛑

`break` detiene el bucle **inmediatamente**, sin importar si la condición sigue siendo True.

## Ejemplo: buscar en una lista

```python
equipos = ["Junior", "Nacional", "América", "Millos", "Santa Fe"]

for equipo in equipos:
    if equipo == "Nacional":
        print(f"¡Encontré al Verde: {equipo}! 🟢")
        break  # ya no necesito seguir buscando
    print(f"Revisando {equipo}...")
```

Resultado:
```
Revisando Junior...
¡Encontré al Verde: Nacional! 🟢
```

Sin `break`, el bucle habría revisado todos los equipos aunque ya encontró al Nacional.

## Con `while` — el uso más común de `break`

```python
intentos = 0
max_intentos = 3

while True:
    password = input("Contraseña: ")
    intentos += 1  # esto es igual a: intentos = intentos + 1

    if password == "python123":
        print("✅ ¡Acceso concedido!")
        break
    elif intentos >= max_intentos:
        print("❌ Demasiados intentos. Bloqueado.")
        break
    else:
        print(f"❌ Incorrecto. Tienes {max_intentos - intentos} intentos más.")
```

## `break` y `for`

```python
for i in range(100):
    if i == 5:
        break
    print(i)
# Imprime solo: 0 1 2 3 4
```
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'teoria',
                'titulo' => 'Saltando una vuelta: continue',
                'contenido' => <<<'MD'
# Saltando una vuelta: `continue` ⏭️

`continue` salta **el resto de la vuelta actual** y pasa a la siguiente. A diferencia de `break`, no sale del bucle.

## Ejemplo: saltarse los números impares

```python
for i in range(1, 11):
    if i % 2 != 0:  # si es impar
        continue    # salta esta vuelta
    print(i)        # solo imprime los pares
```

Resultado:
```
2
4
6
8
10
```

## Otro ejemplo: filtrar datos inválidos

```python
notas = [4.5, -1, 3.2, 110, 2.8, 5.0]

print("Notas válidas:")
for nota in notas:
    if nota < 0 or nota > 5:
        continue  # salta las notas inválidas
    print(f"  {nota}")
```

Resultado:
```
Notas válidas:
  4.5
  3.2
  2.8
  5.0
```

## `break` vs `continue`

| | `break` | `continue` |
|--|---------|-----------|
| ¿Qué hace? | Sale del bucle | Salta esta vuelta |
| ¿El bucle sigue? | No | Sí |
| ¿Cuándo usarlo? | Cuando ya no necesitas más iteraciones | Cuando quieres ignorar ciertos casos |
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Bucles dentro de bucles (anidados)',
                'contenido' => <<<'MD'
# Bucles dentro de bucles 🌀

Puedes poner un bucle `for` adentro de otro. A esto se le llama **bucle anidado**.

## Ejemplo: tabla de multiplicar

```python
for i in range(1, 4):
    for j in range(1, 4):
        print(f"{i} × {j} = {i*j}")
    print("---")
```

Resultado:
```
1 × 1 = 1
1 × 2 = 2
1 × 3 = 3
---
2 × 1 = 2
2 × 2 = 4
2 × 3 = 6
---
3 × 1 = 3
3 × 2 = 6
3 × 3 = 9
---
```

## Cómo funciona

1. El bucle **exterior** empieza con `i = 1`
2. El bucle **interior** corre completo (j = 1, 2, 3)
3. Vuelve al exterior: `i = 2`
4. El interior corre completo de nuevo
5. Y así hasta que el exterior termine

## Ejemplo: dibujar con estrellas

```python
filas = 5

for i in range(1, filas + 1):
    for j in range(i):
        print("⭐", end="")  # end="" evita el salto de línea
    print()  # salto de línea al final de cada fila
```

Resultado:
```
⭐
⭐⭐
⭐⭐⭐
⭐⭐⭐⭐
⭐⭐⭐⭐⭐
```

## 💡 Consejo

Los bucles anidados pueden ser lentos si los números son muy grandes. Para proyectos pequeños como los de este módulo, está perfecto.
MD,
            ],
        ];

        foreach ($lecciones as $data) {
            Leccion::create(array_merge(['modulo_id' => $modulo->id, 'lenguaje' => 'python'], $data));
        }

        // ——— EJERCICIOS ———
        $ejercicios = [
            [
                'orden' => 1, 'tipo' => 'quiz_opcion', 'es_obligatorio' => true,
                'titulo' => '¿Cuántas veces imprime "hola"?',
                'enunciado' => "¿Cuántas veces imprime `\"hola\"` este código?\n\n```python\nfor i in range(5):\n    print(\"hola\")\n```",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'range(5) genera los números 0, 1, 2, 3, 4. Cuenta cuántos son.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) 4 veces', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) 5 veces', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) 6 veces', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) 1 vez', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué función genera la secuencia 0, 1, 2, ..., 9?',
                'enunciado' => "¿Cómo se llama la función que genera una secuencia de números del 0 al 9?\n\nEscribe la función con su argumento (por ejemplo: `range(5)`).",
                'respuesta_correcta' => 'range(10)',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'range(n) genera números desde 0 hasta n-1. Para llegar al 9 necesitas n=10.',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Tabla de multiplicar personalizada',
                'enunciado' => "Crea un programa que:\n\n1. Pida al usuario un número\n2. Imprima la **tabla de multiplicar** de ese número del 1 al 10\n\n**Ejemplo (tabla del 7):**\n```\n¿De qué número quieres la tabla? 7\n7 × 1 = 7\n7 × 2 = 14\n7 × 3 = 21\n...\n7 × 10 = 70\n```",
                'codigo_base' => "numero = int(input(\"¿De qué número quieres la tabla? \"))\n\nfor i in range(1, 11):\n    # Calcula e imprime la multiplicación\n    pass\n",
                'solucion' => "numero = int(input(\"¿De qué número quieres la tabla? \"))\n\nfor i in range(1, 11):\n    resultado = numero * i\n    print(f\"{numero} × {i} = {resultado}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa range(1, 11) para que i vaya del 1 al 10. El resultado es numero * i.',
            ],
            [
                'orden' => 4, 'tipo' => 'quiz_opcion', 'es_obligatorio' => false,
                'titulo' => '¿Qué hace break dentro de un bucle?',
                'enunciado' => "¿Qué hace la instrucción `break` dentro de un bucle `for` o `while`?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Piensa en "break" como "romper". Rompe el bucle.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Salta a la siguiente vuelta del bucle', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Sale del bucle inmediatamente', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) Pausa el programa 1 segundo', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) Reinicia el contador del bucle', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Cuenta regresiva para el despegue',
                'enunciado' => "Crea un programa que imprima una **cuenta regresiva** del 10 al 0 y al final diga `\"¡Despegue! 🚀\"`.\n\n**Ejemplo:**\n```\n10\n9\n8\n...\n1\n0\n¡Despegue! 🚀\n```",
                'solucion' => "for i in range(10, -1, -1):\n    print(i)\nprint(\"¡Despegue! 🚀\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa range(10, -1, -1). El -1 como paso hace que cuente hacia atrás. El -1 como final asegura que el 0 se incluya.',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Suma de los primeros 100 números',
                'enunciado' => "Crea un programa que calcule la **suma de todos los números del 1 al 100** usando un bucle `for`.\n\nAl final debe imprimir el resultado.\n\n**Respuesta esperada:** La suma es 5050",
                'codigo_base' => "total = 0\n\nfor i in range(1, 101):\n    # Suma i al total\n    pass\n\nprint(f\"La suma es {total}\")\n",
                'solucion' => "total = 0\n\nfor i in range(1, 101):\n    total = total + i\n\nprint(f\"La suma es {total}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Necesitas una variable "total" que empiece en 0 y dentro del bucle hagas: total = total + i.',
            ],
            [
                'orden' => 7, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Tablas de multiplicar del 1 al 10',
                'enunciado' => "## Proyecto del Módulo 3 — Tablas de multiplicar\n\nCrea un programa que imprima **todas las tablas de multiplicar del 1 al 10** en formato bonito.\n\n### Formato esperado:\n```\n=== TABLA DEL 1 ===\n1 × 1 = 1\n1 × 2 = 2\n...\n1 × 10 = 10\n\n=== TABLA DEL 2 ===\n2 × 1 = 2\n...\n```\n\n### Requisitos:\n- Usa **bucles anidados** (un for dentro de otro)\n- El título de cada tabla debe tener el número\n- Deja una línea en blanco entre tablas",
                'codigo_base' => "for tabla in range(1, 11):\n    print(f\"\\n=== TABLA DEL {tabla} ===\")\n    for numero in range(1, 11):\n        # Imprime la multiplicación\n        pass\n",
                'solucion' => "for tabla in range(1, 11):\n    print(f\"\\n=== TABLA DEL {tabla} ===\")\n    for numero in range(1, 11):\n        print(f\"{tabla} × {numero} = {tabla * numero}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Necesitas dos for anidados: el de afuera controla la tabla (1-10) y el de adentro los multiplicadores (1-10).',
            ],
            [
                'orden' => 8, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => '⚡ DESAFÍO: FizzBuzz',
                'enunciado' => "## FizzBuzz — el clásico desafío de programación\n\nEste es el ejercicio más famoso en entrevistas de trabajo de programadores. ¡Hazlo y demuestra que eres un crack!\n\n### Reglas:\nImprime los números del **1 al 50**, pero:\n- Si el número es múltiplo de 3 → imprime `\"Fizz\"`\n- Si el número es múltiplo de 5 → imprime `\"Buzz\"`\n- Si es múltiplo de **ambos** (3 y 5) → imprime `\"FizzBuzz\"`\n- Si no es múltiplo de ninguno → imprime el número normal\n\n### Ejemplo (primeros 16):\n```\n1\n2\nFizz\n4\nBuzz\nFizz\n7\n8\nFizz\nBuzz\n11\nFizz\n13\n14\nFizzBuzz\n16\n```\n\n💡 **Pista:** Un número es múltiplo de 3 si `numero % 3 == 0`",
                'solucion' => "for numero in range(1, 51):\n    if numero % 3 == 0 and numero % 5 == 0:\n        print(\"FizzBuzz\")\n    elif numero % 3 == 0:\n        print(\"Fizz\")\n    elif numero % 5 == 0:\n        print(\"Buzz\")\n    else:\n        print(numero)\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => '¡IMPORTANTE! Verifica FizzBuzz PRIMERO (antes que Fizz y Buzz). Si lo haces al revés no funciona bien.',
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
