<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo9Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'hagamos-un-juego-visual')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => 'Python Turtle — dibujando con código',
                'contenido' => <<<'MD'
# Python Turtle — dibujando con código 🐢

`turtle` es una librería que viene incluida con Python. No necesitas instalar nada. Mueve una tortuga por la pantalla y deja un rastro, creando dibujos.

## Tu primer dibujo

```python
import turtle

# Configurar la pantalla
pantalla = turtle.Screen()
pantalla.title("Mi primer dibujo")
pantalla.bgcolor("black")

# Crear la tortuga
t = turtle.Turtle()
t.color("lime")
t.speed(5)  # velocidad (1=lento, 10=rápido, 0=instantáneo)

# Dibujar un cuadrado
for i in range(4):
    t.forward(100)   # avanza 100 píxeles
    t.right(90)      # gira 90 grados a la derecha

# Mantener la ventana abierta
turtle.done()
```

## Comandos básicos de movimiento

```python
t.forward(100)    # avanza 100 píxeles
t.backward(50)    # retrocede 50 píxeles
t.right(90)       # gira a la derecha 90 grados
t.left(45)        # gira a la izquierda 45 grados
t.goto(0, 0)      # va a las coordenadas (0, 0)
t.home()          # vuelve al centro
```

## Estilos

```python
t.penup()         # levanta el lápiz (no dibuja al mover)
t.pendown()       # baja el lápiz (dibuja al mover)
t.pensize(3)      # grosor del lápiz
t.color("lime")   # color del lápiz
t.fillcolor("yellow")
t.begin_fill()
# dibujar figura
t.end_fill()      # rellena la figura
```

## Colores disponibles

`"red"`, `"blue"`, `"green"`, `"yellow"`, `"white"`, `"black"`, `"orange"`, `"purple"`, `"lime"`, `"cyan"`, `"pink"`, y muchos más.
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Bucles y formas geométricas con Turtle',
                'contenido' => <<<'MD'
# Bucles y formas geométricas con Turtle 🔷

Los bucles son perfectos para dibujar formas regulares en Turtle.

## Cuadrado

```python
import turtle
t = turtle.Turtle()

for i in range(4):
    t.forward(100)
    t.right(90)

turtle.done()
```

## Triángulo equilátero

```python
for i in range(3):
    t.forward(100)
    t.right(120)   # 360 / 3 = 120 grados
```

## Círculo (aproximado con muchos lados)

```python
for i in range(36):
    t.forward(10)
    t.right(10)   # 36 × 10 = 360 grados total
```

O más fácil:
```python
t.circle(50)  # círculo de radio 50
```

## Estrella de 5 puntas

```python
for i in range(5):
    t.forward(100)
    t.right(144)  # la clave está en el ángulo 144
```

## Espiral de colores

```python
import turtle
t = turtle.Turtle()
t.speed(0)

colores = ["red", "orange", "yellow", "green", "blue", "purple"]

for i in range(100):
    t.color(colores[i % len(colores)])
    t.forward(i * 2)
    t.right(91)

turtle.done()
```

## Fórmula para polígonos regulares

Para dibujar un polígono de N lados:
```
ángulo = 360 / N
```

```python
N = 6  # hexágono
for i in range(N):
    t.forward(80)
    t.right(360 / N)
```
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'teoria',
                'titulo' => 'Eventos de teclado en Turtle',
                'contenido' => <<<'MD'
# Eventos de teclado en Turtle ⌨️

Puedes hacer que la tortuga responda a las teclas del teclado para crear animaciones interactivas.

## Configurar las teclas

```python
import turtle

pantalla = turtle.Screen()
t = turtle.Turtle()
t.speed(0)

def mover_arriba():
    t.setheading(90)   # apunta hacia arriba
    t.forward(20)

def mover_abajo():
    t.setheading(270)  # apunta hacia abajo
    t.forward(20)

def mover_derecha():
    t.setheading(0)    # apunta a la derecha
    t.forward(20)

def mover_izquierda():
    t.setheading(180)  # apunta a la izquierda
    t.forward(20)

# Registrar los eventos de teclado
pantalla.onkey(mover_arriba,    "Up")
pantalla.onkey(mover_abajo,     "Down")
pantalla.onkey(mover_derecha,   "Right")
pantalla.onkey(mover_izquierda, "Left")

pantalla.listen()  # ← IMPORTANTE: activa el escucha de teclado
turtle.done()      # ← IMPORTANTE: mantiene la ventana abierta
```

## `setheading()` — orientación absoluta

```
0°   → derecha →
90°  → arriba ↑
180° → izquierda ←
270° → abajo ↓
```

## Agregar más controles

```python
def cambiar_color():
    import random
    colores = ["red", "blue", "green", "yellow", "purple"]
    t.color(random.choice(colores))

def limpiar():
    t.clear()
    t.home()

pantalla.onkey(cambiar_color, "c")
pantalla.onkey(limpiar, "space")
```

## ⚠️ Importante

Siempre termina con `pantalla.listen()` y `turtle.done()`. Sin `listen()`, el teclado no funciona.
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'Introducción a Pygame — la ventana del juego',
                'contenido' => <<<'MD'
# Introducción a Pygame 🎮

`pygame` es una librería para crear juegos 2D en Python. Necesitas instalarla:

```bash
pip install pygame
```

## Estructura básica de un programa Pygame

```python
import pygame

# 1. Inicializar pygame
pygame.init()

# 2. Crear la ventana
ANCHO = 800
ALTO = 600
ventana = pygame.display.set_mode((ANCHO, ALTO))
pygame.display.set_caption("Mi Juego")

# 3. Colores (R, G, B)
NEGRO   = (0, 0, 0)
BLANCO  = (255, 255, 255)
VERDE   = (163, 230, 53)    # el verde de PythonJr
ROJO    = (248, 81, 73)
AZUL    = (88, 166, 255)

# 4. El game loop (bucle principal)
corriendo = True
while corriendo:

    # Eventos (teclado, mouse, cerrar ventana)
    for evento in pygame.event.get():
        if evento.type == pygame.QUIT:
            corriendo = False

    # Dibujar
    ventana.fill(NEGRO)  # fondo negro

    # Actualizar pantalla
    pygame.display.flip()

# 5. Cerrar pygame
pygame.quit()
```

## Entendiendo el Game Loop

El bucle `while corriendo` es el corazón del juego. Se ejecuta **60 veces por segundo** (aprox.) y hace 3 cosas cada vez:
1. **Procesar eventos** (teclas presionadas, mouse, cerrar ventana)
2. **Actualizar el estado** (mover objetos, calcular colisiones)
3. **Dibujar** (borrar pantalla, redibujar todo)

## Dibujar formas básicas

```python
# Rectángulo: (surface, color, (x, y, ancho, alto))
pygame.draw.rect(ventana, VERDE, (100, 100, 50, 50))

# Círculo: (surface, color, (cx, cy), radio)
pygame.draw.circle(ventana, ROJO, (400, 300), 30)

# Línea: (surface, color, inicio, fin, grosor)
pygame.draw.line(ventana, BLANCO, (0, 0), (800, 600), 2)
```
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'El bucle del juego y movimiento',
                'contenido' => <<<'MD'
# Movimiento en Pygame 🏃

## Detectar teclas presionadas

```python
import pygame

pygame.init()
ventana = pygame.display.set_mode((800, 600))
reloj = pygame.time.Clock()

NEGRO = (0, 0, 0)
VERDE = (163, 230, 53)

# Posición del jugador
x = 400
y = 300
velocidad = 5

corriendo = True
while corriendo:

    for evento in pygame.event.get():
        if evento.type == pygame.QUIT:
            corriendo = False

    # Detectar teclas MIENTRAS están presionadas
    teclas = pygame.key.get_pressed()

    if teclas[pygame.K_LEFT]:
        x -= velocidad
    if teclas[pygame.K_RIGHT]:
        x += velocidad
    if teclas[pygame.K_UP]:
        y -= velocidad
    if teclas[pygame.K_DOWN]:
        y += velocidad

    # Dibujar
    ventana.fill(NEGRO)
    pygame.draw.rect(ventana, VERDE, (x, y, 40, 40))
    pygame.display.flip()

    reloj.tick(60)  # limitar a 60 FPS

pygame.quit()
```

## Mantener el jugador en pantalla

```python
x = max(0, min(x, 800 - 40))   # limita entre 0 y 760
y = max(0, min(y, 600 - 40))   # limita entre 0 y 560
```

## `pygame.time.Clock()`

El `reloj` controla la velocidad del juego. Sin `reloj.tick(60)`, el juego correría tan rápido como el CPU lo permita (¡miles de FPS!).

## Mostrar texto en pantalla

```python
fuente = pygame.font.Font(None, 36)
texto = fuente.render("Score: 0", True, BLANCO)
ventana.blit(texto, (10, 10))  # blit = pegar en pantalla
```
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'teoria',
                'titulo' => 'Colisiones básicas en Pygame',
                'contenido' => <<<'MD'
# Colisiones básicas en Pygame 💥

Una **colisión** ocurre cuando dos objetos se tocan. En juegos 2D, la forma más simple de detectarlas es con **rectángulos** (Rect).

## `pygame.Rect`

```python
# Crear rectángulos
jugador = pygame.Rect(100, 100, 40, 40)  # x, y, ancho, alto
enemigo = pygame.Rect(200, 100, 30, 30)

# Detectar colisión
if jugador.colliderect(enemigo):
    print("¡Colisión!")
```

## Mover el Rect con el jugador

```python
jugador = pygame.Rect(400, 300, 40, 40)
velocidad = 5

# En el game loop:
teclas = pygame.key.get_pressed()
if teclas[pygame.K_LEFT]:  jugador.x -= velocidad
if teclas[pygame.K_RIGHT]: jugador.x += velocidad
if teclas[pygame.K_UP]:    jugador.y -= velocidad
if teclas[pygame.K_DOWN]:  jugador.y += velocidad

# Dibujar el rectángulo
pygame.draw.rect(ventana, VERDE, jugador)
```

## Obstáculo que cae

```python
import random

obstaculo = pygame.Rect(random.randint(0, 760), -30, 40, 40)
velocidad_caida = 3

# En el game loop:
obstaculo.y += velocidad_caida

# Si salió de la pantalla, reaparece arriba
if obstaculo.y > 600:
    obstaculo.y = -30
    obstaculo.x = random.randint(0, 760)

# Colisión con el jugador
if jugador.colliderect(obstaculo):
    print("¡Te golpearon!")
```

## Lista de obstáculos

```python
obstaculos = [pygame.Rect(random.randint(0, 760), random.randint(-500, -30), 40, 40)
              for _ in range(5)]

for obs in obstaculos:
    obs.y += 3
    if obs.y > 600:
        obs.y = random.randint(-200, -30)
        obs.x = random.randint(0, 760)
    pygame.draw.rect(ventana, ROJO, obs)
    if jugador.colliderect(obs):
        corriendo = False  # game over
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
                'orden' => 1, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Dibuja 3 figuras geométricas con Turtle',
                'enunciado' => "Crea un programa con Python Turtle que dibuje estas **3 figuras** usando bucles `for`:\n\n1. Un **cuadrado** verde (lado = 100)\n2. Un **triángulo** amarillo (lado = 80)\n3. Una **estrella** roja de 5 puntas (lado = 100)\n\nPuedes dibujarlas en diferentes posiciones usando `t.penup()` y `t.goto(x, y)`.\n\n**Pista para la estrella:** el ángulo mágico es `144`\n```python\nfor i in range(5):\n    t.forward(100)\n    t.right(144)\n```",
                'codigo_base' => "import turtle\n\nt = turtle.Turtle()\nt.speed(5)\npantalla = turtle.Screen()\npantalla.bgcolor(\"black\")\n\n# Cuadrado verde\nt.color(\"green\")\nfor i in range(4):\n    t.forward(100)\n    t.right(90)\n\n# Muévete para el siguiente dibujo\nt.penup()\nt.goto(150, 0)\nt.pendown()\n\n# Triángulo amarillo\n# ...\n\n# Estrella roja\n# ...\n\nturtle.done()\n",
                'solucion' => "import turtle\n\nt = turtle.Turtle()\nt.speed(5)\npantalla = turtle.Screen()\npantalla.bgcolor(\"black\")\n\nt.color(\"lime\")\nfor i in range(4):\n    t.forward(100)\n    t.right(90)\n\nt.penup(); t.goto(200, 0); t.pendown()\nt.color(\"yellow\")\nfor i in range(3):\n    t.forward(80)\n    t.right(120)\n\nt.penup(); t.goto(-200, 0); t.pendown()\nt.color(\"red\")\nfor i in range(5):\n    t.forward(100)\n    t.right(144)\n\nturtle.done()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Cuadrado: 4 lados, gira 90°. Triángulo: 3 lados, gira 120°. Estrella: 5 lados, gira 144°.',
            ],
            [
                'orden' => 2, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Controla la tortuga con las flechas',
                'enunciado' => "Crea un programa Turtle donde la tortuga se mueva con las flechas del teclado.\n\n**Requisitos:**\n- Flecha arriba → avanza 20\n- Flecha abajo → retrocede 20\n- Flecha derecha → gira a la derecha 15°\n- Flecha izquierda → gira a la izquierda 15°\n- Tecla `c` → cambia el color al azar\n\n**Pasos clave:**\n```python\npantalla.onkey(funcion, \"Up\")   # registra la tecla\npantalla.listen()               # activa el escucha\nturtle.done()                   # mantiene la ventana\n```",
                'codigo_base' => "import turtle\nimport random\n\npantalla = turtle.Screen()\npantalla.bgcolor(\"black\")\npantalla.title(\"Tortuga teledirigida\")\n\nt = turtle.Turtle()\nt.color(\"lime\")\nt.speed(0)\n\ndef avanzar():\n    t.forward(20)\n\ndef retroceder():\n    t.backward(20)\n\n# Define las demás funciones y registra los eventos\n\npantalla.listen()\nturtle.done()\n",
                'solucion' => "import turtle, random\n\npantalla = turtle.Screen()\npantalla.bgcolor(\"black\")\npantalla.title(\"Tortuga teledirigida\")\n\nt = turtle.Turtle()\nt.color(\"lime\")\nt.speed(0)\n\ndef avanzar():    t.forward(20)\ndef retroceder(): t.backward(20)\ndef girar_der():  t.right(15)\ndef girar_izq():  t.left(15)\ndef cambiar_color():\n    colores = [\"red\", \"blue\", \"yellow\", \"orange\", \"purple\", \"cyan\"]\n    t.color(random.choice(colores))\n\npantalla.onkey(avanzar,      \"Up\")\npantalla.onkey(retroceder,   \"Down\")\npantalla.onkey(girar_der,    \"Right\")\npantalla.onkey(girar_izq,    \"Left\")\npantalla.onkey(cambiar_color, \"c\")\n\npantalla.listen()\nturtle.done()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa pantalla.onkey(nombre_funcion, "Up"). Nota: es el nombre de la función SIN paréntesis.',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Rectángulo que se mueve con Pygame',
                'enunciado' => "Crea una ventana con **Pygame** donde un rectángulo verde se mueva con las flechas del teclado y no pueda salir de la pantalla.\n\n**Requisitos:**\n- Ventana de 800×600, fondo negro\n- Rectángulo verde de 40×40 píxeles\n- Flechas → mueven el rectángulo (velocidad = 5)\n- El rectángulo no puede salir de los bordes de la ventana\n- Al presionar `Escape`, el juego cierra\n\n**Instalar primero:** `pip install pygame`",
                'codigo_base' => "import pygame\n\npygame.init()\nventana = pygame.display.set_mode((800, 600))\npygame.display.set_caption(\"Mi rectángulo\")\nreloj = pygame.time.Clock()\n\nNEGRO = (0, 0, 0)\nVERDE = (163, 230, 53)\n\njugador = pygame.Rect(400, 300, 40, 40)\nvelocidad = 5\n\ncorriendo = True\nwhile corriendo:\n    for evento in pygame.event.get():\n        if evento.type == pygame.QUIT:\n            corriendo = False\n        if evento.type == pygame.KEYDOWN:\n            if evento.key == pygame.K_ESCAPE:\n                corriendo = False\n    \n    # Mover con teclas\n    teclas = pygame.key.get_pressed()\n    # ... (agrega el movimiento aquí)\n    \n    # Limitar dentro de la pantalla\n    # ...\n    \n    # Dibujar\n    ventana.fill(NEGRO)\n    pygame.draw.rect(ventana, VERDE, jugador)\n    pygame.display.flip()\n    reloj.tick(60)\n\npygame.quit()\n",
                'solucion' => "import pygame\n\npygame.init()\nventana = pygame.display.set_mode((800, 600))\npygame.display.set_caption(\"Mi rectángulo\")\nreloj = pygame.time.Clock()\n\nNEGRO = (0, 0, 0)\nVERDE = (163, 230, 53)\n\njugador = pygame.Rect(400, 300, 40, 40)\nvelocidad = 5\n\ncorriendo = True\nwhile corriendo:\n    for evento in pygame.event.get():\n        if evento.type == pygame.QUIT:\n            corriendo = False\n        if evento.type == pygame.KEYDOWN:\n            if evento.key == pygame.K_ESCAPE:\n                corriendo = False\n\n    teclas = pygame.key.get_pressed()\n    if teclas[pygame.K_LEFT]:  jugador.x -= velocidad\n    if teclas[pygame.K_RIGHT]: jugador.x += velocidad\n    if teclas[pygame.K_UP]:    jugador.y -= velocidad\n    if teclas[pygame.K_DOWN]:  jugador.y += velocidad\n\n    jugador.x = max(0, min(jugador.x, 800 - jugador.width))\n    jugador.y = max(0, min(jugador.y, 600 - jugador.height))\n\n    ventana.fill(NEGRO)\n    pygame.draw.rect(ventana, VERDE, jugador)\n    pygame.display.flip()\n    reloj.tick(60)\n\npygame.quit()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Para los límites: jugador.x = max(0, min(jugador.x, 800 - 40)). Esto asegura que x esté entre 0 y 760.',
            ],
            [
                'orden' => 4, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'La bandera de Colombia con Turtle',
                'enunciado' => "Dibuja la **bandera de Colombia** usando Python Turtle.\n\nLa bandera tiene 3 franjas horizontales:\n- Amarillo (ocupa la mitad superior)\n- Azul (una cuarta parte)\n- Rojo (una cuarta parte)\n\n**Dimensiones sugeridas:** 300 × 200 píxeles\n- Franja amarilla: 300 × 100\n- Franja azul: 300 × 50\n- Franja roja: 300 × 50\n\n**Pista:** Usa `t.begin_fill()` y `t.end_fill()` con `t.fillcolor(color)` para rellenar.",
                'solucion' => "import turtle\n\nt = turtle.Turtle()\nt.speed(0)\nt.penup()\n\ndef dibujar_franja(x, y, ancho, alto, color):\n    t.goto(x, y)\n    t.pendown()\n    t.fillcolor(color)\n    t.begin_fill()\n    for _ in range(2):\n        t.forward(ancho)\n        t.right(90)\n        t.forward(alto)\n        t.right(90)\n    t.end_fill()\n    t.penup()\n\ndibujar_franja(-150, 100, 300, 100, \"yellow\")\ndibujar_franja(-150, 0, 300, 50, \"blue\")\ndibujar_franja(-150, -50, 300, 50, \"red\")\n\nturtle.done()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Crea una función dibujar_franja(x, y, ancho, alto, color) que dibuje un rectángulo relleno. Llámala 3 veces.',
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Espiral de colores con Turtle',
                'enunciado' => "Crea una espiral de colores con Python Turtle.\n\n**Especificaciones:**\n- 120 iteraciones\n- El trazo crece en cada vuelta (longitud = i × 3)\n- Cicla por al menos 5 colores\n- Fondo negro, velocidad máxima (speed=0)\n\n**Resultado esperado:** una espiral cuadrada colorida que se expande desde el centro.",
                'solucion' => "import turtle\n\nt = turtle.Turtle()\nt.speed(0)\npantalla = turtle.Screen()\npantalla.bgcolor(\"black\")\n\ncolores = [\"red\", \"orange\", \"yellow\", \"lime\", \"cyan\", \"blue\", \"purple\", \"pink\"]\n\nfor i in range(120):\n    t.color(colores[i % len(colores)])\n    t.forward(i * 3)\n    t.right(91)\n\nturtle.done()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa colores[i % len(colores)] para ciclar los colores. El ángulo ligeramente mayor a 90° (91°) crea la espiral.',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Puntuación en pantalla con Pygame',
                'enunciado' => "Agrega un **sistema de puntuación** al juego del rectángulo móvil.\n\n**Mecánica:** cada segundo que pasa sin salir de pantalla, el score aumenta en 1.\n\n**Agregar al juego anterior:**\n```python\nfuente = pygame.font.Font(None, 36)\nscore = 0\ntiempo = pygame.time.get_ticks()  # tiempo inicial\n\n# En el game loop, cada segundo:\nif pygame.time.get_ticks() - tiempo >= 1000:\n    score += 1\n    tiempo = pygame.time.get_ticks()\n\n# Dibujar el score:\ntexto = fuente.render(f\"Score: {score}\", True, (255, 255, 255))\nventana.blit(texto, (10, 10))\n```",
                'solucion' => "import pygame\n\npygame.init()\nventana = pygame.display.set_mode((800, 600))\npygame.display.set_caption(\"Score Game\")\nreloj = pygame.time.Clock()\nfuente = pygame.font.Font(None, 36)\n\nNEGRO = (0, 0, 0); VERDE = (163, 230, 53); BLANCO = (255, 255, 255)\n\njugador = pygame.Rect(400, 300, 40, 40)\nvelocidad = 5\nscore = 0\ntiempo = pygame.time.get_ticks()\n\ncorriendo = True\nwhile corriendo:\n    for evento in pygame.event.get():\n        if evento.type == pygame.QUIT: corriendo = False\n\n    teclas = pygame.key.get_pressed()\n    if teclas[pygame.K_LEFT]:  jugador.x -= velocidad\n    if teclas[pygame.K_RIGHT]: jugador.x += velocidad\n    if teclas[pygame.K_UP]:    jugador.y -= velocidad\n    if teclas[pygame.K_DOWN]:  jugador.y += velocidad\n\n    jugador.x = max(0, min(jugador.x, 760))\n    jugador.y = max(0, min(jugador.y, 560))\n\n    if pygame.time.get_ticks() - tiempo >= 1000:\n        score += 1\n        tiempo = pygame.time.get_ticks()\n\n    ventana.fill(NEGRO)\n    pygame.draw.rect(ventana, VERDE, jugador)\n    texto = fuente.render(f\"Score: {score}\", True, BLANCO)\n    ventana.blit(texto, (10, 10))\n    pygame.display.flip()\n    reloj.tick(60)\n\npygame.quit()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa pygame.time.get_ticks() para obtener el tiempo en milisegundos. Cada 1000ms = 1 segundo.',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Obstáculos que caen desde arriba',
                'enunciado' => "Agrega **3 obstáculos** que caen desde arriba al juego. Si el jugador los toca, el juego termina con un mensaje de \"GAME OVER\".\n\n**Lógica de los obstáculos:**\n```python\nimport random\n\nobstaculos = [\n    pygame.Rect(random.randint(0, 760), random.randint(-500, -30), 30, 30)\n    for _ in range(3)\n]\n\n# En el game loop:\nfor obs in obstaculos:\n    obs.y += 4  # caen\n    if obs.y > 600:\n        obs.y = random.randint(-300, -30)\n        obs.x = random.randint(0, 760)\n    pygame.draw.rect(ventana, ROJO, obs)\n    if jugador.colliderect(obs):\n        corriendo = False  # game over\n```",
                'solucion' => "import pygame, random\n\npygame.init()\nventana = pygame.display.set_mode((800, 600))\nreloj = pygame.time.Clock()\nfuente = pygame.font.Font(None, 36)\n\nNEGRO = (0,0,0); VERDE = (163,230,53); ROJO = (248,81,73); BLANCO = (255,255,255)\n\njugador = pygame.Rect(400, 500, 40, 40)\nobstaculos = [pygame.Rect(random.randint(0, 760), random.randint(-500, -30), 30, 30) for _ in range(3)]\nscore = 0; tiempo = pygame.time.get_ticks()\n\ncorriendo = True\nwhile corriendo:\n    for e in pygame.event.get():\n        if e.type == pygame.QUIT: corriendo = False\n\n    t = pygame.key.get_pressed()\n    if t[pygame.K_LEFT]:  jugador.x = max(0, jugador.x - 5)\n    if t[pygame.K_RIGHT]: jugador.x = min(760, jugador.x + 5)\n\n    if pygame.time.get_ticks() - tiempo >= 1000:\n        score += 1; tiempo = pygame.time.get_ticks()\n\n    ventana.fill(NEGRO)\n    pygame.draw.rect(ventana, VERDE, jugador)\n\n    for obs in obstaculos:\n        obs.y += 4\n        if obs.y > 600:\n            obs.y = random.randint(-300, -30)\n            obs.x = random.randint(0, 760)\n        pygame.draw.rect(ventana, ROJO, obs)\n        if jugador.colliderect(obs): corriendo = False\n\n    ventana.blit(fuente.render(f\"Score: {score}\", True, BLANCO), (10, 10))\n    pygame.display.flip(); reloj.tick(60)\n\nventana.fill(NEGRO)\nmsg = fuente.render(f\"GAME OVER — Score: {score}\", True, ROJO)\nventana.blit(msg, (200, 270))\npygame.display.flip()\npygame.time.wait(3000)\npygame.quit()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Crea la lista de obstáculos antes del game loop. Dentro del loop, mueve cada uno con obs.y += 4 y verifica colisión.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Juego completo con Pygame',
                'enunciado' => "## Proyecto del Módulo 9 — Tu primer videojuego\n\nCrea un juego completo de **esquiva obstáculos** con Pygame.\n\n### Funcionalidades obligatorias:\n\n1. **Pantalla de inicio** con el nombre del juego y \"ESPACIO para jugar\"\n2. **Gameplay:**\n   - Jugador se mueve izquierda/derecha con flechas\n   - Obstáculos caen desde arriba (mínimo 5)\n   - La velocidad aumenta con el tiempo\n   - Score visible en pantalla\n3. **Game Over:**\n   - Al colisionar, muestra pantalla de GAME OVER con el score\n   - Opción de reiniciar (R) o salir (Escape)\n\n### Bonus (opcional):\n- Diferentes colores o formas de obstáculos\n- Powerup que da puntos extra\n- Efectos de sonido con `pygame.mixer`",
                'solucion' => "import pygame, random\n\npygame.init()\nV = pygame.display.set_mode((800, 600))\npygame.display.set_caption(\"ESQUIVA\")\nR = pygame.time.Clock()\nF_GRANDE = pygame.font.Font(None, 72)\nF_MED = pygame.font.Font(None, 36)\n\nN=(0,0,0); VE=(163,230,53); RO=(248,81,73); BL=(255,255,255); AM=(250,204,21)\n\ndef jugar():\n    j = pygame.Rect(380, 530, 40, 40)\n    obs = [pygame.Rect(random.randint(0,760), random.randint(-600,-30), 30, 40) for _ in range(5)]\n    score = 0; vel = 4; t0 = pygame.time.get_ticks(); t_vel = pygame.time.get_ticks()\n    while True:\n        for e in pygame.event.get():\n            if e.type == pygame.QUIT: return score\n        k = pygame.key.get_pressed()\n        if k[pygame.K_LEFT]:  j.x = max(0, j.x-6)\n        if k[pygame.K_RIGHT]: j.x = min(760, j.x+6)\n        if pygame.time.get_ticks()-t0 >= 1000: score+=1; t0=pygame.time.get_ticks()\n        if pygame.time.get_ticks()-t_vel >= 5000: vel=min(vel+0.5,12); t_vel=pygame.time.get_ticks()\n        V.fill(N); pygame.draw.rect(V,VE,j)\n        for o in obs:\n            o.y+=vel\n            if o.y>600: o.y=random.randint(-300,-30); o.x=random.randint(0,760)\n            pygame.draw.rect(V,RO,o)\n            if j.colliderect(o): return score\n        V.blit(F_MED.render(f\"Score: {score}\",True,BL),(10,10))\n        pygame.display.flip(); R.tick(60)\n\nwhile True:\n    V.fill(N)\n    V.blit(F_GRANDE.render(\"ESQUIVA\",True,VE),(270,200))\n    V.blit(F_MED.render(\"ESPACIO para jugar\",True,BL),(280,300))\n    pygame.display.flip()\n    espera = True\n    while espera:\n        for e in pygame.event.get():\n            if e.type==pygame.QUIT: pygame.quit(); exit()\n            if e.type==pygame.KEYDOWN and e.key==pygame.K_SPACE: espera=False\n    score_final = jugar()\n    V.fill(N)\n    V.blit(F_GRANDE.render(\"GAME OVER\",True,RO),(230,200))\n    V.blit(F_MED.render(f\"Score: {score_final}\",True,AM),(330,300))\n    V.blit(F_MED.render(\"R=reiniciar  ESC=salir\",True,BL),(240,370))\n    pygame.display.flip()\n    esperando = True\n    while esperando:\n        for e in pygame.event.get():\n            if e.type==pygame.QUIT: pygame.quit(); exit()\n            if e.type==pygame.KEYDOWN:\n                if e.key==pygame.K_r: esperando=False\n                if e.key==pygame.K_ESCAPE: pygame.quit(); exit()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Divide el juego en funciones: pantalla_inicio(), jugar(), pantalla_game_over(). Así es más fácil reiniciar.',
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
