-- SQL to create news table
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `news_text` text NOT NULL,
  `news_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample data (optional)
-- INSERT INTO `news` (`news_text`, `news_date`, `created_at`, `updated_at`) VALUES
-- ('Welcome to our news section! This is where we will post daily updates and important announcements.', CURDATE(), NOW(), NOW());
