DROP DATABASE IF EXISTS `Spa`;

CREATE DATABASE `Spa`;

USE `Spa`;

CREATE TABLE `Services` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NOT NULL,
    `Description` TEXT NOT NULL,
    `Price` INT NOT NULL,
    `Duration` VARCHAR(50) NOT NULL
);

CREATE TABLE `Clients` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `ServiceId` INT NOT NULL,
    `Name` VARCHAR(100) NOT NULL,
    `Mobile` VARCHAR(15) NOT NULL,
    `Address` VARCHAR(100) NOT NULL,
    `Age` INT NOT NULL,
    `Email` VARCHAR(100) NOT NULL,
    `AmountPaid` DECIMAL(10, 2) NOT NULL,
    `AmountDue` DECIMAL(10, 2) NOT NULL,
    `StartDate` DATE NOT NULL,
    `EndDate` DATE NOT NULL,
    `IsDelete` INT NOT NULL DEFAULT 1,
    FOREIGN KEY (`ServiceId`) REFERENCES `Services`(`Id`)
);

CREATE TABLE `Employee` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NOT NULL,
    `Mobile` VARCHAR(15) NOT NULL,
    `Address` VARCHAR(100) NOT NULL,
    `Age` INT NOT NULL,
    `Email` VARCHAR(100) NOT NULL,
    `RelationName` VARCHAR(100) NOT NULL,
    `RelationMobile` VARCHAR(15) NOT NULL,
    `RelationAddress` VARCHAR(100) NOT NULL,
    `Relation` VARCHAR(100) NOT NULL,
    `ImageFileName` VARCHAR(255) NOT NULL,
    `AddharCardImageFileName` VARCHAR(255) NOT NULL,
    `AddharCardNumber` VARCHAR(20) NOT NULL,
    `SalaryPaid` DECIMAL(10, 2) NOT NULL,
    `SalaryDue` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `Appointments` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `ClientId` INT NOT NULL,
    `EmployeeId` INT NOT NULL,
    `RoomNo` INT NOT NULL,
    `AppointmentDate` DATETIME NOT NULL,
    `AppointmentTime` TIME NOT NULL,
    `Status` VARCHAR(100) NOT NULL,
    `IsDelete` INT NOT NULL DEFAULT 1,
    FOREIGN KEY (`ClientId`) REFERENCES `Clients`(`Id`),
    FOREIGN KEY (`EmployeeId`) REFERENCES `Employee`(`Id`)
);

CREATE TABLE `Sales` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `AppointmentId` INT NOT NULL,
    `Amount` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`AppointmentId`) REFERENCES `Appointments`(`Id`)
);

CREATE TABLE `Expenses` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NOT NULL,
    `Description` VARCHAR(255) NOT NULL,
    `Date` DATE NOT NULL,
    `Price` DECIMAL(10, 2) NOT NULL,
    `Quantity` INT NOT NULL,
    `TotalAmount` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `TotalExpenses` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `EmployeeId` INT NOT NULL,
    `ExpenseId` INT NOT NULL,
    `TotalAmount` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`EmployeeId`) REFERENCES `Employee`(`Id`),
    FOREIGN KEY (`ExpenseId`) REFERENCES `Expenses`(`Id`)
)