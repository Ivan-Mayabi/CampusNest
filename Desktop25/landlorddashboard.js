document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('searchInput');
  const propertyList = document.getElementById('propertyList');
  const welcomeText = document.getElementById('welcomeText');

  function fetchProperties(filter = '') {
    fetch(`api.php?action=get_properties&filter=${encodeURIComponent(filter)}`)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          propertyList.innerHTML = `<p class="no-results">${data.error}</p>`;
          return;
        }

        // Set welcome message
        welcomeText.textContent = `Welcome Mr. ${data.landlord_name}`;

        // Display properties
        propertyList.innerHTML = '';
        if (data.houses.length === 0) {
          propertyList.innerHTML = `<p class="no-results">No matching houses found.</p>`;
          return;
        }

        data.houses.forEach(house => {
          const card = document.createElement('div');
          card.className = 'student-entry';
          card.innerHTML = `
            <div class="student-info">
              <h3>${house.name}</h3>
              <p><strong>Location:</strong> ${house.location}</p>
              <p><strong>Description:</strong> ${house.description}</p>
            </div>
          `;
          propertyList.appendChild(card);
        });
      })
      .catch(err => {
        propertyList.innerHTML = `<p class="no-results">Error loading properties.</p>`;
        console.error(err);
      });
  }

  searchInput.addEventListener('input', () => {
    fetchProperties(searchInput.value.trim());
  });

  fetchProperties(); // Load on page load
});
