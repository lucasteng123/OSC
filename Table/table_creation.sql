CREATE TABLE IF NOT EXISTS characters
        (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        HEXid TEXT,
        pri_color TINYINT,
        sec_color TINYINT,
        date_created datetime default '0000-00-00 00:00:00',
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS features
        (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        colFilter_ID TEXT,
        sprite_filename TEXT,
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;

CREATE TABLE IF NOT EXISTS visits
        (
        id MEDIUMINT NOT NULL AUTO_INCREMENT,
        date_posted datetime NOT NULL default '0000-00-00 00:00:00',
        character_ID MEDIUMINT,
        feature_ID     MEDIUMINT,
        svg_path TEXT,
        audio_path TEXT,
        FOREIGN KEY (character_ID)  REFERENCES characters(id),
        FOREIGN KEY (feature_ID)      REFERENCES features(id),
        PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=UTF8;