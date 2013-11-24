create table login_token (
  id int not null auto_increment,
  userId int not null,
  token varchar(50) not null,
  created int,
  modified int,

  primary key(id),
  unique key(userId)
);
