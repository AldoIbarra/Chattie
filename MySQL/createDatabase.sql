CREATE DATABASE chattie;

USE chattie;

CREATE TABLE Levels( -- Los datos de esta tabla seran preestablecidos desde la base de datos
	Id int PRIMARY KEY AUTO_INCREMENT,
    LevelName	varchar(20),
    LevelPoints	INT UNIQUE
    -- Posibles niveles
    -- Nivel Cero
    -- Bronce 	 Otorgado al completar la primera tarea?
    -- Plata 	  300
    -- Oro 		  600
    -- Obsidiana  900
    -- Diamante   1200
);

-- El usuario al registrarse se le pediria su nombre completo, correo, contraseña y carrera
CREATE TABLE Users(
	Id int PRIMARY KEY AUTO_INCREMENT,
    UserName varchar(50) NOT NULL,
    Email varchar(50) NOT NULL,
    Password varchar(20) NOT NULL,
    DateBirth date, 
    UserLevel int NOT NULL, -- nivel conseguido
    pointsEarned int, -- VARIABLE AGREGADA
    FOREIGN KEY (UserLevel) REFERENCES Levels(Id)
);

-- ----------------------------------- TABLAS DE CHATS ---------------------------------------

CREATE TABLE Chats ( 
    Id int PRIMARY KEY AUTO_INCREMENT,
    Name varchar(50), #solo si es un grupo,
    AdminId int, -- el ID de creador/admin del grupo, solo si es un grupo si no seria NULL
    CreationDate datetime,
    UpdatedDate datetime,
    IsGroup int, #bandera de grupo 
    FOREIGN KEY (AdminId) REFERENCES Users(Id)
);

CREATE TABLE UserChats(
	Id int PRIMARY KEY AUTO_INCREMENT,
    ChatId int,
    UserId int,
    FOREIGN KEY (UserId) REFERENCES Users(Id),
    FOREIGN KEY (ChatId) REFERENCES Chats(Id)
);

CREATE TABLE Messages(
	Id int PRIMARY KEY AUTO_INCREMENT,
    ChatId int,
    UserId int,
    Message TINYTEXT, -- tinytext max 255 
    CreationDate datetime,
    Status int, #0 = Enviado, 1 = Recibido, 2 = Leído
    FOREIGN KEY (UserId) REFERENCES Users(Id),
    FOREIGN KEY (ChatId) REFERENCES Chats(Id)
);

CREATE TABLE files(
	Id int PRIMARY KEY AUTO_INCREMENT,
    filename varchar(200),
    filesize  int not null,
    filetype varchar(100) not null, 
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ----------------------------------- TABLAS DE TAREAS ---------------------------------------
CREATE TABLE Task(
	Id int PRIMARY KEY AUTO_INCREMENT,
    Title varchar(30), 
    DueDate date, -- fecha de vencimiento
    Points int, 
    Descripcion TINYTEXT,
    GroupId INT, -- Grupo al que pertenece la tarea y con un join se sabe quien es el admin
    FOREIGN KEY (GroupId) REFERENCES Chats(Id)
);


INSERT INTO Levels(LevelName, LevelPoints) VALUES 	('Bronce', 1), 
													('Plata', 300), 
													('Oro', 600),
													('Obsidiana', 900), 
                                                    ('Diamante', 1200);

INSERT INTO Users(UserName, Email, Password, UserLevel, pointsEarned) VALUES ('Pedro', 'pedro.martinezg@gmail.com', '123', 5, 1200),
																			('Miguel', 'miguel.gonzalez@gmail.com', '123', 1, 200),
																			('Jose', 'jose.cavazos@gmail.com', '123', 2, 350),
																			('Victor', 'victor.garcia@gmail.com', '123', 3, 650);

-- CHAT INDIVIDUAL
INSERT INTO Chats(Name, AdminId, CreationDate ,UpdatedDate ,IsGroup)
VALUES(NULL, NULL, curdate(), curdate(), 0); 

INSERT INTO UserChats(ChatId, UserId) VALUES(1, 1), (1, 2);

INSERT INTO Messages(ChatId, UserId, Message, CreationDate, Status)
VALUES	(1, 2, 'Hola', now(), 1), 
		(1, 2, 'Hola', now(), 1),
        (1, 1, '¿Cómo estás?', now(), 1),
        (1, 2, 'Bien, ¿y tú?', now(), 1),
        (1, 1, 'Bien, gracias', now(), 1),
        (1, 2, 'Me alegro', now(), 1);


-- CHAT GRUPAL 1
INSERT INTO Chats(Name, AdminId, CreationDate ,UpdatedDate ,IsGroup)
VALUES('POI', 1, curdate(), curdate(), 1); 

INSERT INTO UserChats(ChatId, UserId) VALUES(2, 1), (2, 2), (2, 3), (2, 4);

INSERT INTO Messages(ChatId, UserId, Message, CreationDate, Status)
VALUES 	(2, 1, 'Hola', now(), 1),
		(2, 2, '¿Qué tal?', now(), 1),
        (2, 3, 'Oigan ¿hay tarea?', now(), 1),
        (2, 2, 'Sí, se vence hoy', now(), 1),
        (2, 3, 'Gracias', now(), 1),
        (2, 4, '¿Qué hay de tarea?', now(), 1),
        (2, 1, 'Solo hacer un resumen', now(), 1);
        
        
-- CHAT GRUPAL 2
INSERT INTO Chats(Name, AdminId, CreationDate ,UpdatedDate ,IsGroup)
VALUES('ADMIN', 2, curdate(), curdate(), 1); 

INSERT INTO UserChats(ChatId, UserId) VALUES(3, 1), (3, 2), (3, 4);

INSERT INTO Messages(ChatId, UserId, Message, CreationDate, Status)
VALUES 	(3, 1, 'Tenemos una tarea para mañana', now(), 1),
		(3, 2, 'Yo ya termine mi parte', now(), 1),
        (3, 2, 'Ahorita la mando', now(), 1),
        (3, 4, 'Yo ando terminando la mia', now(), 1),
        (3, 1, 'Perfecto', now(), 1);
        

-- CHAT GRUPAL 3
INSERT INTO Chats(Name, AdminId, CreationDate ,UpdatedDate ,IsGroup)
VALUES('GUIONISMO', 2, curdate(), curdate(), 1); 

 

INSERT INTO UserChats(ChatId, UserId) VALUES(4, 3), (4, 2);

INSERT INTO Messages(ChatId, UserId, Message, CreationDate, Status)
VALUES 	(4, 3, 'Hola', now(), 1),
		(4, 2, 'Buenas', now(), 1);


INSERT INTO Task(Title, DueDate, Points, Descripcion, GroupId) VALUES 
('Resumen', '2024-12-03', 10, 'Hacer resumen del libro', 2);

INSERT INTO Task(Title, DueDate, Points, Descripcion, GroupId) VALUES 
('Leer', '2024-03-12', 10, 'Del libro', 2);

INSERT INTO Task(Title, DueDate, Points, Descripcion, GroupId) VALUES 
('Trabajo previo al guion', '2024-03-12', 12, 'Hacer asdfghjklñ', 4);

/*
Consultas hechas:
-Traer los mensajes de un chat individual
-Traer los mensajes de un chat grupal
-Mostrar los chats de un usuario distinguiendo entre los que son individuales y grupales
-Traer la puntuación de un usuario
-Traer la información de un usuario y ver si es administrador
-Mostrar los miembros de un chat (Id y rol[miembro o admin])
*/

-- Traer los mensajes de un chat individual
SELECT u.UserName AS 'UserId', m.Message, m.CreationDate
FROM Messages m
INNER JOIN Chats c ON m.ChatId = c.Id
INNER JOIN Users u ON m.UserId = u.Id
WHERE c.Id = 1 AND c.IsGroup = 0
ORDER BY m.CreationDate;

-- Traer los mensajes de un chat grupal
SELECT u.UserName AS 'Emisor', m.Message AS 'Mensaje' 
FROM Messages m
INNER JOIN Chats c ON m.ChatId = c.Id
INNER JOIN Users u ON m.UserId = u.Id
WHERE c.Id = 3 AND c.IsGroup = 1
ORDER BY m.CreationDate;

-- Mostrar los chats de un usuario distinguiendo entre los que son individuales y grupales
SELECT c.Id,
    CASE
        WHEN c.IsGroup = 1 THEN c.Name  -- Si es un grupo, muestra el nombre del grupo
        ELSE (SELECT u2.UserName        -- Si no es un grupo, muestra el nombre del otro usuario
              FROM Users u2 
              INNER JOIN UserChats uc2 ON u2.Id = uc2.UserId 
              WHERE uc2.ChatId = c.Id AND uc2.UserId != 2)
    END AS 'Name'
FROM Chats c
INNER JOIN UserChats uc ON uc.ChatId = c.Id
WHERE uc.UserId = 1;

-- Traer la puntuación de un usuario
SELECT L.LevelName AS 'Nivel', u.pointsEarned AS 'Puntuacion'
FROM Users u
INNER JOIN Levels L ON u.UserLevel = L.Id
WHERE u.Id = 3;

-- Traer la información de un usuario y ver si es administrador
SELECT u.Id AS 'UserId', u.UserName, u.Email, u.UserLevel,
    CASE -- si hay coincidencias en la union entonces si es administrador
        WHEN c.Id IS NOT NULL THEN 'Sí' 
        ELSE 'No' 
    END AS 'EsAdministrador',
    c.Name AS 'GrupoNombre'
FROM Users u
LEFT JOIN Chats c ON c.AdminId = u.Id
WHERE u.Id = 2;

-- Mostrar los miembros de un chat (Id y rol[miembro o admin])
SELECT u.Id AS 'UserId', u.UserName, u.Email,
    CASE
        WHEN c.AdminId = u.Id THEN 'Administrador'
        ELSE 'Miembro'
    END AS Rol
FROM Users u
INNER JOIN UserChats uc ON u.Id = uc.UserId
INNER JOIN Chats c ON uc.ChatId = c.Id
WHERE c.Id = 2;

-- Mostrar las tareas de un grupo
SELECT Title, DueDate, Points, Descripcion
FROM Task
INNER JOIN Chats c ON c.Id = GroupId
WHERE c.Id = 3;

-- Mostrar las tareas de un usuario
SELECT Title, DueDate, Points, Descripcion
FROM Task
INNER JOIN Chats c ON c.Id = GroupId
LEFT JOIN UserChats uc ON c.Id = uc.ChatId
WHERE uc.UserId = 2;

/*
select * from Levels;
select * from Users;
select * from Chats;
select * from Messages;
select * from task;
select * from userchats; 
*/