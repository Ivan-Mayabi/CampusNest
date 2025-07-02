document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const studentList = document.getElementById('studentList');

  function fetchStudents(filter = '') {
    fetch(`api.php?action=get_students&filter=${encodeURIComponent(filter)}`)
      .then(response => response.json())
      .then(data => {
        studentList.innerHTML = '';
        if (data.length === 0) {
          studentList.innerHTML = "<p>No matching students found.</p>";
          return;
        }

        data.forEach(student => {
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
      .catch(error => console.error('Fetch error:', error));
  }

  searchInput.addEventListener('input', () => {
    const filterValue = searchInput.value.trim();
    fetchStudents(filterValue);
  });

  fetchStudents(); // Load all when page starts
});
