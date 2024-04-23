CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_getChatByUsers`(
    IN userId  INT,
    IN contactId INT
)
BEGIN

SELECT
c.Id AS 'Id',
u.UserName AS 'Name',
0 AS 'IsGroup'
FROM Chats c
INNER JOIN Userchats uc ON c.Id = uc.ChatId
INNER JOIN UserChats uc2 ON c.Id = uc.ChatId
INNER JOIN Users u ON uc2.UserId = u.Id
WHERE uc.UserId = userId AND uc2.UserId = contactId AND uc.ChatId = uc2.ChatId AND c.IsGroup = 0;

END