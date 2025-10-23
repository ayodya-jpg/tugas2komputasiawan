<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\Position;

class EmployeeController extends Controller
{
    /**
     * Menampilkan daftar karyawan.
     */
    public function index()
    {
        $pageTitle = 'Daftar Karyawan';
        //Mengambil semua data karyawan dengan Eloquent
        $employees = Employee::all();

        return view('employee.index', compact('pageTitle', 'employees'));
    }

    /**
     * Menampilkan form untuk membuat karyawan baru.
     */
    public function create()
    {
        $pageTitle = 'Tambah Karyawan';
        //Mengambil semua posisi dengan Eloquent
        $positions = Position::all();

        return view('employee.create', compact('pageTitle', 'positions'));
    }

    /**
     * Menyimpan data karyawan baru.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => ':Attribute harus berupa format email yang valid.',
            'numeric' => ':Attribute harus berupa angka.'
        ];

        //Validasi data
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        //Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //Menyimpan data karyawan baru menggunakan Eloquent
        $employee = new Employee();
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();

        return redirect()->route('employees.index');
    }

    /**
     * Menampilkan detail karyawan.
     */
    public function show(string $id)
    {
        $pageTitle = 'Detail Karyawan';

        //Mencari data karyawan berdasarkan ID
        $employee = Employee::findOrFail($id);

        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Menampilkan form untuk mengedit data karyawan.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Edit Karyawan';

        //Mengambil data posisi dan karyawan yang akan diedit
        $positions = Position::all();
        $employee = Employee::findOrFail($id);

        return view('employee.edit', compact('pageTitle', 'positions', 'employee'));
    }

    /**
     * Memperbarui data karyawan.
     */
    public function update(Request $request, string $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => ':Attribute harus berupa format email yang valid.',
            'numeric' => ':Attribute harus berupa angka.'
        ];

        //Validasi data
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        //Jika validasi gagal
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        //Mencari data karyawan berdasarkan ID dan memperbarui
        $employee = Employee::findOrFail($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();

        return redirect()->route('employees.index');
    }
}
