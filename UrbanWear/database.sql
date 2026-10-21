CREATE DATABASE IF NOT EXISTS urbanwear CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE urbanwear;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(30) NOT NULL UNIQUE,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  sale_price DECIMAL(10,2) NULL DEFAULT NULL,
  category ENUM('Oblačila','Dodatki','Obutev') NOT NULL DEFAULT 'Oblačila',
  image VARCHAR(500) NOT NULL,
  stock INT UNSIGNED NOT NULL DEFAULT 0,
  featured TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_products_name (name),
  INDEX idx_products_category (category)
) ENGINE=InnoDB;

INSERT INTO products (name,description,price,sale_price,category,image,stock,featured) VALUES
('Essential bela majica','Mehka bombažna majica preprostega kroja. Odlično se poda k vsakodnevnim kombinacijam.',19.90,NULL,'Oblačila','https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=85',25,1),
('Oversized črn pulover','Udoben oversized pulover za sproščen videz in hladnejše dni.',49.90,39.90,'Oblačila','https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=900&q=85',14,1),
('Urban cargo hlače','Praktične cargo hlače s sproščenim krojem in uporabnimi žepi.',54.90,NULL,'Oblačila','https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=900&q=85',9,1),
('Classic športne superge','Minimalistične superge za vsak dan in preproste kombinacije.',79.90,69.90,'Obutev','https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=85',12,1),
('Urban bomber jakna','Lahka bomber jakna s čistimi linijami za prehodne letne čase.',89.90,NULL,'Oblačila','https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=900&q=85',7,0),
('Daily trenirka','Udoben komplet za sproščene dni, potovanja ali trening.',59.90,49.90,'Oblačila','https://images.unsplash.com/photo-1551488831-00ddcb6c6bd3?auto=format&fit=crop&w=900&q=85',16,0),
('Minimal kapa','Preprosta kapa, ki dopolni vsak urbani videz.',16.90,NULL,'Dodatki','https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=900&q=85',30,0),
('Everyday hoodie','Mehak hoodie s kapuco za udoben in sproščen slog.',44.90,NULL,'Oblačila','https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=900&q=85',20,0),
('Bela street majica','Nevtralna majica za kombiniranje s kavbojkami ali trenirko.',22.90,NULL,'Oblačila','https://images.unsplash.com/photo-1503341504253-dff4815485f1?auto=format&fit=crop&w=900&q=85',18,0),
('Rdeče športne superge','Izrazite športne superge za opazen vsakodnevni videz.',74.90,64.90,'Obutev','https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=85',8,0),
('Črna baseball kapa','Klasična baseball kapa s preprostim dizajnom.',18.90,NULL,'Dodatki','https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=900&q=85',22,0),
('Denim jakna','Brezčasen denim kos, ki ga lahko nosiš več sezon.',69.90,NULL,'Oblačila','https://images.unsplash.com/photo-1543076447-215ad9ba6923?auto=format&fit=crop&w=900&q=85',6,0);
