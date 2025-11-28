INSERT INTO users (name, email, gender, register_date, occupation)
VALUES ('Кочнев Артем Алексеевич', 'artemiukochnev@yandex.ru', 'male', date('now'), 'student');

INSERT INTO users (name, email, gender, register_date, occupation)
VALUES ('Логунов Илья Сергеевич', 'logunovilya@mail.ru', 'male', date('now'), 'student');

INSERT INTO users (name, email, gender, register_date, occupation)
VALUES ('Ивенин Артем Андреевич', 'iveninart@gmail.com', 'male', date('now'), 'student');

INSERT INTO users (name, email, gender, register_date, occupation)
VALUES ('Казейкин Иван Иванович', 'ivankaz@yandex.ru', 'male', date('now'), 'student');

INSERT INTO users (name, email, gender, register_date, occupation)
VALUES ('Колыганов Александр Павлович', 'alexkol@yandex.com', 'male', date('now'), 'student');

INSERT INTO movies (title, year)
VALUES ('Отступники 2006', 2006);

INSERT INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id 
FROM movies m, genres g 
WHERE m.title = 'Отступники 2006' AND g.name = 'Drama';

INSERT INTO movies (title, year)
VALUES ('Царствие небесное 2005', 2005);

INSERT INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id 
FROM movies m, genres g 
WHERE m.title = 'Царствие небесное 2005' AND g.name = 'Action';

INSERT INTO movies (title, year)
VALUES ('Отель Мумбаи: Противостояние 2018', 2018);

INSERT INTO movie_genres (movie_id, genre_id)
SELECT m.id, g.id 
FROM movies m, genres g 
WHERE m.title = 'Отель Мумбаи: Противостояние 2018' AND g.name = 'Action';

INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT 
    (SELECT id FROM users WHERE email = 'artemiukochnev@yandex.ru'),
    (SELECT id FROM movies WHERE title = 'Отступники 2006'),
    4.7,
    strftime('%s', 'now');

INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT 
    (SELECT id FROM users WHERE email = 'artemiukochnev@yandex.ru'),
    (SELECT id FROM movies WHERE title = 'Царствие небесное 2005'),
    4.9,
    strftime('%s', 'now');

INSERT INTO ratings (user_id, movie_id, rating, timestamp)
SELECT 
    (SELECT id FROM users WHERE email = 'artemiukochnev@yandex.ru'),
    (SELECT id FROM movies WHERE title = 'Отель Мумбаи: Противостояние 2018'),
    4.5,
    strftime('%s', 'now');