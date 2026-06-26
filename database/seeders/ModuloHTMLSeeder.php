<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloHTMLSeeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'html-construyendo-la-web')->firstOrFail();

        // ══════════════════════════════════════════════════════
        // LECCIONES
        // ══════════════════════════════════════════════════════

        $lecciones = [
            [
                'orden' => 1,
                'tipo'  => 'teoria',
                'titulo' => '¿Qué es HTML? El esqueleto de la web',
                'contenido' => <<<'MD'
# ¿Qué es HTML? El esqueleto de la web 🌐

Imagínate que vas a construir una casa. Lo primero que hacen los ingenieros es levantar la **estructura**: columnas, vigas, paredes. Sin esa estructura, no hay casa.

HTML es exactamente eso para las páginas web: **la estructura**. Sin HTML no hay web.

## ¿Qué significa HTML?

**H**yper**T**ext **M**arkup **L**anguage

- **HyperText** = texto que tiene links (puedes ir de página en página con un clic)
- **Markup** = marcas o etiquetas que le dicen al navegador qué es cada cosa
- **Language** = es un lenguaje (aunque no "programa" como Python)

## La idea clave: etiquetas

HTML funciona con **etiquetas** (en inglés: *tags*). Casi siempre vienen en pares:

```html
<h1>Este es un título</h1>
<p>Este es un párrafo de texto normal.</p>
```

- `<h1>` → etiqueta de **apertura**
- `</h1>` → etiqueta de **cierre** (nota la barra `/`)
- Lo que está en el medio → el **contenido**

> 🧠 Piénsalo como un sándwich: la etiqueta de apertura es el pan de arriba, el contenido es el relleno, y la etiqueta de cierre es el pan de abajo.

## La estructura básica de toda página HTML

```html
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Mi primera página</title>
  </head>
  <body>
    <h1>¡Hola mundo!</h1>
    <p>Soy nuevo en HTML y ya estoy construyendo webs 🔥</p>
  </body>
</html>
```

| Parte | ¿Qué hace? |
|-------|------------|
| `<!DOCTYPE html>` | Le avisa al navegador que esto es HTML5 |
| `<html>` | El contenedor de TODO |
| `<head>` | Info de la página (no se ve en pantalla) |
| `<title>` | El nombre en la pestaña del navegador |
| `<body>` | **Todo lo visible** va aquí |

> 💡 Los comentarios en HTML se escriben así: `<!-- esto no lo ve el usuario -->`
MD,
            ],
            [
                'orden' => 2,
                'tipo'  => 'teoria',
                'titulo' => 'Títulos, párrafos, listas y links',
                'contenido' => <<<'MD'
# Títulos, párrafos, listas y links ✍️

Estas son las etiquetas que más usarás. Con ellas puedes crear el 80% del contenido de cualquier página web.

## Títulos: de h1 a h6

HTML tiene 6 niveles de títulos, del más importante al menos importante:

```html
<h1>Título principal (solo uno por página)</h1>
<h2>Subtítulo</h2>
<h3>Sub-subtítulo</h3>
<h4>Nivel 4</h4>
<h5>Nivel 5</h5>
<h6>Nivel 6 (el más pequeño)</h6>
```

> 🎯 Regla: en una página solo debe haber **un `<h1>`**. Es como el nombre del capítulo en un libro.

## Párrafos y texto

```html
<p>Esto es un párrafo. El navegador añade espacio arriba y abajo automáticamente.</p>

<p>Puedes hacer texto <strong>en negrilla</strong> o texto <em>en cursiva</em>.</p>

<br> <!-- salto de línea dentro de un párrafo -->
```

## Listas

**Lista desordenada** (puntos, sin orden específico):
```html
<ul>
  <li>Fútbol</li>
  <li>Programar</li>
  <li>Videojuegos</li>
</ul>
```

**Lista ordenada** (numerada, cuando el orden importa):
```html
<ol>
  <li>Primero calentar</li>
  <li>Segundo practicar</li>
  <li>Tercero descansar</li>
</ol>
```

## Links (hipervínculos)

La etiqueta `<a>` crea links. El atributo `href` es la dirección a donde va el link:

```html
<a href="https://www.google.com">Ir a Google</a>

<!-- Para que abra en pestaña nueva: -->
<a href="https://www.google.com" target="_blank">Google en pestaña nueva</a>
```

> 🔗 `href` viene de **H**ypertext **REF**erence = referencia de hipertexto.
MD,
            ],
            [
                'orden' => 3,
                'tipo'  => 'teoria',
                'titulo' => 'Imágenes, formularios y etiquetas semánticas',
                'contenido' => <<<'MD'
# Imágenes, formularios y etiquetas semánticas 🖼️

## Imágenes

La etiqueta `<img>` es especial: **no tiene etiqueta de cierre**. Se cierra sola:

```html
<img src="mi-foto.jpg" alt="Mi foto de perfil">
```

| Atributo | ¿Para qué sirve? |
|----------|-----------------|
| `src` | La dirección de la imagen (src = source = fuente) |
| `alt` | Texto alternativo si la imagen no carga (importante para accesibilidad) |

```html
<!-- Imagen desde internet -->
<img src="https://ejemplo.com/foto.jpg" alt="Una foto chévere">

<!-- Imagen de tu computador (mismo directorio) -->
<img src="foto.png" alt="Mi foto">
```

## Formularios

Los formularios permiten al usuario **ingresar datos**. Los verás en logins, encuestas, búsquedas:

```html
<form>
  <label>Tu nombre:</label>
  <input type="text" placeholder="Escribe aquí...">

  <label>Tu email:</label>
  <input type="email" placeholder="tu@email.com">

  <button type="submit">Enviar</button>
</form>
```

Tipos de input más comunes:
- `type="text"` → campo de texto
- `type="email"` → solo acepta emails
- `type="password"` → oculta lo que se escribe
- `type="number"` → solo números

## Etiquetas semánticas (HTML5)

Las etiquetas semánticas le dicen a Google y a los navegadores **qué rol tiene cada parte de tu página**:

```html
<header>   <!-- Cabecera del sitio: logo y nav -->
<nav>      <!-- Menú de navegación -->
<main>     <!-- Contenido principal (solo uno por página) -->
<section>  <!-- Una sección de contenido -->
<article>  <!-- Un artículo o entrada de blog -->
<aside>    <!-- Contenido secundario / barra lateral -->
<footer>   <!-- Pie de página -->
```

```html
<!-- Ejemplo de estructura completa -->
<body>
  <header>
    <h1>Mi Blog</h1>
    <nav>
      <a href="/">Inicio</a>
      <a href="/about">Acerca de</a>
    </nav>
  </header>

  <main>
    <article>
      <h2>Mi primer post</h2>
      <p>Hoy aprendí HTML...</p>
    </article>
  </main>

  <footer>
    <p>© 2026 Mi Blog</p>
  </footer>
</body>
```

> 🏆 Las etiquetas semánticas hacen tu código más legible, mejor para Google (SEO) y más accesible para personas con discapacidad visual.
MD,
            ],
        ];

        foreach ($lecciones as $l) {
            Leccion::create(array_merge(['modulo_id' => $modulo->id], $l));
        }

        // ══════════════════════════════════════════════════════
        // EJERCICIOS
        // ══════════════════════════════════════════════════════

        $this->crearQuizOpcion($modulo->id, 1, false,
            '¿Qué significa la sigla HTML?',
            <<<'MD'
## 🌐 La sigla detrás de la web

Cada vez que abres Instagram, YouTube o cualquier página web, estás viendo HTML. Pero... ¿sabes qué significa esa sigla?

Basándote en lo que aprendiste, ¿qué significa **HTML**?
MD,
            [
                ['texto' => 'HyperText Markup Language', 'es_correcta' => true],
                ['texto' => 'High Tech Modern Language', 'es_correcta' => false],
                ['texto' => 'Hyper Tool Making Links', 'es_correcta' => false],
                ['texto' => 'How The Machine Learns', 'es_correcta' => false],
            ],
            '¿Recuerdas la parte del "marcado"? En inglés, "marcado" se dice markup.'
        );

        $this->crearQuizOpcion($modulo->id, 2, false,
            'Anatomía de una etiqueta HTML',
            <<<'MD'
## 🔬 ¿Cuál de estas es una etiqueta HTML correcta?

Sabes que las etiquetas HTML tienen apertura y cierre. Mira estas opciones y elige la que esté bien escrita:
MD,
            [
                ['texto' => '<h1>Título principal</h1>', 'es_correcta' => true],
                ['texto' => '[h1]Título principal[/h1]', 'es_correcta' => false],
                ['texto' => '{h1}Título principal{/h1}', 'es_correcta' => false],
                ['texto' => '(h1)Título principal(/h1)', 'es_correcta' => false],
            ],
            'Las etiquetas HTML usan los símbolos < y > (menor que y mayor que).'
        );

        $this->crearQuizOpcion($modulo->id, 3, false,
            'Etiqueta para párrafo de texto',
            <<<'MD'
## ✍️ ¿Qué etiqueta usas para un párrafo?

Vas a escribir el "Acerca de mí" de tu página web. Quieres un bloque de texto normal, no un título.

¿Qué etiqueta HTML usas para un **párrafo de texto**?
MD,
            [
                ['texto' => '<p>', 'es_correcta' => true],
                ['texto' => '<text>', 'es_correcta' => false],
                ['texto' => '<parrafo>', 'es_correcta' => false],
                ['texto' => '<t>', 'es_correcta' => false],
            ],
            'Es la primera letra de la palabra "paragraph" en inglés.'
        );

        $this->crearQuizOpcion($modulo->id, 4, false,
            'Listas ordenadas vs desordenadas',
            <<<'MD'
## 📋 ¿Ol o ul?

Quieres mostrar los **3 mejores jugadores de fútbol** de todos los tiempos, en orden del mejor al tercero. El orden importa.

¿Qué etiqueta HTML usas para esta lista **numerada**?

```
1. Pelé
2. Maradona
3. Messi
```
MD,
            [
                ['texto' => '<ol> (ordered list — lista ordenada/numerada)', 'es_correcta' => true],
                ['texto' => '<ul> (unordered list — lista de puntos)', 'es_correcta' => false],
                ['texto' => '<li> (list item — elemento de lista)', 'es_correcta' => false],
                ['texto' => '<numbered> (no existe en HTML)', 'es_correcta' => false],
            ],
            '"ol" viene de "ordered" = ordenado. "ul" viene de "unordered" = sin orden.'
        );

        $this->crearQuizOpcion($modulo->id, 5, false,
            'La etiqueta de hipervínculos',
            <<<'MD'
## 🔗 Haciendo links

Quieres poner un link en tu página para que la gente vea tu perfil de Instagram. ¿Qué etiqueta HTML usas para crear un **hipervínculo**?
MD,
            [
                ['texto' => '<a href="URL">Texto del link</a>', 'es_correcta' => true],
                ['texto' => '<link href="URL">Texto del link</link>', 'es_correcta' => false],
                ['texto' => '<url>URL</url>', 'es_correcta' => false],
                ['texto' => '<href>URL</href>', 'es_correcta' => false],
            ],
            '"a" viene de "anchor" (ancla en inglés). El atributo href va dentro de la etiqueta.'
        );

        $this->crearQuizOpcion($modulo->id, 6, false,
            'Imágenes en HTML',
            <<<'MD'
## 🖼️ Poniendo imágenes

Quieres mostrar una foto de tu equipo de fútbol favorito en tu página. La imagen está guardada con el nombre `nacional.jpg` en la misma carpeta.

¿Cuál de estos códigos es correcto?
MD,
            [
                ['texto' => '<img src="nacional.jpg" alt="Nacional">', 'es_correcta' => true],
                ['texto' => '<image src="nacional.jpg">', 'es_correcta' => false],
                ['texto' => '<img href="nacional.jpg">', 'es_correcta' => false],
                ['texto' => '<photo src="nacional.jpg"></photo>', 'es_correcta' => false],
            ],
            '"img" no tiene etiqueta de cierre. Usa "src" para la dirección y "alt" para la descripción.'
        );

        $this->crearQuizOpcion($modulo->id, 7, false,
            'Etiquetas semánticas de HTML5',
            <<<'MD'
## 🏗️ Semántica: hablarle a Google

Las etiquetas semánticas le dicen al navegador y a Google cuál es el rol de cada parte de la página.

¿Cuál de estas etiquetas HTML5 se usa para el **pie de página** de un sitio web?
MD,
            [
                ['texto' => '<footer>', 'es_correcta' => true],
                ['texto' => '<bottom>', 'es_correcta' => false],
                ['texto' => '<base>', 'es_correcta' => false],
                ['texto' => '<end>', 'es_correcta' => false],
            ],
            'Footer = pie en inglés. Es la sección al final de la página con copyright, links, etc.'
        );

        $this->crearQuizOpcion($modulo->id, 8, true,
            'Formularios: input de texto',
            <<<'MD'
## 📝 El formulario de registro

Estás creando un formulario de registro para tu app. Necesitas un campo donde el usuario escriba **su nombre completo** (texto libre).

¿Qué etiqueta y atributo usas?
MD,
            [
                ['texto' => '<input type="text">', 'es_correcta' => true],
                ['texto' => '<textbox>', 'es_correcta' => false],
                ['texto' => '<text-input>', 'es_correcta' => false],
                ['texto' => '<form type="text">', 'es_correcta' => false],
            ],
            'La etiqueta es <input> y el tipo "text" indica que acepta cualquier texto.'
        );

        $this->crearCodigoLibre(
            $modulo->id, 9, true,
            'Crea tu página personal en HTML',
            <<<'MD'
## 🧑‍💻 Tu primera página personal

Es hora de escribir código real. Vas a crear **tu página personal en HTML**.

### Debe incluir:
1. La estructura básica completa (`<!DOCTYPE html>`, `<html>`, `<head>`, `<body>`)
2. Un `<title>` con tu nombre
3. Un `<h1>` con tu nombre como título principal
4. Un `<h2>` que diga "Sobre mí"
5. Un `<p>` con una presentación tuya (mínimo 2 oraciones)
6. Un `<h2>` que diga "Mis hobbies"
7. Una lista `<ul>` con al menos 3 hobbies tuyos
8. Un link `<a>` a tu red social favorita (o a YouTube si no tienes)

### Ejemplo de cómo debería verse en el navegador:

```
[Tu nombre aquí]

Sobre mí
Hola, soy [nombre] y tengo [edad] años...

Mis hobbies
• Fútbol
• Videojuegos
• Programar
```

Escribe el código en VS Code y guarda el archivo como `index.html`. Ábrelo en Chrome y toma una foto o screenshot para mostrárselo a papá. ¡Cuando funcione, pega el código aquí! 💪
MD,
            <<<'HTML'
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Mi nombre</title>
  </head>
  <body>
    <!-- Aquí va tu código -->
  </body>
</html>
HTML,
            <<<'MD'
El estudiante debe entregar una página HTML válida que incluya:
- Estructura completa: <!DOCTYPE html>, <html>, <head>, <body>
- <title> con el nombre del estudiante
- <h1> con el nombre (título principal)
- Dos <h2> como subtítulos ("Sobre mí" y "Mis hobbies")
- <p> con texto de presentación personal
- <ul> con al menos 3 elementos <li>
- <a href="..."> con un link real

Aprobar si el código es HTML válido y tiene todos los elementos pedidos. La estructura no tiene que ser perfecta — lo importante es que funcione en el navegador. Si el texto es creativo y personal, considera marcarlo como perfecto.
MD,
            'Asegúrate de cerrar todas las etiquetas. Cada <ul> necesita sus <li> adentro. Y no olvides el <a href="URL">texto</a>.'
        );

        $this->crearMiniProyecto(
            $modulo->id, 10, true,
            'Mi primera página web completa',
            <<<'MD'
## 🏆 Proyecto final: Tu página web completa

¡Ya tienes las bases de HTML! Ahora vas a crear una **página web completa** que muestre todo lo que aprendiste.

### Lo que debe tener tu página:

#### Estructura semántica:
- `<header>` con un `<h1>` y un `<nav>` con al menos 2 links (aunque no lleven a ningún lado todavía)
- `<main>` con el contenido principal
- `<footer>` con tu nombre y el año

#### Contenido en el `<main>`:
1. **Sección "Sobre mí"** (`<section>`): foto (con `<img>`) + párrafo de presentación
2. **Sección "Mi top 3"** (`<section>`): una lista ordenada `<ol>` de tus 3 películas, canciones o jugadores favoritos
3. **Sección "Contáctame"** (`<section>`): un formulario con campo de nombre, campo de email y botón "Enviar"

### Puntos extra ⭐:
- Usar etiquetas `<strong>` y `<em>` para resaltar palabras importantes
- Agregar comentarios HTML explicando cada sección
- Que la información sea personal y creativa

Guarda el archivo como `mi-pagina.html`, ábrelo en Chrome y verifica que todo se vea bien antes de enviarlo. ¡Muéstrale el resultado a papá! 🎉
MD,
            null,
            <<<'MD'
Proyecto final de HTML. Criterios de evaluación:

OBLIGATORIO (para aprobar):
- Estructura semántica: <header>, <main>, <footer>
- <nav> con al menos 2 links <a>
- Al menos 2 <section> dentro del <main>
- Una imagen <img> con src y alt
- Una lista ordenada <ol> con <li>
- Un formulario con <input type="text">, <input type="email"> y <button>

PERFECTO (además de lo anterior):
- Usa etiquetas semánticas correctamente
- El contenido es personal y creativo, no solo ejemplos genéricos
- Incluye comentarios HTML
- Tiene <strong> o <em> para resaltar texto
- El código está bien indentado y organizado

Dar feedback específico sobre qué etiquetas usó bien y qué puede mejorar. Aprobar si tiene los elementos básicos aunque tengan errores menores de sintaxis.
MD,
            'Empieza por el esqueleto completo. Luego agrega el header, luego el main con secciones, y al final el footer. ¡Un paso a la vez, parce!'
        );
    }

    // ══════════════════════════════════════════════════════
    // HELPERS
    // ══════════════════════════════════════════════════════

    private function crearQuizOpcion(int $moduloId, int $orden, bool $obligatorio, string $titulo, string $enunciado, array $opciones, ?string $pista = null): void
    {
        $ej = Ejercicio::create([
            'modulo_id'            => $moduloId,
            'orden'                => $orden,
            'tipo'                 => 'quiz_opcion',
            'titulo'               => $titulo,
            'enunciado'            => $enunciado,
            'es_obligatorio'       => $obligatorio,
            'recompensa_ejercicio' => 100,
            'recompensa_perfecto'  => 150,
            'pista'                => $pista,
        ]);

        foreach ($opciones as $i => $op) {
            EjercicioOpcion::create([
                'ejercicio_id' => $ej->id,
                'texto'        => $op['texto'],
                'es_correcta'  => $op['es_correcta'],
                'orden'        => $i + 1,
            ]);
        }
    }

    private function crearCodigoLibre(int $moduloId, int $orden, bool $obligatorio, string $titulo, string $enunciado, ?string $codigoBase, string $solucion, ?string $pista = null): void
    {
        Ejercicio::create([
            'modulo_id'            => $moduloId,
            'orden'                => $orden,
            'tipo'                 => 'codigo_libre',
            'titulo'               => $titulo,
            'enunciado'            => $enunciado,
            'codigo_base'          => $codigoBase,
            'solucion'             => $solucion,
            'es_obligatorio'       => $obligatorio,
            'recompensa_ejercicio' => 100,
            'recompensa_perfecto'  => 150,
            'pista'                => $pista,
        ]);
    }

    private function crearMiniProyecto(int $moduloId, int $orden, bool $obligatorio, string $titulo, string $enunciado, ?string $codigoBase, string $solucion, ?string $pista = null): void
    {
        Ejercicio::create([
            'modulo_id'            => $moduloId,
            'orden'                => $orden,
            'tipo'                 => 'mini_proyecto',
            'titulo'               => $titulo,
            'enunciado'            => $enunciado,
            'codigo_base'          => $codigoBase,
            'solucion'             => $solucion,
            'es_obligatorio'       => $obligatorio,
            'recompensa_ejercicio' => 100,
            'recompensa_perfecto'  => 200,
            'pista'                => $pista,
        ]);
    }
}
