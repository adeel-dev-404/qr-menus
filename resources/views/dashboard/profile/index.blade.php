@extends('layouts.dashboard')
@section('page-title', 'Profile & Settings')
@section('content')

<style>
/* ── Shared ── */
.section-card { background:#1a1a1a; border:1px solid #222; border-radius:14px; overflow:hidden; margin-bottom:16px; }
.section-head { padding:16px 20px; border-bottom:1px solid #1f1f1f; display:flex; align-items:center; gap:10px; }
.section-head-icon { width:34px;height:34px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0; }
.section-head h3 { font-size:15px;font-weight:700;color:#f0f0f0;margin:0; }
.section-head p  { font-size:12px;color:#555;margin:2px 0 0; }
.section-body { padding:20px; }

.form-label { display:block;font-size:11px;font-weight:600;color:#888;margin-bottom:6px;text-transform:uppercase;letter-spacing:.05em; }
.form-input { width:100%;background:#111;border:1px solid #2a2a2a;border-radius:9px;padding:10px 13px;font-size:14px;color:#f0f0f0;outline:none;transition:border-color .18s; }
.form-input:focus { border-color:#3b82f6; }
.form-input.error { border-color:#ef4444; }
.form-error { font-size:11px;color:#fca5a5;margin-top:4px; }
select.form-input { cursor:pointer; }
textarea.form-input { resize:vertical; }

.two-col { display:grid;grid-template-columns:1fr 1fr;gap:14px; }
.three-col { display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px; }
@media(max-width:600px){ .two-col,.three-col { grid-template-columns:1fr; } }

.btn-save { padding:10px 20px;background:#1d4ed8;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;transition:background .15s; }
.btn-save:hover { background:#1e40af; }
.btn-danger { padding:10px 20px;background:#2d0a0a;color:#fca5a5;border:1px solid #7f1d1d;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer; }

/* ── Avatar uploader ── */
.avatar-wrap { display:flex;align-items:center;gap:16px;margin-bottom:20px; }
.avatar-preview { width:72px;height:72px;border-radius:99px;object-fit:cover;border:2px solid #2a2a2a;cursor:pointer;flex-shrink:0; }
.avatar-initial { width:72px;height:72px;border-radius:99px;background:#1d4ed8;display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;flex-shrink:0;cursor:pointer; }
.upload-btn { padding:8px 14px;background:#1a1a1a;border:1px solid #2a2a2a;border-radius:8px;font-size:13px;color:#aaa;cursor:pointer;transition:all .15s; }
.upload-btn:hover { border-color:#3b82f6;color:#60a5fa; }

/* ── Cover image ── */
.cover-wrap { position:relative;border-radius:12px;overflow:hidden;margin-bottom:16px;cursor:pointer; }
.cover-img { width:100%;height:140px;object-fit:cover;display:block; }
.cover-placeholder { width:100%;height:140px;background:linear-gradient(135deg,#111,#1a1a1a);border:1px dashed #2a2a2a;border-radius:12px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;cursor:pointer; }
.cover-overlay { position:absolute;inset:0;background:rgba(0,0,0,.5);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .2s; }
.cover-wrap:hover .cover-overlay { opacity:1; }

/* ── Logo uploader ── */
.logo-wrap { display:flex;align-items:center;gap:14px;margin-bottom:16px; }
.logo-preview { width:64px;height:64px;border-radius:12px;object-fit:cover;border:1px solid #2a2a2a;cursor:pointer;flex-shrink:0; }
.logo-initial { width:64px;height:64px;border-radius:12px;background:linear-gradient(145deg,#e8a23a,#e8502a);display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#fff;flex-shrink:0;cursor:pointer; }

/* ── Opening hours ── */
.hours-row { display:grid;grid-template-columns:100px 1fr 1fr 80px;gap:10px;align-items:center;padding:8px 0;border-bottom:1px solid #1a1a1a; }
.hours-row:last-child { border-bottom:none; }
.hours-row .day-label { font-size:13px;font-weight:600;color:#aaa;text-transform:capitalize; }
.hours-input { background:#111;border:1px solid #2a2a2a;border-radius:7px;padding:7px 10px;font-size:13px;color:#f0f0f0;outline:none;width:100%; }
.hours-input:focus { border-color:#3b82f6; }
.day-toggle { display:flex;align-items:center;gap:6px;justify-content:flex-end; }
.day-toggle input { accent-color:#3b82f6;width:15px;height:15px;cursor:pointer; }
.day-toggle label { font-size:12px;color:#555;cursor:pointer; }
@media(max-width:540px){
    .hours-row { grid-template-columns:1fr 1fr;grid-template-rows:auto auto; }
    .day-label { grid-column:1; }
    .day-toggle { grid-column:2;justify-content:flex-start; }
    .hours-input { grid-column:span 1; }
}

/* ── Social links ── */
.social-row { display:flex;align-items:center;gap:10px;margin-bottom:12px; }
.social-icon { width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0; }

/* ── Alert ── */
.alert-success { background:#052e16;border:1px solid #166534;color:#86efac;border-radius:10px;padding:12px 16px;font-size:13px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between; }
.alert-error   { background:#2d0a0a;border:1px solid #7f1d1d;color:#fca5a5;border-radius:10px;padding:12px 16px;font-size:13px;margin-bottom:16px; }

/* ── Tabs ── */
.tab-bar { display:flex;gap:6px;margin-bottom:20px;flex-wrap:wrap; }
.tab-btn { padding:8px 18px;border-radius:99px;font-size:13px;font-weight:500;cursor:pointer;border:1px solid #2a2a2a;background:#111;color:#666;transition:all .15s; }
.tab-btn.active { background:#1d4ed8;color:#fff;border-color:#1d4ed8; }
.tab-btn:hover:not(.active) { border-color:#444;color:#ccc; }
.tab-panel { display:none; }
.tab-panel.active { display:block; }
</style>

<div style="max-width:780px;">

    {{-- Flash messages --}}
    @if(session('success'))
    <div class="alert-success">
        <span>✅ {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:#86efac;font-size:18px;">&times;</button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert-error">
        @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
    </div>
    @endif

    {{-- Tab Navigation --}}
    <div class="tab-bar">
        <button class="tab-btn active" onclick="switchTab('personal', this)">👤 Personal Info</button>
        <button class="tab-btn" onclick="switchTab('password', this)">🔒 Password</button>
        <button class="tab-btn" onclick="switchTab('restaurant', this)">🏠 Restaurant Profile</button>
        <button class="tab-btn" onclick="switchTab('hours', this)">🕐 Opening Hours</button>
        <button class="tab-btn" onclick="switchTab('social', this)">🔗 Social Links</button>
    </div>

    {{-- ════ TAB 1: Personal Info ════ --}}
    <div class="tab-panel active" id="tab-personal">
        <div class="section-card">
            <div class="section-head">
                <div class="section-head-icon" style="background:#0f1729;">👤</div>
                <div>
                    <h3>Personal Information</h3>
                    <p>Update your name, email and profile photo</p>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('dashboard.profile.personal') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Avatar --}}
                    <div class="avatar-wrap">
                        @if(auth()->user()->avatar)
                            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                 class="avatar-preview" id="avatarPreview"
                                 onclick="document.getElementById('avatarInput').click()">
                        @else
                            <div class="avatar-initial" id="avatarInitial"
                                 onclick="document.getElementById('avatarInput').click()">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <button type="button" class="upload-btn"
                                    onclick="document.getElementById('avatarInput').click()">
                                Change Photo
                            </button>
                            <p style="font-size:11px;color:#555;margin-top:6px;">JPG, PNG — max 2MB</p>
                        </div>
                        <input type="file" id="avatarInput" name="avatar" accept="image/*"
                               style="display:none;" onchange="previewAvatar(this)">
                    </div>

                    <div class="two-col" style="margin-bottom:14px;">
                        <div>
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name"
                                   value="{{ old('name', auth()->user()->name) }}"
                                   class="form-input {{ $errors->has('name') ? 'error' : '' }}">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                   class="form-input" placeholder="+92 300 0000000">
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               class="form-input {{ $errors->has('email') ? 'error' : '' }}">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="btn-save">Save Personal Info</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ════ TAB 2: Password ════ --}}
    <div class="tab-panel" id="tab-password">
        <div class="section-card">
            <div class="section-head">
                <div class="section-head-icon" style="background:#1a0a2e;">🔒</div>
                <div>
                    <h3>Change Password</h3>
                    <p>Use a strong password with at least 8 characters</p>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('dashboard.profile.password') }}">
                    @csrf

                    <div style="margin-bottom:14px;">
                        <label class="form-label">Current Password *</label>
                        <input type="password" name="current_password"
                               class="form-input {{ $errors->has('current_password') ? 'error' : '' }}"
                               placeholder="Enter current password">
                        @error('current_password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div class="two-col" style="margin-bottom:20px;">
                        <div>
                            <label class="form-label">New Password *</label>
                            <input type="password" name="password"
                                   class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                                   placeholder="Min 8 characters"
                                   oninput="checkStrength(this.value)">
                            <div style="margin-top:6px;">
                                <div style="height:3px;background:#222;border-radius:99px;overflow:hidden;">
                                    <div id="strengthBar" style="height:100%;width:0%;border-radius:99px;transition:all .3s;"></div>
                                </div>
                                <span id="strengthLabel" style="font-size:11px;color:#555;"></span>
                            </div>
                            @error('password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Confirm New Password *</label>
                            <input type="password" name="password_confirmation"
                                   class="form-input" placeholder="Re-enter new password">
                        </div>
                    </div>

                    <button type="submit" class="btn-save">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ════ TAB 3: Restaurant Profile ════ --}}
    <div class="tab-panel" id="tab-restaurant">
        <form method="POST" action="{{ route('dashboard.profile.restaurant') }}" enctype="multipart/form-data">
            @csrf

            {{-- Cover Image --}}
            <div class="section-card">
                <div class="section-head">
                    <div class="section-head-icon" style="background:#1c1100;">🖼</div>
                    <div>
                        <h3>Cover Image</h3>
                        <p>Banner shown at top of your public menu</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="cover-wrap" onclick="document.getElementById('coverInput').click()">
                        @if($restaurant->cover_image)
                            <img src="{{ Storage::url($restaurant->cover_image) }}"
                                 class="cover-img" id="coverPreview">
                        @else
                            <div class="cover-placeholder" id="coverPlaceholder">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     style="width:32px;height:32px;color:#444;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p style="color:#555;font-size:13px;">Click to upload cover image</p>
                                <p style="color:#444;font-size:11px;">Recommended: 1200×400px — Max 4MB</p>
                            </div>
                        @endif
                        <div class="cover-overlay">
                            <span style="background:rgba(0,0,0,.7);color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;">
                                Change Cover
                            </span>
                        </div>
                    </div>
                    <input type="file" id="coverInput" name="cover_image" accept="image/*"
                           style="display:none;" onchange="previewCover(this)">
                </div>
            </div>

            {{-- Logo + Basic Info --}}
            <div class="section-card">
                <div class="section-head">
                    <div class="section-head-icon" style="background:#052e16;">🏠</div>
                    <div>
                        <h3>Restaurant Details</h3>
                        <p>Basic info shown on your public menu</p>
                    </div>
                </div>
                <div class="section-body">

                    {{-- Logo --}}
                    <div class="logo-wrap">
                        @if($restaurant->logo)
                            <img src="{{ Storage::url($restaurant->logo) }}"
                                 class="logo-preview" id="logoPreview"
                                 onclick="document.getElementById('logoInput').click()">
                        @else
                            <div class="logo-initial" id="logoInitial"
                                 onclick="document.getElementById('logoInput').click()">
                                {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <button type="button" class="upload-btn"
                                    onclick="document.getElementById('logoInput').click()">
                                Change Logo
                            </button>
                            <p style="font-size:11px;color:#555;margin-top:5px;">Square image — max 2MB</p>
                        </div>
                        <input type="file" id="logoInput" name="logo" accept="image/*"
                               style="display:none;" onchange="previewLogo(this)">
                    </div>

                    <div class="two-col" style="margin-bottom:14px;">
                        <div>
                            <label class="form-label">Restaurant Name *</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $restaurant->name) }}"
                                   class="form-input {{ $errors->has('name') ? 'error' : '' }}">
                            @error('name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $restaurant->phone) }}"
                                   class="form-input" placeholder="+92 300 0000000">
                        </div>
                    </div>

                    <div class="two-col" style="margin-bottom:14px;">
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $restaurant->email) }}"
                                   class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Address</label>
                            <input type="text" name="address"
                                   value="{{ old('address', $restaurant->address) }}"
                                   class="form-input" placeholder="City, Area">
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label class="form-label">About / Description</label>
                        <textarea name="about" rows="3" class="form-input"
                                  placeholder="Tell customers about your restaurant...">{{ old('about', $restaurant->about) }}</textarea>
                        <p style="font-size:11px;color:#555;margin-top:4px;">Shown on your public menu page</p>
                    </div>

                    <button type="submit" class="btn-save">Save Restaurant Profile</button>
                </div>
            </div>

        </form>
    </div>

    {{-- ════ TAB 4: Opening Hours ════ --}}
    <div class="tab-panel" id="tab-hours">
        <div class="section-card">
            <div class="section-head">
                <div class="section-head-icon" style="background:#111a0a;">🕐</div>
                <div>
                    <h3>Opening Hours</h3>
                    <p>Set your weekly schedule — shown on the public menu</p>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('dashboard.profile.restaurant') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Hidden fields to preserve other restaurant data --}}
                    <input type="hidden" name="name"    value="{{ $restaurant->name }}">
                    <input type="hidden" name="phone"   value="{{ $restaurant->phone }}">
                    <input type="hidden" name="email"   value="{{ $restaurant->email }}">
                    <input type="hidden" name="address" value="{{ $restaurant->address }}">
                    <input type="hidden" name="about"   value="{{ $restaurant->about }}">
                    <input type="hidden" name="whatsapp"   value="{{ $restaurant->whatsapp }}">
                    <input type="hidden" name="instagram"  value="{{ $restaurant->instagram }}">
                    <input type="hidden" name="facebook"   value="{{ $restaurant->facebook }}">

                    <div style="margin-bottom:20px;">
                        @foreach($days as $day)
                        @php $h = $openingHours[$day] ?? ['open'=>true,'from'=>'09:00','to'=>'22:00']; @endphp
                        <div class="hours-row">
                            <span class="day-label">{{ ucfirst($day) }}</span>
                            <input type="time" name="hours_{{ $day }}_from"
                                   value="{{ $h['from'] ?? '09:00' }}"
                                   class="hours-input">
                            <input type="time" name="hours_{{ $day }}_to"
                                   value="{{ $h['to'] ?? '22:00' }}"
                                   class="hours-input">
                            <div class="day-toggle">
                                <input type="checkbox" name="hours_{{ $day }}_open"
                                       id="open_{{ $day }}"
                                       value="1"
                                       {{ ($h['open'] ?? true) ? 'checked' : '' }}>
                                <label for="open_{{ $day }}">Open</label>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="submit" class="btn-save">Save Opening Hours</button>
                </form>
            </div>
        </div>
    </div>

    {{-- ════ TAB 5: Social Links ════ --}}
    <div class="tab-panel" id="tab-social">
        <div class="section-card">
            <div class="section-head">
                <div class="section-head-icon" style="background:#0a1a1a;">🔗</div>
                <div>
                    <h3>Social Media Links</h3>
                    <p>Shown as icons on your public menu</p>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('dashboard.profile.restaurant') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Hidden fields --}}
                    <input type="hidden" name="name"    value="{{ $restaurant->name }}">
                    <input type="hidden" name="phone"   value="{{ $restaurant->phone }}">
                    <input type="hidden" name="email"   value="{{ $restaurant->email }}">
                    <input type="hidden" name="address" value="{{ $restaurant->address }}">
                    <input type="hidden" name="about"   value="{{ $restaurant->about }}">
                    @foreach($days as $day)
                    @php $h = $openingHours[$day] ?? ['open'=>true,'from'=>'09:00','to'=>'22:00']; @endphp
                    <input type="hidden" name="hours_{{ $day }}_from" value="{{ $h['from'] ?? '09:00' }}">
                    <input type="hidden" name="hours_{{ $day }}_to"   value="{{ $h['to'] ?? '22:00' }}">
                    @if($h['open'] ?? true) <input type="hidden" name="hours_{{ $day }}_open" value="1"> @endif
                    @endforeach

                    <div class="social-row">
                        <div class="social-icon" style="background:#052e16;">💬</div>
                        <div style="flex:1;">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="text" name="whatsapp"
                                   value="{{ old('whatsapp', $restaurant->whatsapp) }}"
                                   class="form-input" placeholder="923001234567 (no + or spaces)">
                            <p style="font-size:11px;color:#555;margin-top:4px;">Customers can tap to message you directly</p>
                        </div>
                    </div>

                    <div class="social-row">
                        <div class="social-icon" style="background:#1a0a2e;">📸</div>
                        <div style="flex:1;">
                            <label class="form-label">Instagram Username</label>
                            <input type="text" name="instagram"
                                   value="{{ old('instagram', $restaurant->instagram) }}"
                                   class="form-input" placeholder="your_restaurant">
                        </div>
                    </div>

                    <div class="social-row" style="margin-bottom:24px;">
                        <div class="social-icon" style="background:#0a1029;">👍</div>
                        <div style="flex:1;">
                            <label class="form-label">Facebook Page Username or URL</label>
                            <input type="text" name="facebook"
                                   value="{{ old('facebook', $restaurant->facebook) }}"
                                   class="form-input" placeholder="yourrestaurantpage">
                        </div>
                    </div>

                    <button type="submit" class="btn-save">Save Social Links</button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
// ── Tab switching ──
function switchTab(name, btn) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// ── Image previews ──
function previewAvatar(input) {
    if (!input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    const preview = document.getElementById('avatarPreview');
    const initial = document.getElementById('avatarInitial');
    if (preview) { preview.src = url; }
    else if (initial) {
        const img = document.createElement('img');
        img.src = url;
        img.className = 'avatar-preview';
        img.id = 'avatarPreview';
        img.onclick = () => document.getElementById('avatarInput').click();
        initial.replaceWith(img);
    }
}

function previewLogo(input) {
    if (!input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    const preview = document.getElementById('logoPreview');
    const initial = document.getElementById('logoInitial');
    if (preview) { preview.src = url; }
    else if (initial) {
        const img = document.createElement('img');
        img.src = url;
        img.className = 'logo-preview';
        img.id = 'logoPreview';
        img.onclick = () => document.getElementById('logoInput').click();
        initial.replaceWith(img);
    }
}

function previewCover(input) {
    if (!input.files[0]) return;
    const url = URL.createObjectURL(input.files[0]);
    const placeholder = document.getElementById('coverPlaceholder');
    const existing    = document.getElementById('coverPreview');
    const wrap        = input.closest ? input.parentElement : document.querySelector('.cover-wrap');
    if (existing) { existing.src = url; return; }
    if (placeholder) {
        const img = document.createElement('img');
        img.src       = url;
        img.className = 'cover-img';
        img.id        = 'coverPreview';
        placeholder.replaceWith(img);
    }
}

// ── Password strength ──
function checkStrength(pw) {
    let score = 0;
    if (pw.length >= 8) score++;
    if (/[A-Z]/.test(pw)) score++;
    if (/[0-9]/.test(pw)) score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    const map = [
        {w:'0%',   bg:'transparent', t:''},
        {w:'25%',  bg:'#ef4444',     t:'Weak'},
        {w:'50%',  bg:'#f97316',     t:'Fair'},
        {w:'75%',  bg:'#facc15',     t:'Good'},
        {w:'100%', bg:'#22c55e',     t:'Strong'},
    ];
    document.getElementById('strengthBar').style.width      = map[score].w;
    document.getElementById('strengthBar').style.background = map[score].bg;
    document.getElementById('strengthLabel').textContent    = map[score].t;
    document.getElementById('strengthLabel').style.color    = map[score].bg;
}

// ── Auto-open correct tab on validation error ──
@if($errors->has('current_password') || $errors->has('password'))
    switchTab('password', document.querySelectorAll('.tab-btn')[1]);
@elseif($errors->has('name') || $errors->has('email'))
    // Could be personal or restaurant — check which form was submitted
@endif
</script>

@endsection