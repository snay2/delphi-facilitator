--
-- Database: `snay2_delphi`
--

-- --------------------------------------------------------

--
-- Table structure for table `estimates`
--

CREATE TABLE IF NOT EXISTS `estimates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estimate_id` int(11) NOT NULL,
  `round` int(11) NOT NULL,
  `low` decimal(9,1) NOT NULL,
  `low_unit` varchar(1) NOT NULL,
  `high` decimal(9,1) NOT NULL,
  `high_unit` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1240 ;

-- --------------------------------------------------------

--
-- Table structure for table `rounds`
--

CREATE TABLE IF NOT EXISTS `rounds` (
  `estimate_id` int(11) NOT NULL,
  `round` int(11) NOT NULL DEFAULT '1',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

