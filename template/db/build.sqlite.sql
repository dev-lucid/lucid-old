create table roles (
    role_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name varchar(50)
);

insert into roles (name) values ('admin');
insert into roles (name) values ('user');

create table organizations (
	org_id INTEGER PRIMARY KEY AUTOINCREMENT,
    role_id integer,
	name varchar(50),
    is_enabled bool default true,
    FOREIGN KEY(role_id) REFERENCES roles(role_id)
);

insert into organizations (role_id,name) values (1,'Admin Org');
insert into organizations (role_id,name) values (2,'British General Products');
insert into organizations (role_id,name) values (2,'Canadian Stuff Manufacturing');
insert into organizations (role_id,name) values (2,'Dutch Processing inc');
insert into organizations (role_id,name) values (2,'East Timor Export Co.');

create table users (
	user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    org_id int,
	email varchar(255),
	password varchar(255),
	first_name varchar(255),
	last_name varchar(255),
    is_enabled bool default true,
    FOREIGN KEY(org_id) REFERENCES organizations(org_id)
);

-- password is: password1
insert into users (org_id,email,password,first_name,last_name) values (1,'admin@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Alice','Atlanta');
insert into users (org_id,email,password,first_name,last_name) values (2,'user1@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Bob','Baltimore');
insert into users (org_id,email,password,first_name,last_name) values (2,'user2@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Charlie','Chicago');
insert into users (org_id,email,password,first_name,last_name) values (2,'user3@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Denise','Detroit');
insert into users (org_id,email,password,first_name,last_name) values (3,'user4@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Edgar','Escondido');
insert into users (org_id,email,password,first_name,last_name) values (3,'user5@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Fran','Fremont');
insert into users (org_id,email,password,first_name,last_name) values (3,'user6@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','George','Glendale');
insert into users (org_id,email,password,first_name,last_name) values (4,'user7@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Hattie','Honolulu');
insert into users (org_id,email,password,first_name,last_name) values (4,'user8@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Isaac','Indianapolis');
insert into users (org_id,email,password,first_name,last_name) values (4,'user9@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Julie','Jacksonville');
insert into users (org_id,email,password,first_name,last_name) values (4,'user10@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Kieran','Knoxville');
insert into users (org_id,email,password,first_name,last_name) values (4,'user11@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Lucy','Louisville');
insert into users (org_id,email,password,first_name,last_name) values (4,'user12@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Michael','Minneapolis');
insert into users (org_id,email,password,first_name,last_name) values (5,'user13@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Nancy','Newark');
insert into users (org_id,email,password,first_name,last_name) values (5,'user14@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Oliver','Omaha');

create table patches (
    patch_id INTEGER PRIMARY KEY AUTOINCREMENT,
    identifier varchar(255),
    applied_on_date TIMESTAMP default CURRENT_TIMESTAMP
);