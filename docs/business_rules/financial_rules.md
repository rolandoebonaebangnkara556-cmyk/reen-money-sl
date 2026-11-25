# REEN MONEY: restricciones financieras iniciales

Estas reglas deberán implementarse en servicios y transacciones de base de datos, no solo en la interfaz.

## Operaciones

1. Toda operación financiera exige una `idempotency_key` única.
2. El motor verifica saldo disponible dentro de una transacción ACID.
3. La cuenta y el saldo se bloquean o se controlan mediante una versión optimista.
4. Se crean todos los movimientos contables antes de confirmar la operación.
5. La suma de débitos debe ser igual a la suma de créditos.
6. Una operación confirmada no se elimina ni se modifica.
7. Una reversión genera una nueva operación y asientos compensatorios.
8. Las órdenes de pago externas pueden quedar en estado `UNKNOWN` hasta la conciliación.
9. Las respuestas externas se identifican por referencia externa y payload protegido.
10. Una integración nunca modifica directamente cuentas o asientos contables.

## Workspace

1. Una tarea no concede permisos financieros.
2. Una aprobación puede modificar un estado operativo, pero no una cuenta.
3. Los documentos financieros usan permisos explícitos independientes de RBAC.
4. El rol de secretaria no hereda permisos del director que apoya.
5. Una aprobación financiera debe exigir el permiso específico y puede requerir MFA.

## Auditoría

1. Las acciones administrativas generan `audit_events`.
2. Los cambios sensibles guardan estado anterior y posterior.
3. Los eventos son append-only.
4. El usuario técnico no modifica el histórico sin trazabilidad.

## Integraciones

1. Los adaptadores implementan una interfaz común.
2. Los secretos y credenciales no se almacenan en texto plano.
3. Los webhooks requieren validación de procedencia y firma cuando el proveedor lo soporte.
4. Las respuestas deben ser reintentables e idempotentes.
5. Los procesos de conciliación deben relacionar facturas, órdenes, intentos y transacciones.
