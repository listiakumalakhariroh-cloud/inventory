@extends('layout.layoutadmin')

@section('title', isset($user) ? 'Edit Pengguna' : 'Tambah User')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.user.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center mb-2">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Pengguna
        </a>
        <h2 class="text-2xl font-bold text-gray-800">{{ isset($user) ? 'Edit Pengguna' : 'Tambah User baru' }}</h2>
        <p class="text-sm text-gray-500 mt-1">
            {{ isset($user) ? 'Ubah data pengguna yang dipilih di bawah ini.' : 'Isi formulir berikut untuk mendaftarkan pengguna baru ke dalam sistem.' }}
        </p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6 border border-gray-100">
        <form action="{{ isset($user) ? route('admin.user.update', $user->nip) : route('admin.user.store') }}" method="POST">
            @csrf
            @if(isset($user))
                @method('PUT')
            @endif

            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" name="nip" value="{{ old('nip', isset($user) ? $user->nip : '') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm @if(isset($user)) bg-gray-50 text-gray-500 @endif"
                        placeholder="Contoh: 199001012024011001" {{ isset($user) ? 'readonly' : '' }}>
                    @if(isset($user))
                        <p class="text-xs text-gray-400 mt-1">*NIP tidak dapat diubah setelah terdaftar.</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', isset($user) ? $user->name : '') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Masukkan nama lengkap beserta gelar">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', isset($user) ? $user->email : '') }}" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="contoh@domain.com">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Hak Akses (Role)</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="user" {{ old('role', isset($user) ? $user->role : '') == 'user' ? 'selected' : '' }}>User / Pegawai</option>
                        <option value="admin" {{ old('role', isset($user) ? $user->role : '') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ old('role', isset($user) ? $user->role : '') == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                </div>

                <hr class="border-gray-100 my-2">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' }}">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="{{ isset($user) ? 'Kosongkan jika tidak ingin mengubah password' : 'Ulangi password baru' }}">
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.user.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold shadow-sm transition">
                        {{ isset($user) ? 'Simpan Perubahan' : 'Daftarkan User' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection