# ✈️ Sistema de Reservas Laravel

Proyecto desarrollado en **Laravel** para gestionar reservas de vuelos, con manejo de pasajeros, selección de asientos y control de acceso para que cada usuario gestione únicamente sus propias reservas.

## 📋 Requisitos

Asegúrate de tener instalado:

- **PHP** >= 8.1  
- **Composer**  
- **MySQL** (o el motor de base de datos que uses)  
- **Git**  
- **Node.js** y **npm** (solo si necesitas compilar assets Frontend)  

---

## 🚀 Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/TU_USUARIO/mi-proyecto-laravel.git
   cd mi-proyecto-laravel
   ```

2. **Instalar dependencias de PHP**
   ```bash
   composer install
   ```

3. **Instalar dependencias de Node (si aplica)**
   ```bash
   npm install
   ```

4. **Configurar el archivo `.env`**
   - Copia el archivo de ejemplo:
     ```bash
     cp .env.example .env
     ```
   - Edita `.env` y configura tus datos:
     ```env
     APP_NAME="Sistema de Reservas"
     APP_ENV=local
     APP_KEY=
     APP_DEBUG=true
     APP_URL=http://localhost

     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=nombre_base_datos
     DB_USERNAME=usuario
     DB_PASSWORD=contraseña
     ```

5. **Generar clave de la app**
   ```bash
   php artisan key:generate
   ```

6. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Crear enlace de storage**
   ```bash
   php artisan storage:link
   ```

---

## ▶️ Levantar la aplicación

### Servidor PHP integrado de Laravel
```bash
php artisan serve
```
La app estará disponible en: **http://127.0.0.1:8000**

### Compilar assets (si usas Tailwind/Vite/etc.)
En una terminal aparte:
```bash
npm run dev
```

---

## 🛠 Guía funcional

El flujo básico del sistema es:

1. **Búsqueda de vuelos**: El usuario selecciona origen, destino y fechas.
2. **Selección de vuelo**: Escoge una oferta de vuelo de la lista disponible.
3. **Elección de asientos**: Selecciona los asientos disponibles por segmento.
4. **Ingreso de datos de pasajeros**: Rellena nombre, apellido, documento, fecha de nacimiento y tipo (adulto, niño, infante).
5. **Confirmación de reserva**: El sistema guarda la reserva con pasajeros y asientos.
6. **Gestión de reservas**:
   - El usuario puede **ver** sus reservas.
   - El usuario puede **editar** datos de pasajeros y asientos.
   - El usuario puede **eliminar** su reserva.
   - Ningún usuario puede modificar reservas de otro.

---

## 👤 Credenciales de acceso iniciales (si el seeder las crea)

- **Email:** admin@example.com  
- **Contraseña:** password  

*(Recuerda cambiarlas después de ingresar)*

---

## 📂 Estructura principal

```
app/Http/Controllers/    # Controladores
app/Models/              # Modelos Eloquent
database/migrations/     # Migraciones
resources/views/         # Vistas Blade
routes/web.php           # Rutas web
routes/api.php           # Rutas API
```

---

## 📌 Comandos útiles

- Limpiar caché:
  ```bash
  php artisan optimize:clear
  ```
- Ejecutar tests:
  ```bash
  php artisan test
  ```
- Actualizar dependencias:
  ```bash
  composer update
  ```

---

## 📄 Licencia

Este proyecto es de uso interno y académico. Puedes adaptarlo y mejorarlo según tus necesidades.
