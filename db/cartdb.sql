--
-- Create the database if it doesn't exist
--
CREATE DATABASE IF NOT EXISTS `cartdb`;

--
-- Use the newly created or existing database
--
USE `cartdb`;

--
-- Database: `cartdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_products`
--

CREATE TABLE `cart_products` (
  `productId` int(11) NOT NULL,
  `cartId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `price` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for the `carts` table
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for the `cart_products` table
--
ALTER TABLE `cart_products`
  ADD PRIMARY KEY (`productId`,`cartId`);

--
-- Indexes for the `products` table
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for the `carts` table
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for the `products` table
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Filters for the `cart_products` table
--
ALTER TABLE `cart_products`
  ADD CONSTRAINT `FK_productId` FOREIGN KEY (`productId`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `FK_cartId` FOREIGN KEY (`cartId`) REFERENCES `carts` (`id`);
COMMIT;

--
-- Insert data into the `products` table
--
INSERT INTO `products` (`name`, `price`) VALUES
('Product A', 19.99),
('Product B', 29.99),
('Product C', 15.49),
('Product D', 5.99),
('Product E', 12.49);

--
-- Insert data into the `carts` table
--
INSERT INTO `carts` (`user`) VALUES
('kev'),
('luis'),
('mery'),
('brian');

--
-- Insert data into the `cart_products` table
--
INSERT INTO `cart_products` (`productId`, `cartId`, `quantity`) VALUES
(1, 1, 2),  -- 2 of Product A in user1's cart
(2, 1, 1),  -- 1 of Product B in user1's cart
(3, 2, 3),  -- 3 of Product C in user2's cart
(4, 2, 1),  -- 1 of Product D in user2's cart
(1, 3, 5),  -- 5 of Product A in user3's cart
(5, 4, 1);  -- 1 of Product E in user4's cart
