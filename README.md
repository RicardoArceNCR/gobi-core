# GOBi Core Plugin

Núcleo del dominio GOBi para plataforma WordPress. Este plugin maneja la lógica de negocio, estructuras de datos y flujos de trabajo que alimentan la plataforma de inteligencia política de GOBi.

## Arquitectura

GOBi Core sigue una separación limpia de responsabilidades:

- **WordPress**: Base operativa y persistencia de datos
- **Sage**: Capa de presentación y sistema visual
- **GOBi Core**: Dominio del producto y lógica de negocio (este plugin)
- **ACF Pro**: Configuración editorial
- **Auth0**: Identidad y login social

## Entidades del Dominio

### Custom Post Types
- **Proyectos** (`gobi_proyecto`) - Proyectos legislativos principales
- **Diputados** (`gobi_diputado`) - Perfiles de diputados
- **Comisiones** (`gobi_comision`) - Comisiones legislativas

### Taxonomías
- **Temas** (`gobi_tema`) - Clasificación jerárquica de temas políticos
- **Partidos** (`gobi_partido`) - Afiliación política de diputados

## Soporte Multi-país

GOBi está preparado para operar como plataforma regional dentro de un solo WordPress.

### Entidad País
- `gobi_pais` representa cada país soportado.

### Relación con entidades
Las entidades del dominio pueden asociarse a un país mediante:

- `_gobi_pais_id` 

Inicialmente aplica a:

- `gobi_proyecto` 
- `gobi_diputado` 
- `gobi_comision` 

Esta decisión permite crecer hacia portadas, fuentes, reglas legislativas y equipos editoriales por país sin crear múltiples instalaciones WordPress.

## Sistema de Workflow

### Estados de Proyectos
- `presentado` - Estado inicial del proyecto
- `en_comision` - En estudio en comisión
- `en_debate` - En debate legislativo
- `votado` - Votado en plenario
- `aprobado` - Aprobado y promulgado
- `archivado` - Archivado sin aprobación

### Transiciones Válidas
- `presentado` → `en_comision` | `archivado`
- `en_comision` → `en_debate` | `archivado`
- `en_debate` → `votado` | `archivado`
- `votado` → `aprobado` | `archivado`
- `aprobado` → (terminal)
- `archivado` → (terminal)

Toda transición requiere:
- Validación automática
- Motivo obligatorio
- Registro en bitácora

## Capabilities del Sistema

- `gobi_change_project_state` - Cambiar estado de proyectos
- `gobi_view_bitacora` - Ver bitácora de auditoría
- `gobi_manage_home_curation` - Gestionar curaduría de portada
- `gobi_edit_project_notes` - Editar notas internas de proyectos
- `gobi_view_private_explainers` - Ver explicadores privados
- `gobi_view_internal_metrics` - Ver métricas internas

## Bitácora de Auditoría

Registro completo de acciones del dominio en tabla propia:

- Entidad afectada
- ID de entidad
- Acción realizada
- Valor anterior y nuevo
- Motivo del cambio
- Usuario responsable
- Fecha y hora

## Instalación

1. Colocar carpeta `gobi-core` en `wp-content/plugins/`
2. Activar plugin desde administración de WordPress
3. Configurar roles y permisos según necesidades

## Estructura de Archivos

```
gobi-core/
├── gobi-core.php              # Archivo principal del plugin
├── src/
│   ├── Core/
│   │   └── Core.php          # Coordinación y bootstrap
│   ├── CPT/
│   │   └── Register.php      # Registro de CPTs del dominio
│   ├── Taxonomies/
│   │   └── Register.php      # Registro de taxonomías
│   ├── Capabilities/
│   │   └── Register.php      # Roles y capacidades
│   ├── Workflow/
│   │   └── Manager.php       # Gestión de estados y transiciones
│   └── Bitacora/
│       └── Logger.php        # Sistema de auditoría
└── README.md
```

## Uso

### Integración con Theme

El plugin expone el dominio para consumo del theme:

```php
// En el theme (Sage)
// El plugin ya está listo, usar funciones GOBi
$proyectos = get_posts(['post_type' => 'gobi_proyecto']);

// Verificar capacidades
if (current_user_can('gobi_change_project_state')) {
    // Usuario puede cambiar estados
}
```

### Gestión de Estados

```php
// Cambiar estado de proyecto
$resultado = \Gobi\Workflow\Manager::change_state(
    $proyecto_id, 
    'en_comision', 
    'Proyecto asignado a comisión de legislación'
);

if ($resultado) {
    // Estado cambiado exitosamente
}
```

### Consulta de Bitácora

```php
// Obtener bitácora de un proyecto
$bitacora = \Gobi\Bitacora\Logger::get_by_entity('proyecto', $proyecto_id);

// Obtener resumen de actividad
$resumen = \Gobi\Bitacora\Logger::get_summary();
```

## Seguridad

- Todas las capacidades se verifican antes de ejecutar acciones
- Los metadatos se sanitizan antes de almacenarse
- Las transiciones de estado se validan automáticamente
- Auditoría completa de acciones críticas

## Base de Datos

### Tablas Propias
- `wp_gobi_bitacora` - Registro de auditoría del dominio

### Tablas WordPress Utilizadas
- `posts` - Para CPTs (gobi_proyecto, gobi_diputado, gobi_comision)
- `postmeta` - Para metadatos adicionales de CPTs
- `terms` & `term_taxonomy` - Para taxonomías (gobi_tema, gobi_partido)

## Hooks Disponibles

### Actions
- `gobi_core_ready` - Plugin inicializado y listo
- `gobi_estado_cambiado` - Cambio de estado de proyecto
- `gobi_bitacora_registro` - Nuevo registro en bitácora

### Filters
- `gobi_workflow_transitions` - Modificar transiciones válidas
- `gobi_bitacora_fields` - Modificar campos de bitácora

## Extensión

### Agregar Nuevos Estados

```php
add_filter('gobi_workflow_transitions', function($transitions) {
    $transitions['nuevo_estado'] = ['estado_siguiente'];
    return $transitions;
});
```

### Logging Personalizado

```php
// Registrar eventos personalizados
\Gobi\Bitacora\Logger::log([
    'entidad' => 'custom',
    'entidad_id' => $id,
    'accion' => 'accion_personalizada',
    'valor_anterior' => $viejo,
    'valor_nuevo' => $nuevo,
    'motivo' => 'motivo del cambio',
    'usuario' => get_current_user_id()
]);
```

## Historial de Versiones

### 1.0.0
- Versión inicial
- CPTs del dominio legislativo
- Taxonomías de clasificación
- Sistema de workflow de estados
- Bitácora de auditoría propia
- Capacidades del producto

## Soporte

Para soporte y documentación, visitar el repositorio del proyecto GOBi o contactar al equipo de desarrollo.

## Licencia

MIT License - ver archivo LICENSE para detalles.
