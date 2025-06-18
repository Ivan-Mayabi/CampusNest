#Adds the ability to have more than one person in the room
alter table room add(
	roomcapacity int,
	roomvacancies int
);

#Makes sure you can't have more vacancies than the room can accomodate
#Makes sure you don't have negative vacancies
alter table room
add constraint chk_room_roomvacanies
check ((roomvacancies<=roomcapacity) and (roomvacancies>=0));

--Automatically updates the room capacity and vacancies
Delimiter //
create trigger trg_room_roomvacancyupdate
after insert on roomregistration
for each row
begin
    if new.roomStatus='Approved' then
        update rooms
        set roomvacancies=roomvacancies-1
        where roomID= new.roomId;
    elseif new.roomStatus='Left' then
        update rooms
        set roomvacancies=roomvacancies+1
        where roomId= new.roomID;
    end if;
end;
//
Delimiter ;

