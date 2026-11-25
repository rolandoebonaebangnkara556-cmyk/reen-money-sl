# Sesión 1: Identidad y sesiones

Esta migración crea las tablas necesarias para identificar usuarios y controlar el acceso de forma segura.

## Tablas

1. `users`: identidad principal.
2. `user_credentials`: hash y estado de contraseña.
3. `user_sessions`: sesiones activas o revocadas.
4. `mfa_factors`: factores configurados por el usuario.
5. `mfa_challenges`: verificaciones de segundo factor pendientes o usadas.
6. `login_attempts`: auditoría técnica de intentos de inicio de sesión.
7. `security_lockouts`: bloqueos temporales o administrativos.
8. `account_recovery_requests`: solicitudes de recuperación de cuenta.

## Reglas

- Las contraseñas nunca se almacenan en texto plano.
- Los refresh tokens se guardan únicamente como hash.
- Una sesión revocada no puede renovarse.
- Las operaciones financieras sensibles pueden exigir MFA activo.
- Los bloqueos previenen intentos repetidos por usuario o por origen.
- Las solicitudes de recuperación expiran y se usan una sola vez.
- Los intentos de inicio de sesión no contienen contraseñas.
