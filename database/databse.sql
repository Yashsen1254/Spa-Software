DROP DATABASE IF EXISTS `Spa`;

CREATE DATABASE `Spa`;

USE `Spa`;

CREATE TABLE `Services` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NOT NULL,
    `Description` TEXT NOT NULL,
    `Price` INT NOT NULL,
    `Duration` VARCHAR(50) NOT NULL,
    `NoOfAppointments` INT NOT NULL
);

CREATE TABLE `Membership` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `ServiceId` INT NOT NULL,
    `Name` VARCHAR(100) NOT NULL,
    `Mobile` VARCHAR(15) NOT NULL,
    `Address` VARCHAR(100) NOT NULL,
    `Age` INT NOT NULL,
    `Email` VARCHAR(100) NOT NULL,
    `AmountPaid` DECIMAL(10, 2) NOT NULL,
    `AmountDue` DECIMAL(10, 2) NOT NULL,
    `TotalAmount` DECIMAL(10, 2) NOT NULL,
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
    `JoiningDate` DATE NOT NULL,
    `ImageFileName` VARCHAR(255) NOT NULL,
    `AddharCardImageFileName` VARCHAR(255) NOT NULL,
    `AddharCardNumber` VARCHAR(20) NOT NULL,
    `SalaryPaid` DECIMAL(10, 2) NOT NULL,
    `SalaryPaidDate` DATE NOT NULL,
    `SalaryDue` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `Appointments` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `MemberId` INT NOT NULL,
    `EmployeeId` INT NOT NULL,
    `RoomNo` INT NOT NULL,
    `AppointmentDate` DATETIME NOT NULL,
    `InTime` TIME NOT NULL,
    `OutTime` TIME NOT NULL,
    `IsDelete` INT NOT NULL DEFAULT 1,
    FOREIGN KEY (`MemberId`) REFERENCES `Membership`(`Id`),
    FOREIGN KEY (`EmployeeId`) REFERENCES `Employee`(`Id`)
);

CREATE TABLE `Sales` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `MemberId` INT NOT NULL,
    `Amount` DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (`MemberId`) REFERENCES `Membership`(`Id`)
);

CREATE TABLE `Expenses` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NOT NULL,
    `Description` VARCHAR(255) NOT NULL,
    `Date` DATE NOT NULL,
    `Volume` VARCHAR(50) NOT NULL,
    `Price` DECIMAL(10, 2) NOT NULL,
    `Quantity` INT NOT NULL,
    `TotalAmount` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `TotalExpenses` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `EmployeeTotal` INT NOT NULL,
    `ExpenseTotal` INT NOT NULL,
    `TotalAmount` DECIMAL(10, 2) NOT NULL
);

CREATE TABLE `Clients` (
    `Id` INT PRIMARY KEY AUTO_INCREMENT,
    `Name` VARCHAR(100) NOT NULL,
    `Mobile` INT NOT NULL,
    `Therapy` VARCHAR(100) NOT NULL,
    `TherapistName` VARCHAR(100) NOT NULL,
    `Date` DATE NOT NULL,
    `InTime` TIME NOT NULL,
    `OutTime` TIME NOT NULL,
    `Price` DECIMAL(10, 2) NOT NULL,
    `Payment` VARCHAR(50) NOT NULL
);