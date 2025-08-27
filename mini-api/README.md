# 📌 Mini API Reclutamiento

Mini-API desarrollada en **Laravel** que consume y expone datos de reclutas de manera legible y permite agregar nuevos aspirantes.  

---

## ⚙️ Requisitos
- PHP >= 8.1  
- Composer  
- Laravel 12 
- Acceso a internet para consumir de Firebase

---

## 🔧 Instalación y ejecución
1. Clonar el repositorio:
   ```bash
   git clone https://github.com/EliasGonz/TP-mini-api-laravel
   cd mini-api
   ```
2. Instalar dependencias:
   ```bash
   composer install
   ```
3. Levantar el servidor local:
   ```bash
   php artisan serve
   ```

---

## 📂 Funcionalidades

### 1. GET Api/reclutados

Consume el recurso de Firebase: https://reclutamiento-dev-procontacto-default-rtdb.firebaseio.com/reclutier.json y devuelve la información en un formato legible para humanos.

### 2. POST Api/recluta 

Permite enviar un nuevo aspirante en formato json:
```json
{
  "name": "Jane",
  "suraname": "Doe",
  "birthday": "1995/05/20",
  "documentType": "DNI",
  "documentNumber": 12345678
}
```

Normaliza los datos y los mapea a:
```json
{ 
  "name": "Jane",
  "suraname": "Doe",
  "birthday": "1995/05/20/",
  "age": 29,
  "documentType": "DNI",
  "documentNumber": 12345678
}
```

Valida o Normaliza los datos de la siguiente manera:
  - Convierte name y suraname a Title Case.
  - Verifica que birthday tenga formato YYYY/MM/DD, esté entre 1900/01/01 y la fecha actual.
  - Calcula automáticamente age en base a su fecha de nacimiento.
  - Valida que documentType sea solo CUIT o DNI.
  - Agrega una '/' al final en birthday (YYYY/MM/DD/).

Las request invalidas devuelve codigo de estado 400 con mensaje de error.

Finalmente, se envía el objeto a Firebase: https://reclutamiento-dev-procontacto-default-rtdb.firebaseio.com/reclutier.json

---

## 📌 Endpoints

### 🔹 Obtener todos los reclutas
`GET /api/reclutados`

Ejemplo de respuesta:
```json
{
  "status": "exitoso",
  "details": "lista de reclutas obtenida exitosamente",
  "reclutas list": {
    "...": "..."
  }
}
```

### 🔹 Registrar un nuevo recluta
`POST /api/recluta`

Body esperado:
```json
{
  "name": "Jane",
  "suraname": "Doe",
  "birthday": "1995/05/20",
  "documentType": "DNI",
  "documentNumber": 12345678
}
```

Ejemplo de respuesta:
```json
{
  "status": "exitoso",
  "details": "datos del recluta enviados exitosamente a Firebase",
  "data sent": {
    "name": "Jane",
    "suraname": "Doe",
    "birthday": "1995/05/20/",
    "age": 29,
    "documentType": "DNI",
    "documentNumber": 12345678
  }
}
```
