# Prompt de arranque — PythonJr para Claude Code

Pega esto en Claude Code al iniciar el proyecto:

---

Estoy construyendo **PythonJr**, una plataforma web educativa gamificada para que mi hijo de 11 años (colegio STEM, Itagüí, Colombia) aprenda Python desde cero.

Lee primero el archivo `CLAUDE.md` en la raíz del proyecto — contiene toda la arquitectura, el curriculum completo, el esquema de base de datos y las fases de desarrollo.

Lee también `SKILL.md` para las convenciones específicas del proyecto.

**Stack:**
- Backend: Laravel 12 + Sanctum + PostgreSQL
- Frontend: React 19 + Vite + Tailwind 4 + shadcn/ui + Zustand + Framer Motion
- Deploy target: Railway (backend) + Railway static (frontend)
- Dev local: Laravel Herd + WAMP en Windows, JetBrains IDEs

**Comienza por la Fase 1:**

1. Crear estructura del proyecto Laravel 12 en `/backend`
2. Crear estructura del proyecto React + Vite en `/frontend`
3. Configurar Sanctum para SPA con CORS entre localhost:8000 y localhost:5173
4. Crear TODAS las migraciones según el esquema del CLAUDE.md
5. Crear los modelos con sus relaciones Eloquent
6. Crear el UserSeeder, ModuloSeeder, LeccionSeeder y EjercicioSeeder
   con el contenido completo del Módulo 1 tal como está en CLAUDE.md
7. Verificar que `php artisan migrate --seed` corra sin errores

Credenciales de prueba a crear en el seeder:
- Admin (padre): admin@pythonjr.com / password: admin123
- Estudiante (hijo): santiago@pythonjr.com / password: python123

Al terminar la Fase 1, dime qué archivos creaste y ejecuta las migraciones para confirmar que corren.
