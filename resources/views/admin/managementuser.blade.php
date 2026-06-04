@extends('layout.layoutadmin')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola data seluruh pengguna sistem, admin, dan pegawai.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.user.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition duration-300 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Pengguna
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <p>{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100">

            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">

                <form action="{{ route('admin.user.index') }}" method="GET"
                    class="relative w-full max-w-sm flex items-center">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition duration-150 ease-in-out"
                            placeholder="Cari NIP atau Nama...">
                    </div>

                    <button type="submit" class="hidden">Cari</button>
                </form>

                @if (request('search'))
                    <a href="{{ route('admin.user.index') }}"
                        class="text-sm text-red-500 hover:text-red-700 font-medium ml-4 transition">
                        Bersihkan Pencarian
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                No
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                NIP
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Nama Lengkap
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Email
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Role
                            </th>
                            <th
                                class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-5 py-4 bg-white text-sm text-gray-600">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-5 py-4 bg-white text-sm">
                                    <span
                                        class="font-mono text-blue-600 font-semibold bg-blue-50 px-2 py-1 rounded">{{ $user->nip }}</span>
                                </td>
                                <td class="px-5 py-4 bg-white text-sm">
                                    <p class="text-gray-900 font-semibold whitespace-no-wrap">{{ $user->name }}</p>
                                </td>
                                <td class="px-5 py-4 bg-white text-sm">
                                    <p class="text-gray-600 whitespace-no-wrap">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-4 bg-white text-sm">
                                    @if ($user->role === 'superadmin')
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-bold rounded-md bg-purple-100 text-purple-700">Super
                                            Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-bold rounded-md bg-blue-100 text-blue-700">Admin</span>
                                    @else
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-bold rounded-md bg-green-100 text-green-700">User</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 bg-white text-sm text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('admin.user.edit', $user->nip) }}"
                                            class="text-blue-500 hover:text-blue-700 transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.user.destroy', $user->nip) }}" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition"
                                                title="Hapus">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center bg-white text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <p>Belum ada data pengguna yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-4 bg-white border-t border-gray-200">
                {{-- {{ $users->links() }} --}}
            </div>
        </div>
    </div>
@endsection
