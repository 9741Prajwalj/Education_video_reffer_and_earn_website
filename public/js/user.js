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
