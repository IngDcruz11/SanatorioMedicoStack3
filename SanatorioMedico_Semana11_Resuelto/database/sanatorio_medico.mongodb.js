// Ejecutar con mongosh. Reconstruye la estructura, no recupera datos borrados.
// Puede repetirse: no elimina colecciones ni sobrescribe registros existentes.
const sanatorio = db.getSiblingDB('sanatorio_medico');
const validator = { $jsonSchema: {
  bsonType: 'object',
  required: ['nombre', 'direccion', 'telefono', 'estado'],
  properties: {
    nombre: { bsonType: 'string', minLength: 1, maxLength: 255 },
    direccion: { bsonType: 'string', minLength: 1, maxLength: 255 },
    telefono: { bsonType: 'string', minLength: 1, maxLength: 50 },
    correo: { bsonType: ['string', 'null'], maxLength: 255 },
    ciudad: { bsonType: ['string', 'null'], maxLength: 255 },
    departamento: { bsonType: ['string', 'null'], maxLength: 255 },
    fecha_apertura: { bsonType: ['date', 'null'] },
    tipo_sucursal: { bsonType: ['string', 'null'], maxLength: 255 },
    estado: { bsonType: 'bool' },
    created_at: { bsonType: ['date', 'null'] },
    updated_at: { bsonType: ['date', 'null'] }
  }
}};
if (!sanatorio.getCollectionNames().includes('sucursales')) {
  sanatorio.createCollection('sucursales', { validator });
} else {
  const result = sanatorio.runCommand({ collMod: 'sucursales', validator, validationLevel: 'strict' });
  if (!result.ok) throw new Error('No se pudo configurar la colección sucursales.');
}
sanatorio.sucursales.createIndex({ nombre: 1 }, { name: 'sucursales_nombre' });
sanatorio.sucursales.createIndex({ ciudad: 1 }, { name: 'sucursales_ciudad' });

// Datos ficticios para probar la aplicación; puedes eliminarlos desde la pantalla.
const ejemplos = [
  { _id: ObjectId('660000000000000000000001'), nombre: 'Sucursal Central (ejemplo)', direccion: 'Avenida de ejemplo 1, zona 1', telefono: '0000-0001', correo: 'central@example.com', ciudad: 'Guatemala', departamento: 'Guatemala', fecha_apertura: ISODate('2024-01-15T00:00:00Z'), tipo_sucursal: 'Central', estado: true },
  { _id: ObjectId('660000000000000000000002'), nombre: 'Sucursal Occidente (ejemplo)', direccion: 'Calle de ejemplo 2, zona 3', telefono: '0000-0002', correo: 'occidente@example.com', ciudad: 'Quetzaltenango', departamento: 'Quetzaltenango', fecha_apertura: ISODate('2025-03-10T00:00:00Z'), tipo_sucursal: 'Regional', estado: true },
  { _id: ObjectId('660000000000000000000003'), nombre: 'Sucursal Antigua (ejemplo)', direccion: 'Calle de ejemplo 3', telefono: '0000-0003', correo: 'antigua@example.com', ciudad: 'Antigua Guatemala', departamento: 'Sacatepéquez', fecha_apertura: ISODate('2025-06-01T00:00:00Z'), tipo_sucursal: 'Clínica', estado: false }
];
for (const ejemplo of ejemplos) {
  sanatorio.sucursales.updateOne({ _id: ejemplo._id }, { $setOnInsert: { ...ejemplo, created_at: new Date(), updated_at: new Date() } }, { upsert: true });
}
print('Base sanatorio_medico preparada. Sucursales: ' + sanatorio.sucursales.countDocuments());
