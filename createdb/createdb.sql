/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
--

-- --------------------------------------------------------

--
-- Table structure for table `scsq_alias`
--

CREATE TABLE IF NOT EXISTS `scsq_alias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `typeid` int(4) NOT NULL,
  `tableid` varchar(50) NOT NULL,
  `userlogin` varchar(100) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `active` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_aliasingroups`
--

CREATE TABLE IF NOT EXISTS `scsq_aliasingroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(4) NOT NULL,
  `aliasid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_groups`
--

CREATE TABLE IF NOT EXISTS `scsq_groups` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `typeid` int(2) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `userlogin` varchar(100) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `active` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_httpstatus`
--

CREATE TABLE IF NOT EXISTS `scsq_httpstatus` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_ipaddress`
--

CREATE TABLE IF NOT EXISTS `scsq_ipaddress` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(18) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_logins`
--

CREATE TABLE IF NOT EXISTS `scsq_logins` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_logtable`
--

CREATE TABLE IF NOT EXISTS `scsq_logtable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `datestart` int(11) DEFAULT NULL,
  `dateend` int(11) DEFAULT NULL,
  `message` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_quicktraffic`
--

CREATE TABLE IF NOT EXISTS `scsq_quicktraffic` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `login` int(11) DEFAULT NULL,
  `ipaddress` int(11) DEFAULT NULL,
  `site` varchar(800) DEFAULT NULL,
  `sizeinbytes` bigint DEFAULT NULL,
  `httpstatus` int(11) DEFAULT NULL,
  `par` int(11) NOT NULL,
  `numproxy` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_sqper_activerequests`
--

CREATE TABLE IF NOT EXISTS `scsq_sqper_activerequests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `ipaddress` varchar(30) NOT NULL,
  `site` varchar(800) NOT NULL,
  `sizeinbytes` int(11) NOT NULL,
  `seconds` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_sqper_trend10`
--

CREATE TABLE IF NOT EXISTS `scsq_sqper_trend10` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `par` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_temptraffic`
--

CREATE TABLE IF NOT EXISTS `scsq_temptraffic` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `date` varchar(20) DEFAULT NULL,
  `ipaddress` varchar(18) NOT NULL,
  `login` varchar(200) NOT NULL,
  `httpstatus` varchar(200) NOT NULL,
  `sizeinbytes` bigint DEFAULT NULL,
  `site` varchar(700) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `numproxy` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_traffic`
--

CREATE TABLE IF NOT EXISTS `scsq_traffic` (
  `id` bigint  NOT NULL AUTO_INCREMENT,
  `date` int(11) unsigned DEFAULT NULL,
  `ipaddress` smallint(5) unsigned DEFAULT NULL,
  `login` smallint(5) unsigned DEFAULT NULL,
  `httpstatus` smallint(5) unsigned DEFAULT NULL,
  `sizeinbytes` bigint DEFAULT NULL,
  `site` varchar(700) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `mime` varchar(100) DEFAULT NULL,
  `numproxy` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




CREATE TABLE IF NOT EXISTS `scsq_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `scsq_modules_param`
--

CREATE TABLE IF NOT EXISTS `scsq_modules_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(100) NOT NULL,
  `param` varchar(100) NOT NULL,
  `val` varchar(256) NOT NULL,
  `switch` tinyint(4) NOT NULL DEFAULT 0,
  `comment` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scsq_modules_param`
--

INSERT INTO `scsq_modules_param` (`id`, `module`, `param`, `val`, `switch`, `comment`) VALUES
(1, 'Cache', 'enabled', '', 1, 'Enable module'),
(2, 'Global', 'language', 'en', 0, 'Global language'),
(3, 'Global', 'useLoginalias', '', 1, 'Use login alias'),
(4, 'Global', 'useIpaddressalias', 'on', 1, 'Use ip address alias'),
(5, 'Global', 'enableUseiconv', '', 1, 'Use iconv'),
(6, 'Global', 'enableShowDayNameInReports', 'on', 1, 'Show day name in reports'),
(7, 'Global', 'enableTrafficObjectsInStat', '', 1, 'Show traffic objects in stat'),
(8, 'Global', 'refreshPeriod', '5', 0, 'Seconds to autorefresh online report'),
(9, 'Global', 'bandwidth', '10', 0, 'Bandwidth for online reports in MBits'),
(10, 'Global', 'graphtype_trafficbyhours', '0', 0, 'type graph for traffic by hours 0 -line, 1 - histogram'),
(11, 'Global', 'roundTrafficDigit', '-1', 0, 'How many digits to round traffic. If -1 = no round'),
(12, 'Global', 'countTopSitesLimit', '10', 0, 'Limit of top report Traffic Sites'),
(13, 'Global', 'countTopLoginLimit', '10', 0, 'Limit of top report Traffic Logins'),
(14, 'Global', 'countTopIpLimit', '10', 0, 'Limit of top report Traffic Ipaddress'),
(15, 'Global', 'countPopularSitesLimit', '10', 0, 'Limit of top report Popular sites'),
(16, 'Global', 'countWhoDownloadBigFilesLimit', '10', 0, 'Limit of top report WhoDownloadBigFiles'),
(17, 'Global', 'enableNofriends', '', 1, 'Enable hide friends in reports'),
(18, 'Global', 'goodLogins', '', 0, 'Friends list, separate with blank. For example, $goodLogins="Vasya Sergey Petr"'),
(19, 'Global', 'goodIpaddress', '', 0, 'Friends list, separate with blank. For example, $goodIpaddress="172.16.1.1 172.16.5.16"'),
(20, 'Global', 'enableNoSites', '', 1, 'Enable filter good sites. If enable, goodSites were not shown in statistic.'),
(21, 'Global', 'goodSites', '', 0, 'List good sites "vk.me facebook.com ipp" without quotation'),
(22, 'Global', 'csv_decimalSymbol', ',', 0, 'decimal symbol separator for CSV export'),
(23, 'Global', 'globaltheme', 'default', 0, 'theme'),
(24, 'Global', 'enableUseDecode', '', 1, 'use urldecode to decode % characters in request'),
(25, 'Global', 'workingHours', '8-00:12-30:13-00:17-20', 0, 'Set working hours. For example, set two periods From 8:00 to 12:30 and from 13 to 17'),
(26, 'Global', 'showZeroTrafficInReports', '0', 0, 'Show zero traffic in reports'),
(27, 'Global', 'enableFilterSites', '', 1, 'Enable use filter sites. If enable, only filterSites were shown in statistic.'),
(28, 'Global', 'filterSites', '', 0, 'List filter sites "vk.me facebook.com:433 ipp" without quotation.'),
(29, 'Global', 'DefaultRepDate', 'on', 1, 'After start, first report will be opened on yesterday(on) or current day (off).');



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
