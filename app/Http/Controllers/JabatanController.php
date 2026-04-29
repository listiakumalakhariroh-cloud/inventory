<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('admin.jabatan.index', compact('jabatans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jabatan' => 'required|string|max:50|unique:jabatans,nama_jabatan'
        ]);

        Jabatan::create($request->all());
        return back()->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Jabatan::findOrFail($id)->delete();
        return back()->with('success', 'Jabatan berhasil dihapus');
    }
}