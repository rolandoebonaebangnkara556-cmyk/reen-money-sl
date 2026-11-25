-- V1: esquema actualizado de identidad, organización, workspace y operaciones.

CREATE TABLE users (
    id CHAR(36) NOT NULL,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    updated_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_users_username (username),
    UNIQUE KEY uk_users_email (email),
    CONSTRAINT chk_users_status CHECK (status IN ('ACTIVE','LOCKED','DISABLED'))
) ENGINE=InnoDB;

CREATE TABLE user_sessions (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    refresh_token_hash VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    expires_at TIMESTAMP(6) NOT NULL,
    revoked_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_user_sessions_refresh (refresh_token_hash),
    KEY idx_user_sessions_user (user_id, revoked_at, expires_at),
    CONSTRAINT fk_user_sessions_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE mfa_factors (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    factor_type VARCHAR(30) NOT NULL,
    secret_encrypted VARBINARY(1024) NOT NULL,
    status VARCHAR(30) NOT NULL,
    verified_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_mfa_user_type (user_id, factor_type),
    CONSTRAINT fk_mfa_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_mfa_status CHECK (status IN ('PENDING','ACTIVE','DISABLED'))
) ENGINE=InnoDB;

CREATE TABLE departments (
    id CHAR(36) NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(200) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_departments_code (code),
    CONSTRAINT chk_departments_status CHECK (status IN ('ACTIVE','INACTIVE'))
) ENGINE=InnoDB;

CREATE TABLE job_positions (
    id CHAR(36) NOT NULL,
    department_id CHAR(36) NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(200) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_positions_department_code (department_id, code),
    CONSTRAINT fk_positions_department FOREIGN KEY (department_id) REFERENCES departments(id),
    CONSTRAINT chk_positions_status CHECK (status IN ('ACTIVE','INACTIVE'))
) ENGINE=InnoDB;

CREATE TABLE roles (
    id CHAR(36) NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(200) NOT NULL,
    description VARCHAR(500) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_roles_code (code)
) ENGINE=InnoDB;

CREATE TABLE permissions (
    id CHAR(36) NOT NULL,
    code VARCHAR(100) NOT NULL,
    name VARCHAR(200) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_permissions_code (code)
) ENGINE=InnoDB;

CREATE TABLE user_roles (
    user_id CHAR(36) NOT NULL,
    role_id CHAR(36) NOT NULL,
    assigned_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (user_id, role_id),
    CONSTRAINT fk_user_roles_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_user_roles_role FOREIGN KEY (role_id) REFERENCES roles(id)
) ENGINE=InnoDB;

CREATE TABLE role_permissions (
    role_id CHAR(36) NOT NULL,
    permission_id CHAR(36) NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    CONSTRAINT fk_role_permissions_role FOREIGN KEY (role_id) REFERENCES roles(id),
    CONSTRAINT fk_role_permissions_permission FOREIGN KEY (permission_id) REFERENCES permissions(id)
) ENGINE=InnoDB;

CREATE TABLE customers (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NULL,
    customer_number VARCHAR(30) NOT NULL,
    customer_type VARCHAR(30) NOT NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_customers_user (user_id),
    UNIQUE KEY uk_customers_number (customer_number),
    CONSTRAINT fk_customers_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_customers_type CHECK (customer_type IN ('INDIVIDUAL','BUSINESS')),
    CONSTRAINT chk_customers_status CHECK (status IN ('ACTIVE','SUSPENDED','CLOSED'))
) ENGINE=InnoDB;

CREATE TABLE employees (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    department_id CHAR(36) NOT NULL,
    position_id CHAR(36) NOT NULL,
    employee_number VARCHAR(30) NOT NULL,
    status VARCHAR(30) NOT NULL,
    hired_at DATE NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_employees_user (user_id),
    UNIQUE KEY uk_employees_number (employee_number),
    CONSTRAINT fk_employees_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_employees_department FOREIGN KEY (department_id) REFERENCES departments(id),
    CONSTRAINT fk_employees_position FOREIGN KEY (position_id) REFERENCES job_positions(id),
    CONSTRAINT chk_employees_status CHECK (status IN ('ACTIVE','LEAVE','TERMINATED'))
) ENGINE=InnoDB;

CREATE TABLE accounts (
    id CHAR(36) NOT NULL,
    account_number VARCHAR(40) NOT NULL,
    account_type VARCHAR(30) NOT NULL,
    currency_code CHAR(3) NOT NULL,
    status VARCHAR(30) NOT NULL,
    version BIGINT NOT NULL DEFAULT 0,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_accounts_number (account_number),
    CONSTRAINT chk_accounts_type CHECK (account_type IN ('WALLET','ESCROW','CASHBOX','LIABILITY','INTERNAL')),
    CONSTRAINT chk_accounts_status CHECK (status IN ('ACTIVE','FROZEN','CLOSED'))
) ENGINE=InnoDB;

CREATE TABLE customer_accounts (
    id CHAR(36) NOT NULL,
    customer_id CHAR(36) NOT NULL,
    account_id CHAR(36) NOT NULL,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_customer_accounts_account (account_id),
    KEY idx_customer_accounts_customer (customer_id),
    CONSTRAINT fk_customer_accounts_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT fk_customer_accounts_account FOREIGN KEY (account_id) REFERENCES accounts(id)
) ENGINE=InnoDB;

CREATE TABLE account_balance_snapshots (
    id CHAR(36) NOT NULL,
    account_id CHAR(36) NOT NULL,
    available_balance DECIMAL(20,4) NOT NULL,
    ledger_balance DECIMAL(20,4) NOT NULL,
    snapshot_at TIMESTAMP(6) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_balance_account_time (account_id, snapshot_at),
    CONSTRAINT fk_balance_account FOREIGN KEY (account_id) REFERENCES accounts(id)
) ENGINE=InnoDB;

CREATE TABLE ledger_accounts (
    id CHAR(36) NOT NULL,
    code VARCHAR(30) NOT NULL,
    name VARCHAR(200) NOT NULL,
    normal_side VARCHAR(6) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'ACTIVE',
    PRIMARY KEY (id),
    UNIQUE KEY uk_ledger_accounts_code (code),
    CONSTRAINT chk_ledger_normal_side CHECK (normal_side IN ('DEBIT','CREDIT')),
    CONSTRAINT chk_ledger_status CHECK (status IN ('ACTIVE','INACTIVE'))
) ENGINE=InnoDB;

CREATE TABLE transactions (
    id CHAR(36) NOT NULL,
    transaction_number VARCHAR(40) NOT NULL,
    idempotency_key VARCHAR(100) NOT NULL,
    transaction_type VARCHAR(40) NOT NULL,
    status VARCHAR(30) NOT NULL,
    amount DECIMAL(20,4) NOT NULL,
    currency_code CHAR(3) NOT NULL,
    source_account_id CHAR(36) NULL,
    destination_account_id CHAR(36) NULL,
    reversal_of_id CHAR(36) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    confirmed_at TIMESTAMP(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_transactions_number (transaction_number),
    UNIQUE KEY uk_transactions_idempotency (idempotency_key),
    KEY idx_transactions_status (status, created_at),
    CONSTRAINT fk_transactions_source FOREIGN KEY (source_account_id) REFERENCES accounts(id),
    CONSTRAINT fk_transactions_destination FOREIGN KEY (destination_account_id) REFERENCES accounts(id),
    CONSTRAINT fk_transactions_reversal FOREIGN KEY (reversal_of_id) REFERENCES transactions(id),
    CONSTRAINT chk_transactions_amount CHECK (amount > 0),
    CONSTRAINT chk_transactions_status CHECK (status IN ('PENDING','CONFIRMED','FAILED','REVERSED'))
) ENGINE=InnoDB;

CREATE TABLE transaction_events (
    id CHAR(36) NOT NULL,
    transaction_id CHAR(36) NOT NULL,
    from_status VARCHAR(30) NULL,
    to_status VARCHAR(30) NOT NULL,
    reason VARCHAR(500) NULL,
    actor_user_id CHAR(36) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_transaction_events_transaction (transaction_id, created_at),
    CONSTRAINT fk_transaction_events_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    CONSTRAINT fk_transaction_events_actor FOREIGN KEY (actor_user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE ledger_entries (
    id CHAR(36) NOT NULL,
    transaction_id CHAR(36) NOT NULL,
    ledger_account_id CHAR(36) NOT NULL,
    account_id CHAR(36) NULL,
    entry_side VARCHAR(6) NOT NULL,
    amount DECIMAL(20,4) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_ledger_entries_transaction (transaction_id),
    KEY idx_ledger_entries_account (account_id, created_at),
    CONSTRAINT fk_ledger_entries_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    CONSTRAINT fk_ledger_entries_ledger_account FOREIGN KEY (ledger_account_id) REFERENCES ledger_accounts(id),
    CONSTRAINT fk_ledger_entries_account FOREIGN KEY (account_id) REFERENCES accounts(id),
    CONSTRAINT chk_ledger_entries_side CHECK (entry_side IN ('DEBIT','CREDIT')),
    CONSTRAINT chk_ledger_entries_amount CHECK (amount > 0)
) ENGINE=InnoDB;

CREATE TABLE external_institutions (
    id CHAR(36) NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    institution_type VARCHAR(50) NOT NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_institutions_code (code),
    CONSTRAINT chk_institutions_status CHECK (status IN ('ACTIVE','INACTIVE'))
) ENGINE=InnoDB;

CREATE TABLE institution_services (
    id CHAR(36) NOT NULL,
    institution_id CHAR(36) NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(200) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_institution_services (institution_id, code),
    CONSTRAINT fk_institution_services_institution FOREIGN KEY (institution_id) REFERENCES external_institutions(id),
    CONSTRAINT chk_institution_services_status CHECK (status IN ('ACTIVE','INACTIVE'))
) ENGINE=InnoDB;

CREATE TABLE integration_adapters (
    id CHAR(36) NOT NULL,
    institution_id CHAR(36) NOT NULL,
    adapter_code VARCHAR(100) NOT NULL,
    adapter_type VARCHAR(30) NOT NULL,
    configuration_json JSON NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_adapters_institution (institution_id, adapter_code),
    CONSTRAINT fk_adapters_institution FOREIGN KEY (institution_id) REFERENCES external_institutions(id),
    CONSTRAINT chk_adapters_status CHECK (status IN ('ACTIVE','INACTIVE','MAINTENANCE'))
) ENGINE=InnoDB;

CREATE TABLE bills (
    id CHAR(36) NOT NULL,
    institution_id CHAR(36) NOT NULL,
    institution_service_id CHAR(36) NULL,
    external_reference VARCHAR(100) NOT NULL,
    customer_reference VARCHAR(100) NULL,
    amount DECIMAL(20,4) NOT NULL,
    currency_code CHAR(3) NOT NULL,
    due_at TIMESTAMP(6) NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_bills_institution_reference (institution_id, external_reference),
    KEY idx_bills_customer_reference (customer_reference),
    CONSTRAINT fk_bills_institution FOREIGN KEY (institution_id) REFERENCES external_institutions(id),
    CONSTRAINT fk_bills_service FOREIGN KEY (institution_service_id) REFERENCES institution_services(id),
    CONSTRAINT chk_bills_amount CHECK (amount >= 0),
    CONSTRAINT chk_bills_status CHECK (status IN ('PENDING','PAID','CANCELLED','EXPIRED'))
) ENGINE=InnoDB;

CREATE TABLE payment_orders (
    id CHAR(36) NOT NULL,
    bill_id CHAR(36) NOT NULL,
    customer_account_id CHAR(36) NOT NULL,
    idempotency_key VARCHAR(100) NOT NULL,
    amount DECIMAL(20,4) NOT NULL,
    currency_code CHAR(3) NOT NULL,
    status VARCHAR(30) NOT NULL,
    transaction_id CHAR(36) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    confirmed_at TIMESTAMP(6) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_payment_orders_idempotency (idempotency_key),
    UNIQUE KEY uk_payment_orders_transaction (transaction_id),
    KEY idx_payment_orders_status (status, created_at),
    CONSTRAINT fk_payment_orders_bill FOREIGN KEY (bill_id) REFERENCES bills(id),
    CONSTRAINT fk_payment_orders_customer_account FOREIGN KEY (customer_account_id) REFERENCES customer_accounts(id),
    CONSTRAINT fk_payment_orders_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    CONSTRAINT chk_payment_orders_amount CHECK (amount > 0),
    CONSTRAINT chk_payment_orders_status CHECK (status IN ('PENDING','CONFIRMED','FAILED','REVERSED'))
) ENGINE=InnoDB;

CREATE TABLE external_payment_attempts (
    id CHAR(36) NOT NULL,
    payment_order_id CHAR(36) NOT NULL,
    adapter_id CHAR(36) NOT NULL,
    attempt_number INT NOT NULL,
    external_reference VARCHAR(100) NULL,
    request_payload JSON NULL,
    response_payload JSON NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_attempt_order_number (payment_order_id, attempt_number),
    CONSTRAINT fk_attempt_payment_order FOREIGN KEY (payment_order_id) REFERENCES payment_orders(id),
    CONSTRAINT fk_attempt_adapter FOREIGN KEY (adapter_id) REFERENCES integration_adapters(id),
    CONSTRAINT chk_attempt_status CHECK (status IN ('PENDING','CONFIRMED','FAILED','UNKNOWN'))
) ENGINE=InnoDB;

CREATE TABLE integration_webhooks (
    id CHAR(36) NOT NULL,
    institution_id CHAR(36) NOT NULL,
    adapter_id CHAR(36) NULL,
    event_type VARCHAR(100) NOT NULL,
    external_reference VARCHAR(100) NULL,
    payload_json JSON NOT NULL,
    signature_valid BOOLEAN NULL,
    processed_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_webhooks_institution_created (institution_id, created_at),
    CONSTRAINT fk_webhooks_institution FOREIGN KEY (institution_id) REFERENCES external_institutions(id),
    CONSTRAINT fk_webhooks_adapter FOREIGN KEY (adapter_id) REFERENCES integration_adapters(id)
) ENGINE=InnoDB;

CREATE TABLE agents (
    id CHAR(36) NOT NULL,
    customer_id CHAR(36) NOT NULL,
    agent_code VARCHAR(50) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_agents_code (agent_code),
    CONSTRAINT fk_agents_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT chk_agents_status CHECK (status IN ('ACTIVE','SUSPENDED','INACTIVE'))
) ENGINE=InnoDB;

CREATE TABLE agent_branches (
    id CHAR(36) NOT NULL,
    agent_id CHAR(36) NOT NOT NULL,
    branch_code VARCHAR(50) NOT NULL,
    name VARCHAR(200) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_agent_branch_code (agent_id, branch_code),
    CONSTRAINT fk_agent_branches_agent FOREIGN KEY (agent_id) REFERENCES agents(id)
) ENGINE=InnoDB;

CREATE TABLE merchants (
    id CHAR(36) NOT NULL,
    customer_id CHAR(36) NULL,
    merchant_code VARCHAR(50) NOT NULL,
    name VARCHAR(255) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_merchants_code (merchant_code),
    CONSTRAINT fk_merchants_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT chk_merchants_status CHECK (status IN ('ACTIVE','SUSPENDED','INACTIVE'))
) ENGINE=InnoDB;

CREATE TABLE merchant_locations (
    id CHAR(36) NOT NULL,
    merchant_id CHAR(36) NOT NULL,
    name VARCHAR(200) NOT NULL,
    address VARCHAR(500) NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_merchant_locations_merchant (merchant_id),
    CONSTRAINT fk_merchant_locations_merchant FOREIGN KEY (merchant_id) REFERENCES merchants(id)
) ENGINE=InnoDB;

CREATE TABLE virtual_card_providers (
    id CHAR(36) NOT NULL,
    code VARCHAR(50) NOT NULL,
    name VARCHAR(200) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_card_provider_code (code)
) ENGINE=InnoDB;

CREATE TABLE virtual_cards (
    id CHAR(36) NOT NULL,
    customer_account_id CHAR(36) NOT NULL,
    provider_id CHAR(36) NOT NULL,
    external_card_reference VARCHAR(100) NOT NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    UNIQUE KEY uk_virtual_card_reference (provider_id, external_card_reference),
    CONSTRAINT fk_virtual_cards_account FOREIGN KEY (customer_account_id) REFERENCES customer_accounts(id),
    CONSTRAINT fk_virtual_cards_provider FOREIGN KEY (provider_id) REFERENCES virtual_card_providers(id),
    CONSTRAINT chk_virtual_cards_status CHECK (status IN ('ACTIVE','BLOCKED','EXPIRED','CANCELLED'))
) ENGINE=InnoDB;

CREATE TABLE workspace_tasks (
    id CHAR(36) NOT NULL,
    department_id CHAR(36) NOT NULL,
    assigned_to_id CHAR(36) NOT NULL,
    created_by_id CHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status VARCHAR(30) NOT NULL,
    due_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_workspace_tasks_assigned (assigned_to_id, status),
    CONSTRAINT fk_tasks_department FOREIGN KEY (department_id) REFERENCES departments(id),
    CONSTRAINT fk_tasks_assigned FOREIGN KEY (assigned_to_id) REFERENCES employees(id),
    CONSTRAINT fk_tasks_creator FOREIGN KEY (created_by_id) REFERENCES employees(id),
    CONSTRAINT chk_tasks_status CHECK (status IN ('OPEN','IN_PROGRESS','DONE','CANCELLED'))
) ENGINE=InnoDB;

CREATE TABLE workspace_requests (
    id CHAR(36) NOT NULL,
    requester_employee_id CHAR(36) NOT NULL,
    department_id CHAR(36) NOT NULL,
    request_type VARCHAR(50) NOT NULL,
    payload_json JSON NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_workspace_requests_status (status, created_at),
    CONSTRAINT fk_workspace_requests_employee FOREIGN KEY (requester_employee_id) REFERENCES employees(id),
    CONSTRAINT fk_workspace_requests_department FOREIGN KEY (department_id) REFERENCES departments(id),
    CONSTRAINT chk_workspace_requests_status CHECK (status IN ('DRAFT','SUBMITTED','APPROVED','REJECTED','CANCELLED'))
) ENGINE=InnoDB;

CREATE TABLE workspace_approval_steps (
    id CHAR(36) NOT NULL,
    request_id CHAR(36) NOT NULL,
    approver_employee_id CHAR(36) NOT NULL,
    step_number INT NOT NULL,
    status VARCHAR(30) NOT NULL,
    decision_at TIMESTAMP(6) NULL,
    comment VARCHAR(500) NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_request_approval_step (request_id, step_number),
    CONSTRAINT fk_approval_request FOREIGN KEY (request_id) REFERENCES workspace_requests(id),
    CONSTRAINT fk_approval_approver FOREIGN KEY (approver_employee_id) REFERENCES employees(id),
    CONSTRAINT chk_approval_status CHECK (status IN ('PENDING','APPROVED','REJECTED','SKIPPED'))
) ENGINE=InnoDB;

CREATE TABLE meetings (
    id CHAR(36) NOT NULL,
    organizer_employee_id CHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    starts_at TIMESTAMP(6) NOT NULL,
    ends_at TIMESTAMP(6) NOT NULL,
    status VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_meetings_time (starts_at, ends_at),
    CONSTRAINT fk_meetings_organizer FOREIGN KEY (organizer_employee_id) REFERENCES employees(id),
    CONSTRAINT chk_meetings_status CHECK (status IN ('SCHEDULED','CANCELLED','COMPLETED'))
) ENGINE=InnoDB;

CREATE TABLE meeting_participants (
    meeting_id CHAR(36) NOT NULL,
    employee_id CHAR(36) NOT NULL,
    attendance_status VARCHAR(30) NOT NULL,
    PRIMARY KEY (meeting_id, employee_id),
    CONSTRAINT fk_meeting_participants_meeting FOREIGN KEY (meeting_id) REFERENCES meetings(id),
    CONSTRAINT fk_meeting_participants_employee FOREIGN KEY (employee_id) REFERENCES employees(id),
    CONSTRAINT chk_attendance_status CHECK (attendance_status IN ('INVITED','ACCEPTED','DECLINED','ATTENDED','ABSENT'))
) ENGINE=InnoDB;

CREATE TABLE workspace_documents (
    id CHAR(36) NOT NULL,
    owner_employee_id CHAR(36) NOT NULL,
    title VARCHAR(255) NOT NULL,
    storage_path VARCHAR(1000) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    CONSTRAINT fk_documents_owner FOREIGN KEY (owner_employee_id) REFERENCES employees(id),
    CONSTRAINT chk_documents_status CHECK (status IN ('ACTIVE','ARCHIVED','DELETED'))
) ENGINE=InnoDB;

CREATE TABLE document_permissions (
    document_id CHAR(36) NOT NULL,
    employee_id CHAR(36) NOT NULL,
    can_read BOOLEAN NOT NULL DEFAULT TRUE,
    can_edit BOOLEAN NOT NULL DEFAULT FALSE,
    can_share BOOLEAN NOT NULL DEFAULT FALSE,
    PRIMARY KEY (document_id, employee_id),
    CONSTRAINT fk_document_permissions_document FOREIGN KEY (document_id) REFERENCES workspace_documents(id),
    CONSTRAINT fk_document_permissions_employee FOREIGN KEY (employee_id) REFERENCES employees(id)
) ENGINE=InnoDB;

CREATE TABLE risk_alerts (
    id CHAR(36) NOT NULL,
    transaction_id CHAR(36) NULL,
    customer_id CHAR(36) NULL,
    severity VARCHAR(20) NOT NULL,
    code VARCHAR(50) NOT NULL,
    status VARCHAR(30) NOT NULL,
    details_json JSON NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_risk_alerts_status (status, created_at),
    CONSTRAINT fk_risk_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    CONSTRAINT fk_risk_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT chk_risk_severity CHECK (severity IN ('LOW','MEDIUM','HIGH','CRITICAL')),
    CONSTRAINT chk_risk_status CHECK (status IN ('OPEN','UNDER_REVIEW','CLOSED'))
) ENGINE=InnoDB;

CREATE TABLE compliance_cases (
    id CHAR(36) NOT NULL,
    customer_id CHAR(36) NULL,
    transaction_id CHAR(36) NULL,
    case_type VARCHAR(50) NOT NULL,
    status VARCHAR(30) NOT NULL,
    opened_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    closed_at TIMESTAMP(6) NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_compliance_customer FOREIGN KEY (customer_id) REFERENCES customers(id),
    CONSTRAINT fk_compliance_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id),
    CONSTRAINT chk_compliance_status CHECK (status IN ('OPEN','INVESTIGATING','RESOLVED','ESCALATED'))
) ENGINE=InnoDB;

CREATE TABLE audit_events (
    id CHAR(36) NOT NULL,
    actor_user_id CHAR(36) NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100) NOT NULL,
    entity_id CHAR(36) NOT NULL,
    before_json JSON NULL,
    after_json JSON NULL,
    ip_address VARCHAR(45) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_audit_entity (entity_type, entity_id, created_at),
    KEY idx_audit_actor (actor_user_id, created_at),
    CONSTRAINT fk_audit_actor FOREIGN KEY (actor_user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE notifications (
    id CHAR(36) NOT NULL,
    user_id CHAR(36) NOT NULL,
    channel VARCHAR(30) NOT NULL,
    notification_type VARCHAR(50) NOT NULL,
    subject VARCHAR(255) NULL,
    body TEXT NOT NULL,
    status VARCHAR(30) NOT NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    read_at TIMESTAMP(6) NULL,
    PRIMARY KEY (id),
    KEY idx_notifications_user_status (user_id, status, created_at),
    CONSTRAINT fk_notifications_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_notifications_channel CHECK (channel IN ('IN_APP','EMAIL','SMS','PUSH','WEBSOCKET')),
    CONSTRAINT chk_notifications_status CHECK (status IN ('PENDING','SENT','FAILED','READ'))
) ENGINE=InnoDB;

CREATE TABLE notification_outbox (
    id CHAR(36) NOT NULL,
    aggregate_type VARCHAR(100) NOT NULL,
    aggregate_id CHAR(36) NOT NULL,
    event_type VARCHAR(100) NOT NULL,
    payload_json JSON NOT NULL,
    status VARCHAR(30) NOT NULL,
    available_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    processed_at TIMESTAMP(6) NULL,
    created_at TIMESTAMP(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
    PRIMARY KEY (id),
    KEY idx_notification_outbox_pending (status, available_at),
    CONSTRAINT chk_notification_outbox_status CHECK (status IN ('PENDING','PROCESSED','FAILED'))
) ENGINE=InnoDB;
