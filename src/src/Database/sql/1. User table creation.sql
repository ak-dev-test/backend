CREATE TABLE `User` (
	id BIGINT UNSIGNED auto_increment PRIMARY KEY,
	email varchar(320) NOT NULL,
	password TEXT NOT NULL
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;