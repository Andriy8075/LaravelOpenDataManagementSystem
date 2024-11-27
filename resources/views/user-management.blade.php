<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Search Form --}}
                    <form method="GET" action="{{ url('/user-management') }}">
                        <input type="text" name="search" placeholder="Search by name or email"
                               value="{{ request('search') }}"
                               class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                            Search
                        </button>
                    </form>

                    {{-- User List --}}
                    <table class="table-auto w-full mt-6 border-collapse border border-gray-300">
                        <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2">Name</th>
                            <th class="border border-gray-300 px-4 py-2">Email</th>
                            <th class="border border-gray-300 px-4 py-2">Role</th>
                            <th class="border border-gray-300 px-4 py-2">Actions</th>
                        </tr>
                        </thead>
                        @if (session('status'))
                            <div class="py-4">
                                <div class="bg-green-500 text-white px-4 py-2 rounded-md mb-4">
                                    {{ session('status') }}
                                </div>
                            </div>
                        @endif
                        <tbody>

                        @foreach ($users as $user)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $user->role }}</td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <div class="flex space-x-2">
                                        @if ($user->role === 'default')
                                            <form action="{{ route('users.make-admin', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Promote to Admin</button>
                                            </form>
                                        @endif

                                        @if ($user->role === 'admin')
                                            <form action="{{ route('users.remove-admin', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Remove Admin</button>
                                            </form>

                                            <form action="{{ route('users.make-superadmin', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Promote to Superadmin</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
