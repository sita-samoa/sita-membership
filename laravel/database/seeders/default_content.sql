-- Add Users @todo

-- Add Membership Type Options
INSERT INTO `membership_types` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'Full', 'Full', NULL, NULL), (NULL, 'Associate', 'Associate', NULL, NULL), (NULL, 'Affiliate', 'Affiliate', NULL, NULL), (NULL, 'Student', 'Student', NULL, NULL), (NULL, 'Fellow', 'Fellow', NULL, NULL);

-- Add Gender Options
INSERT INTO `genders` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'm', 'Male', NULL, NULL), (NULL, 'f', 'Female', NULL, NULL), (NULL, 'g', 'Gender non-conforming', NULL, NULL), (NULL, 'n', 'Non Binary', NULL, NULL), (NULL, 'p', 'Prefer not to say', NULL, NULL);

-- Add Title Options
INSERT INTO `titles` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'Mr', 'Mr', NULL, NULL), (NULL, 'Mrs', 'Mrs', NULL, NULL), (NULL, 'Ms', 'Ms', NULL, NULL), (NULL, 'Dr', 'Dr', NULL, NULL);

-- Add Membership Statuses
INSERT INTO `membership_statuses` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'p', 'Pending', NULL, NULL), (NULL, 'a', 'Active', NULL, NULL), (NULL, 'e', 'Expired', NULL, NULL), (NULL, 'b', 'Banned', NULL, NULL);

-- Add Membership Application Statuses
INSERT INTO `membership_application_statuses` (`id`, `code`, `title`, `created_at`, `updated_at`) VALUES (NULL, 'draft', 'Draft', NULL, NULL), (NULL, 'submitted', 'Submitted', NULL, NULL), (NULL, 'endorsed', 'Endorsed', NULL, NULL), (NULL, 'accepted', 'Accepted', NULL, NULL);
