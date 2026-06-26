<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class ModuloCSSSeeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'css-disenando-con-estilo')->firstOrFail();

        // ══════════════════════════════════════════════════════
        // LECCIONES
        // ══════════════════════════════════════════════════════

        $lecciones = [
            [
                'orden' => 1,
                'tipo'  => 'teoria',
                'titulo' => '¿Qué es CSS? El maquillaje de la web',
                'contenido' => <<<'MD'
# ¿Qué es CSS? El maquillaje de la web 🎨

Si HTML es el esqueleto de la web, CSS es **la ropa, el maquillaje y el estilo**. Con CSS decides los colores, las fuentes, los tamaños y cómo se organiza todo en pantalla.

## Sin CSS vs con CSS

Imagínate tu página HTML sin CSS: todo en blanco y negro, fuente de los años 90, todo apilado verticalmente. Exactamente como los primeros sitios web de 1991.

Con CSS, esa misma página puede tener colores vibrantes, fuentes modernas y un layout increíble.

## ¿Cómo se conecta CSS con HTML?

Hay tres formas, pero la más profesional es un **archivo separado** `.css`:

```html
<!-- En tu archivo HTML, dentro del <head>: -->
<link rel="stylesheet" href="estilos.css">
```

También puedes escribir CSS directamente en el `<head>`:

```html
<head>
  <style>
    h1 {
      color: green;
    }
  </style>
</head>
```

O directamente en la etiqueta (no recomendado para proyectos grandes):
```html
<h1 style="color: green;">Título verde</h1>
```

## La sintaxis de CSS

```css
selector {
  propiedad: valor;
  propiedad: valor;
}
```

Ejemplo real:

```css
h1 {
  color: #a3e635;
  font-size: 32px;
  font-weight: bold;
}

p {
  color: #666;
  line-height: 1.6;
}
```

> 🧠 Cada línea CSS es: `propiedad: valor;` — no olvides el punto y coma al final de cada línea.
MD,
            ],
            [
                'orden' => 2,
                'tipo'  => 'teoria',
                'titulo' => 'Selectores, colores y tipografía',
                'contenido' => <<<'MD'
# Selectores, colores y tipografía 🎯

## Selectores: ¿a quién le aplico el estilo?

Los selectores son la forma de decirle a CSS **a qué elemento HTML le vas a aplicar el estilo**.

### Los tres selectores básicos:

```css
/* Por etiqueta: aplica a TODOS los párrafos */
p {
  color: blue;
}

/* Por clase: aplica a todos los que tengan class="titulo-verde" */
.titulo-verde {
  color: green;
}

/* Por ID: aplica solo al elemento con id="logo" */
#logo {
  width: 200px;
}
```

En HTML, así asignas clases e IDs:
```html
<p class="titulo-verde">Este texto es verde</p>
<img id="logo" src="logo.png" alt="Logo">
```

> 💡 Las clases (`.`) se pueden repetir en varios elementos. Los IDs (`#`) deben ser únicos en toda la página.

## Colores

CSS entiende los colores de varias formas:

```css
/* Por nombre (en inglés) */
color: red;
color: blue;
color: darkgray;

/* Hexadecimal (el más usado por diseñadores) */
color: #a3e635;   /* verde lima */
color: #0d1117;   /* casi negro */
color: #ffffff;   /* blanco */

/* RGB: Red, Green, Blue (0-255 cada uno) */
color: rgb(163, 230, 53);   /* mismo verde lima */
color: rgba(0, 0, 0, 0.5); /* negro semitransparente */
```

## Tipografía

```css
p {
  font-family: 'Arial', sans-serif;  /* fuente */
  font-size: 16px;                   /* tamaño */
  font-weight: bold;                  /* grosor: normal, bold, 100-900 */
  font-style: italic;                 /* normal o italic */
  text-align: center;                 /* left, center, right */
  line-height: 1.6;                   /* espacio entre líneas */
  text-decoration: underline;         /* subrayado, none, line-through */
}
```
MD,
            ],
            [
                'orden' => 3,
                'tipo'  => 'teoria',
                'titulo' => 'El box model y Flexbox',
                'contenido' => <<<'MD'
# El box model y Flexbox 📦

## El Box Model: todo es una caja

En CSS, **cada elemento HTML es una caja rectangular**. Esa caja tiene cuatro capas:

```
┌─────────────────────────────┐
│         MARGIN              │ (espacio fuera de la caja)
│  ┌───────────────────────┐  │
│  │       BORDER          │  │ (el borde de la caja)
│  │  ┌─────────────────┐  │  │
│  │  │    PADDING      │  │  │ (espacio dentro, entre borde y contenido)
│  │  │  ┌───────────┐  │  │  │
│  │  │  │ CONTENT   │  │  │  │ (el texto o imagen)
│  │  │  └───────────┘  │  │  │
│  │  └─────────────────┘  │  │
│  └───────────────────────┘  │
└─────────────────────────────┘
```

```css
div {
  width: 300px;
  height: 150px;

  padding: 20px;           /* espacio interno (todos lados) */
  border: 2px solid black; /* borde */
  margin: 10px;            /* espacio externo (todos lados) */

  /* También puedes especificar lados individualmente: */
  padding-top: 10px;
  margin-left: 20px;
}
```

## Flexbox: el layout moderno

Flexbox es el sistema de diseño más usado hoy en día. Permite organizar elementos en filas o columnas fácilmente.

Se activa en el **contenedor padre**:

```css
.contenedor {
  display: flex;
  justify-content: center;  /* eje horizontal: flex-start, center, flex-end, space-between */
  align-items: center;      /* eje vertical: flex-start, center, flex-end */
  gap: 16px;                /* espacio entre elementos */
  flex-wrap: wrap;          /* si los hijos no caben, pasan a la siguiente fila */
}
```

```html
<!-- En HTML -->
<div class="contenedor">
  <div>Caja 1</div>
  <div>Caja 2</div>
  <div>Caja 3</div>
</div>
```

### Casos de uso comunes:

```css
/* Centrar algo perfectamente en pantalla */
.centrado {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

/* Navbar con logo a la izquierda y links a la derecha */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

/* Cards en fila que se envuelven */
.cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}
```

> 🚀 Con `display: flex` en el padre tienes el control total de cómo se organizan los hijos. Es como tener un organizador de habitación profesional para tus elementos HTML.
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
            '¿Para qué sirve CSS?',
            <<<'MD'
## 🎨 HTML y CSS: ¿en qué se diferencian?

Ya sabes que HTML crea la estructura de una página. Pero ¿para qué sirve CSS exactamente?
MD,
            [
                ['texto' => 'Para darle estilos, colores y diseño visual a la página web', 'es_correcta' => true],
                ['texto' => 'Para crear la estructura y el contenido de la página', 'es_correcta' => false],
                ['texto' => 'Para guardar datos en una base de datos', 'es_correcta' => false],
                ['texto' => 'Para programar la lógica y funcionalidad del sitio', 'es_correcta' => false],
            ],
            'CSS = Cascading Style Sheets. La palabra "style" te da la pista.'
        );

        $this->crearQuizOpcion($modulo->id, 2, false,
            'Sintaxis correcta de CSS',
            <<<'MD'
## ✍️ ¿Cuál es la sintaxis correcta?

CSS tiene una sintaxis muy específica. Si la escribes mal, no funciona.

¿Cuál de estos ejemplos tiene la **sintaxis CSS correcta**?
MD,
            [
                ['texto' => 'body { color: red; }', 'es_correcta' => true],
                ['texto' => 'body: color = red;', 'es_correcta' => false],
                ['texto' => '{body} color: red', 'es_correcta' => false],
                ['texto' => 'body color red;', 'es_correcta' => false],
            ],
            'La estructura es: selector { propiedad: valor; } — observa las llaves { } y los dos puntos :'
        );

        $this->crearQuizOpcion($modulo->id, 3, false,
            'Selectores: elemento, clase e ID',
            <<<'MD'
## 🎯 ¿A quién le aplico el estilo?

Tienes esta página HTML:

```html
<h1 id="titulo-principal">Mi Blog</h1>
<p class="texto-destacado">Primer párrafo</p>
<p>Segundo párrafo normal</p>
<p class="texto-destacado">Tercer párrafo</p>
```

Quieres que **solo los elementos con class="texto-destacado"** tengan color azul. ¿Qué selector usas en CSS?
MD,
            [
                ['texto' => '.texto-destacado { color: blue; }', 'es_correcta' => true],
                ['texto' => '#texto-destacado { color: blue; }', 'es_correcta' => false],
                ['texto' => 'texto-destacado { color: blue; }', 'es_correcta' => false],
                ['texto' => 'p { color: blue; }', 'es_correcta' => false],
            ],
            'Las clases se seleccionan con un punto (.) antes del nombre. Los IDs con numeral (#).'
        );

        $this->crearQuizOpcion($modulo->id, 4, false,
            'Colores en CSS: hexadecimal',
            <<<'MD'
## 🎨 El color negro en hexadecimal

Los diseñadores web usan mucho los colores en formato hexadecimal (# + 6 caracteres).

El color **negro puro** en hexadecimal es cuando los tres canales de color (rojo, verde, azul) están en **cero**. En hex, el cero se escribe como `00`.

¿Cuál es el color negro en hexadecimal?
MD,
            [
                ['texto' => '#000000', 'es_correcta' => true],
                ['texto' => '#ffffff', 'es_correcta' => false],
                ['texto' => '#000fff', 'es_correcta' => false],
                ['texto' => 'rgb(255, 255, 255)', 'es_correcta' => false],
            ],
            '#000000 = negro (todo en cero). #ffffff = blanco (todo al máximo).'
        );

        $this->crearQuizOpcion($modulo->id, 5, false,
            'Box model: padding vs margin',
            <<<'MD'
## 📦 La caja del box model

Imagínate un regalo bien empacado:
- La caja es el elemento HTML
- El papel de relleno dentro de la caja (entre el borde y el regalo) es el...
- El espacio en la mesa entre una caja y otra es el...

En el box model de CSS, el espacio **dentro** del borde (entre el borde y el contenido) se llama:
MD,
            [
                ['texto' => 'padding (relleno interno)', 'es_correcta' => true],
                ['texto' => 'margin (margen externo)', 'es_correcta' => false],
                ['texto' => 'border (borde)', 'es_correcta' => false],
                ['texto' => 'content (contenido)', 'es_correcta' => false],
            ],
            'Padding = relleno. Va entre el BORDE y el CONTENIDO. Margin va FUERA del borde.'
        );

        $this->crearQuizOpcion($modulo->id, 6, false,
            'font-weight: bold equivale a...',
            <<<'MD'
## 💪 Grosor de fuente

En CSS, el grosor de la fuente se controla con `font-weight`. Se puede usar palabras (`bold`, `normal`) o números del 100 al 900.

`font-weight: bold` es equivalente a ¿qué número?
MD,
            [
                ['texto' => '700', 'es_correcta' => true],
                ['texto' => '400', 'es_correcta' => false],
                ['texto' => '900', 'es_correcta' => false],
                ['texto' => '500', 'es_correcta' => false],
            ],
            'En CSS: normal = 400, bold = 700. Los valores 800 y 900 son "extra bold" / "black".'
        );

        $this->crearQuizOpcion($modulo->id, 7, false,
            'Flexbox: justify-content',
            <<<'MD'
## 🔲 Centrando con Flexbox

Tienes un `<div>` con `display: flex`. Dentro tienes 3 tarjetas. Quieres que las tarjetas queden **centradas horizontalmente** con el mismo espacio entre ellas.

¿Qué propiedad y valor usas?
MD,
            [
                ['texto' => 'justify-content: space-between', 'es_correcta' => true],
                ['texto' => 'align-items: center', 'es_correcta' => false],
                ['texto' => 'text-align: center', 'es_correcta' => false],
                ['texto' => 'margin: auto', 'es_correcta' => false],
            ],
            'justify-content controla el eje horizontal en flexbox. space-between distribuye el espacio entre elementos.'
        );

        $this->crearQuizOpcion($modulo->id, 8, true,
            '¿Cómo se conecta CSS a un archivo HTML?',
            <<<'MD'
## 🔗 Enlazando tu CSS

Tienes un archivo `estilos.css` y quieres conectarlo a tu página `index.html`.

¿Cuál es la forma correcta de hacer esa conexión dentro del `<head>` de tu HTML?
MD,
            [
                ['texto' => '<link rel="stylesheet" href="estilos.css">', 'es_correcta' => true],
                ['texto' => '<css src="estilos.css">', 'es_correcta' => false],
                ['texto' => '<style src="estilos.css">', 'es_correcta' => false],
                ['texto' => '<import href="estilos.css">', 'es_correcta' => false],
            ],
            'Se usa la etiqueta <link> con rel="stylesheet" y href con la ruta del archivo CSS.'
        );

        $this->crearCodigoLibre(
            $modulo->id, 9, true,
            'Estiliza tu página HTML con CSS',
            <<<'MD'
## 🎨 De aburrido a increíble con CSS

Tienes esta página HTML básica. Tu misión es crearle un archivo CSS (`estilos.css`) y enlazarlo para que se vea profesional.

### Tu HTML base:

```html
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Mi Perfil</title>
    <!-- Aquí debes enlazar tu CSS -->
  </head>
  <body>
    <header>
      <h1>Mi Perfil</h1>
      <nav>
        <a href="#">Inicio</a>
        <a href="#">Proyectos</a>
        <a href="#">Contacto</a>
      </nav>
    </header>
    <main>
      <h2>Sobre mí</h2>
      <p>Aquí va mi descripción personal.</p>
    </main>
    <footer>
      <p>© 2026</p>
    </footer>
  </body>
</html>
```

### Tu CSS debe incluir:
1. **Fuente global**: `font-family: 'Arial', sans-serif` en el `body`
2. **Fondo de página**: un color de fondo al `body` (no blanco)
3. **Color al `h1`**: cualquier color que no sea negro
4. **Header estilizado**: fondo diferente + padding
5. **Links del nav**: quitar el subrayado (`text-decoration: none`) + cambiar color
6. **Footer**: texto centrado + color gris

Guarda ambos archivos en la misma carpeta, abre en Chrome y cuando se vea bien, pega aquí tu código CSS 💪
MD,
            <<<'CSS'
/* Escribe tu CSS aquí */

body {
  font-family: 'Arial', sans-serif;
  background-color: /* pon un color */;
}
CSS,
            <<<'MD'
El estudiante debe entregar un archivo CSS (o el código CSS) que estilice la página HTML provista.

OBLIGATORIO (para aprobar):
- font-family en body
- background-color en body (no blanco puro #ffffff)
- color en h1 diferente al negro
- Estilo en el header: al menos padding y background
- text-decoration: none en los links del nav
- Algún estilo en el footer (text-align o color)

PERFECTO (además de lo anterior):
- Los colores son armónicos y el diseño se ve bien
- Usa selectores de clase además de etiquetas
- Usa rem o em en lugar de solo px para tamaños
- El nav tiene un layout con flexbox

Dar feedback específico. Si el CSS cambia colores y tiene estilos funcionales, aprobar aunque no sea perfecto visualmente.
MD,
            'Empieza por el body (fondo y fuente), luego el header, luego el nav. Construye de arriba hacia abajo, como si pintaras la página.'
        );

        $this->crearMiniProyecto(
            $modulo->id, 10, true,
            'Diseño completo: HTML + CSS',
            <<<'MD'
## 🏆 Proyecto final: Tu página web con diseño profesional

¡El momento de verdad! Vas a combinar HTML + CSS para crear una página que luzca moderna y profesional.

### Crea dos archivos: `index.html` + `estilos.css`

---

### Parte 1 — HTML (estructura)

Tu página debe tener:
- `<header>` con tu nombre/logo y `<nav>` con 3 links
- `<main>` con **al menos 2 secciones** (`<section>`):
  - Sección "Portafolio" o "Proyectos" con **3 tarjetas** (cada una en un `<div class="card">`)
  - Sección de "Contacto" con un formulario
- `<footer>` con texto y links

### Parte 2 — CSS (diseño)

Tu CSS debe usar:

1. **Variables CSS** para los colores principales:
```css
:root {
  --color-primario: /* tu color */;
  --color-fondo: /* tu color */;
}
```

2. **Flexbox** para:
   - El navbar (logo izquierda, links derecha)
   - Las tarjetas de proyectos en fila

3. **Box model** visible:
   - `padding` y `border-radius` en las cards
   - `margin` para separar secciones

4. **Tipografía** cuidada:
   - Al menos 2 tamaños de fuente distintos
   - Algún texto en bold o italic con propósito

### Puntos extra ⭐:
- Paleta de colores coherente (2-3 colores que combinen)
- Un efecto hover en los links del nav (`:hover` en CSS)
- Que funcione bien en pantallas más estrechas

¡Guarda todo, abre en Chrome, y cuando se vea como una página web real, envía el código! 🚀
MD,
            null,
            <<<'MD'
Proyecto final CSS. Criterios de evaluación:

OBLIGATORIO (para aprobar):
- Dos archivos: HTML válido + CSS enlazado correctamente
- Navbar con flexbox (justify-content para logo/links)
- Al menos 2 secciones en el main
- Cards con padding y border-radius
- Variables CSS (:root con al menos 1 variable de color)
- Formulario de contacto visible
- Footer estilizado

PERFECTO (además de lo anterior):
- La paleta de colores es coherente y atractiva
- Los textos son legibles (contraste, tamaño, espaciado)
- Hay un efecto :hover en links o botones
- Las cards forman una fila con flexbox y gap
- El diseño general se ve como una página web moderna real
- El contenido es personal y creativo

Dar feedback específico sobre qué aspectos del diseño son buenos y qué puede mejorar. Si tiene flexbox funcionando y variables CSS aunque otros elementos sean básicos, aprobar. El perfecto se da cuando el resultado se ve realmente profesional para la edad.
MD,
            'Empieza por las variables CSS en :root. Luego el navbar con flexbox. Luego las cards. Al final el footer. ¡Si te pierdes, repasa la lección de flexbox!'
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
