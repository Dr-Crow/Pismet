# This file creates the Pismet database.
# Author: <James Crowley>
drop database if exists pismet_db;
create database pismet_db;
use pismet_db;

CREATE TABLE IF NOT EXISTS users
(
	num INT AUTO_INCREMENT PRIMARY KEY,
	name TEXT NOT NULL,
	email VARCHAR(60) NOT NULL UNIQUE,
	accessLevel set("Technician", "Admin", "Super User") NOT NULL
);

INSERT INTO users(name, email, accessLevel)
VALUES
("James Crowley", "james.crowley1@marist.edu", "Super User");


CREATE TABLE IF NOT EXISTS networks
(
	mac    			VARCHAR(200) PRIMARY KEY,
	essid    			TEXT,
	discovery_type  	TEXT,
	info    			TEXT,
	manuf				TEXT,
	carrier				TEXT,
	dev_name			TEXT,
	network_type		TEXT
);

CREATE TABLE IF NOT EXISTS network_channels
(
	mac					VARCHAR(200),
	channel				BIGINT NOT NULL,
	PRIMARY KEY(mac, channel),
	FOREIGN KEY(mac) REFERENCES networks(mac)
);
	
CREATE TABLE IF NOT EXISTS network_freqs
(
	mac					VARCHAR(200),
	freq				DOUBLE NOT NULL,
	PRIMARY KEY(mac, freq),
	FOREIGN KEY(mac) REFERENCES networks(mac)
);

CREATE TABLE IF NOT EXISTS network_encryptions
(
	mac					VARCHAR(200),
	encryption			VARCHAR(200),
	PRIMARY KEY(mac, encryption),
	FOREIGN KEY(mac) REFERENCES networks(mac)
);

CREATE TABLE IF NOT EXISTS network_snr
(
	mac					VARCHAR(200),
	seen    			TIMESTAMP NOT NULL,
	packets		 		BIGINT,
	last_signal_dbm 	INT,
	last_noise_dbm  	INT,
	max_signal_dbm  	INT,
	max_noise_dbm   	INT,
	PRIMARY KEY(mac, seen),
	FOREIGN KEY(mac) REFERENCES networks(mac)
);

CREATE TABLE IF NOT EXISTS clients
(
	mac					VARCHAR(200),
	seen    			TIMESTAMP NOT NULL,
	manuf				TEXT,
	carrier 			TEXT,
	channel				BIGINT NOT NULL,
	freq				DOUBLE NOT NULL,
	network_mac			VARCHAR(200),
	packets		 		BIGINT,
	last_signal_dbm 	INT,
	last_noise_dbm  	INT,
	max_signal_dbm  	INT,
	max_noise_dbm   	INT,
	PRIMARY KEY(mac, seen),
	FOREIGN KEY(network_mac) REFERENCES networks(mac)
);