-- Database schema updates for complete e-commerce functionality

-- 1. Add category column to product table
ALTER TABLE product ADD COLUMN category VARCHAR(100) NOT NULL DEFAULT 'General';

-- 2. Add status and timestamps to transaction table
ALTER TABLE transaction ADD COLUMN status VARCHAR(50) DEFAULT 'pending';
ALTER TABLE transaction ADD COLUMN fullname VARCHAR(255);
ALTER TABLE transaction ADD COLUMN email VARCHAR(255);
ALTER TABLE transaction ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

-- 3. Update transaction table structure for proper payment tracking
ALTER TABLE transaction MODIFY COLUMN userID INT(11);
ALTER TABLE transaction MODIFY COLUMN total DECIMAL(10,2) NOT NULL DEFAULT 0;

-- 4. Add indexes for better performance
ALTER TABLE product ADD INDEX idx_category (category);
ALTER TABLE transaction ADD INDEX idx_userID (userID);
ALTER TABLE transaction ADD INDEX idx_orderDate (orderDate);
ALTER TABLE cart ADD PRIMARY KEY (cartID);
ALTER TABLE cart ADD AUTO_INCREMENT = 1;

-- 5. Sample product data with categories
INSERT INTO product (productName, productQty, productPrice, productIMG, category) VALUES
('Electric ATV', 5, 1670.97, 'assets/images/ATV.PNG', 'Electric Vehicles'),
('Electric Go-Kart', 8, 1367.18, 'assets/images/gokart.JPG', 'Go-Kart'),
('Electric Jeep', 3, 13674.55, 'assets/images/jeep.JPG', 'Jeep'),
('Electric Scooter', 15, 450.00, 'assets/images/scooter.PNG', 'Scooter'),
('DVD Player', 12, 89.99, 'assets/images/dvd.PNG', 'DVD-Player'),
('RC Hobby Car', 20, 299.99, 'assets/images/hobby.JPG', 'Hobby & RC'),
('Binoculars Pro', 7, 199.99, 'assets/images/binoculars.PNG', 'Binoculars')
ON DUPLICATE KEY UPDATE category=VALUES(category);