# Mini Gestor de Empleados

El proyecto fue desarrollado utilizando Laravel 10, PHP y MySQL.
Las instrucciones siguientes muestran una instalación usando Laragon.

## Instalación y ejecución

1. Clonar el repositorio dentro de `C:\laragon\www`:

```bash
git clone https://github.com/fxbianitx/rrhh-prueba.git rrhh-prueba
```

2. Ingresar a la carpeta del proyecto:

```bash
cd rrhh-prueba
```

3. Instalar las dependencias de PHP:

```bash
composer install
```

4. Copiar el archivo de entorno:

```bash
copy .env.example .env
```

5. Configurar la conexión a MySQL en el archivo `.env`:

```env
APP_NAME="Mini Gestor de Empleados"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://rrhh-prueba.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rrhh_prueba
DB_USERNAME=root
DB_PASSWORD=
```

6. Crear la base de datos `rrhh_prueba` en MySQL.

7. Generar la clave de la aplicación:

```bash
php artisan key:generate
```

8. Iniciar `Apache` y `MySQL` desde Laragon.

9. Ejecutar migraciones y seeders:

```bash
php artisan migrate:fresh --seed
```

10. Abrir el proyecto en el navegador:

```text
http://rrhh-prueba.test
```

## Ejecución de pruebas

Para ejecutar la suite de pruebas:

```bash
php artisan test
```


## Decisiones técnicas

1. Analice los requerimientos e implementé:
	a) Employee: Como entidad principal del sistema debido a que representa a la persona dentro de la empresa para la gestion, desde aquí se manejan ls datos personales y laborales directos del empleado.
	b) Contract: Como modelo relacionado a empleados, porque un empleado puede tener muchos contratos a lo largo del tiempo. Al separarlo de los empleados, nos permite llevar el historial completo sin sobrescribir información.
	c) Area, Position y Location como catálogos independientes para evitar duplicidad de datos manteniendo la base normalizada. De esa forma, varios empleados pueden compartir la misma área, cargo o local sin repetir textos en cada registro, y además facilita filtros, mantenimiento y consistencia.


2. El historial se conserva cuando se crea un registro en Contract por cada renovación. No se debe de reemplazar ni actualizar el contrato anterior como si fuera el mismo. Cuando pasa esto, el algoritmo busca si existe un contrato vigente del empleado. Si existe y la fecha de inicio es posterior el contrato vigente se cierra. Luego se crea un nuevo contrato como un registro adicional para preservar la trazabilidad y saber qué contratos tuvo el empleado en cada periodo.

3. Consideré principalmente estos casos:
	a) Contratos con end_date = null, porque representan contratos vigentes y deben contarse como activos si ya habían iniciado antes o en la fecha de corte.
	b)Empleados con más de un contrato en su historial, para evitar contarlos más de una vez en el resumen.
	c) Contratos ya finalizados antes de la fecha de corte, porque no deben considerarse activos.
 	d) Contratos que inician después de la fecha de corte, porque tampoco deben contarse.
	e) Agrupación por área para que el resultado final sea un resumen útil de gestión por área y no sea solo un total general.


4. En la migración se maneja esto usando el constraint UNIQUE para que no exista 2 DNI con el mismo valor. Y con el apoyo de la capa Request, valide que un nuevo empleado no debe tener un DNI que ya esta registrado en el sistema. Pero que si sea valido cuando se actualiza o edita los datos de un usuario que ya existe. O sea, al actualizar un empleado el sistema permite conservar el mismo DNI del propio empleado, pero no permite asignarle un DNI que ya pertenece a otro registro.

5. La primera medida sería optimizar la base de datos mediante índices en los campos mas usados para filtros, búsquedas y relaciones como dni, area_id, position_id, employee_id, start_date y end_date. Cuidadosamente revisaría las consultas que hoy dependen de relaciones para mostrar datos en pantalla. En casos mas graves, otra medida por mi experiencia seria desnormalizar algunos datos de lectura frecuente como: Almacenar también el nombre visible de un catálogo junto a su identificador en una tabla, siempre que exista una necesidad urgente de rendimiento y sobre todo, que se lleve un buen control de consistencia.
Antes que nada, debe de medirse el comportamiento del sistema, en base a lo que nos digan los tiempos de respuesta de la BD y la aplicación.


## NOTA:
	a) Al dar de baja a un empleado no se realiza una eliminación física. Se utiliza el trait SoftDeletes para aplicar una baja lógica conservando la información y el historial asociado al empleado.
	b) Apliqué la validación tanto para el backend como en el frontend.
	c) Los filtros se ejecutan automáticamente mediante Fetch API sin necesidad de recargar la página, proporcionando una experiencia de usuario más fluida.
