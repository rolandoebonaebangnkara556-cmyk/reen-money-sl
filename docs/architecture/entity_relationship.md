# Modelo Entidad-Relación actualizado

Este modelo distingue claramente entre las identidades, las relaciones laborales, las aplicaciones de trabajo y las operaciones financieras.

## Separación de responsabilidades

- **Usuario**: identidad de acceso y sesiones.
- **Empleado**: vínculo laboral de un usuario.
- **Departamento y cargo**: ubicación organizativa del empleado.
- **Rol y permiso**: autorizaciones independientes del cargo.
- **Workspace**: módulo de interfaz y operación diaria.
- **Core**: cuentas, operaciones financieras y contabilidad.
- **Connect**: instituciones, adaptadores y pagos externos.

## Diagrama principal

```mermaid
erDiagram
    USER ||--o| CUSTOMER : "puede ser"
    USER ||--o| EMPLOYEE : "puede ser"
    EMPLOYEE }o--|| DEPARTMENT : "pertenece"
    EMPLOYEE }o--|| JOB_POSITION : "ocupa"
    USER }o--o{ ROLE : "tiene"
    ROLE }o--o{ PERMISSION : "incluye"

    USER ||--o{ USER_SESSION : "abre"
    USER ||--o{ MFA_FACTOR : "configura"

    CUSTOMER ||--o{ CUSTOMER_ACCOUNT : "titular"
    CUSTOMER_ACCOUNT }o--|| ACCOUNT : "referencia"

    ACCOUNT ||--o{ LEDGER_ENTRY : "afecta"
    TRANSACTION ||--|{ LEDGER_ENTRY : "produce"
    LEDGER_ENTRY }o--|| LEDGER_ACCOUNT : "clasifica"

    CUSTOMER ||--o{ PAYMENT_ORDER : "inicia"
    PAYMENT_ORDER }o--|| CUSTOMER_ACCOUNT : "carga"
    PAYMENT_ORDER }o--|| BILL : "paga"
    PAYMENT_ORDER ||--o| TRANSACTION : "genera"

    EXTERNAL_INSTITUTION ||--o{ INSTITUTION_SERVICE : "ofrece"
    EXTERNAL_INSTITUTION ||--o{ INTEGRATION_ADAPTER : "configura"
    EXTERNAL_INSTITUTION ||--o{ BILL : "emite"
    INTEGRATION_ADAPTER ||--o{ EXTERNAL_PAYMENT_ATTEMPT : "procesa"

    EMPLOYEE ||--o{ WORKSPACE_TASK : "asignado"
    EMPLOYEE ||--o{ MEETING_PARTICIPANT : "participa"
    EMPLOYEE ||--o{ WORKSPACE_REQUEST : "solicita"
    DEPARTMENT ||--o{ WORKSPACE_REQUEST : "recibe"

    AGENT ||--o{ AGENT_BRANCH : "opera"
    MERCHANT ||--o{ MERCHANT_LOCATION : "tiene"
    MERCHANT ||--o{ MERCHANT_SERVICE : "acepta"

    TRANSACTION ||--o{ AUDIT_EVENT : "es auditada"
    EMPLOYEE ||--o{ AUDIT_EVENT : "puede generar"

    USER {
        char36 id PK
        varchar username
        varchar email
        varchar status
    }
    EMPLOYEE {
        char36 id PK
        char36 user_id FK
        char36 department_id FK
        char36 position_id FK
        varchar status
    }
    CUSTOMER {
        char36 id PK
        char36 user_id FK
        varchar customer_number
        varchar status
    }
    ACCOUNT {
        char36 id PK
        varchar account_number
        char3 currency_code
        varchar status
        bigint version
    }
    TRANSACTION {
        char36 id PK
        varchar transaction_number
        varchar idempotency_key
        varchar transaction_type
        varchar status
        decimal amount
    }
    LEDGER_ENTRY {
        char36 id PK
        char36 transaction_id FK
        char36 ledger_account_id FK
        char36 account_id FK
        varchar entry_side
        decimal amount
    }
    EXTERNAL_INSTITUTION {
        char36 id PK
        varchar code
        varchar name
        varchar institution_type
        varchar status
    }
    BILL {
        char36 id PK
        char36 institution_id FK
        varchar external_reference
        decimal amount
        varchar status
    }
    PAYMENT_ORDER {
        char36 id PK
        char36 bill_id FK
        char36 customer_account_id FK
        varchar idempotency_key
        varchar status
    }
```

## Entidades principales

### Identidad y organización

- `users`
- `user_sessions`
- `mfa_factors`
- `customers`
- `employees`
- `departments`
- `job_positions`
- `roles`
- `permissions`
- `user_roles`
- `role_permissions`

Cada empleado pertenece a un departamento y ocupa un cargo, pero recibe autorización mediante roles y permisos independientes. Una secretaria podrá gestionar agenda y documentos sin heredar autorizaciones financieras.

### Clientes y cuentas

- `accounts`
- `customer_accounts`
- `account_holders` si la cuenta admite múltiples titulares
- `account_balance_snapshots`
- `account_status_history`

El saldo no se modifica directamente desde interfaces administrativas. Se concilia mediante `ledger_entries` y snapshots versionados.

### Operaciones financieras

- `transactions`
- `transaction_events`
- `ledger_accounts`
- `ledger_entries`
- `deposits`
- `withdrawals`
- `transfers`
- `payments`
- `commissions`

Toda operación tendrá:

- Identificador interno UUID.
- Número público único.
- Clave de idempotencia única.
- Estado controlado por una máquina de estados.
- Timestamp de creación y confirmación.
- Referencia opcional a la operación original que fue revertida.

### Instituciones y facturas

- `external_institutions`
- `institution_services`
- `integration_adapters`
- `bills`
- `payment_orders`
- `external_payment_attempts`
- `integration_webhooks`
- `reconciliation_runs`
- `reconciliation_items`

Cada institución puede tener varios servicios y adaptadores. El núcleo financiero no depende de una API externa concreta.

### Agentes, comercios y tarjetas

- `agents`
- `agent_branches`
- `agent_assignments`
- `merchants`
- `merchant_locations`
- `merchant_services`
- `merchant_settlement_accounts`
- `virtual_card_providers`
- `virtual_cards`

### Workspace

- `workspace_tasks`
- `workspace_requests`
- `workspace_approval_steps`
- `meetings`
- `meeting_participants`
- `workspace_documents`
- `document_permissions`
- `internal_messages`
- `message_recipients`
- `workspace_notifications`

Estas entidades pertenecen al módulo de operación diaria. No otorgan permisos financieros por sí mismas.

### Riesgos, cumplimiento y auditoría

- `risk_alerts`
- `risk_alert_actions`
- `compliance_cases`
- `audit_events`
- `notifications`
- `notification_outbox`
- `notification_dispatch_attempts`

## Convenciones

- UUID: `CHAR(36)`.
- Importes: `DECIMAL(20,4)`.
- Moneda: `CHAR(3)` siguiendo ISO 4217.
- Timestamps: `TIMESTAMP(6)` en UTC.
- Confirmaciones externas: conservar payload y respuesta sanitizada.
- Operaciones financieras: append-only.
- Correcciones: registrar una operación compensatoria, nunca borrar o modificar el original.
- Asientos contables: una transacción confirmada debe tener débitos y créditos equilibrados.
- Eliminaciones físicas: solo para borradores, datos temporales o entidades sin actividad financiera.

## Estados iniciales

- Usuario: `ACTIVE`, `LOCKED`, `DISABLED`.
- Empleado: `ACTIVE`, `LEAVE`, `TERMINATED`.
- Cuenta: `ACTIVE`, `FROZEN`, `CLOSED`.
- Operación: `PENDING`, `CONFIRMED`, `FAILED`, `REVERSED`.
- Orden de pago: `PENDING`, `CONFIRMED`, `FAILED`, `REVERSED`.
- Factura: `PENDING`, `PAID`, `CANCELLED`, `EXPIRED`.
- Integración: `ACTIVE`, `INACTIVE`, `MAINTENANCE`.
- Riesgo: `OPEN`, `UNDER_REVIEW`, `CLOSED`.
