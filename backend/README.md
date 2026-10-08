# REEN Money - Backend Core

Backend financiero para plataforma REEN Money con soporte B2B (empleados) y B2C (clientes finales).

## Stack Tecnológico

- **Framework**: Laravel 11
- **Autenticación**: Laravel Sanctum
- **Base de Datos**: MySQL/MariaDB
- **Cache**: Redis
- **Queue**: Redis + Laravel Queues
- **Testing**: PHPUnit + Pest

## Estructura del Proyecto

```
app/
├── Models/                 # Modelos de base de datos
├── Http/
│   ├── Controllers/       # Controladores por dominio
│   ├── Middleware/        # Middleware personalizado
│   ├── Requests/          # Form Requests con validaciones
│   └── Resources/         # API Resources (JSON)
├── Services/              # Lógica de negocio
│   ├── Auth/
│   ├── KYC/
│   ├── Wallet/
│   ├── Transaction/
│   ├── Approval/
│   ├── Fraud/
│   └── Audit/
├── Policies/              # Autorización por rol
├── Enums/                 # Estados y tipos
├── Rules/                 # Validaciones personalizadas
├── Jobs/                  # Colas asincrónicas
├── Events/                # Eventos de dominio
├── Listeners/             # Escuchadores de eventos
└── Traits/                # Traits reutilizables

database/
├── migrations/            # Migraciones de BD
├── seeders/               # Datos iniciales
└── factories/             # Factories para tests

routes/
├── api.php                # Rutas para API clientes
├── admin.php              # Rutas para admin interno
├── customer.php           # Rutas para clientes finales
└── merchant.php           # Rutas para merchants

tests/
├── Feature/               # Tests de integración
└── Unit/                  # Tests unitarios

config/
├── fintech.php            # Configuración financiera
├── audit.php              # Configuración de auditoría
├── permissions.php        # Matriz de permisos
└── kyc.php                # Configuración KYC
```

## Instalación

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Modelos Principales

- **User** - Usuarios (empleados y clientes)
- **Role** - Roles del sistema
- **Permission** - Permisos granulares
- **Department** - Departamentos de la empresa
- **Area** - Áreas dentro de departamentos
- **Wallet** - Billeteras de usuarios
- **Transaction** - Transacciones financieras
- **KycProfile** - Perfiles de verificación
- **AuditLog** - Registro inmutable de auditoría
- **FraudAlert** - Alertas de fraude

## API Endpoints

### Autenticación (`/api/auth`)
- `POST /login` - Login de usuario
- `POST /register` - Registro de nuevo usuario
- `POST /logout` - Cierre de sesión
- `POST /refresh-token` - Refresh de token
- `POST /verify-2fa` - Verificación 2FA

### Clientes (`/api/customers`)
- `GET /me` - Datos del usuario
- `GET /wallet` - Saldo de billetera
- `POST /transfer` - Transferencia P2P
- `POST /bill-payment` - Pago de facturas

### Admin (`/api/admin`)
- `GET /dashboard` - Dashboard ejecutivo
- `GET /departments` - Listar departamentos
- `GET /users` - Listar usuarios
- `POST /transactions/{id}/approve` - Aprobar transacción

## Testing

```bash
# Ejecutar todos los tests
php artisan test

# Con cobertura
php artisan test --coverage

# Tests específicos
php artisan test --filter=TransactionTest
```

## Compliance y Seguridad

- ✅ 2FA (TOTP + SMS)
- ✅ KYC en 3 niveles
- ✅ AML con detección de fraude
- ✅ Auditoría inmutable (7 años retención)
- ✅ Encriptación de datos sensibles
- ✅ Rate limiting en endpoints críticos
- ✅ RBAC dinámico por departamento
- ✅ Segregación de funciones

## Deployment

Ver `docs/DEPLOYMENT.md` para instrucciones de despliegue.
