ALTER	DATABASE dateadan CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS report;
DROP TABLE IF EXISTS `match`;
DROP TABLE IF EXISTS answer;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS userDetail;
DROP TABLE IF EXISTS user;

CREATE TABLE user (
	userId BINARY(16) NOT NULL,
	userActivationToken CHAR(32),
	userAgent VARCHAR(255),
	userAvatarUrl VARCHAR(255),
	userBlocked TINYINT(1) NOT NULL,
	userEmail VARCHAR(128) NOT NULL,
	userHandle VARCHAR(32) NOT NULL,
	userHash CHAR(97) NOT NULL,
	userIpAddress VARBINARY(16) NOT NULL,
	UNIQUE (userHandle),
	UNIQUE (userEmail),
	PRIMARY KEY(userId)

);

CREATE TABLE userDetail (
	userDetailId BINARY(16) NOT NULL,
	userDetailUserId BINARY(16) NOT NULL,
	userDetailAboutMe VARCHAR(1024),
	userDetailAge INT(3) NOT NULL,
	userDetailCareer VARCHAR(32),
	userDetailDisplayEmail VARCHAR(128),
	userDetailEducation VARCHAR(256),
	userDetailGender VARCHAR(32) NOT NULL,
	userDetailInterests VARCHAR(1024),
	userDetailRace VARCHAR(32),
	userDetailReligion VARCHAR(128),
	INDEX (userDetailUserId),
	UNIQUE (userDetailUserId),
	FOREIGN KEY(userDetailUserId) REFERENCES user(userId),
	PRIMARY KEY(userDetailId)

);

CREATE TABLE question (
	questionId BINARY(16) NOT NULL,
	questionContent VARCHAR (128) NOT NULL,
	questionValue TINYINT (1) NOT NULL,
	PRIMARY KEY(questionId)
);

CREATE TABLE answer (
	answerQuestionId BINARY(16) NOT NULL,
	answerUserId BINARY(16) NOT NULL,
	answerResult CHAR(1) NOT NULL,
	answerScore TINYINT(1) NOT NULL,
	INDEX (answerQuestionId),
	INDEX (answerUserId),
	FOREIGN KEY (answerUserId) REFERENCES user(userId),
	FOREIGN KEY (answerQuestionId) REFERENCES question(questionId),
	PRIMARY KEY (answerQuestionId, answerUserId)

);

CREATE TABLE `match` (
	matchUserId BINARY(16) NOT NULL,
	matchToUserId BINARY(16) NOT NULL,
	matchApproved TINYINT(1) NOT NULL,
	INDEX (matchUserId),
	INDEX (matchToUserId),
	FOREIGN KEY (matchUserId) REFERENCES user(userId),
	FOREIGN KEY (matchToUserId) REFERENCES user(userId),
	PRIMARY KEY (matchUserId, matchToUserId)
);

CREATE TABLE report (
   reportId BINARY(16) NOT NULL,
	reportUserId BINARY(16) NOT NULL,
	reportAbuserId BINARY(16) NOT NULL,
	reportAgent VARCHAR(255) NOT NULL,
	reportContent VARCHAR(255) NOT NULL,
	reportDate DATETIME(6) NOT NULL,
	reportIp VARBINARY (16) NOT NULL,
	INDEX (reportUserId),
	INDEX (reportAbuserId),
	FOREIGN KEY (reportUserId) REFERENCES user(userId),
	FOREIGN KEY (reportAbuserId) REFERENCES user(userId),
	PRIMARY KEY (reportId)
);
