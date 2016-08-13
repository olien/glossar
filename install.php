<?php
$sql = rex_sql::factory();
$sql->setQuery('
CREATE TABLE IF NOT EXISTS `rex_glossar` (
    `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `id` int(10) unsigned NOT NULL,
    `clang_id` int(10) unsigned NOT NULL,
    `term` varchar(255) DEFAULT NULL,
    `definition` text,
    `description` text,
    PRIMARY KEY (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
');

