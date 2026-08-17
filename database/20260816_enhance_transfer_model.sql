-- Migration to enhance internal_transfers table for external beneficiary support
-- This migration adds support for LOCAL_DEMO and EXTERNAL_SIMULATION transfer types

-- Add new columns to internal_transfers
ALTER TABLE internal_transfers
    ADD COLUMN country VARCHAR(100) NULL AFTER bank_name,
    ADD COLUMN swift_bic VARCHAR(20) NULL AFTER country,
    ADD COLUMN beneficiary_type ENUM('LOCAL_DEMO', 'EXTERNAL_SIMULATION') NOT NULL DEFAULT 'LOCAL_DEMO' AFTER swift_bic;

-- Make recipient_user_id nullable to support external transfers
ALTER TABLE internal_transfers
    MODIFY COLUMN recipient_user_id INT(11) NULL;

-- Remove the constraint that requires different users since external transfers have NULL recipient
ALTER TABLE internal_transfers
    DROP CONSTRAINT chk_internal_transfers_different_users;

-- Add a new constraint to ensure LOCAL_DEMO transfers have different users
ALTER TABLE internal_transfers
    ADD CONSTRAINT chk_internal_transfers_local_demo_different_users 
    CHECK (
        (beneficiary_type = 'EXTERNAL_SIMULATION' AND recipient_user_id IS NULL) OR
        (beneficiary_type = 'LOCAL_DEMO' AND recipient_user_id IS NOT NULL AND sender_user_id <> recipient_user_id)
    );

-- Add index for beneficiary_type queries
ALTER TABLE internal_transfers
    ADD INDEX idx_internal_transfers_beneficiary_type (beneficiary_type);
