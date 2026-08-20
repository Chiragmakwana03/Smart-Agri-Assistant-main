CREATE DATABASE IF NOT EXISTS agriculture_assistant;
USE agriculture_assistant;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Market Prices Table
CREATE TABLE IF NOT EXISTS market_prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    crop_name VARCHAR(100) NOT NULL,
    price_per_kg DECIMAL(10, 2) NOT NULL,
    location VARCHAR(100) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Government Schemes Table
CREATE TABLE IF NOT EXISTS schemes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    eligibility TEXT NOT NULL,
    benefits TEXT NOT NULL,
    how_to_apply TEXT NOT NULL,
    portal_link VARCHAR(255) DEFAULT '#'
);

-- Sensor Data (Live from ESP32)
DROP TABLE IF EXISTS sensor_data;
CREATE TABLE sensor_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    soil_moisture INT,
    temperature FLOAT,
    pump_status VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hardware Controls (Relay / Pump)
CREATE TABLE IF NOT EXISTS hardware_controls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    device_name VARCHAR(50) NOT NULL,
    status TINYINT(1) DEFAULT 0, -- 0: OFF, 1: ON
    mode VARCHAR(20) DEFAULT 'auto', -- 'auto' or 'manual'
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact Messages Table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Initialize Pump Control
INSERT INTO hardware_controls (device_name, status, mode) VALUES ('Water Pump', 0, 'auto');

-- Sample Data
INSERT INTO market_prices (crop_name, price_per_kg, location) VALUES 
('Wheat', 25.50, 'Mumbai Mandi'),
('Rice', 40.00, 'Delhi Market'),
('Tomato', 15.00, 'Nashik Mandi'),
('Onion', 30.00, 'Pune Market'),
('Potato', 20.00, 'Ahmedabad Mandi');

INSERT INTO schemes (title, eligibility, benefits, how_to_apply, portal_link) VALUES 
('PM-Kisan Samman Nidhi', 'Small and marginal farmers with land up to 2 hectares.', 'Financial assistance of ₹6,000 per year in three installments.', 'Apply through official PM-Kisan portal or CSC.', 'https://pmkisan.gov.in/'),
('Pradhan Mantri Fasal Bima Yojana', 'All farmers including sharecroppers and tenant farmers.', 'Crop insurance against natural calamities, pests, and diseases.', 'Register through bank or insurance company.', 'https://pmfby.gov.in/'),
('Soil Health Card Scheme', 'All farmers in the country.', 'Testing of soil and issuance of cards with nutrient recommendations.', 'Contact local agricultural department.', 'https://soilhealth.dac.gov.in/'),
('Kisan Credit Card (KCC) Scheme', 'All farmers, including tenant farmers, sharecroppers, and poultry/fish farmers.', 'Access to credit at low interest rates (4%) for seeds, fertilizers, and equipment.', 'Visit any public sector bank or apply online through the official KCC portal.', 'https://www.myscheme.gov.in/schemes/kcc'),
('Pradhan Mantri Krishi Sinchai Yojana (PMKSY)', 'All farmers with cultivable land and access to a water source.', 'Subsidy up to 55-80% for installing Drip and Sprinkler irrigation systems.', 'Apply through the State Agriculture Department portal or District Irrigation Office.', 'https://pmksy.gov.in/'),
('Paramparagat Krishi Vikas Yojana (PKVY)', 'Groups of 20 or more farmers willing to transition to organic farming.', 'Financial assistance of ₹50,000 per hectare for organic seeds and certification.', 'Contact the Regional Council or State Agriculture Department for cluster formation.', 'https://www.myscheme.gov.in/schemes/pkvy'),
('National Agriculture Market (eNAM)', 'Farmers, traders, and farmer cooperatives across India.', 'Digital platform to sell produce directly to buyers across India for better prices.', 'Register on the e-NAM portal (enam.gov.in) or visit your nearest APMC Mandi.', 'https://enam.gov.in/');

INSERT INTO sensor_data (soil_moisture, temperature, pump_status) VALUES (45, 28.5, 'OFF');
