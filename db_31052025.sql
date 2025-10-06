/*
SQLyog Community v13.3.0 (64 bit)
MySQL - 8.0.30 : Database - gkjprambanan_db
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `cache` */

DROP TABLE IF EXISTS `cache`;

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache` */

LOCK TABLES `cache` WRITE;

insert  into `cache`(`key`,`value`,`expiration`) values 
('gkjprambanan_cache_356a192b7913b04c54574d18c28d46e6395428ab','i:2;',1748618813),
('gkjprambanan_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer','i:1748618813;',1748618813);

UNLOCK TABLES;

/*Table structure for table `cache_locks` */

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cache_locks` */

LOCK TABLES `cache_locks` WRITE;

UNLOCK TABLES;

/*Table structure for table `donations` */

DROP TABLE IF EXISTS `donations`;

CREATE TABLE `donations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `donor_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donor_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `donation_type` enum('internal','external') COLLATE utf8mb4_unicode_ci NOT NULL,
  `donation_category` enum('barang','dana') COLLATE utf8mb4_unicode_ci NOT NULL,
  `donation_size` enum('besar','kecil') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `proposal_id` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `proposal_recipient_id` bigint unsigned DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `item_description` text COLLATE utf8mb4_unicode_ci,
  `proof_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donations_proposal_id_foreign` (`proposal_id`),
  KEY `donations_created_by_foreign` (`created_by`),
  KEY `donations_proposal_recipient_id_foreign` (`proposal_recipient_id`),
  KEY `donations_project_id_foreign` (`project_id`),
  CONSTRAINT `donations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `donations_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `donations_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE SET NULL,
  CONSTRAINT `donations_proposal_recipient_id_foreign` FOREIGN KEY (`proposal_recipient_id`) REFERENCES `proposal_recipients` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `donations` */

LOCK TABLES `donations` WRITE;

insert  into `donations`(`id`,`donor_name`,`donor_address`,`donation_type`,`donation_category`,`donation_size`,`amount`,`description`,`proposal_id`,`created_by`,`created_at`,`updated_at`,`proposal_recipient_id`,`project_id`,`quantity`,`item_description`,`proof_file`) values 
(1,'Bambang Subekti','Solo','external','dana','kecil',2500000.00,'donasi untuk renovasi pepanthan pule',NULL,1,'2025-05-30 15:19:54','2025-05-30 15:26:24',5,1,NULL,NULL,'donations-proof/01JWGWTBF9AF8VQGR75TDAZDY9.png');

UNLOCK TABLES;

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

LOCK TABLES `failed_jobs` WRITE;

UNLOCK TABLES;

/*Table structure for table `job_batches` */

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_batches` */

LOCK TABLES `job_batches` WRITE;

UNLOCK TABLES;

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

LOCK TABLES `jobs` WRITE;

UNLOCK TABLES;

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

LOCK TABLES `migrations` WRITE;

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_05_29_123534_add_role_to_users_table',1),
(5,'2025_05_29_142028_create_projects_table',1),
(6,'2025_05_29_142034_create_project_finances_table',1),
(7,'2025_05_29_142039_create_proposals_table',1),
(8,'2024_02_14_000001_alter_proposals_table',2),
(9,'2024_02_14_000002_add_file_to_proposals_table',3),
(10,'2024_02_14_000003_add_recipient_fields_to_proposals_table',4),
(11,'2024_02_14_000004_fix_status_column_in_proposals_table',5),
(12,'2024_02_14_000005_remove_purpose_from_proposals_table',6),
(13,'2024_02_14_000006_fix_proposals_table_structure',7),
(14,'2024_02_14_000007_create_proposal_recipients_table',8),
(15,'2025_05_29_155239_add_status_to_proposal_recipients_table',9),
(16,'2025_05_30_142213_create_donations_table',10),
(17,'2024_03_21_create_donations_table',11),
(18,'2025_05_30_150803_add_proposal_recipient_id_to_donations_table',12),
(19,'2025_05_30_151356_add_project_id_to_donations_table',13),
(20,'2025_05_30_151636_add_quantity_and_item_description_to_donations_table',14),
(21,'2025_05_30_152320_add_proof_file_to_donations_table',15),
(22,'2024_03_21_create_project_work_items_table',16),
(23,'2024_03_21_create_project_work_sub_items_table',16),
(24,'2024_03_21_add_realization_amount_to_work_items',17),
(25,'2025_05_30_161200_create_project_sub_projects_table',18),
(26,'2025_05_30_161237_add_sub_project_id_to_project_work_items_table',18),
(27,'2025_05_30_161300_add_sub_project_id_to_project_work_items_table',19);

UNLOCK TABLES;

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

LOCK TABLES `password_reset_tokens` WRITE;

UNLOCK TABLES;

/*Table structure for table `project_finances` */

DROP TABLE IF EXISTS `project_finances`;

CREATE TABLE `project_finances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `type` enum('planning','usage') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_proof` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_finances_project_id_foreign` (`project_id`),
  CONSTRAINT `project_finances_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `project_finances` */

LOCK TABLES `project_finances` WRITE;

UNLOCK TABLES;

/*Table structure for table `project_sub_projects` */

DROP TABLE IF EXISTS `project_sub_projects`;

CREATE TABLE `project_sub_projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `budget_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `realization_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_sub_projects_project_id_foreign` (`project_id`),
  CONSTRAINT `project_sub_projects_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `project_sub_projects` */

LOCK TABLES `project_sub_projects` WRITE;

insert  into `project_sub_projects`(`id`,`project_id`,`name`,`description`,`budget_amount`,`realization_amount`,`created_at`,`updated_at`) values 
(1,1,'tahap 1 (2025)',NULL,0.00,0.00,'2025-05-30 16:31:40','2025-05-30 16:31:40'),
(2,1,'tahap 2',NULL,0.00,0.00,'2025-05-30 16:31:54','2025-05-30 16:31:54');

UNLOCK TABLES;

/*Table structure for table `project_work_items` */

DROP TABLE IF EXISTS `project_work_items`;

CREATE TABLE `project_work_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `budget_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `realization_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sub_project_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_work_items_project_id_foreign` (`project_id`),
  KEY `project_work_items_sub_project_id_foreign` (`sub_project_id`),
  CONSTRAINT `project_work_items_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_work_items_sub_project_id_foreign` FOREIGN KEY (`sub_project_id`) REFERENCES `project_sub_projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `project_work_items` */

LOCK TABLES `project_work_items` WRITE;

insert  into `project_work_items`(`id`,`project_id`,`name`,`description`,`budget_amount`,`created_at`,`updated_at`,`realization_amount`,`sub_project_id`) values 
(1,1,'pekerjaan persiapan',NULL,21232911.00,'2025-05-30 16:02:36','2025-05-30 16:36:12',0.00,1),
(2,1,'pekerjaan struktur',NULL,750412550.00,'2025-05-30 16:43:05','2025-05-30 17:12:10',0.00,1);

UNLOCK TABLES;

/*Table structure for table `project_work_sub_items` */

DROP TABLE IF EXISTS `project_work_sub_items`;

CREATE TABLE `project_work_sub_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_work_item_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `budget_amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `realization_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `project_work_sub_items_project_work_item_id_foreign` (`project_work_item_id`),
  CONSTRAINT `project_work_sub_items_project_work_item_id_foreign` FOREIGN KEY (`project_work_item_id`) REFERENCES `project_work_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `project_work_sub_items` */

LOCK TABLES `project_work_sub_items` WRITE;

insert  into `project_work_sub_items`(`id`,`project_work_item_id`,`name`,`description`,`budget_amount`,`created_at`,`updated_at`,`realization_amount`) values 
(1,2,'pekerjaan tanah',NULL,27219080.00,'2025-05-30 16:43:05','2025-05-30 16:57:39',0.00),
(2,2,'pekerjaan pondasi',NULL,59193911.00,'2025-05-30 16:43:05','2025-05-30 16:57:39',0.00),
(3,2,'pekerjaan Beton',NULL,324557943.00,'2025-05-30 16:49:32','2025-05-30 16:57:39',0.00),
(4,2,'pekerjaan Atap',NULL,339441616.00,'2025-05-30 16:49:32','2025-05-30 16:57:39',0.00),
(13,2,'pekerjaan tanah',NULL,27219080.00,'2025-05-30 17:11:33','2025-05-30 17:11:33',0.00),
(14,2,'pekerjaan pondasi',NULL,59193911.00,'2025-05-30 17:11:33','2025-05-30 17:11:33',0.00),
(15,2,'pekerjaan Beton',NULL,324557943.00,'2025-05-30 17:11:33','2025-05-30 17:11:33',0.00),
(16,2,'pekerjaan Atap',NULL,339441616.00,'2025-05-30 17:11:33','2025-05-30 17:11:33',0.00);

UNLOCK TABLES;

/*Table structure for table `projects` */

DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `person_in_charge` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `required_budget` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `projects` */

LOCK TABLES `projects` WRITE;

insert  into `projects`(`id`,`name`,`start_date`,`end_date`,`person_in_charge`,`required_budget`,`created_at`,`updated_at`) values 
(1,'Renovasi Pepanthan Pule','2025-06-01','2026-12-29','Suwarto',0.00,'2025-05-29 15:13:20','2025-05-30 16:32:18');

UNLOCK TABLES;

/*Table structure for table `proposal_recipients` */

DROP TABLE IF EXISTS `proposal_recipients`;

CREATE TABLE `proposal_recipients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `proposal_id` bigint unsigned NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `recipient_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposal_recipients_proposal_id_foreign` (`proposal_id`),
  CONSTRAINT `proposal_recipients_proposal_id_foreign` FOREIGN KEY (`proposal_id`) REFERENCES `proposals` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `proposal_recipients` */

LOCK TABLES `proposal_recipients` WRITE;

insert  into `proposal_recipients`(`id`,`proposal_id`,`recipient_name`,`recipient_address`,`quantity`,`status`,`created_at`,`updated_at`) values 
(5,6,'Bambang Subekti','Solo',1,'pending','2025-05-29 15:56:46','2025-05-29 15:56:46'),
(6,6,'Hartoyo ','Klaten',1,'pending','2025-05-29 15:56:46','2025-05-29 15:56:46'),
(7,7,'Dwi Jarwati','jakarta',1,'pending','2025-05-29 15:58:41','2025-05-29 15:58:41'),
(8,7,'M.K Priska','Semarang',1,'pending','2025-05-29 15:58:42','2025-05-29 15:58:42'),
(9,7,'Bambang Prasetyono','Bantul',1,'pending','2025-05-29 15:58:42','2025-05-29 15:58:42'),
(10,7,'Tri Yuli Ernawati','Jakarta',1,'pending','2025-05-29 15:58:42','2025-05-29 15:58:42'),
(11,7,'Eny Susilowati','Sulawesi',1,'pending','2025-05-29 15:58:42','2025-05-29 15:58:42');

UNLOCK TABLES;

/*Table structure for table `proposals` */

DROP TABLE IF EXISTS `proposals`;

CREATE TABLE `proposals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `requester` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `request_date` date NOT NULL,
  `sent_date` date DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proposals_project_id_foreign` (`project_id`),
  CONSTRAINT `proposals_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `proposals` */

LOCK TABLES `proposals` WRITE;

insert  into `proposals`(`id`,`requester`,`status`,`request_date`,`sent_date`,`quantity`,`file`,`project_id`,`created_at`,`updated_at`) values 
(6,'Hadi Purnomo','pending','2025-05-29',NULL,NULL,NULL,1,'2025-05-29 15:56:46','2025-05-29 15:56:46'),
(7,'Krisyono','pending','2025-05-29',NULL,NULL,NULL,1,'2025-05-29 15:58:41','2025-05-29 15:58:41');

UNLOCK TABLES;

/*Table structure for table `sessions` */

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `sessions` */

LOCK TABLES `sessions` WRITE;

insert  into `sessions`(`id`,`user_id`,`ip_address`,`user_agent`,`payload`,`last_activity`) values 
('opWre6T38TnEcUj4FBF9XTmePj7SliTdzXjzkSgD',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUNwaU1UckhBRndsWFJkZlNzOE5ybElUaDBreXFMbU55NWVkeGpkMSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9sb2dpbiI7fX0=',1748626624);

UNLOCK TABLES;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'jemaat',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

LOCK TABLES `users` WRITE;

insert  into `users`(`id`,`name`,`email`,`role`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,'Super Admin','superadmin3623@gkjprambanan.org','superadmin',NULL,'$2y$12$4lhRTRtDBQgqH/tDvfta9ezz1QdPA0tWIV/OXZorU8gGW/p5i8jLq',NULL,'2025-05-29 15:12:10','2025-05-29 15:12:10'),
(2,'Admin Gereja','admingereja9273@gkjprambanan.org','admin_gereja',NULL,'$2y$12$4n9aLeMUk/WZzYLAebxEl./PP3tZVHAZ0LlGAP/i2w.SNHWsibL2K',NULL,'2025-05-29 15:12:10','2025-05-29 15:12:10'),
(3,'Admin Komisi','adminkomisi7780@gkjprambanan.org','admin_komisi',NULL,'$2y$12$tR23.9JhZu8QJQYDW1h0Ce2NmRl7Ply.X8/Rw.0bggCn2weBWF0Se',NULL,'2025-05-29 15:12:10','2025-05-29 15:12:10'),
(4,'Jemaat','jemaat2062@gkjprambanan.org','jemaat',NULL,'$2y$12$aUIAo5MgH6tl5cq3UPmZNud/T4lWanwByjddC6C2pBTr1Y3gF6FiC',NULL,'2025-05-29 15:12:10','2025-05-29 15:12:10');

UNLOCK TABLES;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
