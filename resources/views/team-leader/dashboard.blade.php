<div class="wrapper">
    @include('user.user-dashboard-base')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #111215;
            color: #fff;
        }
        .leader-container {
            width: 100%;
            margin-top: 1.5rem;
            margin-bottom: 2rem;
            padding: 0 1rem;
        }
        .leader-header {
            background: #1c1d20;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 1.5rem;
            border: 1px solid #2e2f34;
        }
        .leader-title-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .leader-title-icon {
            font-size: 1.6rem;
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
            padding: 12px;
            border-radius: 8px;
        }
        .leader-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }
        .leader-tabs-nav .nav-link {
            border-radius: 8px !important;
            color: #94a3b8 !important;
            background-color: #1c1d20;
            border: 1px solid #2e2f34;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.6rem 1.2rem;
            transition: all 0.15s ease;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .leader-tabs-nav .nav-link.active {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #ffffff !important;
        }
        .leader-card {
            background: #1c1d20;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            border: 1px solid #2e2f34;
            margin-bottom: 1.5rem;
        }
        .form-control-dark {
            background-color: #111215 !important;
            border: 1px solid #2e2f34 !important;
            color: #fff !important;
            border-radius: 8px !important;
            padding: 10px !important;
        }
        .form-control-dark:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
        }
        .table-dark-custom th {
            background: #111215;
            color: #94a3b8;
            font-weight: 600;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #2e2f34;
        }
        .table-dark-custom td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
            border-bottom: 1px solid #2e2f34;
            color: #cbd5e1;
            font-size: 0.88rem;
        }
    </style>

    <div class="content-wrapper text-white" style="background:#111215;">
        <div class="container-fluid">
            <div class="leader-container">

                {{-- Dashboard Header --}}
                <div class="leader-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="leader-title-box">
                        <i class="leader-title-icon fas fa-users-cog"></i>
                        <div>
                            <h3 class="leader-title">Team Leader Dashboard</h3>
                            <p class="text-slate-400 text-xs mb-0">Complete your meetings, upload marketing proofs, manage social channels, and view official materials.</p>
                        </div>
                    </div>
                    <form action="{{ route('team-leader.logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger font-weight-bold" style="border-radius: 20px; padding: 6px 16px;">
                            <i class="fas fa-sign-out-alt mr-1"></i> Log Out
                        </button>
                    </form>
                </div>

                {{-- Session Feedback Alerts --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 mb-4" style="border-radius: 8px; background-color: #0f5132; color: #d1e7dd;">
                        <i class="fas fa-check-circle mr-2"></i><strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 mb-4" style="border-radius: 8px; background-color: #842029; color: #f8d7da;">
                        <i class="fas fa-exclamation-triangle mr-2"></i><strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="close text-white" data-dismiss="alert">&times;</button>
                    </div>
                @endif

                {{-- ══ SUPER LEADER CREDIT CARD ══ --}}
                @if($credit && $credit->credit_amount > 0)
                <div class="leader-header mb-4" style="border-color: #f59e0b; background: linear-gradient(135deg, #1c1d20 0%, #2d2006 100%);">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background: rgba(245,158,11,0.15); padding: 12px; border-radius: 8px;">
                                <i class="fas fa-credit-card text-warning" style="font-size: 1.4rem;"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 font-weight-bold text-warning">SUPER LEADER Credit Wallet</h5>
                                <p class="text-slate-400 text-xs mb-0">Your allocated credit from the administration.</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h3 class="mb-0 font-weight-extrabold" style="color: #fbbf24;">${{ number_format($credit->credit_amount, 2) }}</h3>
                            @if($credit->status === 'active')
                                <span class="badge bg-success px-3 py-1" style="border-radius: 20px; font-size: 0.7rem;">Active</span>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-1" style="border-radius: 20px; font-size: 0.7rem;">Pending</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3 pt-3" style="border-top: 1px solid #2e2f34;">
                        <div class="col-4 text-center">
                            <p class="text-slate-500 text-xs mb-1">Remaining</p>
                            <p class="font-weight-bold text-success mb-0">${{ number_format($credit->remaining_credit, 2) }}</p>
                        </div>
                        <div class="col-4 text-center">
                            <p class="text-slate-500 text-xs mb-1">Cashout</p>
                            <p class="font-weight-bold text-info mb-0">${{ number_format($credit->cashout_amount, 2) }}</p>
                        </div>
                        <div class="col-4 text-center">
                            <p class="text-slate-500 text-xs mb-1">Turnover Target</p>
                            <p class="font-weight-bold text-white mb-0">${{ number_format($credit->sales_turnover_target, 2) }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @php $activeTab = request('tab', 'planning'); @endphp

                {{-- Nav Tabs (5 Pages of the Team Leader Dashboard) --}}
                <ul class="nav nav-pills mb-4 leader-tabs-nav" id="leaderTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'planning' ? 'active' : '' }}" id="tab-planning-link" data-toggle="pill" href="#tab-planning" role="tab">
                            <i class="fas fa-calendar-plus mr-1.5"></i>Page 1: Events Planning &amp; Zoom
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'proof' ? 'active' : '' }}" id="tab-proof-link" data-toggle="pill" href="#tab-proof" role="tab">
                            <i class="fas fa-camera-retro mr-1.5"></i>Page 2: Event Proof Upload
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'ambassador' ? 'active' : '' }}" id="tab-ambassador-link" data-toggle="pill" href="#tab-ambassador" role="tab">
                            <i class="fas fa-bullhorn mr-1.5"></i>Page 3: Social Ambassador Link
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'videos' ? 'active' : '' }}" id="tab-videos-link" data-toggle="pill" href="#tab-videos" role="tab">
                            <i class="fas fa-video mr-1.5"></i>Page 4: Official Videos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $activeTab === 'banners' ? 'active' : '' }}" id="tab-banners-link" data-toggle="pill" href="#tab-banners" role="tab">
                            <i class="fas fa-images mr-1.5"></i>Page 5: Ad Banners
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="leaderTabsContent">

                    {{-- ───────────────────────────────────────────────────────────
                         PAGE 1: EVENTS PLANNING & ZOOM MEETINGS
                         ─────────────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade {{ $activeTab === 'planning' ? 'show active' : '' }}" id="tab-planning" role="tabpanel">
                        <div class="row">
                            {{-- Form --}}
                            <div class="col-lg-5 mb-4">
                                <div class="leader-card">
                                    <h5 class="font-weight-bold mb-3 text-info"><i class="fas fa-calendar-plus mr-2"></i>Schedule a New Event</h5>
                                    <form action="{{ route('team-leader.events.store') }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-sm font-weight-semibold text-slate-300">Event Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" required class="form-control form-control-dark" placeholder="e.g. Bifonex Kigali Seminar">
                                        </div>

                                        <div class="form-group">
                                            <label class="text-sm font-weight-semibold text-slate-300">Event Type <span class="text-danger">*</span></label>
                                            <select name="type" required class="form-control form-control-dark" onchange="toggleEventFields(this.value)">
                                                <option value="physical" selected>Physical Meeting (Seminar, Event)</option>
                                                <option value="zoom">Zoom Online Meeting</option>
                                            </select>
                                        </div>

                                        <div class="form-group" id="group-physical">
                                            <label class="text-sm font-weight-semibold text-slate-300">Event Location <span class="text-danger">*</span></label>
                                            <input type="text" name="location" id="input-location" required class="form-control form-control-dark" placeholder="e.g. Kigali Convention Center, Room 4">
                                        </div>

                                        <div class="form-group d-none" id="group-zoom">
                                            <label class="text-sm font-weight-semibold text-slate-300">Zoom Link / Code <span class="text-danger">*</span></label>
                                            <input type="url" name="zoom_link" id="input-zoom" class="form-control form-control-dark" placeholder="e.g. https://zoom.us/j/...">
                                        </div>

                                        <div class="form-group">
                                            <label class="text-sm font-weight-semibold text-slate-300">Event Date &amp; Time <span class="text-danger">*</span></label>
                                            <input type="datetime-local" name="event_time" required class="form-control form-control-dark">
                                            <small class="text-muted d-block mt-1">Our system will automatically audit this slot for potential calendar conflicts.</small>
                                        </div>

                                        <button type="submit" class="btn btn-info font-weight-bold btn-block py-2.5 mt-3" style="border-radius: 8px;">
                                            <i class="fas fa-paper-plane mr-1.5"></i>Submit for Admin Review
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- List --}}
                            <div class="col-lg-7 mb-4">
                                <div class="leader-card p-0 overflow-hidden">
                                    <div class="p-3 bg-light border-bottom border-secondary">
                                        <h5 class="font-weight-bold mb-0 text-dark"><i class="fas fa-list-ul mr-2 text-primary"></i>My Scheduled Events</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-dark-custom mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Event Details</th>
                                                    <th>Type &amp; Destination</th>
                                                    <th>Scheduled Date</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($events as $event)
                                                    <tr>
                                                        <td class="font-weight-bold text-light">{{ $event->title }}</td>
                                                        <td>
                                                            @if($event->type === 'zoom')
                                                                <span class="badge badge-primary px-2.5 py-1 text-xs">Zoom</span>
                                                                <small class="text-muted d-block mt-1 font-mono truncate max-w-[150px]"><a href="{{ $event->zoom_link }}" target="_blank" class="text-info">{{ $event->zoom_link }}</a></small>
                                                            @else
                                                                <span class="badge badge-info px-2.5 py-1 text-xs">Physical</span>
                                                                <small class="text-muted d-block mt-1 truncate max-w-[150px]" title="{{ $event->location }}">{{ $event->location }}</small>
                                                            @endif
                                                        </td>
                                                        <td class="text-slate-300 text-xs font-medium">{{ $event->event_time->format('d M Y, h:i A') }}</td>
                                                        <td class="text-center">
                                                            @php
                                                                $bClass = match($event->status) {
                                                                    'approved' => 'badge-success',
                                                                    'rejected' => 'badge-danger',
                                                                    default => 'badge-warning',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $bClass }} px-2.5 py-1 font-weight-bold text-xs">{{ ucfirst($event->status) }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-5 text-muted">No scheduled meetings found. Use the left form to schedule.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ───────────────────────────────────────────────────────────
                         PAGE 2: EVENT PROOFS
                         ─────────────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade {{ $activeTab === 'proof' ? 'show active' : '' }}" id="tab-proof" role="tabpanel">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="leader-card">
                                    <h5 class="font-weight-bold mb-3 text-info"><i class="fas fa-camera-retro mr-2"></i>Submit Event Performance Proof</h5>
                                    <p class="text-muted text-sm">Select an approved event you have completed and upload the proof materials (photos, videos, or PDFs) for administrative review.</p>
                                    
                                    @php $approvedEvents = $events->where('status', 'approved')->where('proof_submitted', false); @endphp
                                    
                                    @if($approvedEvents->isEmpty())
                                        <div class="alert alert-warning text-sm rounded-xl py-3 border-0 mt-3" style="background-color: #3b3105; color: #ffe69c;">
                                            <i class="fas fa-exclamation-triangle mr-1.5"></i><strong>No eligible events:</strong> You do not currently have any approved events awaiting proof submission. Please schedule and get approval on Page 1 first.
                                        </div>
                                    @else
                                        <form action="" method="POST" id="proofForm" enctype="multipart/form-data" class="space-y-4 mt-3">
                                            @csrf
                                            
                                            <div class="form-group">
                                                <label class="text-sm font-weight-semibold text-slate-300">1. Select Approved Event <span class="text-danger">*</span></label>
                                                <select id="proofEventSelect" required class="form-control form-control-dark" onchange="updateProofFormAction(this.value)">
                                                    <option value="" disabled selected>-- Choose Completed Meeting --</option>
                                                    @foreach($approvedEvents as $e)
                                                        <option value="{{ $e->id }}">
                                                            {{ $e->title }} ({{ $e->event_time->format('d M Y') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-sm font-weight-semibold text-slate-300">2. Performance Notes / Remarks <span class="text-danger">*</span></label>
                                                <textarea name="proof_notes" rows="4" required class="form-control form-control-dark" placeholder="Explain the outcomes, attendance, or any important highlights of your meeting..."></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label class="text-sm font-weight-semibold text-slate-300">3. Upload Proof File <small class="text-muted">(Optional — image, PDF, or video)</small></label>
                                                <input type="file" name="proof_file" class="form-control form-control-dark py-1" accept="image/*,application/pdf,video/*">
                                                <small class="text-muted d-block mt-1">Upload pictures from the seminar or PDFs detailing registration sheets (Max 50MB).</small>
                                            </div>

                                            <button type="submit" class="btn btn-info font-weight-bold btn-block py-2.5 mt-3" style="border-radius: 8px;">
                                                <i class="fas fa-cloud-upload-alt mr-1.5"></i>Submit Performance Proof
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                {{-- Proof Status Table --}}
                                @php $proofEvents = $events->where('proof_submitted', true); @endphp
                                @if($proofEvents->isNotEmpty())
                                    <div class="leader-card p-0 overflow-hidden mt-4">
                                        <div class="p-3 bg-light border-bottom border-secondary">
                                            <h5 class="font-weight-bold mb-0 text-dark"><i class="fas fa-history mr-2 text-primary"></i>My Submitted Proofs History</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-dark-custom mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Event</th>
                                                        <th>Proof File / Attachment</th>
                                                        <th>Performance Remarks</th>
                                                        <th class="text-center">Audit Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($proofEvents as $e)
                                                        <tr>
                                                            <td class="font-weight-bold text-light">{{ $e->title }}</td>
                                                            <td>
                                                                @if($e->proof_files)
                                                                    <a href="{{ asset('storage/' . $e->proof_files) }}" target="_blank" class="btn btn-xs btn-outline-info font-weight-bold" style="border-radius: 4px;">
                                                                        <i class="fas fa-download mr-1"></i> Download Proof
                                                                    </a>
                                                                @else
                                                                    <span class="text-xs text-muted">No attachment</span>
                                                                @endif
                                                            </td>
                                                            <td><small class="text-muted" title="{{ $e->proof_notes }}">{{ Str::limit($e->proof_notes, 40) }}</small></td>
                                                            <td class="text-center">
                                                                @php
                                                                    $pbClass = match($e->proof_status) {
                                                                        'approved' => 'badge-success',
                                                                        'rejected' => 'badge-danger',
                                                                        default => 'badge-warning',
                                                                    };
                                                                @endphp
                                                                <span class="badge {{ $pbClass }} px-2.5 py-1 font-weight-bold text-xs">{{ ucfirst($e->proof_status) }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ───────────────────────────────────────────────────────────
                         PAGE 3: SOCIAL MEDIA AMBASSADOR
                         ─────────────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade {{ $activeTab === 'ambassador' ? 'show active' : '' }}" id="tab-ambassador" role="tabpanel">
                        <div class="row">
                            {{-- Form --}}
                            <div class="col-lg-5 mb-4">
                                <div class="leader-card">
                                    <h5 class="font-weight-bold mb-3 text-info"><i class="fas fa-bullhorn mr-2"></i>Register Ambassador Link</h5>
                                    <form action="{{ route('team-leader.socials.store') }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="form-group">
                                            <label class="text-sm font-weight-semibold text-slate-300">Platform <span class="text-danger">*</span></label>
                                            <select name="platform" required class="form-control form-control-dark">
                                                <option value="instagram" selected>Instagram</option>
                                                <option value="facebook">Facebook</option>
                                                <option value="x">X / Twitter</option>
                                                <option value="linkedin">LinkedIn</option>
                                                <option value="tiktok">TikTok</option>
                                                <option value="youtube">YouTube Channel</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-sm font-weight-semibold text-slate-300">Profile / Post Link <span class="text-danger">*</span></label>
                                            <input type="url" name="profile_link" required class="form-control form-control-dark" placeholder="e.g. https://instagram.com/your_username">
                                            <small class="text-muted d-block mt-1">Direct URL to your high-view social page or promotion post.</small>
                                        </div>

                                        <div class="form-group">
                                            <label class="text-sm font-weight-semibold text-slate-300">Average Views / Impressions <small class="text-muted">(Optional)</small></label>
                                            <input type="number" name="views" min="0" class="form-control form-control-dark" placeholder="e.g. 5000">
                                            <small class="text-muted d-block mt-1">Estimated monthly impressions or video views.</small>
                                        </div>

                                        <button type="submit" class="btn btn-info font-weight-bold btn-block py-2.5 mt-3" style="border-radius: 8px;">
                                            <i class="fas fa-cloud-upload-alt mr-1.5"></i>Submit Channel Link
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- List --}}
                            <div class="col-lg-7 mb-4">
                                <div class="leader-card p-0 overflow-hidden">
                                    <div class="p-3 bg-light border-bottom border-secondary">
                                        <h5 class="font-weight-bold mb-0 text-dark"><i class="fas fa-sitemap mr-2 text-primary"></i>My Registered Channels</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-dark-custom mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Platform</th>
                                                    <th>Link</th>
                                                    <th class="text-right">Impressions</th>
                                                    <th class="text-center">Audit Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($socials as $social)
                                                    <tr>
                                                        <td>
                                                            <span class="badge badge-light border text-slate-800 font-weight-bold px-2.5 py-1 text-xs text-uppercase">
                                                                {{ $social->platform }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small class="font-mono text-info"><a href="{{ $social->profile_link }}" target="_blank" class="text-info">{{ Str::limit($social->profile_link, 30) }}</a></small>
                                                        </td>
                                                        <td class="text-right font-weight-bold text-slate-300">{{ number_format($social->views_count, 0) }}</td>
                                                        <td class="text-center">
                                                            @php
                                                                $sbClass = match($social->status) {
                                                                    'approved' => 'badge-success',
                                                                    'rejected' => 'badge-danger',
                                                                    default => 'badge-warning',
                                                                };
                                                            @endphp
                                                            <span class="badge {{ $sbClass }} px-2.5 py-1 font-weight-bold text-xs">{{ ucfirst($social->status) }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center py-5 text-muted">No ambassador channels registered yet.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ───────────────────────────────────────────────────────────
                         PAGE 4: OFFICIAL VIDEOS
                         ─────────────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade {{ $activeTab === 'videos' ? 'show active' : '' }}" id="tab-videos" role="tabpanel">
                        <div class="leader-card">
                            <h5 class="font-weight-bold mb-3 text-info"><i class="fas fa-video mr-2"></i>Official Video Promotions &amp; Tutorials</h5>
                            <p class="text-muted text-sm mb-4">View official video campaigns designed by the admin. Learn about system features, new promotions, and guidelines.</p>
                            
                            @if($adminVideos->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-film fa-3x text-slate-600 mb-3 block"></i>No official promotion videos available at this time.
                                </div>
                            @else
                                <div class="row">
                                    @foreach($adminVideos as $video)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card bg-dark border border-secondary shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                                                {{-- Embedded YouTube Player or Standard HTML5 Player --}}
                                                @if($video->video_type === 'youtube')
                                                    @php
                                                        // extract YouTube code
                                                        $ytId = $video->youtubeId();
                                                    @endphp
                                                    @if($ytId)
                                                        <iframe class="w-100" style="height: 180px;" src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allowfullscreen></iframe>
                                                    @else
                                                        <div class="bg-slate-900 d-flex align-items-center justify-content-center" style="height: 180px;">
                                                            <i class="fab fa-youtube text-danger fa-3x"></i>
                                                        </div>
                                                    @endif
                                                @else
                                                    <video class="w-100" style="height: 180px; background:#000;" controls>
                                                        <source src="{{ asset('storage/' . $video->video_url) }}" type="video/mp4">
                                                    </video>
                                                @endif
                                                <div class="card-body p-3 flex-column justify-content-between">
                                                    <h6 class="font-weight-bold text-light mb-1.5 leading-snug">{{ $video->title }}</h6>
                                                    <div class="d-flex justify-content-between align-items-center text-xs mt-3 pt-2 border-top border-secondary">
                                                        <span class="text-slate-400 font-mono"><i class="fas fa-play-circle mr-1"></i> {{ number_format($video->views_count) }} reached</span>
                                                        <span class="text-indigo-400 font-bold"><i class="fas fa-clock mr-1"></i> {{ $video->duration_seconds }} sec</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ───────────────────────────────────────────────────────────
                         PAGE 5: AD BANNERS
                         ─────────────────────────────────────────────────────────── --}}
                    <div class="tab-pane fade {{ $activeTab === 'banners' ? 'show active' : '' }}" id="tab-banners" role="tabpanel">
                        <div class="leader-card">
                            <h5 class="font-weight-bold mb-3 text-info"><i class="fas fa-images mr-2"></i>Marketing Banners &amp; Creatives</h5>
                            <p class="text-muted text-sm mb-4">Official designs and picture banners prepared by our designers. Download them and post on your social profiles to attract recruits.</p>
                            
                            @if($adminBanners->isEmpty())
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-image fa-3x text-slate-600 mb-3 block"></i>No ad banners available at this time.
                                </div>
                            @else
                                <div class="row">
                                    @foreach($adminBanners as $banner)
                                        <div class="col-md-6 col-lg-4 mb-4">
                                            <div class="card bg-dark border border-secondary shadow-sm h-100" style="border-radius: 12px; overflow: hidden;">
                                                <div class="p-1 bg-slate-900 d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden;">
                                                    <img src="{{ asset('storage/' . $banner->image_path) }}" class="img-fluid rounded max-h-[190px]" alt="{{ $banner->title }}" onerror="this.src='https://placehold.co/400x250?text=Marketing+Creative';">
                                                </div>
                                                <div class="card-body p-3">
                                                    <h6 class="font-weight-bold text-light mb-1 leading-snug">{{ $banner->title }}</h6>
                                                    <p class="text-xs text-muted leading-relaxed mt-2" style="font-family: inherit;">{{ Str::limit($banner->description, 75) }}</p>
                                                    <div class="d-flex justify-content-between align-items-center text-xs mt-3 pt-2 border-top border-secondary">
                                                        <a href="{{ asset('storage/' . $banner->image_path) }}" target="_blank" download class="btn btn-xs btn-outline-info font-weight-bold w-100 text-center" style="border-radius: 6px;">
                                                            <i class="fas fa-download mr-1"></i> Download Banner Asset
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleEventFields(type) {
        const groupPhysical = document.getElementById('group-physical');
        const groupZoom = document.getElementById('group-zoom');
        const inputLocation = document.getElementById('input-location');
        const inputZoom = document.getElementById('input-zoom');

        if (type === 'zoom') {
            groupPhysical.classList.add('d-none');
            groupZoom.classList.remove('d-none');
            inputLocation.removeAttribute('required');
            inputZoom.setAttribute('required', 'required');
        } else {
            groupPhysical.classList.remove('d-none');
            groupZoom.classList.add('d-none');
            inputLocation.setAttribute('required', 'required');
            inputZoom.removeAttribute('required');
        }
    }

    function updateProofFormAction(eventId) {
        const form = document.getElementById('proofForm');
        if (form && eventId) {
            form.action = `/team-leader/events/${eventId}/proof`;
        }
    }
</script>

<script src="{{ asset('assets/a/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/a/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
