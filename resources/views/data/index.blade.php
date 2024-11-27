<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Search Bar -->
                    <form method="GET" action="{{ route('data.index') }}" class="mb-4">
                        <input
                            type="text"
                            name="search"
                            placeholder="Search by title or description..."
                            value="{{ request('search') }}"
                            class="border border-gray-300 rounded-md px-4 py-2 w-full"
                        />
                    </form>

                    <!-- Data Table -->
                    <table class="min-w-full border border-gray-300">
                        <thead>
                        <tr>
                            <th class="px-4 py-2 border">Title</th>
                            <th class="px-4 py-2 border">Description</th>
                            <th class="px-4 py-2 border">Files</th>
                            @if(auth()->user())
                                <th class="px-4 py-2 border">Actions</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="px-4 py-2 border">{{ $item->title }}</td>
                                <td class="px-4 py-2 border">{{ $item->description }}</td>
                                <td class="px-4 py-2 border">
                                    @foreach ($item->files as $file)
                                        <a href="{{ route('file.download', $file->id) }}?file_path={{ urlencode($file->file_path) }}&filename={{ urlencode($file->filename) }}"
                                           class="text-green-500">
                                            Download {{ $file->filename }}
                                        </a><br>
                                    @endforeach
                                </td>
                                @if(auth()->user())
                                    <td class="px-4 py-2 border">
                                        @if (auth()->user()->isAdmin())
                                            <a href="{{ route('data.edit', $item) }}" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</a>
                                            <form action="{{ route('data.destroy', $item) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                                            </form>
                                        @endif
                                        @if (auth()->user()->savedData->contains($item->id))
                                            <form action="{{ route('data.unsave', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete from saved</button>
                                            </form>
                                        @else
                                            <form action="{{ route('data.save', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </form>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
