# Constancia de pasos - Tarea Semana 11: Sanatorio Médico

## 1. Comunicación database, backend y frontend

El proyecto usa el recorrido solicitado:

**Vue 3 -> Axios -> Laravel API -> mongodb/laravel-mongodb -> MongoDB**

El backend usa `DB_CONNECTION=mongodb` y la base `sanatorio_medico`. Vite redirige `/api` hacia `http://127.0.0.1:8000` durante desarrollo.

## 2. Backend

Cada módulo tiene archivos propios. El código está comentado con marcas `PASO` para dejar constancia del trabajo.

- Sucursales: `Sucursal.php`, `SucursalRequest.php`, `SucursalRepository.php`, `SucursalService.php`, `SucursalController.php`.
- Pacientes: `Paciente.php`, `PacienteRequest.php`, `PacienteRepository.php`, `PacienteService.php`, `PacienteController.php`.
- Médicos: `Medico.php`, `MedicoRequest.php`, `MedicoRepository.php`, `MedicoService.php`, `MedicoController.php`.
- Las rutas REST están documentadas en `backend/routes/api.php`.

## 3. Frontend

`frontend/src/App.vue` contiene el panel principal con menú para abrir los tres CRUD. Cada módulo permite:

- Consultar listado.
- Buscar.
- Agregar.
- Consultar detalle.
- Editar.
- Eliminar con confirmación.
- Mostrar validaciones y mensajes de éxito/error.

## 4. Iniciar el proyecto

En una terminal:

```bash
cd backend
php artisan serve
```

En otra terminal:

```bash
cd frontend
npm install
npm run dev
```

Abrir la URL que indique Vite, normalmente `http://localhost:5173`.

## 5. Probar conexión con MongoDB

En navegador o Postman:

```text
GET http://127.0.0.1:8000/api/ping
```

Debe responder `ok: true`.

## 6. Endpoints para Postman

Para cada módulo:

```text
GET    /api/sucursales?buscar=texto
POST   /api/sucursales
GET    /api/sucursales/{id}
PUT    /api/sucursales/{id}
DELETE /api/sucursales/{id}

GET    /api/pacientes?buscar=texto
POST   /api/pacientes
GET    /api/pacientes/{id}
PUT    /api/pacientes/{id}
DELETE /api/pacientes/{id}

GET    /api/medicos?buscar=texto
POST   /api/medicos
GET    /api/medicos/{id}
PUT    /api/medicos/{id}
DELETE /api/medicos/{id}
```

Se incluye `postman/Sanatorio_Medico_Semana11.postman_collection.json` para importar directamente en Postman.

## 7. MongoDB Compass y datos ficticios

En `database/` están las exportaciones JSON con cinco registros ficticios para cada colección. Después de importarlos, realizar operaciones desde Vue o Postman y comprobar los cambios en Compass. Como los datos se guardan en MongoDB, permanecen después de actualizar el navegador.

## 8. Verificación antes de entregar

- Ejecutar `php artisan route:list --path=api` y comprobar las rutas.
- Ejecutar `npm run build` en `frontend`.
- Probar crear, buscar, consultar, editar y eliminar al menos un registro por módulo.
- Confirmar en Compass que los cambios se reflejan.
- Entregar la carpeta con `backend/`, `frontend/` y `database/`.
