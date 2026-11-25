-- Sesión 1: identidad y seguridad de acceso.

CREATE TABLE users (
    id CHAR(36) NOT NULL,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    display_name VARCHAR(200) NOT NULL,
    status VARCHAR(30) NOT NULL,
    email_verified BOOLEAN NOT NULL DEFAULT FALSE,
    last_login_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_users_username (username),
    UNIQUE KEY uk_users_email (email),
    KEY idx_users_status (status),
    CONSTRAINT chk_users_status CHECK (status IN ('ACTIVE','LOCKED','DISABLED','PENDING_VERIFICATION'))
) ENGINE=InnoDB;

CREATE TABLE user_credentials (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    password_algorithm VARCHAR(50) NOT NULL,
    password_changed_at TIMESTAMP(6) NOT NULL,
    password_expires_at TIMESTAMP(6) NULL,
    must_change_password BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_credentials_user (user_id),
    CONSTRAINT fk_credentials_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE user_sessions (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    refresh_token_hash VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    device_name VARCHAR(150) NULL,
    expires_at TIMESTAMP(6) NOT NULL,
    revoked_at TIMESTAMP(6) NULL,
    revoke_reason VARCHAR(200) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_sessions_refresh_hash (refresh_token_hash),
    KEY idx_sessions_user_active (user_id, revoked_at, expires_at),
    CONSTRAINT fk_sessions_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE mfa_factors (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    factor_type VARCHAR(30) NOT NULL,
    label VARCHAR(100) NULL,
    secret_encrypted VARBINARY(1024) NULL,
    phone_number VARCHAR(30) NULL,
    status VARCHAR(30) NOT NULL,
    verified_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_mfa_user_type_label (user_id, factor_type, label),
    KEY idx_mfa_user_status (user_id, status),
    CONSTRAINT fk_mfa_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_mfa_factor_type CHECK (factor_type IN ('TOTP','SMS','EMAIL','HARDWARE_KEY')),
    CONSTRAINT chk_mfa_factor_status CHECK (status IN ('PENDING','ACTIVE','DISABLED'))
) ENGINE=InnoDB;

CREATE TABLE mfa_challenges (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    factor_id CHAR(36) NULL,
    purpose VARCHAR(50) NOT NULL,
    challenge_hash VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL,
    attempts INT NOT NULL DEFAULT 0,
    expires_at TIMESTAMP(6) NOT NULL,
    consumed_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_mfa_challenges_hash (challenge_hash),
    KEY idx_mfa_challenges_user_status (user_id, status, expires_at),
    CONSTRAINT fk_mfa_challenges_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_mfa_challenges_factor FOREIGN KEY (factor_id) REFERENCES mfa_factors(id),
    CONSTRAINT chk_mfa_challenge_purpose CHECK (purpose IN ('LOGIN','SENSITIVE_OPERATION','ACCOUNT_RECOVERY','SESSION_CONFIRMATION')),
    CONSTRAINT chk_mfa_challenge_status CHECK (status IN ('PENDING','CONSUMED','EXPIRED','FAILED'))
) ENGINE=InnoDB;

CREATE TABLE login_attempts (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NULL,
    username_entered VARCHAR(100) NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    result VARCHAR(30) NOT NULL,
    failure_reason VARCHAR(100) NULL,
    attempted_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_login_attempts_user_time (user_id, attempted_at),
    KEY idx_login_attempts_ip_time (ip_address, attempted_at),
    CONSTRAINT fk_login_attempts_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_login_attempt_result CHECK (result IN ('SUCCESS','FAILED','LOCKED','MFA_REQUIRED'))
) ENGINE=InnoDB;

CREATE TABLE security_lockouts (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NULL,
    ip_address VARCHAR(45) NULL,
    lockout_type VARCHAR(30) NOT NULL,
    reason VARCHAR(200) NOT NULL,
    failed_attempt_count INT NOT NULL DEFAULT 0,
    starts_at TIMESTAMP(6) NOT NULL,
    ends_at TIMESTAMP(6) NOT NULL,
    released_at TIMESTAMP(6) NULL,
    released_by_user_id CHAR(36) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_lockouts_user_active (user_id, released_at, ends_at),
    KEY idx_lockouts_ip_active (ip_address, released_at, ends_at),
    CONSTRAINT fk_lockouts_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_lockouts_released_by FOREIGN KEY (released_by_user_id) REFERENCES users(id),
    CONSTRAINT chk_lockout_type CHECK (lockout_type IN ('FAILED_LOGIN','ADMINISTRATIVE','SECURITY_ALERT'))
) ENGINE=InnoDB;

CREATE TABLE account_recovery_requests (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    delivery_channel VARCHAR(30) NOT NULL,
    destination_hash VARCHAR(255) NOT NULL,
    token_hash VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL,
    ip_address VARCHAR(45) NULL,
    expires_at TIMESTAMP(6) NOT NULL,
    consumed_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_recovery_token_hash (token_hash),
    KEY idx_recovery_user_status (user_id, status, expires_at),
    CONSTRAINT fk_recovery_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_recovery_channel CHECK (delivery_channel IN ('EMAIL','SMS')),
    CONSTRAINT chk_recovery_status CHECK (status IN ('PENDING','CONSUMED','EXPIRED','REVOKED'))
) ENGINE=InnoDB;
