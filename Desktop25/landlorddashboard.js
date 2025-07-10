document.addEventListener('DOMContentLoaded', function () {
  const studentList = document.getElementById('studentList'); // Match HTML
  const searchInput = document.getElementById('searchInput');

  function fetchStudents(filter = "") {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_students.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      if (xhr.status === 200) {
        studentList.innerHTML = xhr.responseText;
        attachButtonActions();
      } else {
        studentList.innerHTML = "<p>Error loading students.</p>";
      }
    };
    xhr.send("query=" + encodeURIComponent(filter));
  }

  function attachButtonActions() {
    document.querySelectorAll(".approve-btn").forEach(btn => {
      btn.addEventListener("click", function () {
        const id = this.dataset.id;
        updateStudentStatus(id, "approved");
      });
    });

    document.querySelectorAll(".evict-btn").forEach(btn => {
      btn.addEventListener("click", function () {
        const id = this.dataset.id;
        updateStudentStatus(id, "evicted");
      });
    });
  }

  function updateStudentStatus(id, action) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", `${action}.php`, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
      alert(xhr.responseText);
      fetchStudents(searchInput.value.trim());
    };
    xhr.send("id=" + encodeURIComponent(id));
  }

  searchInput.addEventListener("keyup", () => {
    fetchStudents(searchInput.value.trim());
  });

  fetchStudents();
});
