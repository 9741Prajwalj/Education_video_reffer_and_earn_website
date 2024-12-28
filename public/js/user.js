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



document.addEventListener("DOMContentLoaded", function () {
  const openNotificationsBtn = document.getElementById("openNotificationsBtn");
  const notificationModal = document.getElementById("notificationModal");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const notificationContent = document.getElementById("notificationContent");

  // Open modal and fetch notifications
  openNotificationsBtn.addEventListener("click", async () => {
      notificationModal.classList.remove("hidden");

      try {
          const response = await fetch("/notifications");
          const notifications = await response.json();

          if (notifications.length > 0) {
              notificationContent.innerHTML = notifications
                  .map(notification => `
                      <div class="p-3 border-b last:border-0">
                          <h3 class="text-md font-semibold">${notification.title}</h3>
                          <p class="text-sm text-gray-600">${notification.message}</p>
                      </div>
                  `).join("");
          } else {
              notificationContent.innerHTML = `<p class="text-gray-500">No notifications available.</p>`;
          }
      } catch (error) {
          console.error("Error fetching notifications:", error);
          notificationContent.innerHTML = `<p class="text-red-500">Failed to load notifications.</p>`;
      }
  });

  // Close modal
  closeModalBtn.addEventListener("click", () => {
      notificationModal.classList.add("hidden");
  });
});
