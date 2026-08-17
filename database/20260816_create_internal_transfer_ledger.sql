CREATE TABLE IF NOT EXISTS internal_transfers (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reference VARCHAR(40) NOT NULL,
    sender_user_id INT(11) NOT NULL,
    recipient_user_id INT(11) NOT NULL,
    beneficiary_name VARCHAR(255) NOT NULL,
    beneficiary_account VARCHAR(100) NOT NULL,
    bank_name VARCHAR(120) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'USD',
    amount DECIMAL(15,2) NOT NULL,
    fee DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    total_debit DECIMAL(15,2) NOT NULL,
    description VARCHAR(500) NOT NULL DEFAULT '',
    status ENUM('DRAFT', 'PREVIEWED', 'PENDING_AUTHORIZATION', 'AUTHORIZED', 'COMPLETED', 'FAILED', 'CANCELLED', 'EXPIRED') NOT NULL DEFAULT 'DRAFT',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_internal_transfers_reference (reference),
    KEY idx_internal_transfers_sender_status (sender_user_id, status, created_at),
    CONSTRAINT fk_internal_transfers_sender FOREIGN KEY (sender_user_id) REFERENCES users(id),
    CONSTRAINT fk_internal_transfers_recipient FOREIGN KEY (recipient_user_id) REFERENCES users(id),
    CONSTRAINT chk_internal_transfers_different_users CHECK (sender_user_id <> recipient_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS transfer_authorizations (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    transfer_id BIGINT UNSIGNED NOT NULL,
    code_hash VARCHAR(255) NOT NULL,
    status ENUM('ACTIVE', 'USED', 'EXPIRED', 'LOCKED', 'CANCELLED') NOT NULL DEFAULT 'ACTIVE',
    attempt_count TINYINT UNSIGNED NOT NULL DEFAULT 0,
    max_attempts TINYINT UNSIGNED NOT NULL DEFAULT 5,
    expires_at DATETIME NOT NULL,
    used_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_transfer_authorizations_transfer (transfer_id),
    CONSTRAINT fk_transfer_authorizations_transfer FOREIGN KEY (transfer_id) REFERENCES internal_transfers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS internal_ledger_entries (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    transfer_id BIGINT UNSIGNED NOT NULL,
    user_id INT(11) NOT NULL,
    direction ENUM('DEBIT', 'CREDIT') NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    balance_after DECIMAL(15,2) NOT NULL,
    description VARCHAR(500) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_internal_ledger_entry (transfer_id, user_id, direction),
    KEY idx_internal_ledger_entries_user_created (user_id, created_at),
    CONSTRAINT fk_internal_ledger_entries_transfer FOREIGN KEY (transfer_id) REFERENCES internal_transfers(id),
    CONSTRAINT fk_internal_ledger_entries_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
