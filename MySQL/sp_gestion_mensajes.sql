CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_mensajes`(
	IN opc VARCHAR(20),
    IN p_Id int,
    IN p_ChatId int,
    IN p_UserId int,
    IN p_Message TINYTEXT,
    IN p_CreationDate datetime
)
BEGIN

IF opc = 'insertar' THEN -- insertar mensajes
INSERT INTO Messages(
ChatId,
UserId,
Message,
CreationDate,
Status
)VALUES(
p_ChatId,
p_UserId,
p_Message,
now(),
1
);

END IF;

IF opc = 'mostrar' THEN -- mostrar mensajes de un chat

-- Traer los mensajes de un chat
SELECT u.UserName AS 'UserId', m.Message, DATE_FORMAT(m.CreationDate, '%Y-%m-%d %H:%i') AS CreationDate 
FROM Messages m
INNER JOIN Chats c ON m.ChatId = c.Id
INNER JOIN Users u ON m.UserId = u.Id
WHERE c.Id = p_ChatId
ORDER BY m.CreationDate;


END IF;

END