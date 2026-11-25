# REEN MONEY

Plataforma fintech modular para pagos, servicios financieros e integraciones con instituciones y empresas en Guinea Ecuatorial y el espacio CEMAC.

## Arquitectura

- Backend: monolito modular con Java 25 y Spring Boot.
- Frontend administrativo: React y TypeScript.
- Frontend de empleados: React y TypeScript.
- Aplicación móvil: Flutter y Dart.
- Base de datos: MySQL 8.
- Migraciones: Flyway.
- API: REST y WebSocket.
- Seguridad: Spring Security, JWT y RBAC.

## Componentes

- `REEN MONEY CORE`: núcleo financiero y motor operativo.
- `REEN MONEY ADMIN`: administración central.
- `REEN WORKSPACE`: entorno laboral por departamento.
- `REEN MONEY MOBILE`: aplicación para clientes.
- `REEN MONEY CONNECT`: adaptadores de integraciones externas.

## Reglas iniciales

- No implementar microcréditos.
- Las operaciones deben ser idempotentes.
- Las operaciones confirmadas no se eliminan.
- Las correcciones financieras usan movimientos compensatorios.
- El saldo debe conciliarse con el libro contable.
- Las modificaciones administrativas deben quedar auditadas.

## Inicio rápido

### Requisitos

- JDK 25
- Maven 3.9+
- Docker Desktop
- Node.js 22+
- Flutter estable
- MySQL 8

### Backend

```bash
cd backend
mvn spring-boot:run
```

## Estado actual

Inicialización del repositorio:

- [x] Visión y arquitectura documentadas.
- [x] Estructura modular creada.
- [x] Configuración inicial del backend.
- [ ] Diseño completo del modelo entidad-relación.
- [ ] Seguridad y autenticación.
- [ ] Núcleo contable y transacciones.
