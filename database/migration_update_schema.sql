-- Migration script to update existing plans table
-- Run this if you have existing data

USE word_tracker;

-- Add new columns to plans table if they don't exist
ALTER TABLE plans 
ADD COLUMN IF NOT EXISTS name VARCHAR(255) AFTER user_id,
ADD COLUMN IF NOT EXISTS content_type VARCHAR(100) AFTER name,
ADD COLUMN IF NOT EXISTS activity_type VARCHAR(100) AFTER content_type,
ADD COLUMN IF NOT EXISTS goal_amount INT DEFAULT 0 AFTER activity_type,
ADD COLUMN IF NOT EXISTS strategy VARCHAR(50) DEFAULT 'steady' AFTER end_date,
ADD COLUMN IF NOT EXISTS intensity VARCHAR(50) DEFAULT 'average' AFTER strategy;

-- Rename columns in plan_days table
ALTER TABLE plan_days 
CHANGE COLUMN target_count target INT NOT NULL DEFAULT 0,
CHANGE COLUMN actual_count logged INT DEFAULT 0;

-- Create checklists table
CREATE TABLE IF NOT EXISTS checklists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    plan_id INT NULL,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plan_id) REFERENCES plans(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create checklist_items table
CREATE TABLE IF NOT EXISTS checklist_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    checklist_id INT NOT NULL,
    item_text TEXT NOT NULL,
    is_done BOOLEAN DEFAULT FALSE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (checklist_id) REFERENCES checklists(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
