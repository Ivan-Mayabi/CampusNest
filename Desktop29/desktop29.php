<!DOCTYPE HTML>

<html>
    <head>
        <title>Desktop 29</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div id="sidebar_div">
            <!-- This is the sidebar -->
             <sidebar>
                <a><button>Add House</button></a>
                <a href="../Desktop26/Desktop26.php"><button>My Homes</button></a>
            </sidebar>
        </div>
        <!-- This is the second part, that has the form -->
        <div id="form_div">
            <form method="POST" enctype="multipart/form-data" action="registerHouse.php">
                <!-- Division for Name of House -->
                <div>
                    <label for="hse_name">Name of House</label>
                    <input type="text" id="hse_name" name="Name">
                </div>

                <!-- Division for the House Location -->
                <div>
                    <label for="hse_location">Location</label>
                    <input type="text" id="hse_location" name="Location">
                </div>

                <!-- Division for accepting image file -->
                <div>
                    <img id="file_preview" alt="nothing here yet">
                    <input id="file_chosen" name="file_chosen" type=file accept=".png" style="border:none">
                    <small>.png only</small>
                </div>

                <!-- Division for showing the number of rooms -->
                <!-- <div>
                    <label>Number of Rooms</label>
                    <input>
                </div> -->

                <!-- Division for description text area -->
                <div>
                    <label for="hse_description">Description</label>
                    <textarea id="hse_description" name="Description"></textarea>
                </div>

                <!-- Division for add house button -->
                <div>
                    <button type="submit" id="btn_addHouse">Add house</button>
                </div>
            </form>
        </div>

        <!-- Link to the javascript file, should be last thing in body -->
        <script src="desktop29.js"></script>
    </body>
</html>