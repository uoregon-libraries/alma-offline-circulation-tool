DROP TABLE IF EXISTS `circulation`;
CREATE TABLE `circulation` (
  `library` char(3) NOT NULL,
  `datetime` char(14) NOT NULL,
  `retorlo` char(1) NOT NULL,
  `itmbarcd` char(20) NOT NULL,
  `itmcalnm` char(48) NOT NULL,
  `ptrbarcd` char(20) NOT NULL,
  `ptrname` char(48) NOT NULL,
  `saved` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
