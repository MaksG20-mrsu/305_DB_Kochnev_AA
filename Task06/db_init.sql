DROP TABLE IF EXISTS work_records;
DROP TABLE IF EXISTS appointment_services;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS schedules;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS employees;

CREATE TABLE employees (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    position TEXT NOT NULL,
    hire_date DATE NOT NULL,
    dismissal_date DATE,
    salary_percentage REAL NOT NULL CHECK(salary_percentage BETWEEN 0 AND 100),
    status TEXT NOT NULL DEFAULT 'active' CHECK(status IN ('active', 'fired')),
    phone TEXT,
    email TEXT
);

CREATE TABLE services (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL UNIQUE,
    duration_minutes INTEGER NOT NULL CHECK(duration_minutes > 0),
    price REAL NOT NULL CHECK(price >= 0)
);

CREATE TABLE schedules (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER NOT NULL,
    day_of_week INTEGER NOT NULL CHECK(day_of_week BETWEEN 1 AND 7),
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
);

CREATE TABLE appointments (
    id SERIAL PRIMARY KEY,
    employee_id INTEGER NOT NULL,
    client_name TEXT NOT NULL,
    client_phone TEXT,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status TEXT NOT NULL DEFAULT 'scheduled' CHECK(status IN ('scheduled', 'completed', 'cancelled')),
    total_price REAL NOT NULL DEFAULT 0 CHECK(total_price >= 0),
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE NO ACTION
);

CREATE TABLE appointment_services (
    appointment_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    PRIMARY KEY (appointment_id, service_id),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE NO ACTION
);

CREATE TABLE work_records (
    id INTEGER PRIMARY KEY,
    appointment_id INTEGER NOT NULL,
    employee_id INTEGER NOT NULL,
    service_id INTEGER NOT NULL,
    work_date TEXT NOT NULL CHECK(work_date = strftime('%Y-%m-%d', work_date)),
    work_time TEXT NOT NULL CHECK(work_time = strftime('%H:%M', work_time)),
    revenue REAL NOT NULL CHECK(revenue >= 0),
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE NO ACTION,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE NO ACTION,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE NO ACTION
);

CREATE INDEX idx_employees_status ON employees(status);
CREATE INDEX idx_employees_name ON employees(name);
CREATE INDEX idx_employees_hire_dismiss ON employees(hire_date, dismissal_date);

CREATE INDEX idx_services_name ON services(name);

CREATE INDEX idx_schedules_employee_id ON schedules(employee_id);

CREATE INDEX idx_appointments_employee_id ON appointments(employee_id);
CREATE INDEX idx_appointments_date ON appointments(appointment_date);
CREATE INDEX idx_appointments_status ON appointments(status);

CREATE INDEX idx_work_records_employee_id ON work_records(employee_id);
CREATE INDEX idx_work_records_date ON work_records(work_date);


INSERT INTO employees (id, name, position, hire_date, dismissal_date, salary_percentage, status, phone, email) VALUES
(1, 'Соколов Иван Петрович', 'Мастер', '2025-01-15', NULL, 26.0, 'active', '+7-911-111-22-33', 'sokolov@sto-auto.ru'),
(2, 'Козлова Елена Викторовна', 'Мастер', '2025-03-20', NULL, 29.5, 'active', '+7-911-222-33-44', 'kozlova@sto-auto.ru'),
(3, 'Новиков Андрей Дмитриевич', 'Мастер', '2024-11-10', '2025-08-15', 27.0, 'fired', '+7-911-333-44-55', 'novikov@sto-auto.ru'),
(4, 'Лебедева Оксана Сергеевна', 'Мастер', '2025-05-05', NULL, 28.0, 'active', '+7-911-444-55-66', 'lebedeva@sto-auto.ru'),
(5, 'Попов Роман Александрович', 'Мастер', '2024-08-25', NULL, 25.0, 'active', '+7-911-555-66-77', 'popov@sto-auto.ru');

INSERT INTO services (id, name, duration_minutes, price) VALUES
(1, 'Техническое обслуживание (ТО-1)', 45, 2200.00),
(2, 'Замена тормозных дисков', 75, 4500.00),
(3, 'Компьютерная диагностика', 30, 1800.00),
(4, 'Замена воздушного и салонного фильтров', 35, 1900.00),
(5, 'Сезонная замена шин', 25, 1300.00),
(6, '3D-развал-схождение', 80, 4200.00),
(7, 'Замена стоек амортизаторов', 110, 7500.00),
(8, 'Установка гелевого аккумулятора', 30, 3200.00),
(9, 'Промывка радиатора и антифриза', 55, 2600.00),
(10, 'Замена комплекта свечей накаливания', 40, 2100.00);

INSERT INTO schedules (employee_id, day_of_week, start_time, end_time) VALUES
(1, 1, '09:00', '18:00'),
(1, 2, '09:00', '18:00'),
(1, 3, '09:00', '18:00'),
(1, 4, '09:00', '18:00'),
(1, 5, '09:00', '18:00'),
(2, 2, '10:00', '19:00'),
(2, 3, '10:00', '19:00'),
(2, 4, '10:00', '19:00'),
(2, 5, '10:00', '19:00'),
(2, 6, '10:00', '17:00'), 
(4, 1, '07:30', '16:30'),
(4, 2, '07:30', '16:30'),
(4, 3, '07:30', '16:30'),
(4, 4, '07:30', '16:30'),
(4, 5, '07:30', '16:30'),
(4, 6, '08:00', '14:00'),
(5, 1, '12:00', '21:00'),
(5, 2, '12:00', '21:00'),
(5, 3, '12:00', '21:00'),
(5, 4, '12:00', '21:00'),
(5, 5, '12:00', '21:00');

INSERT INTO appointments (id, employee_id, client_name, client_phone, appointment_date, appointment_time, status, total_price) VALUES
(1, 1, 'Макаров Даниил Алексеевич', '+7-952-101-20-30', '2025-12-01', '09:30', 'completed', 2200.00),
(2, 1, 'Волкова Софья Игоревна', '+7-952-202-30-40', '2025-12-01', '14:00', 'completed', 6300.00),
(3, 2, 'Зайцев Артём Романович', '+7-952-303-40-50', '2025-12-02', '11:00', 'completed', 3200.00),
(4, 2, 'Орлова Виктория Максимовна', '+7-952-404-50-60', '2025-12-02', '17:00', 'scheduled', 4500.00),
(5, 4, 'Гусев Матвей Евгеньевич', '+7-952-505-60-70', '2025-12-03', '08:00', 'completed', 3900.00),
(6, 5, 'Климова Ангелина Данииловна', '+7-952-606-70-80', '2025-12-03', '13:00', 'scheduled', 1800.00),
(7, 1, 'Фомин Игорь Павлович', '+7-952-707-80-90', '2025-12-04', '10:30', 'scheduled', 7500.00),
(8, 4, 'Белова Елизавета Андреевна', '+7-952-808-90-00', '2025-12-04', '15:30', 'scheduled', 2600.00);

INSERT INTO appointment_services (appointment_id, service_id) VALUES
(1, 1),
(2, 2),
(2, 3),
(3, 8),
(4, 2),
(5, 1),
(5, 4),
(6, 3),
(7, 7),
(8, 9);

INSERT INTO work_records (id, appointment_id, employee_id, service_id, work_date, work_time, revenue) VALUES
(1, 1, 1, 1, '2025-12-01', '09:30', 2200.00),
(2, 2, 1, 2, '2025-12-01', '14:00', 4500.00),
(3, 2, 1, 3, '2025-12-01', '15:30', 1800.00),
(4, 3, 2, 8, '2025-12-02', '11:00', 3200.00),
(5, 4, 2, 2, '2025-12-02', '17:00', 4500.00),
(6, 5, 4, 1, '2025-12-03', '08:00', 2200.00),
(7, 5, 4, 4, '2025-12-03', '09:00', 1900.00),
(8, 6, 5, 3, '2025-12-03', '13:00', 1800.00),
(9, 7, 1, 7, '2025-12-04', '10:30', 7500.00),
(10, 8, 4, 9, '2025-12-04', '15:30', 2600.00);