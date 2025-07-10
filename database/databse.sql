DROP DATABASE IF EXISTS `Spa`;

CREATE DATABASE `Spa`;

USE `Spa`;

CREATE TABLE `Services` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NULL,
    `Description` TEXT NULL,
    `Price` INT NULL,
    `Duration` VARCHAR(50) NULL,
    `NoOfAppointments` INT NULL
);

CREATE TABLE `Membership` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `ServiceId` INT NULL,
    `Name` VARCHAR(100) NULL,
    `Mobile` VARCHAR(15) NULL,
    `Address` VARCHAR(100) NULL,
    `Age` INT NULL,
    `Email` VARCHAR(100) NULL,
    `AmountPaid` DECIMAL(10, 2) NULL,
    `AmountDue` DECIMAL(10, 2) NULL,
    `TotalAmount` DECIMAL(10, 2) NULL,
    `StartDate` DATE NULL,
    `EndDate` DATE NULL,
    `IsDelete` INT NULL DEFAULT 1,
    `PaymentMode` VARCHAR(50) NULL,
    FOREIGN KEY (`ServiceId`) REFERENCES `Services`(`Id`)
);

CREATE TABLE `Employee` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NULL,
    `Mobile` VARCHAR(15) NULL,
    `Address` VARCHAR(100) NULL,
    `Age` INT NULL,
    `Email` VARCHAR(100) NULL,
    `RelationName` VARCHAR(100) NULL,
    `RelationMobile` VARCHAR(15) NULL,
    `RelationAddress` VARCHAR(100) NULL,
    `Relation` VARCHAR(100) NULL,
    `JoiningDate` DATE NULL,
    `ImageFileName` VARCHAR(255) NULL,
    `AddharCardImageFileName` VARCHAR(255) NULL,
    `AddharCardNumber` VARCHAR(20) NULL,
    `TotalSalary` INT NULL,
    `SalaryPaidDate` DATE NULL,
    `GivenSalary` INT NULL,
    `PaymentMode` VARCHAR(255) NULL
);

CREATE TABLE `Appointments` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `MemberId` INT NULL,
    `EmployeeId` INT NULL,
    `RoomNo` INT NULL,
    `Massage` TEXT NULL,
    `AppointmentDate` DATETIME NULL,
    `InTime` TIME NULL,
    `OutTime` TIME NULL,
    `IsDelete` INT NULL DEFAULT 1,
    FOREIGN KEY (`MemberId`) REFERENCES `Membership`(`Id`),
    FOREIGN KEY (`EmployeeId`) REFERENCES `Employee`(`Id`)
);

CREATE TABLE `Sales` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `MemberId` INT NULL,
    `Amount` DECIMAL(10, 2) NULL,
    FOREIGN KEY (`MemberId`) REFERENCES `Membership`(`Id`)
);

CREATE TABLE `Expenses` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NULL,
    `Description` VARCHAR(255) NULL,
    `Date` DATE NULL,
    `Volume` VARCHAR(50) NULL,
    `Price` DECIMAL(10, 2) NULL,
    `Quantity` INT NULL,
    `TotalAmount` DECIMAL(10, 2) NULL,
    `PaymentMode` VARCHAR(50) NULL

);

CREATE TABLE `TotalExpenses` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `EmployeeTotal` INT NULL,
    `ExpenseTotal` INT NULL,
    `TotalAmount` DECIMAL(10, 2) NULL
);

CREATE TABLE `Clients` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NULL,
    `Mobile` INT NULL,
    `Therapy` VARCHAR(100) NULL,
    `EmployeeId` INT NULL,
    `Date` DATE NULL,
    `InTime` TIME NULL,
    `OutTime` TIME NULL,
    `Massage` TEXT NULL,
    `Price` DECIMAL(10, 2) NULL,
    `PaymentMode` VARCHAR(50) NULL,
    FOREIGN KEY (`EmployeeId`) REFERENCES `Employee`(`Id`)
);