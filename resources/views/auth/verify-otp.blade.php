<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Verify OTP</h2>
        <form method="POST" action="{{ route('otp.verify.post') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium">Email</label>
                <input type="email" name="email" id="email"
                       class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                       required>
            </div>
            <div class="mb-4">
                <label for="otp" class="block text-gray-700 font-medium">OTP</label>
                <input type="text" name="otp" id="otp"
                       class="w-full mt-1 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200"
                       required>
            </div>
            <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">
                Verify OTP
            </button>
        </form>
    </div>
</body>
</html>
