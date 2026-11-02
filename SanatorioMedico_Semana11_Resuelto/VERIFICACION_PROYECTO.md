# Verificación del proyecto

- Se validó la sintaxis PHP de los archivos nuevos/modificados con `php -l`.
- Se ejecutó `php artisan route:list --path=api --except-vendor` y se confirmaron los 15 endpoints CRUD + `/api/ping`.
- Los tres archivos JSON de `database/` y la colección de Postman se validaron como JSON correcto.
- En este entorno no fue posible finalizar `npm install` por disponibilidad de dependencias nativas/red. En Windows debe ejecutarse `npm install` dentro de `frontend/` y luego `npm run dev`.
- PHPUnit no pudo ejecutarse en este entorno porque el PHP disponible no tiene las extensiones DOM, mbstring y XMLWriter. Esto es una limitación del entorno de validación, no del código del proyecto.
