<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Reset Password</h2>
        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <input type="hidden" name="email" value="{{ request('email') }}">
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium">New Password</label>
                <input type="password" name="password" id="password"
                       class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                       required>
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700 font-medium">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                       required>
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                Reset Password
            </button>
        </form>
    </div>
</body>
</html>
