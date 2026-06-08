<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo7Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'objetos-y-clases')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => '¿Qué es la programación orientada a objetos?',
                'contenido' => <<<'MD'
# ¿Qué es la programación orientada a objetos? 🏗️

Hasta ahora has programado de forma **procedural**: una serie de instrucciones que se ejecutan una tras otra.

La **Programación Orientada a Objetos (POO)** es una forma de organizar el código usando "objetos" que representan cosas del mundo real.

## La analogía del mundo real

Piensa en un **jugador de fútbol**:

| Característica | En POO |
|----------------|--------|
| Tiene un nombre | **Atributo** |
| Tiene una edad | **Atributo** |
| Tiene un número de goles | **Atributo** |
| Puede anotar un gol | **Método** |
| Puede recibir una tarjeta | **Método** |

En POO:
- Los **atributos** son las características (variables)
- Los **métodos** son las acciones (funciones)

## Clase vs Objeto

```
Clase = el molde / la plantilla
Objeto = la cosa creada con ese molde
```

Ejemplo:
```
Clase Jugador → es el molde genérico
Objeto jugador1 → James Rodríguez, 32 años
Objeto jugador2 → Falcao García, 37 años
```

## ¿Por qué usar POO?

1. **Organiza mejor el código** — agrupa datos y funciones relacionados
2. **Reutilizable** — el molde (clase) sirve para crear muchos objetos
3. **Todo el mundo profesional lo usa** — Django, FastAPI, pandas... todo es POO
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Clases — el molde; objetos — las copias',
                'contenido' => <<<'MD'
# Clases y objetos en Python 🏺

## Crear una clase simple

```python
class Jugador:
    pass  # clase vacía por ahora
```

## Crear objetos (instancias) de la clase

```python
jugador1 = Jugador()  # crear un objeto
jugador2 = Jugador()  # crear otro objeto

print(type(jugador1))  # <class '__main__.Jugador'>
```

## Agregar atributos directamente (forma básica)

```python
jugador1.nombre = "James Rodríguez"
jugador1.equipo = "Rayo Vallecano"
jugador1.goles = 8

jugador2.nombre = "Falcao García"
jugador2.equipo = "Millonarios"
jugador2.goles = 15

print(f"{jugador1.nombre} del {jugador1.equipo}")
print(f"{jugador2.nombre} con {jugador2.goles} goles")
```

## Convenciones de nombres

```python
# Clases: PascalCase (cada palabra con mayúscula)
class JugadorFutbol:
    pass

class CuentaBancaria:
    pass

# Objetos: snake_case (como siempre)
mi_jugador = JugadorFutbol()
cuenta_ahorros = CuentaBancaria()
```

## Agregar métodos simples

```python
class Jugador:
    def presentarse(self):
        print(f"Soy un jugador de fútbol")

james = Jugador()
james.presentarse()  # Soy un jugador de fútbol
```

¿Qué es ese `self`? ¡Lo verás en la siguiente lección!
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'teoria',
                'titulo' => 'El método __init__ y self',
                'contenido' => <<<'MD'
# El método `__init__` y `self` 🔧

## `__init__` — el constructor

`__init__` es un método especial que se llama **automáticamente** cuando creas un objeto. Sirve para dar los valores iniciales.

```python
class Jugador:
    def __init__(self, nombre, equipo, goles):
        self.nombre = nombre
        self.equipo = equipo
        self.goles = goles
```

Ahora para crear un jugador **debes** pasar los datos:

```python
james = Jugador("James Rodríguez", "Rayo Vallecano", 8)
falcao = Jugador("Falcao García", "Millonarios", 15)

print(james.nombre)   # James Rodríguez
print(falcao.goles)   # 15
```

## `self` — "yo mismo"

`self` es una referencia al **objeto actual**. Cuando escribes `self.nombre`, le dices a Python: "el atributo `nombre` de **este** objeto".

```python
class Jugador:
    def __init__(self, nombre, equipo, goles):
        self.nombre = nombre   # self.nombre = el parámetro nombre
        self.equipo = equipo
        self.goles = goles
```

Cuando Python ejecuta `james = Jugador("James", ...)`, `self` es `james`.

## ⚠️ El error más común

```python
class Jugador:
    def __init__(self, nombre):  # ← self siempre primero
        self.nombre = nombre

# ❌ No pasas self al llamar la función:
jugador = Jugador("James")  # Python pasa self automáticamente
# ✅ Solo pasas los parámetros que definiste
```

## Resumen

```python
class MiClase:
    def __init__(self, dato1, dato2):
        self.atributo1 = dato1
        self.atributo2 = dato2
```
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'Atributos — las características del objeto',
                'contenido' => <<<'MD'
# Atributos — las características del objeto 📋

Los **atributos** son las variables que pertenecen a un objeto. Se definen en `__init__` con `self.`.

## Tipos de atributos

### Atributos de instancia (los más comunes)
Cada objeto tiene sus propios valores:

```python
class Mascota:
    def __init__(self, nombre, especie, edad):
        self.nombre = nombre     # ← atributo de instancia
        self.especie = especie
        self.edad = edad

gato = Mascota("Michi", "gato", 3)
perro = Mascota("Rex", "perro", 5)

print(gato.nombre)    # Michi  ← el valor de ESTE objeto
print(perro.nombre)   # Rex    ← el valor de ESTE objeto
```

### Atributos de clase (comparten todos los objetos)

```python
class Jugador:
    deporte = "fútbol"  # ← atributo de clase, todos lo comparten

james = Jugador()
falcao = Jugador()

print(james.deporte)   # fútbol
print(falcao.deporte)  # fútbol
```

## Acceder y modificar atributos

```python
class Jugador:
    def __init__(self, nombre, goles):
        self.nombre = nombre
        self.goles = goles

james = Jugador("James", 8)

# Acceder
print(james.goles)  # 8

# Modificar
james.goles = 10
print(james.goles)  # 10

# Agregar nuevo atributo
james.equipo = "Nacional"
print(james.equipo)  # Nacional
```

## `__str__` — representación en texto

```python
class Jugador:
    def __init__(self, nombre, goles):
        self.nombre = nombre
        self.goles = goles

    def __str__(self):
        return f"{self.nombre} ({self.goles} goles)"

james = Jugador("James", 8)
print(james)  # James (8 goles) ← usa __str__ automáticamente
```
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Métodos — lo que el objeto puede hacer',
                'contenido' => <<<'MD'
# Métodos — lo que el objeto puede hacer ⚡

Los **métodos** son funciones que pertenecen a la clase. Se definen con `def` dentro de la clase y siempre tienen `self` como primer parámetro.

## Métodos básicos

```python
class Jugador:
    def __init__(self, nombre, goles):
        self.nombre = nombre
        self.goles = goles

    def anotar(self):
        self.goles += 1
        print(f"⚽ ¡Gol de {self.nombre}! Total: {self.goles}")

    def presentarse(self):
        print(f"Soy {self.nombre} y llevo {self.goles} goles esta temporada.")
```

```python
james = Jugador("James", 8)
james.presentarse()  # Soy James y llevo 8 goles esta temporada.
james.anotar()       # ⚽ ¡Gol de James! Total: 9
james.anotar()       # ⚽ ¡Gol de James! Total: 10
james.presentarse()  # Soy James y llevo 10 goles esta temporada.
```

## Métodos con parámetros

```python
class CuentaBancaria:
    def __init__(self, propietario, saldo=0):
        self.propietario = propietario
        self.saldo = saldo

    def depositar(self, monto):
        self.saldo += monto
        print(f"Depositado ${monto:,}. Nuevo saldo: ${self.saldo:,}")

    def retirar(self, monto):
        if monto > self.saldo:
            print(f"❌ Sin fondos. Saldo actual: ${self.saldo:,}")
        else:
            self.saldo -= monto
            print(f"Retiro exitoso. Saldo restante: ${self.saldo:,}")
```

```python
cuenta = CuentaBancaria("Santiago")
cuenta.depositar(50000)   # Depositado $50,000. Nuevo saldo: $50,000
cuenta.retirar(20000)     # Retiro exitoso. Saldo restante: $30,000
cuenta.retirar(100000)    # ❌ Sin fondos. Saldo actual: $30,000
```
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'teoria',
                'titulo' => 'Herencia — una clase que hereda de otra',
                'contenido' => <<<'MD'
# Herencia — una clase que hereda de otra 🧬

La **herencia** permite crear una clase nueva basada en una existente. La nueva clase "hereda" todos los atributos y métodos de la original.

## Clase padre (base) y clase hija

```python
# Clase padre
class Animal:
    def __init__(self, nombre, edad):
        self.nombre = nombre
        self.edad = edad

    def comer(self):
        print(f"{self.nombre} está comiendo 🍖")

    def dormir(self):
        print(f"{self.nombre} está durmiendo 💤")
```

```python
# Clases hijas — heredan de Animal
class Perro(Animal):
    def ladrar(self):
        print(f"{self.nombre}: ¡Guau guau! 🐕")

class Gato(Animal):
    def maullar(self):
        print(f"{self.nombre}: ¡Miau! 🐱")
```

```python
rex = Perro("Rex", 3)
michi = Gato("Michi", 2)

# Heredaron los métodos del padre
rex.comer()    # Rex está comiendo
michi.dormir() # Michi está durmiendo

# Y tienen sus propios métodos
rex.ladrar()   # Rex: ¡Guau guau!
michi.maullar() # Michi: ¡Miau!
```

## Sobreescribir métodos (override)

```python
class Animal:
    def sonido(self):
        print("Algún sonido genérico")

class Perro(Animal):
    def sonido(self):  # ← sobreescribe el del padre
        print("¡Guau guau!")

class Gato(Animal):
    def sonido(self):
        print("¡Miau!")

animales = [Perro(), Gato(), Perro()]
for animal in animales:
    animal.sonido()
# ¡Guau guau!
# ¡Miau!
# ¡Guau guau!
```

## `super()` — llamar al padre

```python
class Perro(Animal):
    def __init__(self, nombre, edad, raza):
        super().__init__(nombre, edad)  # llama al __init__ del padre
        self.raza = raza                # agrega atributo propio
```
MD,
            ],

            // ── PILAR 1 ──────────────────────────────────────────────
            [
                'orden' => 7, 'tipo' => 'teoria',
                'titulo' => 'Pilar 1: Encapsulamiento — protege tus datos 🔒',
                'contenido' => <<<'MD'
# Pilar 1: Encapsulamiento 🔒

**Encapsulamiento** significa controlar quién puede ver y modificar los datos de un objeto.

## La analogía del cajero automático

Un cajero tiene dinero adentro, pero no puedes meterle la mano directamente. Solo puedes interactuar a través de los botones que te ofrecen.

En código: algunos datos son tan importantes que no deberían cambiar directamente desde afuera.

## 3 niveles de acceso en Python

```python
class Jugador:
    def __init__(self, nombre, salario):
        self.nombre = nombre       # ✅ PÚBLICO — cualquiera lo ve y cambia
        self._club = "Sin club"    # ⚠️ PROTEGIDO — convención: "uso interno"
        self.__salario = salario   # 🔒 PRIVADO — Python lo oculta
```

```python
falcao = Jugador("Falcao", 5_000_000)

print(falcao.nombre)     # ✅ Funciona: Falcao
print(falcao._club)      # ⚠️ Funciona pero no deberías: Sin club
print(falcao.__salario)  # ❌ AttributeError — está oculto
```

> 💡 Python no bloquea `_atributo` técnicamente, pero es una señal de "oye, esto es de uso interno, no lo toques".

## @property — acceso controlado

`@property` te permite leer un atributo privado de forma segura:

```python
class Jugador:
    def __init__(self, nombre, salario):
        self.nombre = nombre
        self.__salario = salario

    @property
    def salario(self):             # getter: permite LEER
        return self.__salario

    @salario.setter
    def salario(self, nuevo):      # setter: permite ESCRIBIR con validación
        if nuevo < 0:
            print("❌ El salario no puede ser negativo")
        else:
            self.__salario = nuevo
            print(f"✅ Nuevo salario: {nuevo:,} COP")
```

```python
falcao = Jugador("Falcao", 5_000_000)

print(falcao.salario)     # 5000000 ← usa el getter
falcao.salario = 6_000_000  # ← usa el setter con validación
falcao.salario = -500     # ❌ El salario no puede ser negativo
```

## ¿Por qué encapsular?

```python
class Partido:
    def __init__(self):
        self.__goles = 0     # sin encapsulamiento: alguien podría hacer partido.goles = -5

    def anotar(self):
        self.__goles += 1    # la única forma válida de sumar goles

    @property
    def goles(self):
        return self.__goles
```

**Sin encapsulamiento:**
```python
partido.goles = -999  # ← ¡catástrofe! nadie te detiene
```

**Con encapsulamiento:**
```python
partido.__goles = -999  # ← AttributeError, Python lo bloquea
partido.anotar()        # ← única forma válida
```
MD,
            ],

            // ── PILAR 2 ──────────────────────────────────────────────
            [
                'orden' => 8, 'tipo' => 'teoria',
                'titulo' => 'Pilar 2: Abstracción — botón fácil para algo complejo 🎮',
                'contenido' => <<<'MD'
# Pilar 2: Abstracción 🎮

**Abstracción** significa mostrar solo lo necesario y ocultar cómo funciona adentro.

## La analogía del videojuego

En PES o FIFA, cuando presionas el botón de patear, el juego calcula:
- La velocidad de la bola
- El ángulo de tiro
- El efecto (curva, tiro raso, etc.)
- Si hay portero en la trayectoria
- ...miles de cálculos

¿Tú ves todo eso? No. Solo ves un botón: **PATEAR**. Eso es abstracción.

## Interfaz pública vs. implementación privada

```python
class PartidoFutbol:
    def __init__(self, local, visitante):
        self.local = local
        self.visitante = visitante
        self.__goles_local = 0       # 🔒 privado — detalle interno
        self.__goles_visitante = 0   # 🔒 privado — detalle interno
        self.__en_curso = False      # 🔒 privado — detalle interno

    # ── INTERFAZ PÚBLICA (lo que el usuario usa) ──────────────
    def iniciar(self):
        self.__validar_equipos()     # llama a método privado
        self.__en_curso = True
        print(f"🏁 ¡Inicia el partido: {self.local} vs {self.visitante}!")

    def anotar(self, equipo):        # simple para quien lo usa
        self.__registrar_gol(equipo) # complejo adentro

    def resultado(self):
        return f"{self.local} {self.__goles_local} - {self.__goles_visitante} {self.visitante}"

    # ── IMPLEMENTACIÓN PRIVADA (los detalles) ─────────────────
    def __validar_equipos(self):
        if self.local == self.visitante:
            raise ValueError("Un equipo no puede jugar contra sí mismo")

    def __registrar_gol(self, equipo):
        if not self.__en_curso:
            print("❌ El partido no ha iniciado")
            return
        if equipo == 'local':
            self.__goles_local += 1
            print(f"⚽ ¡Gol del {self.local}!")
        elif equipo == 'visitante':
            self.__goles_visitante += 1
            print(f"⚽ ¡Gol del {self.visitante}!")
```

```python
# ✅ Usar la clase es SIMPLE — abstracción en acción:
partido = PartidoFutbol("Nacional", "DIM")
partido.iniciar()
partido.anotar("local")
partido.anotar("local")
partido.anotar("visitante")
print(partido.resultado())   # Nacional 2 - 1 DIM
```

Quien usa la clase no sabe nada de `__goles_local`, `__en_curso` ni `__registrar_gol`. Solo ve 3 botones: `iniciar()`, `anotar()`, `resultado()`.

## Abstracción vs Encapsulamiento — ¿cuál es la diferencia?

| Encapsulamiento | Abstracción |
|-----------------|-------------|
| **Proteger** los datos | **Simplificar** la interfaz |
| `__atributo` privado | Métodos `__privados` complejos |
| "No toques esto" | "No necesitas saber cómo funciona" |

> 🎯 En la práctica van juntos: usas encapsulamiento (`__`) para lograr abstracción (interfaz simple).
MD,
            ],

            // ── PILAR 3 ──────────────────────────────────────────────
            [
                'orden' => 9, 'tipo' => 'teoria',
                'titulo' => 'Pilar 3: Herencia profunda — abuelos, padres, hijos 🧬',
                'contenido' => <<<'MD'
# Pilar 3: Herencia profunda 🧬

Ya conoces la herencia básica. Ahora vamos más hondo: cadenas de 3+ niveles, `super()` bien usado, y cómo saber si un objeto "es" de cierta clase.

## Herencia en 3 niveles — la familia del fútbol

```python
class Persona:                          # nivel 1 — el abuelo
    def __init__(self, nombre, edad):
        self.nombre = nombre
        self.edad = edad

    def presentarse(self):
        print(f"Soy {self.nombre}, {self.edad} años")


class MiembroEquipo(Persona):           # nivel 2 — el padre
    def __init__(self, nombre, edad, equipo):
        super().__init__(nombre, edad)  # ← llama a Persona.__init__
        self.equipo = equipo

    def presentarse(self):
        super().presentarse()           # ← llama a Persona.presentarse()
        print(f"  Club: {self.equipo}")


class Jugador(MiembroEquipo):           # nivel 3 — el hijo
    def __init__(self, nombre, edad, equipo, posicion):
        super().__init__(nombre, edad, equipo)  # ← llama a MiembroEquipo.__init__
        self.posicion = posicion
        self.goles = 0

    def presentarse(self):
        super().presentarse()           # ← llama a MiembroEquipo.presentarse()
        print(f"  Posición: {self.posicion} | Goles: {self.goles}")

    def anotar(self):
        self.goles += 1
        print(f"⚽ ¡Gol de {self.nombre}!")
```

```python
falcao = Jugador("Falcao", 37, "Millonarios", "Delantero")
falcao.presentarse()
# Soy Falcao, 37 años
#   Club: Millonarios
#   Posición: Delantero | Goles: 0
```

Cuando llamas `presentarse()`, Python sigue la cadena: **Jugador → MiembroEquipo → Persona**. Cada nivel agrega su información.

## isinstance() y issubclass()

```python
falcao = Jugador("Falcao", 37, "Millonarios", "Delantero")

isinstance(falcao, Jugador)          # True  — es Jugador
isinstance(falcao, MiembroEquipo)    # True  — también es MiembroEquipo
isinstance(falcao, Persona)          # True  — también es Persona
isinstance(falcao, str)              # False — no es un string

issubclass(Jugador, MiembroEquipo)   # True  — Jugador hereda de MiembroEquipo
issubclass(Jugador, Persona)         # True  — transitivamente
issubclass(MiembroEquipo, Jugador)   # False — es al revés
```

> 💡 `isinstance()` es muy útil para validar parámetros: "¿el objeto que me pasaron es del tipo correcto?"

## Extender vs. sobrescribir

```python
class Entrenador(MiembroEquipo):
    def __init__(self, nombre, edad, equipo, titulos):
        super().__init__(nombre, edad, equipo)
        self.titulos = titulos

    def presentarse(self):
        super().presentarse()           # ← reutiliza el del padre
        print(f"  Títulos: {self.titulos}")  # ← y agrega más

    def dar_indicaciones(self, jugador):  # método propio, no heredado
        print(f"{self.nombre} le dice a {jugador}: ¡Más presión!")
```

## El árbol completo

```
Persona
├── MiembroEquipo
│   ├── Jugador
│   │   ├── Portero     (puede agregar: paradas, vallas_invictas)
│   │   └── Delantero   (puede agregar: asistencias)
│   └── Entrenador
│       └── AsistenteTecnico
```
MD,
            ],

            // ── PILAR 4 ──────────────────────────────────────────────
            [
                'orden' => 10, 'tipo' => 'teoria',
                'titulo' => 'Pilar 4: Polimorfismo — mismo nombre, diferente forma 🎭',
                'contenido' => <<<'MD'
# Pilar 4: Polimorfismo 🎭

**Polimorfismo** viene del griego: *poly* = muchas, *morphe* = formas.

El mismo método, llamado con el mismo nombre, se comporta diferente según la clase del objeto.

## El ejemplo del fútbol

```python
class Jugador:
    def __init__(self, nombre):
        self.nombre = nombre

    def habilidad_especial(self):
        print(f"{self.nombre} hace algo genérico")


class Portero(Jugador):
    def habilidad_especial(self):
        print(f"{self.nombre} hace una atajada imposible 🧤")


class Delantero(Jugador):
    def habilidad_especial(self):
        print(f"{self.nombre} mete un golazo de chilena ⚽")


class Mediocampista(Jugador):
    def habilidad_especial(self):
        print(f"{self.nombre} da un pase filtrado perfecto 🎯")
```

```python
# Lista de objetos de DIFERENTES clases
once_ideal = [
    Portero("Ospina"),
    Mediocampista("James"),
    Mediocampista("Cuadrado"),
    Delantero("Falcao"),
    Delantero("Bacca"),
]

# El MISMO código funciona para todos
for jugador in once_ideal:
    jugador.habilidad_especial()   # ← polimorfismo en acción
```

Resultado:
```
Ospina hace una atajada imposible 🧤
James da un pase filtrado perfecto 🎯
Cuadrado da un pase filtrado perfecto 🎯
Falcao mete un golazo de chilena ⚽
Bacca mete un golazo de chilena ⚽
```

El `for` no sabe si cada objeto es Portero, Delantero o Mediocampista. Solo llama `habilidad_especial()` y cada objeto hace lo suyo. Eso es polimorfismo.

## Duck typing — "si camina como pato..."

Python no exige que los objetos hereden de la misma clase. Solo necesitan tener el método:

```python
class Pato:
    def sonido(self):
        print("Cuac cuac")

class Persona:
    def sonido(self):
        print("Hola mundo")

class Motor:
    def sonido(self):
        print("Vroooom")

# Ninguno hereda del otro — pero todos tienen sonido()
cosas = [Pato(), Persona(), Motor()]
for cosa in cosas:
    cosa.sonido()   # Python no pregunta "¿eres un pato?" — solo llama el método
```

> 🦆 "Si camina como pato y grazna como pato, es un pato." Python acepta cualquier objeto que tenga lo que necesitas.

## Métodos mágicos — polimorfismo con operadores

Python usa métodos especiales (`__nombre__`) para que tus objetos funcionen con operadores como `+`, `==`, `<`, `print()`:

```python
class Equipo:
    def __init__(self, nombre, puntos):
        self.nombre = nombre
        self.puntos = puntos

    def __str__(self):          # print(equipo)
        return f"{self.nombre} — {self.puntos} pts"

    def __eq__(self, otro):     # equipo1 == equipo2
        return self.puntos == otro.puntos

    def __lt__(self, otro):     # equipo1 < equipo2 (para sorted())
        return self.puntos < otro.puntos
```

```python
tabla = [
    Equipo("Nacional",   34),
    Equipo("DIM",        28),
    Equipo("América",    31),
]

for e in sorted(tabla, reverse=True):   # ordena usando __lt__
    print(e)                             # imprime usando __str__

# Nacional — 34 pts
# América — 31 pts
# DIM — 28 pts
```

## Los 4 pilares — resumen visual

```
ENCAPSULAMIENTO  → protege los datos (__atributo, @property)
ABSTRACCIÓN      → oculta la complejidad (interfaz simple)
HERENCIA         → reutiliza y extiende (class Hijo(Padre))
POLIMORFISMO     → mismo nombre, distintos comportamientos
```

Los 4 trabajan juntos en cualquier proyecto real. 🏆
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
                'titulo' => '¿Qué es self en Python?',
                'enunciado' => "¿Qué representa `self` en los métodos de una clase?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Piensa en "self" como "yo mismo". Cada objeto necesita saber cuáles son sus propias variables.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) El nombre de la clase', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Una referencia al objeto actual (instancia)', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) El método constructor', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) Un parámetro opcional para imprimir', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué método se llama automáticamente al crear un objeto?',
                'enunciado' => "¿Cómo se llama el método especial que Python ejecuta **automáticamente** cuando creas un nuevo objeto?\n\nEscribe el nombre completo incluyendo los guiones bajos.",
                'respuesta_correcta' => '__init__',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Tiene doble guion bajo al inicio y al final. Viene de "initialize" (inicializar).',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Clase Mascota',
                'enunciado' => "Crea una clase `Mascota` con:\n\n**Atributos** (en `__init__`):\n- `nombre`\n- `especie` (gato, perro, etc.)\n- `edad`\n\n**Métodos:**\n- `hablar()` → imprime algo apropiado según la especie (miau/guau/etc.)\n- `cumpleanos()` → aumenta la edad en 1 e imprime el mensaje\n- `presentarse()` → imprime toda su info\n\n**Prueba:**\n```python\ngato = Mascota(\"Michi\", \"gato\", 2)\nperro = Mascota(\"Rex\", \"perro\", 4)\ngato.presentarse()\nperro.hablar()\ngato.cumpleanos()\n```",
                'codigo_base' => "class Mascota:\n    def __init__(self, nombre, especie, edad):\n        pass  # guarda los atributos\n    \n    def hablar(self):\n        pass\n    \n    def cumpleanos(self):\n        pass\n    \n    def presentarse(self):\n        pass\n\n# Crea dos mascotas diferentes y prueba todos los métodos\n",
                'solucion' => "class Mascota:\n    def __init__(self, nombre, especie, edad):\n        self.nombre = nombre\n        self.especie = especie\n        self.edad = edad\n\n    def hablar(self):\n        sonidos = {\"gato\": \"¡Miau!\", \"perro\": \"¡Guau!\", \"pájaro\": \"¡Pío!\"}\n        sonido = sonidos.get(self.especie, \"...\")\n        print(f\"{self.nombre}: {sonido}\")\n\n    def cumpleanos(self):\n        self.edad += 1\n        print(f\"🎂 ¡Feliz cumpleaños {self.nombre}! Ahora tiene {self.edad} años.\")\n\n    def presentarse(self):\n        print(f\"Hola, soy {self.nombre}, un {self.especie} de {self.edad} años.\")\n\ngato = Mascota(\"Michi\", \"gato\", 2)\nperro = Mascota(\"Rex\", \"perro\", 4)\ngato.presentarse()\nperro.hablar()\ngato.cumpleanos()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'En hablar(), puedes usar un if/elif para los diferentes sonidos, o un diccionario de sonidos por especie.',
            ],
            [
                'orden' => 4, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Clase Rectangulo',
                'enunciado' => "Crea una clase `Rectangulo` con:\n- Atributos: `base` y `altura`\n- Métodos:\n  - `area()` → retorna base × altura\n  - `perimetro()` → retorna 2 × (base + altura)\n  - `es_cuadrado()` → retorna True si base == altura\n\n**Prueba:**\n```python\nr1 = Rectangulo(5, 3)\nprint(r1.area())          # 15\nprint(r1.perimetro())     # 16\nprint(r1.es_cuadrado())   # False\n\ncuadrado = Rectangulo(4, 4)\nprint(cuadrado.es_cuadrado())  # True\n```",
                'solucion' => "class Rectangulo:\n    def __init__(self, base, altura):\n        self.base = base\n        self.altura = altura\n\n    def area(self):\n        return self.base * self.altura\n\n    def perimetro(self):\n        return 2 * (self.base + self.altura)\n\n    def es_cuadrado(self):\n        return self.base == self.altura\n\nr1 = Rectangulo(5, 3)\nprint(r1.area())\nprint(r1.perimetro())\nprint(r1.es_cuadrado())\n\ncuadrado = Rectangulo(4, 4)\nprint(cuadrado.es_cuadrado())\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Recuerda usar self.base y self.altura dentro de los métodos para acceder a los atributos del objeto.',
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Clase CuentaBancaria',
                'enunciado' => "Crea una clase `CuentaBancaria` con:\n- Atributos: `propietario` y `saldo` (por defecto 0)\n- Métodos:\n  - `depositar(monto)` → aumenta el saldo\n  - `retirar(monto)` → disminuye si hay fondos, sino muestra error\n  - `ver_saldo()` → imprime el saldo actual\n\n**Prueba:**\n```python\ncuenta = CuentaBancaria(\"Santiago\")\ncuenta.depositar(50000)\ncuenta.ver_saldo()         # Saldo: $50,000\ncuenta.retirar(20000)\ncuenta.ver_saldo()         # Saldo: $30,000\ncuenta.retirar(100000)     # ❌ Sin fondos\n```",
                'solucion' => "class CuentaBancaria:\n    def __init__(self, propietario, saldo=0):\n        self.propietario = propietario\n        self.saldo = saldo\n\n    def depositar(self, monto):\n        self.saldo += monto\n        print(f\"Depositado {monto:,} COP\")\n\n    def retirar(self, monto):\n        if monto > self.saldo:\n            print(f\"Sin fondos suficientes. Saldo: {self.saldo:,}\")\n        else:\n            self.saldo -= monto\n            print(f\"Retiro de {monto:,} exitoso\")\n\n    def ver_saldo(self):\n        print(f\"Saldo de {self.propietario}: {self.saldo:,} COP\")\n\ncuenta = CuentaBancaria(\"Santiago\")\ncuenta.depositar(50000)\ncuenta.ver_saldo()\ncuenta.retirar(20000)\ncuenta.ver_saldo()\ncuenta.retirar(100000)\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'En retirar(), verifica primero si monto > self.saldo para evitar saldo negativo.',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Herencia: Animal, Perro y Gato',
                'enunciado' => "Crea una jerarquía de clases:\n\n1. Clase `Animal` (padre) con:\n   - `__init__(nombre, edad)`\n   - `comer()` → imprime que está comiendo\n   - `sonido()` → imprime \"...\" (genérico)\n\n2. Clase `Perro(Animal)` con:\n   - `sonido()` → sobreescribe: imprime \"¡Guau!\"\n   - `buscar(objeto)` → imprime que está buscando el objeto\n\n3. Clase `Gato(Animal)` con:\n   - `sonido()` → sobreescribe: imprime \"¡Miau!\"\n   - `ronronear()` → imprime que está ronroneando\n\n**Prueba con una lista de animales:**\n```python\nanimales = [Perro(\"Rex\", 3), Gato(\"Michi\", 2), Perro(\"Bobby\", 5)]\nfor a in animales:\n    a.sonido()\n    a.comer()\n```",
                'solucion' => "class Animal:\n    def __init__(self, nombre, edad):\n        self.nombre = nombre\n        self.edad = edad\n\n    def comer(self):\n        print(f\"{self.nombre} está comiendo 🍖\")\n\n    def sonido(self):\n        print(f\"{self.nombre}: ...\")\n\nclass Perro(Animal):\n    def sonido(self):\n        print(f\"{self.nombre}: ¡Guau guau! 🐕\")\n\n    def buscar(self, objeto):\n        print(f\"{self.nombre} está buscando {objeto}\")\n\nclass Gato(Animal):\n    def sonido(self):\n        print(f\"{self.nombre}: ¡Miau! 🐱\")\n\n    def ronronear(self):\n        print(f\"{self.nombre}: Prrrr... 😸\")\n\nanimales = [Perro(\"Rex\", 3), Gato(\"Michi\", 2), Perro(\"Bobby\", 5)]\nfor a in animales:\n    a.sonido()\n    a.comer()\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Para la herencia: class Perro(Animal). Los métodos en la clase hija sobreescriben los del padre automáticamente.',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Lista de objetos — 5 mascotas',
                'enunciado' => "Crea una clase `Mascota` con `nombre`, `especie` y `edad`.\n\nLuego crea una **lista con 5 mascotas** diferentes.\n\nFinalmente:\n1. Imprime todas las mascotas (nombre y especie)\n2. Encuentra y muestra la **mascota más vieja**\n3. Cuenta cuántos **perros** hay",
                'solucion' => "class Mascota:\n    def __init__(self, nombre, especie, edad):\n        self.nombre = nombre\n        self.especie = especie\n        self.edad = edad\n\nmascotas = [\n    Mascota(\"Rex\", \"perro\", 5),\n    Mascota(\"Michi\", \"gato\", 3),\n    Mascota(\"Lola\", \"perro\", 7),\n    Mascota(\"Nemo\", \"pez\", 1),\n    Mascota(\"Piolín\", \"pájaro\", 4)\n]\n\nprint(\"Mis mascotas:\")\nfor m in mascotas:\n    print(f\"  {m.nombre} ({m.especie})\")\n\nmas_vieja = max(mascotas, key=lambda m: m.edad)\nprint(f\"\\nMás vieja: {mas_vieja.nombre} con {mas_vieja.edad} años\")\n\nperros = [m for m in mascotas if m.especie == \"perro\"]\nprint(f\"Perros: {len(perros)}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Para la más vieja: max(mascotas, key=lambda m: m.edad). Para contar perros: usa un for con if o list comprehension.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: Equipo de Fútbol',
                'enunciado' => "## Proyecto del Módulo 7 — Equipo de Fútbol\n\nCrea un sistema de gestión de equipo usando clases.\n\n### Clase `Jugador`:\n- Atributos: `nombre`, `posicion`, `numero_camiseta`, `goles=0`, `tarjetas=0`\n- Métodos:\n  - `anotar()` → goles += 1\n  - `tarjeta_amarilla()` → tarjetas += 1\n  - `stats()` → imprime todas sus estadísticas\n\n### Clase `Equipo`:\n- Atributos: `nombre`, `ciudad`, `jugadores=[]`\n- Métodos:\n  - `agregar_jugador(jugador)` → agrega a la lista\n  - `goleador()` → retorna el jugador con más goles\n  - `mostrar_plantilla()` → muestra todos los jugadores\n  - `total_goles()` → suma de goles de todos\n\n### Prueba con al menos 4 jugadores y simula un partido.",
                'solucion' => "class Jugador:\n    def __init__(self, nombre, posicion, numero):\n        self.nombre = nombre\n        self.posicion = posicion\n        self.numero = numero\n        self.goles = 0\n        self.tarjetas = 0\n\n    def anotar(self):\n        self.goles += 1\n        print(f\"⚽ ¡Gol de {self.nombre}!\")\n\n    def tarjeta_amarilla(self):\n        self.tarjetas += 1\n        print(f\"🟡 Tarjeta amarilla para {self.nombre}\")\n\n    def stats(self):\n        print(f\"#{self.numero} {self.nombre} ({self.posicion}): {self.goles} goles, {self.tarjetas} tarjetas\")\n\nclass Equipo:\n    def __init__(self, nombre, ciudad):\n        self.nombre = nombre\n        self.ciudad = ciudad\n        self.jugadores = []\n\n    def agregar_jugador(self, jugador):\n        self.jugadores.append(jugador)\n\n    def goleador(self):\n        return max(self.jugadores, key=lambda j: j.goles)\n\n    def mostrar_plantilla(self):\n        print(f\"\\n=== {self.nombre} — {self.ciudad} ===\")\n        for j in self.jugadores:\n            j.stats()\n\n    def total_goles(self):\n        return sum(j.goles for j in self.jugadores)\n\nnacional = Equipo(\"Nacional\", \"Medellín\")\nnacional.agregar_jugador(Jugador(\"Ospina\", \"Portero\", 1))\nnacional.agregar_jugador(Jugador(\"Andrade\", \"Defensa\", 3))\nnacional.agregar_jugador(Jugador(\"Morelo\", \"Delantero\", 9))\nnacional.agregar_jugador(Jugador(\"Gio\", \"Mediocampo\", 10))\n\nnacional.jugadores[2].anotar()\nnacional.jugadores[2].anotar()\nnacional.jugadores[3].anotar()\nnacional.mostrar_plantilla()\nprint(f\"\\nGoleador: {nacional.goleador().nombre}\")\nprint(f\"Total goles: {nacional.total_goles()}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Empieza con la clase Jugador completa y pruébala. Luego crea Equipo. Para goleador: max(self.jugadores, key=lambda j: j.goles).',
            ],

            // ══════════════════════════════════════════════════════════
            // PILAR 1 — ENCAPSULAMIENTO
            // ══════════════════════════════════════════════════════════
            [
                'orden' => 9, 'tipo' => 'quiz_opcion', 'es_obligatorio' => true,
                'titulo' => 'Quiz Encapsulamiento — niveles de acceso',
                'enunciado' => "Tienes esta clase:\n\n```python\nclass Jugador:\n    def __init__(self):\n        self.nombre = \"Falcao\"     # A\n        self._club = \"Nacional\"    # B\n        self.__salario = 5000000   # C\n```\n\n¿Cuál atributo **no** puede leerse directamente con `jugador.__salario` desde fuera de la clase?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'El doble guion bajo __ hace que Python "oculte" el atributo con name mangling. Intenta accederlo y verás un AttributeError.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Solo el A (self.nombre) está protegido', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) El B (_club) no puede leerse desde fuera', 'es_correcta' => false],
                    ['orden' => 3, 'texto' => 'c) El C (__salario) — el doble guion bajo lo oculta y da AttributeError al acceder directamente', 'es_correcta' => true],
                    ['orden' => 4, 'texto' => 'd) Ninguno, todos son accesibles directamente en Python', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 10, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Proyecto Encapsulamiento — Jugador con salario protegido',
                'enunciado' => "Crea una clase `Jugador` que aplique encapsulamiento en su salario:\n\n**Requisitos:**\n1. `nombre` → público\n2. `__salario` → privado (no accesible desde afuera)\n3. `@property salario` → permite **leerlo**\n4. `@salario.setter` → permite **modificarlo** solo si el nuevo valor es > 0\n5. Método `info()` → imprime nombre y salario formateado\n\n**Prueba que incluya:**\n```python\nfalcao = Jugador(\"Falcao\", 5_000_000)\nprint(falcao.salario)       # 5000000 ✅\nfalcao.salario = 6_000_000  # ✅ actualiza\nfalcao.salario = -100       # ❌ mensaje de error, NO actualiza\nfalcao.info()\n# No debe funcionar: falcao.__salario\n```",
                'codigo_base' => "class Jugador:\n    def __init__(self, nombre, salario):\n        self.nombre = nombre\n        self.__salario = salario   # privado\n\n    @property\n    def salario(self):\n        pass  # retorna __salario\n\n    @salario.setter\n    def salario(self, nuevo):\n        pass  # valida y asigna\n\n    def info(self):\n        pass  # imprime nombre y salario\n\n\nfalcao = Jugador('Falcao', 5_000_000)\nprint(falcao.salario)\nfalcao.salario = 6_000_000\nfalcao.salario = -100\nfalcao.info()\n",
                'solucion' => "class Jugador:\n    def __init__(self, nombre, salario):\n        self.nombre = nombre\n        self.__salario = salario\n\n    @property\n    def salario(self):\n        return self.__salario\n\n    @salario.setter\n    def salario(self, nuevo):\n        if nuevo <= 0:\n            print(f'Error: el salario debe ser positivo. Se mantiene {self.__salario:,} COP')\n        else:\n            self.__salario = nuevo\n            print(f'Salario actualizado: {self.__salario:,} COP')\n\n    def info(self):\n        print(f'{self.nombre} — salario: {self.__salario:,} COP')\n\n\nfalcao = Jugador('Falcao', 5_000_000)\nprint(falcao.salario)\nfalcao.salario = 6_000_000\nfalcao.salario = -100\nfalcao.info()\n# Esto daría AttributeError:\n# print(falcao.__salario)\n",
                'recompensa_ejercicio' => 3000, 'recompensa_perfecto' => 5000,
                'pista' => 'El @property va arriba y no lleva parámetro extra. El @salario.setter lleva (self, nuevo) y ahí validas. Dentro de la clase puedes seguir usando self.__salario normalmente.',
            ],

            // ══════════════════════════════════════════════════════════
            // PILAR 2 — ABSTRACCIÓN
            // ══════════════════════════════════════════════════════════
            [
                'orden' => 11, 'tipo' => 'quiz_opcion', 'es_obligatorio' => true,
                'titulo' => 'Quiz Abstracción — ¿qué describe la abstracción?',
                'enunciado' => "¿Cuál de estas afirmaciones describe mejor el principio de **abstracción** en POO?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Piensa en el control remoto del TV o el botón de patear en PES. Tú solo ves los botones, no los circuitos internos.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Bloquear atributos con __ para que nadie los toque', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Copiar métodos de una clase padre a una clase hija', 'es_correcta' => false],
                    ['orden' => 3, 'texto' => 'c) Exponer una interfaz sencilla y ocultar cómo funciona adentro', 'es_correcta' => true],
                    ['orden' => 4, 'texto' => 'd) Hacer que varios objetos tengan el mismo método con diferente comportamiento', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 12, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Proyecto Abstracción — Cajero de la Liga',
                'enunciado' => "Crea una clase `CajeroLiga` (el cajero virtual donde los jugadores cobran).\n\n**Interfaz pública** (lo que el usuario puede usar):\n- `__init__(pin_correcto, saldo_inicial)`\n- `insertar_pin(pin)` → verifica el pin\n- `consultar()` → muestra el saldo si el pin fue verificado\n- `retirar(monto)` → retira si hay fondos y pin correcto\n- `salir()` → cierra la sesión\n\n**Implementación privada** (oculta con __):\n- `__autenticado` → bool, True solo si el pin fue correcto\n- `__saldo` → el dinero real\n- `__verificar_fondos(monto)` → método privado que valida\n\n**El usuario NO debe poder** acceder directamente a `__saldo` ni a `__autenticado`.",
                'codigo_base' => "class CajeroLiga:\n    def __init__(self, pin_correcto, saldo_inicial):\n        self.__pin = pin_correcto\n        self.__saldo = saldo_inicial\n        self.__autenticado = False\n\n    def insertar_pin(self, pin):\n        pass\n\n    def consultar(self):\n        pass\n\n    def retirar(self, monto):\n        pass\n\n    def salir(self):\n        pass\n\n    def __verificar_fondos(self, monto):\n        pass\n\n\n# Prueba\ncajero = CajeroLiga('1234', 2_000_000)\ncajero.consultar()          # debe pedir autenticarse\ncajero.insertar_pin('0000') # pin incorrecto\ncajero.insertar_pin('1234') # pin correcto\ncajero.consultar()          # muestra saldo\ncajero.retirar(500_000)\ncajero.salir()\ncajero.consultar()          # debe pedir pin de nuevo\n",
                'solucion' => "class CajeroLiga:\n    def __init__(self, pin_correcto, saldo_inicial):\n        self.__pin = pin_correcto\n        self.__saldo = saldo_inicial\n        self.__autenticado = False\n\n    def insertar_pin(self, pin):\n        if pin == self.__pin:\n            self.__autenticado = True\n            print('Pin correcto. Bienvenido.')\n        else:\n            print('Pin incorrecto. Intenta de nuevo.')\n\n    def consultar(self):\n        if not self.__autenticado:\n            print('Debes insertar tu pin primero.')\n            return\n        print(f'Saldo disponible: {self.__saldo:,} COP')\n\n    def retirar(self, monto):\n        if not self.__autenticado:\n            print('Debes insertar tu pin primero.')\n            return\n        if self.__verificar_fondos(monto):\n            self.__saldo -= monto\n            print(f'Retiro exitoso: {monto:,} COP. Saldo: {self.__saldo:,} COP')\n\n    def salir(self):\n        self.__autenticado = False\n        print('Sesion cerrada. Hasta luego.')\n\n    def __verificar_fondos(self, monto):\n        if monto <= 0:\n            print('El monto debe ser mayor a 0.')\n            return False\n        if monto > self.__saldo:\n            print(f'Fondos insuficientes. Saldo: {self.__saldo:,} COP')\n            return False\n        return True\n\n\ncajero = CajeroLiga('1234', 2_000_000)\ncajero.consultar()\ncajero.insertar_pin('0000')\ncajero.insertar_pin('1234')\ncajero.consultar()\ncajero.retirar(500_000)\ncajero.salir()\ncajero.consultar()\n",
                'recompensa_ejercicio' => 3000, 'recompensa_perfecto' => 5000,
                'pista' => 'Los métodos privados se llaman con self.__nombre() desde dentro de la clase. Verifica siempre self.__autenticado antes de hacer cualquier operación.',
            ],

            // ══════════════════════════════════════════════════════════
            // PILAR 3 — HERENCIA PROFUNDA
            // ══════════════════════════════════════════════════════════
            [
                'orden' => 13, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Proyecto Herencia profunda — familia del fútbol',
                'enunciado' => "Crea esta jerarquía de herencia de **3 niveles** para la Liga BetPlay:\n\n```\nPersona (nivel 1)\n└── MiembroEquipo (nivel 2)\n    ├── Jugador (nivel 3)\n    │   ├── Portero\n    │   └── Delantero\n    └── Entrenador (nivel 3)\n```\n\n**Cada clase debe:**\n1. Llamar a `super().__init__()` con los parámetros del padre\n2. Tener `presentarse()` que llama a `super().presentarse()` y agrega su propia info\n3. Portero agrega: `paradas=0` y método `atajar()`\n4. Delantero agrega: `goles=0` y método `anotar()`\n5. Entrenador agrega: `titulos` y método `dar_indicacion(jugador)`\n\n**Prueba con isinstance():**\n```python\nospina = Portero('David Ospina', 35, 'Nacional', 0)\nprint(isinstance(ospina, Jugador))       # True\nprint(isinstance(ospina, MiembroEquipo)) # True\nprint(isinstance(ospina, Persona))       # True\n```",
                'codigo_base' => "class Persona:\n    def __init__(self, nombre, edad):\n        self.nombre = nombre\n        self.edad = edad\n\n    def presentarse(self):\n        print(f'Soy {self.nombre}, {self.edad} anios')\n\n\nclass MiembroEquipo(Persona):\n    def __init__(self, nombre, edad, equipo):\n        super().__init__(nombre, edad)\n        self.equipo = equipo\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Club: {self.equipo}')\n\n\nclass Jugador(MiembroEquipo):\n    pass  # agrega posicion y goles=0\n\n\nclass Portero(Jugador):\n    pass  # agrega paradas=0 y atajar()\n\n\nclass Delantero(Jugador):\n    pass  # agrega goles=0 (ya tiene Jugador) y anotar()\n\n\nclass Entrenador(MiembroEquipo):\n    pass  # agrega titulos y dar_indicacion(jugador)\n\n\n# Prueba\nospina = Portero('Ospina', 35, 'Nacional', 0)\nospina.presentarse()\nospina.atajar()\nprint(isinstance(ospina, Persona))\n",
                'solucion' => "class Persona:\n    def __init__(self, nombre, edad):\n        self.nombre = nombre\n        self.edad = edad\n\n    def presentarse(self):\n        print(f'Soy {self.nombre}, {self.edad} anios')\n\n\nclass MiembroEquipo(Persona):\n    def __init__(self, nombre, edad, equipo):\n        super().__init__(nombre, edad)\n        self.equipo = equipo\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Club: {self.equipo}')\n\n\nclass Jugador(MiembroEquipo):\n    def __init__(self, nombre, edad, equipo, posicion):\n        super().__init__(nombre, edad, equipo)\n        self.posicion = posicion\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Posicion: {self.posicion}')\n\n\nclass Portero(Jugador):\n    def __init__(self, nombre, edad, equipo, paradas):\n        super().__init__(nombre, edad, equipo, 'Portero')\n        self.paradas = paradas\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Paradas: {self.paradas}')\n\n    def atajar(self):\n        self.paradas += 1\n        print(f'{self.nombre} ataja el disparo! Total paradas: {self.paradas}')\n\n\nclass Delantero(Jugador):\n    def __init__(self, nombre, edad, equipo):\n        super().__init__(nombre, edad, equipo, 'Delantero')\n        self.goles = 0\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Goles: {self.goles}')\n\n    def anotar(self):\n        self.goles += 1\n        print(f'Gol de {self.nombre}! Total: {self.goles}')\n\n\nclass Entrenador(MiembroEquipo):\n    def __init__(self, nombre, edad, equipo, titulos):\n        super().__init__(nombre, edad, equipo)\n        self.titulos = titulos\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Titulos: {self.titulos}')\n\n    def dar_indicacion(self, jugador):\n        print(f'{self.nombre} le dice a {jugador.nombre}: mas presion!')\n\n\nospina = Portero('David Ospina', 35, 'Nacional', 0)\nfalcao = Delantero('Falcao', 37, 'Millonarios')\nrueda = Entrenador('Reinaldo Rueda', 62, 'Colombia', 0)\n\nospina.presentarse()\nfalcao.anotar()\nrueda.dar_indicacion(falcao)\n\nprint(isinstance(ospina, Jugador))\nprint(isinstance(ospina, MiembroEquipo))\nprint(isinstance(ospina, Persona))\n",
                'recompensa_ejercicio' => 3000, 'recompensa_perfecto' => 5000,
                'pista' => 'Cada __init__ llama a super().__init__() con los parámetros que el padre necesita. Cada presentarse() llama super().presentarse() primero y luego agrega su propia línea.',
            ],

            // ══════════════════════════════════════════════════════════
            // PILAR 4 — POLIMORFISMO
            // ══════════════════════════════════════════════════════════
            [
                'orden' => 14, 'tipo' => 'quiz_opcion', 'es_obligatorio' => true,
                'titulo' => 'Quiz Polimorfismo — duck typing',
                'enunciado' => "Lee este código:\n\n```python\nclass Perro:\n    def sonido(self): print('Guau')\n\nclass Gato:\n    def sonido(self): print('Miau')\n\nclass Motor:\n    def sonido(self): print('Vroooom')\n\nfor cosa in [Perro(), Gato(), Motor()]:\n    cosa.sonido()\n```\n\n¿Por qué funciona este código aunque `Perro`, `Gato` y `Motor` no heredan de la misma clase?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2500, 'recompensa_perfecto' => 4000,
                'pista' => 'Python no verifica el "tipo" del objeto, solo si tiene el método que necesitas.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) Porque Python convierte automáticamente todos los objetos al mismo tipo', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) Duck typing: Python acepta cualquier objeto que tenga el método sonido(), sin importar la clase', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) Porque todas las clases heredan de object en Python', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) Es un error — solo funcionaría si todas heredaran de una clase Sonido', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 15, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Proyecto Polimorfismo — once ideal con habilidades únicas',
                'enunciado' => "Crea una jerarquía de posiciones de fútbol con polimorfismo:\n\n1. Clase base `Jugador(nombre, equipo)` con método `habilidad_especial()` genérico\n2. Clase `Portero(Jugador)` — sobreescribe `habilidad_especial()`: atajada espectacular\n3. Clase `Defensa(Jugador)` — sobreescribe: barrida perfecta\n4. Clase `Mediocampista(Jugador)` — sobreescribe: pase filtrado de 40 metros\n5. Clase `Delantero(Jugador)` — sobreescribe: golazo de chilena\n\n**También implementa `__str__`** en la clase base que retorne `'NombreJugador (Equipo)'`.\n\n**Prueba con un 'once ideal':**\n```python\nonce = [\n    Portero('Ospina', 'Nacional'),\n    Defensa('Mina', 'Everton'),\n    Mediocampista('James', 'Rayo'),\n    Mediocampista('Cuadrado', 'Napoli'),\n    Delantero('Falcao', 'Millonarios'),\n]\nfor jugador in once:\n    print(jugador)            # usa __str__\n    jugador.habilidad_especial()\n```",
                'codigo_base' => "class Jugador:\n    def __init__(self, nombre, equipo):\n        self.nombre = nombre\n        self.equipo = equipo\n\n    def habilidad_especial(self):\n        print(f'{self.nombre} hace algo increible')\n\n    def __str__(self):\n        return f'{self.nombre} ({self.equipo})'\n\n\nclass Portero(Jugador):\n    def habilidad_especial(self):\n        pass  # atajada espectacular\n\n\nclass Defensa(Jugador):\n    def habilidad_especial(self):\n        pass  # barrida perfecta\n\n\nclass Mediocampista(Jugador):\n    def habilidad_especial(self):\n        pass  # pase filtrado\n\n\nclass Delantero(Jugador):\n    def habilidad_especial(self):\n        pass  # golazo de chilena\n\n\nonce = [\n    Portero('Ospina', 'Nacional'),\n    Defensa('Mina', 'Everton'),\n    Mediocampista('James', 'Rayo'),\n    Mediocampista('Cuadrado', 'Napoli'),\n    Delantero('Falcao', 'Millonarios'),\n]\nfor jugador in once:\n    print(jugador)\n    jugador.habilidad_especial()\n",
                'solucion' => "class Jugador:\n    def __init__(self, nombre, equipo):\n        self.nombre = nombre\n        self.equipo = equipo\n\n    def habilidad_especial(self):\n        print(f'{self.nombre} hace algo increible')\n\n    def __str__(self):\n        return f'{self.nombre} ({self.equipo})'\n\n\nclass Portero(Jugador):\n    def habilidad_especial(self):\n        print(f'{self.nombre} hace una atajada IMPOSIBLE bajo los tres palos! No hay gol!')\n\n\nclass Defensa(Jugador):\n    def habilidad_especial(self):\n        print(f'{self.nombre} hace una barrida perfecta en el ultimo momento!')\n\n\nclass Mediocampista(Jugador):\n    def habilidad_especial(self):\n        print(f'{self.nombre} da un pase filtrado de 40 metros con el exterior!')\n\n\nclass Delantero(Jugador):\n    def habilidad_especial(self):\n        print(f'{self.nombre} anota un GOLAZO de chilena desde fuera del area!')\n\n\nonce = [\n    Portero('David Ospina', 'Nacional'),\n    Defensa('Yerry Mina', 'Everton'),\n    Mediocampista('James Rodriguez', 'Rayo Vallecano'),\n    Mediocampista('Juan Cuadrado', 'Napoli'),\n    Delantero('Radamel Falcao', 'Millonarios'),\n]\n\nprint('=== ONCE IDEAL COLOMBIA ===')\nfor jugador in once:\n    print(f'\\n{jugador}')\n    jugador.habilidad_especial()\n",
                'recompensa_ejercicio' => 3000, 'recompensa_perfecto' => 5000,
                'pista' => 'Para sobreescribir (override) un método, simplemente defínelo en la clase hija con el mismo nombre. No necesitas super() si vas a reemplazar el comportamiento completamente.',
            ],

            // ══════════════════════════════════════════════════════════
            // PROYECTO INTEGRADOR — LOS 4 PILARES
            // ══════════════════════════════════════════════════════════
            [
                'orden' => 16, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO FINAL POO — Liga BetPlay con los 4 pilares',
                'enunciado' => "## Proyecto Final — Los 4 pilares de la POO en acción\n\nCrea un sistema de gestión de Liga BetPlay que aplique **los 4 pilares** explícitamente.\n\n### Los 4 pilares que debes aplicar:\n\n**PILAR 1 — Encapsulamiento** en clase `Jugador`:\n- `__salario` privado con `@property` y `@setter` (validación > 0)\n- `__goles` privado — solo modificable con `anotar()`\n\n**PILAR 2 — Abstracción** en clase `PartidoLiga`:\n- Internamente maneja `__goles_local`, `__goles_visitante`, `__activo`\n- Solo expone: `iniciar()`, `anotar(equipo)`, `resultado()`\n- Oculta la lógica de validación con métodos `__privados`\n\n**PILAR 3 — Herencia** con jerarquía de 3 niveles:\n- `Persona → MiembroEquipo → Jugador / Entrenador`\n- `Jugador → Portero / Delantero`\n- `super().__init__()` en cada nivel\n- `presentarse()` acumulativo\n\n**PILAR 4 — Polimorfismo**:\n- `presentarse()` diferente en cada clase\n- `habilidad_especial()` diferente en Portero vs Delantero\n- `__str__` que permite hacer `print(jugador)` directamente\n\n### Flujo del programa:\n```\nLiga BetPlay crea 2 equipos\nCada equipo tiene jugadores (Porteros + Delanteros) y un Entrenador\nSe simula un partido con anotar() \nSe imprime el resultado y las estadísticas de los jugadores\n```",
                'codigo_base' => "# === LOS 4 PILARES DE LA POO ===\n# Implementa cada clase con el pilar indicado\n\n# PILAR 1: ENCAPSULAMIENTO\nclass Persona:\n    def __init__(self, nombre, edad):\n        self.nombre = nombre\n        self.edad = edad\n\n    def presentarse(self):\n        print(f'Soy {self.nombre}')\n\n    def __str__(self):\n        return self.nombre\n\n\nclass MiembroEquipo(Persona):  # PILAR 3: HERENCIA nivel 2\n    def __init__(self, nombre, edad, equipo):\n        super().__init__(nombre, edad)\n        self.equipo = equipo\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Club: {self.equipo}')\n\n\nclass Jugador(MiembroEquipo):  # PILAR 3: HERENCIA nivel 3\n    def __init__(self, nombre, edad, equipo, posicion, salario):\n        super().__init__(nombre, edad, equipo)\n        self.posicion = posicion\n        self.__salario = salario   # PILAR 1\n        self.__goles = 0           # PILAR 1\n\n    @property\n    def goles(self):  # getter\n        return self.__goles\n\n    def anotar(self):\n        self.__goles += 1\n        print(f'Gol de {self.nombre}!')\n\n    def habilidad_especial(self):  # PILAR 4: POLIMORFISMO base\n        print(f'{self.nombre} hace algo')\n\n    def presentarse(self):         # PILAR 4: POLIMORFISMO\n        super().presentarse()\n        print(f'  Posicion: {self.posicion} | Goles: {self.__goles}')\n\n\n# Implementa Portero, Delantero y Entrenador...\n# Implementa PartidoLiga con PILAR 2 (Abstraccion)...\n# Crea equipos, jugadores y simula un partido...\n",
                'solucion' => "# === LOS 4 PILARES DE LA POO — Liga BetPlay ===\n\n# PILAR 3: HERENCIA — Persona (nivel 1)\nclass Persona:\n    def __init__(self, nombre, edad):\n        self.nombre = nombre\n        self.edad = edad\n\n    def presentarse(self):\n        print(f'--- {self.nombre} ({self.edad} anios) ---')\n\n    def __str__(self):  # PILAR 4: POLIMORFISMO (__str__)\n        return self.nombre\n\n\n# PILAR 3: HERENCIA — MiembroEquipo (nivel 2)\nclass MiembroEquipo(Persona):\n    def __init__(self, nombre, edad, equipo):\n        super().__init__(nombre, edad)\n        self.equipo = equipo\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Club: {self.equipo}')\n\n\n# PILAR 3: HERENCIA — Jugador (nivel 3)\nclass Jugador(MiembroEquipo):\n    def __init__(self, nombre, edad, equipo, posicion, salario):\n        super().__init__(nombre, edad, equipo)\n        self.posicion = posicion\n        self.__salario = salario   # PILAR 1: ENCAPSULAMIENTO\n        self.__goles = 0           # PILAR 1: ENCAPSULAMIENTO\n\n    @property\n    def goles(self):\n        return self.__goles\n\n    @property\n    def salario(self):\n        return self.__salario\n\n    @salario.setter\n    def salario(self, nuevo):\n        if nuevo > 0:\n            self.__salario = nuevo\n\n    def anotar(self):\n        self.__goles += 1\n        print(f'  Gol de {self.nombre}! ({self.__goles} total)')\n\n    def habilidad_especial(self):  # PILAR 4: POLIMORFISMO\n        print(f'{self.nombre} muestra sus habilidades')\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Posicion: {self.posicion} | Goles: {self.__goles} | Salario: {self.__salario:,} COP')\n\n\n# PILAR 3+4: HERENCIA y POLIMORFISMO — Portero\nclass Portero(Jugador):\n    def __init__(self, nombre, edad, equipo, salario):\n        super().__init__(nombre, edad, equipo, 'Portero', salario)\n        self.__paradas = 0\n\n    def atajar(self):\n        self.__paradas += 1\n        print(f'  {self.nombre} ATAJA! ({self.__paradas} paradas)')\n\n    def habilidad_especial(self):  # PILAR 4: POLIMORFISMO\n        print(f'{self.nombre} sale a los pies y evita el gol seguro!')\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Paradas: {self.__paradas}')\n\n\n# PILAR 3+4: HERENCIA y POLIMORFISMO — Delantero\nclass Delantero(Jugador):\n    def __init__(self, nombre, edad, equipo, salario):\n        super().__init__(nombre, edad, equipo, 'Delantero', salario)\n\n    def habilidad_especial(self):  # PILAR 4: POLIMORFISMO\n        print(f'{self.nombre} convierte de chilena y enloquece el estadio!')\n\n\n# PILAR 3: HERENCIA — Entrenador\nclass Entrenador(MiembroEquipo):\n    def __init__(self, nombre, edad, equipo, titulos):\n        super().__init__(nombre, edad, equipo)\n        self.titulos = titulos\n\n    def presentarse(self):\n        super().presentarse()\n        print(f'  Rol: Entrenador | Titulos: {self.titulos}')\n\n    def arengar(self):\n        print(f'{self.nombre}: Vamos {self.equipo}, hoy ganamos!')\n\n\n# PILAR 2: ABSTRACCION — PartidoLiga\nclass PartidoLiga:\n    def __init__(self, local, visitante):\n        self.local = local\n        self.visitante = visitante\n        self.__goles_local = 0       # oculto\n        self.__goles_visitante = 0   # oculto\n        self.__activo = False        # oculto\n\n    def iniciar(self):               # interfaz simple\n        self.__activo = True\n        print(f'\\nInicia: {self.local} vs {self.visitante}')\n        print('-' * 35)\n\n    def anotar(self, jugador, tipo='local'):  # interfaz simple\n        self.__registrar_gol(jugador, tipo)   # delega a privado\n\n    def resultado(self):             # interfaz simple\n        return f'{self.local} {self.__goles_local} - {self.__goles_visitante} {self.visitante}'\n\n    def __registrar_gol(self, jugador, tipo):  # implementacion privada\n        if not self.__activo:\n            print('El partido no ha iniciado')\n            return\n        jugador.anotar()\n        if tipo == 'local':\n            self.__goles_local += 1\n        else:\n            self.__goles_visitante += 1\n\n\n# === SIMULACION ===\nospina = Portero('David Ospina', 35, 'Nacional', 8_000_000)\nfalcao = Delantero('Falcao Garcia', 37, 'Nacional', 15_000_000)\nrueda = Entrenador('Reinaldo Rueda', 62, 'Nacional', 0)\nvidal = Delantero('Arturo Vidal', 35, 'DIM', 9_000_000)\n\nprint('=== PLANTILLA NACIONAL ===')\nospina.presentarse()\nfalcao.presentarse()\nrueda.arengar()\n\nprint('\\n=== HABILIDADES ESPECIALES (POLIMORFISMO) ===')\nfor j in [ospina, falcao, vidal]:\n    j.habilidad_especial()\n\npartido = PartidoLiga('Nacional', 'DIM')\npartido.iniciar()\npartido.anotar(falcao, 'local')\npartido.anotar(falcao, 'local')\npartido.anotar(vidal, 'visitante')\nospina.atajar()\n\nprint(f'\\nResultado final: {partido.resultado()}')\nprint(f'Goleador: {falcao} con {falcao.goles} goles')\n",
                'recompensa_ejercicio' => 5000, 'recompensa_perfecto' => 8000,
                'pista' => 'Empieza por la jerarquía de herencia (Persona → MiembroEquipo → Jugador → Portero/Delantero). Luego implementa PartidoLiga con abstracción. Los comentarios # PILAR X en el codigo_base te indican dónde aplica cada concepto.',
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
