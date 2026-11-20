<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
       User::create([
        'name' => 'Admin OperinSini',
        'email' => 'admin@operinsini.ac,id',
        'phone' => '08120000000',
        'role' => 'admin',
        'password' => 'admin12345',
       ]);
       $donatur = User::create([
        'name' => 'Budi Santoso',
        'email' => 'budi@student.ub.ac.id',
        'phone' => '081234567890',
        'role' => 'mahasiswa',
        'password' => 'password123',
       ]);
       User::create([
        'name' => 'Ahmad Fauzi',
        'email' => 'fauzi@student.ub.ac.id',
        'phone' => '081298765432',
        'role' => 'mahasiswa',
        'password' => 'password123',
       ]);

       foreach (['Buku Kuliah', 'Perabot Kost', 'Pakaian', 'Elektronik', 'Alat Tulis'] as $name) {
        Category::create(['name' => $name]);
       }
       $demo = [
        ['Buku Kalkulus & Struktur Data', 1, 'Baik', 'Masih rapi tanpa coretan, edisi ke-8. Cocok untuk semester 1 dan 2'],
        ['Kipas Angin Meja 9 Inch', 4, 'Baik', 'Motor halus, 3 level kecepatan. Dihibahkan karena sudah wisuda'],
        ['Rak Sepatu Portable 4 Susun', 2, 'Cukup', 'Bisa bongkar pasang, muat 12 pasang sepatu'],
       ];

       foreach ($demo as [$name, $category, $condition, $description]) {
        Item::create([
            'user_id' => $donatur->id,
            'category_id' => $category,
            'name' => $name,
            'condition' => $condition,
            'description' => $description,
            'status' => 'tersedia',
        ]);
       }
    }
}
