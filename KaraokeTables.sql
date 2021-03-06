/* 
    Sania Azhar    (z1884677)
    Nikolas Gatov  (z1884744)
    Leo Jaos       (z1911688)
    Olivia Merrell (z1896986)
    Muhammad Naeem (z1906224)
    
    CSCI 466-0001
    Group Project - Karaoke Management System
*/

DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Songs;
DROP TABLE IF EXISTS Contributor;
DROP TABLE IF EXISTS Kfiles;
DROP TABLE IF EXISTS Contribution;
DROP TABLE IF EXISTS Queues;
DROP TABLE IF EXISTS PQueues;

CREATE TABLE Users(
	USID	INT	       NOT NULL AUTO_INCREMENT,
	NAME    CHAR(100)  NOT NULL,

	PRIMARY KEY (USID)
);
CREATE TABLE Songs(
	SID	    INT	       NOT NULL AUTO_INCREMENT,
	TITLE   CHAR(100)  NOT NULL,
	ARTIST  CHAR(100)  NOT NULL,

	PRIMARY KEY (SID)
);
CREATE TABLE Contributor(
	CID     INT        NOT NULL AUTO_INCREMENT,
	NAME    CHAR(100)  NOT NULL,

	PRIMARY KEY (CID)
);
CREATE TABLE Kfiles(
	KID	       INT	      NOT NULL AUTO_INCREMENT,
	VERSION    CHAR(100)  NOT NULL,
	SID        INT        NOT NULL,

	PRIMARY KEY (KID),
	FOREIGN KEY(SID) REFERENCES Songs(SID)
);
CREATE TABLE Contribution(
	SID	  INT	     NOT NULL,
	CID   INT        NOT NULL,
	ROLE  CHAR(100)  NOT NULL,

	PRIMARY KEY (SID, CID),
	FOREIGN KEY(SID) REFERENCES Songs(SID),
	FOREIGN KEY(CID) REFERENCES Contributor(CID)
);
CREATE TABLE Queues(
	USID	 INT	  NOT NULL,
	KID      INT      NOT NULL,
	SID      INT,

	PRIMARY KEY (USID, KID),
	FOREIGN KEY(USID) REFERENCES Users(USID),
	FOREIGN KEY(KID) REFERENCES Kfiles(KID),
	FOREIGN KEY(SID) REFERENCES Songs(SID)	
);
CREATE TABLE PQueues(
	USID	 INT	          NOT NULL,
	KID      INT              NOT NULL,
	SID      INT,
	AMOUNT   DECIMAL(6,2)	  NOT NULL,

	PRIMARY KEY (USID, KID),
	FOREIGN KEY(USID) REFERENCES Users(USID),
	FOREIGN KEY(KID) REFERENCES Kfiles(KID),
	FOREIGN KEY(SID) REFERENCES Songs(SID)	
);