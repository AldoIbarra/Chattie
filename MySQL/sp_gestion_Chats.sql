CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_Chats`(
	IN opc VARCHAR(20),
    IN p_Id  INT,
    IN p_Name VARCHAR(50), #solo si es un grupo,
    IN p_AdminId INT, -- el ID de creador/admin del grupo, solo si es un grupo si no seria NULL
    IN p_IsGroup INT, #bandera de grupo,
    IN p_IdUserLoged INT
)
BEGIN

IF opc = 'Mostrar' THEN -- mostrar chats del usuario
SELECT c.Id AS 'Id',
    CASE
        WHEN c.IsGroup = 1 THEN c.Name  -- Si es un grupo, muestra el nombre del grupo
        ELSE (SELECT u2.UserName        -- Si no es un grupo, muestra el nombre del otro usuario
              FROM Users u2 
              INNER JOIN UserChats uc2 ON u2.Id = uc2.UserId 
              WHERE uc2.ChatId = c.Id AND uc2.UserId != 2)
    END AS 'Name'
FROM Chats c
INNER JOIN UserChats uc ON uc.ChatId = c.Id
WHERE uc.UserId = p_IdUserLoged;
END IF;

END


--EJECUTA ESTA EN VEZ DE LA ANTERIOR SI USAS XAMP O WAMP:
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_Chats`(
    IN `opc` VARCHAR(20), 
    IN `p_Id` INT, 
    IN `p_Name` VARCHAR(50), 
    IN `p_AdminId` INT, 
    IN `p_IsGroup` INT, 
    IN `p_IdUserLoged` INT) 
    NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER 
    BEGIN 
    IF opc = 'Mostrar' THEN -- mostrar chats del usuario 
    SELECT c.Id AS 'Id', 
        CASE 
            WHEN c.IsGroup = 1 THEN c.Name -- Si es un grupo, muestra el nombre del grupo 
            ELSE (SELECT u2.UserName -- Si no es un grupo, muestra el nombre del otro usuario 
                FROM Users u2 
                INNER JOIN UserChats uc2 ON u2.Id = uc2.UserId 
                WHERE uc2.ChatId = c.Id AND uc2.UserId != 2) 
        END AS 'Name' 
    FROM Chats c 
    INNER JOIN UserChats uc ON uc.ChatId = c.Id 
    WHERE uc.UserId = p_IdUserLoged; 
    END IF;
    
    END