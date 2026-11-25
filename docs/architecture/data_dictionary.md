# Diccionario de entidades actualizado

## users

Identidad técnica utilizada por los componentes web y móvil.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| username | VARCHAR(100) | UNIQUE, NOT NULL |
| email | VARCHAR(255) | UNIQUE, NOT NULL |
| password_hash | VARCHAR(255) | NOT NULL |
| status | VARCHAR(30) | NOT NULL |
| created_at | TIMESTAMP(6) | NOT NULL |
| updated_at | TIMESTAMP(6) | NOT NULL |

## user_sessions

Sesiones activas y anuladas del usuario.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| user_id | CHAR(36) | FK, NOT NULL |
| refresh_token_hash | VARCHAR(255) | UNIQUE, NOT NULL |
| ip_address | VARCHAR(45) | NULL |
| user_agent | VARCHAR(500) | NULL |
| expires_at | TIMESTAMP(6) | NOT NULL |
| revoked_at | TIMESTAMP(6) | NULL |

## employees

Relación laboral entre usuario, departamento y cargo.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| user_id | CHAR(36) | UNIQUE, FK, NOT NULL |
| department_id | CHAR(36) | FK, NOT NULL |
| position_id | CHAR(36) | FK, NOT NULL |
| employee_number | VARCHAR(30) | UNIQUE, NOT NULL |
| status | VARCHAR(30) | NOT NULL |

## accounts

Cuenta financiera o billetera central.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| account_number | VARCHAR(40) | UNIQUE, NOT NULL |
| account_type | VARCHAR(30) | NOT NULL |
| currency_code | CHAR(3) | NOT NULL |
| status | VARCHAR(30) | NOT NULL |
| version | BIGINT | NOT NULL |

## transactions

Cabecera inmutable de una operación financiera.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| transaction_number | VARCHAR(40) | UNIQUE, NOT NULL |
| idempotency_key | VARCHAR(100) | UNIQUE, NOT NULL |
| transaction_type | VARCHAR(40) | NOT NULL |
| status | VARCHAR(30) | NOT NULL |
| amount | DECIMAL(20,4) | NOT NULL |
| currency_code | CHAR(3) | NOT NULL |
| reversal_of_id | CHAR(36) | FK, NULL |
| created_at | TIMESTAMP(6) | NOT NULL |
| confirmed_at | TIMESTAMP(6) | NULL |

## ledger_entries

Movimiento contable de partida doble.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| transaction_id | CHAR(36) | FK, NOT NULL |
| ledger_account_id | CHAR(36) | FK, NOT NULL |
| account_id | CHAR(36) | FK, NULL |
| entry_side | VARCHAR(6) | DEBIT o CREDIT |
| amount | DECIMAL(20,4) | NOT NULL |

## bills

Factura consultada o recibida desde una institución externa.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| institution_id | CHAR(36) | FK, NOT NULL |
| institution_service_id | CHAR(36) | FK, NULL |
| external_reference | VARCHAR(100) | NOT NULL |
| customer_reference | VARCHAR(100) | NULL |
| amount | DECIMAL(20,4) | NOT NULL |
| currency_code | CHAR(3) | NOT NULL |
| status | VARCHAR(30) | NOT NULL |

## payment_orders

Intención de pago creada por el cliente o por un canal autorizado.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| bill_id | CHAR(36) | FK, NOT NULL |
| customer_account_id | CHAR(36) | FK, NOT NULL |
| idempotency_key | VARCHAR(100) | UNIQUE, NOT NULL |
| amount | DECIMAL(20,4) | NOT NULL |
| status | VARCHAR(30) | NOT NULL |
| transaction_id | CHAR(36) | UNIQUE, FK, NULL |

## workspace_tasks

Tarea operativa asignada dentro de REEN WORKSPACE.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| department_id | CHAR(36) | FK, NOT NULL |
| assigned_to_id | CHAR(36) | FK, NOT NULL |
| created_by_id | CHAR(36) | FK, NOT NULL |
| title | VARCHAR(255) | NOT NULL |
| status | VARCHAR(30) | NOT NULL |
| due_at | TIMESTAMP(6) | NULL |

## workspace_requests

Solicitud interna que puede pasar por aprobaciones.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| requester_employee_id | CHAR(36) | FK, NOT NULL |
| department_id | CHAR(36) | FK, NOT NULL |
| request_type | VARCHAR(50) | NOT NULL |
| status | VARCHAR(30) | NOT NULL |
| payload_json | JSON | NULL |

## audit_events

Registro append-only de acciones administrativas y financieras.

| Campo | Tipo | Restricción |
|---|---|---|
| id | CHAR(36) | PK |
| actor_user_id | CHAR(36) | FK, NULL |
| action | VARCHAR(100) | NOT NULL |
| entity_type | VARCHAR(100) | NOT NULL |
| entity_id | CHAR(36) | NOT NULL |
| before_json | JSON | NULL |
| after_json | JSON | NULL |
| created_at | TIMESTAMP(6) | NOT NULL |
