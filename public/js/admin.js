
    // Function to show the selected table (user or referral)
    function showTable(tableType) {
      // Hide both tables by default
      document.getElementById('userTable').style.display = 'none';
      document.getElementById('referralTable').style.display = 'none';
      // Show the selected table
      if (tableType === 'user') {
          document.getElementById('userTable').style.display = 'block';
      } else if (tableType === 'referral') {
          document.getElementById('referralTable').style.display = 'block';       
          // Fetch referrals for the specific user if provided
          if (userId) {
              fetchReferrals(userId);
          }
      }
  }
  // Function to fetch referrals via an AJAX request
  function fetchReferrals(userId) {
      // Make an AJAX request to fetch the referrals
      fetch(`/admin/referrals/${userId}`)
          .then(response => response.json())
          .then(data => {
              let referralTableBody = document.getElementById('referralTableBody');
              referralTableBody.innerHTML = ''; // Clear existing table rows
              // Append new referral data to the table
              data.forEach(referral => {
                  let row = document.createElement('tr');
                  row.classList.add('border-t');
                  row.innerHTML = `
                      <td class="py-2 px-4">${referral.id}</td>
                      <td class="py-2 px-4">${referral.referral_name}</td>
                      <td class="py-2 px-4">${referral.referral_phone}</td>
                      <td class="py-2 px-4">${referral.user_id}</td>
                  `;
                  referralTableBody.appendChild(row);
              });
          })
          .catch(error => console.error('Error fetching referrals:', error));
      }
      // Function to open the edit modal and populate the input field with current points
      function openEditModal(userId, currentPoints) {
          document.getElementById('pointsInput').value = currentPoints;
          document.getElementById('editPointsModal').style.display = 'flex';
          window.currentUserId = userId; // Store the user ID to save points later
      }
      // Function to save the updated points
    function savePoints() {
        const newPoints = document.getElementById('pointsInput').value;

        try {
            // Validate new points
            if (!newPoints || isNaN(newPoints) || newPoints < 0) {
                alert('Please enter a valid non-negative number for points.');
                return;
            }

            // Make the fetch request to update points
            fetch(`/admin/dashboard/update-points/${window.currentUserId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ points: parseInt(newPoints) }),
            })
            .then(response => {
                // Handle non-200 HTTP responses
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to update points. Status: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Points updated successfully!');
                    console.log('Response:', data);
                    updateUserTable(window.currentUserId, newPoints);
                    closeEditModal();
                    setTimeout(() => location.reload(), 1000);// Add a slight delay for better UX
                } else {
                    throw new Error(data.message || 'Unknown error occurred');
                }
            })
            .catch(error => {
                // Show error toast message
                showToast('An error occurred: ' + error.message, 'error');
                console.error('Error:', error);
            });
        } catch (error) {
            // Handle unexpected errors
            alert('An unexpected error occurred: ' + error.message);
            console.error('Unexpected Error:', error);
        }
}

// Function to update the points in the user table after successful saving
function updateUserTable(userId, newPoints) {
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (userRow) {
        const pointsCell = userRow.querySelector('.points-cell');
        if (pointsCell) {
            pointsCell.textContent = newPoints;
        }
    }
}

// Close the modal
function closeEditModal() {
    document.getElementById('editPointsModal').style.display = 'none';
}

// Open the modal and pre-fill data
function openEditModal(userId, currentPoints) {
    window.currentUserId = userId; // Store user ID globally for use in savePoints
    document.getElementById('pointsInput').value = currentPoints;
    document.getElementById('editPointsModal').style.display = 'flex';
} 

