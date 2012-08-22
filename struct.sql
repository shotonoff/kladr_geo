SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Структура таблицы `doma`
--

CREATE TABLE IF NOT EXISTS `doma` (
  `name` varchar(255) NOT NULL,
  `korp` varchar(255) NOT NULL,
  `socr` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `index` varchar(25) DEFAULT NULL,
  `gninmb` varchar(25) DEFAULT NULL,
  `uno` varchar(255) NOT NULL,
  `ocatd` varchar(255) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `geolocation`
--

CREATE TABLE IF NOT EXISTS `geolocation` (
  `ip` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `country` varchar(255) CHARACTER SET utf8 NOT NULL,
  `city` varchar(255) CHARACTER SET utf8 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `region` varchar(60) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Структура таблицы `kladr`
--

CREATE TABLE IF NOT EXISTS `kladr` (
  `name` varchar(255) NOT NULL,
  `socr` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `zip` varchar(255) DEFAULT NULL,
  `code_gninmb` varchar(255) DEFAULT NULL,
  `uno` varchar(255) NOT NULL,
  `ocatd` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `socrbase`
--

CREATE TABLE IF NOT EXISTS `socrbase` (
  `level` varchar(255) NOT NULL,
  `scname` varchar(255) NOT NULL,
  `socrname` varchar(255) NOT NULL,
  `kod_t_st` varchar(255) NOT NULL,
  PRIMARY KEY (`kod_t_st`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `street`
--

CREATE TABLE IF NOT EXISTS `street` (
  `name` varchar(255) NOT NULL,
  `socr` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `index` varchar(25) DEFAULT NULL,
  `gninmb` varchar(25) DEFAULT NULL,
  `uno` varchar(255) NOT NULL,
  `ocatd` varchar(255) NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `userlocation`
--

CREATE TABLE IF NOT EXISTS `userlocation` (
  `account` varchar(10) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `code` varchar(255) NOT NULL,
  `zip` int(11) NOT NULL,
  `home` int(11) NOT NULL,
  `section` varchar(255) DEFAULT NULL,
  `geoAddress` varchar(255) CHARACTER SET utf8 NOT NULL,
  `street` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`account`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
