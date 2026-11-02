# Modelo de datos de referencia — Sanatorio Médico Stack 3

## Propósito
El archivo `database/Modelo_Referencia_PostgreSQL.sql` es el **diccionario funcional de referencia** del proyecto anterior (Stack 2). No se ejecuta en MongoDB ni se utiliza como migración Laravel. Consultarlo antes de diseñar cualquier módulo nuevo para conservar nombres, atributos, obligatoriedad y relaciones de negocio.

## Instrucciones para Codex
1. Leer el bloque `CREATE TABLE Tbl_<Modulo>` correspondiente y las tablas relacionadas.
2. Extraer campos, tipos, `NOT NULL`/`NULL`, claves y relaciones. Proponer un esquema de documentos MongoDB equivalente y explicar diferencias; no asumir que las claves foráneas SQL existen en MongoDB.
3. Mantener los nombres funcionales del script como referencia; aplicar las convenciones reales del Stack 3 según `AGENTS.md` y el contrato de API existente. No renombrar ni modificar Sucursales automáticamente.
4. Los `INT GENERATED ... IDENTITY` y `FOREIGN KEY` son propios de PostgreSQL: en MongoDB elegir identificadores y referencias compatibles con Laravel MongoDB. No convertir automáticamente todo a entero ni inventar restricciones relacionales.
5. Los tipos `VARCHAR(n)`, `DATE`, `TIME`, `DECIMAL(p,s)`, `BOOLEAN`, `TIMESTAMP` describen intención y límites de los datos; implementar validaciones apropiadas en Laravel. Para importes monetarios, evitar conversiones con pérdida de precisión.
6. Antes de crear código, señalar reglas faltantes: unicidad, búsquedas, eliminación, datos sensibles, autenticación y relaciones. No inventarlas como hechos confirmados.
7. Trabajar con datos ficticios. No introducir información real de pacientes; no publicar endpoints de datos médicos sin autenticación y autorización.
8. No copiar el código del módulo Sucursales: construir cada módulo desde sus requisitos y estándares generales.

## Módulo de la clase: Pacientes
Fuente: `CREATE TABLE Tbl_Pacientes` del script de referencia.

| Campo de referencia | Tipo PostgreSQL | Obligatorio |
|---|---|---|
| CodigoPaciente | INT IDENTITY, PK | Sí, generado |
| NumeroExpediente | VARCHAR(30) | Sí |
| TipoDocumento | VARCHAR(30) | Sí |
| NumeroDocumento | VARCHAR(25) | Sí |
| Nombres | VARCHAR(100) | Sí |
| Apellidos | VARCHAR(100) | Sí |
| FechaNacimiento | DATE | Sí |
| Genero | VARCHAR(20) | Sí |
| TipoSangre | VARCHAR(10) | No |
| Telefono | VARCHAR(20) | Sí |
| CorreoElectronico | VARCHAR(120) | No |
| Direccion | VARCHAR(200) | Sí |
| ContactoEmergencia | VARCHAR(150) | No |
| TelefonoEmergencia | VARCHAR(20) | No |
| Alergias | VARCHAR(500) | No |
| Estado | VARCHAR(20) | Sí |

**Pendiente de decisión:** criterios de búsqueda, reglas de unicidad para expediente/documento, valores permitidos de `Estado`, política de eliminación y control de acceso. El script original no define estas reglas. No confundir `Estado VARCHAR(20)` de Pacientes con `Estado BOOLEAN` de Sucursales.

## Otras entidades
El script contiene 16 tablas: Tbl_Sucursales, Tbl_Roles, Tbl_Colaboradores, Tbl_Pacientes, Tbl_Especialidades, Tbl_ColaboradoresEspecialidades, Tbl_Horarios, Tbl_CitasConsultas, Tbl_CitasConsultasDetalle, Tbl_Habitaciones, Tbl_Hospitalizaciones, Tbl_Productos, Tbl_Proveedores, Tbl_MovimientosInventario, Tbl_Facturas y Tbl_FacturasDetalle. Leer sus definiciones originales al implementar cada módulo; esta lista no sustituye al SQL.

## Integración con las instrucciones del agente
En `AGENTS.md`, incluir esta regla:

> Antes de diseñar o implementar un módulo de negocio, lee `database/LEER_MODELO_DATOS.md` y el bloque correspondiente de `database/Modelo_Referencia_PostgreSQL.sql`. Utilízalos como referencia funcional de atributos, tipos y relaciones, sin ejecutar PostgreSQL en MongoDB. Si hay diferencias con el modelo MongoDB existente o requisitos sin definir, repórtalas antes de modificar código.
