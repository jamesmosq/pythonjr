<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo6Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'guardando-informacion')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => '¿Por qué guardar información en archivos?',
                'contenido' => <<<'MD'
# ¿Por qué guardar información en archivos? 💾

Hasta ahora tus programas tienen un problema: cuando los cierras, **toda la información desaparece**.

- El inventario de videojuegos → se pierde al cerrar
- Los puntajes del juego → desaparecen
- La lista de tareas → olvidada para siempre

Para que un programa "recuerde" información entre ejecuciones, necesita **guardar en archivos**.

## ¿Qué tipos de archivos usamos?

| Tipo | Extensión | Para qué |
|------|-----------|----------|
| Texto plano | `.txt` | Notas, logs, listas simples |
| JSON | `.json` | Datos estructurados (como diccionarios) |
| CSV | `.csv` | Tablas de datos (como Excel) |

En este módulo aprenderás `.txt` y `.json`.

## ¿Dónde se guardan?

Los archivos se guardan en la **misma carpeta** donde está tu programa `.py`, a menos que especifiques otra ruta.

```
mi_proyecto/
├── calculadora.py
├── puntajes.txt      ← lo crea tu programa
└── configuracion.json ← lo crea tu programa
```

## El ciclo de vida de un archivo

```
1. Abrir el archivo (open)
2. Leer o escribir
3. Cerrar el archivo (close)
```

Python tiene una forma elegante de hacer esto automáticamente con `with`. ¡Lo verás pronto!
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Abrir y leer un archivo: open() y read()',
                'contenido' => <<<'MD'
# Abrir y leer un archivo: `open()` y `read()` 📂

## La función `open()`

```python
archivo = open("mi_archivo.txt", "r")
```

El segundo parámetro es el **modo**:
- `"r"` → read (leer) — el archivo ya debe existir
- `"w"` → write (escribir) — crea o sobreescribe
- `"a"` → append (agregar) — agrega al final
- `"r+"` → lectura y escritura

## Leer todo el contenido

```python
archivo = open("notas.txt", "r")
contenido = archivo.read()
print(contenido)
archivo.close()  # ⚠️ siempre cerrar
```

## Leer línea por línea

```python
archivo = open("notas.txt", "r")
for linea in archivo:
    print(linea.strip())  # .strip() elimina el \n del final
archivo.close()
```

## Leer todas las líneas en una lista

```python
archivo = open("notas.txt", "r")
lineas = archivo.readlines()
print(lineas)       # ['línea 1\n', 'línea 2\n', ...]
print(len(lineas))  # número de líneas
archivo.close()
```

## ⚠️ FileNotFoundError

Si el archivo no existe y usas `"r"`, Python lanza un error:

```python
archivo = open("archivo_inexistente.txt", "r")
# FileNotFoundError: [Errno 2] No such file or directory
```

Por eso aprenderás `try/except` pronto.
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'teoria',
                'titulo' => 'Escribir en archivos: write() y append()',
                'contenido' => <<<'MD'
# Escribir en archivos: `write()` y `append()` ✍️

## Modo `"w"` — escribir (crea o sobreescribe)

```python
archivo = open("saludo.txt", "w")
archivo.write("¡Hola, mundo!\n")
archivo.write("Esto es Python.\n")
archivo.close()
```

Crea el archivo (o lo borra y recrea si ya existe) con el contenido.

> ⚠️ **Cuidado con `"w"`** — si el archivo ya existe, **borra todo el contenido anterior**.

## Modo `"a"` — append (agregar sin borrar)

```python
# Primera ejecución
archivo = open("historial.txt", "a")
archivo.write("Partido 1: Nacional 3 - Millonarios 0\n")
archivo.close()

# Segunda ejecución
archivo = open("historial.txt", "a")
archivo.write("Partido 2: América 2 - Junior 1\n")
archivo.close()
```

Después de dos ejecuciones, el archivo tiene **ambas líneas**.

## Escribir múltiples líneas

```python
resultados = [
    "Nacional 3 - 0 Millonarios",
    "América 1 - 2 Junior",
    "Santa Fe 0 - 0 Tolima"
]

archivo = open("resultados.txt", "w")
for resultado in resultados:
    archivo.write(resultado + "\n")
archivo.close()
```

## `\n` — el salto de línea

`write()` no agrega salto de línea automáticamente. Debes agregar `\n` manualmente.

```python
archivo.write("línea 1\n")  # ← el \n hace el salto
archivo.write("línea 2\n")
```
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'El operador with — la forma correcta',
                'contenido' => <<<'MD'
# El operador `with` — la forma correcta y segura 🛡️

El problema de `open()` / `close()` es que si el programa falla en medio de la operación, el archivo queda "abierto" y puede dañarse.

**La solución:** usar `with`. Cierra el archivo automáticamente, pase lo que pase.

## Sintaxis con `with`

```python
# ✅ Forma correcta — siempre usa with
with open("notas.txt", "r") as archivo:
    contenido = archivo.read()
    print(contenido)
# Al salir del bloque, el archivo se cierra solo
```

## Escribir con `with`

```python
with open("puntajes.txt", "w") as f:
    f.write("Santiago: 1500\n")
    f.write("Nikolas: 2300\n")
```

## Agregar con `with`

```python
nuevo_puntaje = "María: 1800"

with open("puntajes.txt", "a") as f:
    f.write(nuevo_puntaje + "\n")
```

## Leer y escribir en el mismo bloque

```python
# Leer el archivo actual
with open("contador.txt", "r") as f:
    visitas = int(f.read().strip())

# Actualizar y guardar
visitas += 1
with open("contador.txt", "w") as f:
    f.write(str(visitas))

print(f"Esta es la visita número {visitas}")
```

## Regla de oro

> Siempre usa `with open(...)` en vez de `open()` + `close()`.
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'teoria',
                'titulo' => 'Archivos JSON — guardar datos estructurados',
                'contenido' => <<<'MD'
# Archivos JSON — guardar datos estructurados 🗂️

Los archivos `.txt` guardan texto plano. Pero ¿y si quieres guardar una lista o un diccionario? Para eso es **JSON**.

JSON (JavaScript Object Notation) es un formato que representa datos estructurados:

```json
{
  "nombre": "Santiago",
  "edad": 11,
  "notas": [4.5, 3.8, 5.0],
  "ciudad": "Medellín"
}
```

## Importar el módulo json

```python
import json
```

## Guardar (escribir) a JSON

```python
import json

perfil = {
    "nombre": "Santiago",
    "edad": 11,
    "equipo": "Nacional",
    "notas": [4.5, 3.8, 5.0]
}

with open("perfil.json", "w") as f:
    json.dump(perfil, f, indent=2)  # indent=2 hace el archivo legible
```

## Cargar (leer) desde JSON

```python
import json

with open("perfil.json", "r") as f:
    perfil = json.load(f)

print(perfil["nombre"])    # Santiago
print(perfil["notas"])     # [4.5, 3.8, 5.0]
```

## Guardar una lista

```python
import json

juegos = ["Minecraft", "FIFA 24", "Roblox", "Fortnite"]

with open("juegos.json", "w") as f:
    json.dump(juegos, f, indent=2)

# Cargar:
with open("juegos.json", "r") as f:
    mis_juegos = json.load(f)

print(mis_juegos)  # ['Minecraft', 'FIFA 24', 'Roblox', 'Fortnite']
```

## JSON vs TXT

| | `.txt` | `.json` |
|--|--------|---------|
| Guarda texto simple | ✅ | Solo texto |
| Guarda listas y dicts | ❌ | ✅ |
| Fácil de leer por humanos | ✅ | ✅ (con indent) |
| Python lo procesa directo | ❌ | ✅ (json.load) |
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'teoria',
                'titulo' => 'Manejo de errores: try / except',
                'contenido' => <<<'MD'
# Manejo de errores: `try / except` 🛡️

Los errores en Python se llaman **excepciones**. Cuando ocurren, el programa se detiene. Con `try/except` puedes atraparlos y manejarlos elegantemente.

## El problema

```python
with open("scores.txt", "r") as f:
    datos = f.read()
# Si el archivo no existe: ¡programa explota! 💥
```

## La solución con try/except

```python
try:
    with open("scores.txt", "r") as f:
        datos = f.read()
    print(datos)
except FileNotFoundError:
    print("El archivo no existe. Empezando desde cero.")
    datos = ""
```

## Múltiples tipos de error

```python
try:
    numero = int(input("Ingresa un número: "))
    resultado = 10 / numero
    print(f"10 / {numero} = {resultado}")
except ValueError:
    print("❌ Eso no es un número válido")
except ZeroDivisionError:
    print("❌ No se puede dividir entre 0")
```

## El bloque `finally` — siempre se ejecuta

```python
try:
    with open("datos.txt", "r") as f:
        contenido = f.read()
    print("✅ Archivo leído")
except FileNotFoundError:
    print("❌ Archivo no encontrado")
finally:
    print("Programa terminado.")  # esto siempre se imprime
```

## Errores más comunes

| Error | Cuándo ocurre |
|-------|--------------|
| `FileNotFoundError` | Archivo no encontrado |
| `ValueError` | Tipo de dato inválido |
| `ZeroDivisionError` | División entre 0 |
| `IndexError` | Índice fuera de rango |
| `KeyError` | Clave no existe en diccionario |

## Capturar cualquier error

```python
try:
    # código riesgoso
    pass
except Exception as e:
    print(f"Ocurrió un error: {e}")
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
                'titulo' => '¿Qué modo abre un archivo para agregar sin borrar?',
                'enunciado' => "¿Qué modo de `open()` abre un archivo para **agregar texto al final** sin borrar el contenido anterior?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'La letra viene de "append" (agregar). Es una sola letra.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) "r" (read)', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) "w" (write)', 'es_correcta' => false],
                    ['orden' => 3, 'texto' => 'c) "a" (append)', 'es_correcta' => true],
                    ['orden' => 4, 'texto' => 'd) "x" (exclusive)', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué librería se usa para leer archivos JSON?',
                'enunciado' => "Para leer y escribir archivos `.json` en Python usas una librería de la biblioteca estándar.\n\n¿Cómo se llama esa librería?",
                'respuesta_correcta' => 'json',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Su nombre es el mismo formato del archivo: .json',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Sistema de puntajes en archivo',
                'enunciado' => "Crea un programa que:\n\n1. Pida al usuario su **nombre** y **puntuación**\n2. **Guarde** el registro en `puntajes.txt` (modo append)\n3. **Lea y muestre** todos los puntajes guardados\n\n**Formato del archivo:**\n```\nSantiago: 1500\nNikolas: 2300\nSantiago: 1800\n```\n\n**Usa `with` en ambas operaciones (escribir y leer).**",
                'codigo_base' => "nombre = input(\"Tu nombre: \")\npuntaje = input(\"Tu puntaje: \")\n\n# 1. Guarda en puntajes.txt (modo append)\n\n\n# 2. Lee y muestra todos los puntajes\nprint(\"\\n=== TODOS LOS PUNTAJES ===\")\n",
                'solucion' => "nombre = input(\"Tu nombre: \")\npuntaje = input(\"Tu puntaje: \")\n\nwith open(\"puntajes.txt\", \"a\") as f:\n    f.write(f\"{nombre}: {puntaje}\\n\")\n\nprint(\"\\n=== TODOS LOS PUNTAJES ===\")\nwith open(\"puntajes.txt\", \"r\") as f:\n    for linea in f:\n        print(linea.strip())\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Para agregar sin borrar usa modo "a". Para leer todos, usa modo "r" y recorre el archivo con for.',
            ],
            [
                'orden' => 4, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Contar líneas de un archivo',
                'enunciado' => "Crea un programa que:\n1. Cree un archivo `poema.txt` con al menos **5 líneas** de texto\n2. Luego lo abra y cuente cuántas líneas tiene\n3. Imprima el total\n\n**Ejemplo:**\n```\nEl archivo 'poema.txt' tiene 5 líneas.\n```",
                'solucion' => "# Crear el archivo con contenido\nwith open(\"poema.txt\", \"w\") as f:\n    f.write(\"Verde que te quiero verde.\\n\")\n    f.write(\"Verde viento. Verdes ramas.\\n\")\n    f.write(\"Medellín, ciudad de flores.\\n\")\n    f.write(\"Nacional siempre en el corazón.\\n\")\n    f.write(\"Python es el futuro.\\n\")\n\n# Contar líneas\nwith open(\"poema.txt\", \"r\") as f:\n    lineas = f.readlines()\n\nprint(f\"El archivo 'poema.txt' tiene {len(lineas)} líneas.\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa .readlines() para obtener una lista de líneas, luego len() para contar.',
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Lista de tareas en JSON',
                'enunciado' => "Crea un programa de **lista de tareas** que guarda los datos en JSON.\n\nCuando el programa inicia:\n- Si `tareas.json` existe → carga las tareas\n- Si no existe → empieza con lista vacía\n\nPermite:\n1. Ver tareas\n2. Agregar tarea\n3. Salir (guardando en JSON)\n\nUsa `try/except FileNotFoundError` para manejar el caso de primer uso.",
                'solucion' => "import json\n\n# Cargar tareas existentes\ntry:\n    with open(\"tareas.json\", \"r\") as f:\n        tareas = json.load(f)\nexcept FileNotFoundError:\n    tareas = []\n\nwhile True:\n    print(\"\\n1) Ver  2) Agregar  3) Salir\")\n    op = input(\"Opción: \")\n    if op == \"1\":\n        if not tareas:\n            print(\"No hay tareas.\")\n        for i, t in enumerate(tareas, 1):\n            print(f\"  {i}. {t}\")\n    elif op == \"2\":\n        nueva = input(\"Nueva tarea: \")\n        tareas.append(nueva)\n        print(\"✅ Agregada\")\n    elif op == \"3\":\n        with open(\"tareas.json\", \"w\") as f:\n            json.dump(tareas, f, indent=2)\n        print(\"💾 Guardado. ¡Hasta luego!\")\n        break\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Carga las tareas al inicio con try/except. Guarda al salir con json.dump.',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'try/except — manejar archivo inexistente',
                'enunciado' => "Crea un programa que intente leer el archivo `configuracion.txt`.\n\n- Si existe → muestra el contenido\n- Si no existe → crea el archivo con el texto `\"configuracion_inicial=true\"` y avisa al usuario\n\nUsa `try/except FileNotFoundError`.",
                'solucion' => "try:\n    with open(\"configuracion.txt\", \"r\") as f:\n        contenido = f.read()\n    print(\"Configuración encontrada:\")\n    print(contenido)\nexcept FileNotFoundError:\n    print(\"Archivo no encontrado. Creando configuración inicial...\")\n    with open(\"configuracion.txt\", \"w\") as f:\n        f.write(\"configuracion_inicial=true\\n\")\n    print(\"✅ Archivo creado.\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'En el bloque except FileNotFoundError, crea el archivo con modo "w".',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Buscar una palabra en un archivo',
                'enunciado' => "Crea un programa que:\n1. Cree un archivo de texto con al menos 5 líneas\n2. Pregunte al usuario qué palabra buscar\n3. Imprima **qué líneas contienen esa palabra** (con número de línea)\n\n**Ejemplo:**\n```\nBuscar: Python\nLínea 2: Python es genial\nLínea 5: Aprender Python es divertido\nTotal: 2 coincidencias\n```",
                'solucion' => "with open(\"texto.txt\", \"w\") as f:\n    f.write(\"Medellín es una ciudad increíble\\n\")\n    f.write(\"Python es el mejor lenguaje para aprender\\n\")\n    f.write(\"El Nacional es el mejor equipo\\n\")\n    f.write(\"James Rodríguez es colombiano\\n\")\n    f.write(\"Aprender Python abre muchas puertas\\n\")\n\nbuscar = input(\"Buscar: \")\ncontador = 0\n\nwith open(\"texto.txt\", \"r\") as f:\n    for i, linea in enumerate(f, 1):\n        if buscar.lower() in linea.lower():\n            print(f\"Línea {i}: {linea.strip()}\")\n            contador += 1\n\nprint(f\"Total: {contador} coincidencias\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa .lower() en ambas cadenas para que la búsqueda no sea sensible a mayúsculas.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Sistema de puntajes con ranking',
                'enunciado' => "## Proyecto del Módulo 6 — Sistema de puntajes\n\nCrea un sistema completo que guarda puntajes en JSON y muestra un ranking.\n\n### Funcionalidades:\n\n1. **Registrar puntaje**: pide nombre, puntaje y fecha (puedes usar una fecha fija o `datetime`)\n2. **Ver ranking Top 5**: muestra los 5 mejores puntajes ordenados de mayor a menor\n3. **Ver historial completo**: todos los registros cronológicamente\n4. **Datos guardados en `scores.json`**\n\n### Formato del JSON:\n```json\n[\n  {\"nombre\": \"Santiago\", \"puntaje\": 1500, \"fecha\": \"2024-01-15\"},\n  {\"nombre\": \"Nikolas\", \"puntaje\": 2300, \"fecha\": \"2024-01-16\"}\n]\n```\n\n### Menú:\n```\n1) Registrar puntaje\n2) Ver Top 5\n3) Ver historial\n4) Salir\n```",
                'solucion' => "import json\n\ndef cargar_scores():\n    try:\n        with open(\"scores.json\", \"r\") as f:\n            return json.load(f)\n    except FileNotFoundError:\n        return []\n\ndef guardar_scores(scores):\n    with open(\"scores.json\", \"w\") as f:\n        json.dump(scores, f, indent=2)\n\nscores = cargar_scores()\n\nwhile True:\n    print(\"\\n=== SISTEMA DE PUNTAJES ===\")\n    print(\"1) Registrar  2) Top 5  3) Historial  4) Salir\")\n    op = input(\"Opción: \")\n\n    if op == \"1\":\n        nombre = input(\"Nombre: \")\n        puntaje = int(input(\"Puntaje: \"))\n        scores.append({\"nombre\": nombre, \"puntaje\": puntaje, \"fecha\": \"2024\"})\n        guardar_scores(scores)\n        print(\"✅ Registrado\")\n    elif op == \"2\":\n        top = sorted(scores, key=lambda x: x[\"puntaje\"], reverse=True)[:5]\n        print(\"\\n🏆 TOP 5:\")\n        for i, s in enumerate(top, 1):\n            print(f\"  {i}. {s['nombre']}: {s['puntaje']}\")\n    elif op == \"3\":\n        print(\"\\n📋 HISTORIAL:\")\n        for s in scores:\n            print(f\"  {s['nombre']}: {s['puntaje']}\")\n    elif op == \"4\":\n        guardar_scores(scores)\n        break\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Para ordenar por puntaje usa: sorted(scores, key=lambda x: x["puntaje"], reverse=True)',
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
