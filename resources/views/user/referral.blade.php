{{-- pkl-v1 (§76 FOM-aware package labels) --}}
<div class="wrapper">
    @include('user.user-dashboard-base')
    
    <div class="content-wrapper" style="">   
        <section class="content">
            <div class="container-fluid">
                @php
                    $user = Auth::user();
                    $name = $user->user;
                    $email = $user->email;
                    $activated = $user->has_paid_package;
                    $ref_code = $user->activation;
                    $totalReferrals = 0;
                    $baseUrl =  url('/');
                @endphp



<!-- navigation -->
<div class="d-flex flex-wrap justify-content-center mt-4">
    <ul class="list-unstyled resource-list d-flex flex-wrap justify-content-center m-0 p-0">
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-link mr-2"></i>Referral Banners & Link
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-share-alt mr-2"></i>Social Media Images
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-file-pdf mr-2"></i>Presentation PDF
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-trophy mr-2"></i>Ranks & Reward PDF
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-user-tie mr-2"></i>CEO PDF
            </a>
        </li>
        <li class="resource-item px-2 mb-2">
            <a href="#" class="btn btn-outline-primary text-left d-flex align-items-center bg-white">
                <i class="las la-play-circle mr-2"></i>Video
            </a>
        </li>
    </ul>
</div>




<!-- back the first cards -->
<div class="row mt-4">
                    <!-- Left Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="card-header bg-success  p-3" >
                      <span class="mb-0 text-white"><i class="las la-users"></i> Left Referral</span>
                              </div>

                            <div class="card-body">
                                <div class="referral-link-container">
                                 <h6>Left Referral Link</h6>
                                    <div class="input-group mb-3">
                                        @if($activated != 'standard')
                                            <input type="text" class="form-control" id="leftRefLink" 
                                            value="{{$baseUrl}}/register?referral={{$ref_code}}&side=LEFT"

                                                 readonly>
                                        @else
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        @endif
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary copy-btn" type="button" onclick="copyToClipboard('leftRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>                                      
                                    </div>
                                    <div>
                                <h6><i class="las la-users"></i> Left Referrals: {{$left_referrals->count()}}</h6>
                                <h6><i class="las la-coins"></i> Left Earning Coins: {{$leftEarnings ?? 0}}</h6>
                                        </div>
                                </div>
                                
                            
                            </div>
                        </div>
                    </div>

                    <!-- Right Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="card-header bg-primary p-3" >
                      <span class="mb-0"><i class="las la-users"></i> Right Referral</span>
                              </div>
                            <div class="card-body">
                                <div class="referral-link-container">
                                <h6>Right Referral Link</h6>
                                    <div class="input-group mb-3">
                                        @if($activated != 'standard')
                                            <input type="text" class="form-control" id="rightRefLink" 
                                            value="{{$baseUrl}}/register?referral={{$ref_code}}&side=RIGHT"

                                                readonly>
                                        @else
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        @endif
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                <h6><i class="las la-users"></i> Right Referrals: {{$right_referrals->count()}}</h6>
                                <h6><i class="las la-coins"></i> Right Earning Coins: {{$leftEarnings ?? 0}}</h6>
                                        </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- Referred By (Referrer) Card --}}
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                    <div class="card-body p-4 d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <span class="badge badge-warning text-dark font-weight-bold text-uppercase px-2.5 py-1 mb-2" style="border-radius: 4px; font-size: 0.72rem;">
                                <i class="fas fa-user-check mr-1"></i> Your Referrer (Referred By)
                            </span>
                            @if(isset($referrerUser) && $referrerUser)
                                <h4 class="font-weight-bold text-white mb-1" style="font-size: 1.3rem;">
                                    {{ $referrerUser->name }} <span class="text-warning small font-weight-normal">(@ {{ $referrerUser->user }})</span>
                                </h4>
                                <div class="text-light small opacity-90">
                                    Email: <strong>{{ $referrerUser->email }}</strong> · Transfer Code: <strong>{{ $referrerUser->getTransferCode() }}</strong> · Joined: <strong>{{ $referrerUser->created_at ? $referrerUser->created_at->format('d M Y') : 'N/A' }}</strong>
                                </div>
                            @else
                                <h4 class="font-weight-bold text-white mb-1" style="font-size: 1.2rem;">
                                    Registered Directly / System Master Account
                                </h4>
                                <div class="text-light small opacity-75">You joined directly without a referee link.</div>
                            @endif
                        </div>
                        <div>
                            <span class="badge badge-dark px-3 py-2 font-weight-bold text-uppercase border border-secondary" style="border-radius: 8px;">
                                <i class="fas fa-sitemap text-warning mr-1"></i> Network Tree Node
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Direct Referrals Table (Level 1) --}}
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-primary text-white font-weight-bold py-3 d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-user-friends mr-2"></i> Direct Referrals (Level 1 — 10% Bonus)</span>
                        <span class="badge badge-light text-primary font-weight-bold">{{ $directReferrals->total() }} Direct {{ Str::plural('Referral', $directReferrals->total()) }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($directReferrals->isEmpty())
                            <p class="text-muted p-4 mb-0 text-center"><i class="fas fa-users text-muted mb-2 d-block fa-2x"></i>No direct referrals yet. Share your referral link above to invite new members.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-striped text-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Transfer Code</th>
                                            <th>Username</th>
                                            <th>Position / Side</th>
                                            <th>Package</th>
                                            <th>Status</th>
                                            <th>Joined Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($directReferrals as $refer)
                                            @php
                                                $side = $refer->teamSide ? $refer->teamSide->side : 'N/A';
                                                $hasPkg = $refer->hasAnyPackage(); // §76: FOM-aware (Royal promos / legacy FOM won't show FREE)
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration + ($directReferrals->currentPage() - 1) * $directReferrals->perPage() }}</td>
                                                <td class="font-weight-bold text-dark font-mono">{{ $refer->transfer_code ?? '—' }}</td>
                                                <td><span class="badge badge-light border">@ {{ $refer->user }}</span></td>
                                                <td>
                                                    <span class="badge badge-{{ $side === 'LEFT' ? 'info' : ($side === 'RIGHT' ? 'primary' : 'secondary') }} px-2 py-1">
                                                        {{ $side }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-slate-800">
                                                        {{ $refer->packageLabel() }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $hasPkg ? 'success' : 'warning' }} px-2 py-1">
                                                        {{ $hasPkg ? 'Active Package' : 'Registered' }}
                                                    </span>
                                                </td>
                                                <td><small class="text-muted">{{ $refer->created_at ? $refer->created_at->format('d M Y') : 'N/A' }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 d-flex justify-content-center border-top">
                                {{ $directReferrals->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Indirect Referrals Table (Level 2+) --}}
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px; overflow: hidden;">
                    <div class="card-header bg-dark text-white font-weight-bold py-3 d-flex align-items-center justify-content-between">
                        <span><i class="fas fa-sitemap mr-2"></i> Indirect Referrals (Level 2+ Downline)</span>
                        <span class="badge badge-light text-dark font-weight-bold">{{ $indirectReferrals->total() }} Indirect {{ Str::plural('Referral', $indirectReferrals->total()) }}</span>
                    </div>
                    <div class="card-body p-0">
                        @if($indirectReferrals->isEmpty())
                            <p class="text-muted p-4 mb-0 text-center"><i class="fas fa-network-wired text-muted mb-2 d-block fa-2x"></i>No indirect referrals yet.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-striped text-center mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Transfer Code</th>
                                            <th>Username</th>
                                            <th>Referred By</th>
                                            <th>Position / Side</th>
                                            <th>Package</th>
                                            <th>Joined Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($indirectReferrals as $refer)
                                            @php
                                                $side = $refer->teamSide ? $refer->teamSide->side : 'N/A';
                                                $hasPkg = $refer->hasAnyPackage(); // §76: FOM-aware (Royal promos / legacy FOM won't show FREE)
                                                $directRef = $refer->referrer;
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration + ($indirectReferrals->currentPage() - 1) * $indirectReferrals->perPage() }}</td>
                                                <td class="font-weight-bold text-dark font-mono">{{ $refer->transfer_code ?? '—' }}</td>
                                                <td><span class="badge badge-light border">@ {{ $refer->user }}</span></td>
                                                <td><small class="text-primary font-weight-bold">{{ $directRef ? '@' . $directRef->user : '—' }}</small></td>
                                                <td>
                                                    <span class="badge badge-{{ $side === 'LEFT' ? 'info' : ($side === 'RIGHT' ? 'primary' : 'secondary') }} px-2 py-1">
                                                        {{ $side }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="font-weight-bold text-slate-800">
                                                        {{ $refer->packageLabel() }}
                                                    </span>
                                                </td>
                                                <td><small class="text-muted">{{ $refer->created_at ? $refer->created_at->format('d M Y') : 'N/A' }}</small></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 d-flex justify-content-center border-top">
                                {{ $indirectReferrals->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    </div>
                </div>



<!-- referral link -->
                <div class="row mt-4">
                    <!-- Left Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"><i class="las la-users"></i> Left Referral</span>
                              </div>

                            <div class="card-body">
                                <div class="referral-link-container">
                                 
                                    <div class="input-group mb-3">
                                        @if($activated != 'standard')
                                            <input type="text" class="form-control" id="leftRefLink" 
                                                value="{{ url('/register?referral=' . $ref_code . '&side=LEFT') }}" readonly>
                                        @else
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        @endif
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary copy-btn" type="button" onclick="copyToClipboard('leftRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                            
                            </div>
                        </div>
                    </div>

                    <!-- Right Card -->
                    <div class="col-md-6">
                        <div class="card">
                        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"><i class="las la-users"></i> Right Referral</span>
                              </div>
                            <div class="card-body">
                                <div class="referral-link-container">
                                    
                                    <div class="input-group mb-3">
                                        @if($activated != 'standard')
                                            <input type="text" class="form-control" id="rightRefLink" 
                                                value="{{ url('/register?referral=' . $ref_code . '&side=RIGHT') }}" readonly>
                                        @else
                                            <input type="text" class="form-control" value="https://bifonex.com/register?referral=*******" readonly>
                                        @endif
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                                <i class="las la-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


<!-- banners row -->
<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 120 X 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/tm1.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 120px; height: 60px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 120 X 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/tm2.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 30%; height: 50%;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--  banner row -->
<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 125 X 125</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/dm3.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 125px ; height: 125px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 125 X 125</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/dm1.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 125px ; height: 125px;">
            </div>              
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 125 X 125</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/dm2.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 125px ; height: 125px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!--  banner row -->
<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
        <span class="mb-0">160 x 160</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/dm3.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 160px ; height: 160px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0">160 x 160</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/dm1.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 160px ; height: 160px;">
            </div>              
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Card -->
    <div class="col-md-4">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
        <span class="mb-0">160 x 160</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/dm2.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 160px ; height: 160px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 468 x 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/bn1.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 440px; height: 60px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 468 X 60</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/bn2.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 440px; height: 60px;">
            </div>                
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 728 x 90</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/bn1.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 455px; height: 70px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- middles card -->
    <div class="col-md-6">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 728 X 90</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/bn2.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="max-width: 455px; height: 70px;">
            </div>                
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>





<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-12">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 1200 X 500</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/bn1.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 1200px; height: 120px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<div class="row mt-4">
    <!-- Left Card -->
    <div class="col-md-12">
        <div class="card">
        <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> 1200 X 500</span>
                              </div>
            <div class="card-body">

            <div class="text-center">
            <img src="{{asset('image/bn2.png')}}" alt="Right Referral Image" class="img-fluid mb-3" style="width: 1200px; height: 120px;">
            </div>               
                <div class="referral-link-container">
               
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




<!-- dm cards -->

 <div class="row">
     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="{{asset('image/dmcard.png')}}" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>

                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>

             </div>
         </div>
     </div>

     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="{{asset('image/dm3.png')}}" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>

                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>

             </div>
         </div>
     </div>

     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="{{asset('image/dm1.png')}}" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>


                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
             </div>
         </div>
     </div>

     <div class="col-md-3">
         <div class="card">
         <div class="text-center  text-white p-1  mt-3" style="width: auto; margin: 0 auto; border-radius: 8px; background: darkblue;">
                      <span class="mb-0"> DM Card</span>
                              </div>
             <div class="card-body">
                 <div class="text-center">
                     <img src="{{asset('image/dm2.png')}}" alt="DM Card" class="img-fluid mb-3" style="width: 100%; height: 220px;">
                 </div>


                 <div class="input-group mb-3">
                        <input type="text" class="form-control" id="rightRefLink" 
                            value="{{ url('/register?referral=' . $ref_code) }}" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-success copy-btn" type="button" onclick="copyToClipboard('rightRefLink')">
                                <i class="las la-copy"></i>
                            </button>
                        </div>
                    </div>
             </div>
         </div>
     </div>
 </div>
 

            </div>
        </section>
    </div>
</div>


<style>
.card {
    border: none;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.card-header {
    padding: 15px;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.copy-btn {
    cursor: pointer;
    transition: all 0.3s;
}

.copy-btn:hover {
    transform: scale(1.05);
}

.referral-stats {
    margin-top: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.stat-item i {
    font-size: 24px;
    margin-right: 10px;
}

.badge {
    padding: 8px 12px;
    border-radius: 4px;
}

.table th {
    border-top: none;
}
</style>

<script>
function copyToClipboard(elementId) {
    if ("{{$activated}}" === "standard") {
        alert('Activate package to use this feature');
        return;
    }
    
    const copyText = document.getElementById(elementId);
    copyText.select();
    document.execCommand("copy");
    
    alert('Referral link copied to clipboard!');
}
</script>
