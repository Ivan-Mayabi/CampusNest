document.addEventListener('DOMContentLoaded', function () {
    const studentList = document.getElementById('studentList');
    const searchInput = document.getElementById('searchInput');

    // Fetch and display student data
    function fetchStudents(filter = "") {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_students.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                studentList.innerHTML = xhr.responseText;
                attachButtonActions(); // Reattach button actions for the new content
            } else {
                studentList.innerHTML = "<p>Error loading students.</p>";
            }
        };

        xhr.send("query=" + encodeURIComponent(filter));
    }

    // Attach approve/evict button click handlers
    function attachButtonActions() {
        document.querySelectorAll(".approve-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const studentId = this.getAttribute("data-student-id");
                const roomId = this.getAttribute("data-room-id");
                updateStudentStatus(studentId, roomId, "approve");
            });
        });

        document.querySelectorAll(".evict-btn").forEach(btn => {
            btn.addEventListener("click", function () {
                const studentId = this.getAttribute("data-student-id");
                const roomId = this.getAttribute("data-room-id");
                updateStudentStatus(studentId, roomId, "evict");
            });
        });
    }

    // Send the approve/evict request
    function updateStudentStatus(studentId, roomId, action) {
        const url = action === "approve" ? "approve.php" : "evict.php";
        const xhr = new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            alert(xhr.responseText); // Show success/error message
            fetchStudents(searchInput.value.trim()); // Refresh student list
        };

        xhr.send(`id=${encodeURIComponent(studentId)}&room_id=${encodeURIComponent(roomId)}`);
    }

    // Filter students when typing
    searchInput.addEventListener("keyup", function () {
        fetchStudents(searchInput.value.trim());
    });

    // Initial fetch
    fetchStudents();
});
