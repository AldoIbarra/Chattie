ALTER TABLE users ADD UNIQUE (Email);


-- nueva columna para saber si el chat tiene sus datos encriptados o no
ALTER TABLE userchats ADD isDataEncrypted TINYINT(1) NOT NULL DEFAULT 0;