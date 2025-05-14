-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2025-03-21 14:16:46
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `vx_cc`
--

-- --------------------------------------------------------

--
-- 表的结构 `cf_admins`
--

CREATE TABLE `cf_admins` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `session` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_admins`
--

INSERT INTO `cf_admins` (`id`, `email`, `username`, `password`, `rank`, `status`, `token`, `session`, `create_time`) VALUES
(1, '137691250@qq.com', 'test111', '$2y$10$H9FCqJp3fS/Rldixx8daGe0wCwN2Q4f/6JQdzvUihBAf5ThvVyX7G', 1, 1, 'SewsSvdJyZO2Qm604f4PrLHcLQaCeMcF', 0, 1696256597);

-- --------------------------------------------------------

--
-- 表的结构 `cf_admin_log`
--

CREATE TABLE `cf_admin_log` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `content` varchar(200) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_admin_log`
--

INSERT INTO `cf_admin_log` (`id`, `name`, `content`, `ip`, `type`, `create_time`) VALUES
(1, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1721477286),
(2, 'test111', '管理员“test111”给【1】充值了CF点：1', '127.0.0.1', 3, 1721477897),
(3, 'test111', '管理员“test111”给【1】充值了CF点：10000', '127.0.0.1', 3, 1721478076),
(4, 'test111', '管理员“test111”给【1】充值了CF点：1', '127.0.0.1', 3, 1721478131),
(5, 'test111', '管理员“test111”给【1】充值了CF点：10000', '127.0.0.1', 3, 1721478140),
(6, 'test111', '管理员“test111”给【1】充值了CF点：1000', '127.0.0.1', 3, 1721478155),
(7, 'test111', '管理员“test111”给【1】充值了CF点：1', '127.0.0.1', 3, 1721478164),
(8, 'test111', '管理员“test111”修改了用户USN：1的信息', '127.0.0.1', 3, 1721478313),
(9, 'test111', '管理员“test111”修改了用户USN：1的信息', '127.0.0.1', 3, 1721478322),
(10, 'test111', '管理员“test111”解封用户USN：1', '127.0.0.1', 3, 1721478332),
(11, 'test111', '管理员“test111”修改了用户USN：1的信息', '127.0.0.1', 3, 1721478506),
(12, 'test111', '管理员“test111”修改了用户1的信息', '127.0.0.1', 3, 1721478514),
(13, 'test111', '管理员“test111”修改了用户1的信息', '127.0.0.1', 3, 1721478521),
(14, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1721561621),
(15, 'test111', '管理员“test111”删除了注册记录', '127.0.0.1', 3, 1721561788),
(16, 'test111', '管理员“test111”给【2】充值了CF点：1000', '127.0.0.1', 3, 1721562229),
(17, 'test111', '管理员“test111”生成了1张Cdk', '127.0.0.1', 3, 1721563451),
(18, 'test111', '管理员“test111”发送了1个物品给USN：zx101018，物品代码：1000000201', '127.0.0.1', 3, 1721564115),
(19, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1721821349),
(20, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1721919200),
(21, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1721974229),
(22, 'test111', '管理员“test111”禁用了代理账号【】', '127.0.0.1', 3, 1721974706),
(23, 'test111', '管理员“test111”启用了代理账号【】', '127.0.0.1', 3, 1721974712),
(24, 'test111', '管理员“test111”删除了代理用户', '127.0.0.1', 3, 1721975843),
(25, 'test111', '管理员“test111”删除了代理用户', '127.0.0.1', 3, 1721975898),
(26, 'test111', '管理员“test111”生成了1张邀请码', '127.0.0.1', 3, 1721976411),
(27, 'test111', '管理员“test111”生成了1张代理邀请码', '127.0.0.1', 3, 1721976471),
(28, 'test111', '管理员“test111”生成了1张代理邀请码', '127.0.0.1', 3, 1721976536),
(29, 'test111', '管理员“test111”生成了1张代理邀请码', '127.0.0.1', 3, 1721976621),
(30, 'test111', '管理员“test111”删除了代理注册邀请码', '127.0.0.1', 3, 1721977330),
(31, 'test111', '管理员“test111”删除了代理注册邀请码', '127.0.0.1', 3, 1721983333),
(32, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1722082105),
(33, 'test111', '管理员“test111”更新了网站基础信息', '127.0.0.1', 3, 1722083149),
(34, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1722085913),
(35, 'test111', '管理员“test111”更新了网站基础信息', '127.0.0.1', 3, 1722085924),
(36, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1722168304),
(37, 'test111', '管理员“test111”删除了代理用户', '127.0.0.1', 3, 1722168715),
(38, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1723770876),
(39, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1730806906),
(40, 'test111', '管理员“test111”登录了系统。', '127.0.0.1', 1, 1741786887);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_code`
--

CREATE TABLE `cf_agency_code` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_code`
--

INSERT INTO `cf_agency_code` (`id`, `uid`, `username`, `code`, `status`, `create_time`) VALUES
(4, 1, '', 'MsNlBLhclQdFIvzaiH3zTeNgUjSDjUDU', 0, 1721978004),
(2, 0, '', 'MUFVvaNsvMbpMP1SLS1gIZDWjPNiT7Wr', 0, 1721976536),
(3, 0, '', 'tcTC310pDvffCmViMkpVjYQYeEF4eW8b', 0, 1721976621),
(5, 1, '', 'btc5KKNMtWB2anspaaYK0aXpBDuUgLtZ', 0, 1721978168),
(25, 1, '', 'xQQDUG2tKz5Oizglk3A0WfzjXpzYI9fU', 0, 1722083116),
(24, 1, '', 'SEoVJltghpNHq4l46Xvsco2fl9m3s8VU', 0, 1722083116),
(23, 1, '', 'dGpDci71v2AWVj3Bp82ntGwqOa88fEim', 0, 1722083116),
(22, 1, '', '2JP34YZmXL0smMnas2EObcEOqSRnJH6a', 0, 1722083116),
(21, 1, '', 'XsYbYs30DcT4O36ctLCm9bpnW0gJTNDL', 0, 1722083116),
(20, 1, '', 'T91BXl6kBRB8oFCI2y2UniXNhbFt4oIy', 0, 1722083116),
(19, 1, '', 'IiIXSyCig8Sf8ZDZFuZpQVuGq4pfYijK', 0, 1722083116),
(18, 1, '', 'HGyX1tAW6mxRLWzKN3iVmQBqPCRtdVOg', 0, 1722083116),
(17, 1, '', 'TqWnxa9Qw4itbPbbbCCp7TfJ3vNnXTbV', 0, 1722083116),
(16, 1, '', 'DiQm0X7lNqyB7DbqH9ahAZL3BdCUmS5U', 0, 1722083116),
(26, 1, '', 'ArBuWEHd3RvbjgP2AmmAtxqm4cwrl82r', 0, 1722083116),
(27, 1, '', '5J1GS6nooUQpnmJwaZKzElc7YLqeWz46', 0, 1722083116),
(28, 1, '', 'qDiekUNPPlyZJASD9nKEjVQxiftQXjXh', 0, 1722083116),
(29, 1, '', 'B1ehcv7mUDKMJxrw4ss0wzt0bljLsfiR', 0, 1722083116),
(30, 1, '', 'IW53SLsEtwbFwqgEYhSwySmPKKioemLL', 0, 1722083116),
(31, 1, '', '7slfG7XH7mtYMLUV6U2RAwghaU94xPZL', 0, 1722083116),
(32, 1, '', 'mxRSQ9iY3RsPdUTvsj9iYmUoJTFkDwEu', 0, 1722083116),
(33, 1, '', 'L7N4UFS4UG9Wkn2mPdxDCu5aXgN6A3Kw', 0, 1722083116),
(34, 1, '', 'nkaSv9khLsPSxoJFlxQX1K9OJGnFhGNP', 0, 1722083116),
(35, 1, '', 'e3gjTdsYIWofNwApXgODOXophcIT6ZOV', 0, 1722083116),
(36, 1, '', 'NTDikCXxVW97ZVSb1ypR1dy6LewQhG9P', 0, 1722083116),
(37, 1, '', 'hLbjaEhjqhwanK5oejcUG0UrNQXo1u9H', 0, 1722083116),
(38, 1, '', 'Ew3nixwsCoOKzUlkfKDvayUDmfZnXsAO', 0, 1722083116),
(39, 1, '', 'O5Xqz0EEJEgmn0NJqtQXjzrxsZpP0n7P', 0, 1722083116),
(40, 1, '', 'atFWyUup5hDfM3hM76ov04wsvdZ1owCB', 0, 1722083116),
(41, 1, '', 'zV4n9EvxrR2TUXGJ7fTVeEcCHFrBxOpG', 0, 1722083116),
(42, 1, '', 'l9p2VEVsWcnxo40ymg6iluGLKVncAGJN', 0, 1722083116),
(43, 1, '', 'Ni9d1aHhDl9kpuY3mnRR86D3d1PFEdp3', 0, 1722083116),
(44, 1, '', 'YbX5kID52DUGAlEk69s4xsnFn7yej4vK', 0, 1722083116),
(45, 1, '', 'EClO5TiUQifFzumcygLxRmNGz4vovLIV', 0, 1722083116),
(46, 1, '', 'FuYXO0gJ1s4TEpOtNyAqnBwgvq7r4moY', 0, 1722083116),
(47, 1, '', 'Rv2EPTArLiBvrEYVbWn5pKGvw6sptuMd', 0, 1722083116),
(48, 1, '', 'CtVj1i3QK2mKZtAuy5xjSlcJxMw7PsNc', 0, 1722083116),
(49, 1, '', 'yx9bKT7VQu3sgYb6jzhrkPe93M005Nwi', 0, 1722083116),
(50, 1, '', 'KenCrttpiri6o2NdVqrKROsnCJMnrOcy', 0, 1722083116),
(51, 1, '', '5qkOoZuuYI1Km2gluI5FAV0RTc3jqytS', 0, 1722083116),
(52, 1, '', '2hh3jlRBOzAl1a1hc0svZzGyyRkk3Y8b', 0, 1722083116),
(53, 1, '', 'Roya1uVFEwwQaFq5ydyWqOXSNzVmM5wh', 0, 1722083116),
(54, 1, '', '7iGhakPNOJ9E0GltzO1itgbnSZIcCL0W', 0, 1722083116),
(55, 1, '', 'n0lZXy1Rp6m85kjnRUKjbvn07o6zkbEl', 0, 1722083116),
(56, 1, '', '9Lla8EQBNfJMQQ6rymdbEon0jFYKowKb', 0, 1722083116),
(57, 1, '', 'oqnNc5nESdQuMeGfHXAiS6qyMqm75coE', 0, 1722083116),
(58, 1, '', 'BuxwISB4JV4wisasfhJCg4tOwyy4L2Fb', 0, 1722083116),
(59, 1, '', 'rGRuH4wVcDobb85e63cuUR9WlN05tdWM', 0, 1722083116),
(60, 1, '', 'cXdbONNZZJNjj4vrzfJ47XFQIMmUuDe8', 0, 1722083116),
(61, 1, '', '2zHiwdZv983CgaIkRjmCf82nya6Qy7p5', 0, 1722083116),
(62, 1, '', 'aysQsZ4f7ARzWzA7LKeopV2juMttVWIn', 0, 1722083116),
(63, 1, '', 'pOX37rQL13G8Lbzhp5uqsV2JxLAgkvje', 0, 1722083116),
(64, 1, '', 'mIXOpHpcavDPPwK3eEViHiKhD9zXgoci', 0, 1722083116),
(65, 1, '', 'SNuHw5wLAlPL02uUlG9nJ4da6WNlXGDr', 0, 1722083116),
(66, 1, '', 'ftMhHtOjNn0vuPQjn5jmzMmJHhOZ3RMB', 0, 1722083116),
(67, 1, '', 'YJT0qShLFi89COPLMoWB8FnyErzsKjUt', 0, 1722083116),
(68, 1, '', '2xaIGFPFlEHNPCQYkhbxR2pB0RMzUWcL', 0, 1722083116),
(69, 1, '', 'Cl5UVzOYmuj2N9TeAUzczxcX5ZJVq8Z2', 0, 1722083116),
(70, 1, '', 'HBobhoWVn2JqZDQt28IYZP5gGXQ6chE8', 0, 1722083116),
(71, 1, '', 'wbfSZ5kciAecPD8WtD05UHY0jCj50clG', 0, 1722083116),
(72, 1, '', 'yRbEULxJajADRdKSRXic1yjinmxFHIYc', 0, 1722083116),
(73, 1, '', 'oAUBPhjpNpNGVj9aHpeRdsov0xZihU9Q', 0, 1722083116),
(74, 1, '', 'BEGvIMdFGB4wIzTmZyjvJC0Kbx7AAISi', 0, 1722083116),
(75, 1, '', 'T0gSTi2DedLk1NYK8IFKBFCNGRCeDmLb', 0, 1722083116),
(76, 1, '', 'gMvW0fUUjF9DKia2tm9CeStBYamhuLtb', 0, 1722083116),
(77, 1, '', 'dW8kQCqFhUr6czM5P2jDrG3RGGKi4whm', 0, 1722083116),
(78, 1, '', 'S93ikUOQSAbs5f4T4yRxBToHf8ZdlY5U', 0, 1722083116),
(79, 1, '', 'a8gmJc2U8ILrXP3rlhPT1MULMHp4Dex1', 0, 1722083116),
(80, 1, '', 'iFnAzOZxAh0kIh7iolVQZy1aXdoI3YQB', 0, 1722083116),
(81, 1, '', 'H7MnJS4N3puOQ4kNNimwOKUoJhhwj75s', 0, 1722083116),
(82, 1, '', 'Ht9Yvhahg3QOOGurn2fvI75cCpZodQV7', 0, 1722083116),
(83, 1, '', 'aj6ZDFrwIOwlWhreAdIbWisb1Q1PYXP8', 0, 1722083116),
(84, 1, '', 'WRJye7TXSNbvxnlFcSwdgObTHczOiPSu', 0, 1722083116),
(85, 1, '', 'BOgbOvE9uK06dM9FzDC1v7zai3DlKvZb', 0, 1722083116),
(86, 1, '', '514YNkRk0JcxXNEkWFzNobVTDfjk6KdP', 0, 1722083116),
(87, 1, '', 'WpWstqtcRWru02aZY7fBm6incp3jxXh8', 0, 1722083116),
(88, 1, '', 'ekAsu7keK8TMVykMB55WUdpGdMX2NTrW', 0, 1722083116),
(89, 1, '', 'Q1QuXAXPAbF2KlN4gkrcCSfEZrH6Cqxi', 0, 1722083116),
(90, 1, '', 'LbEsu4xtRA5SbdAJahKQ2zoLgYbKYWdG', 0, 1722083116),
(91, 1, '', '1gAWstBt4o7vwYPdRdzM2sFZfVcF4Dd3', 0, 1722083116),
(92, 1, '', 'OgrMXUkcgHaTZYiWCqCk5G22qOY8MWXE', 0, 1722083116),
(93, 1, '', '4d5AM9YFUTnSJqRRr9XJUHHUsCOWt34g', 0, 1722083116),
(94, 1, '', 'ZxUxyiOlYA0DiDstBZNu20dtzhFLTWB2', 0, 1722083116),
(95, 1, '', 'TN5V57DKwSBhTyLHJ0Wz7JU4kz81fiTL', 0, 1722083116),
(96, 1, '', 'YOoXyforkidy23M5KOxiKXcdCf39ujjo', 0, 1722083116),
(97, 1, '', '5gFEkrM8Jt6RGvsFVme8a0kV89GIQtOB', 0, 1722083116),
(98, 1, '', '2fjYOI2CAqaiZtVLYRIjbpckIoepNLTB', 0, 1722083116),
(99, 1, '', 'cmKEWqnX1e7eWS1UnZb7bxwNbcjYjBUm', 0, 1722083116),
(100, 1, '', 'QTB5j1DQrVA8b2RRtVI7jXem08pd06Yr', 0, 1722083116),
(101, 1, '', 'kH3H0oL3boIebU5YLPLJj94dvHHmLLFs', 0, 1722083116),
(102, 1, '', 'OW5VdL9fTiDtiBjrt7xT9fOkEE5Pux7J', 0, 1722083116),
(103, 1, '', 'nTMTKvrxVKUsdGLemoM4ZuxnHTXMc4Cu', 0, 1722083116),
(104, 1, '', 'vl0a6va7whNHMcRqrtu4geGbaoBf96sC', 0, 1722083116),
(105, 1, '', 'FUehxSDamp2CwnqPCKpj7D7Aaxm7Lxmn', 0, 1722083116),
(106, 1, '', '41odhjN9wfg9wldVjLfy1bYSgGqXNRoo', 0, 1722083116),
(107, 1, '', 'cDiFkZtBzdVrwB6OEZaeDHmq8Ib5KySG', 0, 1722083116),
(108, 1, '', 'HQTufsCqUhBfZjHKqsvXXHaOvC4x0f01', 0, 1722083116),
(109, 1, '', 'WZvxFFMZKeWV7CQwTjR2s7PrCWnNUF8S', 0, 1722083116),
(110, 1, '', 'VoyETkyK90ZqVBPR7ogU8J8I6FXXsujT', 0, 1722083116),
(111, 1, '', 'XLOHhHHqnbeF8BndINM3UrExjafX5Qib', 0, 1722083116),
(112, 1, '', 'UffXQAVdsgck1g1FWzmGs3x1jTcyI6yb', 0, 1722083116),
(113, 1, '', 'hZPhKCFVgjB9Jz6es0lyRoXTUfvJZcI8', 0, 1722083116),
(114, 1, '', 'feeuAju9mVF90jUUBlLJDqKPpXDe4get', 0, 1722083116),
(115, 1, '', '9EOXMYq9Px4s4wwX8vGbrae9upHd2Kro', 0, 1722083116),
(116, 1, '', 'HqVFMEOTJWira1rIW0YvtOelAuZn3Z14', 0, 1722083116),
(117, 1, '', 'z4oxXoctp34xpY5clo8vsHRrVkRjSsK6', 0, 1722083116),
(118, 1, '', 'YGBiLNsm4A9g2jPX4OarJX9pNBTWawvg', 0, 1722083116),
(119, 1, '', 'KJ542p0aCoeQ72mRlyKvW9JLlRIfJSfa', 0, 1722083116),
(120, 1, '', 'B0CUskpiugMQ9AIc1b2LxBkCtunUe9RP', 0, 1722083116),
(121, 1, '', 'kMGcLoetn22QpiyXgXfHicGwATdyk1ML', 0, 1722083116),
(122, 1, '', 'iTnuwRLlzkP6GK7SCbdIBKjxJnngMxfB', 0, 1722083116),
(123, 1, '', 'Zqoamz0Kz2pK2EIHdetFfWwflA2YWdru', 0, 1722083116),
(124, 1, '', '2swJAfTGF6OZKrQL5IGbNKMxstADfkS8', 0, 1722083116),
(125, 1, '', '1IBQGtXHNbThraj30dMkbPhAxyhwhy14', 0, 1722083116),
(126, 1, '', 'ovZqjAIZsWIJIptIIw18wmXyKQFVH3ik', 0, 1722083116),
(127, 1, '', '4qd1sIxWv1vJwPD7lOxpRAYv9WZcYauM', 0, 1722083116),
(128, 1, '', 'ai9fpFyaO0aw8kiZSkI26Rw7TboNt0TD', 0, 1722083116),
(129, 1, '', 'xDn1KVzcTaVW98Rw0vPD5solZrb1KULC', 0, 1722083116),
(130, 1, '', 'OyL4kcCnZ19Htrii9irCIPbOI7vxvweo', 0, 1722083116),
(131, 1, '', 'B6EV55sjdiGhQKci8a02h8YyNPQvlu1m', 0, 1722083116),
(132, 1, '', '8UJsXwr45afqlPlyX8ljCtG6Vo3Sbo67', 0, 1722083116),
(133, 1, '', 'ivcSdBiZxswaKTNIlldEHsJtgUleFFUU', 0, 1722083116),
(134, 1, '', 'lsX00OGkr8UAIUzNC6FVhZ949zqMstSG', 0, 1722083116),
(135, 1, '', 'dhTfLLvkJbhGr0tFqfeHYtbNTKes7aL8', 0, 1722083116),
(136, 1, '', 'aNWDp6aNJT8HJCqTjJoCFUFL5LYDCqGh', 0, 1722083116),
(137, 1, '', 'MTVFFqfhzFZTucooFRiWAMzW3RjSdyYw', 0, 1722083116),
(138, 1, '', '8J96iOUP3d9JwuD5TmrugN4ekPXngfXp', 0, 1722083116),
(139, 1, '', 'dF70todFMtB4iJEOxY3ARa7NfJnkbuxW', 0, 1722083116),
(140, 1, '', '327bBQw5gTxapD9w5hzcemn6Q5cchtUR', 0, 1722083116),
(141, 1, '', 'eT8FHBF4ssKF2A91zS3UPkCm9XB3rUgK', 0, 1722083116),
(142, 1, '', 'vkHeNDq4MuuhLJJ6bL7akbaood7CKkgC', 0, 1722083116),
(143, 1, '', 'Av5RrEockC3B82Z6EozffGoYhCBujUu5', 0, 1722083116),
(144, 1, '', 'Cs29q7f22YWybIM0yObvCDUWygo4jkap', 0, 1722083116),
(145, 1, '', '8KviJ6nOhcG0LVaOmHQMRFgcsSt6Kjav', 0, 1722083116),
(146, 1, '', 'y2sD4BEBTQQMrJhHH8lvgXETZPO47S3R', 0, 1722083116),
(147, 1, '', 'xYHTPDAIzJQ1RenyRsVjjtLbZaehx8sp', 0, 1722083116),
(148, 1, '', 'TFWsxHGAkVoWmuS3c7tzP2diIRtTpCDB', 0, 1722083116),
(149, 1, '', 'E0hwoEdPXWpmTcH2O4W8FwhOWVg9GjjY', 0, 1722083116),
(150, 1, '', 'weY6HNqVQaHEdRlAxYaklIknKs65m08f', 0, 1722083116),
(151, 1, '', 'Fx9ygtIZI8586yVwjdDrljY5w5SzArsx', 0, 1722083116),
(152, 1, '', 'T470jZeNSbYyp0LGs6uXvUoz0oRGJe9E', 0, 1722083116),
(153, 1, '', 'vOYz2C8ZFFKDccg6hWectvR9IQkzs8bU', 0, 1722083116),
(154, 1, '', 'mj1SSIMK4DW5D1LZh5JOgXg3JLbseCpl', 0, 1722083116),
(155, 1, '', '6GXBXJmx2tNvfZ814DDg2vkqKDV262mN', 0, 1722083116),
(156, 1, '', '1BO8yywX3Di9OXKZzDDsPXZFXzGweYWI', 0, 1722083116),
(157, 1, '', 'h1Z2JFe64TdoqygM9uNjX2fTQSYNL3lt', 0, 1722083116),
(158, 1, '', 'K1BKcWmxspgJgetIRsuHDos1DAgMa6aV', 0, 1722083116),
(159, 1, '', '9y0iYtqVxdoDbcZMHJEhLpJMjBYovaam', 0, 1722083116),
(160, 1, '', 'GRBu8zkRvFOJ63ctj5cbtO3FgESpD1VB', 0, 1722083116),
(161, 1, '', '5wJ9k5DkBstKKzISu8J0GeIUlM9hLpCV', 0, 1722083116),
(162, 1, '', 'x8Jhek7Nbu9GaiqA1ddQedE3FwsdNm38', 0, 1722083116),
(163, 1, '', 'ehUxutWSyZzXrAL73JSEvF3bqpEeDgGN', 0, 1722083116),
(164, 1, '', 'I1YEcfKJcRmvF9qp1yMmyfJhuCBZQayU', 0, 1722083116),
(165, 1, '', 't0OJ0lNCyoWNvsJgGe2pfqjoTFqk48UM', 0, 1722083116),
(166, 1, '', 'nYModKBsyUF2YNtV9LDFsMNDX1PSVBVo', 0, 1722083116),
(167, 1, '', 'qeBkhq3tskVceSLV9ZOYD4lM2juhsyOG', 0, 1722083116),
(168, 1, '', 'MhExbK56P0RxarvgvPWVYD6Tys0hetUj', 0, 1722083116),
(169, 1, '', 'Zpjh17H6ixLyWdXf0FeiVr9nE0YJeidX', 0, 1722083116),
(170, 1, '', 'cLLneWCp101ip6tKOENYCMaVtiwep6Yu', 0, 1722083116),
(171, 1, '', 'hdQo8SB52KWb6uodXBDakMccQdmIiv6T', 0, 1722083116),
(172, 1, '', 'YA7jbb13bydbD9MJB3MTOJmmidOqw7WP', 0, 1722083116),
(173, 1, '', 'oI5RNVI7ngBHRDQgv9Tsy5BQGSMZF4mS', 0, 1722083116),
(174, 1, '', '1oc1sC6fHHn285UNCrQNiHDaMUUrEUp7', 0, 1722083116),
(175, 1, '', '04ZedD18eRJ3eIQI1DDwB47LRvBcbaPS', 0, 1722083116),
(176, 1, '', 'mfq6CZr6HVjUBo9watJawX7YSsFY6h0L', 0, 1722083116),
(177, 1, '', 'YPvo29IAFLxmDfMaf3sTxoJzfmIVnZ2a', 0, 1722083116),
(178, 1, '', 'Z1VQxUhXQA66DwDWzVmksh9ig86bddwN', 0, 1722083116),
(179, 1, '', '4wzv0xJJqlJViCGL9VVmoZwAVJvVyIyI', 0, 1722083116),
(180, 1, '', 'Rv6sgyowdela53eIhrGBgfv6f4S17yTy', 0, 1722083116),
(181, 1, '', 'dV2fIQvYh9mEz6KUJEDbLCWga97Dgmab', 0, 1722083116),
(182, 1, '', 'RiL8IBKAq2qHOMqUzDQbidlRbyhTcz4N', 0, 1722083116),
(183, 1, '', '7l2Pm5tgnW5HPmaJ6fo0Oo7bMf58M8Rt', 0, 1722083116),
(184, 1, '', 'kK9L6o2B1hnJVtWpK2YzH1ptNtDVFfRX', 0, 1722083116),
(185, 1, '', 'GLLEVX2s2H15jETDi8Z3MG1vgdBAiu9C', 0, 1722083116),
(186, 1, '', 'w5CfhjevTj4Fh38J1sDVfthqbGHgv7Vk', 0, 1722083116),
(187, 1, '', '9DdOgW97Qt3T0Ovz40Ix1xjp1rJLLvx7', 0, 1722083116),
(188, 1, '', 'nnU1fRk3HGoN5rHzxkkqYOuVhrr1WBA9', 0, 1722083116),
(189, 1, '', '0bnFFnawF4DClCz2qJOMhy9V6liNQJAw', 0, 1722083116),
(190, 1, '', 'toq5KIhVi66RpcZ3XQpvik1Kj527j5SG', 0, 1722083116),
(191, 1, '', 'GfpI2D2X80g7ega8dmc6JZL0dBJy7f5d', 0, 1722083116),
(192, 1, '', 'J3x2t6hARSgDX7S4gcXaXbGU8AbWx83u', 0, 1722083116),
(193, 1, '', 'wtbmYESKgAKoelPiFSQ0HwlStKCWJELV', 0, 1722083116),
(194, 1, '', 'frJXZbL8DAMb4ocUV4AovImOdl4QAE6C', 0, 1722083116),
(195, 1, '', 'm03VtwhcUbBgyiEGmWQY63exEOn0WgxN', 0, 1722083116),
(196, 1, '', '6lyketxPkU0qXLzlQJknNnnzF5A7yFvg', 0, 1722083116),
(197, 1, '', 'pgJ65pyKoxBhvQI6rlAeQ0crU5G0AAfK', 0, 1722083116),
(198, 1, '', 'xlUD1vy6bxrWRcifuBzjWKN2vEcIjGg5', 0, 1722083116),
(199, 1, '', 'HWjaiE1cHdsjF8w0ymza5lGImghejNjN', 0, 1722083116),
(200, 1, '', 'bP7brntx3pK66dvLbdIcYR6CyYMSK5JD', 0, 1722083116),
(201, 1, '', '4HURmFKlCkhSEGiEAHN4vTBogLugRboD', 0, 1722083116),
(202, 1, '', 'HcaPijT16vd1JpjgNwClsoTNPHlKKUH6', 0, 1722083116),
(203, 1, '', 'j8WdfpZ1wNXv9TNdvdw3OSqcbHycgrpA', 0, 1722083116),
(204, 1, '', 'SbSNUnuWmViEB1eHLMVbDF4ZXzWZfkQ9', 0, 1722083116),
(205, 1, '', 't06265y02dLKJ4XA1vl78KiRJBxyiA9T', 0, 1722083116),
(206, 1, '', '0CeKEYYVTsFjPzFItbTmHgrAlPlmbU7A', 0, 1722083116),
(207, 1, '', 'EpPWf7QC8UtshX4YM9tEKZoNBf6pJToN', 0, 1722083116),
(208, 1, '', 'QtJgbiuvlFbCvGFcvP40mUU0X4KfylML', 0, 1722083116),
(209, 1, '', 'k1SiWiU6qp9aAZGl5esNxHKVi5DboTCq', 0, 1722083116),
(210, 1, '', 'TFra1gp6Zv1i6KiGtJAQco7NrUJM2w4s', 0, 1722083116),
(211, 1, '', 'duWp8ttdmWUZwAuF7GO1GBRIUvPUpvLn', 0, 1722083116),
(212, 1, '', 'ckNqY44JD3UBCHyQdywo4GGZrBkgBvhj', 0, 1722083116),
(213, 1, '', 'DzEhhdRQtLlYyLvKcrIfPsddOXByr5Xb', 0, 1722083116),
(214, 1, '', 'OfNgbzE1AfMJl69U6nJjOkAfAXMYE8zn', 0, 1722083116),
(215, 1, '', 'dQiPej8jcNOQksC8SBw8zDdrs3DDMRhp', 0, 1722083116),
(216, 1, '', 'mHLoxcruL0PUQMrbFnzmyUEBL182BIpE', 0, 1722083116),
(217, 1, '', 'MWgKu1eibebsEQnit6PrWRkED2MYbPM2', 0, 1722083116),
(218, 1, '', 'ZBJvW2I2wMrT4tXp4miPdTTZuc8WBj3n', 0, 1722083116),
(219, 1, '', '0gGTG3Rk6nvnNpz0g5H4NaoJJLoz1atu', 0, 1722083116),
(220, 1, '', '6hjmHMxHtWmFkZ3BI2ICZD8RYrwkOdnp', 0, 1722083116),
(221, 1, '', 'mVwKW6XBMsFJCaXKJpNN8pQSChfvkqH8', 0, 1722083116),
(222, 1, '', 'z1mmiDTgJgQ6NdkeubUhABxrpsPKWcQe', 0, 1722083116),
(223, 1, '', 'hvFud5CUTIfgYygfptGopp6IeGq67ZU1', 0, 1722083116),
(224, 1, '', 'p2dT6xqinZRNJrZq0xaE7kdRb402Fzea', 0, 1722083116),
(225, 1, '', 'hTWW6kRiRhVF37dYnv611cn4uHTesHKu', 0, 1722083116),
(226, 1, '', '5sooGfjYtpZxfovj4afIUSWqm2lpAgSt', 0, 1722083116),
(227, 1, '', 'FWuAWzFoQcVHaJDEtJtUORxSn2Q4EPJF', 0, 1722083116),
(228, 1, '', 'mrjqoNssnb4y5oWEeztJD0z1F4UJmZPq', 0, 1722083116),
(229, 1, '', 'hQMErs3jH43ROYi1DaaRYxcPtZ42Oohd', 0, 1722083116),
(230, 1, '', 'RqhzwQf33Tll43KXrUhOK0ueK5n4nQAM', 0, 1722083116),
(231, 1, '', 'Fhz3my7leJF7NX9kSruuQvMZrnBCrGhy', 0, 1722083116),
(232, 1, '', 's1eSpo7WGCIjA3T7l6STePSmB2MOASMX', 0, 1722083116),
(233, 1, '', 'qjz7XMfNLwL1ZRueLRElt8H7teih6CLO', 0, 1722083116),
(234, 1, '', 'LNxjP7zhunAJKTNlKdOYvWv6Ptb8SKuo', 0, 1722083116),
(235, 1, '', 'djs6KsznySPqlYnNQPGNKU1OGdLM1BrS', 0, 1722083116),
(236, 1, '', 'nKAcIHZQFSwgvj10gupSEsHvrgqW7W2T', 0, 1722083116),
(237, 1, '', 'knRQNLEfy0Okwp03pQS53zZJZweZkQ7o', 0, 1722083116),
(238, 1, '', 'LjHCcQ0eNYB8HjniuIfQdpUEyqRhQyfK', 0, 1722083116),
(239, 1, '', 'SXAmTTP4RQwR4gZfGWVsFTi8VKBvKdub', 0, 1722083116),
(240, 1, '', 'ilPLccDjuzZZRPO3jmnojgCiXFrYXMjY', 0, 1722083116),
(241, 1, '', 'YGAOkRc1BybzV9Vyuoh1qpeEdmwmEWI4', 0, 1722083116),
(242, 1, '', 'RiNQpzQAEMdn3QCtsWGqpkjkiRVuA4iE', 0, 1722083116),
(243, 1, '', 'qIiVZEOGiUXwJ1hQaswgEGtX9rAUi3UV', 0, 1722083116),
(244, 1, '', '8evOU3Uo1M2EHQBLe4X2aChj0OStfDvA', 0, 1722083116),
(245, 1, '', 'USsvxSrBXeLA4Ewo0rXdyP0phTZJyLG4', 0, 1722083116),
(246, 1, '', 'gfkcfx155k49Yafcp4UIyBTpawBg1g8x', 0, 1722083116),
(247, 1, '', 't40DjLWvw5GdzdRaczKmmt2uuLmY3t3b', 0, 1722083116),
(248, 1, '', 'nmkQ6lopE3ETEU9ST9TfP36rAWsMvWTO', 0, 1722083116),
(249, 1, '', 'OzH5zDBgUCdiPG4PGbKMl1j389UJYfAW', 0, 1722083116),
(250, 1, '', 'Xz219SQU0ZZole3o3hyfKDpxpnVrkHsr', 0, 1722083153),
(251, 1, '', 'oSRTXuOlGBNmUqoWqZRUHPh62SZuXtPF', 0, 1722083153),
(252, 1, '', '9rDWL7BcOLuEWqIuJ6wh5R09xSaShfUS', 0, 1722083153),
(253, 1, '', 'RuzlKQs1RYJrV3gxhDyso6zEFfP5GJer', 0, 1722083153),
(254, 1, '', 'k01sqaJNXlDz9IhEahqX1QbBeFZtPZmw', 0, 1722083153),
(255, 1, '', 'dPaFz9uUxRACDB7W5Ix3UROJGILEsEb5', 0, 1722083153),
(256, 1, '', 'B5KnbD2667TLH0RGOWnDDsoz0L0acWl3', 0, 1722083153),
(257, 1, '', '1tnCK4fKyJi3GjZF1HmP4TV3XXJZDzaZ', 0, 1722083153),
(258, 1, '', 'CQRSVzdizEibQWyVir5axnpyXMIcDnin', 0, 1722083153),
(259, 1, '', '8IM6YyZgYCqoDTkMW0DfwUpxKhH4yOt5', 0, 1722083153),
(260, 1, '', 'a6meqOOT5rCGCioG9KQMVx5C3A9Z2mk2', 0, 1722083153),
(261, 1, '', 'MrOUFxM0lxcUMmqfUQGArMwWeRZVelcE', 0, 1722083153),
(262, 1, '', 'vGDFCPWHTPreo8cMqKHN9B9UOcbjqbdT', 0, 1722083153),
(263, 1, '', 'zDcPvCWMnwHD6RgKJWnEGGMLWGIaNEhK', 0, 1722083153),
(264, 1, '', 'Sw83AWk9u7ID3P7UJMqIDnT7FoFUzEvL', 0, 1722083153),
(265, 1, '', 'N7ykSQn79eIvygb3BFaj1OqIj10gkq82', 0, 1722083153),
(266, 1, '', 'JI6wwrpLEBOjVxcxhF3pwe9MRoRUd4Hi', 0, 1722083153),
(267, 1, '', '1EK41MTLAEQRCtJ460TedVJ9BvO9dNpo', 0, 1722083153),
(268, 1, '', 'XOlvMq2SFbTz5wZ3bfsEUZK3fIkY2xR3', 0, 1722083153),
(269, 1, '', 'hJAgjD1EeqJxDpMpnOVkprM0AMgfTv7x', 0, 1722083153),
(270, 1, '', '7Ur5PtLcKkGcJqewIGcPZE3DOEgpHJoj', 0, 1722083153),
(271, 1, '', 'LwbyizzqyioHxusJ54EX1NJ36lpYnHpP', 0, 1722083153),
(272, 1, '', '8EafyBB2i5VumvP9E6xr2mzVNUDuH5P1', 0, 1722083153),
(273, 1, '', 'FfwDWfM1XyvT3jnoAtFDnsWiWbmgjyQt', 0, 1722083153),
(274, 1, '', 'JWMXS7tskIa57c25aGGI0wq8rzylGtYG', 0, 1722083153),
(275, 1, '', 'wqqc4brZppOuIjtSRPKg035upU3YmvjK', 0, 1722083153),
(276, 1, '', 'dNdzuGHiT8DSRyudnf7Vg9gycchiPFPv', 0, 1722083153),
(277, 1, '', 'duEaYltViFydy4vtDridi9hyvy99fT1c', 0, 1722083153),
(278, 1, '', '296Lw8Tmg7YjAmDxxZtNHmwnU9HbVZmV', 0, 1722083153),
(279, 1, '', 'VwV83qNVxFs1RDpeWLmTsw3ceyd6XZSs', 0, 1722083153),
(280, 1, '', 'ahskfIPYbpGnwqKwqAAbn6NlKVtINk7J', 0, 1722083153),
(281, 1, '', 'Vh7eLa5XOBUTO0QJbIGbEacxHEGqFTtQ', 0, 1722083153),
(282, 1, '', 'trJUOMLN3JT5uhvb2Ust9s9uM2x6rWva', 0, 1722083153),
(283, 1, '', 'u7PIMQoQgABnHBfwcBjFq3hUdLolTM8i', 0, 1722083153),
(284, 1, '', 'PRfzAJuO8DRCMAB3xWezfOTp21TpB7EW', 0, 1722083153),
(285, 1, '', 'NU23H7g92jzkntKh4U9OJLFjSMaLhF2p', 0, 1722083153),
(286, 1, '', 'gUlEstCO14AamrauEMljnaB9EVrMjLRs', 0, 1722083153),
(287, 1, '', 'q7ZGIOr3NJt2s68v2L4V6ojNK6RbbX4T', 0, 1722083153),
(288, 1, '', '0VCQXMaI3bwr7G4NX9f181xsIyDlJlVj', 0, 1722083153),
(289, 1, '', 'cDi4IV2ke492PVsIKgLDWDZn4Wd44Xkn', 0, 1722083153),
(290, 1, '', 'qIrmraYCbGWM8Vt9GhLpoh6k46fND4h5', 0, 1722083153),
(291, 1, '', 'xHu371huB2uXCMA95ENN9d0Z435yCEOz', 0, 1722083153),
(292, 1, '', 'csXkLOUsnGDzjzhHmRSXyMRTpohay5qY', 0, 1722083153),
(293, 1, '', 'Aav0g9FpNFIxgQwV7XFFqTpMTVyGNPiV', 0, 1722083153),
(294, 1, '', 'Lg5lOO1w7SgCAjzqBV3ZNTaZN0zEOERr', 0, 1722083153),
(295, 1, '', 'Df94167Iyus0iIx46HOC538Kh89uo68G', 0, 1722083153),
(296, 1, '', '2tHHyGbxPo7oXJSEKI0GZtKmp4HZsc4W', 0, 1722083153),
(297, 1, '', 'gneIO0WiItOjPouhRwkoMV9ltcvyzJzC', 0, 1722083153),
(298, 1, '', 'PW5Z1kRgrdllm30jKbFsYMLRMwO1PHnM', 0, 1722083153),
(299, 1, '', 'CaPrT1ZESqiR4Q84PfrNXtsKWhsxD7Jl', 0, 1722083153),
(300, 1, '', 'hSofcidYsd3la6v31XvXPBrxVS0QBP6Z', 0, 1722083153),
(301, 1, '', 'xOZm6MouEY4EG0dgh2DU1TU6Yb5OQAWh', 0, 1722083153),
(302, 1, '', 'DRghw5RW753twvAN7y4Qx8mSs4yqGC0q', 0, 1722083153),
(303, 1, '', 'WLDLyCS3rtl6njTgynFn4vSdvmjavkwX', 0, 1722083153),
(304, 1, '', 'kydw1kHg4tYi8rvzFCf01i2c1qzFmRCJ', 0, 1722083153),
(305, 1, '', 'EZ4hbltc8oc80pdoE4EVWIXIiK3k5snG', 0, 1722083153),
(306, 1, '', 'z2ks2OBKmIYI7pyVvMAtii4zg4ogYhrh', 0, 1722083153),
(307, 1, '', 'ILZdTbc1qubx2uc1OftIjmC5OZW72ok7', 0, 1722083153),
(308, 1, '', 'oRGYgPqx4qQhGl1Bc1qy99EU9WRgOXnn', 0, 1722083153),
(309, 1, '', 'qAWO0XgJQeTKBeC59jtrvs02NdUpBl44', 0, 1722083153),
(310, 1, '', 'ovs2aY2zsYotlorEZO888jRpimAPsBG9', 0, 1722083153),
(311, 1, '', 'KnM6yLt0NIeW6cJ73KqN3AKLT9ubG9yv', 0, 1722083153),
(312, 1, '', 'KyeBwDx9TnNdj6XKIljjPzVLdhWjJOda', 0, 1722083153),
(313, 1, '', 'QIZAQtYvz1idm8hWGxyNjFMBvEMKFHDC', 0, 1722083153),
(314, 1, '', 'GIr49EulwMbdDuEl7kPU9f8MbmbP9D22', 0, 1722083153),
(315, 1, '', '6U8BFpNEHkzLiqoqzrhsVE7340bNGUjc', 0, 1722083153),
(316, 1, '', 'NG8hBmC1dPlZbUSLrFM0mqohLymWOkiI', 0, 1722083153),
(317, 1, '', 'wp1PQI2yTQjqYwG0k5udnNVCgsyv8KKB', 0, 1722083153),
(318, 1, '', 'JbG2AEQFtqmwLakWDsqbtgKwSivhrXg1', 0, 1722083153),
(319, 1, '', 'G3IcZ4VdBt2sVtxM8xRGnoCaIemOHUqh', 0, 1722083153),
(320, 1, '', 'xCmx4eDgJeLGyPLezbbMC9JvJ6pg7WCz', 0, 1722083153),
(321, 1, '', 'WgQahD9DDPXhaRgAoKW8ulSJm93rYG85', 0, 1722083153),
(322, 1, '', '5Nnf73yUULAbTV39ErwQ3sSjXL0vyIkk', 0, 1722083153),
(323, 1, '', 'gKRlralYBTPSJKP33sXNHzBrbTF6Bhha', 0, 1722083153),
(324, 1, '', 'mGSNbyWHLRo9ld167RGbfNW0ZJkLaKTd', 0, 1722083153),
(325, 1, '', 'dR2h1hkdhimO04hZhpFHQxDS9p0htg68', 0, 1722083153),
(326, 1, '', '3EO87hRBvDq4LXSDIYoqAbr31henPAWo', 0, 1722083153),
(327, 1, '', 'OKCzbVOVxaaquBztv41emOVNncxp531k', 0, 1722083153),
(328, 1, '', 'M7ANa1hGB629bCnXO6LV32ajlUS0QrWn', 0, 1722083153),
(329, 1, '', 'lCw1a9C4aRpqRCHGLiiCR4TDU3zZO8se', 0, 1722083153),
(330, 1, '', 'R87kxZWWDDNZ5asmpVfarBnwyDe7Lgy7', 0, 1722083153),
(331, 1, '', '8weVK9YLYapJduIuIK4h4bEnf5KPVFgw', 0, 1722083153),
(332, 1, '', 'EVzGK9jcllgJ0GxMC1ZJfSFPoyDkBeTB', 0, 1722083153),
(333, 1, '', 'jSMHv1sP23gc4DrREYTBzxHB6idjLWBt', 0, 1722083153),
(334, 1, '', 'VWb5m2sj3rZHTqFGgNKxPQxFwrugHEY9', 0, 1722083153),
(335, 1, '', 'dNaMTyDoshVYZ6MkeUviq3s9XCH0K9sR', 0, 1722083153),
(336, 1, '', 'bHwGLjfoCgAzXWcnZMXtICqvkwFMlC4A', 0, 1722083153),
(337, 1, '', 'iE6eHXTabDe5U1mMOyN9Cpx4VDOVuXX0', 0, 1722083153),
(338, 1, '', 'kIYYT9Fd0ROHI4V3Qyotc4ZQyOGXvrDD', 0, 1722083153),
(339, 1, '', 'ZEl47sFPtFpGCbyCY1wBUcYsJnQJKTBI', 0, 1722083153),
(340, 1, '', 'xI3T6Qqp4GWWu70B1dkDkXoHnzmnw4cP', 0, 1722083153),
(341, 1, '', 'aclHHaiJehHsmAaOmdymsBduOWNTTAQQ', 0, 1722083153),
(342, 1, '', 'eF4JnbwinNDXLaKMJrvnCJOx3pvDJ1s8', 0, 1722083153),
(343, 1, '', '3vjNQHFOUARBUtlOryXerkJCAPfYpZeN', 0, 1722083153),
(344, 1, '', '7cPIKhg3Ln08uRBUNQCMmKVNvnrE7XWB', 0, 1722083153),
(345, 1, '', 'z8XlefF5DegNMZ1yMdKTOZxJkJk8pUg2', 0, 1722083153),
(346, 1, '', 'W6rHZtKr5qY4ZnghWndaGpD2mR5WaAOU', 0, 1722083153),
(347, 1, '', 'H4gyoFwfc5vgPRXwZeBwFWDawvgGiEUZ', 0, 1722083153),
(348, 1, '', 'mjwbvL8JAEfZOrBGCiQP1lZrHrCmWI4e', 0, 1722083153),
(349, 1, '', 'dTcKO2I9lALQD26Qkvgp0lGWTAHw0gb8', 0, 1722083153),
(350, 1, '', 'GuXaIjOPdWy3FjH4329CFTUUviafz0p6', 0, 1722083189),
(351, 1, '', 'kGZLQZvqvt2xNUdBgw4xoyPzqmUQJopO', 0, 1722083189),
(352, 1, '', 'eQfRL7lFKaCybiSCcjxT7YLomWNwvZTN', 0, 1722083189),
(353, 1, '', 'GSNLeW3rOhEGX5VuzJn7qliIxSRpYOwy', 0, 1722083189),
(354, 1, '', 'kmTeTj4uzXPjJYh3pM3Qm1WfR0BWSDfA', 0, 1722083189),
(355, 1, '', 'DAtuSK5jlGT7mjgRo3oy62Sayc5u1aBj', 0, 1722083189),
(356, 1, '', 'Cl7pzX2rTBrjlYXU5cS2k0UD29HX2D0f', 0, 1722083189),
(357, 1, '', 'SNIgUyPZncnNafzlvhxOEkaLGe1suQ3D', 0, 1722083189),
(358, 1, '', 'gqosI7RfAaKlh9BGDGtzjgLpQAoYpqje', 0, 1722083189),
(359, 1, '', '5L7MTbiSa4A0BsoHzzqH0gFo1G0UfL6t', 0, 1722083189),
(360, 1, '', 'ZjRCXqbKYPi96vP04p3ZzzRgO1TMXRNh', 0, 1722083227),
(361, 1, '', 'LNCmhkyNh2gzVOtlIuVAlXxc5ruPJkb4', 0, 1722083227),
(362, 1, '', 'R4b36dGfqbF1pqh6XzWBlM4fRHhL1QMb', 0, 1722083227),
(363, 1, '', 'UWZFXAM5jJi7ejVVBDFO6ot5GNUStovg', 0, 1722083227),
(364, 1, '', 'nkFrLAgaB06l1lvk2JmeBQD2ZBLr1zfp', 0, 1722083227),
(365, 1, '', 'r5bak1ksNxw0rLwLL1Tkhc0eS6fr91Kn', 0, 1722083227),
(366, 1, '', 'HdY4RiylpqW5USfFovLNSSFcWIJN17SL', 0, 1722083227),
(367, 1, '', 'CEWN9T1fVajH5lktoLRNc74i6polpLSi', 0, 1722083227),
(368, 1, '', 'Oh830rRJeSs5WEtEEXRedg8CFnIp7hX6', 0, 1722083227),
(369, 1, '', 'bFBeBfBvL84VUMg8ieAqVNjUn91ZvIf9', 0, 1722083227);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_money_log`
--

CREATE TABLE `cf_agency_money_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `money` float(32,2) NOT NULL,
  `user` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_money_log`
--

INSERT INTO `cf_agency_money_log` (`id`, `uid`, `money`, `user`, `title`, `create_time`) VALUES
(1, 1, 1.00, '发送给了zx101018', '购买物品：测试物品11', 1722082978),
(2, 1, 0.10, '生成了1张CDK', '生成物品CDK', 1722083060),
(3, 1, 23.40, '生成了张邀请码', '生成注册邀请码', 1722083116),
(4, 1, 10.00, '生成了100张邀请码', '生成注册邀请码', 1722083153),
(5, 1, 1.00, '生成了10张邀请码', '生成注册邀请码', 1722083189),
(6, 1, 0.00, '生成了10张邀请码', '生成注册邀请码', 1722083227),
(7, 1, 1.00, '生成了10张改名卡', '生成改名卡', 1722086130),
(8, 1, 0.10, '生成了1张改名卡', '生成改名卡', 1722086162),
(9, 1, 12414134.00, '给予账号123赠送了12414134点', '赠送CF点', 1722170628),
(10, 1, 99999.00, '给予账号123赠送了99999点', '赠送CF点', 1722170660);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_nick_cdk`
--

CREATE TABLE `cf_agency_nick_cdk` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_nick_cdk`
--

INSERT INTO `cf_agency_nick_cdk` (`id`, `uid`, `username`, `code`, `status`, `create_time`) VALUES
(1, 1, '123', 'T1HIeGPRiLGJkO0LHIuuR5C0jtZd0d1a', 1, 1722086130),
(2, 1, '', 'KizEIW5WIpdaG5MjvvYKkegYKxlA7IQ3', 0, 1722086130),
(3, 1, '', 'vwfodzFJFEN8CgftkjfUuzZ5NyT8GN9o', 0, 1722086130),
(4, 1, '', '928wKW03fnGMieOAvg1WSnDV0ZUr5DnP', 0, 1722086130),
(5, 1, '', 'qeL8NiCuAX3JxxS2ARPZxYkFa5W2GdcD', 0, 1722086130),
(6, 1, '', 'WG8ZWrrfGXRpAiaMMDWqhwBACcl3CE8V', 0, 1722086130),
(7, 1, '', 'gJLniIPrtGHTsvca1ipVVZnJifLV0NJw', 0, 1722086130),
(8, 1, '', '4yq6qryTrw5upVY2RTNNLJqHVpK8drrF', 0, 1722086130),
(9, 1, '', 'vhY6gWWWHKVX2fpCd2DvotsMjO0arJhF', 0, 1722086130),
(10, 1, '', 'tpFeuXfv9tAXmKLuYf271FdiUov4ovyn', 0, 1722086130),
(11, 1, '123', '4RnTdCqpn6ANHIIUYdXZtZGs201VYfbi', 1, 1722086162);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_order`
--

CREATE TABLE `cf_agency_order` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `out_trade_no` varchar(50) NOT NULL COMMENT '商户订单号',
  `trade_no` varchar(50) NOT NULL COMMENT '彩虹易支付交易号',
  `trade_status` varchar(50) NOT NULL COMMENT '交易状态',
  `type` varchar(10) NOT NULL COMMENT '支付方式',
  `money` float(32,2) NOT NULL COMMENT '支付金额',
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_order`
--

INSERT INTO `cf_agency_order` (`id`, `uid`, `out_trade_no`, `trade_no`, `trade_status`, `type`, `money`, `create_time`, `update_time`) VALUES
(1, 1, '20240723203136773', '2024072320313652858', 'TRADE_SUCCESS', 'wxpay', 0.01, 1721737896, 0),
(2, 1, '20240723203231679', '2024072320323158012', 'TRADE_SUCCESS', 'wxpay', 0.01, 1721737951, 0),
(3, 1, '20240723203345540', '', '0', 'wxpay', 1.00, 1721738025, 0),
(4, 1, '20240723204452120', '', '0', 'alipay', 1.00, 1721738692, 0),
(5, 1, '20240723221119665', '', '0', 'qqpay', 1.00, 1721743879, 0),
(6, 1, '20240725225222294', '', '0', 'alipay', 1.00, 1721919142, 0),
(7, 1, '20240727193601172', '', '0', 'alipay', 1.00, 1722080161, 0);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_shop`
--

CREATE TABLE `cf_agency_shop` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `itemid` bigint(11) NOT NULL,
  `img` longtext NOT NULL,
  `status` int(11) NOT NULL,
  `money` float(32,2) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_shop`
--

INSERT INTO `cf_agency_shop` (`id`, `title`, `itemid`, `img`, `status`, `money`, `create_time`) VALUES
(1, '测试物品', 1000000201, 'http://vx.cc/static/images/ITEMICON/ItemIcon_007.png', 1, 100.00, 1696256597),
(2, '测试物品11', 1000000201, 'http://vx.cc/static/images/ITEMICON/ItemIcon_007.png', 1, 1.00, 1696256597),
(3, '测试物3333品', 1000000201, 'http://vx.cc/static/images/ITEMICON/ItemIcon_007.png', 1, 0.10, 1696256597);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_shop_cdk`
--

CREATE TABLE `cf_agency_shop_cdk` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `itemid` longtext NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_shop_cdk`
--

INSERT INTO `cf_agency_shop_cdk` (`id`, `uid`, `username`, `code`, `itemid`, `status`, `create_time`) VALUES
(1, 1, '', 'hpVq3wKI5Vus58GbQutnGeCBOPNJWMNk', '1,2,3', 0, 1722081893),
(2, 1, '', 'ifN6G9WJh51UEIpywdRFcGmQN6l06DJf', '1,2,3', 0, 1722081893),
(3, 1, '', 'FYhmZJpCS22BgUIxOMzxmxtfw9loDuWf', '1,2,3', 0, 1722081893),
(4, 1, '', 'DQyv8ftdN6NqIHBcvaaHFGKFeQlbMhCq', '1,2,3', 0, 1722081893),
(5, 1, '', 'hf7NHhMOxE8lrXZ4hiKmZsaOD3wl5Ox6', '1,2,3', 0, 1722081893),
(6, 1, '', 'vxvoq8MN9uNWIRLw7UOVThwcwGTiLdJv', '1,2,3', 0, 1722081893),
(7, 1, '', 'BomKjhGEmxVzUFAucISYCRbhwhR7F1ea', '1,2,3', 0, 1722081893),
(8, 1, '', 'QL9TdVsHgf4kBH31rR6quPxSxTmStHHJ', '1,2,3', 0, 1722081893),
(9, 1, '', 'QSXTgHGbWRLuP6BZ2ArI9ZcZav0Dhjol', '1,2,3', 0, 1722081893),
(10, 1, '', 'fKDhqPCCasihFiPvY6Z0yGmct1OQHraK', '1,2,3', 0, 1722081893),
(11, 1, '', 'ilL5bmNi9MThjsMfbCc3PZ9ls3AxDu9J', '1,2,3', 0, 1722081893),
(12, 1, '', 'xgBhbh9iedF6oaJ3NBbv3iOyjo5Vn9wS', '1,2,3', 0, 1722081893),
(13, 1, '', 'osGd8YAxsCo9WzCwk7rv8GnGONNN8Hap', '1,2,3', 0, 1722081893),
(14, 1, '', 'pnD9KGpS6lHoQrhkSywJVXhcWdUIJfmz', '1,2,3', 0, 1722081893),
(15, 1, '', 'ynizu6iGuWH37Be1FV0cHvitjGYA2306', '1,2,3', 0, 1722081893),
(16, 1, '', 'CPQCZa4kqe9udq599xK4BaBIrsH5LLfR', '1,2,3', 0, 1722081893),
(17, 1, '', '3svQCp4wVGEbUcewAHYpYJ2mXEUPutKV', '1,2,3', 0, 1722081893),
(18, 1, '', '81De8pFaRmn3n0pP4tyF0aNv2S80fYLt', '1,2,3', 0, 1722081893),
(19, 1, '', 'wVYhBOzywl0aqr4XnsPKZlXqcRMwlMoL', '1,2,3', 0, 1722081893),
(20, 1, '', 'q6VYARirfa3rv0Q6j5RyedGw8YFhNxFo', '1,2,3', 0, 1722081893),
(21, 1, '', 'vi5YYy7J21R991PRRrGkBx39E4jshm0A', '2', 0, 1722082667),
(22, 1, '', 'e8SL7O2b6ieJBMfTUqz4wQaPp7AvD0v6', '2', 0, 1722082667),
(23, 1, '', 'Pbso4eNQ0X33SHEH6mSPeZD3eY9tsh70', '2', 0, 1722082667),
(24, 1, '', 'jFSDkprnIlJRX7SBV54WY4nKlIQ8GX71', '2', 0, 1722082667),
(25, 1, '', '90UcvTzymDnQYcWVkJXZXg3CwwOEvwcT', '2', 0, 1722082667),
(26, 1, '', 'Vh4vjvC0dJyq3rZVe9GlCpI58lZZJeNj', '2', 0, 1722082667),
(27, 1, '', 'EdcfeGyCAiyJtrtgByBqrExOg5S6vCJ7', '2', 0, 1722082667),
(28, 1, '', 'dnQPLPcRWYSUsi66Dt03CAbEtG1OTmnT', '2', 0, 1722082667),
(29, 1, '', 'ARSvrQTD3gzDxCCyKQXIlwVZmRefOwKX', '2', 0, 1722082667),
(30, 1, '', 'QSSQcpv75r8mDMcOMoD4Gtpn6oFkJuGW', '2', 0, 1722082667),
(31, 1, '', 'f4MNjz2eH2NHjTJSn2S04ZC2KE0Oak8B', '3', 0, 1722082826),
(32, 1, '', 'GpxTIps90rTpiqD2xcHzmOBOGLSvXlou', '3', 0, 1722083060);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_user`
--

CREATE TABLE `cf_agency_user` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `cf` longtext NOT NULL,
  `money` float(32,2) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_user`
--

INSERT INTO `cf_agency_user` (`id`, `user`, `pass`, `status`, `cf`, `money`, `create_time`) VALUES
(1, '131313', '$2y$10$.zBvX6bmjclAvG0rdNHOoOQPlMPyI7BPfFZWNHjINpb0L6pazIVB.', 1, '352224325', 62.40, 1696256597),
(5, '234234', '$2y$10$.zBvX6bmjclAvG0rdNHOoOQPlMPyI7BPfFZWNHjINpb0L6pazIVB.', 1, '0', 11.00, 1696256597);

-- --------------------------------------------------------

--
-- 表的结构 `cf_agency_user_log`
--

CREATE TABLE `cf_agency_user_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_agency_user_log`
--

INSERT INTO `cf_agency_user_log` (`id`, `uid`, `user`, `email`, `create_time`) VALUES
(1, 1, '123', '123@qq.com', 1721909958),
(2, 1, 'zx101018', '1123@qq.com', 1721909958),
(3, 5, 'asdasd', '14324@qq.cc', 1721909958);

-- --------------------------------------------------------

--
-- 表的结构 `cf_cdks`
--

CREATE TABLE `cf_cdks` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `item` longtext NOT NULL,
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_cdks`
--

INSERT INTO `cf_cdks` (`id`, `name`, `code`, `item`, `status`, `type`, `create_time`) VALUES
(11, '0', 'd2lstaX053UHTxssOhfybJyTcKwWcFmD', '9000018603', 0, 2, 1715754509),
(8, '0', 'zffy8wyan2hTrCLK6asbCHLp0sBugt7t', '9000018603,9000018701,9000018801,9000018802', 0, 1, 1715752094),
(7, '0', 'mfKxwdFNZWi38r9LzDNeCjantjjOeVJI', '5646,6546,546,46,5465,46546,5465456,465,46546546,546546,4646,54646,4646546,464646,464,646,48,468,74,64,8,648946,489,746,48976,48746,4897', 0, 1, 1715750542),
(6, '0', 'NXIHAVOXlfhzJz1YRiIPikdABZllFJoI', '123', 0, 1, 1715749006),
(9, '0', 'wzTFMjTWNk3i98K7IhqftgNFrSx0xR8F', '2010032101,2010032001,9000020101', 0, 1, 1715753067),
(10, 'zx101018', 'OHuTZ7peq3VNyD2Xr4SYbnyHOmnxDp5M', '2010030701,2010031901,2010021701,2010032001,2010032101,2010032201,2010032202,2010032301,2010031301,2010030801,2010032601', 1, 1, 1715753783),
(12, '0', 'yoCs1ZuiqMhrOzEI4YHP28NPoZI0myaK', '123', 0, 1, 1715865354),
(13, '0', '12JZw2XxcAJqZa4RCrCie0mMwwQEaDLb', '123', 0, 1, 1715865364),
(14, '0', 'VatAea5eGr1Ndl5buZfuKuma0l7qSAkW', '123', 0, 1, 1715865394),
(15, 'zx101018', 'nYvDXykd7i19czPJJoTvkrZblVjFas8o', '123', 1, 1, 1715865408),
(16, 'zx101018', '7VLXqyK7Bgqj614ynXmrYhdHrHd0CWBa', '9000018603', 1, 1, 1716648190),
(17, '0', 'Mk6VjuIhzZK6wSQJMqwIGjBoUaBd8xkH', '9000018603', 0, 2, 1716648397),
(19, 'zx131313', 'PSlFNJM2azL82mqtepxvu9Vdz96N9AMB', '1000000101', 1, 1, 1721563451);

-- --------------------------------------------------------

--
-- 表的结构 `cf_configs`
--

CREATE TABLE `cf_configs` (
  `id` varchar(11) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_configs`
--

INSERT INTO `cf_configs` (`id`, `value`) VALUES
('title', '网站标题'),
('keywords', '关键词'),
('description', '网站描述'),
('info', '副标题'),
('dtitle1', '下载标题1'),
('dtitle2', '下载标题2'),
('durl1', '下载地址1'),
('durl2', '下载地址2'),
('ver', '版本'),
('petitle', '特性标题'),
('pecontent', '特性内容'),
('indeximg', 'https://api.picurl.cn/redir/6ec593a878351b367f893b209f9fd377.jpg'),
('in', '0'),
('reg_switch', '1'),
('token', 'fr3242asdfew34'),
('url', 'http://1.95.61.21:648/'),
('regapi', '1'),
('cdkapi', '0'),
('loginapi', '0'),
('jieapi', '1'),
('itemapi', '1'),
('server', '0'),
('InSwitch', '1'),
('aValue', '1'),
('cknamemoney', '1'),
('agency_inv', '0.1'),
('agency_nick', '0.1');

-- --------------------------------------------------------

--
-- 表的结构 `cf_events`
--

CREATE TABLE `cf_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `img` text NOT NULL,
  `content` text NOT NULL,
  `value` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `without` int(11) NOT NULL,
  `start_time` varchar(50) NOT NULL,
  `end_time` varchar(50) NOT NULL,
  `create_time` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_events`
--

INSERT INTO `cf_events` (`id`, `title`, `img`, `content`, `value`, `url`, `type`, `without`, `start_time`, `end_time`, `create_time`) VALUES
(3, '免费领取10000CF点', 'https://api.picurl.cn/redir/2f0fd2dbb33b4e5eae4e2853d6ea0743.jpg', '<p>测试一个免费领取10000CF点的活动</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>换行</p>\n<p>凑数补丁.................</p>', '10000', '1', 1, 1, '1713369600', '1713542400', '1713406640'),
(4, '这是一个领取10000GP点的活动', 'https://api.picurl.cn/redir/48f454a8a9a7972b094e8405fc524161.jpg', '<p>免费领取10000GP点，一号一次，切勿作弊</p>', '10000', '1', 2, 1, '1713456000', '1713404432', '1713489079'),
(5, '这是一个领取物品的活动', 'https://api.picurl.cn/redir/81058cedeb86a670f180115c09b9726b.jpg', '<p>这是一个领取武器的活动</p>\n<p>武器ID：1000000101</p>\n<p>武器名称：SWAT</p>\n<p>武器图片 <img src=\"https://api.picurl.cn/redir/81058cedeb86a670f180115c09b9726b.jpg\" /></p>', '1000000101', '1', 3, 1, '1713369600', '1713404432', '1713489367'),
(6, '测试一个快开始了的活动', 'https://api.picurl.cn/redir/36bf216050346be5bf03bb2aa2fc804c.jpg', '<p>测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动测试一个快开始了的活动</p>', '1', '1', 1, 1, '1713542400', '1713974400', '1713493750'),
(7, '测试一个外部活动链接', 'https://api.picurl.cn/redir/3afb84cb4f44a3f7d5ee042599996a5d.jpg', '<p>这是一个外部活动链接</p>', '11', 'https://houz.cn/post/761', 1, 2, '1713456000', '1714406400', '1713494972'),
(8, '123', '12', '<p>212</p>', '12', '12', 1, 1, '1713542400', '1713542400', '1713573835'),
(9, '12', '12', '<p>212</p>', '12', '12', 1, 1, '1713974400', '1713542400', '1713572443'),
(10, '12', '12', '<p>212</p>', '12', '12', 1, 1, '1713542400', '1713542400', '1713572465');

-- --------------------------------------------------------

--
-- 表的结构 `cf_events_log`
--

CREATE TABLE `cf_events_log` (
  `id` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `usn` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `cf_invitation`
--

CREATE TABLE `cf_invitation` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_invitation`
--

INSERT INTO `cf_invitation` (`id`, `username`, `code`, `status`, `create_time`) VALUES
(1, '', 'kmGduDgg8dj3WGR4Fl6025Zg3QJIqlcV', 0, 1715747730),
(2, 'test1113', 'rcVXn9UESdXx20OjtJV6lkE96SvrKJlu', 1, 1715865317),
(3, 'zx101018222', 'rNE9YVN6ZJdMpgSPrvk8M1nwm1zQagQO', 1, 1716206963),
(4, '', 'yI5GvNaH7AAXh9l9RW3I8FvhZiSU5Kae', 0, 1721976411);

-- --------------------------------------------------------

--
-- 表的结构 `cf_invite_log`
--

CREATE TABLE `cf_invite_log` (
  `id` int(11) NOT NULL,
  `auserid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buserid` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cf` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `cf_invite_log`
--

INSERT INTO `cf_invite_log` (`id`, `auserid`, `buserid`, `cf`, `status`, `create_time`) VALUES
(1, 'zx101018', 'test111asd', 1000, 1, 1720355248),
(2, 'zx101018', 'test1121asd', 1000, 1, 1720355248),
(3, 'test111', 'test1121awwsd', 1000, 1, 1720355248),
(4, 'zx101018', 'test2111', 1, 1, 1720612789),
(5, 'zx101018', 'test2111', 1, 1, 1720612794),
(6, 'zx101018', 'test2111', 1, 1, 1720612801),
(7, 'zx101018', 'test2111', 1, 1, 1720612810),
(8, 'zx101018', 'test2111', 1, 1, 1720612868),
(9, 'zx101018', 'test2111', 1, 1, 1720612901),
(10, 'zx101018', 'test2111', 1, 1, 1720612923),
(11, 'zx101018', 'test22111', 1, 1, 1720612929);

-- --------------------------------------------------------

--
-- 表的结构 `cf_news`
--

CREATE TABLE `cf_news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_news`
--

INSERT INTO `cf_news` (`id`, `title`, `content`, `create_time`) VALUES
(5, '测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>', 1713398385),
(6, '测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>', 1713398386),
(7, '测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>', 1713398386),
(8, '测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>', 1713398387),
(9, '测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>\n<p>测试一篇文章111111111111</p>\n<p>&nbsp;</p>', 1713398387),
(10, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398402),
(11, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398402),
(12, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398402),
(13, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398402),
(14, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398402),
(15, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398403),
(16, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398403),
(17, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398403),
(18, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398403),
(19, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398403),
(20, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398403),
(21, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398404),
(22, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398404),
(23, '测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111', '<p>测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111测试一篇文章111111111111</p>', 1713398404);

-- --------------------------------------------------------

--
-- 表的结构 `cf_report`
--

CREATE TABLE `cf_report` (
  `id` int(11) NOT NULL,
  `usn` int(11) NOT NULL,
  `rusn` int(11) NOT NULL,
  `reportedNickname` varchar(200) NOT NULL COMMENT '举报昵称',
  `appealAccount` varchar(200) NOT NULL COMMENT '申诉账号',
  `reportType` int(11) NOT NULL,
  `content` longtext NOT NULL COMMENT '提交内容',
  `status` int(11) NOT NULL COMMENT '0：未审核，1：已审核',
  `aid` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_report`
--

INSERT INTO `cf_report` (`id`, `usn`, `rusn`, `reportedNickname`, `appealAccount`, `reportType`, `content`, `status`, `aid`, `update_time`, `create_time`) VALUES
(1, 0, 0, '12', 'zx101018', 1, '<p>1212</p>', 1, 1, 0, 1713920653),
(2, 0, 0, '1212', 'zx101018', 1, '<p>12121212</p>', 0, 0, 1714008994, 1714008994),
(3, 0, 0, '', 'zx101018', 2, '<p>测试一下内容</p>\n<p>asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方</p>\n<p>第三方链接<a href=\"https://houz.cn/\">https://houz.cn/</a></p>\n<p>asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方</p>\n<p>asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方</p>\n<p><span style=\"color: #f1c40f;\">asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方</span></p>\n<p>&nbsp;</p>\n<p>asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方</p>\n<p>asd asd阿斯顿阿斯顿撒旦阿斯顿阿斯蒂芬撒地方撒地方第三方</p>\n<p><img src=\"https://api.picurl.cn/redir/56fa365b71897883695913efaace43e0.jpg\" alt=\"\" /></p>\n<p><img src=\"https://houz.cn/content/uploadfile/202203/ad7b1648645601.jpg\" alt=\"\" width=\"160\" height=\"160\" /></p>', 1, 0, 1714009409, 1714009409),
(4, 4, 0, '123', 'zz131313', 1, '<p>3124123</p>', 0, 0, 1716100851, 1716100851),
(5, 4, 0, '12412', 'zz131313', 1, '<p>123213</p>', 0, 0, 1716101077, 1716101077),
(6, 4, 0, '213', 'zz131313', 1, '<p>213213</p>', 0, 0, 1716101100, 1716101100),
(7, 4, 0, '', 'zz131313', 2, '<p>123213</p>', 0, 0, 1716101157, 1716101157),
(8, 4, 0, '555', 'zz131313', 1, '<p>123123</p>', 0, 0, 1716101208, 1716101208),
(9, 4, 0, '', 'zz131313', 2, '<p>3213213</p>', 0, 0, 1716101217, 1716101217),
(10, 4, 1, '555', 'zz131313', 1, '<p>5</p>', 1, 0, 1716101503, 1716101503),
(11, 4, 0, '', 'zz131313', 2, '<p>123</p>', 1, 0, 1716101544, 1716101544),
(12, 4, 0, '', 'zz131313', 2, '<p>234234</p>', 0, 0, 1716101695, 1716101695);

-- --------------------------------------------------------

--
-- 表的结构 `cf_senditem_auth`
--

CREATE TABLE `cf_senditem_auth` (
  `id` int(11) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `usn` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_senditem_auth`
--

INSERT INTO `cf_senditem_auth` (`id`, `userid`, `usn`, `status`, `create_time`) VALUES
(4, 'zx101018', 2, 1, 1721372349),
(5, '123', 1, 1, 1721564196);

-- --------------------------------------------------------

--
-- 表的结构 `cf_shop`
--

CREATE TABLE `cf_shop` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `itemid` bigint(20) NOT NULL,
  `money` float(32,2) NOT NULL,
  `type` int(11) NOT NULL,
  `img` text NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_shop`
--

INSERT INTO `cf_shop` (`id`, `title`, `itemid`, `money`, `type`, `img`, `status`, `create_time`) VALUES
(9, 'GP', 1010017601, 1.00, 2, 'https://block.codescandy.com/assets/images/blog/blog-img-1.jpg', 1, 1713282771),
(8, 'CFAM后台系统 支持所有CF2.0服务端 V1.9', 121212, 666.00, 2, 'http://www.cloudebc.com/assets/images/head.jpg', 2, 1713280875),
(10, 'cf点', 12121, 121212.00, 1, 'https://block.codescandy.com/assets/images/blog/blog-img-1.jpg', 1, 1713282788),
(11, '1212', 121212, 12.00, 1, '1212', 1, 1713402054),
(12, '33', 33, 33.00, 1, '333', 1, 1713402063),
(13, '123', 123, 23.00, 1, '213', 1, 1713402071),
(14, '123123', 121212, 11.00, 1, 'https://api.picurl.cn/redir/fd635c35a13b6015e15fc98d6784d16e', 1, 1713402076),
(15, 'CFAM后台系统 支持所有CF2.0服务端 V1.9', 213213, 11.00, 1, 'https://api.picurl.cn/redir/fd635c35a13b6015e15fc98d6784d16e', 1, 1713402082),
(16, 'CFAM后台系统 支持所有CF2.0服务端 V1.9', 121212, 11.00, 1, 'https://api.picurl.cn/redir/fd635c35a13b6015e15fc98d6784d16e', 1, 1713402086),
(17, 'CFAM后台系统 支持所有CF2.0服务端 V1.9', 121212, 11.00, 1, 'https://api.picurl.cn/redir/2c3e32656b0d911106e880e3327cb3c2', 1, 1713402092),
(18, '1212', 121212, 11.00, 1, 'https://api.picurl.cn/redir/fd635c35a13b6015e15fc98d6784d16e', 1, 1713402109),
(19, '1', 121212, 1.00, 1, '1212https://api.picurl.cn/redir/fd635c35a13b6015e15fc98d6784d16e', 1, 1713403154),
(20, '1212', 1, 1.00, 1, 'https://api.picurl.cn/redir/2c3e32656b0d911106e880e3327cb3c2', 1, 1713403160),
(21, '1', 1, 1.00, 1, 'https://api.picurl.cn/redir/fd635c35a13b6015e15fc98d6784d16e', 1, 1713403171),
(22, '1', 1, 1.00, 1, 'https://api.picurl.cn/redir/36bf216050346be5bf03bb2aa2fc804c.jpg', 1, 1713403177),
(23, '1', 1, 1.00, 1, 'https://api.picurl.cn/redir/36bf216050346be5bf03bb2aa2fc804c.jpg', 1, 1713403182),
(24, '123', 123, 1.00, 1, '123213', 1, 1714650406);

-- --------------------------------------------------------

--
-- 表的结构 `cf_shop_log`
--

CREATE TABLE `cf_shop_log` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `usn` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_shop_log`
--

INSERT INTO `cf_shop_log` (`id`, `sid`, `usn`, `username`, `create_time`) VALUES
(3, 24, 1, '123', 1721563707);

-- --------------------------------------------------------

--
-- 表的结构 `cf_user_log`
--

CREATE TABLE `cf_user_log` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `userid` varchar(50) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `Invite` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `cf_user_log`
--

INSERT INTO `cf_user_log` (`id`, `email`, `userid`, `ip`, `type`, `Invite`, `create_time`) VALUES
(13, '1123@qq.com', 'zx101018', '127.0.0.1', 1, 0, 1721562205),
(14, '1232@qq.com', 'zx131313', '127.0.0.1', 0, 0, 1721563037),
(15, '14324@qq.cc', 'asdasd', '127.0.0.1', 1, 0, 1721909890),
(16, '12223@qq.com', 'asd3243124', '127.0.0.1', 1, 0, 1721909958);

--
-- 转储表的索引
--

--
-- 表的索引 `cf_admins`
--
ALTER TABLE `cf_admins`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_admin_log`
--
ALTER TABLE `cf_admin_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_code`
--
ALTER TABLE `cf_agency_code`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_money_log`
--
ALTER TABLE `cf_agency_money_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_nick_cdk`
--
ALTER TABLE `cf_agency_nick_cdk`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_order`
--
ALTER TABLE `cf_agency_order`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_shop`
--
ALTER TABLE `cf_agency_shop`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_shop_cdk`
--
ALTER TABLE `cf_agency_shop_cdk`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_user`
--
ALTER TABLE `cf_agency_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_agency_user_log`
--
ALTER TABLE `cf_agency_user_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_cdks`
--
ALTER TABLE `cf_cdks`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_configs`
--
ALTER TABLE `cf_configs`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_events`
--
ALTER TABLE `cf_events`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_events_log`
--
ALTER TABLE `cf_events_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_invitation`
--
ALTER TABLE `cf_invitation`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_invite_log`
--
ALTER TABLE `cf_invite_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_news`
--
ALTER TABLE `cf_news`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_report`
--
ALTER TABLE `cf_report`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_senditem_auth`
--
ALTER TABLE `cf_senditem_auth`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_shop`
--
ALTER TABLE `cf_shop`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_shop_log`
--
ALTER TABLE `cf_shop_log`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `cf_user_log`
--
ALTER TABLE `cf_user_log`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `cf_admins`
--
ALTER TABLE `cf_admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- 使用表AUTO_INCREMENT `cf_admin_log`
--
ALTER TABLE `cf_admin_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- 使用表AUTO_INCREMENT `cf_agency_code`
--
ALTER TABLE `cf_agency_code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=370;

--
-- 使用表AUTO_INCREMENT `cf_agency_money_log`
--
ALTER TABLE `cf_agency_money_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `cf_agency_nick_cdk`
--
ALTER TABLE `cf_agency_nick_cdk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `cf_agency_order`
--
ALTER TABLE `cf_agency_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `cf_agency_shop`
--
ALTER TABLE `cf_agency_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `cf_agency_shop_cdk`
--
ALTER TABLE `cf_agency_shop_cdk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- 使用表AUTO_INCREMENT `cf_agency_user`
--
ALTER TABLE `cf_agency_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `cf_agency_user_log`
--
ALTER TABLE `cf_agency_user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `cf_cdks`
--
ALTER TABLE `cf_cdks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用表AUTO_INCREMENT `cf_events`
--
ALTER TABLE `cf_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用表AUTO_INCREMENT `cf_events_log`
--
ALTER TABLE `cf_events_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `cf_invitation`
--
ALTER TABLE `cf_invitation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `cf_invite_log`
--
ALTER TABLE `cf_invite_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- 使用表AUTO_INCREMENT `cf_news`
--
ALTER TABLE `cf_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- 使用表AUTO_INCREMENT `cf_report`
--
ALTER TABLE `cf_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `cf_senditem_auth`
--
ALTER TABLE `cf_senditem_auth`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `cf_shop`
--
ALTER TABLE `cf_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- 使用表AUTO_INCREMENT `cf_shop_log`
--
ALTER TABLE `cf_shop_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `cf_user_log`
--
ALTER TABLE `cf_user_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
