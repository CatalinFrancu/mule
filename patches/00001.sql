create table user (
  id int not null auto_increment,
  identity text,
  nickname varchar(255),
  name varchar(255),
  email varchar(255),
  admin int,
  created int,
  modified int,

  primary key(id),
  unique key(identity(255))
);

create table variable (
  id int not null auto_increment,
  name varchar(100) not null,
  value varchar(100) not null,
  created int,
  modified int,

  primary key(id),
  unique key(name)
);

create table login_cookie (
  id int not null auto_increment,
  userId int not null,
  value varchar(20) not null,
  created int not null,
  modified int not null,

  primary key(id),
  key(value)
);
