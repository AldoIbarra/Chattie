CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_Contacts`(
    IN p_Id  INT
)
BEGIN

SELECT
u.Id AS 'Id',
u.UserName AS 'UserName',
u.Email AS 'Email',
u.DateBirth AS 'DateBirth'
FROM Users u
WHERE u.Id != p_Id;

END

--EJECUTA ESTA EN VEZ DE LA ANTERIOR SI USAS PHPMYADMIN:

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_gestion_Contacts`(
    IN `p_Id` INT) 
    NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER 
    BEGIN 
        SELECT 
        u.Id AS 'Id',
        u.UserName AS 'UserName', 
        u.Email AS 'Email', 
        u.DateBirth AS 'DateBith' 
        FROM Users u 
        WHERE u.Id != p_Id; 
    END