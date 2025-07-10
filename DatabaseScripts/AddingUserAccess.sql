insert into roles(roletype,roleid) values ("Administrator","R003");

alter table users add column(
	userAccess boolean
);

update users set userAccess=1 WHERE USERid between 1 and 1000;