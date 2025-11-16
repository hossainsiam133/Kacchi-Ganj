-- Create messages table for live messaging system
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `is_read` tinyint(1) DEFAULT 0,
  FOREIGN KEY (`sender_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `idx_conversation` (`sender_id`, `receiver_id`),
  INDEX `idx_receiver` (`receiver_id`, `is_read`),
  INDEX `idx_timestamp` (`timestamp`)
);

-- Sample data (optional)
-- INSERT INTO `messages` (`sender_id`, `receiver_id`, `message`, `timestamp`, `is_read`) 
-- VALUES (1, 100, 'Hello! How are you?', NOW(), 0);
