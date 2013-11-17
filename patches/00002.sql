-- move OpenIDs to a separate table so we can have several OpenIDs per account
create table if not exists identity (
  id int not null auto_increment,
  openId text,
  userId int,
  created int,
  modified int,

  primary key(id),
  unique key(openId(255)),
  key(userId)
);

insert into identity (openId, userId, created, modified) select identity, id, created, modified from user;

alter table user drop identity;
alter table user change nickname username varchar(255), add unique key(username);
