USE test;

CREATE DATABASE IF NOT EXISTS test;

SHOW DATABASES LIKE 'test';

SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'sys';

INSERT INTO employee(`CompanyID`, `UserID`) VALUES ((SELECT `CompanyID` FROM `company` WHERE `name` = 'test5'),(SELECT `userID` FROM `user` WHERE `email` = 'test@test.com'));

SELECT `userID` FROM `user` WHERE `email` = 'test@test.com';

SELECT `CompanyID` FROM `company` WHERE `name` = 'test5';

SELECT * FROM `user`;

SELECT * FROM `company`;

SELECT * FROM `booking`;

SELECT * FROM `employee`;

SELECT * FROM `meetingroom`;

SELECT * FROM `roomequipment`;

SELECT * FROM `equipment`;

SELECT * FROM `logevent`;

SELECT * FROM `logaction`;

SELECT * FROM `companyposition`;

SELECT * FROM `accesslevel`;

INSERT INTO `accesslevel`(`accessname`, `description`) VALUES ('test','test');

DELETE FROM `accesslevel` WHERE `AccessID` = 7;

INSERT INTO `companyposition`(`name`, `description`) VALUES ('test','test');

DELETE FROM `companyposition` WHERE `PositionID` = 3;

DELETE FROM `logaction` WHERE `actionID` = 1;

INSERT INTO `logevent`(`actionID`) VALUES (1);

UPDATE `logevent` SET `logDateTime` = `logDateTime` - INTERVAL 40 DAY WHERE `logID` < 6;

DELETE FROM `logevent` WHERE (`logDateTime` < CURDATE() - INTERVAL 30 DAY) AND `logID` <> 0;

DELETE FROM `equipment` WHERE `EquipmentID` = 3;

SELECT re.amount, e.`name`, e.`description` FROM `equipment` e JOIN `roomequipment` re JOIN `meetingroom` m WHERE m.meetingroomid = re.meetingroomid AND re.EquipmentID = e.EquipmentID AND m.`name` = 'Blåmann';

DELETE FROM `roomequipment` WHERE `MeetingRoomID` = 1 AND `equipmentID` = 3;

INSERT INTO `roomequipment`(`equipmentID`, `meetingRoomID`, `amount`) VALUES (3,1,3);

UPDATE `meetingroom` SET `location` = NULL WHERE `meetingRoomID` = 2;

UPDATE `meetingroom` SET `location` = 'New location URL/location description' WHERE `meetingRoomID` = 2;

DELETE FROM `meetingroom` WHERE `meetingRoomID` = 3;

SELECT m.`name` AS BookedRoomName, b.startDateTime AS StartTime, b.endDateTime AS EndTime, b.displayName AS BookedBy, u.firstName, u.lastName, u.email, c.`name` AS WorksForCompany, b.description AS BookingDescription, b.dateTimeCreated AS BookingWasCreatedOn, b.actualEndDateTime AS BookingWasCompletedOn, b.dateTimeCancelled AS BookingWasCancelledOn FROM `booking` b LEFT JOIN `meetingroom` m ON b.meetingRoomID = m.meetingRoomID LEFT JOIN `user` u ON u.userID = b.userID LEFT JOIN `employee` e ON e.UserID = u.userID LEFT JOIN `company` c ON c.CompanyID = e.CompanyID;

SELECT m.`name` AS BookedRoomName, b.startDateTime AS StartTime, b.endDateTime AS EndTime, b.displayName AS BookedBy, u.firstName, u.lastName, u.email, GROUP_CONCAT(c.`name` separator ', ') AS WorksForCompany, b.description AS BookingDescription, b.dateTimeCreated AS BookingWasCreatedOn, b.actualEndDateTime AS BookingWasCompletedOn, b.dateTimeCancelled AS BookingWasCancelledOn FROM `booking` b LEFT JOIN `meetingroom` m ON b.meetingRoomID = m.meetingRoomID LEFT JOIN `user` u ON u.userID = b.userID LEFT JOIN `employee` e ON e.UserID = u.userID LEFT JOIN `company` c ON c.CompanyID = e.CompanyID GROUP BY b.bookingID;

DELETE FROM `company` WHERE `companyID` = 3;

DELETE FROM `user` WHERE `userID` = 9;

INSERT INTO `employee`(`companyID`, `userID`, `positionID`) VALUES (1,1,2);

DELETE FROM `employee` WHERE `UserID` = 10 AND `companyID` = 1;

SELECT * FROM `booking` ORDER BY `startDateTime` ASC;

DELETE FROM `booking` WHERE `bookingID` <> 0 AND ((`actualEndDateTime` < CURDATE() - INTERVAL 30 DAY) OR  (`dateTimeCancelled` < CURDATE() - INTERVAL 30 DAY));

DELETE FROM `booking` WHERE `bookingID` = 8;

UPDATE `company` SET `removeAtDate` = DATE(CURRENT_TIMESTAMP) WHERE `CompanyID` = 9;

DELETE FROM `company` WHERE `removeAtDate` IS NOT NULL AND `removeAtDate` < CURRENT_TIMESTAMP AND `CompanyID` <> 0;

DELETE FROM `company` WHERE `CompanyID` = 8;

DELETE FROM `user` WHERE userID = 15;

UPDATE `accesslevel` SET `Description` = 'New description of the permission for the access level' WHERE `AccessID` = 6;

UPDATE `accesslevel` SET `AccessName` = 'New name for the access level' WHERE `AccessID` = 6;

UPDATE `logaction` SET `name` = 'New log action name' WHERE `actionID` = 1;

UPDATE `companyposition` SET `name` = 'Employee' WHERE `PositionID` = 2;

UPDATE `equipment` SET `description` = 'New description for equipment' WHERE `EquipmentID` = 3;

UPDATE `equipment` SET `name` = 'New name for equipment' WHERE `EquipmentID` = 3;

UPDATE `roomequipment` re JOIN `equipment` e ON e.EquipmentID = re.EquipmentID JOIN `meetingroom` m ON m.meetingRoomID = re.MeetingRoomID SET re.`amount` = 2 WHERE re.EquipmentID = 2 AND re.meetingRoomID = 1;

UPDATE `meetingroom` SET `location` = 'New location URL/location description' WHERE `meetingRoomID` = 3;

INSERT INTO `meetingroom`(`name`, `capacity`, `description`, `location`) VALUES ('A fake meeting room', 0, 'Cannot fit anyone.', 'Random image url');

UPDATE `meetingroom` SET `description` = 'New Description of the meeting room' WHERE `meetingRoomID` = 3;

UPDATE `meetingroom` SET `capacity` = 4 WHERE `meetingRoomID` = 2;

UPDATE `employee` e JOIN `user` u ON u.userID = e.UserID JOIN `company` c ON c.CompanyID = e.CompanyID SET e.`PositionID` = 1 WHERE c.CompanyID = 4 AND u.userID = 5;

SELECT m.`name` AS BookedRoomName, b.startDateTime AS StartTime, b.endDateTime AS EndTime FROM `booking` b LEFT JOIN `meetingroom` m ON b.meetingRoomID = m.meetingRoomID LEFT JOIN `user` u ON u.userID = b.userID LEFT JOIN `employee` e ON e.UserID = u.userID LEFT JOIN `company` c ON c.CompanyID = e.CompanyID WHERE b.dateTimeCancelled IS NULL AND b.actualEndDateTime IS NULL AND b.endDateTime BETWEEN '2017-03-22 14:20:00' AND '2017-03-30 14:30:00';

SELECT m.`name` AS BookedRoomName, b.startDateTime AS StartTime, b.endDateTime AS EndTime, b.displayName AS BookedBy, c.`name` AS Company, b.description AS BookingDescription FROM `booking` b LEFT JOIN `meetingroom` m ON b.meetingRoomID = m.meetingRoomID LEFT JOIN `user` u ON u.userID = b.userID LEFT JOIN `employee` e ON e.UserID = u.userID LEFT JOIN `company` c ON c.CompanyID = e.CompanyID WHERE b.dateTimeCancelled IS NULL AND b.actualEndDateTime IS NULL AND b.endDateTime BETWEEN '2017-03-22 14:20:00' AND '2017-03-30 14:30:00';

SELECT m.`name` AS BookedRoomName, b.startDateTime AS StartTime, b.endDateTime AS EndTime, b.displayName AS BookedBy, c.`name` AS Company, b.description AS BookingDescription FROM `booking` b LEFT JOIN `meetingroom` m ON b.meetingRoomID = m.meetingRoomID LEFT JOIN `user` u ON u.userID = b.userID LEFT JOIN `employee` e ON e.UserID = u.userID LEFT JOIN `company` c ON c.CompanyID = e.CompanyID WHERE b.dateTimeCancelled IS NULL AND b.actualEndDateTime IS NULL AND CURRENT_TIMESTAMP < b.endDateTime AND c.CompanyID = 4;

SELECT m.`name` AS BookedRoomName, b.startDateTime AS StartTime, b.endDateTime AS EndTime, b.displayName AS YourDisplayedName, b.description AS YourBookingDescription, b.dateTimeCreated AS BookingWasCreatedOn, b.actualEndDateTime AS BookingWasCompletedOn, b.dateTimeCancelled AS BookingWasCancelledOn FROM `booking` b LEFT JOIN `meetingroom` m ON b.meetingRoomID = m.meetingRoomID LEFT JOIN `user` u ON u.userID = b.userID LEFT JOIN `employee` e ON e.UserID = u.userID LEFT JOIN `company` c ON c.CompanyID = e.CompanyID WHERE b.userID = 1;

INSERT INTO meetingroom(`name`, `capacity`, `description`) VALUES ('Toillpeis', 3, 'You must be a real toillpeis to have booked to room!');

INSERT INTO booking(`meetingRoomID`, `userID`, `displayName`, `startDateTime`, `endDateTime`, `description`) VALUES ((SELECT `meetingRoomID` FROM `meetingroom` WHERE `name` = 'Blåmann'), (SELECT `userID` FROM `user` WHERE `email` = 'test@test.com'), 'CoolViewGuy', '2017-03-15 16:00:00', '2017-03-15 17:30:00', 'This booking is just to look at the COOL VIEW!');

INSERT INTO booking(`meetingRoomID`, `userID`, `displayName`, `startDateTime`, `endDateTime`, `description`) VALUES ((SELECT `meetingRoomID` FROM `meetingroom` WHERE `name` = 'Toillpeis'), (SELECT `userID` FROM `user` WHERE `email` = 'test2@test.com'), 'A real toillpeis', '2017-03-16 12:00:00', '2017-03-16 13:30:00', 'I could not find a better room');

INSERT INTO booking(`meetingRoomID`, `userID`, `displayName`, `startDateTime`, `endDateTime`, `description`) VALUES ((SELECT `meetingRoomID` FROM `meetingroom` WHERE `name` = 'Toillpeis'), (SELECT `userID` FROM `user` WHERE `email` = 'test@test.com'), 'CoolViewGuy', '2017-03-16 13:30:00', '2017-03-16 14:00:00', 'Someone told me this has a cool view');

INSERT INTO booking(`meetingRoomID`, `userID`, `displayName`, `startDateTime`, `endDateTime`, `description`, `cancellationCode`) VALUES ((SELECT `meetingRoomID` FROM `meetingroom` WHERE `name` = 'Toillpeis'), (SELECT `userID` FROM `user` WHERE `email` = 'test2@test.com'), 'A real toillpeis', '2017-03-21 12:00:00', '2017-03-21 13:30:00', 'I could not find a better room', 'ecd71870d1963316a97e3ac3408c9835ad8cf0f3c1bc703527c30265534f75ae');

INSERT INTO booking(`meetingRoomID`, `userID`, `displayName`, `startDateTime`, `endDateTime`, `description`, `cancellationCode`) VALUES ((SELECT `meetingRoomID` FROM `meetingroom` WHERE `name` = 'Toillpeis'), (SELECT `userID` FROM `user` WHERE `email` = 'test11@test.com'), 'NEED IT!', '2017-01-22 14:30:00', '2017-01-22 15:30:00', '...?', 'ecd71870d1963316a97e3ac3408c9835ad8cf0f3c1bc703527c30265534f75ae');

UPDATE `booking` SET actualEndDateTime = endDateTime WHERE actualEndDateTime IS NULL AND dateTimeCancelled IS NULL AND endDateTime < CURRENT_TIMESTAMP AND bookingID <> 0;

UPDATE `booking` SET dateTimeCancelled = CURRENT_TIMESTAMP WHERE bookingID = 6;

UPDATE `booking` SET `displayName` = 'new Display Name' WHERE bookingID = 6;

UPDATE `booking` SET `description` = 'new Booking Description' WHERE bookingID = 6;

UPDATE `booking` SET `actualEndDateTime` = CURRENT_TIMESTAMP WHERE bookingID = 5 AND CURRENT_TIMESTAMP BETWEEN `startDateTime` AND `endDateTime`;

SELECT * FROM `booking` b LEFT JOIN `meetingroom` m ON b.meetingRoomID = m.meetingRoomID LEFT JOIN `user` u ON u.userID = b.userID LEFT JOIN `employee` e ON e.UserID = u.userID LEFT JOIN `company` c ON c.CompanyID = e.CompanyID;

SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(b.`actualEndDateTime`) - TIME_TO_SEC(b.`startDateTime`)))  AS BookingTimeUsed FROM `booking` b INNER JOIN `user` u ON b.`UserID` = u.`UserID` WHERE b.actualEndDateTime BETWEEN '2017-03-15' AND '2017-03-17' AND u.`userID` = 1;

SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(b.`actualEndDateTime`) - TIME_TO_SEC(b.`startDateTime`)))  AS CompanyWideBookingTimeUsed FROM `booking` b INNER JOIN `employee` e ON b.`UserID` = e.`UserID` INNER JOIN `company` c ON e.`CompanyID` = c.`CompanyID` WHERE b.`actualEndDateTime` BETWEEN '2017-03-15' AND '2017-03-17' AND c.`CompanyID` = 5;

DELETE FROM `booking` WHERE `bookingID` = 1;

SELECT * FROM `booking` INNER JOIN `employee` ON `Booking`.`UserID` = `employee`.`UserID`;

SELECT COUNT(*) AS CompanyBookings FROM `booking` INNER JOIN `employee` ON `Booking`.`UserID` = `employee`.`UserID`;

SELECT * FROM `booking` INNER JOIN `employee` ON `Booking`.`UserID` = `employee`.`UserID` INNER JOIN `company` ON `employee`.`CompanyID` = `company`.`CompanyID` WHERE `name` = 'test5';

SELECT `booking`.`bookingID`, `booking`.`startDateTime`, `booking`.`endDateTime` FROM `booking` INNER JOIN `employee` ON `Booking`.`UserID` = `employee`.`UserID` INNER JOIN `company` ON `employee`.`CompanyID` = `company`.`CompanyID` WHERE `name` = 'test5';

SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(b.`actualEndDateTime`) - TIME_TO_SEC(b.`startDateTime`)))  AS CompanyWideBookingTimeUsed FROM `booking` b INNER JOIN `employee` e ON b.`UserID` = e.`UserID` INNER JOIN `company` c ON e.`CompanyID` = c.`CompanyID` WHERE c.`name` = 'New Company Name';

SELECT c.`name`, SEC_TO_TIME(SUM(TIME_TO_SEC(b.`actualEndDateTime`) - TIME_TO_SEC(b.`startDateTime`)))  AS CompanyWideBookingTimeUsed FROM `booking` b INNER JOIN `employee` e ON b.`UserID` = e.`UserID` INNER JOIN `company` c ON e.`CompanyID` = c.`CompanyID` ORDER BY c.`name`;

INSERT INTO `companyposition`(`name`, `description`) VALUES ('Owner', 'This person has access to all company information and management.');

INSERT INTO `companyposition`(`name`, `description`) VALUES ('Employee', 'This person has access to browse company information.');

INSERT INTO `accesslevel`(`AccessName`, `Description`) VALUES ('Admin', 'Full website access.');

INSERT INTO `accesslevel`(`AccessName`, `Description`) VALUES ('Company Owner', 'Full company information and management.');

INSERT INTO `accesslevel`(`AccessName`, `Description`) VALUES ('In-House User', 'Can book meeting rooms with a booking code.');

INSERT INTO `accesslevel`(`AccessName`, `Description`) VALUES ('Normal User', 'Can browse meeting room schedules, with limited information, and request a booking.');

INSERT INTO `accesslevel`(`AccessName`, `Description`) VALUES ('Meeting Room', 'These are special accounts used to handle booking code login.');

UPDATE `employee` SET `PositionID` = 1 WHERE `PositionID` = 2;

INSERT INTO `equipment`(`name`, `description`) VALUES('HDTV','This TV has an HD signal. HDMI input. etc.');

INSERT INTO `equipment`(`name`, `description`) VALUES('WiFi','This room has a WiFi connection.');

INSERT INTO `equipment`(`name`, `description`) VALUES('ETHERNET','This room supports wired Ethernet connections.');

UPDATE `equipment` SET `description` = '2.4 and 5 Ghz.' WHERE `equipmentID` = 2;

UPDATE `equipment` SET `description` = 'CAT-6 10Gb/s.' WHERE `equipmentID` = 3;

SELECT `EquipmentID` FROM `equipment` WHERE `name` = 'WiFi';

INSERT INTO `roomequipment`(`EquipmentID`, `MeetingRoomID`, `amount`) VALUES((SELECT `EquipmentID` FROM `equipment` WHERE `name` = 'WiFi'), (SELECT `MeetingRoomID` FROM `meetingroom` WHERE `name`= 'Blåmann'), 1);

INSERT INTO `roomequipment`(`EquipmentID`, `MeetingRoomID`, `amount`) VALUES((SELECT `EquipmentID` FROM `equipment` WHERE `name` = 'ETHERNET'), (SELECT `MeetingRoomID` FROM `meetingroom` WHERE `name`= 'Blåmann'), 4);

SELECT * FROM `roomequipment` re JOIN `equipment` e JOIN `meetingroom` m WHERE re.EquipmentID = e.EquipmentID AND re.MeetingRoomID = m.meetingRoomID;

SELECT re.`amount`, e.`name`, e.`description` FROM `roomequipment` re JOIN `equipment` e JOIN `meetingroom` m WHERE re.EquipmentID = e.EquipmentID AND re.MeetingRoomID = m.meetingRoomID;

SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(b.`endDateTime`) - TIME_TO_SEC(b.`startDateTime`)))  AS BookingTimeUsed FROM `booking` b INNER JOIN `user` u ON b.`UserID` = u.`UserID` WHERE u.`firstName` = 'testy' AND u.`lastName` = 'mctester';

SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(b.`endDateTime`) - TIME_TO_SEC(b.`startDateTime`)))  AS BookingTimeUsed FROM `booking` b INNER JOIN `user` u ON b.`UserID` = u.`UserID` WHERE u.`email` = 'test@test.com';

SELECT u.firstName, u.lastName, cp.`name` FROM `company` c JOIN `companyposition` cp JOIN `employee` e JOIN `user` u WHERE u.userID = e.UserID AND e.CompanyID = c.CompanyID AND cp.PositionID = e.PositionID AND c.`name` = 'test5';

SELECT * FROM `company` c JOIN `companyposition` cp JOIN `employee` e JOIN `user` u WHERE u.userID = e.UserID AND e.CompanyID = c.CompanyID AND cp.PositionID = e.PositionID;

SELECT c.`name` FROM `company` c JOIN `employee` e WHERE c.CompanyID = e.CompanyID;

SELECT c.`name`, COUNT(c.`name`) AS NumberOfEmployees FROM `company` c JOIN `employee` e WHERE c.CompanyID = e.CompanyID GROUP BY c.`name`;

INSERT INTO `employee`(`CompanyID`, `UserID`, `PositionID`) VALUES ((SELECT `CompanyID` FROM `company` WHERE `name` = 'test1'),(SELECT `userID` FROM `user` WHERE `email` = 'test10@test.com'), (SELECT `PositionID` FROM `companyposition` WHERE `name` = 'Employee'));

INSERT INTO `company`(`name`) VALUES ('test6');

INSERT INTO `user`(`email`, `password`, `firstname`, `lastname`,`accessID`, `activationcode`) VALUES ('test15@test.com', '123test', 'testy15', 'mctester15', 4, 'ecd71870d1963316a97e3ac3408c9835ad8cf0f3c1bc703527c30265534f75ae');

SELECT u.`firstname`, u.`lastname`, u.`email`, c.`name` AS CompanyName, cp.`name` AS CompanyRole FROM `user` u JOIN `company` c JOIN `employee` e JOIN `companyposition` cp WHERE e.CompanyID = c.CompanyID AND e.UserID = u.userID AND cp.PositionID = e.PositionID ORDER BY c.`name` ;

SELECT `firstname`, `lastname`, `email`, (SELECT c.`name` FROM `company` c JOIN `employee` e JOIN `companyposition`cp JOIN `user` u WHERE c.CompanyID = e.CompanyID AND u.userID = e.UserID) AS CompanyName, (SELECT cp.`name` FROM `companyposition` cp JOIN `company` c JOIN `employee` e JOIN `user` u WHERE c.companyID = e.companyID AND u.userid = e.userid AND cp.positionID = e.positionID) AS CompanyRole FROM `user`;

SELECT DISTINCT u.userID FROM `user` u JOIN `company` c JOIN `employee` e JOIN `companyposition` cp WHERE e.CompanyID = c.CompanyID AND e.UserID = u.userID AND cp.PositionID = e.PositionID ORDER BY u.userid ASC;

SELECT c.`name` FROM `company` c JOIN `employee` e JOIN `companyposition`cp JOIN `user` u WHERE c.CompanyID = e.CompanyID AND u.userID = e.UserID;

SELECT cp.`name` FROM `companyposition` cp JOIN `company` c JOIN `employee` e JOIN `user` u WHERE c.companyID = e.companyID AND u.userid = e.userid AND cp.positionID = e.positionID;

SELECT * FROM `equipment` e JOIN `roomequipment` re JOIN `meetingroom` m WHERE m.meetingroomid = re.meetingroomid AND re.EquipmentID = e.EquipmentID;

INSERT INTO `user`(`email`, `password`, `firstname`, `lastname`, `accessID`, `activationcode`) VALUES ('test15@test.com', '123test', 'testy15', 'mctester15', 4, 'ecd71870d1963316a97e3ac3408c9835ad8cf0f3c1bc703527c30265534f75ae');
