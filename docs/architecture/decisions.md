# Decisiones de arquitectura

## ADR-001: Monolito modular

**Estado:** aceptada.

**Decisión:** iniciar con un único backend Spring Boot organizado por módulos de dominio. Se usarán paquetes separados y fronteras de servicios claros. La separación física mediante microservicios se pospondrá hasta que existan razones operativas verificadas.

**Motivos:** menor complejidad inicial, transacciones financieras más simples, despliegue unificado y mejor trazabilidad contable.

## ADR-002: MySQL como base de datos central

**Estado:** aceptada.

**Decisión:** usar MySQL 8 para todos los módulos durante la fase inicial.

**Motivos:** consistencia de datos, soporte transaccional y facilidad de conciliación.

**Restricción:** ningún módulo accede directamente a tablas fuera de su dominio.

## ADR-003: Integraciones mediante adaptadores

**Estado:** aceptada.

**Decisión:** REEN MONEY CONNECT utilizará interfaces de proveedor comunes y adaptadores independientes por institución o tipo de servicio.

**Motivos:** incorporar nuevas empresas e instituciones sin modificar el motor central de pagos y contabilidad.

## ADR-004: Java 25 LTS

**Estado:** aceptada.

**Decisión:** implementar el backend con Java 25 y Spring Boot.

## ADR-005: Sin microcréditos

**Estado:** aceptada definitivamente.

**Decisión:** el producto no incluirá microcréditos ni componentes asociados a préstamo de dinero.
