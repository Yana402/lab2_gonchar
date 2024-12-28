CREATE TABLE params (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(64) NOT NULL,
    value FLOAT NOT NULL
);

UPDATE `services` SET `name` = 'Макияж' WHERE `services`.`id` = 3;

INSERT INTO params (name, value) VALUES 
('service_price_coef', 0.7), -- коэффициент влияния цены услуги
('master_stage_coef', 0.4), -- коэффициент влияния стажа мастера
('master_rate_coef', 0.5); -- коэффициент влияния рейтинга мастера

CREATE TABLE master_service (
    master_id INT NOT NULL, 
    service_id INT NOT NULL,
    FOREIGN KEY (master_id) REFERENCES users(id),
    FOREIGN KEY (service_id) REFERENCES services(id)
); 
    
INSERT INTO master_service VALUES 
(1, 1), (2, 3), (3, 5);
