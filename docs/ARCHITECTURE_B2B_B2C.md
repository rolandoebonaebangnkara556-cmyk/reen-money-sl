# 🏢🤝 REEN Money - Arquitectura Dual B2B + B2C

## 1. Visión General de la Plataforma

```
┌─────────────────────────────────────────────────────────────────┐
│                    REEN MONEY FINTECH                           │
├─────────────────────────┬─────────────────────────────────────┤
│                         │                                       │
│   B2B (BACKEND)         │         B2C (FRONTEND)               │
│   Internal Platform     │       Customer Platform              │
│                         │                                       │
│ • Gestión Interna       │   • Aplicación Mobile (iOS/Android) │
│ • 8 Niveles de Roles    │   • Web Personal (PWA)              │
│ • Departamentos         │   • Pagos de Facturas               │
│ • Aprobaciones          │   • Transferencias P2P              │
│ • Auditoría             │   • Recargas Móviles                │
│ • Reportes              │   • Pagos a Empresas                │
│ • Compliance            │   • Gestión de Tarjetas             │
│                         │   • Historial de Transacciones      │
│                         │   • Soporte al Cliente              │
│                         │                                       │
└─────────────────────────┴─────────────────────────────────────┘

                          ↓ SHARED API ↓
                    
                    ┌─────────────────┐
                    │  Backend Core   │
                    │   (Laravel 11)  │
                    ├─────────────────┤
                    │  • Database     │
                    │  • Auth         │
                    │  • API REST     │
                    │  • Webhooks     │
                    │  • Processing   │
                    └─────────────────┘
                          ↓
            ┌─────────────────────────────┐
            │   Servicios Externos        │
            ├─────────────────────────────┤
            │ • Gateways de Pago          │
            │ • Operadores Móviles        │
            │ • Proveedores de Facturas   │
            │ • SMS/Email                 │
            │ • KYC/AML                   │
            │ • CEMAC Regulators          │
            └─────────────────────────────┘
```

---

## 2. Matriz de Usuarios y Roles Completa

### **B2B - Usuarios Internos**
```
Nivel 1: Admin Global
Nivel 2: Director de Departamento
Nivel 3: Responsable de Área
Nivel 4: Adjunto Senior
Nivel 5: Adjunto Junior
Nivel 6: Operador General
Nivel 7: Support/Compliance Officer
```

### **B2C - Usuarios Finales**

```
┌───────────────────────────────────��─────────────────────────┐
│                    USUARIOS FINALES                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  Nivel 8: Cliente Individual (Usuario de Consola)          │
│  ├─ Verificación KYC Nivel 1 (Básica)                     │
│  │  └─ Límites: XAF 50,000/día                            │
│  │                                                         │
│  ├─ Verificación KYC Nivel 2 (Intermedia)                 │
│  │  └─ Límites: XAF 500,000/día                           │
│  │                                                         │
│  └─ Verificación KYC Nivel 3 (Premium)                    │
│     └─ Límites: XAF 2,000,000/día                         │
│                                                             │
│  Nivel 9: Empresa/Negocio Local (Merchant)                 │
│  ├─ Verificación KYC Empresarial                          │
│  ├─ Cuenta Comercial                                       │
│  ├─ Recepción de Pagos                                    │
│  ├─ Facturación                                           │
│  ├─ Reportes de Ventas                                    │
│  └─ Dashboard Merchant                                     │
│                                                             │
│  Nivel 10: Proveedor de Servicios (API Partner)           │
│  ├─ Integración API                                       │
│  ├─ Webhooks                                              │
│  ├─ Rate Limiting                                         │
│  └─ Credenciales OAuth2                                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 3. Casos de Uso B2C - Usuarios Finales

### **3.1 Pago de Facturas**

```
Usuario Final abre app REEN Money
        ↓
Selecciona "Pagar Factura"
        ↓
Ingresa número de factura o escanea QR
        ↓
Sistema busca en base de datos de facturas
        ├─ SONATEL (telefonía)
        ├─ ENEO (electricidad)
        ├─ CAMWATER (agua)
        ├─ Bancos (préstamos)
        ├─ Seguros
        └─ Otros proveedores registrados
        ↓
Muestra monto adeudado
        ↓
Elige método de pago:
        ├─ Saldo REEN Money
        ├─ Tarjeta de débito
        ├─ Transferencia bancaria
        └─ Mobile Money Partner
        ↓
Ingresa PIN/2FA
        ↓
Procesa pago
        ↓
Genera recibo
        ↓
Notifica al proveedor
        ↓
Actualiza estado en plataforma del proveedor
```

### **3.2 Transferencias P2P (Usuario a Usuario)**

```
Usuario A abre REEN Money
        ↓
Selecciona "Enviar Dinero"
        ↓
Ingresa número de teléfono de Usuario B
        ↓
Sistema verifica:
        ├─ Usuario B existe en REEN Money
        ├─ Usuario A tiene saldo suficiente
        ├─ No hay alertas de fraude
        └─ Límites diarios no superados
        ↓
Muestra nombre de Usuario B
        ↓
Usuario A ingresa monto y concepto
        ↓
Confirma con PIN/2FA
        ↓
Sistema ejecuta transacción:
        ├─ Debita de Cuenta A
        ├─ Acredita Cuenta B
        ├─ Registra en audit_logs
        └─ Genera receipts
        ↓
Usuario A recibe comprobante
        ↓
Usuario B recibe notificaci��n SMS/Push
```

### **3.3 Pagos a Empresas Locales**

```
Usuario entra a restaurante/tienda
        ↓
Empresa muestra código QR (con número único)
        ↓
Usuario abre REEN Money
        ↓
Escanea QR
        ↓
Sistema decodifica:
        ├─ merchant_id
        ├─ monto (puede o no estar preestablecido)
        └─ factura_number (opcional)
        ↓
Muestra nombre empresa + monto
        ↓
Usuario confirma pago
        ↓
Ingresa PIN/2FA
        ↓
Sistema procesa:
        ├─ Valida saldo
        ├─ Ejecuta transacción
        ├─ Notifica a empresa en tiempo real
        └─ Genera recibo
        ↓
Usuario ve confirmación
        ↓
Empresa ve pago acreditado
```

### **3.4 Recargas Móviles**

```
Usuario selecciona "Recargas Móviles"
        ↓
Elige operador:
        ├─ VIETTEL
        ├─ NEXTTEL
        ├─ MTN
        └─ Orange
        ↓
Ingresa número a recargar (propio o de otros)
        ↓
Elige monto (presets: 1K, 2K, 5K, 10K, etc)
        ↓
Confirma pago
        ↓
Sistema contacta API de operador
        ↓
Operador acredita saldo
        ↓
Usuario recibe confirmación
```

---

## 4. Estructura de Base de Datos Dual

### **Tablas B2B (Empleados y Operaciones Internas)**

```sql
-- Organización
departments
users_b2b          -- Empleados internos
user_roles_b2b     -- Roles internos
areas
transaction_limits_b2b
internal_transactions
```

### **Tablas B2C (Usuarios Finales)**

```sql
-- Usuarios Finales
users_b2c
kyc_profiles       -- Niveles de verificación
kyc_documents      -- Documentos subidos
user_addresses     -- Múltiples direcciones

-- Cuentas y Billeteras
customer_accounts
wallets
cards              -- Tarjetas vinculadas

-- Transacciones Finales
customer_transactions
p2p_transfers
bill_payments
merchant_payments
recharge_payments

-- Merchants (Empresas)
merchants
merchant_accounts
merchant_transactions
merchant_invoice_providers

-- Notificaciones y Comunicaciones
notifications
sms_logs
email_logs
push_notifications

-- Fraud & Security
fraud_alerts
suspicious_transactions
kyc_alerts
aml_flags

-- Proveedores Externos
bill_providers      -- SONATEL, ENEO, etc
invoice_database   -- Facturas
mobile_operators
payment_gateways
```

---

## 5. Tablas Detalladas - Modelo de Datos

### **users_b2c (Clientes Finales)**

```sql
CREATE TABLE users_b2c (
    id BIGINT PRIMARY KEY,
    phone_number VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    id_document_type ENUM('NATIONAL_ID', 'PASSPORT', 'DRIVER_LICENSE'),
    id_document_number VARCHAR(50) UNIQUE,
    date_of_birth DATE,
    nationality VARCHAR(50),
    occupation VARCHAR(100),
    
    -- KYC Status
    kyc_level INT DEFAULT 1,  -- 1: Básica, 2: Intermedia, 3: Premium
    kyc_status ENUM('PENDING', 'APPROVED', 'REJECTED', 'SUSPENDED'),
    kyc_verified_at TIMESTAMP,
    
    -- Security
    password_hash VARCHAR(255),
    pin_hash VARCHAR(255),
    two_factor_enabled BOOLEAN DEFAULT false,
    two_factor_method ENUM('TOTP', 'SMS', 'EMAIL'),
    
    -- Status
    status ENUM('ACTIVE', 'INACTIVE', 'BLOCKED', 'SUSPENDED'),
    last_login_at TIMESTAMP,
    
    -- Compliance
    politically_exposed BOOLEAN DEFAULT false,
    aml_status ENUM('CLEAR', 'PENDING', 'FLAGGED', 'BLOCKED'),
    aml_reviewed_at TIMESTAMP,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **kyc_profiles**

```sql
CREATE TABLE kyc_profiles (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY REFERENCES users_b2c(id),
    kyc_level INT,  -- 1, 2, 3
    
    -- Nivel 1: Verificación Básica (teléfono + email)
    phone_verified BOOLEAN,
    email_verified BOOLEAN,
    
    -- Nivel 2: Verificación Intermedia (documento + dirección)
    document_verified BOOLEAN,
    address_verified BOOLEAN,
    document_path VARCHAR(255),
    document_verified_at TIMESTAMP,
    
    -- Nivel 3: Verificación Premium (biometría + información empresarial)
    biometric_verified BOOLEAN,
    biometric_verified_at TIMESTAMP,
    source_of_funds VARCHAR(255),
    business_registration_number VARCHAR(50),
    
    -- Límites según nivel
    daily_limit DECIMAL(15,2),
    monthly_limit DECIMAL(15,2),
    per_transaction_limit DECIMAL(15,2),
    
    verified_by_user_id BIGINT FOREIGN KEY REFERENCES users_b2b(id),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **customer_wallets**

```sql
CREATE TABLE customer_wallets (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY REFERENCES users_b2c(id),
    currency VARCHAR(3) DEFAULT 'XAF',
    balance DECIMAL(18,2) DEFAULT 0,
    available_balance DECIMAL(18,2) DEFAULT 0,
    blocked_balance DECIMAL(18,2) DEFAULT 0,
    
    -- Limites
    daily_limit DECIMAL(15,2),
    daily_spent DECIMAL(15,2) DEFAULT 0,
    monthly_spent DECIMAL(15,2) DEFAULT 0,
    
    status ENUM('ACTIVE', 'FROZEN', 'CLOSED'),
    last_transaction_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **customer_transactions**

```sql
CREATE TABLE customer_transactions (
    id BIGINT PRIMARY KEY,
    transaction_reference VARCHAR(50) UNIQUE,
    user_id BIGINT FOREIGN KEY REFERENCES users_b2c(id),
    
    -- Tipo de transacción
    type ENUM(
        'P2P_TRANSFER',
        'BILL_PAYMENT', 
        'MERCHANT_PAYMENT',
        'RECHARGE_MOBILE',
        'CARD_TOPUP',
        'WITHDRAWAL',
        'DEPOSIT',
        'REVERSAL'
    ),
    
    -- Monto
    amount DECIMAL(18,2),
    currency VARCHAR(3),
    fee DECIMAL(18,2),
    net_amount DECIMAL(18,2),  -- amount - fee
    
    -- Detalles según tipo
    recipient_id BIGINT,  -- Para P2P
    merchant_id BIGINT,   -- Para pagos a empresas
    bill_provider_id INT, -- Para pagos de facturas
    bill_number VARCHAR(100),
    mobile_operator_id INT, -- Para recargas
    recipient_phone VARCHAR(20),
    
    -- Status
    status ENUM('PENDING', 'PROCESSING', 'COMPLETED', 'FAILED', 'REVERSED'),
    failure_reason VARCHAR(255),
    
    -- Fraud Check
    fraud_score INT DEFAULT 0,  -- 0-100
    fraud_status ENUM('CLEAN', 'PENDING_REVIEW', 'FLAGGED', 'BLOCKED'),
    
    -- Auditoría
    ip_address VARCHAR(45),
    device_id VARCHAR(255),
    user_agent TEXT,
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    completed_at TIMESTAMP
);
```

### **merchants**

```sql
CREATE TABLE merchants (
    id BIGINT PRIMARY KEY,
    business_name VARCHAR(255) NOT NULL,
    business_type ENUM('RESTAURANT', 'RETAIL', 'UTILITY', 'TELECOM', 'INSURANCE', 'OTHER'),
    
    -- Contacto
    contact_person VARCHAR(255),
    contact_email VARCHAR(255),
    contact_phone VARCHAR(20),
    
    -- Ubicación
    country_code VARCHAR(2),
    city VARCHAR(100),
    address TEXT,
    
    -- Verificación
    registration_number VARCHAR(50) UNIQUE,
    tax_id VARCHAR(50),
    kyc_status ENUM('PENDING', 'APPROVED', 'REJECTED'),
    kyc_verified_at TIMESTAMP,
    
    -- Cuentas
    merchant_account_id BIGINT FOREIGN KEY,
    api_key VARCHAR(255),
    api_secret VARCHAR(255),
    webhook_url VARCHAR(255),
    
    -- Status
    status ENUM('ACTIVE', 'INACTIVE', 'SUSPENDED'),
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### **bill_providers**

```sql
CREATE TABLE bill_providers (
    id INT PRIMARY KEY,
    name VARCHAR(255),  -- SONATEL, ENEO, CAMWATER, etc
    code VARCHAR(20) UNIQUE,
    category ENUM('TELECOM', 'ELECTRICITY', 'WATER', 'INSURANCE', 'LOANS', 'OTHER'),
    
    -- Integración
    api_url VARCHAR(255),
    api_key VARCHAR(255),
    api_secret VARCHAR(255),
    
    -- Búsqueda de facturas
    supports_bill_lookup BOOLEAN,
    bill_lookup_method ENUM('API', 'DATABASE', 'MANUAL'),
    
    status ENUM('ACTIVE', 'INACTIVE'),
    created_at TIMESTAMP
);
```

### **fraud_alerts**

```sql
CREATE TABLE fraud_alerts (
    id BIGINT PRIMARY KEY,
    transaction_id BIGINT FOREIGN KEY REFERENCES customer_transactions(id),
    user_id BIGINT FOREIGN KEY REFERENCES users_b2c(id),
    
    alert_type ENUM(
        'UNUSUAL_AMOUNT',
        'RAPID_TRANSACTIONS',
        'LOCATION_CHANGE',
        'NEW_DEVICE',
        'VELOCITY_CHECK_FAILED',
        'BLACKLIST_MATCH',
        'DUPLICATE_TRANSACTION',
        'HIGH_RISK_COUNTRY',
        'SANCTIONED_ENTITY'
    ),
    
    risk_score INT,  -- 0-100
    description TEXT,
    
    status ENUM('OPEN', 'INVESTIGATING', 'CONFIRMED', 'FALSE_POSITIVE'),
    investigated_by_user_id BIGINT,
    action_taken VARCHAR(255),
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 6. API Endpoints - Estructura Completa

### **B2B Internal API** (`/api/internal/v1/`)
```
POST   /auth/login
POST   /auth/logout
POST   /auth/refresh

GET    /dashboard
GET    /departments
GET    /areas/{area_id}
GET    /users

GET    /my-team
GET    /pending-approvals
PUT    /transactions/{id}/approve
PUT    /transactions/{id}/reject

GET    /transactions
GET    /audit-logs
GET    /reports

POST   /users (crear usuarios internos)
PUT    /users/{id}/roles
DELETE /users/{id}
```

### **B2C Customer API** (`/api/customers/v1/`)
```
POST   /auth/register
POST   /auth/login
POST   /auth/verify-phone
POST   /auth/verify-pin

GET    /me
PUT    /me (actualizar perfil)
GET    /kyc-status

GET    /wallet
GET    /transactions
GET    /transactions/{id}

POST   /transfer/p2p
POST   /payments/bill
POST   /payments/merchant
POST   /payments/recharge

GET    /merchants (buscar negocios)
GET    /bill-providers (listar proveedores)
GET    /bills/search (buscar facturas)

POST   /cards/link
GET    /cards
DELETE /cards/{id}

GET    /support-tickets
POST   /support-tickets
```

### **Merchant API** (`/api/merchants/v1/`)
```
POST   /auth/login (merchant)
GET    /dashboard
GET    /transactions
GET    /income-summary

POST   /webhook/payment-received
GET    /invoice-templates
POST   /invoices/create
GET    /reports/sales

PUT    /settings
GET    /api-keys
POST   /api-keys/generate
```

### **Payment Gateway Webhooks** (`/api/webhooks/`)
```
POST   /card-provider/payment-update
POST   /mobile-operator/recharge-status
POST   /bill-provider/payment-confirmation
```

---

## 7. Flujo de Seguridad - Página por Página

### **B2C: Registro de Usuario Final**

```
PANTALLA 1: Básica (KYC Nivel 1)
├─ Número de teléfono
├─ Email
├─ Nombre completo
└─ Crear PIN (6 dígitos)
        ↓
Sistema valida:
├─ Teléfono único
├─ Email único
├─ PIN fuerte
└─ No está en lista negra
        ↓
Envía código OTP a teléfono
        ↓
PANTALLA 2: Verificación OTP
├─ Ingresa código OTP
└─ Confirma
        ↓
Usuario creado con LÍMITE: XAF 50K/día
        ↓

PARA KYC NIVEL 2 (Verificación Intermedia):
├─ Subir documento de identidad (foto)
├─ Capturar selfie
├─ Validar datos del documento
└─ Confirmar dirección
        ↓
Sistema procesa con IA/Manual:
├─ Valida documento
├─ Valida biometría
├─ Valida dirección
└─ Aprueba o rechaza
        ↓
Si aprueba → LÍMITE: XAF 500K/día
        ↓

PARA KYC NIVEL 3 (Premium):
├─ Video llamada de verificación
├─ Preguntas de seguridad
├─ Información de ingresos
└─ Propósito de la cuenta
        ↓
Si aprueba → LÍMITE: XAF 2M/día
```

### **B2C: Pago de Factura (Flujo Principal)**

```
PANTALLA 1: Seleccionar Proveedor
┌─────────────────────┐
│ ¿Qué deseas pagar?  │
├─────────────────────┤
│ [🔔 SONATEL]       │
│ [⚡ ENEO]          │
│ [💧 CAMWATER]      │
│ [🏥 SEGUROS]       │
│ [📱 NEXTTEL]       │
│ [➕ Otro Proveedor]│
└─────────────────────┘
        ↓
PANTALLA 2: Buscar Factura
┌─────────────────────┐
│ Ingresa # Factura   │
│ o tu cuenta:        │
├─────────────────────┤
│ [📱 06XXXXXXXX]    │
│ [🔍 Buscar]        │
└─────────────────────┘
        ↓
Sistema busca en:
├─ Base de datos local caché
├─ API del proveedor
└─ Si no encuentra → búsqueda manual
        ↓
PANTALLA 3: Confirmación
┌─────────────────────────────┐
│ SONATEL Camerún            │
├─────────────────────────��───┤
│ Número: 237 691 234 567    │
│ Deuda Anterior: XAF 15,000 │
│ Monto Factura: XAF 25,000  │
│ Comisión REEN: XAF 500     │
│ Total a Pagar: XAF 25,500  │
│                             │
│ [Cambiar] [Continuar]      │
└─────────────────────────────┘
        ↓
PANTALLA 4: Método de Pago
┌──────────────────────────┐
│ Elige forma de pago:     │
├──────────────────────────┤
│ [✓] Saldo REEN Money     │
│ [ ] Tarjeta de Débito    │
│ [ ] Transferencia Bancaria│
│ [ ] Mobile Money Partner  │
└──────────────────────────┘
        ↓
PANTALLA 5: Confirmación de PIN
┌─────────────────────┐
│ Ingresa tu PIN:     │
│ [• • • • • •]      │
│                     │
│ [Cancelar] [OK]    │
└─────────────────────┘
        ↓
Sistema ejecuta:
├─ Valida saldo
├─ Valida límites diarios
├─ Valida fraude
├─ Ejecuta transacción
└─ Notifica proveedor
        ↓
PANTALLA 6: Confirmación
┌──────────────────────────┐
│ ✓ Pago Procesado        │
├──────────────────────────┤
│ Referencia: REN-20241015 │
│ Monto: XAF 25,500        │
│ Proveedor: SONATEL       │
│ Estado: COMPLETADO       │
│                          │
│ [Descargar Recibo]      │
│ [Compartir]             │
│ [Volver]                │
└──────────────────────────┘
```

### **B2C: Pago a Empresa Local (QR)**

```
PANTALLA 1: Escanear QR
┌─────────────────────┐
│ Punto de Venta:     │
│ Escanea QR          │
│                     │
│ [📷 Abrir Cámara]  │
│ [➕ Código Manual]  │
└─────────────────────┘
        ↓
Sistema decodifica QR:
├─ merchant_id: 12345
├─ amount: 45,000 (puede o no estar)
└─ invoice_id: INV-2024-001
        ↓
PANTALLA 2: Confirmación del Pago
┌──────────────────────────┐
│ 🍔 RESTAURANT "EL MONTE" │
├────────────────────��─────┤
│ Ubicación: Yaundé        │
│ Monto: XAF 45,000        │
│ Concepto: Comida         │
│ Comisión: XAF 225        │
│ Total: XAF 45,225        │
│                          │
│ [Editar Monto] [OK]     │
└──────────────────────────┘
        ↓
PANTALLA 3: Confirmar PIN
┌─────────────────────┐
│ Ingresa tu PIN:     │
│ [• • • • • •]      │
└─────────────────────┘
        ↓
Sistema procesa:
├─ Valida saldo
├─ Valida límites
├─ Bloquea dinero
├─ Notifica a merchant
├─ Espera confirmación
└─ Completa o revierte
        ↓
PANTALLA 4: Recibo
┌──────────────────────┐
│ ✓ Pago Exitoso     │
├──────────────────────┤
│ Ref: REN-QR-2024    │
│ Empresa: EL MONTE   │
│ Monto: XAF 45,000   │
│ Estado: COMPLETADO  │
│ Hora: 14:35         │
│                     │
│ [Recibo] [Volver]   │
└──────────────────────┘
```

---

## 8. Seguridad Multinivel para B2C

### **Validaciones por Transacción**

```
1. VALIDACIÓN DE USUARIO
   ├─ Usuario existe
   ├─ Cuenta activa (no bloqueada)
   ├─ KYC verificado
   ├─ No tiene alertas abiertas
   └─ 2FA validado

2. VALIDACIÓN DE FONDOS
   ├─ Saldo suficiente
   ├─ No hay bloques de dinero
   ├─ Límites diarios no superados
   └─ Límites mensuales no superados

3. VALIDACIÓN DE FRAUDE
   ├─ Análisis de velocidad (múltiples tx rápido)
   ├─ Análisis de monto (inusual)
   ├─ Análisis de patrón (deviacióndel patrón normal)
   ├─ Análisis de ubicación (cambio rápido)
   ├─ Análisis de dispositivo (nuevo dispositivo)
   └─ Blacklist/Whitelist checks

4. VALIDACIÓN DE CUMPLIMIENTO
   ├─ Receptor no está en lista de fraude
   ├─ Receptor no está en lista de sanciones
   ├─ Transacción no es estructuración (montos pequeños repetidos)
   └─ AML flags

5. ANÁLISIS DE RIESGO
   ├─ Score de riesgo < 50
   ├─ Si score entre 50-70 → requiere revisión
   └─ Si score > 70 → rechazar automáticamente

6. EJECUCIÓN
   ├─ Bloquea fondos
   ├─ Ejecuta transacción
   ├─ Notifica a ambas partes
   └─ Registra en audit logs
```

---

## 9. Notificaciones y Comunicaciones

### **SMS Notifications**
```
- Bienvenida al registro
- Código OTP para autenticación
- Confirmación de transacción
- Alerta de fraude
- Cambio de contraseña
- Nuevo dispositivo conectado
```

### **Push Notifications**
```
- Transacción completada
- Solicitud de aprobación (para empleados)
- Alerta de seguridad
- Oferta y promociones
- Nuevo mensaje de soporte
```

### **Email**
```
- Recibos detallados
- Estados de cuenta
- Alertas de seguridad críticas
- Confirmación de cambios de cuenta
- Información regulatoria
```

---

## 10. Casos de Uso Avanzados

### **Caso 1: Usuario quiere pagar factura sin conocer el monto exacto**
```
1. Ingresa número de cuenta
2. Sistema busca en BD de facturas
3. Si no existe en caché → consulta API del proveedor
4. Si proveedor no tiene API → envía SMS pidiendo al usuario
5. Usuario responde con monto
6. Sistema procesa pago
```

### **Caso 2: Usuario intenta pago sospechoso (fraude)**
```
1. Transacción llega con score de riesgo 75/100
2. Sistema bloquea automáticamente
3. Notifica al usuario: "Transacción bloqueada por seguridad"
4. Le da opción de "Verificar por video llamada"
5. Si verifica → aprueba transacción
6. Si no verifica → rechaza después de 24h
7. Registra en audit log para análisis posterior
```

### **Caso 3: Merchant recibe pago y ve dinero en tiempo real**
```
1. Usuario escanea QR en punto de venta
2. Merchant ve notificación: "Pago de XAF 45,000 recibido de Usuario X"
3. En 2 segundos el dinero está en su cuenta
4. Merchant ve recibirse el dinero
5. Sistema genera comprobante automático
6. Dinero puede ser retirado o usado para pagar sus deudas
```

---

## 11. Compliance CEMAC/Guinea Ecuatorial para B2C

### **KYC Tiers**
```
Nivel 1 (Básica):
├─ Teléfono verificado
├─ Límite: XAF 50,000/día
└─ Tiempo de aprobación: Inmediato

Nivel 2 (Intermedia):
├─ Documento de identidad verificado
├─ Biometría capturada
├─ Dirección verificada
├─ Límite: XAF 500,000/día
└─ Tiempo de aprobación: 24-48 horas

Nivel 3 (Premium):
├─ Video llamada
├─ Verificación de ingresos
├─ Antecedentes financieros
├─ Límite: XAF 2,000,000/día
└─ Tiempo de aprobación: 5-7 días
```

### **AML - Análisis de Patrones**
```
Alertas automáticas si:
├─ Transacciones > XAF 5M requieren documento de origen de fondos
├─ Patrón de estructuración (múltiples tx de XAF 49,999)
├─ Cambios súbitos de volumen de transacciones
├─ Transferencias a países de alto riesgo
├─ Nombre aparece en lista de sanciones
└─ Cuenta abierta y luego transacción grande inmediatamente
```

### **Retención de Datos**
```
Retener por 7 años (según CEMAC):
├─ Documentos KYC
├─ Todos los audit logs
├─ Transacciones completadas
├─ Documentos de identidad escaneados
├─ Fotografías de biometría
└─ Registros de comunicaciones
```

---

## 12. Monetización para REEN Money

```
Comisiones por transacción:
├─ P2P Transfers: 1-2% (mín XAF 500)
├─ Bill Payments: 1.5-2% (mín XAF 750)
├─ Merchant Payments: 2-2.5% (paga el negocio)
├─ Recargas Móviles: 2% (mín XAF 100)
├─ Card Topup: 1.5% (mín XAF 500)
└─ Withdrawal: 2-3% (mín XAF 1,000)

Suscripción Premium (opcional):
├─ XAF 2,000/mes
├─ Comisiones reducidas (0.5%)
├─ Límites aumentados
├─ Soporte prioritario
└─ Cashback de 0.5%
```

