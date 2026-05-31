<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

$user = Auth::user();
$name = $user->name;
$ref_code = $user->activation;


?>


 <?php $results=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL");
     $verified=count($results);
      $result=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NULL");
     $unverified=count($result);
     
     $f2=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='fc2'");
     $fc2=count($f2);
      $f1=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS  NOT NULL and has_paid_package='fc1'");
     $fc1=count($f1);
           $std=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='standard'");

     $sta=count($std);
      
           $ft=DB::select("SELECT * from users where utype != 'ADM' and email_verified_at IS NOT NULL and has_paid_package='ft'");
     $ftt=count($ft);
     ?>

@extends('admin.sidebar')

@section('contents')
<section>

<div class="content-wrapper right-side p-4">
    <?php $messages = DB::select("SELECT * from contacteds"); ?>
    <div class="table-container rounded shadow-lg bg-white">
        <div class="pt-3 border-b">
            <p class="text-center font-bold text-xl mb-2">Messages</p>
        </div>

        <div class="overflow-x-auto">
            @if(count($messages))
                <table class="min-w-full bg-white border border-gray-200 text-center">
                    <thead class="bg-gradient-to-r from-blue-500 to-blue-700 text-white">
                        <tr>
                            <th class="py-2 px-4 border-r">#</th>
                            <th class="py-2 px-4 border-r">Names</th>
                            <th class="py-2 px-4 border-r">Surname</th>
                            <th class="py-2 px-4 border-r">Email</th>
                            <th class="py-2 px-4 border-r">Phone</th>
                            <th class="py-2 px-4">Messages</th>
                        </tr>
                    </thead>

                    <?php $n = 1; foreach ($messages as $row): ?>
                    <tbody>
                        <tr class="border-t">
                            <td class="py-2 px-4 border-r">{{ $n++ }}</td>
                            <td class="py-2 px-4 border-r">{{ $row->name }}</td>
                            <td class="py-2 px-4 border-r">{{ $row->sur }}</td>
                            <td class="py-2 px-4 border-r">{{ $row->email }}</td>
                            <td class="py-2 px-4 border-r">{{ $row->phone }}</td>
                            <td class="py-2 px-4">{{ $row->message }}</td>
                        </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
            @else
                <p class="text-center py-4">No messages yet</p>
            @endif
        </div>
    </div>
</div>

</section>
     @endsection


