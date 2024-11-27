<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <form action="{{ route('data.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title:</label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            value="{{ old('title', $data->title) }}"
                            required
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description:</label>
                        <textarea
                            name="description"
                            id="description"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            rows="4">{{ old('description', $data->description) }}</textarea>
                    </div>

                    <!-- Attach New Files -->
                    <div class="mb-6">
                        <label for="files" class="block text-sm font-medium text-gray-700 mb-2">Attach New Files (optional):</label>
                        <input
                            type="file"
                            name="files[]"
                            id="files"
                            multiple
                            accept=".docx,.pdf,.png,.jpg,.jpeg"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <!-- Existing Files -->
                    <div class="mb-6">
                        <p class="block text-sm font-medium text-gray-700 mb-2">Existing Files:</p>
                        <ul class="list-disc pl-5 space-y-2">
                            @foreach ($data->files as $file)
                                <li class="flex items-center justify-between">
                                    <span>{{ $file->filename }}</span>
                                    <div class="flex items-center space-x-4">
                                        <a
                                            href="{{ route('file.download', $file->id) }}"
                                            class="text-blue-500 hover:underline">
                                            Download
                                        </a>
                                        <label class="flex items-center text-gray-700">
                                            <input
                                                type="checkbox"
                                                name="files_to_delete[]"
                                                value="{{ $file->id }}"
                                                class="mr-2">
                                            Mark for Deletion
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right">
                        <button
                            type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
