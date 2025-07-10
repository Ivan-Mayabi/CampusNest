document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const studentList = document.getElementById('studentList');
  const welcomeText = document.getElementById('welcomeText');

  function fetchStudents(filter = '') {
    fetch(`api.php?action=get_students&filter=${encodeURIComponent(filter)}`)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          studentList.innerHTML = `<p class="no-results">${data.error}</p>`;
          return;
        }

        welcomeText.textContent = `Welcome Mr. ${data.landlord_name}`;

        studentList.innerHTML = '';
        if (data.students.length === 0) {
          studentList.innerHTML = "<p class='no-results'>No students found.</p>";
          return;
        }

        data.students.forEach(student => {
          const entry = document.createElement('div');
          entry.className = 'student-entry';
          entry.innerHTML = `
            <div class="student-info">
              <h3>${student.name}</h3>
              <p><strong>Location:</strong> ${student.location}</p>
              <p><strong>Room:</strong> ${student.room_number}</p>
              <p><strong>Status:</strong> ${student.status}</p>
            </div>
            <div class="student-actions">
              <button data-id="${student.id}">Student Details</button>
              <button data-id="${student.id}">Room Details</button>
              <button class="approve-btn" data-id="${student.id}">APPROVE</button>
              <button class="evict-btn" data-id="${student.id}">EVICT</button>
            </div>
          `;
          studentList.appendChild(entry);
        });
      })
      .catch(err => {
        studentList.innerHTML = `<p class="no-results">Something went wrong</p>`;
        console.error(err);
      });
  }

    function attachActionHandlers() {
    document.querySelectorAll('.approve-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        fetch('approve.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${encodeURIComponent(id)}`
        })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          fetchStudents(); // refresh list
        });
      });
    });

    document.querySelectorAll('.evict-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        fetch('evict.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `id=${encodeURIComponent(id)}`
        })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          fetchStudents(); // refresh list
        });
      });
    });
  }

  searchInput.addEventListener('input', () => {
    fetchStudents(searchInput.value.trim());
  });

  fetchStudents(); // Load all students when page loads
});



