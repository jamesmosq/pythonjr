<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo4Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'funciones-magicas')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => '¿Qué es una función y por qué usarla?',
                'contenido' => <<<'MD'
# ¿Qué es una función y por qué usarla? ✨

Imagina que cada vez que quieres hacer una pizza tienes que recordar todos los pasos: amasar, poner salsa, queso, hornear... ¿Y si pudieras decir simplemente "hacer_pizza()"?

Eso es una **función**: un bloque de código con nombre que puedes ejecutar cuando quieras, las veces que quieras.

## Sin funciones — código repetido 😫

```python
# Calcular área del rectángulo 1
area1 = 5 * 3
print(f"Área 1: {area1}")

# Calcular área del rectángulo 2
area2 = 8 * 4
print(f"Área 2: {area2}")

# Calcular área del rectángulo 3
area3 = 2 * 7
print(f"Área 3: {area3}")
```

## Con funciones — código limpio 😎

```python
def calcular_area(base, altura):
    return base * altura

print(f"Área 1: {calcular_area(5, 3)}")
print(f"Área 2: {calcular_area(8, 4)}")
print(f"Área 3: {calcular_area(2, 7)}")
```

## ¿Por qué usar funciones?

1. **No repites código** — escribes la lógica una vez
2. **Fácil de corregir** — cambias en un solo lugar
3. **Más legible** — el código dice qué hace, no cómo
4. **Reutilizable** — la misma función en muchos programas

> 💡 Las funciones son el corazón de la programación. ¡Todos los programas grandes están hechos de funciones!
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Definiendo funciones con def',
                'contenido' => <<<'MD'
# Definiendo funciones con `def` 📝

Para crear una función usas la palabra clave `def`:

## Estructura básica

```python
def nombre_de_la_funcion():
    # código que ejecuta la función
    print("¡Hola desde la función!")
```

## Llamar (ejecutar) la función

```python
def saludar():
    print("¡Hola! 👋")
    print("Bienvenido a Python")

# Definir la función no la ejecuta.
# Debes llamarla así:
saludar()
saludar()  # puedes llamarla varias veces
```

Resultado:
```
¡Hola! 👋
Bienvenido a Python
¡Hola! 👋
Bienvenido a Python
```

## Funciones con varias líneas

```python
def mostrar_info_partido():
    print("=== PARTIDO NACIONAL vs MILLONARIOS ===")
    print("Estadio: Atanasio Girardot")
    print("Ciudad: Medellín")
    print("Hora: 8:00 PM")
    print("=" * 40)

mostrar_info_partido()
```

## ⚠️ Orden importante

La función debe estar **definida antes de llamarla**:

```python
# ❌ Error: usas la función antes de definirla
saludar()

def saludar():
    print("Hola")
```

```python
# ✅ Correcto: primero defines, luego llamas
def saludar():
    print("Hola")

saludar()
```
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'teoria',
                'titulo' => 'Parámetros — la función recibe información',
                'contenido' => <<<'MD'
# Parámetros — la función recibe información 📥

Las funciones pueden recibir datos para trabajar con ellos. Esos datos se llaman **parámetros** (o argumentos).

## Sin parámetros vs con parámetros

```python
# Sin parámetros — siempre hace lo mismo
def saludar():
    print("¡Hola, extraño!")

# Con parámetros — se adapta a los datos
def saludar(nombre):
    print(f"¡Hola, {nombre}!")
```

```python
saludar("Santiago")   # ¡Hola, Santiago!
saludar("Nikolas")    # ¡Hola, Nikolas!
saludar("Papá")       # ¡Hola, Papá!
```

## Múltiples parámetros

```python
def presentar_jugador(nombre, equipo, goles):
    print(f"🏃 {nombre} del {equipo} con {goles} goles esta temporada")

presentar_jugador("James Rodríguez", "Rayo Vallecano", 8)
presentar_jugador("Falcao", "Millonarios", 15)
```

Resultado:
```
🏃 James Rodríguez del Rayo Vallecano con 8 goles esta temporada
🏃 Falcao del Millonarios con 15 goles esta temporada
```

## Orden importa

Los argumentos se asignan **en el mismo orden** que los parámetros:

```python
def restar(a, b):
    print(a - b)

restar(10, 3)   # 10 - 3 = 7
restar(3, 10)   # 3 - 10 = -7  ← ¡orden diferente, resultado diferente!
```
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'return — la función devuelve un resultado',
                'contenido' => <<<'MD'
# `return` — la función devuelve un resultado 📤

Hasta ahora las funciones imprimen cosas. Pero la mayoría de funciones útiles **calculan algo y te lo devuelven** para que puedas usarlo.

## Función que imprime vs función que retorna

```python
# Esta función imprime pero no devuelve nada útil
def sumar_print(a, b):
    print(a + b)

# Esta función devuelve el resultado
def sumar(a, b):
    return a + b
```

¿Cuál es mejor? La de `return`:

```python
resultado = sumar(3, 4)    # resultado = 7
doble = sumar(3, 4) * 2   # doble = 14
print(f"La suma es {sumar(5, 10)}")  # La suma es 15
```

Con `print()` no puedes usar el valor para nada más.

## Ejemplo completo

```python
def calcular_promedio(nota1, nota2, nota3):
    suma = nota1 + nota2 + nota3
    promedio = suma / 3
    return promedio

mi_promedio = calcular_promedio(4.5, 3.8, 4.2)

if mi_promedio >= 3.5:
    print(f"✅ Promedio {mi_promedio:.1f} — ¡Aprobaste!")
else:
    print(f"❌ Promedio {mi_promedio:.1f} — A estudiar más.")
```

## `return` detiene la función

```python
def es_positivo(numero):
    if numero > 0:
        return True    # ← la función termina aquí si el número es positivo
    return False       # ← solo llega aquí si no fue positivo
```

## 💡 Buena práctica

Una función debería hacer **una sola cosa** y hacerla bien. No 5 cosas.
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'teoria',
                'titulo' => 'Variables locales vs globales',
                'contenido' => <<<'MD'
# Variables locales vs globales 🏠🌍

Una de las cosas más importantes sobre las funciones: las variables que creas adentro **no existen afuera**.

## Variable local — vive dentro de la función

```python
def calcular():
    resultado = 42    # ← variable LOCAL
    print(resultado)  # ✅ funciona

calcular()
print(resultado)   # ❌ ERROR: resultado no existe aquí
```

Las variables locales se crean cuando la función se llama y **desaparecen** cuando termina.

## Variable global — vive en todo el programa

```python
nombre = "Santiago"   # ← variable GLOBAL

def saludar():
    print(f"Hola, {nombre}")  # ✅ puede leer la variable global

saludar()   # Hola, Santiago
```

## ¿Por qué esto importa?

```python
puntos = 0  # variable global

def ganar_puntos(cantidad):
    puntos = puntos + cantidad  # ❌ ERROR: Python crea una variable local nueva
```

Para modificar una variable global desde una función, necesitas `global`:

```python
puntos = 0

def ganar_puntos(cantidad):
    global puntos
    puntos = puntos + cantidad  # ✅ ahora sí modifica la global

ganar_puntos(10)
ganar_puntos(5)
print(puntos)  # 15
```

## 💡 Consejo de experto

Evita `global` en lo posible. Es mejor **retornar el valor** y reasignarlo:

```python
puntos = 0

def ganar_puntos(puntos_actuales, cantidad):
    return puntos_actuales + cantidad  # retorna el nuevo valor

puntos = ganar_puntos(puntos, 10)   # puntos = 10
puntos = ganar_puntos(puntos, 5)    # puntos = 15
```
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Parámetros con valores por defecto',
                'contenido' => <<<'MD'
# Parámetros con valores por defecto 🎯

A veces quieres que un parámetro tenga un valor predefinido por si el usuario no lo especifica.

## Definir valores por defecto

```python
def saludar(nombre, saludo="¡Hola"):
    print(f"{saludo}, {nombre}!")
```

```python
saludar("Santiago")           # ¡Hola, Santiago!
saludar("Papá")               # ¡Hola, Papá!
saludar("James", "¡Buenas")   # ¡Buenas, James!
```

## Ejemplo: función de presentación

```python
def presentar(nombre, ciudad="Medellín", rol="programador"):
    print(f"Hola, soy {nombre}, {rol} de {ciudad}.")

presentar("Santiago")
presentar("Nikolas", "Itagüí")
presentar("James", "Rionegro", "futbolista")
```

Resultado:
```
Hola, soy Santiago, programador de Medellín.
Hola, soy Nikolas, programador de Itagüí.
Hola, soy James, futbolista de Rionegro.
```

## Regla importante

Los parámetros **con** valor por defecto deben ir **al final**:

```python
# ✅ Correcto
def saludar(nombre, saludo="Hola"):
    pass

# ❌ Error de Python
def saludar(saludo="Hola", nombre):
    pass
```

## Parámetros nombrados (keyword arguments)

También puedes especificar los parámetros por nombre al llamar la función:

```python
def crear_perfil(nombre, edad, ciudad):
    print(f"{nombre}, {edad} años, {ciudad}")

crear_perfil(ciudad="Medellín", nombre="Santiago", edad=11)
# → Santiago, 11 años, Medellín
```
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
                'titulo' => '¿Qué hace return en una función?',
                'enunciado' => "¿Qué hace la instrucción `return` dentro de una función?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Piensa en "devolver". La función devuelve algo al que la llamó.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Imprime el valor en pantalla', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Devuelve un valor y termina la función', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) Repite la función desde el principio', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) Define los parámetros', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué palabra se usa para definir una función?',
                'enunciado' => "¿Qué palabra clave se usa en Python para **definir** (crear) una función?\n\nEscribe solo la palabra.",
                'respuesta_correcta' => 'def',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Es abreviación de "define". Empieza con "d".',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Mis tres primeras funciones',
                'enunciado' => "Crea estas **3 funciones** y pruébalas:\n\n1. `saludar(nombre)` → imprime: `\"¡Hola, [nombre]! ¿Cómo vas?\"`\n2. `area_rectangulo(base, altura)` → retorna el área (base × altura)\n3. `es_par(numero)` → retorna `True` si es par, `False` si es impar\n\n**Prueba cada función:**\n```python\nsaludar(\"Santiago\")\nprint(area_rectangulo(5, 3))   # debe imprimir 15\nprint(es_par(10))              # True\nprint(es_par(7))               # False\n```",
                'codigo_base' => "def saludar(nombre):\n    pass  # reemplaza pass con el código\n\ndef area_rectangulo(base, altura):\n    pass\n\ndef es_par(numero):\n    pass\n\n# Prueba tus funciones aquí\nsaludar(\"Santiago\")\nprint(area_rectangulo(5, 3))\nprint(es_par(10))\nprint(es_par(7))\n",
                'solucion' => "def saludar(nombre):\n    print(f\"¡Hola, {nombre}! ¿Cómo vas?\")\n\ndef area_rectangulo(base, altura):\n    return base * altura\n\ndef es_par(numero):\n    return numero % 2 == 0\n\nsaludar(\"Santiago\")\nprint(area_rectangulo(5, 3))\nprint(es_par(10))\nprint(es_par(7))\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Para es_par: un número es par si numero % 2 == 0. Puedes retornar directamente esa comparación.',
            ],
            [
                'orden' => 4, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Conversor de temperatura',
                'enunciado' => "Crea una función `celsius_a_fahrenheit(celsius)` que convierta temperatura de Celsius a Fahrenheit.\n\n**Fórmula:** `fahrenheit = (celsius × 9/5) + 32`\n\n**Prueba:**\n```python\nprint(celsius_a_fahrenheit(0))    # 32.0\nprint(celsius_a_fahrenheit(100))  # 212.0\nprint(celsius_a_fahrenheit(37))   # 98.6 (temperatura del cuerpo)\n```",
                'solucion' => "def celsius_a_fahrenheit(celsius):\n    return (celsius * 9/5) + 32\n\nprint(celsius_a_fahrenheit(0))\nprint(celsius_a_fahrenheit(100))\nprint(celsius_a_fahrenheit(37))\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'La fórmula es: fahrenheit = (celsius * 9/5) + 32',
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Factorial de un número',
                'enunciado' => "Crea una función `factorial(n)` que calcule el factorial de un número.\n\n**¿Qué es el factorial?**\n```\n5! = 5 × 4 × 3 × 2 × 1 = 120\n3! = 3 × 2 × 1 = 6\n1! = 1\n0! = 1 (por definición)\n```\n\n**Pista:** Usa un bucle `for` dentro de la función.\n\n**Prueba:**\n```python\nprint(factorial(5))  # 120\nprint(factorial(3))  # 6\nprint(factorial(0))  # 1\n```",
                'solucion' => "def factorial(n):\n    if n == 0:\n        return 1\n    resultado = 1\n    for i in range(1, n + 1):\n        resultado = resultado * i\n    return resultado\n\nprint(factorial(5))\nprint(factorial(3))\nprint(factorial(0))\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Empieza con resultado = 1 y multiplica por cada número desde 1 hasta n.',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Función con parámetro por defecto',
                'enunciado' => "Crea una función `saludar(nombre, saludo=\"¡Hola\")` que salude al usuario.\n\nSi no se pasa el saludo, usa `\"¡Hola\"` por defecto.\n\n**Prueba:**\n```python\nsaludar(\"Santiago\")              # ¡Hola, Santiago!\nsaludar(\"Papá\", \"¡Buenas tardes\")  # ¡Buenas tardes, Papá!\nsaludar(\"Nacional\", \"¡Arriba\")  # ¡Arriba, Nacional!\n```",
                'solucion' => "def saludar(nombre, saludo=\"¡Hola\"):\n    print(f\"{saludo}, {nombre}!\")\n\nsaludar(\"Santiago\")\nsaludar(\"Papá\", \"¡Buenas tardes\")\nsaludar(\"Nacional\", \"¡Arriba\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'El parámetro con valor por defecto va al final: def saludar(nombre, saludo="¡Hola")',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Mini calculadora con funciones',
                'enunciado' => "Crea una **mini calculadora** con 4 funciones: `sumar`, `restar`, `multiplicar`, `dividir`.\n\nCada función recibe 2 números y retorna el resultado. Para `dividir`, verifica que el divisor no sea 0.\n\n**Prueba:**\n```python\nprint(sumar(10, 5))       # 15\nprint(restar(10, 5))      # 5\nprint(multiplicar(4, 3))  # 12\nprint(dividir(10, 2))     # 5.0\nprint(dividir(10, 0))     # Error: no se puede dividir entre 0\n```",
                'solucion' => "def sumar(a, b):\n    return a + b\n\ndef restar(a, b):\n    return a - b\n\ndef multiplicar(a, b):\n    return a * b\n\ndef dividir(a, b):\n    if b == 0:\n        return \"Error: no se puede dividir entre 0\"\n    return a / b\n\nprint(sumar(10, 5))\nprint(restar(10, 5))\nprint(multiplicar(4, 3))\nprint(dividir(10, 2))\nprint(dividir(10, 0))\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Para dividir, usa if b == 0: return "Error..." antes de hacer la división.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Librería de funciones útiles',
                'enunciado' => "## Proyecto del Módulo 4 — Librería personal\n\nCrea un archivo con al menos **6 funciones propias** que sean genuinamente útiles para ti.\n\n### Ideas (elige las que quieras o inventa las tuyas):\n- `promedio(nota1, nota2, nota3)` → promedio de 3 notas\n- `bmi(peso_kg, altura_m)` → índice de masa corporal\n- `es_bisiesto(año)` → True si el año es bisiesto\n- `porcentaje(parte, total)` → calcula el % que representa parte de total\n- `mayor_de_tres(a, b, c)` → retorna el mayor de los 3\n- `contar_vocales(texto)` → cuántas vocales tiene un texto\n\n### Requisitos:\n- Mínimo 6 funciones\n- Cada función tiene comentario de una línea explicando qué hace\n- Prueba cada función con al menos 2 llamadas\n- Al menos 3 funciones usan `return`",
                'solucion' => "def promedio(n1, n2, n3):\n    return (n1 + n2 + n3) / 3\n\ndef es_par(n):\n    return n % 2 == 0\n\ndef celsius_a_fahrenheit(c):\n    return (c * 9/5) + 32\n\ndef mayor_de_tres(a, b, c):\n    if a >= b and a >= c:\n        return a\n    elif b >= c:\n        return b\n    return c\n\ndef es_bisiesto(año):\n    return (año % 4 == 0 and año % 100 != 0) or (año % 400 == 0)\n\ndef porcentaje(parte, total):\n    return (parte / total) * 100\n\nprint(promedio(4.5, 3.8, 4.2))\nprint(es_par(7))\nprint(celsius_a_fahrenheit(37))\nprint(mayor_de_tres(5, 9, 3))\nprint(es_bisiesto(2024))\nprint(porcentaje(25, 200))\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Empieza con las funciones más simples (es_par, promedio). Para cada una: def nombre(params): → cuerpo → return.',
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
