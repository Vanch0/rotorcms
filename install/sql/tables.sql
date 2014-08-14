--
-- Структура таблицы `admlog`
--

CREATE TABLE IF NOT EXISTS `admlog` (
  `admlog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admlog_user` varchar(20) NOT NULL,
  `admlog_request` varchar(255) NOT NULL DEFAULT '',
  `admlog_referer` varchar(255) NOT NULL DEFAULT '',
  `admlog_ip` varchar(20) NOT NULL DEFAULT '',
  `admlog_brow` varchar(25) NOT NULL DEFAULT '',
  `admlog_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`admlog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `advert`
--

CREATE TABLE IF NOT EXISTS `advert` (
  `adv_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `adv_url` varchar(100) NOT NULL,
  `adv_title` varchar(100) NOT NULL,
  `adv_color` varchar(10) NOT NULL DEFAULT '',
  `adv_user` varchar(20) NOT NULL,
  PRIMARY KEY (`adv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `antimat`
--

CREATE TABLE IF NOT EXISTS `antimat` (
  `mat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mat_string` varchar(100) NOT NULL,
  PRIMARY KEY (`mat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `avatars`
--

CREATE TABLE IF NOT EXISTS `avatars` (
  `avatars_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `avatars_cats` smallint(4) unsigned NOT NULL,
  `avatars_name` varchar(20) NOT NULL,
  PRIMARY KEY (`avatars_id`),
  KEY `avatars_cats` (`avatars_cats`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ban`
--

CREATE TABLE IF NOT EXISTS `ban` (
  `ban_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ban_ip` varchar(15) NOT NULL,
  `ban_user` varchar(20) NOT NULL DEFAULT '',
  `ban_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ban_id`),
  UNIQUE KEY `ban_ip` (`ban_ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `banhist`
--

CREATE TABLE IF NOT EXISTS `banhist` (
  `ban_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ban_user` varchar(20) NOT NULL,
  `ban_send` varchar(20) NOT NULL,
  `ban_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ban_reason` text NOT NULL,
  `ban_term` int(11) unsigned NOT NULL DEFAULT '0',
  `ban_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ban_id`),
  KEY `ban_user` (`ban_user`),
  KEY `ban_time` (`ban_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `blacklist`
--

CREATE TABLE IF NOT EXISTS `blacklist` (
  `black_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `black_type` tinyint(1) unsigned NOT NULL,
  `black_value` varchar(100) NOT NULL,
  `black_user` varchar(20) NOT NULL DEFAULT '',
  `black_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`black_id`),
  KEY `black_type` (`black_type`),
  KEY `black_value` (`black_value`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `blogs_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `blogs_cats_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `blogs_user` varchar(20) NOT NULL,
  `blogs_title` varchar(50) NOT NULL,
  `blogs_text` text NOT NULL,
  `blogs_tags` varchar(100) NOT NULL,
  `blogs_rating` mediumint(8) NOT NULL DEFAULT '0',
  `blogs_read` int(11) unsigned NOT NULL DEFAULT '0',
  `blogs_comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `blogs_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`blogs_id`),
  KEY `blogs_user` (`blogs_user`),
  KEY `blogs_time` (`blogs_time`),
  KEY `blogs_cats_id` (`blogs_cats_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `bookmarks`
--

CREATE TABLE IF NOT EXISTS `bookmarks` (
  `book_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `book_user` varchar(20) NOT NULL,
  `book_topic` mediumint(8) unsigned NOT NULL,
  `book_forum` smallint(4) unsigned NOT NULL,
  `book_posts` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`book_id`),
  KEY `book_user` (`book_user`),
  KEY `book_forum` (`book_forum`),
  KEY `book_topic` (`book_topic`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `cats`
--

CREATE TABLE IF NOT EXISTS `cats` (
  `cats_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `cats_order` smallint(4) unsigned NOT NULL DEFAULT '0',
  `cats_parent` smallint(4) unsigned NOT NULL DEFAULT '0',
  `cats_name` varchar(100) NOT NULL,
  `cats_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cats_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `catsblog`
--

CREATE TABLE IF NOT EXISTS `catsblog` (
  `cats_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `cats_order` smallint(4) unsigned NOT NULL DEFAULT '0',
  `cats_name` varchar(100) NOT NULL,
  `cats_count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cats_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `changemail`
--

CREATE TABLE IF NOT EXISTS `changemail` (
  `change_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `change_user` varchar(20) NOT NULL,
  `change_mail` varchar(50) NOT NULL,
  `change_key` varchar(25) NOT NULL,
  `change_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`change_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `chat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `chat_user` varchar(20) NOT NULL,
  `chat_text` text NOT NULL,
  `chat_ip` varchar(20) NOT NULL,
  `chat_brow` varchar(25) NOT NULL,
  `chat_time` int(11) unsigned NOT NULL,
  `chat_edit` varchar(20) NOT NULL DEFAULT '',
  `chat_edit_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`chat_id`),
  KEY `chat_time` (`chat_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `commblog`
--

CREATE TABLE IF NOT EXISTS `commblog` (
  `commblog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commblog_cats` smallint(4) unsigned NOT NULL,
  `commblog_blog` int(11) unsigned NOT NULL,
  `commblog_text` text NOT NULL,
  `commblog_author` varchar(20) NOT NULL,
  `commblog_time` int(11) unsigned NOT NULL DEFAULT '0',
  `commblog_ip` varchar(20) NOT NULL,
  `commblog_brow` varchar(25) NOT NULL,
  PRIMARY KEY (`commblog_id`),
  KEY `commblog_blog` (`commblog_blog`),
  KEY `commblog_time` (`commblog_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `commload`
--

CREATE TABLE IF NOT EXISTS `commload` (
  `commload_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commload_cats` smallint(4) unsigned NOT NULL,
  `commload_down` mediumint(8) unsigned NOT NULL,
  `commload_text` text NOT NULL,
  `commload_author` varchar(20) NOT NULL,
  `commload_time` int(11) unsigned NOT NULL DEFAULT '0',
  `commload_ip` varchar(20) NOT NULL,
  `commload_brow` varchar(25) NOT NULL,
  PRIMARY KEY (`commload_id`),
  KEY `commload_down` (`commload_down`),
  KEY `commload_time` (`commload_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `commnews`
--

CREATE TABLE IF NOT EXISTS `commnews` (
  `commnews_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commnews_news_id` mediumint(8) unsigned NOT NULL,
  `commnews_text` text NOT NULL,
  `commnews_author` varchar(20) NOT NULL,
  `commnews_time` int(11) unsigned NOT NULL,
  `commnews_ip` varchar(20) NOT NULL,
  `commnews_brow` varchar(25) NOT NULL,
  PRIMARY KEY (`commnews_id`),
  KEY `commnews_news_id` (`commnews_news_id`),
  KEY `commnews_time` (`commnews_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `commphoto`
--

CREATE TABLE IF NOT EXISTS `commphoto` (
  `commphoto_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `commphoto_gid` mediumint(8) unsigned NOT NULL,
  `commphoto_text` text NOT NULL,
  `commphoto_user` varchar(20) NOT NULL,
  `commphoto_time` int(11) unsigned NOT NULL DEFAULT '0',
  `commphoto_ip` varchar(20) NOT NULL,
  `commphoto_brow` varchar(25) NOT NULL,
  PRIMARY KEY (`commphoto_id`),
  KEY `commphoto_gid` (`commphoto_gid`),
  KEY `commphoto_time` (`commphoto_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `counter`
--

CREATE TABLE IF NOT EXISTS `counter` (
  `count_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `count_hours` mediumint(8) unsigned NOT NULL,
  `count_days` mediumint(8) unsigned NOT NULL,
  `count_allhosts` int(11) unsigned NOT NULL,
  `count_allhits` int(11) unsigned NOT NULL,
  `count_dayhosts` mediumint(8) unsigned NOT NULL,
  `count_dayhits` mediumint(8) unsigned NOT NULL,
  `count_hosts24` mediumint(8) unsigned NOT NULL,
  `count_hits24` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`count_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `counter24`
--

CREATE TABLE IF NOT EXISTS `counter24` (
  `count_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `count_hour` mediumint(8) unsigned NOT NULL,
  `count_hosts` mediumint(8) unsigned NOT NULL,
  `count_hits` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`count_id`),
  UNIQUE KEY `count_hour` (`count_hour`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `counter31`
--

CREATE TABLE IF NOT EXISTS `counter31` (
  `count_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `count_days` mediumint(8) unsigned NOT NULL,
  `count_hosts` mediumint(8) unsigned NOT NULL,
  `count_hits` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`count_id`),
  UNIQUE KEY `count_days` (`count_days`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `downs`
--

CREATE TABLE IF NOT EXISTS `downs` (
  `downs_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `downs_cats_id` smallint(4) unsigned NOT NULL DEFAULT '0',
  `downs_title` varchar(100) NOT NULL,
  `downs_text` text NOT NULL,
  `downs_link` varchar(50) NOT NULL,
  `downs_user` varchar(20) NOT NULL,
  `downs_author` varchar(50) NOT NULL,
  `downs_site` varchar(50) NOT NULL DEFAULT '',
  `downs_screen` varchar(50) NOT NULL DEFAULT '',
  `downs_time` int(11) unsigned NOT NULL DEFAULT '0',
  `downs_comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `downs_raiting` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `downs_rated` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `downs_load` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `downs_last_load` int(11) unsigned NOT NULL DEFAULT '0',
  `downs_app` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `downs_notice` text NOT NULL,
  `downs_active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`downs_id`),
  KEY `downs_cats_id` (`downs_cats_id`),
  KEY `downs_time` (`downs_time`),
  FULLTEXT KEY `downs_text` (`downs_text`),
  FULLTEXT KEY `downs_title` (`downs_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `error`
--

CREATE TABLE IF NOT EXISTS `error` (
  `error_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `error_num` smallint(4) unsigned NOT NULL,
  `error_request` varchar(255) NOT NULL DEFAULT '',
  `error_referer` varchar(255) NOT NULL DEFAULT '',
  `error_username` varchar(20) NOT NULL DEFAULT '',
  `error_ip` varchar(20) NOT NULL DEFAULT '',
  `error_brow` varchar(25) NOT NULL DEFAULT '',
  `error_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`error_id`),
  KEY `error_num` (`error_num`,`error_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `files_forum`
--

CREATE TABLE IF NOT EXISTS `files_forum` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_topics_id` mediumint(8) unsigned NOT NULL,
  `file_posts_id` int(11) unsigned NOT NULL,
  `file_hash` varchar(40) NOT NULL,
  `file_name` varchar(60) NOT NULL,
  `file_size` int(11) unsigned NOT NULL,
  `file_user` varchar(20) NOT NULL,
  `file_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`file_id`),
  KEY `file_topics_id` (`file_topics_id`),
  KEY `file_posts_id` (`file_posts_id`),
  KEY `file_user` (`file_user`),
  KEY `file_time` (`file_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `flood`
--

CREATE TABLE IF NOT EXISTS `flood` (
  `flood_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `flood_user` varchar(20) NOT NULL,
  `flood_page` varchar(30) NOT NULL,
  `flood_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`flood_id`),
  KEY `flood_user` (`flood_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `forums_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `forums_order` smallint(4) unsigned NOT NULL DEFAULT '0',
  `forums_parent` smallint(4) unsigned NOT NULL DEFAULT '0',
  `forums_title` varchar(50) NOT NULL,
  `forums_desc` varchar(100) NOT NULL DEFAULT '',
  `forums_topics` int(11) unsigned NOT NULL DEFAULT '0',
  `forums_posts` int(11) unsigned NOT NULL DEFAULT '0',
  `forums_last_id` int(11) unsigned NOT NULL DEFAULT '0',
  `forums_last_themes` varchar(50) NOT NULL DEFAULT '',
  `forums_last_user` varchar(20) NOT NULL DEFAULT '',
  `forums_last_time` int(11) unsigned NOT NULL DEFAULT '0',
  `forums_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`forums_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `guest`
--

CREATE TABLE IF NOT EXISTS `guest` (
  `guest_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `guest_user` varchar(20) NOT NULL,
  `guest_text` text NOT NULL,
  `guest_ip` varchar(20) NOT NULL,
  `guest_brow` varchar(25) NOT NULL,
  `guest_time` int(11) unsigned NOT NULL,
  `guest_reply` text NOT NULL,
  `guest_edit` varchar(20) NOT NULL DEFAULT '',
  `guest_edit_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`guest_id`),
  KEY `guest_time` (`guest_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `inbox`
--

CREATE TABLE IF NOT EXISTS `inbox` (
  `inbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inbox_user` varchar(20) NOT NULL,
  `inbox_author` varchar(20) NOT NULL,
  `inbox_text` text NOT NULL,
  `inbox_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`inbox_id`),
  KEY `inbox_user` (`inbox_user`),
  KEY `inbox_time` (`inbox_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `input`
--

CREATE TABLE IF NOT EXISTS `input` (
  `input_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `input_mail` varchar(50) NOT NULL,
  `input_code` varchar(25) NOT NULL,
  `input_ip` varchar(20) NOT NULL,
  `input_brow` varchar(25) NOT NULL,
  `input_time` int(11) unsigned NOT NULL,
  `input_user` varchar(20) NOT NULL,
  `input_cms` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`input_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `invite`
--

CREATE TABLE IF NOT EXISTS `invite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(15) NOT NULL,
  `user` varchar(20) NOT NULL,
  `invited` varchar(20) NOT NULL DEFAULT '',
  `used` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `used` (`used`),
  KEY `time` (`time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `loads`
--

CREATE TABLE IF NOT EXISTS `loads` (
  `loads_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `loads_down` mediumint(8) unsigned NOT NULL,
  `loads_ip` varchar(20) NOT NULL,
  `loads_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`loads_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `login_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login_user` varchar(20) NOT NULL,
  `login_ip` varchar(15) NOT NULL,
  `login_brow` varchar(25) NOT NULL,
  `login_time` int(11) unsigned NOT NULL,
  `login_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`login_id`),
  KEY `login_user` (`login_user`),
  KEY `login_time` (`login_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `navigation`
--

CREATE TABLE IF NOT EXISTS `navigation` (
  `nav_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nav_url` varchar(100) NOT NULL,
  `nav_title` varchar(100) NOT NULL,
  `nav_order` smallint(4) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`nav_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `news_title` varchar(100) NOT NULL,
  `news_text` text NOT NULL,
  `news_author` varchar(20) NOT NULL,
  `news_image` varchar(30) NOT NULL DEFAULT '',
  `news_time` int(11) unsigned NOT NULL DEFAULT '0',
  `news_comments` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `news_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `news_top` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`news_id`),
  KEY `news_time` (`news_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `note`
--

CREATE TABLE IF NOT EXISTS `note` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `note_user` varchar(20) NOT NULL,
  `note_text` text NOT NULL,
  `note_edit` varchar(20) NOT NULL,
  `note_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`note_id`),
  UNIQUE KEY `note_user` (`note_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `notebook`
--

CREATE TABLE IF NOT EXISTS `notebook` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `note_user` varchar(20) NOT NULL,
  `note_text` text NOT NULL,
  `note_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`note_id`),
  UNIQUE KEY `note_user` (`note_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `notice`
--

CREATE TABLE IF NOT EXISTS `notice` (
  `notice_id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `notice_name` varchar(100) NOT NULL,
  `notice_text` text NOT NULL,
  `notice_user` varchar(20) NOT NULL DEFAULT '',
  `notice_time` int(11) unsigned NOT NULL DEFAULT '0',
  `notice_protect` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`notice_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `online`
--

CREATE TABLE IF NOT EXISTS `online` (
  `online_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `online_ip` varchar(15) NOT NULL,
  `online_brow` varchar(25) NOT NULL,
  `online_time` int(11) unsigned NOT NULL,
  `online_user` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`online_id`),
  KEY `online_ip` (`online_ip`),
  KEY `online_time` (`online_time`),
  KEY `online_user` (`online_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `outbox`
--

CREATE TABLE IF NOT EXISTS `outbox` (
  `outbox_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `outbox_user` varchar(20) NOT NULL,
  `outbox_author` varchar(20) NOT NULL,
  `outbox_text` text NOT NULL,
  `outbox_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`outbox_id`),
  KEY `outbox_user` (`outbox_user`),
  KEY `outbox_time` (`outbox_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `photo`
--

CREATE TABLE IF NOT EXISTS `photo` (
  `photo_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `photo_user` varchar(20) NOT NULL,
  `photo_title` varchar(50) NOT NULL,
  `photo_text` text NOT NULL,
  `photo_link` varchar(30) NOT NULL,
  `photo_time` int(11) unsigned NOT NULL,
  `photo_rating` mediumint(8) NOT NULL DEFAULT '0',
  `photo_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `photo_comments` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `photo_user` (`photo_user`),
  KEY `photo_time` (`photo_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `posts_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `posts_forums_id` smallint(4) unsigned NOT NULL,
  `posts_topics_id` mediumint(8) unsigned NOT NULL,
  `posts_user` varchar(20) NOT NULL,
  `posts_text` text NOT NULL,
  `posts_time` int(11) unsigned NOT NULL,
  `posts_ip` varchar(15) NOT NULL,
  `posts_brow` varchar(25) NOT NULL,
  `posts_edit` varchar(20) NOT NULL DEFAULT '',
  `posts_edit_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`posts_id`),
  KEY `posts_forums_id` (`posts_forums_id`),
  KEY `posts_user` (`posts_user`),
  KEY `posts_topics_id` (`posts_topics_id`,`posts_time`),
  FULLTEXT KEY `posts_text` (`posts_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pyramid`
--

CREATE TABLE IF NOT EXISTS `pyramid` (
  `pyramid_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pyramid_link` varchar(50) NOT NULL,
  `pyramid_name` varchar(50) NOT NULL,
  `pyramid_user` varchar(20) NOT NULL,
  PRIMARY KEY (`pyramid_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ratedblog`
--

CREATE TABLE IF NOT EXISTS `ratedblog` (
  `rated_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rated_blog` int(11) unsigned NOT NULL,
  `rated_user` varchar(20) NOT NULL,
  `rated_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`rated_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rateddown`
--

CREATE TABLE IF NOT EXISTS `rateddown` (
  `rated_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rated_down` mediumint(8) unsigned NOT NULL,
  `rated_user` varchar(20) NOT NULL,
  `rated_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`rated_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `ratedphoto`
--

CREATE TABLE IF NOT EXISTS `ratedphoto` (
  `rated_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rated_photo` int(11) unsigned NOT NULL,
  `rated_user` varchar(20) NOT NULL,
  `rated_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`rated_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rating`
--

CREATE TABLE IF NOT EXISTS `rating` (
  `rating_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rating_user` varchar(20) NOT NULL,
  `rating_login` varchar(20) NOT NULL,
  `rating_text` text NOT NULL,
  `rating_vote` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rating_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rating_id`),
  KEY `rating_user` (`rating_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `readblog`
--

CREATE TABLE IF NOT EXISTS `readblog` (
  `read_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `read_blog` int(11) unsigned NOT NULL,
  `read_ip` varchar(20) NOT NULL,
  `read_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`read_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rekuser`
--

CREATE TABLE IF NOT EXISTS `rekuser` (
  `rek_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `rek_site` varchar(50) NOT NULL,
  `rek_name` varchar(50) NOT NULL,
  `rek_color` varchar(10) NOT NULL DEFAULT '',
  `rek_bold` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `rek_user` varchar(20) NOT NULL,
  `rek_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`rek_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `rules`
--

CREATE TABLE IF NOT EXISTS `rules` (
  `rules_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT,
  `rules_text` text NOT NULL,
  `rules_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`rules_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `setting_name` varchar(25) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  PRIMARY KEY (`setting_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `smiles`
--

CREATE TABLE IF NOT EXISTS `smiles` (
  `smiles_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `smiles_cats` smallint(4) unsigned NOT NULL,
  `smiles_name` varchar(25) NOT NULL,
  `smiles_code` varchar(20) NOT NULL,
  PRIMARY KEY (`smiles_id`),
  KEY `smiles_cats` (`smiles_cats`),
  KEY `smiles_code` (`smiles_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `spam`
--

CREATE TABLE IF NOT EXISTS `spam` (
  `spam_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `spam_key` tinyint(1) unsigned NOT NULL,
  `spam_idnum` int(11) unsigned NOT NULL,
  `spam_user` varchar(20) NOT NULL,
  `spam_login` varchar(20) NOT NULL,
  `spam_text` text NOT NULL,
  `spam_time` int(11) unsigned NOT NULL,
  `spam_addtime` int(11) unsigned NOT NULL,
  `spam_link` varchar(100) NOT NULL,
  PRIMARY KEY (`spam_id`),
  KEY `spam_key` (`spam_key`),
  KEY `spam_time` (`spam_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `status_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `status_topoint` mediumint(8) unsigned NOT NULL,
  `status_point` mediumint(8) unsigned NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `status_color` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`status_id`),
  KEY `status_topoint` (`status_topoint`),
  KEY `status_point` (`status_point`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `topics`
--

CREATE TABLE IF NOT EXISTS `topics` (
  `topics_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `topics_forums_id` smallint(4) unsigned NOT NULL,
  `topics_title` varchar(50) NOT NULL,
  `topics_author` varchar(20) NOT NULL,
  `topics_closed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topics_locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `topics_posts` int(11) unsigned NOT NULL DEFAULT '0',
  `topics_last_user` varchar(20) NOT NULL DEFAULT '',
  `topics_last_time` int(11) unsigned NOT NULL DEFAULT '0',
  `topics_mod` varchar(255) NOT NULL DEFAULT '',
  `topics_note` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`topics_id`),
  KEY `topics_forums_id` (`topics_forums_id`),
  KEY `topics_locked` (`topics_locked`),
  KEY `topics_last_time` (`topics_last_time`),
  FULLTEXT KEY `topics_title` (`topics_title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `transfers`
--

CREATE TABLE IF NOT EXISTS `transfers` (
  `trans_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trans_user` varchar(20) NOT NULL,
  `trans_login` varchar(20) NOT NULL,
  `trans_text` text NOT NULL,
  `trans_summ` int(11) unsigned NOT NULL DEFAULT '0',
  `trans_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`trans_id`),
  KEY `trans_user` (`trans_user`),
  KEY `trans_login` (`trans_login`),
  KEY `trans_time` (`trans_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `trash`
--

CREATE TABLE IF NOT EXISTS `trash` (
  `trash_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `trash_user` varchar(20) NOT NULL,
  `trash_author` varchar(20) NOT NULL,
  `trash_text` text NOT NULL,
  `trash_time` int(11) unsigned NOT NULL,
  `trash_del` int(11) unsigned NOT NULL,
  PRIMARY KEY (`trash_id`),
  KEY `trash_user` (`trash_user`),
  KEY `trash_time` (`trash_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `users_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `users_login` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `users_pass` varchar(40) NOT NULL,
  `users_email` varchar(50) NOT NULL,
  `users_joined` int(11) unsigned NOT NULL,
  `users_level` smallint(4) unsigned NOT NULL DEFAULT '107',
  `users_nickname` varchar(20) NOT NULL DEFAULT '',
  `users_name` varchar(20) NOT NULL DEFAULT '',
  `users_country` varchar(30) NOT NULL DEFAULT '',
  `users_city` varchar(50) NOT NULL DEFAULT '',
  `users_info` text NOT NULL,
  `users_site` varchar(50) NOT NULL DEFAULT '',
  `users_icq` varchar(10) NOT NULL DEFAULT '',
  `users_skype` varchar(32) NOT NULL DEFAULT '',
  `users_jabber` varchar(50) NOT NULL DEFAULT '',
  `users_gender` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users_birthday` varchar(10) NOT NULL DEFAULT '',
  `users_visits` int(11) unsigned NOT NULL DEFAULT '0',
  `users_newprivat` smallint(4) unsigned NOT NULL DEFAULT '0',
  `users_allforum` int(11) unsigned NOT NULL DEFAULT '0',
  `users_allguest` int(11) unsigned NOT NULL DEFAULT '0',
  `users_allcomments` int(11) unsigned NOT NULL DEFAULT '0',
  `users_themes` varchar(20) NOT NULL DEFAULT '',
  `users_postguest` smallint(4) unsigned NOT NULL DEFAULT '0',
  `users_postnews` smallint(4) unsigned NOT NULL DEFAULT '0',
  `users_postprivat` smallint(4) unsigned NOT NULL DEFAULT '0',
  `users_postforum` smallint(4) unsigned NOT NULL DEFAULT '0',
  `users_themesforum` smallint(4) unsigned NOT NULL DEFAULT '0',
  `users_postboard` smallint(4) unsigned NOT NULL DEFAULT '0',
  `users_timezone` varchar(3) NOT NULL DEFAULT '0',
  `users_point` int(11) unsigned NOT NULL DEFAULT '0',
  `users_money` int(11) unsigned NOT NULL DEFAULT '0',
  `users_ban` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users_timeban` int(11) unsigned NOT NULL DEFAULT '0',
  `users_timelastban` int(11) unsigned NOT NULL DEFAULT '0',
  `users_reasonban` text NOT NULL,
  `users_loginsendban` varchar(20) NOT NULL DEFAULT '',
  `users_totalban` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `users_explainban` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users_status` varchar(50) NOT NULL DEFAULT '',
  `users_avatar` varchar(50) NOT NULL DEFAULT '',
  `users_picture` varchar(50) NOT NULL DEFAULT '',
  `users_rating` mediumint(8) NOT NULL DEFAULT '0',
  `users_posrating` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `users_negrating` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `users_keypasswd` varchar(20) NOT NULL DEFAULT '',
  `users_timepasswd` int(11) unsigned NOT NULL DEFAULT '0',
  `users_timelastlogin` int(11) unsigned NOT NULL DEFAULT '0',
  `users_confirmreg` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users_confirmregkey` varchar(30) NOT NULL DEFAULT '',
  `users_secquest` varchar(50) NOT NULL DEFAULT '',
  `users_secanswer` varchar(40) NOT NULL DEFAULT '',
  `users_timenickname` int(11) unsigned NOT NULL DEFAULT '0',
  `users_ipbinding` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users_navigation` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users_newchat` int(11) unsigned NOT NULL DEFAULT '0',
  `users_privacy` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `users_apikey` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`users_id`),
  UNIQUE KEY `users_login` (`users_login`),
  KEY `users_level` (`users_level`),
  KEY `users_email` (`users_email`),
  KEY `users_nickname` (`users_nickname`),
  KEY `users_themes` (`users_themes`),
  KEY `users_point` (`users_point`),
  KEY `users_money` (`users_money`),
  KEY `users_rating` (`users_rating`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `visit`
--

CREATE TABLE IF NOT EXISTS `visit` (
  `visit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `visit_user` varchar(20) NOT NULL,
  `visit_self` varchar(100) NOT NULL DEFAULT '',
  `visit_page` varchar(100) NOT NULL DEFAULT '',
  `visit_ip` varchar(15) NOT NULL DEFAULT '',
  `visit_count` int(11) unsigned NOT NULL DEFAULT '0',
  `visit_nowtime` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`visit_id`),
  UNIQUE KEY `visit_user` (`visit_user`),
  KEY `visit_nowtime` (`visit_nowtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
