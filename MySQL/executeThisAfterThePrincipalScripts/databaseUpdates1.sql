ALTER TABLE users ADD UNIQUE (Email);




-- nueva columna para saber si el chat tiene sus datos encriptados o no
ALTER TABLE userchats ADD isDataEncrypted TINYINT(1) NOT NULL DEFAULT 0;




SET SQL_SAFE_UPDATES = 0; -- esto es para desactivar el "Safe Update Mode"
                        --  que evita la ejecución accidental de sentencias
                        -- UPDATE o DELETE que podrían afectar a una gran cantidad de filas.




-- nueva columna para guardar los mensajes encriptados
ALTER TABLE Messages ADD COLUMN DataEncrypted VARBINARY(250);