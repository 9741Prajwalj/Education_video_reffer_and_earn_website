// Open the Change Password Modal
function openChangePasswordModal() {
  const modal = document.getElementById('changePasswordModal');
  if (modal) {
      modal.classList.remove('hidden');
  } else {
      console.error('Modal element not found.');
  }
}

// Close the Change Password Modal
function closeChangePasswordModal() {
  const modal = document.getElementById('changePasswordModal');
  if (modal) {
      modal.classList.add('hidden');
  } else {
      console.error('Modal element not found.');
  }
}

// Toggle Password Visibility
function togglePasswordVisibility() {
  const currentPassword = document.getElementById('currentPassword');
  const newPassword = document.getElementById('newPassword');

  if (currentPassword && newPassword) {
      const type = currentPassword.type === 'password' ? 'text' : 'password';
      currentPassword.type = type;
      newPassword.type = type;
  } else {
      console.error('Password input elements not found.');
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const successAlert = document.getElementById('successAlert');
  const errorAlert = document.getElementById('errorAlert');
  if (successAlert || errorAlert) {
      setTimeout(() => {
          if (successAlert) successAlert.remove();
          if (errorAlert) errorAlert.remove();
      }, 5000); // Alert disappears after 5 seconds
  }
});

document.addEventListener("DOMContentLoaded", function() {
  // Function to fetch referral list data
  function fetchReferralList() {
      fetch('/referrals')
          .then(response => response.json())
          .then(data => {
              const referralListContainer = document.getElementById('referral-list-container');
              referralListContainer.innerHTML = ''; // Clear existing content

              if (data.length === 0) {
                  referralListContainer.innerHTML = '<p class="text-gray-500 text-center">No referrals yet.</p>';
              } else {
                  const table = document.createElement('table');
                  table.classList.add('min-w-full', 'bg-white', 'border', 'border-gray-200', 'rounded-lg', 'shadow-sm');
                  const thead = document.createElement('thead');
                  thead.innerHTML = `
                      <tr class="bg-gray-100">
                          <th class="px-4 py-2 text-left text-gray-700">Referred Name</th>
                          <th class="px-4 py-2 text-left text-gray-700">Referred Phone</th>
                          <th class="px-4 py-2 text-left text-gray-700">Status</th>
                      </tr>
                  `;
                  table.appendChild(thead);

                  const tbody = document.createElement('tbody');

                  data.forEach(referral => {
                      const row = document.createElement('tr');
                      row.classList.add('border-b');

                      const referralName = document.createElement('td');
                      referralName.classList.add('px-4', 'py-2');
                      referralName.textContent = referral.referral_name;

                      const referralPhone = document.createElement('td');
                      referralPhone.classList.add('px-4', 'py-2');
                      referralPhone.textContent = referral.referral_phone;

                      const referralStatus = document.createElement('td');
                      referralStatus.classList.add('px-4', 'py-2');

                      // Set the status icon based on the referral status
                      if (referral.status === 'sent') {
                          referralStatus.innerHTML = '<i class="fas fa-check text-green-500"></i>'; // Check icon
                      } else {
                          referralStatus.innerHTML = '<i class="fas fa-clock text-yellow-500"></i>'; // Clock icon
                      }

                      // Append the new data to the row
                      row.appendChild(referralName);
                      row.appendChild(referralPhone);
                      row.appendChild(referralStatus);

                      // Append the row to the table body
                      tbody.appendChild(row);
                  });

                  table.appendChild(tbody);
                  referralListContainer.appendChild(table);
              }
          })
          .catch(error => console.error('Error fetching referral data:', error));
  }

  // Fetch referral data when the page loads
  fetchReferralList();
});

