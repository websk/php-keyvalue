CREATE TABLE key_value (id int NOT NULL AUTO_INCREMENT PRIMARY KEY, created_at_ts int NOT NULL DEFAULT 0) ENGINE InnoDB DEFAULT CHARSET utf8 /* 293ug308h0f923ff3 */;
ALTER TABLE key_value ADD COLUMN name varchar(255) NOT NULL /* 2c8h09320293fj039 */;
ALTER TABLE key_value ADD COLUMN value mediumtext /* 2c8h09320293fj039 */;
ALTER TABLE key_value ADD COLUMN description varchar(255) NOT NULL DEFAULT '' /* 3984hg34h00349j0f */;
ALTER TABLE key_value ADD UNIQUE KEY name (name)  /* 2h893ch9c8ch93f8h */;