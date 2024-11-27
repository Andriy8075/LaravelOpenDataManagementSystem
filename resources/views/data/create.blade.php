<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Form -->
                    <form action="{{ route('data.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="title">Title:</label>
                            <input type="text" name="title" id="title" required>
                        </div>

                        <div>
                            <label for="description">Description:</label>
                            <textarea name="description" id="description"></textarea>
                        </div>

                        <div>
                            <label for="files">Attach Files:</label>
                            <input type="file" name="files[]" id="files" multiple accept=".docx,.pdf,.png,.jpg,.jpeg">
                        </div>

                        <button type="submit">Save</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
