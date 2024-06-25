CREATE TABLE `subscriptions` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `endpoint` text NOT NULL,
 `p256dh` text NOT NULL,
 `auth` text NOT NULL,
 `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci