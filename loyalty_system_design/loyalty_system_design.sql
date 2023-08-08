CREATE TABLE `customers` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `email` text,
  `phone` text,
  `membership_status` integer,
  `points` decimal,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `rewards` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `description` text,
  `cost` decimal,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `rewards_history` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `customer_id` integer,
  `reward_id` integer,
  `reward_date` dateTime,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `transactions` (
  `id` integer PRIMARY KEY,
  `amount` decimal,
  `transaction_date` date,
  `transaction_time` time,
  `customer_id` integer,
  `earned_points` decimal,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE TABLE `memberships` (
  `id` integer PRIMARY KEY AUTO_INCREMENT,
  `title` varchar(255),
  `rewards` decimal,
  `benefits` text,
  `created_at` timestamp,
  `updated_at` timestamp
);

CREATE INDEX `idx_customers_email` ON `customers` (`email`);

CREATE INDEX `idx_customers_name` ON `customers` (`first_name`, `last_name`);

CREATE INDEX `idx_rewards_history_customer` ON `rewards_history` (`customer_id`);

CREATE INDEX `idx_rewards_history_reward` ON `rewards_history` (`reward_id`);

CREATE INDEX `idx_transactions_customer` ON `transactions` (`customer_id`);

CREATE INDEX `idx_transactions_date` ON `transactions` (`transaction_date`);

CREATE INDEX `idx_memberships_title` ON `memberships` (`title`);

ALTER TABLE `rewards_history` ADD FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

ALTER TABLE `rewards_history` ADD FOREIGN KEY (`reward_id`) REFERENCES `rewards` (`id`);

ALTER TABLE `transactions` ADD FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`);

ALTER TABLE `customers` ADD FOREIGN KEY (`membership_status`) REFERENCES `memberships` (`id`);
