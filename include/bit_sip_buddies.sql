CREATE TABLE `sip_buddies` (
`id` int(11) NOT NULL auto_increment,
`name` varchar(80) NOT NULL default '',
`host` varchar(31) NOT NULL default 'dynamic',
`nat` varchar(5) NOT NULL default 'yes',
`type` enum('user','peer','friend') NOT NULL default 'friend',
`accountcode` varchar(20) default NULL,
`amaflags` varchar(13) default NULL,
`call-limit` smallint(5) unsigned default NULL,
`callgroup` varchar(10) default NULL,
`callerid` varchar(80) default NULL,
`cancallforward` char(3) default 'no',
`canreinvite` char(3) default 'no',
`context` varchar(15) default 'user',
`defaultip` varchar(15) default NULL,
`dtmfmode` varchar(7) default NULL,
`fromuser` varchar(80) default NULL,
`fromdomain` varchar(80) default NULL,
`insecure` varchar(20) default 'port,invite',
`language` char(2) default NULL,
`mailbox` varchar(50) default NULL,
`md5secret` varchar(80) default NULL,
`deny` varchar(95) default NULL,
`permit` varchar(95) default NULL,
`mask` varchar(95) default NULL,
`musiconhold` varchar(100) default NULL,
`pickupgroup` varchar(10) default NULL,
`qualify` char(3) default 'yes',
`regexten` varchar(80) default NULL,
`restrictcid` char(3) default NULL,
`rtptimeout` char(3) default '30',
`rtpholdtimeout` char(3) default NULL,
`secret` varchar(80) default NULL,
`setvar` varchar(100) default NULL,
`disallow` varchar(100) default 'all',
`allow` varchar(100) default 'g729;g723;ilbc;gsm;ulaw;alaw',
`fullcontact` varchar(80) NOT NULL default '',
`ipaddr` varchar(15) NOT NULL default '',
`port` smallint(5) unsigned NOT NULL default '5060',
`regserver` varchar(100) default NULL,
`regseconds` int(11) NOT NULL default '300',
`lastms` int(11) NOT NULL default '0',
`username` varchar(80) NOT NULL default '',
`defaultuser` varchar(80) NOT NULL default '',
`subscribecontext` varchar(80) default NULL,
`useragent` varchar(20) default NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `name` (`name`),
KEY `name_2` (`name`)
) ENGINE=InnoDB ROW_FORMAT=DYNAMIC;