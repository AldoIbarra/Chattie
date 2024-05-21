ALTER TABLE users ADD UNIQUE (Email);




-- nueva columna para saber si el chat tiene sus datos encriptados o no
ALTER TABLE Chats ADD isDataEncrypted TINYINT(1) NOT NULL DEFAULT 0;




SET SQL_SAFE_UPDATES = 0; -- esto es para desactivar el "Safe Update Mode"
                        --  que evita la ejecución accidental de sentencias
                        -- UPDATE o DELETE que podrían afectar a una gran cantidad de filas.




-- nueva columna para guardar los mensajes encriptados
ALTER TABLE Messages ADD COLUMN DataEncrypted VARBINARY(250);


ALTER TABLE Users
ADD COLUMN Status INT DEFAULT 1 #0 = Desconectado, 1 = En linea, 2 = Ocupado


-- Con esta columna se sabra si el usuario esta en linea
ALTER TABLE Users
ADD COLUMN LastTimeOnline DATETIME DEFAULT CURRENT_TIMESTAMP; 

-- Agregados el 20/05/2024 --

-- Añadir el tipo de mensaje
-- 1 = texto, 2 = ubicación, 3 = archivo
ALTER TABLE messages
ADD Type int;

-- La columna se acaba de crear, si ya tienes datos en tu base de datos y aún no has usado la funcionalidad del mapa
-- ejecuta esta linea para marcar todos los mensajes como mensajes de texto

UPDATE messages
SET Type = 1;

CREATE TABLE Files (
    Id INT PRIMARY KEY AUTO_INCREMENT,
    messageId INT,
    fileName VARCHAR(255),
    filePath VARCHAR(255),
    fileType VARCHAR(50),
    fileSize INT,
    FOREIGN KEY (MessageId) REFERENCES Messages(Id)
);