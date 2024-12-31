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



document.addEventListener('DOMContentLoaded', () => {
  const openNotificationsBtn = document.getElementById('openNotificationsBtn');
  const notificationModal = document.getElementById('notificationModal');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const notificationCounter = document.getElementById('notificationCounter');
  const notificationContent = document.getElementById('notificationContent');

  let unseenCount = 0; // Counter for unseen notifications

  // Fetch notifications and update counter
  async function fetchNotifications() {
      try {
          const response = await fetch('/notifications/fetch');
          const data = await response.json();

          // Update notification content
          if (data.notifications.length > 0) {
              notificationContent.innerHTML = data.notifications
                  .map(
                      (notification) => `
                      <div class="mb-4 p-3 bg-gray-100 rounded shadow">
                          <h3 class="font-bold">${notification.title}</h3>
                          <p>${notification.message}</p>
                      </div>`
                  )
                  .join('');
              unseenCount = data.unseen_count; // Update unseen notifications
              updateNotificationCounter();
          } else {
              notificationContent.innerHTML = '<p class="text-gray-500">No notifications found.</p>';
              unseenCount = 0;
              updateNotificationCounter();
          }
      } catch (error) {
          console.error('Error fetching notifications:', error);
      }
  }

  // Update the notification counter
  function updateNotificationCounter() {
      if (unseenCount > 0) {
          notificationCounter.textContent = unseenCount;
          notificationCounter.classList.remove('hidden');
      } else {
          notificationCounter.classList.add('hidden');
      }
  }

  // Open the modal and mark notifications as seen
  openNotificationsBtn.addEventListener('click', async () => {
      notificationModal.classList.toggle('hidden');

      if (!notificationModal.classList.contains('hidden')) {
          unseenCount = 0; // Reset unseen notifications
          updateNotificationCounter();

          // Mark notifications as seen in the backend
          await fetch('/notifications/mark-seen', { method: 'POST' });
      }
  });

  // Close the modal
  closeModalBtn.addEventListener('click', () => {
      notificationModal.classList.add('hidden');
  });

  // Initial fetch for notifications
  fetchNotifications();

  // Periodically check for new notifications
  setInterval(fetchNotifications, 100000); // Check every 30 seconds
});


document.addEventListener("DOMContentLoaded", function () {
    const totalReferralElement = document.getElementById("totalReferral");

    // Fetch referral_received value on page load
    axios
        .get('/referral-received')
        .then((response) => {
            totalReferralElement.textContent = response.data.referral_received;
        })
        .catch((error) => {
            console.error(error.response.data);
            alert("Failed to load referral data.");
        });

    // Handle form submission
    const referralForm = document.getElementById("referralForm");
    referralForm.addEventListener("submit", function (e) {
        e.preventDefault();

        const referralNumber = document.getElementById("referralNumber").value;
        const csrfToken = document.querySelector('input[name="_token"]').value;

        axios
            .post('/store-referral', {
                referral_received: referralNumber,
            }, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
            .then((response) => {
                alert("Referral updated successfully!");
                totalReferralElement.textContent = response.data.referral_received;
            })
            .catch((error) => {
                console.error(error.response.data);
                alert("An error occurred: " + error.response.data.message);
            });
    });
});
