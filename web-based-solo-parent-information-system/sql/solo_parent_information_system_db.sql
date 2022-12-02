-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2022 at 06:06 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `solo_parent_information_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `AccountID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `MiddleName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) NOT NULL,
  `Username` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `AccountStatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`AccountID`, `FirstName`, `MiddleName`, `LastName`, `Username`, `Password`, `AccountStatus`) VALUES
(1, 'Administrator', '', 'Account', 'admin123', '$2y$10$b6t9/WHC/0YeTIbHwWBi3OP3dM2ypjQMzkOzYwp6lrEKTXsPyXmH2', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `BarangayID` int(11) NOT NULL,
  `Barangay` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`BarangayID`, `Barangay`) VALUES
(1, 'BACLARAN'),
(2, 'BANAY BANAY'),
(3, 'BANLIC'),
(4, 'BARANGAY DOS'),
(5, 'BARANGAY TRES'),
(6, 'BARANGAY UNO'),
(7, 'BIGAA'),
(8, 'BUTONG'),
(9, 'CASILE'),
(10, 'DIEZMO'),
(11, 'GULOD'),
(12, 'MAMATID'),
(13, 'MARINIG'),
(14, 'NIUGAN'),
(15, 'PITTLAND'),
(16, 'PULO'),
(17, 'SALA'),
(18, 'SAN ISIDRO');

-- --------------------------------------------------------

--
-- Table structure for table `children`
--

CREATE TABLE `children` (
  `ChildID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `MiddleName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) NOT NULL,
  `Birthdate` date NOT NULL,
  `Sex` varchar(10) NOT NULL,
  `SoloParentID` int(11) NOT NULL,
  `RelationshipToSoloParentCategoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `informativematerials`
--

CREATE TABLE `informativematerials` (
  `InformativeMaterialID` int(11) NOT NULL,
  `InformativeMaterialCategory` varchar(50) NOT NULL,
  `InformativeMaterial` varchar(300) NOT NULL,
  `DateAdded` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `naturesofsoloparent`
--

CREATE TABLE `naturesofsoloparent` (
  `NatureOfSoloParentID` int(11) NOT NULL,
  `NatureOfSoloParent` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `naturesofsoloparent`
--

INSERT INTO `naturesofsoloparent` (`NatureOfSoloParentID`, `NatureOfSoloParent`) VALUES
(1, 'VICTIM OF CRIME'),
(2, 'ABANDONED'),
(3, 'WIDOWED'),
(4, 'CONVICTED SPOUSE'),
(5, 'INCAPACITATED SPOUSE'),
(6, 'ANNULED'),
(7, 'DIVORCED'),
(8, 'IMMEDIATE FAMILY'),
(9, 'SEPARATED');

-- --------------------------------------------------------

--
-- Table structure for table `pwdstatus`
--

CREATE TABLE `pwdstatus` (
  `PWDStatusID` int(11) NOT NULL,
  `PWDStatus` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pwdstatus`
--

INSERT INTO `pwdstatus` (`PWDStatusID`, `PWDStatus`) VALUES
(1, 'PERSON WITH PSYCHOSOCIAL DISABILITY'),
(2, 'PERSON THAT IS DISABLED DUE TO CHRONIC ILLNESS'),
(3, 'PERSON WITH LEARNING DISABILTY'),
(4, 'PERSON WITH MENTAL DISABILITY'),
(5, 'PERSON WITH VISUAL DISABILITY'),
(6, 'PERSON WITH ORTHOPEDIC DISABILITY'),
(7, 'PERSON WITH COMMUNICATION DISABILITY'),
(8, 'NOT PWD');

-- --------------------------------------------------------

--
-- Table structure for table `relationshiptosoloparentcategories`
--

CREATE TABLE `relationshiptosoloparentcategories` (
  `RelationshipToSoloParentCategoryID` int(11) NOT NULL,
  `RelationshipToSoloParentCategory` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `relationshiptosoloparentcategories`
--

INSERT INTO `relationshiptosoloparentcategories` (`RelationshipToSoloParentCategoryID`, `RelationshipToSoloParentCategory`) VALUES
(1, 'MOTHER'),
(2, 'FATHER'),
(3, 'SIBLING'),
(4, 'STEP-FATHER'),
(5, 'STEP-MOTHER'),
(6, 'STEP-SIBLING'),
(7, 'HALF-SIBLING'),
(8, 'FOSTER MOTHER'),
(9, 'FOSTER FATHER'),
(10, 'UNCLE'),
(11, 'AUNT'),
(12, 'COUSIN'),
(13, 'GRANDFATHER'),
(14, 'GRANDMOTHER');

-- --------------------------------------------------------

--
-- Table structure for table `soloparents`
--

CREATE TABLE `soloparents` (
  `SoloParentID` int(11) NOT NULL,
  `ControlNumber` varchar(100) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `MiddleName` varchar(100) DEFAULT NULL,
  `LastName` varchar(100) NOT NULL,
  `Sex` varchar(50) NOT NULL,
  `Birthdate` date NOT NULL,
  `BarangayID` int(11) NOT NULL,
  `StreetAddress` varchar(100) NOT NULL,
  `Occupation` varchar(50) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `NatureOfSoloParentID` int(11) NOT NULL,
  `PWDStatusID` int(11) NOT NULL,
  `4PsStatus` varchar(50) NOT NULL,
  `MembershipStatus` varchar(50) NOT NULL,
  `DateJoined` date NOT NULL,
  `DateLastRenewed` date DEFAULT NULL,
  `Remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`AccountID`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`BarangayID`);

--
-- Indexes for table `children`
--
ALTER TABLE `children`
  ADD PRIMARY KEY (`ChildID`),
  ADD KEY `SoloParentID` (`SoloParentID`),
  ADD KEY `RelationshipToSoloParentCategoryID` (`RelationshipToSoloParentCategoryID`);

--
-- Indexes for table `informativematerials`
--
ALTER TABLE `informativematerials`
  ADD PRIMARY KEY (`InformativeMaterialID`);

--
-- Indexes for table `naturesofsoloparent`
--
ALTER TABLE `naturesofsoloparent`
  ADD PRIMARY KEY (`NatureOfSoloParentID`);

--
-- Indexes for table `pwdstatus`
--
ALTER TABLE `pwdstatus`
  ADD PRIMARY KEY (`PWDStatusID`);

--
-- Indexes for table `relationshiptosoloparentcategories`
--
ALTER TABLE `relationshiptosoloparentcategories`
  ADD PRIMARY KEY (`RelationshipToSoloParentCategoryID`);

--
-- Indexes for table `soloparents`
--
ALTER TABLE `soloparents`
  ADD PRIMARY KEY (`SoloParentID`),
  ADD KEY `BarangayID` (`BarangayID`),
  ADD KEY `NatureOfSoloParentID` (`NatureOfSoloParentID`),
  ADD KEY `PWDStatusID` (`PWDStatusID`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `AccountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `BarangayID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `children`
--
ALTER TABLE `children`
  MODIFY `ChildID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `informativematerials`
--
ALTER TABLE `informativematerials`
  MODIFY `InformativeMaterialID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `naturesofsoloparent`
--
ALTER TABLE `naturesofsoloparent`
  MODIFY `NatureOfSoloParentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pwdstatus`
--
ALTER TABLE `pwdstatus`
  MODIFY `PWDStatusID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `relationshiptosoloparentcategories`
--
ALTER TABLE `relationshiptosoloparentcategories`
  MODIFY `RelationshipToSoloParentCategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `soloparents`
--
ALTER TABLE `soloparents`
  MODIFY `SoloParentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `children`
--
ALTER TABLE `children`
  ADD CONSTRAINT `children_ibfk_1` FOREIGN KEY (`SoloParentID`) REFERENCES `soloparents` (`SoloParentID`),
  ADD CONSTRAINT `children_ibfk_2` FOREIGN KEY (`RelationshipToSoloParentCategoryID`) REFERENCES `relationshiptosoloparentcategories` (`RelationshipToSoloParentCategoryID`);

--
-- Constraints for table `soloparents`
--
ALTER TABLE `soloparents`
  ADD CONSTRAINT `soloparents_ibfk_1` FOREIGN KEY (`PWDStatusID`) REFERENCES `pwdstatus` (`PWDStatusID`),
  ADD CONSTRAINT `soloparents_ibfk_2` FOREIGN KEY (`NatureOfSoloParentID`) REFERENCES `naturesofsoloparent` (`NatureOfSoloParentID`),
  ADD CONSTRAINT `soloparents_ibfk_3` FOREIGN KEY (`BarangayID`) REFERENCES `barangays` (`BarangayID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
