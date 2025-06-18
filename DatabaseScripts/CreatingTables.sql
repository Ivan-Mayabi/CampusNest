/*Throughout the document foreign keys and primary keys are labelled:
		The syntax is:
			`		typeOFConstraint_tablesConstraintAffects_ColumnConstraintAffects
		For example
					pk_student_studentID;
                    fk_house_landlord_landlordID;
*/

#Create the landlord table
create table Landlord(
    LandlordId int,
    LandLordFname varchar(30),
    LandLordLname varchar(30),
    LandLordPhone varchar(15),
    LandLordEmail varchar(60),
    LandlordPassword varchar(30)
);

#Create the PK for landlord
alter table Landlord 
add constraint pk_LandLord_LandLordid
PRIMARY KEY(LandLordID);

#Creating the house table
create table House(  
    HouseID int,
    HouseLocation varchar(60),
    HouseName varchar(30),
    HouseDescription varchar(100),
    HousePhoto varchar(2000),
    LandLordID int
);

#Creating the PK for house
alter table House 
add constraint pk_House_HouseID
primary key(HouseId);

#Creating the FK for house that links to landlords
alter table House
add constraint fk_House_LandLord_LandLordID
Foreign Key(LandLordId)
references LandLord(LandLordId);

#Creating the rooms table
create table room(
    RoomId int,
    RoomName varchar(30),
    RoomPrice int,
    RoomAvailability boolean, #Can either be available or not -> true or false;
    HouseId int,
    RoomPhoto varchar(2000)
);

#Creating the PK for rooms
alter table Room
add constraint pk_Room_roomID
primary key(RoomId);

#Creating the FK for rooms that links to houses
alter table Room
add constraint fk_Room_House_houseID
foreign key(houseID)
references house(houseID);

#Creating the student database
create table student(
    studentID int,
    studentFname varchar(30),
    studentLname varchar(30),
    studentPhone varchar(15),
    studentEmail varchar(60),
    studentPassword varchar(30)
);

#Creating the PK for students
alter table student 
add constraint pk_student_studentID
primary key(studentID);

#Creating the roomregistration table
create table roomregistration(
    RoomRegistrationId int,
    RoomStatus varchar(20) default ('Pending'), #Makes the default room status to be pending
    RoomId int,
    StudentID int,
    RoomRegisteredTime datetime default (now()) #Makes the default registered time to be now
);

#RoomStatus can be pending, approved or left;
alter table roomregistration
add constraint chk_roomregistration_roomStatus
check ((roomStatus='Pending') or (roomStatus='Approved') or (roomStatus='Left'));

#Creating the PK for roomregistration 
alter table roomregistration
add constraint pk_roomregistration_roomregistrationID
primary key(roomregistrationID);

#Creating the FK for roomregistration that links to roomID
alter table roomregistration
add constraint fk_roomregistration_room_roomid
foreign key(roomid)
references room(roomid);

#Creating the FK for roomregistration that links to studentID
alter table roomregistration
add constraint fk_roomregistration_student_studentid
foreign key (studentid)
references student(studentid);

#Creating the review table
create table review(
    ReviewID int auto_increment,
	StudentID int,
    HouseID int,
    ReviewRating int,
    CommentDescription varchar(120),
    CommentTime datetime default(now()), #The default time for a comment is now
    constraint pk_review_reviewID primary Key(ReviewID)
);

#Creating the FK for review table that links with student
alter table review
add constraint fk_review_student_studentID
foreign key(StudentID)
references roomregistration(roomregistrationid);

