# Arquitectura de REEN MONEY

REEN MONEY será inicialmente un monolito modular. Todas las interfaces compartirán el mismo backend central, pero los módulos conservarán límites de responsabilidad claros.

```text
REEN MONEY ECOSYSTEM
       |
       +-------------------+
       |                   |
 ADMIN / WORKSPACE      MOBILE
       |                   |
       +------- API REST ---+
                       |
               REEN MONEY CORE
                       |
       +---------------+---------------+
       |               |               |
  Seguridad       Finanzas         Operaciones
       |               |               |
       +---------------+---------------+
                       |
                  REEN MONEY CONNECT
                       |
       +-------+-------+-------+-------+
     Ministerios Supermercados Empresas Proveedores

                       |
                     MySQL 8
```

## Frontend y backend

El backend expone una API REST para las aplicaciones web y móviles. Los módulos pueden comunicarse entre sí mediante servicios y puertos internos; no se permite que una interfaz acceda directamente a tablas de otro dominio.

## Dominios principales

- `identity`: usuarios, credenciales, sesiones y autenticación.
- `organization`: departamentos, empleados, cargos, roles y permisos.
- `customers`: clientes y datos de relación.
- `accounts`: cuentas, billeteras y tipos de cuenta.
- `ledger`: cuentas contables y movimientos.
- `transactions`: operaciones financieras y idempotencia.
- `payments`: pagos, facturas y órdenes de pago.
- `transfers`: transferencias entre cuentas.
- `connect`: instituciones, comercios, adaptadores y conciliación.
- `workspace`: tareas, calendario, documentos y comunicación interna.
- `risk`, `compliance`, `audit` y `notifications`: controles transversales.

## Reglas transversales

1. Las operaciones financieras usan transacciones de base de datos y bloqueos apropiados.
2. Toda operación tiene identificador único e idempotency key.
3. Los saldos deben derivarse y conciliarse con movimientos contables.
4. Las confirmaciones de proveedores externos deben verificar su firma o mecanismo autorizado.
5. Las acciones sensibles requieren permisos RBAC y, cuando corresponda, doble factor.
6. Los registros financieros confirmados no se eliminan.

## Fase 1

La fase inicial comprende:

1. Modelo de datos de identidad y organización.
2. Clientes y cuentas.
3. Operaciones financieras y contabilidad.
4. Facturas, instituciones y órdenes de pago.
5. Workspace, auditoría y seguridad.
