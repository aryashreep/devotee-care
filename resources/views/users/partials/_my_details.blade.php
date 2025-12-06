<div id="my-details" class="tab-content">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Full Name</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" value="{{ $user->name }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="gender">Gender</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="gender" name="gender" type="text" value="{{ $user->gender }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="date_of_birth">Date of Birth</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="date_of_birth" name="date_of_birth" type="date" value="{{ $user->date_of_birth }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="marital_status">Marital Status</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="marital_status" name="marital_status" type="text" value="{{ $user->marital_status }}" disabled>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="marriage_anniversary_date">Marriage Anniversary Date</label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="marriage_anniversary_date" name="marriage_anniversary_date" type="date" value="{{ $user->marriage_anniversary_date }}" disabled>
    </div>
</div>
