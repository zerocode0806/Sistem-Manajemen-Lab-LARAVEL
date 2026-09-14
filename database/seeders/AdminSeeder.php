<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Seed the `admin` table from the provided database export.
     * Uses upsert() keyed on the primary key so this seeder is safe to
     * run multiple times without creating duplicates or failing on
     * unique constraints.
     */
    public function run(): void
    {
        $rows = [
            ['id_admin' => 1, 'nama' => 'M Ubaidilla Dahlan', 'email' => 'dahlanubed@gmail.com', 'username' => 'opet', 'password' => '$2y$12$gU3hFOKE1tHlunWnyS.Yn.Nm59RMsJz6rPbCjD9WdiQYAe4OW4Csq', 'remember_token' => null, 'created_at' => '2026-09-09 05:14:17', 'updated_at' => '2026-09-09 05:14:17'],
            ['id_admin' => 7, 'nama' => 'Ferdian', 'email' => 'ferdian@gmail.com', 'username' => 'joran', 'password' => '$2y$12$MyS3ra196kNuam4wiqLXKOjjRf8hfmfMGUa3sUOVXNbLsZ.0G0MTG', 'remember_token' => null, 'created_at' => '2026-09-10 07:08:51', 'updated_at' => '2026-09-10 01:52:41'],
            ['id_admin' => 8, 'nama' => 'Aira', 'email' => 'aira@gmail.com', 'username' => 'weci', 'password' => '$2y$12$gXjYOPbXdllKpUD3Tzc.wOCBOjFjcd12KoX16.biyYfuUTZxu8wTW', 'remember_token' => null, 'created_at' => '2026-09-10 07:08:51', 'updated_at' => '2026-09-10 02:40:35'],
            ['id_admin' => 9, 'nama' => 'Apriza', 'email' => 'apriza@gmail.com', 'username' => 'ape', 'password' => '$2y$12$CBlcgKRPDbfcmMArtsfkw.FP0zHtn/9jlabo3lZpJOcoaemlANye.', 'remember_token' => null, 'created_at' => '2026-09-10 07:08:51', 'updated_at' => '2026-09-10 00:09:08'],
            ['id_admin' => 10, 'nama' => 'Angga', 'email' => 'angga@gmail.com', 'username' => 'pragos', 'password' => '$2y$10$0vmrvBaTi0KIZPtsavEAReS1pyZhQR/hwDusBiDHJZwNIaBNE4bny', 'remember_token' => null, 'created_at' => '2026-09-10 07:08:51', 'updated_at' => '2026-09-10 07:08:51'],
            ['id_admin' => 11, 'nama' => 'Agung', 'email' => 'agung@gmail.com', 'username' => 'enol', 'password' => '$2y$10$sVHQv../1NFdjyqL8lW1f.678NUSHKvHchm8UKP.dAPF1CENFymdS', 'remember_token' => null, 'created_at' => '2026-09-10 07:08:51', 'updated_at' => '2026-09-10 07:08:51'],
            ['id_admin' => 12, 'nama' => 'Zia', 'email' => 'zia@gmail.com', 'username' => 'bluetooth', 'password' => '$2y$10$O6Zb4399HzGBYa2XAhCY5u7l604S9cuOxXwCMBijejbWz05MVq0NW', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 13, 'nama' => 'Adrian', 'email' => 'adrian@gmail.com', 'username' => 'awing', 'password' => '$2y$10$CAJwl2lrBuMsK07apf/Jje.vdrhzhp8pNJ79lh9zLodCb.ZKC5c3W', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 14, 'nama' => 'Amel', 'email' => 'amel@gmail.com', 'username' => 'saleh', 'password' => '$2y$10$3QlKraDvxh1X25iojrfj6.V5qlP7th9ATXZ9pih/K49ndSCNcMTqu', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 15, 'nama' => 'Dhea', 'email' => 'dhea@gmail.com', 'username' => 'dhewor', 'password' => '$2y$10$9NMmTFlYr4QJmpkdoaf2tOnUqWGa8wV9oRdVBPDlS0W20qrJdBEye', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 16, 'nama' => 'Lian', 'email' => 'lian@gmail.com', 'username' => 'genjreng', 'password' => '$2y$10$yeT0/iFfC.rzpPqXHSHIL.RbFp1HYI3jKJClavHoyoT11Q.SD.LS.', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 17, 'nama' => 'Ardira', 'email' => 'ardira@gmail.com', 'username' => 'dora', 'password' => '$2y$10$m/1/2SGIgxeLzDqffNTve.QOQ0i4FLEbX3kt76gUkiWUP2G2byoRO', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 18, 'nama' => 'Bintang', 'email' => 'bintang@gmail.com', 'username' => 'gopal', 'password' => '$2y$10$CyuUq/dCcRU.EUHZrzKpfe0ksjbhV78xoN/d.30nf8KPDV5zZb0rC', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 19, 'nama' => 'Nadya', 'email' => 'nadya@gmail.com', 'username' => 'ipeh', 'password' => '$2y$10$uCwcaVJ0EYd2lIL6.SJhGOSZUH5SyCpJrYHYe0mfPUqiL.fzGHYv6', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 20, 'nama' => 'Emil', 'email' => 'emil@gmail.com', 'username' => 'congek', 'password' => '$2y$10$nIhLtHnBJigBP40/40.za.iAc0y0HtReGcr7bptXdQdoWs9G/OULK', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 21, 'nama' => 'Lia', 'email' => 'lia@gmail.com', 'username' => 'gendur', 'password' => '$2y$10$400Nj8R.1eaNvnyUTxdo7.bOhE2Nm/DgALFg/VJBsnyUecLdPp7sW', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 22, 'nama' => 'Abiyu', 'email' => 'abiyu@gmail.com', 'username' => 'bongek', 'password' => '$2y$10$76M/SSvcpwZpOY5tl.9NV.ZjRxx.ZWgLwCdf8ikUPeERfzS1YfRSm', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 23, 'nama' => 'Arifin', 'email' => 'arifin@gmail.com', 'username' => 'ipul', 'password' => '$2y$10$a71MQbYvTQ5qFl5u6.OwR.SZ0BCVt5izBduMI4Iadzo0WpOlHXr6G', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 24, 'nama' => 'Nadtasya', 'email' => 'nadtasya@gmail.com', 'username' => 'naong', 'password' => '$2y$10$pl8Rf3Z7sTm8HygPknb4VOlzLicL6LQgXWsRhVTt8UX4A3Ri2YbEm', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 25, 'nama' => 'Julia', 'email' => 'julia@gmail.com', 'username' => 'pramuy', 'password' => '$2y$10$j0/KQIEIX04N0.CfqP7mtOKlF96iTxxAjKbmUxiP6KmFnP2fcN/BO', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 26, 'nama' => 'Brian', 'email' => 'brian@gmail.com', 'username' => 'brewok', 'password' => '$2y$10$q3Uai4ORkmuSKROIcKZIeuSC.eDkI7hhbTc8uCafGBPByA9yiJGUW', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 27, 'nama' => 'Isvander', 'email' => 'isvander@gmail.com', 'username' => 'cakim', 'password' => '$2y$10$uP.ugCmMXZooI8gRnUhg8ueUgrvGOezZVqs3FqV6Ii/JeECr4W7na', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
            ['id_admin' => 28, 'nama' => 'Arya', 'email' => 'arya@gmail.com', 'username' => 'saruto', 'password' => '$2y$10$T1DTiTEK.hl2.Ue/d7gOkOphJWj4lTt7f.0Vwbtr8ieioV0AALoSe', 'remember_token' => null, 'created_at' => '2026-09-14 05:22:48', 'updated_at' => '2026-09-14 05:22:48'],
        ];

        foreach (array_chunk($rows, 250) as $chunk) {
            DB::table('admin')->upsert($chunk, ['id_admin'], ['nama', 'email', 'username', 'password', 'remember_token', 'created_at', 'updated_at']);
        }
    }
}
