<?php

use App\Models\User;


// Counting verified users (excluding 'ADM' users)
$verified = User::where('utype', '!=', 'ADM')
                            ->whereNotNull('email_verified_at')
                            ->count();

// Counting unverified users (excluding 'ADM' users)
$unverified = User::where('utype', '!=', 'ADM')
                              ->whereNull('email_verified_at')
                              ->count();

// Counting users with 'fc2' package who are verified (excluding 'ADM' users)
$fc2 = User::where('utype', '!=', 'ADM')
                       ->whereNotNull('email_verified_at')
                       ->where('has_paid_package', 'fc2')
                       ->count();

// Counting users with 'fc1' package who are verified (excluding 'ADM' users)
$fc1 = User::where('utype', '!=', 'ADM')
                       ->whereNotNull('email_verified_at')
                       ->where('has_paid_package', 'fc1')
                       ->count();

// Counting users with 'standard' package who are verified (excluding 'ADM' users)
$sta = User::where('utype', '!=', 'ADM')
                       ->whereNotNull('email_verified_at')
                       ->where('has_paid_package', 'standard')
                       ->count();

// Counting users with 'ft' package who are verified (excluding 'ADM' users)
$ftt = User::where('utype', '!=', 'ADM')
                       ->whereNotNull('email_verified_at')
                       ->where('has_paid_package', 'ft')
                       ->count();


?>

@extends('admin.sidebar')

@section('contents')
<section>
<div class="px-2">
          <div class="grid grid-cols-2 sm:grid-cols-7 gap-3
        mt-[5rem] container  mx-auto ">
            <div class="py-5 card  border-l-[4px] border-blue-400 bg-white flex  items-center  rounded shadow-sm px-2 justify-between">

                <div>
                <h1 class="text-blue-500 uppercase text-sm  text-center font-semibold">
                    Private Pre-Sale
                </h1>
                <h1 class="text-slate-500 font-bold pt-2">$ 400,000</h1>
                </div>

            <div class="card-body flex items-center px-2">
                <i class="fa-solid fa-landmark text-gray-300 text-3xl drop-shadow-sm"></i>

            </div>
            </div>

            <div class="py-5 card rounded border-l-[4px] border-green-400 bg-white flex  items-center  rounded shadow-sm px-2 justify-between">

            <div>
                <h1 class="text-green-500 uppercase text-sm  text-center font-semibold">
                Pre-Sale
                </h1>
                <h1 class="text-slate-500 font-bold pt-2">$ 403,000</h1>
            </div>

            <div class="card-body flex items-center px-2">
            <i class="fa-solid fa-briefcase text-gray-300 text-3xl drop-shadow-sm"></i>

            </div>
            </div>

            <div class=" py-5 card rounded border-l-[4px] border-teal-400 bg-white flex  items-center  rounded shadow-sm px-2 justify-between">

            <div>
                <h1 class="text-teal-500 uppercase text-sm  text-center font-semibold">
                Sales Package
                </h1>
                <h1 class="text-slate-500 font-bold pt-2">$ 9,000</h1>
            </div>

            <div class="card-body flex items-center px-2">
            <i class="fa-solid fa-box text-gray-300 text-3xl drop-shadow-sm"></i>

            </div>
            </div>

            <div class="py-5 card rounded border-l-[4px] border-blue-400 bg-white flex  items-center  rounded shadow-sm px-2 justify-between">

            <div>
                <h1 class="text-blue-500 uppercase text-sm  text-center font-semibold">
                Liquidity Pool
                </h1>
                <h1 class="text-slate-500 font-bold pt-2">$ 400,000</h1>
            </div>

            <div class="card-body flex items-center px-2">
            <i class="fa-solid fa-credit-card text-gray-300 text-3xl drop-shadow-sm"></i>

            </div>
            </div>

        <div class="py-5 card rounded border-l-[4px] border-blue-400 bg-white flex  items-center  rounded shadow-sm px-2 justify-between">

            <div>
            <h1 class="text-blue-500 uppercase text-sm  text-center font-semibold">
                Staking Pool
            </h1>
            <h1 class="text-slate-500 font-bold pt-2">$ 407,000</h1>
            </div>

        <div class="card-body flex items-center px-2">
            <i class="fa-solid fa-square-poll-vertical text-gray-300 text-3xl drop-shadow-sm"></i>

        </div>
        </div>



        <div class="card  rounded border-l-[4px] border-blue-400 bg-white flex  items-center  rounded shadow-sm px-2 justify-between py-5">

            <div>
            <h1 class="text-blue-500 uppercase text-sm  text-center font-semibold">
                Total Coin Sales
            </h1>
            <h1 class="text-slate-500 font-bold pt-2">$ 5400,000</h1>
            </div>

            <div class="card-body flex items-center px-2">
            <i class="fa-solid fa-coins text-gray-300 text-3xl drop-shadow-sm"></i>

            </div>
        </div>

            <div class="card  rounded border-l-[4px] border-blue-400 bg-white flex  items-center  rounded shadow-sm px-2 justify-between py-5">

            <div>
                <h1 class="text-blue-500 uppercase text-sm  text-center font-semibold">
                Total Supply
                </h1>
                <h1 class="text-slate-500 font-bold pt-2">$12 400,000</h1>
            </div>

            <div class="card-body flex items-center px-2">
            <i class="fa-solid fa-landmark text-gray-300 text-3xl drop-shadow-sm"></i>

            </div>
            </div>



        <!-- <div class="col-span-3 row-span-3">
        <div id="candleStick" class="bg-white py-2 px-2 rounded"></div>
        </div> -->


        <!-- 3 boxes -->

        <!-- <div class="col-md-4">
        <div class="box box1">
            <div id="spark1"></div>
        </div>
        </div>

        <div class="col-md-4">
        <div class="box box2">
            <div id="spark2"></div>
        </div>
        </div>

        <div class="col-md-4">
        <div class="box box3">
            <div id="spark3"></div>
        </div>
        </div> -->


        </div>


          <!-- end of 7 stacked grids -->
          <div class="container my-4  block sm:grid grid-cols-12 gap-2 mx-auto">

          <!-- removed section -->

          <div class="section col-span-8 row-span-2">


            <ul role="list" class="divide-x divide-indigo-400 py-2 bg-indigo-500
            grid grid-cols-2 sm:grid-cols-7  wrap-auto">
              <li class="flex justify-between  py-5 px-2 text-white text-xs">
                <div class="cards">
                  <div class="header flex gap-2">
                      <i class="fa-solid fa-user-check"></i>
                      <span>Balance</span>
                  </div>
                  <h3 class="text-center py-2">$ 362,029,36</h3>
                  <button class="text-xs bg-blue-400  rounded-2xl px-2 py-1">1,774,15% Increase</button>
              </div>
              </li>

              <li class="flex justify-between  py-5 px-3 text-white text-xs">
                <div class="cards">
                  <div class="header flex gap-2 ">
                      <i class="fa-solid fa-user-gear"></i>
                      <span>All Actions</span>
                  </div>
                  <h3 class="text-center p-2">14/$12</h3>
                  <button class="text-xs bg-blue-400  rounded-2xl px-2 py-1">
                    100% Increase</button>
              </div>
              </li>


              <li class="flex justify-between  py-5 px-2 text-white text-xs">
                <div class="cards">
                  <div class="header flex gap-2 ">
                    <i class="fa-solid fa-bullseye"></i>
                      <span>All Clicks</span>
                  </div>
                  <h3 class="text-center py-2">549/$169,55</h3>
                  <button class=" bg-blue-400  rounded-2xl p-1">
                    160,010,0 % Income</button>
              </div>
              </li>

              <li class="flex justify-between  py-5 text-xs px-2 text-white">
                <div class="cards">
                  <div class="header flex gap-2 ">
                    <i class="fa-solid fa-chart-line"></i>
                      <span>Admin Sales</span>
                  </div>
                  <h3 class="text-center py-2">$169,55</h3>
                  <button class=" bg-blue-400  rounded-2xl px-2 py-1">
                    100 % Income</button>
              </div>
              </li>

              <li class="flex justify-between  py-5 text-xs px-2 text-white">
                <div class="cards">
                  <div class="header flex gap-2 ">
                    <i class="fa-solid fa-chart-line"></i>
                      <span>Vendors Sales</span>
                  </div>
                  <h3 class="text-center py-2">$25,483.14</h3>
                  <button class=" bg-blue-400  rounded-2xl px-2 py-1">
                    100 % Income</button>
              </div>
              </li>

              <li class="flex justify-between  col-span-1 sm:col-span-2   py-5 text-xs px-2 text-white">
                <div class="cards">
                  <div class="header   ">
                    <i class="fa-solid fa-scale-unbalanced-flip"></i>
                      <span>Total Online</span>
                  </div>

                <div class="online grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div>
                        <h4 class="py-2">Admin</h4>
                        <p class="text-center">3</p>
                    </div>
                    <div>
                      <h4 class="py-2">Affiliate</h4>
                      <p class="text-center">3</p>
                  </div>
                  <div>
                    <h4 class="py-2">Vendor</h4>
                    <p class="text-center">3</p>
                  </div>
                  <div>
                    <h4 class="py-2">Client</h4>
                    <p class="text-center">300</p>
                </div>

                </div>
              </div>
              </li>






            </ul>

            <ul role="list" class=" px-3  py-2 bg-indigo-200 block sm:grid grid-cols-3 gap-3">

              <li class="  bg-white py-5 px-2  text-xs  my-2 lg:my-auto">
                <div class="cards">
                  <div class="header flex gap-2 pb-2">
                      <h3 class="font-bold uppercase">Total Deposit</h3>
                  </div>
                  <div class="middle flex justify-between gap-3 w-full items-center pb-2 ">
                    <h2 class="text-lg">49,595.34 USD</h2>
                      <div class="indicator flex gap-1 text-green-500 font-bold">
                        <p>
                          <i class="fa-solid fa-arrow-up text-xs "></i>
                          <i class="fa-solid fa-arrow-up text-xs "></i>
                        </p>
                        <p>93%</p>
                      </div>
                  </div>
                  <div class="down flex gap-2 uppercase items-end justify-between">
                      <div>
                        <h2 class="pb-1 font-semibold">This Moth</h2>
                        <p class="text-slate-600 text-bold">2,940.59 USD</p>
                      </div>
                      <div>
                        <h2 class="pb-1 font-semibold">This Week</h2>
                        <p class="text-slate-600 text-bold">1,259.28 USD</p>
                      </div>
                      <div class="simple-chart  flex justify-center ">
                        <div id="deposit-chart-1"></div>
                      </div>
                  </div>
              </div>
              </li>

              <li class="  bg-white py-5 px-2  text-xs my-2 lg:my-auto">
                <div class="cards">
                  <div class="header flex gap-2 pb-2">
                      <h3 class="font-bold">Total Deposit</h3>
                  </div>
                  <div class="middle flex justify-between gap-3 w-full items-center pb-2 ">
                    <h2 class="text-lg">49,595.34 USD</h2>
                      <div class="indicator flex gap-1 text-green-500 font-bold">
                        <p>
                          <i class="fa-solid fa-arrow-up text-xs "></i>
                          <i class="fa-solid fa-arrow-up text-xs "></i>
                        </p>
                        <p>93%</p>
                      </div>
                  </div>
                  <div class="down flex gap-2 uppercase items-end justify-between">
                      <div>
                        <h2 class="pb-1 font-semibold">This Moth</h2>
                        <p class="text-slate-600 text-bold">2,940.59 USD</p>
                      </div>
                      <div>
                        <h2 class="pb-1 font-semibold">This Week</h2>
                        <p class="text-slate-600 text-bold">1,259.28 USD</p>
                      </div>
                      <div class="simple-chart  flex justify-center ">
                        <div id="deposit-chart-2"></div>
                    </div>
                  </div>
              </div>
              </li>


              <li class="  bg-white py-5 px-2  text-xs">
                <div class="cards">
                  <div class="header flex gap-2 pb-2">
                      <h3 class="font-bold">Total Deposit</h3>
                  </div>
                  <div class="middle flex justify-between gap-3 w-full items-center pb-2 ">
                    <h2 class="text-lg">49,595.34 USD</h2>
                      <div class="indicator flex gap-1 text-red-500 font-bold">
                        <p>
                          <i class="fa-solid fa-arrow-down text-xs "></i>
                          <i class="fa-solid fa-arrow-down text-xs "></i>
                        </p>
                        <p>93%</p>
                      </div>
                  </div>
                  <div class="down flex gap-2 uppercase items-end justify-between">
                      <div>
                        <h2 class="pb-1 font-semibold">This Moth</h2>
                        <p class="text-slate-600 text-bold">2,940.59 USD</p>
                      </div>
                      <div>
                        <h2 class="pb-1 font-semibold">This Week</h2>
                        <p class="text-slate-600 text-bold">1,259.28 USD</p>
                      </div>
                      <div class="simple-chart  flex justify-center ">
                        <div id="deposit-chart-3"></div>
                    </div>
                  </div>
              </div>
              </li>








            </ul>

        </div>
        <!-- section 2 -->

        <div class="section col-span-4 grid grid-cols-2 gap-2 ">

          <div class="card rounded-none bg-gray-800 my-2 lg:m-0 ">
            <div class="bg-gray-700">
              <h1 class="text-gray-200 py-2 py-3 text-center font-bold">LOAN</h1>
            </div>
            <div class="card-body px-2 flex gap-2 justify-between items-center py-4">
              <div>
                <h3 class="text-red-500 uppercase ">Balance</h3>
              <p class="text-gray-300">$3600000</p>
              </div>
              <div>
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>

          <div class="card rounded-none bg-gray-800 my-2 lg:m-0">
            <div class="bg-gray-700">
              <h1 class="text-gray-200 py-2 py-3 text-center font-bold uppercase">Marketing</h1>
            </div>
            <div class="card-body px-2 flex gap-2 justify-between items-center py-4">
              <div>
                <h3 class="text-red-500 uppercase ">Balance</h3>
              <p class="text-gray-300">$36000.00</p>
              </div>
              <div>
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>



          <div class="card rounded-none bg-gray-800 my-2 lg:m-0">
            <div class="bg-gray-700">
              <h1 class="text-gray-200   text-center font-bold">PM</h1>
            </div>
            <div class="card-body px-2 flex flex-col gap-2 py-2">
              <div>
                <h3 class="text-red-500 uppercase ">Balance</h3>
                <p class="text-gray-300">$3600000</p>
              </div>
              <div>
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>

          <div class="card rounded-none bg-gray-800 my-2 lg:m-0">
            <div class="bg-gray-700">
              <h1 class="text-gray-200   text-center font-bold uppercase">Merchant</h1>
            </div>
            <div class="card-body px-2 flex flex-col  gap-2 py-2 col-span-3">
              <div>
                <h3 class="text-red-500 uppercase ">Balance</h3>
                <p class="text-gray-300">$3600000</p>
              </div>
              <div>
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>

        </div>


        <div class="section col-span-12 grid grid-cols-2 sm:grid-cols-5 gap-3">
          <div class="card rounded-none bg-gray-800">
            <div class="bg-gray-700">
              <h1 class="text-gray-200 py-2 py-3 text-center font-bold">FOMO</h1>
            </div>
            <div class="card-body px-2 flex gap-2 justify-between items-center py-2">
              <div>
                <h3 class="text-red-500 uppercase ">Balance</h3>
              <p class="text-gray-300">$3600000</p>
              </div>
              <div class="py-2">
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>

          <div class="card rounded-none bg-gray-800">
            <div class="bg-gray-700">
              <h1 class="text-gray-200 py-2 py-3 text-center font-bold">Reward</h1>
            </div>
            <div class="card-body px-2 flex gap-2 justify-between items-center py-2">
              <div class="py-2">
                <h3 class="text-red-500 uppercase ">Balance</h3>
              <p class="text-gray-300">$3600000</p>
              </div>
              <div class="py-2">
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>

          <div class="card rounded-none bg-gray-800">
            <div class="bg-gray-700">
              <h1 class="text-gray-200 py-2 py-3 text-center font-bold">Team</h1>
            </div>
            <div class="card-body px-2 flex gap-2 justify-between items-center py-2">
              <div class="py-2">
                <h3 class="text-red-500 uppercase ">Balance</h3>
              <p class="text-gray-300">$3600000</p>
              </div>
              <div>
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>

          <div class="card rounded-none bg-gray-800">
            <div class="bg-gray-700">
              <h1 class="text-gray-200 py-2 py-3 text-center font-bold">Distribution</h1>
            </div>
            <div class="card-body px-2 flex gap-2 justify-between items-center py-2">
              <div class="py-2">
                <h3 class="text-red-500 uppercase ">Balance</h3>
              <p class="text-gray-300">$3600000</p>
              </div>
              <div>
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>

          <div class="card rounded-none bg-gray-800">
            <div class="bg-gray-700">
              <h1 class="text-gray-200 py-2 py-3 text-center font-bold">Exchange</h1>
            </div>
            <div class="card-body px-2 flex gap-2 justify-between items-center py-2">
              <div class="py-2">
                <h3 class="text-red-500 uppercase ">Balance</h3>
              <p class="text-gray-300">$3600000</p>
              </div>
              <div>
                <p class="text-yellow-400">Sold: <span>000</span></p>
                <p class="text-cyan-400">Remain: <span>0000</span></p>
              </div>
            </div>
          </div>
        </div>



        <!-- 3 -->





        <!-- 4 -->



        <!-- REST cards -->



        <!-- second part -->

        <div class="section col  col-span-8 ">
          <div class=" my-2 bg-gray-50 py-2 justify-center grid grid-cols-3 gap-1 sm:grid-cols-6 px-2">
            <!-- verified users -->

            <a href="{{route('admin.verify')}}">
            <div class="card bg-teal-500 py-3  px-2 rounded text-white  text-center w-10/12 mx-auto ">
                  <div class="top py-1 flex items-center justify-center">

                <i class="fa-solid fa-user-check bg-teal-600 p-2 h-10 w-10 flex items-center justify-center rounded-full text-center "  ></i>
              </div>
              <div class="content">
                  <h3 class="uppercase">Veryfied users</h3>
                  <p>{{$verified}}</p>
              </div>
          </div>
          </a>


          <!-- pending users -->

          <a href="{{route('admin.pending')}}">
          <div class="card bg-green-500 py-3  px-2 rounded text-white  text-center w-10/12 mx-auto ">
            <div class="top py-1 flex items-center justify-center">
            <i class="fa-solid fa-user-check bg-green-600 p-2 h-10 w-10 flex items-center justify-center rounded-full text-center "  ></i>
            </div>
            <div class="content">
                <h3 class="uppercase">Pending users</h3>
                <p>{{$unverified}}</p>
            </div>
          </div>
          </a>

          <!-- projects -->


          <div class="card bg-blue-500 py-3  px-2 rounded text-white  text-center w-10/12 mx-auto ">
            <div class="top py-1 flex items-center justify-center">
            <i class="fa-solid fa-user-check bg-blue-600 p-2 h-10 w-10 flex items-center justify-center rounded-full text-center "  ></i>
            </div>
            <div class="content">
                <h3 class="uppercase">Projects</h3>
                <p>0.0</p>
            </div>
          </div>

          <!-- FC 100 -->
          <div class="card bg-red-500 py-3  px-2 rounded text-white  text-center w-10/12 mx-auto ">
          <a href="{{route('admin.fc1')}}">
            <div class="top py-1 flex items-center justify-center">
            <i class="fa-solid fa-user-check bg-red-600 p-2 h-10 w-10 flex items-center justify-center rounded-full text-center "  ></i>
            </div>
            <div class="content">
                <h3 class="uppercase">Fc $100</h3>
                <p>{{$fc1}}</p>
            </div>
        </a>
          </div>

          <!-- f200 -->

          <div class="card bg-yellow-500 py-3  px-2 rounded text-white  text-center w-10/12 mx-auto ">
          <a  href="{{route('admin.fc2')}}">
            <div class="top py-1 flex items-center justify-center">
            <i class="fa-solid fa-user-check bg-yellow-600 p-2 h-10 w-10 flex items-center justify-center rounded-full text-center "  ></i>
            </div>
            <div class="content">
                <h3 class="uppercase">Fc $200</h3>
                <p>{{$fc2}}</p>
            </div>
            </a>
          </div>

          <!--  ft worker-->

          <div class="card bg-green-400 py-3  px-2 rounded text-white  text-center  ">
          <a href="{{route('admin.ft')}}">
            <div class="top py-1 flex items-center justify-center">
            <i class="fa-solid fa-user-check bg-green-600 p-2 h-10 w-10 flex items-center justify-center rounded-full text-center "  ></i>
            </div>
            <div class="content">
                <h3 class="uppercase">FT worker</h3>
                <p>{{$ftt}}</p>
            </div>
            </a>
          </div>



          </div>

            <div class="12grids grid  grid-cols-2 sm:grid-cols-4 gap-2
            overflow-hidden">
            <!-- share holders -->
              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-indigo-800 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-50 uppercase py-2">
                          Total Shareholder</h1>

                        <p class="text-white">0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-10">
                    <i class="fa-solid fa-user-check
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>
            <!-- deposited -->

            <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-red-700 relative flex justify-between">

                    <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                    justify-center absolute w-full text-center" >
                        <div>
                            <h1 class="text-slate-100 uppercase py-2">Deposit</h1>

                            <p class="text-white uppercase">USD 0</p>
                        </div>
                    </div>

                    <div></div>
                    <div class="self-center px-2 opacity-10">
                      <i class="fa-solid fa-landmark
                    p-2 h-10 w-10 flex items-center
                      justify-center rounded-full
                      text-center text-[3rem] text-white "  ></i>

                    </div>


              </div>




              <!-- withdraw -->

              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-lime-500 relative flex justify-between">

              <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
              justify-center absolute w-full text-center" >
                  <div>
                      <h1 class="text-slate-100 uppercase py-2">withdraw</h1>

                      <p class="text-white uppercase">usd 0</p>
                  </div>
              </div>

                <div></div>
                <div class="self-center px-2 opacity-10">
                  <i class="fa-solid fa-user-check
                  p-2 h-10 w-10 flex items-center
                  justify-center rounded-full
                  text-center text-[3rem] text-white "  ></i>

                </div>


          </div>



              <!-- token sold -->

              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-emerald-800 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-100 uppercase py-2">Token Sold</h1>

                        <p class="text-white uppercase">dms 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-10">
                    <i class="fa-solid fa-coins
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>

              <!-- token -->


              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-teal-600 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-50 uppercase py-2">Token</h1>

                        <p class="text-white uppercase">DMC 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-10">
                    <i class="fa-solid fa-user-check
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>



              <!-- total Fees -->

              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-gray-700 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-100 uppercase py-2">Total Fees</h1>

                        <p class="text-slate-100">USD 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-10">
                    <i class="fa-solid fa-user-check
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>


              <!--  total investment -->

              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-cyan-500 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-50 uppercase py-2">
                          Total investment</h1>

                        <p class="text-slate-100 uppercase">USD 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-20">
                    <!-- <i class="fa-solid "></i> -->
                    <!-- <i class="fa-solid fa-briefcase"></i> -->

                    <i class="fa-solid fa-briefcase
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>


              <!-- total ROI -->

              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-yellow-600 relative flex justify-between">

              <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
              justify-center absolute w-full text-center" >
                  <div>
                      <h1 class="text-slate-50 uppercase py-2">Total Roi</h1>

                      <p class="text-white">USD 0</p>
                  </div>
              </div>

                <div></div>
                <div class="self-center px-2 opacity-10">
                  <i class="fa-solid fa-chart-pie
                  p-2 h-10 w-10 flex items-center
                  justify-center rounded-full
                  text-center text-[3rem] text-white "  ></i>

                </div>


            </div>


            <!-- secured inv -->

            <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-gray-500 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-50 uppercase py-2">Secured investment</h1>

                        <p class="text-white text-slate-100 uppercase">Usd 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-10">
                    <i class="fa-solid fa-briefcase
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>


              <!-- your custom inv -->


              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-fuchsia-800 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-50 uppercase py-2">Your Custom Inv</h1>

                        <p class="text-white text-slate-100 uppercase">USD 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-10">
                    <i class="fa-solid fa-briefcase
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>


              <!-- secured Roi -->


              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-amber-700 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-50 uppercase py-2">Secured Roi</h1>

                        <p class="text-white uppercase">usd 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-10">
                    <i class="fa-solid fa-chart-pie
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-white "  ></i>

                  </div>


              </div>


              <!-- Guarented -->

              <div class=" px-2 h-[5.5rem] overflow-hidden
              rounded bg-rose-400 relative flex justify-between">

                <div class="z-28 top-1/2 left-1/2 -translate-x-1/2  -translate-y-1/2  flex items-center
                justify-center absolute w-full text-center" >
                    <div>
                        <h1 class="text-slate-50 uppercase py-2">Guaranted Roi</h1>

                        <p class="text-slate-100 uppercase">Usd 0</p>
                    </div>
                </div>

                <div></div>
                  <div class="self-center px-2 opacity-5">
                    <i class="fa-solid fa-chart-pie
                  p-2 h-10 w-10 flex items-center
                    justify-center rounded-full
                    text-center text-[3rem] text-gray-600 "  ></i>

                  </div>


              </div>

            </div>

        </div>


        <!-- chart part -->

        <div class="section chart col-span-4 py-2">

          <div class="card bg-white p-2 rounded">
              <div id="line-chart"></div>
          </div>
          <div class="bg-white p-2 rounded my-2">
            <div id="sales-report-chart"></div>
          </div>

        </div>





          <div class="ch col-span-12 grid grid-cols-2 sm:grid-cols-6 gap-2">
                  <!-- messaing -->

                <div class="bg-white col-span-2 px-2">

                  <ul role="list" class="divide-y divide-gray-100">
                    <li class="flex justify-between gap-x-6 py-5">
                      <div class="flex  gap-x-4 font-bold text-slate-500">
                      <h1>Support Request</h1>
                      </div>
                      <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end ">
                        <a href="#" class="font-bold text-blue-500">All Request</a>
                      </div>
                    </li>
                    <li class="flex justify-between gap-x-6 py-5">
                      <div class="flex  gap-x-4">
                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <div class="min-w-0 flex-auto">
                          <p class="text-sm font-semibold leading-6 text-gray-900">Leslie Alexander</p>
                          <p class="mt-1 truncate text-xs leading-5 text-gray-500">Thank you for contacting us with your issue..</p>
                          <p class="mt-1 text-xs leading-5 text-gray-500">Last seen <time datetime="2023-01-23T13:23Z">3h ago</time></p>

                        </div>
                      </div>
                      <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end w-20">
                        <i class="fa-solid fa-envelope-circle-check text-md leading-6 text-green-700"></i>

                      </div>
                    </li>
                    <li class="flex justify-between gap-x-6 py-5">
                      <div class="flex  gap-x-4">
                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <div class="min-w-0 flex-auto">
                          <p class="text-sm font-semibold leading-6 text-gray-900">Michael Foster</p>
                          <p class="mt-1 truncate text-xs leading-5 text-gray-500">Thank you for contacting us with your issue..</p>
                          <p class="mt-1 text-xs leading-5 text-gray-500">Last seen <time datetime="2023-01-23T13:23Z">3h ago</time></p>
                        </div>
                      </div>
                      <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end w-20">
                        <i class="fa-solid fa-envelope-open text-md leading-6 text-slate-400"></i>

                      </div>
                    </li>

                    <li class="flex justify-between gap-x-6 py-5">
                      <div class="flex min-w-0 gap-x-4">
                        <img class="h-12 w-12 flex-none rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                        <div class="min-w-0 flex-auto">
                          <p class="text-sm font-semibold leading-6 text-gray-900">Dries Vincent</p>
                          <p class="mt-1 truncate text-xs leading-5 text-gray-500">Thank you for contacting us with your issue..</p>
                          <!-- <p class="mt-1 text-xs leading-5 text-gray-500">Last seen <time datetime="2023-01-23T13:23Z">3h ago</time></p> -->
                        </div>
                      </div>
                      <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                        <i class="fa-solid fa-envelope-open text-md leading-6 text-slate-400"></i>
                        <div class="mt-1 flex items-center gap-x-1.5">
                          <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                            <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                          </div>
                          <p class="text-xs leading-5 text-gray-500">Online</p>
                        </div>
                      </div>
                    </li>



                  </ul>

                </div>

                <div class="col-span-2">


                  <div class="bg-white py-2 px-2">
                    <div class="px-2">
                      <h1 class="text-gray-500 text-lg font-semibold pb-1 uppercase">Website Performance</h1>
                      <p class="text-sm">How it has performed this Month</p>
                    </div>
                    <ul role="list" class="divide-y divide-gray-100 ">

                      <li class="flex justify-between gap-x-6 py-2">
                        <div class="flex-auto">
                            <div id="spark1" ></div>
                          </div>

                        <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end self-end">
                          <h1>0%</h1>
                          <p class="text-xs font-semibold uppercase"> 0%</p>
                          <p class="text-xs font-semibold uppercase">Vs last Month</p>
                        </div>
                      </li>


                      <li class="flex justify-between gap-x-6 py-2">
                        <div class="flex-auto">
                          <div id="spark2" ></div>
                        </div>

                      <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end self-end">
                        <h1>0%</h1>
                        <p class="text-xs font-semibold uppercase"> 0%</p>
                        <p class="text-xs font-semibold uppercase">Vs last Month</p>
                      </div>
                    </li>


                    <li class="flex justify-between gap-x-6 py-2">
                      <div class="flex-auto">
                        <div id="spark3" ></div>
                      </div>

                    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end self-end">
                      <h1>0%</h1>
                      <p class="text-xs font-semibold uppercase"> 0%</p>
                      <p class="text-xs font-semibold uppercase">Vs last Month</p>
                    </div>
                  </li>


                    </ul>
                  </div>


                </div>

                <div class="col-span-2 bg-white py-2 px-2 rounded">
                  <div class="top">
                      <h1 class="font-semibold text-gray-500 text-lg">INVESTIMENT OVERVIEW</h1>
                      <p class="text-xs text-slate-500">Lorem ipsum dolor sit, amet consectetur </p>
                      <div class="stack flex gap-2 py-2 ">
                        <button class="py-2 px-2 text-indigo-500 font-semibold">Overview</button>
                        <button class="py-2 px-2 ">This Year</button>
                        <button class="py-2 px-2 ">All Time</button>
                      </div>
                  </div>

                  <ul role="list" class="divide-y divide-gray-100">

                    <li class="flex justify-between gap-x-6 py-5">
                      <div class="flex  gap-x-4">

                        <div class="min-w-0 flex-auto">
                          <p class="text-sm font-semibold leading-6 text-gray-900 uppercase">Currently Activated Investment</p>
                          <div class="py-1">
                            <h1 class="font-bold text-slate-600">0 USD</h1>
                            <p class="font-semibold text-xs  uppercase">Amount</p>
                          </div>
                          <div class="py-1">
                            <h1 class="font-bold text-slate-600">0 USD</h1>
                            <p class="font-semibold text-xs  uppercase">Paid Amount</p>
                          </div>
                        </div>
                      </div>
                      <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end self-center">
                        <div class="flex gap-2">
                          <h1 class="text-gray-500 font-bold">0</h1>
                            <div class="flex text-green-500 text-sm font-bold items-center justify-between gap-1">
                              <i class="fa-solid fa-arrow-up text-xs self-center"></i>
                              <i class="fa-solid fa-arrow-up text-xs self-center"></i>
                              <p>0 %</p>
                            </div>
                        </div>
                        <p class="uppercase text-gray-600 text-xs">Plans</p>
                      </div>
                    </li>

                    <li class="flex justify-between gap-x-6 py-5">
                      <div class="flex  gap-x-4">

                        <div class="min-w-0 flex-auto">
                          <p class="text-sm font-semibold leading-6 text-gray-900 uppercase">Investment in this month</p>
                          <div class="py-1">
                            <h1 class="font-bold text-slate-600">0 USD</h1>
                            <p class="font-semibold text-xs  uppercase">Amount</p>
                          </div>

                        </div>
                      </div>
                      <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end self-center">
                        <div class="flex gap-2">
                          <h1 class="text-gray-500 font-bold">0</h1>
                            <div class="flex text-red-500 text-sm font-bold items-center justify-between gap-1">
                              <i class="fa-solid fa-arrow-down text-xs self-center"></i>
                              <i class="fa-solid fa-arrow-down text-xs self-center"></i>
                              <p>0 %</p>
                            </div>
                        </div>
                        <p class="uppercase text-gray-600 text-xs">Plans</p>
                      </div>
                    </li>




                    </ul>

                </div>

          </div>

          <div class="ch col-span-12">

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                  <!-- investment plan -->


                  <div class="bg-white px-2">
                      <div class="flex pt-2 gap-2 justify-between px-2  items-center">
                          <h1 class="uppercase text-lg">Sessions By Devices</h1>
                          <select class="outline-none border border-gray-200 rounded px-2 py-2">
                                <option>Select range</option>
                                <option value="30" selected>30 Days</option>
                                <option value="60" selected>2 Month</option>
                                <option>Yesterday</option>
                                <option>To day</option>
                          </select>
                      </div>
                      <div id="donut"></div>
                  </div>

                  <div class=" text-xs col-span-1  sm:col-span-2 sm:flex justify-center items-center bg-white">
                    <div class="bg-white p-2 relative overflow-hidden ">
                      <h1 class="py-2 font-bold uppercase">Internet Visibility</h1>
                      <table class="border-collapse border border-slate-400">
                        <thead>
                          <th class="p-2 border border-slate-300 uppercase text-sm ">Channel</th>
                          <th class="p-2 border border-slate-300 uppercase text-sm ">Sessions</th>
                          <th class="p-2 border border-slate-300 uppercase text-sm ">Prev Sessions</th>
                          <th class="p-2 border border-slate-300 uppercase text-sm ">Change</th>
                          <th class="p-2 border border-slate-300 uppercase text-sm ">Trend</th>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="border border-slate-300  p-2">Organic Search</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">
                              <h2  class="text-xs font-bold">0% <i class="fa-solid fa-arrow-up text-green-500"></i></h2>
                            </td>
                            <td class="border border-slate-300  p-2">
                                <div id="chart-1"></div>
                            </td>

                          </tr>
                          <tr>
                            <td class="border border-slate-300  p-2">Social Media</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">
                              <h2  class="text-xs font-bold">0% <i class="fa-solid fa-arrow-up text-green-500"></i></h2>
                            </td>
                            <td class="border border-slate-300  p-2">
                              <div id="chart-2"></div>
                            </td>

                          </tr>
                          <tr>
                            <td class="border border-slate-300  p-2">Referrals</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">
                              <h2  class="text-xs font-bold">0% <i class="fa-solid fa-arrow-down text-red-500"></i></h2>
                            </td>
                            <td class="border border-slate-300  p-2">
                              <div id="chart-3"></div>
                            </td>

                          </tr>
                          <tr>
                            <td class="border border-slate-300  p-2">Others</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">0</td>
                            <td class="border border-slate-300  p-2">
                              <h2  class="text-xs font-bold">0% <i class="fa-solid fa-arrow-down text-red-500"></i></h2>
                            </td>

                            <td class="border border-slate-300  p-2">
                              <div id="chart-4"></div>
                            </td>

                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="bg-white">
                    <ul role="list" class="divide-y divide-gray-100 px-3">
                      <li class="flex justify-between gap-x-6 py-5">
                        <div class="flex   font-bold text-slate-500">
                        <h1 class="uppercase  ">Recent Activities</h1>
                        </div>
                        <div class="hidden shrink-0 sm:flex gap-2 px-2 sm:items-end ">
                          <a href="#" class="font-bold text-gray-500">Cancel</a>
                          <a href="#" class="font-bold text-blue-500">All</a>
                        </div>
                      </li>
                      <li class="flex justify-between gap-x-6 py-3">
                        <div class="flex  gap-x-4">
                          <img class="h-8 w-8 flex-none rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                          <div class="min-w-0 flex-auto">

                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">Leslie Alexander Requested To withdraw</p>
                            <p class="mt-1 text-xs leading-5 text-gray-500"><time datetime="2023-01-23T13:23Z">3h ago</time></p>

                          </div>
                        </div>

                      </li>
                      <li class="flex justify-between gap-x-6 py-3">
                        <div class="flex  gap-x-4">
                          <img class="h-8 w-8 flex-none rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                          <div class="min-w-0 flex-auto">

                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">Leslie Alexander Requested To withdraw</p>
                            <p class="mt-1 text-xs leading-5 text-gray-500"><time datetime="2023-01-23T13:23Z">3h ago</time></p>

                          </div>
                        </div>

                      </li>
                      <li class="flex justify-between gap-x-6 py-3">
                        <div class="flex  gap-x-4">
                          <img class="h-8 w-8 flex-none rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
                          <div class="min-w-0 flex-auto">

                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">Leslie Alexander Requested To withdraw</p>
                            <p class="mt-1 text-xs leading-5 text-gray-500"><time datetime="2023-01-23T13:23Z">3h ago</time></p>

                          </div>
                        </div>

                      </li>

                      </ul>
                  </div>


                      <div class="box shadow bg-white">
                        <div id="bar"></div>
                      </div>


                      <div class="box shadow bg-white">
                        <div id="donutTop"></div>
                      </div>


                      <div class="box shadow bg-white px-2 py-2">
                          <div class="top-header flex px-2 justify-between items-top">
                              <div>
                                <h2 class="uppercase font-bold text-slate-600">Top Invested Plan</h2>
                                <p class="py-1 text-sm text-slate-500">Atleast 20 days of top invested plans</p>
                              </div>
                              <i></i>
                          </div>
                          <ul role="list" class="py-2">

                            <li class="px-2  text-xs  lg:my-auto pb-4">
                              <div class="plans ">
                                <div class="top flex justify-between px-1">
                                  <h3 class="text-slate-500 font-semibold uppercase">Starter Plan</h3>
                                  <h3 class="text-slate-500 font-semibold uppercase">45%</h3>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 45%"> 45%</div>
                              </div>
                            </div>
                            </li>

                            <li class="px-2  text-xs  lg:my-auto pb-4">
                              <div class="plans ">
                                <div class="top flex justify-between px-1">
                                  <h3 class="text-slate-500 font-semibold uppercase">Sliver Plan</h3>
                                  <h3 class="text-slate-500 font-semibold uppercase">15%</h3>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-pink-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 15%"> 15%</div>
                              </div>
                            </div>
                            </li>

                            <li class="px-2  text-xs  lg:my-auto pb-4">
                              <div class="plans ">
                                <div class="top flex justify-between px-1">
                                  <h3 class="text-slate-500 font-semibold uppercase">Oriented Plan</h3>
                                  <h3 class="text-slate-500 font-semibold uppercase">35%</h3>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-amber-400 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 35%"> 35%</div>
                              </div>
                            </div>
                            </li>


                            <li class="px-2  text-xs  lg:my-auto pb-4">
                              <div class="plans ">
                                <div class="top flex justify-between px-1">
                                  <h3 class="text-slate-500 font-semibold uppercase">Premium Plan</h3>
                                  <h3 class="text-slate-500 font-semibold uppercase">38%</h3>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-pink-500 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 38%"> 38%</div>
                              </div>
                            </div>
                            </li>

                            <li class="px-2  text-xs  lg:my-auto pb-4">
                              <div class="plans ">
                                <div class="top flex justify-between px-1">
                                  <h3 class="text-slate-500 font-semibold uppercase">Vibranium Plan</h3>
                                  <h3 class="text-slate-500 font-semibold uppercase">22%</h3>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-blue-400 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" style="width: 22.5%"> 22.5%</div>
                              </div>
                            </div>
                            </li>







                      </div>

                      <div class="box shadow bg-white">
                        <div id="radialBar1"></div>
                      </div>



                          <div class="box box1 bg-slate-900 py-2 px-3 shadow">
                            <div id="spark10"></div>
                          </div>


                          <div class="box box2 bg-slate-900 py-2 px-3 shadow">
                            <div id="spark20"></div>
                          </div>




                          <div class="box box3 bg-slate-900 py-2 px-3 shadow">
                            <div id="spark30"></div>
                          </div>


                          <div class="box box4 bg-slate-900 py-2 px-3 shadow">
                            <div id="spark40"></div>
                          </div>






            </div>




          </div>




          </div>


        </div>

</section>
@endsection
