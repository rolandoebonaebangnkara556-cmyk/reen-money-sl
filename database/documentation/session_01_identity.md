# Sesión 1 — modelo lógico

## users

Identidad base independiente de si la persona es cliente, empleado o ambos.

## user_credentials

Separación segura de contraseñas y datos de identidad. Permite cambiar el algoritmo sin perder el usuario.

## user_sessions

Cada sesión conserva un hash del refresh token. Los access tokens no deben persistirse.

## mfa_factors

Factores disponibles para iniciar sesión o confirmar operaciones sensibles.

## mfa_challenges

Representa una prueba MFA temporal. Debe expirar y consumirse una sola vez.

## login_attempts

Registro técnico de intentos, sin almacenar contraseñas ni códigos MFA.

## security_lockouts

Bloqueos automáticos o administrativos por usuario y/o dirección IP.

## account_recovery_requests

Solicitudes temporales de recuperación por correo o SMS.
