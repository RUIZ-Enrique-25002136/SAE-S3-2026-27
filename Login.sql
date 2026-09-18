create table Users ( login TEXT primary key , pwd TEXT );
INSERT INTO Users VALUES('user','test');

CREATE OR REPLACE function connexion(p_user TEXT,p_pwd TEXT)
RETURNS TEXT AS $$
DECLARE
reponse TEXT := '' ;
BEGIN
Select * into reponse from Users WHERE login =p_user and pwd = p_user;
IF reponse = '' THEN
        RETURN 0;
end if;
RETURN 1;
end;
$$  language plpgsql;

CREATE OR REPLACE function register(p_user TEXT,p_pwd TEXT)
    RETURNS TEXT AS $$
DECLARE
reponse TEXT := '' ;
BEGIN
Select * into reponse from Users WHERE login =p_user;
IF reponse = '' THEN
        insert into Users VALUES (p_user,p_pwd);
RETURN 1;
end if;
RETURN 0;
end;
$$ language plpgsql