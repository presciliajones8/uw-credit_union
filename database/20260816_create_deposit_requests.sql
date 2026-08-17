CREATE TABLE IF NOT EXISTS deposit_requests (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    reference VARCHAR(40) NOT NULL,
    method VARCHAR(16) NOT NULL,
    receiving_address VARCHAR(128) NOT NULL,
    declared_amount DECIMAL(18,8) NULL,
    status ENUM('PENDING_VERIFICATION', 'VERIFIED', 'REJECTED', 'CANCELLED') NOT NULL DEFAULT 'PENDING_VERIFICATION',
    idempotency_key CHAR(64) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_deposit_requests_reference (reference),
    UNIQUE KEY uq_deposit_requests_idempotency_key (idempotency_key),
    KEY idx_deposit_requests_user_status_created (user_id, status, created_at),
    CONSTRAINT fk_deposit_requests_user
        FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
