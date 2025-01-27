-- создание индексов

CREATE INDEX services_name ON services(name);
CREATE INDEX users_firstname ON users(firstname);
CREATE INDEX users_lastname ON users(lastname);
CREATE INDEX appointments_date_time ON appointments(date_time);

-- удаление индексов

ALTER TABLE services DROP INDEX services_name;
ALTER TABLE users DROP INDEX users_firstname; 
ALTER TABLE users DROP INDEX users_lastname;
ALTER TABLE appointments DROP INDEX appointments_date_time;

-- создание представлений 

CREATE VIEW appointments_clients AS SELECT appointments.id, appointments.master_id, date_time, firstname, lastname, status FROM appointments INNER JOIN users ON user_id = users.id;
CREATE VIEW appointments_masters AS SELECT appointments.id, appointments.user_id AS user_id, date_time, firstname, lastname, speciality, status FROM appointments INNER JOIN masters ON master_id = masters.user_id INNER JOIN users ON master_id = users.id;