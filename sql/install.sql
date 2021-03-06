CREATE TABLE PREFIX_menu_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, is_single_link TINYINT(1) NOT NULL, link_id TINYINT(1) NULL, position INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = ENGINE_TYPE;
CREATE TABLE PREFIX_menu_item_block (item_id INT NOT NULL, block_id INT NOT NULL, position INT NOT NULL,PRIMARY KEY(item_id, block_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = ENGINE_TYPE;
CREATE TABLE PREFIX_menu_block (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL,parent_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = ENGINE_TYPE;
CREATE TABLE PREFIX_menu_block_link (link_id  INT NOT NULL, block_id INT NOT NULL, position INT NOT NULL,PRIMARY KEY(link_id , block_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = ENGINE_TYPE;
CREATE TABLE PREFIX_menu_link (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = ENGINE_TYPE;
ALTER TABLE PREFIX_menu_item_block ADD CONSTRAINT FK_723DEA93AD44E205 FOREIGN KEY (item_id) REFERENCES PREFIX_menu_item (id) ON DELETE CASCADE;
ALTER TABLE PREFIX_menu_item_block ADD CONSTRAINT FK_723DEA934A5F2BF5 FOREIGN KEY (block_id) REFERENCES PREFIX_menu_block (id) ON DELETE CASCADE;
ALTER TABLE PREFIX_menu_block ADD CONSTRAINT FK_861AC17D727ACA70 FOREIGN KEY (parent_id) REFERENCES PREFIX_menu_block (id);
ALTER TABLE PREFIX_menu_block_link ADD CONSTRAINT FK_34203B194A5F2BF5 FOREIGN KEY (block_id) REFERENCES PREFIX_menu_block (id) ON DELETE CASCADE;
ALTER TABLE PREFIX_menu_block_link ADD CONSTRAINT FK_34203B19128FB22A FOREIGN KEY (link_id) REFERENCES PREFIX_menu_link (id) ON DELETE CASCADE;
    