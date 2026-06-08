<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo5Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'colecciones-de-cosas')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => 'Listas — guardar muchas cosas en una variable',
                'contenido' => <<<'MD'
# Listas — guardar muchas cosas en una variable 📋

Hasta ahora cada variable guarda una sola cosa. Pero, ¿y si quieres guardar los goles de 10 partidos? ¿O los nombres de 5 jugadores?

Para eso existen las **listas**: variables que guardan **múltiples valores** en orden.

## Crear una lista

```python
# Lista de jugadores
jugadores = ["Ospina", "Muriel", "James", "Falcao", "Cuadrado"]

# Lista de goles
goles_partidos = [3, 0, 2, 1, 4, 2]

# Lista mixta (puede tener diferentes tipos)
info = ["Santiago", 11, True, 4.5]
```

## Lista vacía

```python
lista_tareas = []  # empieza vacía, se llenará después
```

## Ver el contenido

```python
frutas = ["mango", "guanábana", "lulo", "maracuyá"]
print(frutas)
# ['mango', 'guanábana', 'lulo', 'maracuyá']

print(len(frutas))  # 4 — cuántos elementos tiene
```

## ¿Por qué listas?

Sin listas tendrías que hacer:
```python
jugador1 = "Ospina"
jugador2 = "Muriel"
jugador3 = "James"
# ...

# Con lista:
jugadores = ["Ospina", "Muriel", "James"]
```
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'teoria',
                'titulo' => 'Accediendo a elementos: índices',
                'contenido' => <<<'MD'
# Accediendo a elementos: índices 🔍

Cada elemento de una lista tiene una **posición numérica** llamada **índice**. El primer elemento tiene índice **0** (no 1).

## Acceder por índice

```python
equipos = ["Nacional", "América", "Junior", "Santa Fe", "Millos"]
#índices:       0          1         2          3           4

print(equipos[0])  # Nacional ← el primero
print(equipos[2])  # Junior
print(equipos[4])  # Millos ← el último
```

## Índices negativos — desde el final

```python
print(equipos[-1])  # Millos ← el último
print(equipos[-2])  # Santa Fe ← el penúltimo
```

## ⚠️ IndexError — el error más común con listas

```python
equipos = ["Nacional", "América", "Junior"]
print(equipos[3])  # ❌ ERROR: solo hay índices 0, 1, 2
```

## Slicing — obtener un pedazo de la lista

```python
numeros = [10, 20, 30, 40, 50, 60]
print(numeros[1:4])   # [20, 30, 40] ← desde índice 1 hasta 3
print(numeros[:3])    # [10, 20, 30] ← desde el inicio hasta 2
print(numeros[3:])    # [40, 50, 60] ← desde índice 3 hasta el final
```

## Modificar un elemento

```python
frutas = ["mango", "guanábana", "lulo"]
frutas[1] = "maracuyá"  # reemplaza guanábana por maracuyá
print(frutas)  # ['mango', 'maracuyá', 'lulo']
```
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Modificando listas: append, remove, pop, sort',
                'contenido' => <<<'MD'
# Modificando listas: append, remove, pop, sort 🔧

Las listas son **mutables** — puedes cambiarlas después de crearlas. Estos son los métodos más usados:

## `append()` — agregar al final

```python
favoritos = ["fútbol", "Minecraft"]
favoritos.append("Python")
favoritos.append("música")
print(favoritos)
# ['fútbol', 'Minecraft', 'Python', 'música']
```

## `remove()` — eliminar por valor

```python
frutas = ["mango", "guanábana", "lulo", "mango"]
frutas.remove("guanábana")  # elimina la primera aparición
print(frutas)  # ['mango', 'lulo', 'mango']
```

## `pop()` — eliminar por posición (o el último)

```python
numeros = [10, 20, 30, 40, 50]
ultimo = numeros.pop()    # elimina y retorna el último
print(ultimo)  # 50
print(numeros) # [10, 20, 30, 40]

tercero = numeros.pop(1)   # elimina el índice 1
print(tercero) # 20
```

## `sort()` — ordenar

```python
notas = [3.5, 4.8, 2.9, 5.0, 4.2]
notas.sort()             # ordena de menor a mayor
print(notas)  # [2.9, 3.5, 4.2, 4.8, 5.0]

notas.sort(reverse=True) # ordena de mayor a menor
print(notas)  # [5.0, 4.8, 4.2, 3.5, 2.9]
```

## `len()` — cantidad de elementos

```python
equipos = ["Nacional", "América", "Junior"]
print(len(equipos))  # 3
```

## Resumen rápido

```python
lista = []
lista.append("a")    # agrega al final
lista.remove("a")    # elimina por valor
lista.pop()          # elimina el último
lista.sort()         # ordena
len(lista)           # cuenta elementos
```
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'Iterando sobre listas con for',
                'contenido' => <<<'MD'
# Iterando sobre listas con `for` 🔄

El bucle `for` es perfecto para recorrer todos los elementos de una lista, uno por uno.

## Recorrer una lista

```python
goleadores = ["Falcao", "James", "Cuadrado", "Muriel"]

for jugador in goleadores:
    print(f"⚽ {jugador}")
```

Resultado:
```
⚽ Falcao
⚽ James
⚽ Cuadrado
⚽ Muriel
```

## Con el índice — `enumerate()`

Si necesitas el índice y el valor al mismo tiempo:

```python
frutas = ["mango", "lulo", "guanábana"]

for i, fruta in enumerate(frutas):
    print(f"{i+1}. {fruta}")
```

Resultado:
```
1. mango
2. lulo
3. guanábana
```

## Operaciones con todos los elementos

```python
notas = [4.5, 3.8, 4.2, 3.5, 5.0]

# Sumar todas las notas
total = 0
for nota in notas:
    total += nota  # igual a: total = total + nota

promedio = total / len(notas)
print(f"Promedio: {promedio:.2f}")
```

## List comprehension — la forma pro 😎

```python
# Crear una lista con los cuadrados del 1 al 5
cuadrados = [x * x for x in range(1, 6)]
print(cuadrados)  # [1, 4, 9, 16, 25]

# Filtrar solo los pares
numeros = [1, 2, 3, 4, 5, 6, 7, 8]
pares = [n for n in numeros if n % 2 == 0]
print(pares)  # [2, 4, 6, 8]
```
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'teoria',
                'titulo' => 'Diccionarios — pares clave:valor',
                'contenido' => <<<'MD'
# Diccionarios — pares clave:valor 📖

Un diccionario es como una agenda: en vez de buscar por número (índice), buscas por **nombre** (clave).

## Crear un diccionario

```python
jugador = {
    "nombre": "James Rodríguez",
    "edad": 32,
    "equipo": "Rayo Vallecano",
    "posicion": "mediapunta",
    "goles": 8
}
```

## Acceder por clave

```python
print(jugador["nombre"])    # James Rodríguez
print(jugador["goles"])     # 8
```

## Agregar y modificar

```python
jugador["nacionalidad"] = "colombiana"   # agregar nueva clave
jugador["goles"] = 10                    # modificar existente
print(jugador["goles"])  # 10
```

## Eliminar

```python
del jugador["posicion"]    # elimina la clave "posicion"
```

## Verificar si una clave existe

```python
if "edad" in jugador:
    print(f"Tiene {jugador['edad']} años")
```

## Recorrer un diccionario

```python
for clave, valor in jugador.items():
    print(f"{clave}: {valor}")
```

## Ejemplo práctico

```python
capitales = {
    "Colombia": "Bogotá",
    "Antioquia": "Medellín",
    "Venezuela": "Caracas",
    "Perú": "Lima",
    "Brasil": "Brasilia"
}

pais = input("¿De qué país quieres la capital? ")

if pais in capitales:
    print(f"La capital de {pais} es {capitales[pais]}")
else:
    print("No tengo información de ese país")
```
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'teoria',
                'titulo' => 'Tuplas — listas que no cambian',
                'contenido' => <<<'MD'
# Tuplas — listas que no cambian 🔒

Una **tupla** es como una lista, pero **inmutable**: no puedes modificarla después de crearla.

## Crear una tupla

```python
# Con paréntesis
coordenadas = (6.2442, -75.5812)  # lat/lon de Medellín

colores_semaforo = ("rojo", "amarillo", "verde")

# También se puede sin paréntesis
punto = 3, 4
```

## Acceder a elementos (igual que listas)

```python
colores = ("rojo", "amarillo", "verde")
print(colores[0])   # rojo
print(colores[-1])  # verde
```

## ¿Por qué usar tuplas en vez de listas?

```python
# Las tuplas son para datos que NO deben cambiar:
dias_semana = ("lunes", "martes", "miércoles", "jueves", "viernes", "sábado", "domingo")

colores_bandera_colombia = ("amarillo", "azul", "rojo")

# Si intentas cambiar una tupla: ❌ ERROR
colores_bandera_colombia[0] = "verde"  # TypeError
```

## Desempaquetar tuplas

```python
coordenadas = (6.2442, -75.5812)
latitud, longitud = coordenadas

print(f"Lat: {latitud}, Lon: {longitud}")
```

## Funciones que retornan múltiples valores

Las funciones en Python usan tuplas para retornar varios valores:

```python
def minmax(lista):
    return min(lista), max(lista)  # retorna una tupla

notas = [3.5, 4.8, 2.9, 5.0, 4.2]
minimo, maximo = minmax(notas)
print(f"Mínimo: {minimo}, Máximo: {maximo}")
```

## Resumen: lista vs tupla

| | Lista `[]` | Tupla `()` |
|--|-----------|-----------|
| ¿Se puede modificar? | ✅ Sí | ❌ No |
| Velocidad | Un poco más lenta | Un poco más rápida |
| ¿Cuándo usarla? | Datos que cambian | Datos fijos |
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
                'titulo' => '¿Qué índice tiene el primer elemento de una lista?',
                'enunciado' => "¿Cuál es el índice del **primer elemento** de una lista en Python?\n\n```python\nfrutas = [\"mango\", \"lulo\", \"guanábana\"]\n# ¿Con qué índice accedo a \"mango\"?\n```",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'En Python (y casi todos los lenguajes), las listas empiezan a contar desde 0.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) 1', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) -1', 'es_correcta' => false],
                    ['orden' => 3, 'texto' => 'c) 0', 'es_correcta' => true],
                    ['orden' => 4, 'texto' => 'd) Depende del tamaño de la lista', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué método agrega un elemento al final de una lista?',
                'enunciado' => "¿Qué **método** de las listas se usa para agregar un elemento al **final**?\n\nEscribe solo el nombre del método (sin paréntesis).",
                'respuesta_correcta' => 'append',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Empieza con "app". Es el método más usado de las listas.',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Operaciones con lista de frutas',
                'enunciado' => "Crea un programa con una lista de frutas que haga todo esto en orden:\n\n1. Empieza con: `[\"mango\", \"lulo\", \"guanábana\"]`\n2. Agrega `\"maracuyá\"` al final\n3. Imprime cuántas frutas hay con `len()`\n4. Imprime la segunda fruta (índice 1)\n5. Elimina la primera fruta (`remove` o `pop(0)`)\n6. Ordena la lista con `sort()`\n7. Imprime la lista final\n\n**Resultado esperado:**\n```\n4\nlulo\n['guanábana', 'lulo', 'maracuyá']\n```",
                'codigo_base' => "frutas = [\"mango\", \"lulo\", \"guanábana\"]\n\n# 1. Agrega maracuyá\n\n# 2. Imprime cuántas hay\n\n# 3. Imprime la segunda\n\n# 4. Elimina la primera\n\n# 5. Ordena\n\n# 6. Imprime la lista final\n",
                'solucion' => "frutas = [\"mango\", \"lulo\", \"guanábana\"]\n\nfrutas.append(\"maracuyá\")\nprint(len(frutas))\nprint(frutas[1])\nfrutas.pop(0)\nfrutas.sort()\nprint(frutas)\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Hazlo paso a paso. Para eliminar la primera: frutas.pop(0) o frutas.remove(frutas[0]).',
            ],
            [
                'orden' => 4, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Diccionario de información personal',
                'enunciado' => "Crea un **diccionario** con tu información personal que tenga las claves: `nombre`, `edad`, `ciudad`, `equipo_fav`, `hobby`.\n\nLuego recórrelo con un `for` e imprime cada clave y valor.\n\n**Ejemplo de salida:**\n```\nnombre: Santiago\nedad: 11\nciudad: Medellín\nequipo_fav: Nacional\nhobby: programación\n```",
                'solucion' => "perfil = {\n    \"nombre\": \"Santiago\",\n    \"edad\": 11,\n    \"ciudad\": \"Medellín\",\n    \"equipo_fav\": \"Nacional\",\n    \"hobby\": \"programación\"\n}\n\nfor clave, valor in perfil.items():\n    print(f\"{clave}: {valor}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa .items() para recorrer el diccionario y obtener clave y valor al mismo tiempo.',
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => '¿Está en la lista?',
                'enunciado' => "Crea un programa con una lista de equipos colombianos y que le pregunte al usuario un equipo para ver si está en la lista.\n\n**Lista de equipos:**\n```python\nequipos = [\"Nacional\", \"América\", \"Junior\", \"Millos\", \"Santa Fe\"]\n```\n\n**Ejemplo:**\n```\n¿Qué equipo buscas? Junior\n✅ Junior está en la lista\n\n¿Qué equipo buscas? Arsenal\n❌ Arsenal no está en nuestra lista\n```",
                'solucion' => "equipos = [\"Nacional\", \"América\", \"Junior\", \"Millos\", \"Santa Fe\"]\n\nbusqueda = input(\"¿Qué equipo buscas? \")\n\nif busqueda in equipos:\n    print(f\"✅ {busqueda} está en la lista\")\nelse:\n    print(f\"❌ {busqueda} no está en nuestra lista\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa el operador "in" para verificar si un valor está en la lista: if elemento in lista.',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Promedio de notas',
                'enunciado' => "Crea un programa que:\n1. Tenga una lista de **5 notas** (tú las defines)\n2. Calcule el **promedio**\n3. Diga si aprobó o no (promedio >= 3.0)\n4. Imprima la nota **más alta** y la **más baja**\n\n**Pista:** Usa las funciones `sum()`, `min()`, `max()` y `len()`.",
                'solucion' => "notas = [4.5, 3.8, 2.9, 5.0, 4.2]\n\npromedio = sum(notas) / len(notas)\nmas_alta = max(notas)\nmas_baja = min(notas)\n\nprint(f\"Promedio: {promedio:.2f}\")\nprint(f\"Nota más alta: {mas_alta}\")\nprint(f\"Nota más baja: {mas_baja}\")\n\nif promedio >= 3.0:\n    print(\"✅ ¡Aprobaste!\")\nelse:\n    print(\"❌ Necesitas mejorar.\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'sum(lista) suma todos los elementos. min(lista) y max(lista) dan el mínimo y máximo.',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Diccionario de países y capitales latinoamericanas',
                'enunciado' => "Crea un diccionario con al menos **5 países latinoamericanos y sus capitales**.\n\nEl programa debe:\n1. Preguntar el nombre de un país\n2. Si está en el diccionario, mostrar su capital\n3. Si no está, decirlo\n4. Repetir hasta que el usuario escriba `\"salir\"`",
                'solucion' => "capitales = {\n    \"Colombia\": \"Bogotá\",\n    \"Venezuela\": \"Caracas\",\n    \"Perú\": \"Lima\",\n    \"Brasil\": \"Brasilia\",\n    \"Argentina\": \"Buenos Aires\",\n    \"Chile\": \"Santiago\"\n}\n\nwhile True:\n    pais = input(\"País (o 'salir'): \")\n    if pais == \"salir\":\n        break\n    if pais in capitales:\n        print(f\"La capital de {pais} es {capitales[pais]}\")\n    else:\n        print(f\"No tengo info de {pais}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa un while True con break para el ciclo de preguntas.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Inventario de videojuegos',
                'enunciado' => "## Proyecto del Módulo 5 — Inventario de videojuegos\n\nCrea un programa que gestione tu colección de videojuegos.\n\n### El menú debe tener:\n```\n=== MI INVENTARIO DE JUEGOS ===\n1) Ver todos mis juegos\n2) Agregar un juego\n3) Buscar un juego\n4) Eliminar un juego\n5) Salir\n```\n\n### Requisitos:\n- Usa una **lista** para guardar los juegos\n- Empieza con al menos 3 juegos predefinidos\n- El menú usa `while True` con `break` para salir\n- Cada opción hace su función correctamente\n- Cuando se elimina un juego que no existe, da mensaje de error",
                'codigo_base' => "juegos = [\"Minecraft\", \"FIFA 24\", \"Roblox\"]\n\nwhile True:\n    print(\"\\n=== MI INVENTARIO ===\")\n    print(\"1) Ver juegos\")\n    print(\"2) Agregar\")\n    print(\"3) Buscar\")\n    print(\"4) Eliminar\")\n    print(\"5) Salir\")\n    \n    opcion = input(\"\\n¿Qué quieres hacer? \")\n    \n    # Implementa cada opción\n",
                'solucion' => "juegos = [\"Minecraft\", \"FIFA 24\", \"Roblox\"]\n\nwhile True:\n    print(\"\\n=== MI INVENTARIO ===\")\n    print(\"1) Ver juegos  2) Agregar  3) Buscar  4) Eliminar  5) Salir\")\n    opcion = input(\"Opción: \")\n\n    if opcion == \"1\":\n        if not juegos:\n            print(\"No tienes juegos aún.\")\n        for i, j in enumerate(juegos, 1):\n            print(f\"  {i}. {j}\")\n    elif opcion == \"2\":\n        nuevo = input(\"Nombre del juego: \")\n        juegos.append(nuevo)\n        print(f\"✅ {nuevo} agregado.\")\n    elif opcion == \"3\":\n        buscar = input(\"¿Qué juego buscas? \")\n        if buscar in juegos:\n            print(f\"✅ {buscar} está en tu colección.\")\n        else:\n            print(f\"❌ {buscar} no está.\")\n    elif opcion == \"4\":\n        borrar = input(\"¿Qué juego eliminar? \")\n        if borrar in juegos:\n            juegos.remove(borrar)\n            print(f\"🗑️ {borrar} eliminado.\")\n        else:\n            print(f\"❌ {borrar} no está en la lista.\")\n    elif opcion == \"5\":\n        print(\"¡Hasta luego! 👋\")\n        break\n    else:\n        print(\"Opción no válida\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa if/elif para cada opción del menú. Para eliminar, verifica primero si el juego está en la lista.',
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
