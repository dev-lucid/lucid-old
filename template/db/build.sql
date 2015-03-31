create table organizations (
	org_id INTEGER PRIMARY KEY AUTOINCREMENT,
	name varchar(50)
);

insert into organizations (name) values ('admin');
insert into organizations (name) values ('guest');

create table users (
	user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    org_id int,
	email varchar(255),
	password varchar(255),
	first_name varchar(255),
	last_name varchar(255),
    FOREIGN KEY(org_id) REFERENCES organizations(org_id)
);

-- password is: password1
insert into users (org_id,email,password,first_name,last_name) values (1,'admin@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','Administrator','');
insert into users (org_id,email,password,first_name,last_name) values (2,'user1@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','1');
insert into users (org_id,email,password,first_name,last_name) values (2,'user2@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','2');
insert into users (org_id,email,password,first_name,last_name) values (2,'user3@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','3');
insert into users (org_id,email,password,first_name,last_name) values (2,'user4@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','4');
insert into users (org_id,email,password,first_name,last_name) values (2,'user5@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','5');
insert into users (org_id,email,password,first_name,last_name) values (2,'user6@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','6');
insert into users (org_id,email,password,first_name,last_name) values (2,'user7@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','7');
insert into users (org_id,email,password,first_name,last_name) values (2,'user8@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','8');
insert into users (org_id,email,password,first_name,last_name) values (2,'user9@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','9');
insert into users (org_id,email,password,first_name,last_name) values (2,'user10@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','10');
insert into users (org_id,email,password,first_name,last_name) values (2,'user11@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','11');
insert into users (org_id,email,password,first_name,last_name) values (2,'user12@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','12');
insert into users (org_id,email,password,first_name,last_name) values (2,'user13@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','13');
insert into users (org_id,email,password,first_name,last_name) values (2,'user14@localhost','$2y$10$wHDMMT8IfEa9lEMzd2kQAuZvA2BwKNKPtwSE7ZNpQOBlO9Zlz0ORi','User','14');
