<form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Existing fields for name, email, etc. -->
    <div class="mb-4">
        <label for="driver_license" class="block text-gray-700 dark:text-gray-400">Driver's License</label>
        <input type="file" id="driver_license" name="driver_license" class="mt-1 block w-full">
    </div>
    <div class="mb-4">
        <label for="proof_of_billing" class="block text-gray-700 dark:text-gray-400">Proof of Billing</label>
        <input type="file" id="proof_of_billing" name="proof_of_billing" class="mt-1 block w-full">
    </div>
    <!-- Submit button -->
    <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded">Save</button>
</form>