CREATE TABLE IF NOT EXISTS characters
        (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        HEXid char(6) unique,
        pri_color TINYINT,
        sec_color TINYINT,
        date_created datetime default '0000-00-00 00:00:00',
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS features
        (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        HEXid char(6) unique,
        sprite_filename TEXT,
        station_id TEXT,
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS visits
        (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,        
        date_posted datetime NOT NULL default '0000-00-00 00:00:00',
        current_state TINYINT,
        character_ID   char(6),
        feature_ID     char(6),
        svg_path TEXT,
        audio_path TEXT,
        FOREIGN KEY (character_ID)  REFERENCES characters(HEXid),
        FOREIGN KEY (feature_ID)      REFERENCES features(HEXid),
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;
