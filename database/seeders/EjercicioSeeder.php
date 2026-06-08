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

        $moduloGit = Modulo::where('slug', 'git-github')->first();
        if (! $moduloGit) {
            return;
        }

        $ejerciciosGit = [
            // --- OBLIGATORIOS ---
            [
                'modulo_id'           => $moduloGit->id,
                'orden'               => 1,
                'tipo'                => 'quiz_opcion',
                'titulo'              => '¿Qué hace git init?',
                'enunciado'           => "Elige la respuesta correcta:\n\n¿Qué hace el comando `git init` en una carpeta?",
                'solucion'            => null,
                'respuesta_correcta'  => null,
                'es_obligatorio'      => true,
                'recompensa_ejercicio'=> 2500,
                'recompensa_perfecto' => 4000,
                'pista'               => 'Piensa en "inicializar" — significa empezar algo desde cero.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Descarga Git en tu computador', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Crea un repositorio Git en esa carpeta', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) Sube el código a GitHub', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) Borra todo el historial de commits', 'es_correcta' => false],
                ],
            ],
            [
                'modulo_id'           => $moduloGit->id,
                'orden'               => 2,
                'tipo'                => 'quiz_texto',
                'titulo'              => 'El comando de la "foto"',
                'enunciado'           => "Escribe el comando Git que se usa para **guardar los cambios** (tomar la \"foto\") con el mensaje `\"primer commit\"`.\n\nEscribe el comando completo, incluyendo el mensaje.",
                'solucion'            => null,
                'respuesta_correcta'  => 'git commit -m "primer commit"',
                'es_obligatorio'      => true,
                'recompensa_ejercicio'=> 2500,
                'recompensa_perfecto' => 4000,
                'pista'               => 'El comando empieza con "git commit" y tiene una opción "-m" para el mensaje.',
            ],
            [
                'modulo_id'           => $moduloGit->id,
                'orden'               => 3,
                'tipo'                => 'terminal_git',
                'titulo'              => 'Tu primer repositorio real',
                'enunciado'           => "¡Es hora de hacerlo de verdad! 💪\n\n## Pasos a seguir en tu terminal:\n\n1. Crea una carpeta llamada `mi-repo-git`\n2. Entra a esa carpeta con `cd mi-repo-git`\n3. Inicializa Git con `git init`\n4. Crea un archivo `hola.py` con `print(\"Hola Git!\")` adentro\n5. Agrega el archivo: `git add hola.py`\n6. Haz tu primer commit: `git commit -m \"primer commit: agrego hola.py\"`\n7. Ejecuta `git log --oneline` para ver el historial\n\n**Pega aquí todo el output de tu terminal desde el paso 3 hasta el paso 7.**",
                'solucion'            => "Criterios de evaluación:\n1. Debe aparecer output de 'git init' indicando repositorio inicializado\n2. Debe aparecer output de 'git add' o silencio (git add no imprime nada cuando funciona)\n3. Debe aparecer output de 'git commit' con el mensaje (cualquier mensaje descriptivo es aceptable, no solo 'primer commit')\n4. El git log --oneline debe mostrar al menos 1 commit\n5. El mensaje del commit debe ser descriptivo (no solo 'a', 'aaa', 'test' o caracteres aleatorios)\nes_perfecto si: el mensaje del commit es descriptivo Y el flujo está completo Y no hay errores",
                'respuesta_correcta'  => null,
                'es_obligatorio'      => true,
                'recompensa_ejercicio'=> 5000,
                'recompensa_perfecto' => 8000,
                'pista'               => 'Asegúrate de estar dentro de la carpeta antes de hacer git init. Usa cd para entrar.',
            ],
            [
                'modulo_id'           => $moduloGit->id,
                'orden'               => 4,
                'tipo'                => 'quiz_opcion',
                'titulo'              => '¿Qué es GitHub?',
                'enunciado'           => "Elige la mejor descripción de GitHub:",
                'solucion'            => null,
                'respuesta_correcta'  => null,
                'es_obligatorio'      => true,
                'recompensa_ejercicio'=> 2500,
                'recompensa_perfecto' => 4000,
                'pista'               => 'GitHub es el lugar donde vive tu código en internet.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Un editor de código como VS Code', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Una red social solo para diseñadores', 'es_correcta' => false],
                    ['orden' => 3, 'texto' => 'c) Un servidor en internet donde guardas repositorios Git', 'es_correcta' => true],
                    ['orden' => 4, 'texto' => 'd) El nombre del creador de Git', 'es_correcta' => false],
                ],
            ],
            [
                'modulo_id'           => $moduloGit->id,
                'orden'               => 5,
                'tipo'                => 'terminal_git',
                'titulo'              => 'El historial de tu código',
                'enunciado'           => "Vamos a ver cómo se ve el historial después de varios commits. 📜\n\n## Pasos:\n\n1. En tu carpeta `mi-repo-git` (la del ejercicio anterior)\n2. Modifica `hola.py` — agrega una segunda línea: `print(\"¡Git es increíble!\")`\n3. Haz un segundo commit: `git add hola.py` y luego `git commit -m \"agrego segunda impresión\"`\n4. Ejecuta `git log --oneline`\n\n**Pega el output de git log --oneline. Deben aparecer 2 commits.**",
                'solucion'            => "Criterios:\n1. El output debe mostrar 2 líneas (2 commits)\n2. Cada línea tiene un hash corto (7 caracteres) seguido del mensaje del commit\n3. Los mensajes deben ser diferentes entre sí\n4. El commit más reciente debe aparecer arriba\nes_perfecto si: hay exactamente 2 commits con mensajes descriptivos diferentes",
                'respuesta_correcta'  => null,
                'es_obligatorio'      => false,
                'recompensa_ejercicio'=> 4000,
                'recompensa_perfecto' => 6000,
                'pista'               => 'git log --oneline muestra una línea por commit, el más reciente arriba.',
            ],
            [
                'modulo_id'           => $moduloGit->id,
                'orden'               => 6,
                'tipo'                => 'terminal_git',
                'titulo'              => '¡Sube tu código a GitHub!',
                'enunciado'           => "¡El momento más emocionante! 🚀 Vas a subir tu código a GitHub por primera vez.\n\n## Pasos:\n\n1. Entra a **github.com** y crea una cuenta si no tienes\n2. Crea un repositorio nuevo llamado `mi-repo-git` (sin README)\n3. En tu terminal, conecta tu repo local con GitHub:\n```bash\ngit remote add origin https://github.com/TU_USUARIO/mi-repo-git.git\ngit push -u origin main\n```\n4. Ejecuta `git remote -v` para verificar la conexión\n\n**Pega el output de `git remote -v` y `git push` (o los mensajes que aparecieron).**",
                'solucion'            => "Criterios:\n1. El output de git remote -v debe mostrar la URL de GitHub (origin fetch y origin push)\n2. La URL debe contener 'github.com'\n3. Debe haber evidencia del push (puede ser el output de git push o el mensaje de éxito)\n4. No se requiere que el usuario ya tenga cuenta — si describe que creó la cuenta y configuró el remote, eso es válido\nes_perfecto si: el remote apunta a GitHub, el push fue exitoso y la URL tiene el nombre del usuario",
                'respuesta_correcta'  => null,
                'es_obligatorio'      => false,
                'recompensa_ejercicio'=> 5000,
                'recompensa_perfecto' => 8000,
                'pista'               => 'Reemplaza TU_USUARIO con tu nombre de usuario de GitHub. Si no tienes cuenta aún, créala primero en github.com.',
            ],
            [
                'modulo_id'           => $moduloGit->id,
                'orden'               => 7,
                'tipo'                => 'mini_proyecto',
                'titulo'              => 'Tu portafolio de Python en GitHub 🌐',
                'enunciado'           => "## Proyecto final del módulo\n\nCrea un repositorio en GitHub llamado `pythonjr-ejercicios` con tus mejores programas Python.\n\n## Requisitos:\n\n1. Crea el repo local, agrega al menos 2 archivos `.py` de tus módulos anteriores\n2. Agrega un `README.md` con:\n   - Tu nombre (o apodo)\n   - Una descripción: \"Mis ejercicios de Python de PythonJr\"\n   - Lista de los programas incluidos\n3. Sube todo a GitHub con commits descriptivos\n4. Copia aquí la URL de tu repositorio (ej: `github.com/tu_usuario/pythonjr-ejercicios`)\n\n**Pega la URL de tu repositorio en GitHub.**",
                'solucion'            => null,
                'respuesta_correcta'  => null,
                'es_obligatorio'      => false,
                'recompensa_ejercicio'=> 8000,
                'recompensa_perfecto' => 12000,
                'pista'               => 'Puedes copiar los archivos .py de tus ejercicios anteriores a la nueva carpeta.',
            ],
        ];

        foreach ($ejerciciosGit as $data) {
            $opciones = $data['opciones'] ?? [];
            unset($data['opciones']);

            $ejercicio = Ejercicio::create($data);

            foreach ($opciones as $opcion) {
                EjercicioOpcion::create(array_merge($opcion, ['ejercicio_id' => $ejercicio->id]));
            }
        }
    }
}
