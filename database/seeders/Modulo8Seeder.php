<?php

namespace Database\Seeders;

use App\Models\Ejercicio;
use App\Models\EjercicioOpcion;
use App\Models\Leccion;
use App\Models\Modulo;
use Illuminate\Database\Seeder;

class Modulo8Seeder extends Seeder
{
    public function run(): void
    {
        $modulo = Modulo::where('slug', 'python-y-el-internet')->firstOrFail();

        $lecciones = [
            [
                'orden' => 1, 'tipo' => 'teoria',
                'titulo' => '¿Qué es una API? La analogía del restaurante',
                'contenido' => <<<'MD'
# ¿Qué es una API? 🍽️

Imagina que estás en un restaurante. Tú (el cliente) no puedes entrar a la cocina a preparar la comida directamente. En cambio:

1. Le dices al **mesero** lo que quieres
2. El mesero va a la **cocina** y pide tu pedido
3. La cocina lo prepara y lo entrega al mesero
4. El mesero te trae **el plato**

En el mundo del software:
- **Tú** = tu programa Python
- **Mesero** = la API
- **Cocina** = el servidor del servicio
- **El plato** = la respuesta (datos)

## API = Application Programming Interface

Una **API** es un conjunto de reglas que define cómo dos programas se comunican entre sí.

## APIs que conoces (sin saberlo)

- Cuando tu app del clima muestra la temperatura → está consultando la API del clima
- Cuando inicias sesión con Google en otra app → OAuth es una API
- Cuando Spotify carga tus recomendaciones → consulta la API de Spotify

## APIs gratuitas y divertidas para practicar

```
PokéAPI      → https://pokeapi.co            Datos de Pokémon
Open-Meteo   → https://open-meteo.com        Clima en tiempo real
Numbers API  → http://numbersapi.com          Datos curiosos de números
Chuck Norris → https://api.chucknorris.io    Chistes random
```

**¡Ninguna de estas necesita registro ni API key!** 🎉
MD,
            ],
            [
                'orden' => 2, 'tipo' => 'teoria',
                'titulo' => 'El módulo requests: pip install requests',
                'contenido' => <<<'MD'
# El módulo `requests` 📦

Para hacer peticiones a APIs en Python usas el módulo `requests`. A diferencia de todo lo que has visto hasta ahora, este módulo **no viene instalado por defecto** — debes instalarlo.

## Instalación (solo una vez)

Abre tu terminal y escribe:

```bash
pip install requests
```

Verás algo como:
```
Successfully installed requests-2.31.0
```

¡Listo! Ya puedes usarlo.

## Verificar que funciona

```python
import requests

# Si no da error, está instalado correctamente
print("¡requests instalado correctamente! 🎉")
print(requests.__version__)
```

## El ciclo de una petición HTTP

```
Tu programa                API Server
     │                         │
     │──── GET /pokemon/pikachu ──▶│
     │                         │
     │◀── Respuesta JSON ────────│
     │                         │
```

## Códigos de estado HTTP

| Código | Significado |
|--------|-------------|
| `200` | ✅ OK — todo bien |
| `404` | ❌ No encontrado |
| `403` | 🚫 Acceso denegado |
| `500` | 💥 Error del servidor |

```python
response = requests.get("https://pokeapi.co/api/v2/pokemon/pikachu")
print(response.status_code)  # 200 si todo bien
```
MD,
            ],
            [
                'orden' => 3, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'requests.get() y la respuesta',
                'contenido' => <<<'MD'
# `requests.get()` — haciendo tu primera petición 🌐

## La función básica

```python
import requests

url = "https://pokeapi.co/api/v2/pokemon/pikachu"
response = requests.get(url)

print(response.status_code)  # 200
print(type(response))        # <class 'requests.models.Response'>
```

## Verificar el éxito antes de usar la respuesta

```python
import requests

response = requests.get("https://pokeapi.co/api/v2/pokemon/pikachu")

if response.status_code == 200:
    print("✅ Conexión exitosa")
else:
    print(f"❌ Error: {response.status_code}")
```

## Con timeout — buena práctica

```python
import requests

try:
    response = requests.get(
        "https://pokeapi.co/api/v2/pokemon/pikachu",
        timeout=10  # espera máximo 10 segundos
    )
    print(f"Estado: {response.status_code}")
except requests.exceptions.ConnectionError:
    print("❌ Sin conexión a internet")
except requests.exceptions.Timeout:
    print("❌ La solicitud tardó demasiado")
```

## Ver el contenido en texto

```python
import requests

response = requests.get("http://numbersapi.com/11")
print(response.text)
# "11 is the number of players on a soccer team."
```

## Pasar parámetros en la URL

```python
import requests

# Manualmente:
url = "https://open-meteo.com/v1/forecast?latitude=6.2442&longitude=-75.5812&current_weather=true"

# O con params:
params = {
    "latitude": 6.2442,
    "longitude": -75.5812,
    "current_weather": True
}
response = requests.get("https://api.open-meteo.com/v1/forecast", params=params)
```
MD,
            ],
            [
                'orden' => 4, 'tipo' => 'teoria',
                'titulo' => 'Parseando JSON — response.json()',
                'contenido' => <<<'MD'
# Parseando JSON — `response.json()` 🔍

La mayoría de APIs responden en formato **JSON**. Ya aprendiste JSON en el módulo anterior. Ahora lo usas con datos reales.

## El método `.json()`

```python
import requests

response = requests.get("https://pokeapi.co/api/v2/pokemon/pikachu")
datos = response.json()

print(type(datos))   # <class 'dict'>
print(datos.keys())  # dict_keys(['id', 'name', 'height', 'weight', 'types', ...])
```

## Navegar la respuesta JSON

```python
import requests

url = "https://pokeapi.co/api/v2/pokemon/pikachu"
datos = requests.get(url).json()

print(f"Nombre: {datos['name']}")
print(f"Altura: {datos['height'] * 10} cm")
print(f"Peso: {datos['weight'] / 10} kg")

# Los tipos son una lista de diccionarios
tipos = [t['type']['name'] for t in datos['types']]
print(f"Tipos: {', '.join(tipos)}")
```

Resultado:
```
Nombre: pikachu
Altura: 40 cm
Peso: 6.0 kg
Tipos: electric
```

## Ejemplo con Chuck Norris API

```python
import requests

response = requests.get("https://api.chucknorris.io/jokes/random")
datos = response.json()

print("😄 Chiste del día:")
print(datos["value"])
```

## Guardar la respuesta en JSON

```python
import json, requests

datos = requests.get("https://pokeapi.co/api/v2/pokemon/pikachu").json()

with open("pikachu.json", "w") as f:
    json.dump(datos, f, indent=2)

print("💾 Datos guardados en pikachu.json")
```
MD,
            ],
            [
                'orden' => 5, 'tipo' => 'teoria',
                'titulo' => 'Manejo de errores de red',
                'contenido' => <<<'MD'
# Manejo de errores de red 🛡️

Las peticiones a internet pueden fallar por muchas razones: sin conexión, URL incorrecta, servidor caído... Siempre debes manejar estos errores.

## Los errores más comunes de `requests`

```python
import requests

# Sin conexión a internet
try:
    r = requests.get("https://pokeapi.co/api/v2/pokemon/pikachu", timeout=5)
except requests.exceptions.ConnectionError:
    print("❌ Sin conexión. Verifica tu WiFi.")

# URL que no existe (respuesta 404)
r = requests.get("https://pokeapi.co/api/v2/pokemon/pokemoninexistente999")
if r.status_code == 404:
    print("❌ Pokémon no encontrado")

# Timeout
try:
    r = requests.get("https://pokeapi.co/api/v2/pokemon/pikachu", timeout=0.001)
except requests.exceptions.Timeout:
    print("❌ La solicitud tardó demasiado")
```

## Patrón recomendado

```python
import requests

def consultar_pokemon(nombre):
    url = f"https://pokeapi.co/api/v2/pokemon/{nombre.lower()}"

    try:
        response = requests.get(url, timeout=10)
    except requests.exceptions.ConnectionError:
        return None, "Sin conexión a internet"
    except requests.exceptions.Timeout:
        return None, "La solicitud tardó demasiado"

    if response.status_code == 404:
        return None, f"Pokémon '{nombre}' no encontrado"

    if response.status_code != 200:
        return None, f"Error del servidor: {response.status_code}"

    return response.json(), None

datos, error = consultar_pokemon("pikachu")
if error:
    print(f"❌ {error}")
else:
    print(f"✅ {datos['name']}")
```

## `raise_for_status()` — atajo útil

```python
r = requests.get("https://pokeapi.co/api/v2/pokemon/pikachu")
r.raise_for_status()  # lanza excepción si el código no es 2xx
datos = r.json()
```
MD,
            ],
            [
                'orden' => 6, 'tipo' => 'ejemplo_codigo',
                'titulo' => 'Proyecto: App del clima con Open-Meteo',
                'contenido' => <<<'MD'
# Proyecto: App del clima de Medellín 🌤️

[Open-Meteo](https://open-meteo.com) es una API gratuita de clima. Sin registro. Sin límites para uso personal.

## Coordenadas de ciudades colombianas

```python
CIUDADES = {
    "Medellín":  {"lat": 6.2442,  "lon": -75.5812},
    "Bogotá":    {"lat": 4.7110,  "lon": -74.0721},
    "Cali":      {"lat": 3.4516,  "lon": -76.5320},
    "Barranquilla": {"lat": 10.9685, "lon": -74.7813},
    "Cartagena": {"lat": 10.3910, "lon": -75.4794},
}
```

## La petición

```python
import requests

def consultar_clima(ciudad, lat, lon):
    url = "https://api.open-meteo.com/v1/forecast"
    params = {
        "latitude": lat,
        "longitude": lon,
        "current_weather": True,
        "daily": "temperature_2m_max,temperature_2m_min,precipitation_probability_max",
        "timezone": "America/Bogota",
        "forecast_days": 1
    }

    response = requests.get(url, params=params, timeout=10)
    return response.json()
```

## Interpretar los datos

```python
datos = consultar_clima("Medellín", 6.2442, -75.5812)

clima_actual = datos["current_weather"]
dia = datos["daily"]

temp_actual = clima_actual["temperature"]
temp_max = dia["temperature_2m_max"][0]
temp_min = dia["temperature_2m_min"][0]
lluvia = dia["precipitation_probability_max"][0]

print(f"☁️ CLIMA EN MEDELLÍN")
print(f"🌡️  Temperatura actual: {temp_actual}°C")
print(f"🔺 Máxima del día: {temp_max}°C")
print(f"🔻 Mínima del día: {temp_min}°C")
print(f"🌧️  Probabilidad de lluvia: {lluvia}%")

if lluvia > 60:
    print("☔ Trae paraguas, hay buena probabilidad de lluvia")
elif temp_actual > 28:
    print("☀️ Buen día para la piscina")
else:
    print("🌤️ Día fresco y agradable")
```

## Códigos de tiempo (`weathercode`)

El campo `weathercode` indica el tipo de clima:
- `0` = cielo despejado ☀️
- `1-3` = parcialmente nublado ⛅
- `45, 48` = niebla 🌫️
- `51-67` = llovizna/lluvia 🌧️
- `71-77` = nieve ❄️
- `80-99` = chubascos y tormentas ⛈️
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
                'titulo' => '¿Qué función de requests hace una petición GET?',
                'enunciado' => "¿Qué función del módulo `requests` se usa para hacer una petición **GET** a una URL?",
                'respuesta_correcta' => null,
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'El nombre de la función es el mismo que el tipo de petición HTTP.',
                'opciones' => [
                    ['orden' => 1, 'texto' => 'a) requests.fetch(url)', 'es_correcta' => false],
                    ['orden' => 2, 'texto' => 'b) requests.get(url)', 'es_correcta' => true],
                    ['orden' => 3, 'texto' => 'c) requests.http(url)', 'es_correcta' => false],
                    ['orden' => 4, 'texto' => 'd) requests.call(url)', 'es_correcta' => false],
                ],
            ],
            [
                'orden' => 2, 'tipo' => 'quiz_texto', 'es_obligatorio' => true,
                'titulo' => '¿Qué método convierte la respuesta a diccionario?',
                'enunciado' => "Después de hacer `response = requests.get(url)`, ¿qué **método** convierte la respuesta JSON a un diccionario de Python?\n\nEscribe el método con sus paréntesis.",
                'respuesta_correcta' => '.json()',
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'El nombre del método es el mismo que el formato del dato que quieres obtener.',
            ],
            [
                'orden' => 3, 'tipo' => 'codigo_libre', 'es_obligatorio' => true,
                'titulo' => 'Consultando la PokéAPI',
                'enunciado' => "Crea un programa que:\n1. Pregunte al usuario el **nombre de un Pokémon**\n2. Consulte la PokéAPI: `https://pokeapi.co/api/v2/pokemon/{nombre}`\n3. Muestre: nombre, altura, peso y tipos\n4. Si el Pokémon no existe (status 404), muestre un mensaje de error\n\n**Ejemplo:**\n```\n¿Qué Pokémon buscas? pikachu\nNombre: pikachu\nAltura: 40 cm\nPeso: 6.0 kg\nTipos: electric\n```",
                'codigo_base' => "import requests\n\nnombre = input(\"¿Qué Pokémon buscas? \").lower()\nurl = f\"https://pokeapi.co/api/v2/pokemon/{nombre}\"\n\nresponse = requests.get(url, timeout=10)\n\n# Verifica si existe y muestra los datos\n",
                'solucion' => "import requests\n\nnombre = input(\"¿Qué Pokémon buscas? \").lower()\nurl = f\"https://pokeapi.co/api/v2/pokemon/{nombre}\"\n\ntry:\n    response = requests.get(url, timeout=10)\nexcept requests.exceptions.ConnectionError:\n    print(\"❌ Sin conexión a internet\")\n    exit()\n\nif response.status_code == 404:\n    print(f\"❌ El Pokémon '{nombre}' no existe\")\nelse:\n    datos = response.json()\n    altura = datos['height'] * 10\n    peso = datos['weight'] / 10\n    tipos = [t['type']['name'] for t in datos['types']]\n    print(f\"Nombre: {datos['name']}\")\n    print(f\"Altura: {altura} cm\")\n    print(f\"Peso: {peso} kg\")\n    print(f\"Tipos: {', '.join(tipos)}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'La altura viene en decímetros (×10 para cm). El peso viene en hectogramos (÷10 para kg). Los tipos están en datos["types"] como lista de dicts.',
            ],
            [
                'orden' => 4, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Chiste random de Chuck Norris',
                'enunciado' => "Crea un programa que consulte un chiste random de la API de Chuck Norris y lo muestre.\n\n**URL:** `https://api.chucknorris.io/jokes/random`\n\nEl campo del chiste en la respuesta JSON es `\"value\"`.\n\n**Bonus:** Si quieres chistes en una categoría específica, usa:\n`https://api.chucknorris.io/jokes/random?category=science`",
                'solucion' => "import requests\n\ntry:\n    response = requests.get(\"https://api.chucknorris.io/jokes/random\", timeout=10)\n    chiste = response.json()[\"value\"]\n    print(\"😄 Chiste del día:\")\n    print(chiste)\nexcept requests.exceptions.ConnectionError:\n    print(\"❌ Sin conexión a internet\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'El JSON tiene la clave "value" con el texto del chiste.',
            ],
            [
                'orden' => 5, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Dato curioso de tu edad',
                'enunciado' => "Crea un programa que:\n1. Pregunte al usuario su edad\n2. Consulte la Numbers API para ese número\n3. Muestre el dato curioso\n\n**URL:** `http://numbersapi.com/{numero}` (responde texto plano, no JSON)\n\n**Usa `response.text` en vez de `response.json()`**\n\n**Ejemplo:**\n```\n¿Cuántos años tienes? 11\n11 is the number of players on a soccer team.\n```",
                'solucion' => "import requests\n\nedad = input(\"¿Cuántos años tienes? \")\n\ntry:\n    response = requests.get(f\"http://numbersapi.com/{edad}\", timeout=10)\n    print(f\"\\n🔢 Dato curioso sobre el {edad}:\")\n    print(response.text)\nexcept requests.exceptions.ConnectionError:\n    print(\"❌ Sin conexión a internet\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'La Numbers API responde con texto plano. Usa response.text en vez de response.json().',
            ],
            [
                'orden' => 6, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Comparar clima de 3 ciudades colombianas',
                'enunciado' => "Crea un programa que compare el clima actual de **Medellín, Bogotá y Cali** usando Open-Meteo.\n\n**URL:** `https://api.open-meteo.com/v1/forecast`\n**Parámetros:** `latitude`, `longitude`, `current_weather=true`\n\nMuestra:\n- Temperatura actual de cada ciudad\n- Cuál es la más caliente y cuál la más fría\n\n**Coordenadas:**\n- Medellín: lat=6.2442, lon=-75.5812\n- Bogotá: lat=4.7110, lon=-74.0721\n- Cali: lat=3.4516, lon=-76.5320",
                'solucion' => "import requests\n\nciudades = {\n    \"Medellín\": {\"lat\": 6.2442, \"lon\": -75.5812},\n    \"Bogotá\": {\"lat\": 4.7110, \"lon\": -74.0721},\n    \"Cali\": {\"lat\": 3.4516, \"lon\": -76.5320},\n}\n\ntemperaturas = {}\n\nfor ciudad, coords in ciudades.items():\n    params = {\"latitude\": coords[\"lat\"], \"longitude\": coords[\"lon\"], \"current_weather\": True}\n    r = requests.get(\"https://api.open-meteo.com/v1/forecast\", params=params, timeout=10)\n    temp = r.json()[\"current_weather\"][\"temperature\"]\n    temperaturas[ciudad] = temp\n    print(f\"🌡️  {ciudad}: {temp}°C\")\n\nmas_caliente = max(temperaturas, key=temperaturas.get)\nmas_fria = min(temperaturas, key=temperaturas.get)\nprint(f\"\\n☀️  Más caliente: {mas_caliente} ({temperaturas[mas_caliente]}°C)\")\nprint(f\"❄️  Más fría: {mas_fria} ({temperaturas[mas_fria]}°C)\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'La temperatura actual está en datos["current_weather"]["temperature"]. Para la más caliente: max(dict, key=dict.get).',
            ],
            [
                'orden' => 7, 'tipo' => 'codigo_libre', 'es_obligatorio' => false,
                'titulo' => 'Guardar resultado de API en archivo JSON',
                'enunciado' => "Crea un programa que:\n1. Consulte información de **3 Pokémon** (elige tus favoritos)\n2. Guarde los datos en `mis_pokemones.json`\n3. Al reabrir el programa, muestre los datos guardados sin hacer nuevas peticiones\n\n**Los datos a guardar por cada Pokémon:** nombre, altura, peso, tipos",
                'solucion' => "import requests, json\n\nPOKEMONES = [\"pikachu\", \"charizard\", \"mewtwo\"]\n\ntry:\n    with open(\"mis_pokemones.json\") as f:\n        datos_guardados = json.load(f)\n    print(\"📂 Cargado desde archivo:\")\nexcept FileNotFoundError:\n    datos_guardados = []\n    print(\"🌐 Descargando datos...\")\n    for nombre in POKEMONES:\n        r = requests.get(f\"https://pokeapi.co/api/v2/pokemon/{nombre}\", timeout=10)\n        d = r.json()\n        datos_guardados.append({\n            \"nombre\": d[\"name\"],\n            \"altura_cm\": d[\"height\"] * 10,\n            \"peso_kg\": round(d[\"weight\"] / 10, 1),\n            \"tipos\": [t[\"type\"][\"name\"] for t in d[\"types\"]]\n        })\n    with open(\"mis_pokemones.json\", \"w\") as f:\n        json.dump(datos_guardados, f, indent=2)\n\nfor p in datos_guardados:\n    print(f\"  {p['nombre']}: {p['altura_cm']}cm, {p['peso_kg']}kg, tipos: {p['tipos']}\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Usa try/except FileNotFoundError: si el archivo existe, carga. Si no existe, descarga y guarda.',
            ],
            [
                'orden' => 8, 'tipo' => 'mini_proyecto', 'es_obligatorio' => false,
                'titulo' => '🏆 PROYECTO: App del clima de Medellín',
                'enunciado' => "## Proyecto del Módulo 8 — App del Clima\n\nCrea una app completa del clima usando Open-Meteo.\n\n### Funcionalidades:\n\n1. **Clima actual** de una ciudad (temperatura, viento, código de tiempo)\n2. **Pronóstico de hoy** (máxima, mínima, probabilidad de lluvia)\n3. **Menú** para elegir entre ciudades colombianas\n4. **Consejo** según el clima (llevar paraguas, usar protector solar, etc.)\n\n### URL base:\n`https://api.open-meteo.com/v1/forecast`\n\n### Ciudades disponibles:\n- Medellín: 6.2442, -75.5812\n- Bogotá: 4.7110, -74.0721\n- Cali: 3.4516, -76.5320\n- Barranquilla: 10.9685, -74.7813\n\n### Parámetros recomendados:\n```python\nparams = {\n    \"latitude\": lat,\n    \"longitude\": lon,\n    \"current_weather\": True,\n    \"daily\": \"temperature_2m_max,temperature_2m_min,precipitation_probability_max\",\n    \"timezone\": \"America/Bogota\",\n    \"forecast_days\": 1\n}\n```",
                'solucion' => "import requests\n\nCIUDADES = {\n    \"1\": (\"Medellín\", 6.2442, -75.5812),\n    \"2\": (\"Bogotá\", 4.7110, -74.0721),\n    \"3\": (\"Cali\", 3.4516, -76.5320),\n    \"4\": (\"Barranquilla\", 10.9685, -74.7813),\n}\n\ndef consultar_clima(lat, lon):\n    params = {\n        \"latitude\": lat, \"longitude\": lon,\n        \"current_weather\": True,\n        \"daily\": \"temperature_2m_max,temperature_2m_min,precipitation_probability_max\",\n        \"timezone\": \"America/Bogota\", \"forecast_days\": 1\n    }\n    return requests.get(\"https://api.open-meteo.com/v1/forecast\", params=params, timeout=10).json()\n\nprint(\"=== APP DEL CLIMA ===\")\nfor k, (ciudad, *_) in CIUDADES.items():\n    print(f\"{k}) {ciudad}\")\n\nopcion = input(\"\\nElige ciudad: \")\nif opcion not in CIUDADES:\n    print(\"Opción inválida\"); exit()\n\nnombre, lat, lon = CIUDADES[opcion]\n\ntry:\n    datos = consultar_clima(lat, lon)\nexcept Exception as e:\n    print(f\"❌ Error: {e}\"); exit()\n\nactual = datos[\"current_weather\"]\ndia = datos[\"daily\"]\n\ntemp = actual[\"temperature\"]\ntemp_max = dia[\"temperature_2m_max\"][0]\ntemp_min = dia[\"temperature_2m_min\"][0]\nlluvia = dia[\"precipitation_probability_max\"][0]\n\nprint(f\"\\n🌤️  CLIMA EN {nombre.upper()}\")\nprint(f\"🌡️  Actual: {temp}°C  (Máx: {temp_max}°C / Mín: {temp_min}°C)\")\nprint(f\"🌧️  Probabilidad de lluvia: {lluvia}%\")\n\nif lluvia > 60:\n    print(\"☔ Consejo: lleva paraguas\")\nelif temp > 28:\n    print(\"☀️  Consejo: usa protector solar\")\nelse:\n    print(\"🧥 Consejo: lleva una chaqueta ligera\")\n",
                'recompensa_ejercicio' => 2000, 'recompensa_perfecto' => 3000,
                'pista' => 'Crea una función consultar_clima(lat, lon) y llámala con las coordenadas elegidas. La data diaria es una lista, usa [0] para el primer día.',
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
