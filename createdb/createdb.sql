/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `scsq_alias`
--

CREATE TABLE IF NOT EXISTS `scsq_alias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `typeid` int(4) NOT NULL,
  `tableid` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_aliasingroups`
--

CREATE TABLE IF NOT EXISTS `scsq_aliasingroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupid` int(4) NOT NULL,
  `aliasid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_groups`
--

CREATE TABLE IF NOT EXISTS `scsq_groups` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `typeid` int(2) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_httpstatus`
--

CREATE TABLE IF NOT EXISTS `scsq_httpstatus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_ipaddress`
--

CREATE TABLE IF NOT EXISTS `scsq_ipaddress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(18) DEFAULT NULL,
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
  `message` varchar(500) COLLATE latin1_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_temptraffic`
--

CREATE TABLE IF NOT EXISTS `scsq_temptraffic` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) DEFAULT NULL,
  `ipaddress` varchar(18) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `httpstatus` varchar(100) DEFAULT NULL,
  `sizeinbytes` int(11) DEFAULT NULL,
  `site` varchar(700) DEFAULT NULL,
  `method` varchar(15) DEFAULT NULL,
  `mime` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scsq_traffic`
--

CREATE TABLE IF NOT EXISTS `scsq_traffic` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `date` int(11) DEFAULT NULL,
  `ipaddress` varchar(18) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `httpstatus` varchar(100) DEFAULT NULL,
  `sizeinbytes` int(11) DEFAULT NULL,
  `site` varchar(700) DEFAULT NULL,
  `method` varchar(15) DEFAULT NULL,
  `mime` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
