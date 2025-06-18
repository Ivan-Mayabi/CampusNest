drop table tbl_role;

select * from house;
select * from landlord;
select * from student;
select * from review;
select * from room;
select * from roomregistration;

delete from landlord where landlordid between 1 and 1000;
delete from house where houseId between 1 and 1000;
delete from review where reviewid between 1 and 1000;
delete from room where roomid between 1 and 1000;
delete from roomregistration where roomregistrationid between 1 and 1000;
delete from student where studentid between 1 and 1000;

alter table house drop constraint fk_House_LandLord_LandLordID;
alter table review drop constraint fk_review_student_studentID;
alter table roomregistration drop constraint fk_roomregistration_student_studentid;

create table roles(
	roleId int,
    roleType varchar(30)
);

alter table roles
add constraint pk_role_roleId
primary key (roleID);

alter table roles
modify column roleid varchar(30);

insert into roles(roleID,roleType) values
	("Student","Student"),
    ("Landlord","Landlord");

delete from roles where roleid in ("Student","Landlord");

insert into roles(roleID,roleType) values
	("R002","Student"),
    ("R001","Landlord");
    
select * from student;
select * from landlord;

create table users(
	userID int,
    userFname varchar(30),
    userLname varchar(30),
    userPhone varchar(30),
    userEmail varchar(30),
    userPassword varchar(30),
    userRole varchar(30)
);

alter table users
add constraint fk_users_userRole_roles_roleId
foreign key(userRole)
references roles(roleid);

alter table users
add constraint pk_users_userId
primary key(userId);

select  * from users;
describe house;

alter table house
add constraint fk_house_landlordId_users_userId
foreign key(landlordId)
references users(userID);

alter table house drop constraint fk_house_landlord_users_userId;

alter table roomregistration
add constraint fk_roomregistration_studentId_users_userId
foreign key(studentID)
references users(userId);

alter table review
add constraint fk_review_studentId_users_userId
foreign key(studentId)
references users(userId);

delimiter //
create trigger trg_fk_house_landlordId_users_userId
before insert on house
for each row
Begin
	declare new_roleId varchar(30);
	select userRole into new_roleId from users where userId = new.landlordId;
	if new_roleId != "R001" then
		signal sqlstate "45000"
        set message_text = 'Cannot link a user to house unless they are specified as Landlords';
	end if;
end;
//
delimiter ;

select * from users;

alter table users rename column userRole to userRoleId;

delimiter //
create trigger trg_fk_roomregistration_studentId_users_userId
before insert on roomregistration
for each row
Begin
	declare new_userRoleID varchar(30);
    select userRoleId into new_userRoleId from users where userid = new.studentId;
    if new_userRoleId != "R002" then 
		signal sqlstate "45000"
        set message_text  = "Cannot add to room registration if not a student";
	end if;
end;
//

select * from users;

delimiter //
create trigger trg_fk_review_studentId_users_userId
before insert on review
for each row
begin
	declare new_userRoleId varchar(30);
    select userRoleId into new_userRoleId from users where userID = new.studentId;
    if new_userRoleId != "R002" then
		signal sqlstate "45000"
        set message_text = "Cannot add to review if not a student";
	end if;
end;
//
fk_review_studentId_users_userIdtrg_fk_review_studentId_users_userId
drop table landlord;
drop table student;