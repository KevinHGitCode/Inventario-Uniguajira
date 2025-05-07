# Inventario Uniguajira

Este es un proyecto desarrollado para la asignatura de Ingeniería de Software 2. Es una aplicación web para la gestión de inventarios.

## Despliegue

El proyecto está desplegado en: [https://inventario-uniguajira.onrender.com](https://inventario-uniguajira.onrender.com)

## Requisitos previos

Antes de instalar el proyecto, asegúrate de tener lo siguiente instalado en tu sistema:

- PHP >= 7.4
- Servidor web (como Apache o Nginx)
- MySQL
- XAMPP (opcional, para un entorno de desarrollo local)
- Composer (para gestionar dependencias)

## Instalación

Sigue estos pasos para instalar el proyecto en tu entorno local:

1. **Clona el repositorio**

   ```bash
   git clone https://github.com/KevinHGitCode/Inventario-Uniguajira.git
   cd Inventario-Uniguajira
   ```

## Configuración e Instalación de Composer

Composer es el gestor de dependencias para PHP que utilizamos en este proyecto. Sigue estos pasos para configurarlo:

### Instalación de Composer

1. **Instala Composer globalmente**:

   - **Windows**: Descarga el instalador desde [getcomposer.org](https://getcomposer.org/download/) y sigue las instrucciones de instalación.
   
   - **MacOS/Linux**:
     ```bash
     curl -sS https://getcomposer.org/installer | php
     sudo mv composer.phar /usr/local/bin/composer
     sudo chmod +x /usr/local/bin/composer
     ```

2. **Verifica la instalación**:
   ```bash
   composer --version
   ```

### Configuración del proyecto con Composer

1. **Instala las dependencias** (ya definidas en composer.json):
   ```bash
   composer install
   ```

2. **Para agregar nuevas dependencias**:
   ```bash
   composer require [nombre-del-paquete]
   ```

3. **Para actualizar las dependencias**:
   ```bash
   composer update
   ```

4. **Regenerar el autoloader de Composer** (útil después de agregar clases nuevas):
   ```bash
   composer dump-autoload
   ```

### Uso de Composer en el proyecto

1. **Inclusión del autoloader en tus scripts PHP**:
   ```php
   require __DIR__ . '/vendor/autoload.php';
   ```

2. **Uso de paquetes externos**:
   - Después de instalar un paquete con Composer, puedes usarlo directamente:
   ```php
   use NombreNamespace\Clase;
   
   $objeto = new Clase();
   ```

> **Nota importante**: Asegúrate de que el archivo `composer.json` esté actualizado y que todas las dependencias necesarias estén especificadas correctamente. No subas la carpeta `vendor/` al repositorio, ya que puede ser muy grande y está excluida en el archivo `.gitignore`.