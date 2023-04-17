-- Add Users
INSERT INTO `users` (`id`, `username`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'ainsofs', 'ainsofs@outlook.com', NULL, '$2y$10$sQwL9mGaZIC3aPbJNn6D8e5Lehk9a1i/bLm.BuQWoHOtqp88xv2RS', NULL, NULL, NULL, NULL, NULL, NULL, '2023-04-17 04:36:30', '2023-04-17 04:36:30');

-- Add Membership Type Options
INSERT INTO `membership_types` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'Full', 'Full', NULL, NULL), (NULL, 'Associate', 'Associate', NULL, NULL), (NULL, 'Affiliate', 'Affiliate', NULL, NULL), (NULL, 'Student', 'Student', NULL, NULL), (NULL, 'Fellow', 'Fellow', NULL, NULL);

-- Add Gender Options
INSERT INTO `genders` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'm', 'Male', NULL, NULL), (NULL, 'f', 'Female', NULL, NULL), (NULL, 'g', 'Gender non-conforming', NULL, NULL), (NULL, 'n', 'Non Binary', NULL, NULL), (NULL, 'p', 'Prefer not to say', NULL, NULL);

-- Add Title Options
INSERT INTO `titles` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'Mr', 'Mr', NULL, NULL), (NULL, 'Mrs', 'Mrs', NULL, NULL), (NULL, 'Ms', 'Ms', NULL, NULL), (NULL, 'Dr', 'Dr', NULL, NULL);
