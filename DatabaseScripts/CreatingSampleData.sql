#Sample Landlord Data
INSERT INTO Landlord (LandlordId, LandLordFname, LandLordLname, LandLordPhone, LandLordEmail, LandlordPassword)
VALUES
(1, 'John', 'Doe', '0712345678', 'johndoe@email.com', 'password123'),
(2, 'Alice', 'Smith', '0723456789', 'alice.smith@email.com', 'alicepass456'),
(3, 'Robert', 'Brown', '0734567890', 'robert.brown@email.com', 'robert789');

#Sample House Data
INSERT INTO House (HouseID, HouseLocation, HouseName, HouseDescription, HousePhoto, LandLordID)
VALUES
(101, 'Downtown Avenue, Nairobi', 'Sunset Villa', 'Modern house with spacious rooms', 'sunsetvilla.jpg', 1),
(102, 'Thika Road, Nairobi', 'Maple Residency', 'Quiet neighborhood with WiFi', 'mapleresidency.jpg', 2),
(103, 'Kilimani Area, Nairobi', 'Palm Heights', 'Secure apartments with balcony view', 'palmheights.jpg', 3);

#Sample Room Data
INSERT INTO Room (RoomId, RoomName, RoomPrice, RoomAvailability, HouseId, RoomPhoto)
VALUES
(201, 'Room A', 10000, true, 101, 'rooma.jpg'),
(202, 'Room B', 12000, true, 101, 'roomb.jpg'),
(203, 'Room C', 9000, false, 102, 'roomc.jpg'),
(204, 'Room D', 11000, true, 103, 'roomd.jpg');

#Sample Student Data
INSERT INTO Student (StudentID, studentFname, studentLname, studentPhone, studentEmail, studentPassword)
VALUES
(301, 'David', 'Kimani', '0798765432', 'david.kimani@student.com', 'kimani123'),
(302, 'Grace', 'Njeri', '0787654321', 'grace.njeri@student.com', 'grace456'),
(303, 'Brian', 'Ouma', '0776543210', 'brian.ouma@student.com', 'ouma789');

#Sample Room Registration Data
INSERT INTO RoomRegistration (RoomRegistrationId, RoomStatus, RoomId, StudentID)
VALUES
(401, 'Pending', 201, 301),
(402, 'Approved', 203, 302),
(403, 'Left', 204, 303);

#Sample Review Data
INSERT INTO Review (StudentID, HouseID, ReviewRating, CommentDescription)
VALUES
(401, 101, 5, 'Amazing stay, very quiet and clean.'),
(402, 102, 4, 'Good house but the water pressure was low.'),
(403, 103, 3, 'Decent place but noisy neighbors.');
