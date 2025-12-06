@if($view === 'view')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <p class="text-gray-700 font-bold">Email:</p>
        <p>{{ $user->email ?? 'N/A' }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Mobile Number:</p>
        <p>{{ $user->mobile_number }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Address:</p>
        <p>{{ $user->address }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">City:</p>
        <p>{{ $user->city }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">State:</p>
        <p>{{ $user->state }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Pincode:</p>
        <p>{{ $user->pincode }}</p>
    </div>
    <div>
        <p class="text-gray-700 font-bold">Country:</p>
        <p>{{ $user->country ?? 'N/A' }}</p>
    </div>
</div>
@else
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
        <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
        <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="state" class="block text-sm font-medium text-gray-700">State</label>
        <input type="text" name="state" id="state" value="{{ old('state', $user->state) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="pincode" class="block text-sm font-medium text-gray-700">Pincode</label>
        <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $user->pincode) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
    <div>
        <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
        <input type="text" name="country" id="country" value="{{ old('country', $user->country) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
    </div>
</div>
@endif
