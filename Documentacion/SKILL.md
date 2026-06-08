# SKILL: PythonJr — Convenciones y decisiones del proyecto

## Stack
- Backend: Laravel 12 + Sanctum + PostgreSQL (Railway)
- Frontend: React 19 + Vite + Tailwind 4 + shadcn/ui + Zustand + Framer Motion
- Deploy: Railway (backend + DB) + Railway static (frontend)

## Convenciones de código

### Laravel
- Modelos en español: Modulo, Leccion, Ejercicio, Billetera, etc.
- Controladores prefijados: Api\ModuloController, Api\Admin\ValidacionController
- Resources para todas las respuestas JSON (no devolver modelos crudos)
- Policies para autorización (no middleware de roles ad-hoc)
- Eventos+Listeners para efectos secundarios de gamificación
- Form Requests para validación (no inline en controladores)
- Timezone: America/Bogota en config/app.php

### React
- Componentes en PascalCase, archivos .jsx
- Stores Zustand en /stores/, un archivo por dominio
- Axios instance en /lib/axios.js con CSRF y base URL
- Rutas con React Router v6, lazy loading por página
- Tailwind: mobile-first, breakpoint principal md: (768px = tablet)

### Nomenclatura DB
- Tablas en snake_case plural español
- FKs: tabla_id (user_id, modulo_id, ejercicio_id)
- Estados siempre como strings: 'bloqueado', 'disponible', 'en_progreso', 'completado'
- Montos siempre en pesos COP enteros (no decimales)
- Timestamps: created_at, updated_at, completado_at, etc.

## Reglas de negocio críticas

1. Un ejercicio solo puede completarse UNA vez (UNIQUE user_id + ejercicio_id)
2. El saldo de la billetera NUNCA baja (solo saldo_pendiente se mueve a saldo_pagado)
3. Las respuestas correctas NUNCA se exponen al frontend del estudiante
4. La racha se reinicia sin penalización monetaria
5. Los bonos de velocidad se calculan desde iniciado_at del ProgresoModulo
6. El desafío del día caduca a medianoche (America/Bogota)
7. Módulos se desbloquean secuencialmente — completar M1 desbloquea M2
8. El padre puede desbloquear manualmente cualquier módulo desde admin

## Seeder order (respeta FK constraints)
1. UserSeeder
2. ModuloSeeder
3. LeccionSeeder
4. EjercicioSeeder + EjercicioOpcionSeeder
5. LogroSeeder
6. DesafioDiaSeeder
7. ProgresoSeeder (estado inicial)
8. BilleteraSeeder + RachaSeeder (registros vacíos para el estudiante)

## Respuesta API estándar
```php
return response()->json([
    'success' => true,
    'data' => $resource,
    'message' => 'Descripción breve',
    'meta' => [
        'recompensa_ganada' => 3000,
        'es_perfecto' => true,
        'nuevo_logro' => null,
        'racha_actual' => 4,
        'billetera_total' => 48000,
    ]
]);
```

## Animaciones de celebración (Framer Motion)
- Ejercicio correcto: confetti + sonido (opcional)
- Módulo completado: pantalla completa de celebración con monto ganado
- Logro desbloqueado: toast animado con badge del logro
- Racha nueva: llama animada en el contador
- Perfecto: estrella dorada + "¡PERFECTO!" en grande
