drop database if exists retrogamestoredb;
create database retrogamestoredb;
use retrogamestoredb;

-- create the retrogames table
create table RetroGames
(
gameID int not null,
name varchar(60) not null,
platform varchar(50) not null,
releaseDate DATE not null,
price decimal(6, 2) not null,
primary key (gameID)
);

-- insert these records/values into the RetroGames table
-- inserting 30 records
insert into RetroGames values(101, 'Super Mario Bros. + Duck Hunt', 'Nintendo Entertainment System', '1988-11-01', 14.99);
insert into RetroGames values(102, 'SimCity', 'Super Nintendo Entertainment System', '1991-08-23', 29.99);
insert into RetroGames values(103, 'Sonic the Hedgehog', 'Sega Genesis', '1991-06-23', 34.99);
insert into RetroGames values(104, 'Doom', 'PlayStation', '1995-11-16', 44.99);
insert into RetroGames values(105, 'GoldenEye 007', 'Nintendo 64', '1997-08-25', 29.99);
insert into RetroGames values(106, 'Disney\'s Aladdin', 'Sega Genesis', '1993-11-11', 24.99);
insert into RetroGames values(107, 'Mortal Kombat', 'Super Nintendo Entertainment System', '1993-09-13', 34.99);
insert into RetroGames values(108, 'Tetris', 'Game Boy', '1989-07-31', 14.99);
insert into RetroGames values(109, 'NHL \'94', 'Sega Genesis', '1993-10-01', 19.99);
insert into RetroGames values(110, 'NBA Jam', 'Super Nintendo Entertainment System', '1994-03-04', 24.99);
insert into RetroGames values(111, 'Mario Kart 64', 'Nintendo 64', '1997-02-10', 49.99);
insert into RetroGames values(112, 'Metal Gear Solid', 'PlayStation', '1998-10-20', 39.99);
insert into RetroGames values(113, 'Crash Team Racing', 'PlayStation', '1999-10-19', 24.99);
insert into RetroGames values(114, 'Spyro: Year of the Dragon', 'PlayStation', '2000-10-10', 29.99);
insert into RetroGames values(115, 'The Legend of Zelda: Majora\'s Mask', 'Nintendo 64', '2000-11-17', 59.99);
insert into RetroGames values(116, 'Tony Hawk\'s Pro Skater 4', 'GameCube', '2002-10-23', 19.99);
insert into RetroGames values(117, 'Silent Hill 2', 'PlayStation 2', '2001-09-25', 139.99);
insert into RetroGames values(118, 'Spider-Man', 'Dreamcast', '2001-05-01', 119.99);
insert into RetroGames values(119, 'The Simpson\'s: Hit and Run', 'GameCube', '2003-09-16', 114.99);
insert into RetroGames values(120, 'Half-Life 2', 'Xbox', '2004-11-15', 14.99);
insert into RetroGames values(121, 'Panzer Dragoon Saga', 'Sega Saturn', '1998-05-05', 1599.99);
insert into RetroGames values(122, 'Conker\'s Bad Fur Day', 'Nintendo 64', '2001-03-05', 149.99);
insert into RetroGames values(123, 'PaRappa the Rapper', 'PlayStation', '1997-11-18', 159.99);
insert into RetroGames values(124, 'Pac-Man Collection', 'Game Boy Advance', '2001-07-13', 14.99);
insert into RetroGames values(125, 'Star Wars: Knights of the Old Republic', 'Xbox', '2003-07-16', 9.99);
insert into RetroGames values(126, 'Wario Land 4', 'Game Boy Advance', '2001-11-19', 59.99);
insert into RetroGames values(127, 'Viewtiful Joe', 'PlayStation 2', '2004-08-24', 34.99);
insert into RetroGames values(128, 'Alien Trilogy', 'Sega Saturn', '1996-08-08', 89.99);
insert into RetroGames values(129, 'Mario Golf: Toadstool Tour', 'GameCube', '2003-07-28', 44.99);
insert into RetroGames values(130, 'Donkey Kong 64', 'Nintendo 64', '1999-11-22', 39.99);