<div class="wrapper">
    <?php echo $__env->make('user.user-dashboard-base', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* ══════════════════════════════════════════════════════════
           DESIGN SYSTEM — 8px grid, Inter font, 3-tier shadows
           ══════════════════════════════════════════════════════════ */
        :root {
            --space-1: 4px; --space-2: 8px; --space-3: 12px; --space-4: 16px;
            --space-5: 20px; --space-6: 24px; --space-8: 32px;
            --radius-sm: 8px; --radius-md: 12px; --radius-lg: 16px;
            --shadow-1: 0 1px 2px rgba(0,0,0,.05);
            --shadow-2: 0 2px 8px rgba(0,0,0,.08), 0 1px 2px rgba(0,0,0,.04);
            --shadow-3: 0 8px 24px rgba(0,0,0,.12), 0 2px 8px rgba(0,0,0,.06);
            --color-bg: #f3f4f6;
            --color-surface: #ffffff;
            --color-text: #111827;
            --color-text-secondary: #6b7280;
            --color-accent: #ef4444;
            --color-primary: #3b82f6;
            --color-success: #10b981;
            --color-warning: #f59e0b;
        }
        .all-page *{box-sizing:border-box;margin:0;padding:0}
        .all-page{font-family:'Inter',system-ui,sans-serif;background:var(--color-bg);color:var(--color-text);line-height:1.5;-webkit-font-smoothing:antialiased}

        /* ── HEADER ─ */
        .all-header{background:linear-gradient(135deg,#64748b,#94a3b8);padding:var(--space-5) var(--space-6);position:relative}
        .all-header h1{color:var(--color-accent);font-size:clamp(1.4rem,3vw,2.4rem);font-weight:900;letter-spacing:1.5px;text-shadow:0 2px 4px rgba(0,0,0,.2)}

        /* ── PAGE GRID: main + sidebar ── */
        .page-grid{display:grid;grid-template-columns:1fr 150px;gap:var(--space-3);padding:var(--space-3);align-items:start}

        /* ── CONTENT AREA: stacks rows ── */
        .content-area{display:flex;flex-direction:column;gap:var(--space-3)}

        /* ── ROW LAYOUTS ── */
        .row{display:grid;gap:var(--space-3)}
        .row-1{grid-template-columns:minmax(200px,1fr) minmax(220px,1.1fr) minmax(320px,2fr)}
        .row-2{grid-template-columns:minmax(280px,2fr) minmax(300px,3fr)}

        /* ── CARD BASE ── */
        .card{background:var(--color-surface);border-radius:var(--radius-md);box-shadow:var(--shadow-2);overflow:hidden;display:flex;flex-direction:column}

        /* ── CARD HEADER ── */
        .card-head{padding:var(--space-3) var(--space-4);border-bottom:1px solid #f3f4f6;display:flex;align-items:center;gap:var(--space-2)}
        .card-head h3{font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--color-text-secondary)}
        .card-head .icon{width:28px;height:28px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:.75rem}
        .card-body{padding:var(--space-4);flex:1;display:flex;flex-direction:column;gap:var(--space-3)}

        /* ═══ SECTION 1: Status & Countdown ═══ */
        .countdown{display:grid;grid-template-columns:repeat(4,1fr);gap:var(--space-2)}
        .cd-cell{background:linear-gradient(145deg,#1e293b,#0f172a);color:#fff;text-align:center;padding:var(--space-3) var(--space-2);border-radius:var(--radius-sm)}
        .cd-cell .n{font-size:1.4rem;font-weight:900;line-height:1;display:block}
        .cd-cell .l{font-size:.5rem;text-transform:uppercase;letter-spacing:1px;opacity:.5;margin-top:var(--space-1);display:block}
        .badges{display:flex;gap:var(--space-2);flex-wrap:wrap}
        .badge-pill{padding:var(--space-2) var(--space-3);border-radius:var(--radius-sm);font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.3px}
        .badge-tm{background:#fef2f2;color:#dc2626;border:1px solid #fecaca}
        .badge-fc{background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0}
        .credit-box{background:linear-gradient(135deg,#1e1b4b,#312e81);color:#fff;padding:var(--space-4);border-radius:var(--radius-sm);font-size:.72rem}
        .credit-box h4{font-size:.8rem;color:#fbbf24;font-weight:700;margin-bottom:var(--space-2);display:flex;align-items:center;gap:var(--space-2)}
        .credit-box .row-stat{display:flex;justify-content:space-between;padding:var(--space-1) 0;border-bottom:1px solid rgba(255,255,255,.08)}
        .credit-box .row-stat:last-child{border:none}
        .credit-box .val{font-weight:700;color:#a5b4fc}
        .zoom-cta{display:flex;align-items:center;gap:var(--space-3);padding:var(--space-3) var(--space-4);background:#0f172a;border-radius:var(--radius-sm);border:2px solid #dc2626;cursor:pointer;transition:all .2s}
        .zoom-cta:hover{border-color:#ef4444;box-shadow:0 0 16px rgba(220,38,38,.2)}
        .zoom-cta i{color:#dc2626;font-size:1.2rem}
        .zoom-cta .t{color:#22c55e;font-weight:800;font-size:.7rem}
        .zoom-cta .s{color:#94a3b8;font-size:.6rem}

        /* ═══ SECTION 2: Tasks & Analytics ═══ */
        .task-block{background:linear-gradient(145deg,#92400e,#78350f);color:#fff;padding:var(--space-4);border-radius:var(--radius-sm);font-size:.7rem;line-height:1.7;flex:1;overflow-y:auto;max-height:200px}
        .task-block::-webkit-scrollbar{width:3px}
        .task-block::-webkit-scrollbar-thumb{background:rgba(255,255,255,.2);border-radius:3px}
        .task-block h4{color:#fcd34d;font-size:.8rem;font-weight:700;margin-bottom:var(--space-2)}
        .charts-grid{display:grid;grid-template-columns:1fr 1fr;gap:var(--space-2);flex:1}
        .chart-cell{background:#f9fafb;border-radius:var(--radius-sm);padding:var(--space-2);border:1px solid #e5e7eb;min-height:120px}

        /* ═══ SECTION 3: Events ═══ */
        .events-layout{display:grid;grid-template-columns:1fr 1fr;gap:var(--space-3);flex:1}
        .history-panel{background:#f9fafb;border-radius:var(--radius-sm);border:1px solid #e5e7eb;padding:var(--space-3);overflow-y:auto;max-height:280px}
        .history-panel h4{font-size:.8rem;font-weight:800;text-align:center;color:var(--color-text);padding-bottom:var(--space-2);border-bottom:2px solid var(--color-accent);margin-bottom:var(--space-2)}
        .form-panel{background:linear-gradient(160deg,#000033,#0c0c52);border-radius:var(--radius-sm);padding:var(--space-3);color:#fff}
        .form-panel h4{font-size:.8rem;font-weight:800;text-align:center;color:var(--color-accent);padding-bottom:var(--space-2);border-bottom:2px solid var(--color-accent);margin-bottom:var(--space-2)}
        .form-panel label{font-size:.65rem;font-weight:600;display:block;margin:var(--space-2) 0 var(--space-1);color:#cbd5e1}
        .form-panel label .r{color:#fca5a5;font-weight:400}
        .form-panel label .o{color:#64748b;font-weight:400}
        .form-panel input,.form-panel textarea{width:100%;padding:var(--space-2) var(--space-3);border:1px solid rgba(255,255,255,.12);border-radius:6px;font-size:.72rem;background:rgba(255,255,255,.95);color:#1f2937}
        .form-panel input:focus,.form-panel textarea:focus{outline:none;border-color:var(--color-primary);box-shadow:0 0 0 3px rgba(59,130,246,.15)}
        .form-panel textarea{height:56px;resize:vertical}
        .form-submit{width:100%;padding:var(--space-2) var(--space-3);background:linear-gradient(135deg,#0ea5e9,#0369a1);color:#fff;border:none;border-radius:6px;font-weight:700;font-size:.75rem;cursor:pointer;margin-top:var(--space-3);transition:all .15s}
        .form-submit:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(14,165,233,.3)}
        .carousel-strip{display:flex;gap:var(--space-2);overflow-x:auto;padding:var(--space-1) 0}
        .carousel-strip::-webkit-scrollbar{height:3px}
        .carousel-strip::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:3px}
        .carousel-strip img{height:80px;width:120px;object-fit:cover;border-radius:var(--radius-sm);border:2px solid #fff;flex-shrink:0;box-shadow:var(--shadow-1)}
        .toggle-row{display:grid;grid-template-columns:1fr 1fr;gap:var(--space-2)}
        .toggle-btn{padding:var(--space-4) var(--space-3);text-align:center;border-radius:var(--radius-sm);font-weight:800;font-size:1rem;color:var(--color-accent);cursor:pointer;border:3px solid transparent;transition:all .2s;background:linear-gradient(145deg,#475569,#334155)}
        .toggle-btn.active{border-color:var(--color-accent);background:linear-gradient(145deg,#334155,#1e293b);box-shadow:0 0 16px rgba(239,68,68,.2)}
        .toggle-btn:hover{transform:translateY(-1px)}
        .eh-item{padding:var(--space-2) 0;border-bottom:1px solid #f3f4f6;font-size:.7rem}
        .eh-item:last-child{border:none}
        .eh-badge{display:inline-block;padding:2px 8px;border-radius:10px;font-size:.55rem;font-weight:700;text-transform:uppercase}
        .eh-badge.pending{background:#fef3c7;color:#92400e}
        .eh-badge.approved{background:#d1fae5;color:#065f46}
        .eh-badge.rejected{background:#fee2e2;color:#991b1b}
        .eh-empty{text-align:center;color:#9ca3af;padding:var(--space-6) var(--space-2);font-size:.75rem}

        /* ═══ SECTION 4: Ambassador ═══ */
        .amb-hero{background:linear-gradient(135deg,#0f0a2e,#1a1650);color:#fff;text-align:center;padding:var(--space-4);border-radius:var(--radius-sm);font-weight:900;font-size:1rem;letter-spacing:.5px;text-transform:uppercase}
        .amb-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:var(--space-2);flex:1}
        .amb-item{background:linear-gradient(145deg,#2563eb,#1d4ed8);color:#fff;border-radius:var(--radius-sm);padding:var(--space-3);display:flex;flex-direction:column;gap:var(--space-2);transition:all .2s}
        .amb-item:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(37,99,235,.3)}
        .amb-item h5{font-size:.7rem;font-weight:800;text-transform:uppercase}
        .amb-item p{font-size:.55rem;line-height:1.4;opacity:.8;flex:1}
        .amb-item button{background:rgba(0,0,0,.25);border:none;color:#fff;padding:var(--space-2);border-radius:6px;font-size:.6rem;font-weight:700;cursor:pointer;transition:background .15s}
        .amb-item button:hover{background:rgba(0,0,0,.4)}

        /* ═══ SECTION 5: Videos ═══ */
        .video-hero{background:#0f172a;border-radius:var(--radius-sm);overflow:hidden}
        .video-hero iframe,.video-hero video{width:100%;height:260px;border:none;display:block}
        .video-strip{display:flex;gap:var(--space-2);overflow-x:auto;padding:var(--space-1) 0}
        .video-strip::-webkit-scrollbar{height:3px}
        .video-strip::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:3px}
        .v-thumb{width:140px;height:78px;border-radius:6px;overflow:hidden;flex-shrink:0;cursor:pointer;border:2px solid transparent;transition:all .15s;background:#1e293b}
        .v-thumb.active{border-color:var(--color-primary);box-shadow:0 0 10px rgba(59,130,246,.3)}
        .v-thumb:hover{transform:scale(1.03)}
        .v-thumb img{width:100%;height:100%;object-fit:cover}

        /* ═══ RIGHT SIDEBAR ═══ */
        .sidebar-ads{display:flex;flex-direction:column;gap:var(--space-2)}
        .ad-slot{background:linear-gradient(145deg,#475569,#334155);border-radius:var(--radius-sm);flex:1;min-height:90px;display:flex;align-items:center;justify-content:center;overflow:hidden;transition:all .2s}
        .ad-slot:hover{transform:scale(1.02);box-shadow:var(--shadow-3)}
        .ad-slot img{width:100%;height:100%;object-fit:cover}
        .ad-slot .empty{color:rgba(255,255,255,.3);font-size:.65rem;font-weight:600}

        /* ═══ MODAL ═══ */
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99999;align-items:center;justify-content:center;backdrop-filter:blur(4px)}
        .modal-overlay.open{display:flex}
        .modal-box{background:#fff;border-radius:var(--radius-lg);padding:var(--space-6);width:380px;max-width:92vw;box-shadow:0 24px 48px rgba(0,0,0,.2);animation:popIn .2s ease}
        @keyframes  popIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
        .modal-box h4{font-size:1rem;font-weight:800;margin-bottom:var(--space-4)}
        .modal-box label{font-size:.72rem;font-weight:700;display:block;margin:var(--space-3) 0 var(--space-1);color:#374151}
        .modal-box select,.modal-box input{width:100%;padding:var(--space-2) var(--space-3);border:2px solid #e5e7eb;border-radius:var(--radius-sm);font-size:.82rem}
        .modal-box select:focus,.modal-box input:focus{outline:none;border-color:var(--color-primary)}
        .modal-actions{display:flex;gap:var(--space-2);justify-content:flex-end;margin-top:var(--space-5)}
        .modal-actions button{padding:var(--space-2) var(--space-4);border:none;border-radius:var(--radius-sm);font-weight:700;font-size:.8rem;cursor:pointer}
        .modal-actions .cancel{background:#f3f4f6;color:#374151}
        .modal-actions .confirm{background:var(--color-primary);color:#fff}

        /* ═══ RESPONSIVE ═══ */
        @media(max-width:1200px){.row-1{grid-template-columns:1fr 1fr}.card-sec3{grid-column:1/-1}}
        @media(max-width:992px){.page-grid{grid-template-columns:1fr}.sidebar-ads{flex-direction:row;flex-wrap:wrap}.ad-slot{min-height:70px;flex:1 1 calc(33% - 8px)}.row-1,.row-2{grid-template-columns:1fr}.amb-cards{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:768px){.events-layout{grid-template-columns:1fr}.history-panel{max-height:160px}.toggle-row{grid-template-columns:1fr}.amb-cards{grid-template-columns:1fr}.sidebar-ads{flex-direction:column}.ad-slot{min-height:60px}.video-hero iframe,.video-hero video{height:180px}.charts-grid{grid-template-columns:1fr}.countdown{grid-template-columns:repeat(4,1fr)}}
        @media(max-width:480px){.all-header h1{font-size:1.1rem}.badges{flex-direction:column}.cd-cell .n{font-size:1.1rem}}
    </style>

    <div class="all-page">
        <div class="all-header"><h1>Team apply dashboard</h1></div>

        <div class="page-grid">
            <div class="content-area">

                
                <div class="row row-1">
                    
                    <div class="card">
                        <div class="card-head"><div class="icon" style="background:#fef2f2;color:#dc2626"><i class="fas fa-clock"></i></div><h3>Duration & Status</h3></div>
                        <div class="card-body">
                            <div class="countdown">
                                <div class="cd-cell"><span class="n" id="cd-d">0</span><span class="l">Days</span></div>
                                <div class="cd-cell"><span class="n" id="cd-h">0</span><span class="l">Hours</span></div>
                                <div class="cd-cell"><span class="n" id="cd-m">0</span><span class="l">Min</span></div>
                                <div class="cd-cell"><span class="n" id="cd-s">0</span><span class="l">Sec</span></div>
                            </div>
                            <div class="badges">
                                <span class="badge-pill badge-tm">TM Super Leader</span>
                                <span class="badge-pill badge-fc">FC Leader</span>
                            </div>
                            <?php if($credit && $credit->credit_amount > 0): ?>
                            <div class="credit-box">
                                <h4><i class="fas fa-credit-card"></i> Credit Wallet</h4>
                                <div class="row-stat"><span>Total</span><span class="val">$<?php echo e(number_format($credit->credit_amount,2)); ?></span></div>
                                <div class="row-stat"><span>Remaining</span><span class="val">$<?php echo e(number_format($credit->remaining_credit,2)); ?></span></div>
                                <div class="row-stat"><span>Cashout</span><span class="val">$<?php echo e(number_format($credit->cashout_amount,2)); ?></span></div>
                                <div class="row-stat"><span>Status</span><span class="val" style="color:<?php echo e($credit->status==='active'?'#34d399':'#fbbf24'); ?>"><?php echo e(strtoupper($credit->status)); ?></span></div>
                            </div>
                            <?php endif; ?>
                            <div class="zoom-cta">
                                <i class="fas fa-video"></i>
                                <div><div class="t">WE ARE LIVE NOW ON ZOOM</div><div class="s">CLICK HERE TO JOIN US</div></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card">
                        <div class="card-head"><div class="icon" style="background:#fef3c7;color:#d97706"><i class="fas fa-tasks"></i></div><h3>Tasks & Analytics</h3></div>
                        <div class="card-body">
                            <div class="task-block">
                                <h4><i class="fas fa-clipboard-list"></i> Assigned Tasks</h4>
                                <?php if($tasks): ?><div style="white-space:pre-line"><?php echo e($tasks); ?></div><?php else: ?><p style="opacity:.6">No tasks assigned.</p><?php endif; ?>
                            </div>
                            <div class="charts-grid">
                                <div class="chart-cell"><canvas id="lineChart"></canvas></div>
                                <div class="chart-cell"><canvas id="donutChart"></canvas></div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="card card-sec3">
                        <div class="card-head"><div class="icon" style="background:#eff6ff;color:#2563eb"><i class="fas fa-calendar-alt"></i></div><h3>Events & Reports</h3></div>
                        <div class="card-body">
                            <div class="events-layout">
                                <div class="history-panel">
                                    <h4>My Event History</h4>
                                    <div id="history-plan"><?php $__empty_1 = true; $__currentLoopData = $planEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="eh-item"><strong><?php echo e($e->title); ?></strong> <span class="eh-badge <?php echo e($e->status); ?>"><?php echo e($e->status); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="eh-empty">No event plan history.</div><?php endif; ?></div>
                                    <div id="history-zoom" style="display:none"><?php $__empty_1 = true; $__currentLoopData = $zoomEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="eh-item"><strong><?php echo e($e->title); ?></strong> <span class="eh-badge <?php echo e($e->status); ?>"><?php echo e($e->status); ?></span></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><div class="eh-empty">No zoom history.</div><?php endif; ?></div>
                                </div>
                                <div class="form-panel">
                                    <h4>Report Event</h4>
                                    <form action="<?php echo e(route('team-leader.event-report')); ?>" method="POST" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?><input type="hidden" name="event_type" id="form-type" value="plan">
                                        <div id="fp"><label>Image 1 <span class="r">required</span></label><input type="file" name="event_image_1" accept="image/*" required id="ri1"><label>Image 2 <span class="o">optional</span></label><input type="file" name="event_image_2" accept="image/*"><label>Description <span class="r">required</span></label><textarea name="description" required id="rd"></textarea><label>Event done on <span class="r">required</span></label><input type="date" name="event_done_on" required id="rdt"><label>Hotel/Location <span class="o">optional</span></label><input type="text" name="hotel_location"></div>
                                        <div id="fz" style="display:none"><label>Zoom Link <span class="r">required</span></label><input type="url" name="zoom_link" id="rz"><label>Country <span class="r">required</span></label><input type="text" name="country" id="rc"><label>Place <span class="r">required</span></label><input type="text" name="place" id="rp"><label>Location <span class="o">optional</span></label><input type="text" name="location"><label>Date <span class="r">required</span></label><input type="date" name="event_date" id="rdt2"></div>
                                        <button type="submit" class="form-submit">Submit Report</button>
                                    </form>
                                </div>
                            </div>
                            <div class="carousel-strip" id="carousel"><?php $__currentLoopData = $eventImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><img src="<?php echo e(asset('storage/'.$img)); ?>" alt=""><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php if($eventImages->isEmpty()): ?><span style="color:#9ca3af;font-size:.7rem;padding:12px">No event photos yet.</span><?php endif; ?></div>
                            <div class="toggle-row">
                                <div class="toggle-btn active" id="tb-plan" onclick="toggle('plan')">Upload Event plan</div>
                                <div class="toggle-btn" id="tb-zoom" onclick="toggle('zoom')">Upload zoom</div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="row row-2">
                    
                    <div class="card">
                        <div class="card-head"><div class="icon" style="background:#f0f9ff;color:#0284c7"><i class="fas fa-users"></i></div><h3>Ambassador Program</h3></div>
                        <div class="card-body">
                            <div class="amb-hero">🌟 Become a Bifonex Ambassador</div>
                            <div class="amb-cards">
                                <?php $__currentLoopData = ['Instagram'=>'Promote Bifonex through posts and reels.','Twitter'=>'Promote through Retweets, Tweets, and Threads.','TikTok'=>'Promote Bifonex through short-form videos.','YouTube'=>'Promote through long-form video content.','Support'=>'Provide support via likes, comments and reposts.','Creator'=>'Create original content promoting Bifonex.']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p => $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="amb-item"><h5><?php echo e($p); ?></h5><p><?php echo e($d); ?></p><button onclick="openModal('<?php echo e($p); ?>')">Apply now</button></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-head"><div class="icon" style="background:#faf5ff;color:#7c3aed"><i class="fas fa-play-circle"></i></div><h3>Official Videos</h3></div>
                        <div class="card-body">
                            <?php if($adminVideos->isNotEmpty()): ?>
                                <?php $fv=$adminVideos->first(); ?>
                                <div class="video-hero" id="vMain"><?php if($fv->video_type==='youtube'&&$fv->youtubeId()): ?><iframe src="https://www.youtube.com/embed/<?php echo e($fv->youtubeId()); ?>" allowfullscreen></iframe><?php elseif($fv->video_url): ?><video controls><source src="<?php echo e(asset('storage/'.$fv->video_url)); ?>" type="video/mp4"></video><?php endif; ?></div>
                                <div class="video-strip"><?php $__currentLoopData = $adminVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><div class="v-thumb <?php echo e($i===0?'active':''); ?>" onclick="playV(this,'<?php echo e($v->video_type); ?>','<?php echo e($v->video_type==='youtube'?$v->youtubeId():asset('storage/'.$v->video_url)); ?>')"><?php if($v->video_type==='youtube'&&$v->youtubeId()): ?><img src="https://img.youtube.com/vi/<?php echo e($v->youtubeId()); ?>/mqdefault.jpg" alt=""><?php else: ?><div style="display:flex;align-items:center;justify-content:center;height:100%;color:#64748b;font-size:.55rem;padding:4px;text-align:center"><?php echo e($v->title); ?></div><?php endif; ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></div>
                            <?php else: ?><div style="background:#f9fafb;border-radius:8px;padding:40px;text-align:center;color:#9ca3af;flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center"><i class="fas fa-video fa-2x" style="margin-bottom:8px"></i><span style="font-size:.75rem">No videos available</span></div><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="sidebar-ads">
                <?php $__empty_1 = true; $__currentLoopData = $adminBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><div class="ad-slot"><?php if($b->image_path): ?><img src="<?php echo e(asset('storage/'.$b->image_path)); ?>" alt="<?php echo e($b->title); ?>"><?php else: ?><span class="empty"><?php echo e($b->title); ?></span><?php endif; ?></div><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="ad-slot"><span class="empty">Ad Space</span></div><div class="ad-slot"><span class="empty">Ad Space</span></div><div class="ad-slot"><span class="empty">Ad Space</span></div><div class="ad-slot"><span class="empty">Ad Space</span></div><div class="ad-slot"><span class="empty">Ad Space</span></div><div class="ad-slot"><span class="empty">Ad Space</span></div><?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="modal-overlay" id="ambModal"><div class="modal-box"><h4>Apply for Ambassador Program</h4><form action="<?php echo e(route('team-leader.socials.store')); ?>" method="POST"><?php echo csrf_field(); ?><label>Platform</label><select name="platform" id="mPlat"><option>Instagram</option><option>Twitter</option><option>TikTok</option><option>YouTube</option><option>Support</option><option>Creator</option></select><label>Follower Count</label><input type="number" name="views" min="0" value="0"><label>Profile URL</label><input type="url" name="profile_link" required placeholder="https://..."><div class="modal-actions"><button type="button" class="cancel" onclick="closeModal()">Cancel</button><button type="submit" class="confirm">Submit Application</button></div></form></div></div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Countdown
    !function(){<?php if($expiryDate): ?>var e=new Date('<?php echo e($expiryDate->toIso8601String()); ?>').getTime();<?php else: ?> var e=Date.now()+60*864e5;<?php endif; ?>
    function t(){var d=Math.max(0,e-Date.now());document.getElementById('cd-d').textContent=Math.floor(d/864e5);document.getElementById('cd-h').textContent=Math.floor(d%864e5/36e5);document.getElementById('cd-m').textContent=Math.floor(d%36e5/6e4);document.getElementById('cd-s').textContent=Math.floor(d%6e4/1e3)}t();setInterval(t,1e3)}();
    // Toggle
    function toggle(t){document.getElementById('form-type').value=t;document.getElementById('fp').style.display=t==='plan'?'block':'none';document.getElementById('fz').style.display=t==='zoom'?'block':'none';document.getElementById('history-plan').style.display=t==='plan'?'block':'none';document.getElementById('history-zoom').style.display=t==='zoom'?'block':'none';document.getElementById('tb-plan').classList.toggle('active',t==='plan');document.getElementById('tb-zoom').classList.toggle('active',t==='zoom');['ri1','rd','rdt'].forEach(function(i){var e=document.getElementById(i);if(e)e.required=t==='plan'});['rz','rc','rp','rdt2'].forEach(function(i){var e=document.getElementById(i);if(e)e.required=t==='zoom'})}
    // Modal
    function openModal(p){document.getElementById('mPlat').value=p;document.getElementById('ambModal').classList.add('open')}
    function closeModal(){document.getElementById('ambModal').classList.remove('open')}
    document.getElementById('ambModal').onclick=function(e){if(e.target===this)closeModal()};
    // Video
    function playV(el,t,s){document.querySelectorAll('.v-thumb').forEach(function(x){x.classList.remove('active')});el.classList.add('active');document.getElementById('vMain').innerHTML=t==='youtube'?'<iframe src="https://www.youtube.com/embed/'+s+'" allowfullscreen></iframe>':'<video controls autoplay><source src="'+s+'" type="video/mp4"></video>'}
    // Charts
    document.addEventListener('DOMContentLoaded',function(){var a=document.getElementById('lineChart');if(a)new Chart(a,{type:'line',data:{labels:['W1','W2','W3','W4','W5','W6'],datasets:[{data:[<?php echo e(rand(0,max(1,$directReferrals))); ?>,<?php echo e(rand(0,max(1,$directReferrals))); ?>,<?php echo e($directReferrals); ?>,<?php echo e($activeReferrals); ?>,<?php echo e($directReferrals); ?>,<?php echo e($activeReferrals); ?>],borderColor:'#6366f1',backgroundColor:'rgba(99,102,241,.08)',fill:true,tension:.4,pointRadius:2,borderWidth:2}]},options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,grid:{color:'#f3f4f6'},ticks:{font:{size:8}}},x:{grid:{display:false},ticks:{font:{size:8}}}}}});var b=document.getElementById('donutChart');if(b)new Chart(b,{type:'doughnut',data:{labels:['Active','Inactive'],datasets:[{data:[<?php echo e($activeReferrals); ?>,<?php echo e(max(0,$directReferrals-$activeReferrals)); ?>],backgroundColor:['#6366f1','#e5e7eb'],borderWidth:0}]},options:{responsive:true,maintainAspectRatio:false,cutout:'60%',plugins:{legend:{position:'bottom',labels:{font:{size:8},padding:6}}}}})});
    </script>
</div>
<?php /**PATH C:\xampp\htdocs\bifonepo\mcu.focoin.eu\afonete\resources\views/team-leader/all.blade.php ENDPATH**/ ?>