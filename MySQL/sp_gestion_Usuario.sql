CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_Usuario`(
	IN opc						VARCHAR(20),
    IN p_Id							INT,
    IN p_UserName					VARCHAR(30),
    IN p_Email						VARCHAR(30),
    IN p_Password					VARCHAR(20),
    IN p_DateBirth					DATE
)
BEGIN

IF opc = 'signIn' THEN 
SELECT 
ID,
UserName,
        Email,		
        Password,	
        DateBirth
FROM Users 
WHERE Email = p_Email AND Password = p_Password;
END IF;

IF opc = 'insertar' THEN -- Insertar

		INSERT INTO Users (
        UserName,
        Email,		
        Password,	
		DateBirth,
        UserLevel,
        pointsEarned
        ) VALUES (			
        p_UserName,
        p_Email,		
        p_Password,	
        p_DateBirth,
        1, -- Nivel 0
        0 -- 0 puntos ganados
        );
	END IF;
    
    IF opc = 'mostrarDatos' THEN -- mostrar
	SELECT 
		UserName,
        Email,		
        Password,	
        DateBirth
        FROM Users 
	WHERE Id = p_Id;
	END IF;

IF opc = 'findUserByUsername' THEN -- findUserByUsername
SELECT 
		Id,
		UserName,
        Email,		
        Password,	
        DateBirth
FROM Users 
WHERE  UserName = p_UserName AND Password = p_Password 
LIMIT 1;
	END IF;

IF opc = 'findUserById' THEN -- findUserById
SELECT 
		Id,
		UserName,
        Email,		
        Password,	
        DateBirth
FROM Users 
WHERE  Id = p_Id 
LIMIT 1;
	END IF;

    
END