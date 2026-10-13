# Organizador de Tareas - CodeIgniter 4

Sistema web para la gestión personalizada de tareas escolares, apuntes por materia y galería de materiales adjuntos.

## Características Principales
- **Autenticación Completa:** Registro de usuarios, activación de cuenta mediante correo SMTP y recuperación de contraseña.
- **Gestión de Perfil:** Edición de datos personales, cambio de contraseña, avatar de perfil y opción de eliminar cuenta.
- **Pizarrón de Tareas:** Organización por clase/materia, fecha de asignación y fecha de entrega.
- **Multimedios:** Adjunto de múltiples imágenes por tarea.
- **Buscador y Filtros:** Buscador de tareas y opción para ocultar/mostrar tareas completadas.
- **Multi-idioma:** Soporte dinámico para Español e Inglés (ES / EN).
- **Aislamiento de Datos:** Seguridad por usuario donde cada cuenta maneja únicamente sus propias tareas.

## Tecnologías Utilizadas
- **Backend:** PHP 8.x, CodeIgniter 4
- **Base de Datos:** MySQL
- **Frontend:** Bootstrap 5, HTML5, JavaScript
- **Entorno:** Ubuntu en WSL (Windows Subsystem for Linux)

## Instalación Local

1. Clonar el repositorio:
   git clone https://github.com/wuicho-source/organizador-tareas.git
   cd organizador-tareas

2. Instalar dependencias de Composer:
   composer install

3. Configurar la base de datos:
   Crear la base de datos MySQL `organizador_tareas_db` y configurar el archivo `.env`.

4. Iniciar el servidor local:
   php spark serve
