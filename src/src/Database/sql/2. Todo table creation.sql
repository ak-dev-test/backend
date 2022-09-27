CREATE TABLE `Todo` (
	id BIGINT UNSIGNED auto_increment PRIMARY KEY,
	task TEXT NOT NULL,
	status ENUM('Todo', 'In progress', 'Done') NOT NULL,
	user_id BIGINT UNSIGNED NOT NULL,
    CONSTRAINT FK_User FOREIGN KEY (user_id) REFERENCES User(id)
)
ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;