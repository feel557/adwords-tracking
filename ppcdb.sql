-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 22 2017 г., 01:34
-- Версия сервера: 5.5.47-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ppcdb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `billing_plans`
--

CREATE TABLE IF NOT EXISTS `billing_plans` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(455) NOT NULL,
  `braintree_plan_id` varchar(455) NOT NULL,
  `price` int(255) NOT NULL,
  `period_days` int(255) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `country_t`
--

CREATE TABLE IF NOT EXISTS `country_t` (
  `country_id` int(5) NOT NULL AUTO_INCREMENT,
  `iso2` char(2) DEFAULT NULL,
  `short_name` varchar(80) NOT NULL DEFAULT '',
  `long_name` varchar(80) NOT NULL DEFAULT '',
  `iso3` char(3) DEFAULT NULL,
  `numcode` varchar(6) DEFAULT NULL,
  `un_member` varchar(12) DEFAULT NULL,
  `calling_code` varchar(8) DEFAULT NULL,
  `cctld` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=251 ;

-- --------------------------------------------------------

--
-- Структура таблицы `dates_range`
--

CREATE TABLE IF NOT EXISTS `dates_range` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(455) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Структура таблицы `default_settings`
--

CREATE TABLE IF NOT EXISTS `default_settings` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(455) NOT NULL,
  `value` varchar(455) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_from` int(255) NOT NULL,
  `user_from_email` varchar(455) NOT NULL,
  `user_to` int(255) NOT NULL,
  `theme` varchar(455) NOT NULL,
  `text` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `view` int(2) NOT NULL,
  `user_to_del` int(2) NOT NULL,
  `user_from_del` int(2) NOT NULL,
  `user_to_del_forever` int(2) NOT NULL,
  `user_from_del_forever` int(2) NOT NULL,
  `spam` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2244 ;

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `password_reminders_email_index` (`email`),
  KEY `password_reminders_token_index` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `queue`
--

CREATE TABLE IF NOT EXISTS `queue` (
  `unique_key` varchar(64) DEFAULT NULL,
  `function_name` varchar(255) DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `data` longblob,
  `when_to_run` bigint(20) DEFAULT NULL,
  UNIQUE KEY `unique_key` (`unique_key`,`function_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_type` int(25) NOT NULL,
  `username` varchar(455) NOT NULL,
  `first_name` varchar(455) NOT NULL,
  `last_name` varchar(455) NOT NULL,
  `company_name` varchar(455) NOT NULL,
  `email` varchar(455) NOT NULL,
  `email_new` varchar(255) NOT NULL,
  `password` varchar(455) NOT NULL,
  `updated_at` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remember_token` varchar(100) NOT NULL,
  `is_admin` int(2) NOT NULL,
  `is_active` int(2) NOT NULL,
  `activation_code` varchar(455) NOT NULL,
  `ava` varchar(455) NOT NULL,
  `phone` varchar(455) NOT NULL,
  `website` varchar(455) NOT NULL,
  `country_id` int(255) NOT NULL,
  `state` varchar(455) NOT NULL,
  `zipcode` int(255) NOT NULL,
  `address1` varchar(455) NOT NULL,
  `address2` varchar(455) NOT NULL,
  `tariff_id` int(255) NOT NULL,
  `credit_card_number` varchar(455) NOT NULL,
  `exp_date_m` int(2) NOT NULL,
  `exp_date_y` int(4) NOT NULL,
  `credit_card_cvv` int(3) NOT NULL,
  `billing_start_date` varchar(455) NOT NULL,
  `billing_plan_id` varchar(455) NOT NULL,
  `braintree_customer_id` varchar(455) NOT NULL,
  `braintree_payment_token` varchar(455) NOT NULL,
  `billing_subscription_id` varchar(255) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `daily_summary_email` int(2) NOT NULL,
  `last_date_daily_summary` varchar(255) NOT NULL,
  `count_expiration_emails` int(12) NOT NULL,
  `last_date_expiration` varchar(255) NOT NULL,
  `trial` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_payments`
--

CREATE TABLE IF NOT EXISTS `users_payments` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `amount` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_tokens`
--

CREATE TABLE IF NOT EXISTS `users_tokens` (
  `user_id` int(255) NOT NULL,
  `token` varchar(455) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `_adwords_campaigns`
--

CREATE TABLE IF NOT EXISTS `_adwords_campaigns` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `manager_adwords_id` varchar(255) NOT NULL,
  `adwords_user_id` varchar(255) NOT NULL,
  `name` varchar(455) NOT NULL,
  `adwords_campaign_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1194 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_adwords_keywords`
--

CREATE TABLE IF NOT EXISTS `_adwords_keywords` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `keyAdwordsId` varchar(455) NOT NULL,
  `keyword_text` varchar(455) NOT NULL,
  `campaign_id` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=85 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_adwords_settings`
--

CREATE TABLE IF NOT EXISTS `_adwords_settings` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(455) NOT NULL,
  `client_secret` varchar(455) NOT NULL,
  `redirect_uri` varchar(455) NOT NULL,
  `developerToken` varchar(455) NOT NULL,
  `userAgent` varchar(455) NOT NULL,
  `managerRefreshToken` varchar(455) NOT NULL,
  `managerClientCustomerId` varchar(455) NOT NULL,
  `client_auth_ini_path` varchar(455) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_adwords_users`
--

CREATE TABLE IF NOT EXISTS `_adwords_users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `internal_user_id` int(255) NOT NULL,
  `adwords_user_id` varchar(255) NOT NULL,
  `adwords_refresh_token` varchar(455) NOT NULL,
  `adwords_name` varchar(455) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `internal_name` varchar(455) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=85 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_adwords_users_nonmanager`
--

CREATE TABLE IF NOT EXISTS `_adwords_users_nonmanager` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `manager_adwords_id` varchar(255) NOT NULL,
  `adwords_user_id` varchar(255) NOT NULL,
  `adwords_name` varchar(455) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='managed accounts under managers acc. in table adwords_users' AUTO_INCREMENT=104 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers`
--

CREATE TABLE IF NOT EXISTS `_trackers` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(455) NOT NULL,
  `user` int(255) NOT NULL,
  `tracking_level` int(255) NOT NULL,
  `tracking_item` varchar(455) NOT NULL,
  `landing_page` varchar(455) NOT NULL,
  `email_1_notification` varchar(455) NOT NULL,
  `email_2_notification` varchar(455) NOT NULL,
  `act` int(2) NOT NULL,
  `is_deleted` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_data`
--

CREATE TABLE IF NOT EXISTS `_trackers_data` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(255) NOT NULL,
  `click_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `click_timestamp` int(255) NOT NULL,
  `user_ip` varchar(455) NOT NULL,
  `ip_location_coordinates` varchar(455) NOT NULL,
  `ip_location` text NOT NULL,
  `user_browser` varchar(455) NOT NULL,
  `user_cookies` varchar(455) NOT NULL,
  `user_referer_page` varchar(455) NOT NULL,
  `user_os` varchar(455) NOT NULL,
  `device_is_mobile` int(2) NOT NULL,
  `tracking_keyword` varchar(455) NOT NULL,
  `device_hostname` varchar(455) NOT NULL,
  `remote_port` int(255) NOT NULL,
  `adwords_input_data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tracker_id` (`tracker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4443 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_data_blocked_ip`
--

CREATE TABLE IF NOT EXISTS `_trackers_data_blocked_ip` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(255) NOT NULL,
  `ip` varchar(455) NOT NULL,
  `insert_timestamp` int(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_data_statistic`
--

CREATE TABLE IF NOT EXISTS `_trackers_data_statistic` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(255) NOT NULL,
  `alert_1_views` int(255) NOT NULL,
  `alert_2_views` int(255) NOT NULL,
  `email_sent_count` int(255) NOT NULL,
  `shown_warnings` int(255) NOT NULL,
  `ip_blocked` int(255) NOT NULL,
  `redirected_alt_url_count` int(255) NOT NULL,
  `placement_blocked` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_ip_whitelists`
--

CREATE TABLE IF NOT EXISTS `_trackers_ip_whitelists` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` int(255) NOT NULL,
  `tracker_id` int(255) NOT NULL,
  `ip` varchar(455) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tracker_id` (`tracker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_rules`
--

CREATE TABLE IF NOT EXISTS `_trackers_rules` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(255) NOT NULL,
  `rule_id` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tracker_id` (`tracker_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=137 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_rules_default`
--

CREATE TABLE IF NOT EXISTS `_trackers_rules_default` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `level` int(255) NOT NULL,
  `rule_id` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_rules_users`
--

CREATE TABLE IF NOT EXISTS `_trackers_rules_users` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `number_of_clicks` int(255) NOT NULL,
  `time_amount` int(255) NOT NULL,
  `alert_message` text NOT NULL,
  `send_alert` int(2) NOT NULL,
  `block_ip` int(2) NOT NULL,
  `show_message` int(2) NOT NULL,
  `alert_level` int(2) NOT NULL,
  `act` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=139 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_tasks_current`
--

CREATE TABLE IF NOT EXISTS `_trackers_tasks_current` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `tracker_id` int(255) NOT NULL,
  `task_type` varchar(455) NOT NULL,
  `value` varchar(455) NOT NULL,
  `act` int(2) NOT NULL,
  `updated_at` varchar(455) NOT NULL,
  `created` varchar(455) NOT NULL,
  `errors` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30003 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_tasks_types`
--

CREATE TABLE IF NOT EXISTS `_trackers_tasks_types` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(455) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_trackers_users_domains`
--

CREATE TABLE IF NOT EXISTS `_trackers_users_domains` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user` int(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `domains_count` int(11) NOT NULL,
  `domains` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Структура таблицы `_tracking_level`
--

CREATE TABLE IF NOT EXISTS `_tracking_level` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `value` varchar(455) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `___archive_tracking_data`
--

CREATE TABLE IF NOT EXISTS `___archive_tracking_data` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(455) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
