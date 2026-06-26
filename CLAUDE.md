# PythonJr — Contexto rápido para Claude

## ¿Qué es?
Plataforma educativa gamificada para que niños (~11 años) aprendan programación en casa. El papá supervisa y paga al hijo en dinero real (COP) por completar ejercicios. Stack: **Laravel 13 + React 19 + Sanctum token auth**.

Producción: `https://www.pythonjr.com` — Railway (Docker). Cada `git push main` despliega automáticamente.

---

## Roles

| Rol | URL de acceso | Descripción |
|-----|--------------|-------------|
| `superadmin` | `/acceso` | Dueño de la plataforma (James). Ve todas las familias, gestiona módulos, desactiva familias, resetea contraseñas de papás. |
| `admin` | `/login` → "Soy papá" | Papá de una familia. Ve progreso de su(s) hijo(s), valida ejercicios, paga, envía bonos, configura recompensas. |
| `estudiante` | `/login` → "Soy estudiante" | Hijo. Hace ejercicios, acumula COP, ve su racha y billetera. |

**Cuentas de prueba en producción:**
- Superadmin: `jamesmosqr@gmail.com` / `superadmin123`
- Admin: `admin@pythonjr.com` / `admin123`
- Estudiantes: `santiago@pythonjr.com` / `python123`, `nikolasmosqr@gmail.com` / `python123`

---

## Flujos principales (happy path)

### 1. Registro de nueva familia
- URL: `/registro/pythonjr_s3cr3t0_2026`
- Formulario 2 pasos: paso 1 = datos del papá, paso 2 = datos + avatar del hijo
- POST `/api/auth/registro` → crea `admin` (padre) + `estudiante` (hijo con `parent_id`) + Billetera + Racha + ProgresoModulo para el primer módulo activo
- El papá queda logueado con token, redirige a `/admin/dashboard`

### 2. Login
- **Admin/Superadmin:** POST `/api/auth/login` → verifica `activo`, devuelve token
- **Estudiante:** misma ruta → si `activo` = false o padre inactivo, error
- Token se guarda en Zustand + localStorage, se envía como `Authorization: Bearer {token}`

### 3. Estudiante hace un ejercicio
- Ve módulos en `/dashboard` → entra al módulo → ve lecciones (teoría) → hace ejercicios
- POST `/api/ejercicios/{id}/intentar` con `{respuesta: "..."}`
- Si `quiz_opcion` o `quiz_texto` → auto-verificado → si correcto → `GamificacionService` acredita COP + actualiza racha + verifica logros + desbloquea siguiente módulo
- Si `codigo_libre` o `mini_proyecto` → queda `pendiente_validacion = true`
- Si `terminal_git` → IA de Anthropic evalúa

### 4. Papá valida un ejercicio
- Admin dashboard → botón "Validar" → lista de ejercicios pendientes
- POST `/api/admin/validar/{completado}` con `{aprobado: true/false, feedback: "..."}`
- Si aprobado → `GamificacionService` acredita recompensa al hijo

### 5. Papá paga al hijo
- En `/admin/dashboard` aparece card "Registrar pago" si hay saldo pendiente
- POST `/api/admin/pagar` con `{monto, estudiante_id}` → crea transacción negativa en billetera → registra pago físico

### 6. Superadmin gestiona familias
- `/acceso` → login exclusivo superadmin
- `/superadmin/dashboard` → stats + lista familias + módulos
- Toggle activo/inactivo por familia (revoca tokens inmediatamente)
- Resetear contraseña del papá (genera temporal, se muestra inline)

---

## Arquitectura clave

### Backend — Laravel 13
```
app/
  Http/
    Controllers/Api/
      AuthController.php          — login, registro, logout, me
      EjercicioController.php     — show, intentar (auto + IA + padre)
      DashboardController.php     — dashboard del estudiante
      ModuloController.php        — lista + detalle de módulos
      Admin/
        AdminDashboardController  — dashboard del admin (filtrado por parent_id)
        ValidacionController      — pendientes, validar, pagar
        BonoSorpresaController    — enviar/historial bonos
        ConfiguracionController   — recompensas por tipo ejercicio
        AdminPerfilController     — actualizar perfil admin
        HackathonAdminController  — activar/cancelar hackathon
      SuperAdmin/
        SuperAdminController      — stats, familias, toggleFamilia, resetPassword, módulos
  Services/
    GamificacionService           — NÚCLEO: acredita, desbloquea siguiente módulo, verifica logros
    BilleteraService              — acreditar/debitar transacciones
    RachaService                  — registrar actividad diaria
    AnthropicGradingService       — califica ejercicios con IA (codigo_libre tipo terminal_git)
  Models/
    User                          — roles: superadmin/admin/estudiante, parent_id, activo
    Modulo                        — nivel, orden, slug, recompensa_base
    Ejercicio                     — tipo, recompensa_ejercicio, recompensa_perfecto, es_obligatorio
    EjercicioOpcion               — opciones de quiz_opcion
    EjercicioCompletado           — registro de intento del estudiante
    ProgresoModulo                — estado: bloqueado/disponible/en_progreso/completado
    Billetera                     — saldo_total, saldo_pendiente, saldo_pagado
    TransaccionBilletera          — historial de movimientos
    Racha                         — dias_actuales, dias_maximos
    Logro / LogroUsuario          — badges del estudiante
    Hackathon                     — eventos especiales con recompensa extra
    ConfiguracionPlataforma       — multiplicadores de recompensa por tipo
  Middleware/
    EsAdmin       — permite admin Y superadmin
    EsSuperAdmin  — solo superadmin
```

### Frontend — React 19 + Zustand + React Router
```
src/
  App.jsx                         — routing principal (3 guards separados)
  stores/
    authStore.js                  — user, token, initialized, login/logout/fetchMe
    progresoStore.js              — dashboard y módulos del estudiante
    billeteraStore.js             — saldo en tiempo real
  pages/
    Login.jsx                     — modo estudiante / papá (solo acepta admin, no superadmin)
    SuperAdminLogin.jsx           — /acceso — solo acepta superadmin
    Register.jsx                  — 2 pasos (datos papá + datos hijo)
    Dashboard.jsx                 — panel estudiante: racha, módulos, desafío del día
    Modulo.jsx                    — lecciones + ejercicios de un módulo
    Ejercicio.jsx                 — resolver un ejercicio
    Billetera.jsx / Logros.jsx
    Hackathon.jsx
    admin/
      AdminDashboard.jsx          — progreso del hijo, pago rápido, validar
      Validaciones.jsx
      ConfiguracionAdmin.jsx
      ActivarHackathon.jsx
    superadmin/
      SuperAdminDashboard.jsx     — stats, familias (toggle+reset), módulos toggle
  components/layout/
    AppLayout.jsx                 — layout con XP bar (estudiantes y admins)
    SuperAdminLayout.jsx          — layout minimalista sin XP (solo superadmin)
    Header.jsx
```

### Routing guards (App.jsx)
- `RequireEstudiante` → solo `role === 'estudiante'` (superadmin/admin → a su dashboard)
- `RequireAdmin` → solo `role === 'admin'` (superadmin → a `/superadmin/dashboard`)
- `RequireSuperAdmin` → solo `role === 'superadmin'`

---

## Base de datos — tablas clave

```
users           id, name, email, password, role, avatar, parent_id, activo
modulos         id, nivel, orden, slug, titulo, icono, recompensa_base, dias_estimados, activo
lecciones       id, modulo_id, orden, tipo, titulo, contenido (markdown)
ejercicios      id, modulo_id, orden, tipo, titulo, enunciado, codigo_base, solucion,
                respuesta_correcta, es_obligatorio, recompensa_ejercicio, recompensa_perfecto, pista
ejercicio_opciones  id, ejercicio_id, texto, es_correcta, orden
ejercicio_completados  id, user_id, ejercicio_id, modulo_id, respuesta_dada,
                       es_correcto, es_perfecto, validado_por_padre, validado_por_ia,
                       feedback_ia, recompensa_ganada, intento, completado_at
progreso_modulos    id, user_id, modulo_id, estado, iniciado_at, completado_at
billeteras          id, user_id, saldo_total, saldo_pendiente, saldo_pagado
transacciones_billetera  id, user_id, tipo, monto, descripcion, referencia_id, referencia_tipo
rachas              id, user_id, dias_actuales, dias_maximos, ultima_actividad_at
logros / logro_usuario
hackathons / hackathon_participante
configuracion_plataforma  — multiplicadores de recompensa
```

---

## Módulos actuales

| Nivel | Módulos |
|-------|---------|
| 1 | hola-python 🐍, tomando-decisiones 🤔, repitiendo-cosas 🔁 |
| 2 | funciones-magicas ✨, colecciones-de-cosas 📦, guardando-informacion 💾, git-github 🌿 |
| 3 | bases-de-datos 🗄️, objetos-y-clases 🏗️, python-y-el-internet 🌐, hagamos-un-juego-visual 🎮 |
| 4 | proyecto-final 🏆 |
| 5 | html-construyendo-la-web 🌐, css-disenando-con-estilo 🎨 |

**Progresión:** lineal por nivel/orden. Al completar todos los `es_obligatorio` de un módulo → `GamificacionService::desbloquearSiguienteModulo()` → siguiente módulo queda `disponible`.

**Recompensas por ejercicio:**
- Python (niveles 1-4): 2000 COP (ejercicio) / 3000 COP (perfecto)
- HTML/CSS (nivel 5): 100 COP / 150 COP (ajustable por admin)
- `mini_proyecto`: hasta 8000 COP Python, 200 COP HTML/CSS

---

## Tipos de ejercicio

| Tipo | Verificación | ¿Quién evalúa? |
|------|-------------|----------------|
| `quiz_opcion` | Auto por ID de opción correcta | Sistema |
| `quiz_texto` | Auto por comparación exacta lowercase | Sistema |
| `codigo_libre` | Manual | Papá (panel validaciones) |
| `mini_proyecto` | Manual | Papá (panel validaciones) |
| `terminal_git` | IA | `AnthropicGradingService` |

---

## Variables de entorno críticas (Railway)
```
APP_KEY, DB_*, SANCTUM_STATEFUL_DOMAINS — no necesario (token auth)
ANTHROPIC_API_KEY — para calificación IA
REGISTRO_TOKEN=pythonjr_s3cr3t0_2026 — token de registro de familias
PORT — Railway lo inyecta
```

---

## Docker / deploy
- `docker/start.sh`: config:cache → migrate --force → seed si DB vacía → backfill estudiantes huérfanos → FrankenPHP
- Backfill: vincula estudiantes sin `parent_id` al primer admin
- Cada push a `main` → Railway rebuild + redeploy automático

---

## Lo que NO existe todavía
- Pasarela de pagos (el pago es manual, el papá lo registra)
- Notificaciones push / email
- Gestión de contraseñas del estudiante por el niño mismo (lo hace el papá desde su panel)
- Chat o mensajería entre papá e hijo
- Más módulos web (actualmente HTML + CSS son los únicos del nivel 5)
