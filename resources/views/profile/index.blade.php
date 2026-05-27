@extends('layouts.dashboard')
@section('page-title', 'Profile & Settings')
@section('content')

<style>
.profile-tab { display:none; }
.profile-tab.active { display:block; }

.tab-btn {
    display:flex; align-items:center; gap:8px;
    padding:10px 16px; border-radius:10px;
    font-size:13px; font-weight:500;
    cursor:pointer; border:none; background:none;
    color:#888; width:100%; text-align:left;
    transition:all .15s;
}
.tab-btn:hover { background:#1f1f1f; color:#ccc; }
.tab-btn.active { background:#1d4ed8; color:#fff; }
.tab-btn svg { width:16px; height:16px; flex-shrink:0; }

.section-card {
    background:#1a1a1a; border:1px solid #222;
    border-radius:14px; overflow:hidden; margin-bottom:16px;
}
.section-head {
    padding:16px 20px; border-bottom:1px solid #222;
    font-size:14px; font-weight:700; color:#e2e8f0;
    display:flex; align-items:center; gap:8px;
}
.section-body { padding:20px; }

.f-label {
    display:block; font-size:11px; font-weight:600;
    color:#666; text-transform:uppercase; letter-spacing:.06em;
    margin-bottom:6px;
}
.f-input {
    width:100%; background:#111; border:1px solid #2a2a2a;
    border-radius:8px; padding:10px 12px; font-size:14px;
    color:#e2e8f0; outline:none; transition:border-color .2s;
}
.f-input:focus { border-color:#3b82f6; }
.f-input.error { border-color:#ef4444; }
textarea.f-input { resize:vertical; }
.f-error { color:#fca5a5; font-size:11px; margin-top:3px; }
.f-hint  { color:#555; font-size:11px; margin-top:3px; }

.two-col { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.three-col { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
@media(max-width:640px) { .two-col,.three-col { grid-template-columns:1fr; } }

.btn-save {
    padding:10px 22px; background:#1d4ed8; color:#fff;
    border:none; border-radius:8px; font-size:14px;
    font-weight:600; cursor:pointer; transition:background .15s;
}
.btn-save:hover { background:#1e40af; }
.btn-danger-sm {
    padding:6px 12px; background:#2d0a0a; color:#fca5a5;
    border:1px solid #7f1d1d; border-radius:6px; font-size:12px;
    cursor:pointer; text-decoration:none; display:inline-block;
}

/* Image upload zone */
.img-zone {
    border:2px dashed #2a2a2a; border-radius:10px;
    padding:20px; text-align:center; cursor:pointer;
    transition:border-color .2s;
}
.img-zone:hover { border-color:#3b82f6; }

/* Hours grid */
.hours-row {
    display:grid; grid-template-columns:100px 60px 1fr 1fr;
    gap:10px; align-items:center; padding:8px 0;
    border-bottom:1px solid #1f1f1f;
}
.hours-row:last-child { border-bottom:none; }
@media(max-width:560px) {
    .hours-row { grid-template-columns:1fr 1fr; grid-template-rows:auto auto; }
    .hours-row .day-name { grid-column:1; }
    .hours-row .day-toggle { grid-column:2; justify-self:end; }
}
.day-name { font-size:13px; font-weight:600; color:#aaa; text-transform:capitalize; }
.time-input {
    background:#111; border:1px solid #2a2a2a; border-radius:6px;
    padding:6px 10px; color:#e2e8f0; font-size:13px;
    outline:none; width:100%;
}
.time-input:focus { border-color:#3b82f6; }
.toggle-switch { position:relative; width:36px; height:20px; flex-shrink:0; }
.toggle-switch input { opacity:0; width:0; height:0; }
.toggle-slider {
    position:absolute; cursor:pointer; inset:0;
    background:#2a2a2a; border-radius:99px; transition:.2s;
}
.toggle-slider:before {
    content:''; position:absolute;
    width:14px; height:14px; left:3px; bottom:3px;
    background:#fff; border-radius:50%; transition:.2s;
}
.toggle-switch input:checked + .toggle-slider { background:#1d4ed8; }
.toggle-switch input:checked + .toggle-slider:before { transform:translateX(16px); }

/* Social input with icon prefix */
.social-wrap { position:relative; }
.social-prefix {
    position:absolute; left:12px; top:50%; transform:translateY(-50%);
    font-size:13px; color:#555; pointer-events:none; white-space:nowrap;
}
.social-wrap .f-input { padding-left:32px; }

/* Avatar ring */
.avatar-ring {
    width:80px; height:80px; border-radius:50%;
    border:2px solid #2a2a2a; overflow:hidden;
    background:#1a1a1a; display:flex; align-items:center;
    justify-content:center; flex-shrink:0;
}
.avatar-ring img { width:100%; height:100%; object-fit:cover; }
.avatar-initial {
    font-size:28px; font-weight:700; color:#fff;
    background:linear-gradient(135deg,#1d4ed8,#7c3aed);
    width:100%; height:100%; display:flex; align-items:center; justify-content:center;
}
</style>

<div style="max-width:900px;">

    {{-- Page header --}}
    <div style="margin-bottom:20px;">
        <h2 style="font-size:20px;font-weight:700;color:#fff;margin:0;">Profile & Settings</h2>
        <p style="font-size:13px;color:#666;margin:4px 0 0;">Manage your account and restaurant details</p>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
    <div style="background:#052e16;border:1px solid #166534;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:13px;color:#86efac;">✅ {{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" style="background:none;border:none;color:#4ade80;cursor:pointer;font-size:18px;">&times;</button>
    </div>
    @endif

    <div style="display:grid; grid-template-columns:200px 1fr; gap:20px; align-items:start;">

        @media-placeholder

        {{-- Sidebar Tabs --}}
        <div style="background:#1a1a1a;border:1px solid #222;border-radius:14px;padding:10px;position:sticky;top:80px;">
            <p style="font-size:10px;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:.06em;padding:8px 10px 4px;">Account</p>

            <button class="tab-btn active" onclick="switchTab('personal', this)">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Personal Info
            </button>
            <button class="tab-btn" onclick="switchTab('password', this)">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                Password
            </button>

            <p style="font-size:10px;font-weight:600;color:#555;text-transform:uppercase;letter-spacing:.06em;padding:12px 10px 4px;">Restaurant</p>

            <button class="tab-btn" onclick="switchTab('restaurant', this)">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Restaurant Details
            </button>
            <button class="tab-btn" onclick="switchTab('hours', this)">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Opening Hours
            </button>
            <button class="tab-btn" onclick="switchTab('social', this)">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Social Links
            </button>
        </div>

        {{-- Tab Content --}}
        <div>

            {{-- ═══ PERSONAL INFO ═══ --}}
            <div id="tab-personal" class="profile-tab active">

                <div class="section-card">
                    <div class="section-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;color:#888">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Personal Information
                    </div>
                    <div class="section-body">

                        {{-- Avatar --}}
                        <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;">
                            <div class="avatar-ring">
                                @if($user->avatar)
                                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" id="avatarPreview">
                                @else
                                    <div class="avatar-initial" id="avatarInitial">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <button type="button"
                                        onclick="document.getElementById('avatarInput').click()"
                                        style="padding:7px 14px;background:#1a1a1a;border:1px solid #2a2a2a;border-radius:7px;color:#aaa;font-size:13px;cursor:pointer;display:block;margin-bottom:6px;">
                                    📷 Change Photo
                                </button>
                                @if($user->avatar)
                                <form method="POST" action="{{ route('dashboard.profile.avatar.delete') }}" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Remove</button>
                                </form>
                                @endif
                                <p style="font-size:11px;color:#555;margin-top:4px;">JPG, PNG, WebP — max 2MB</p>
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('dashboard.profile.personal') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <input type="file" id="avatarInput" name="avatar" accept="image/*"
                                   style="display:none" onchange="previewAvatar(this)">

                            <div class="two-col" style="margin-bottom:14px;">
                                <div>
                                    <label class="f-label">Full Name *</label>
                                    <input type="text" name="name"
                                           value="{{ old('name', $user->name) }}"
                                           class="f-input {{ $errors->has('name') ? 'error' : '' }}">
                                    @error('name') <p class="f-error">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="f-label">Email Address *</label>
                                    <input type="email" name="email"
                                           value="{{ old('email', $user->email) }}"
                                           class="f-input {{ $errors->has('email') ? 'error' : '' }}">
                                    @error('email') <p class="f-error">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div style="display:flex;align-items:center;justify-content:space-between;padding-top:8px;border-top:1px solid #1f1f1f;">
                                <div>
                                    <p style="font-size:12px;color:#666;">Role:
                                        <span style="color:#60a5fa;font-weight:600;">{{ ucwords(str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'Staff')) }}</span>
                                    </p>
                                </div>
                                <button type="submit" class="btn-save">Save Changes</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>

            {{-- ═══ PASSWORD ═══ --}}
            <div id="tab-password" class="profile-tab">
                <div class="section-card">
                    <div class="section-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;color:#888">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Change Password
                    </div>
                    <div class="section-body">
                        <form method="POST" action="{{ route('dashboard.profile.password') }}" style="max-width:420px;">
                            @csrf

                            <div style="margin-bottom:14px;">
                                <label class="f-label">Current Password *</label>
                                <input type="password" name="current_password"
                                       class="f-input {{ $errors->has('current_password') ? 'error' : '' }}"
                                       placeholder="Enter current password">
                                @error('current_password') <p class="f-error">{{ $message }}</p> @enderror
                            </div>
                            <div style="margin-bottom:14px;">
                                <label class="f-label">New Password *</label>
                                <input type="password" name="password"
                                       class="f-input {{ $errors->has('password') ? 'error' : '' }}"
                                       placeholder="Min. 8 characters"
                                       oninput="strengthCheck(this.value)">
                                @error('password') <p class="f-error">{{ $message }}</p> @enderror
                                <div style="margin-top:6px;">
                                    <div style="height:3px;background:#2a2a2a;border-radius:99px;overflow:hidden;">
                                        <div id="pwBar" style="height:100%;width:0;border-radius:99px;transition:all .3s;"></div>
                                    </div>
                                    <span id="pwLabel" style="font-size:11px;color:#555;"></span>
                                </div>
                            </div>
                            <div style="margin-bottom:20px;">
                                <label class="f-label">Confirm New Password *</label>
                                <input type="password" name="password_confirmation"
                                       class="f-input"
                                       placeholder="Re-enter new password">
                            </div>

                            <button type="submit" class="btn-save">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ═══ RESTAURANT DETAILS ═══ --}}
            <div id="tab-restaurant" class="profile-tab">
                @if($restaurant)
                <form method="POST"
                      action="{{ route('dashboard.profile.restaurant') }}"
                      enctype="multipart/form-data">
                @csrf

                {{-- Cover Image --}}
                <div class="section-card">
                    <div class="section-head">🖼 Cover Image</div>
                    <div class="section-body">

                        {{-- Current cover --}}
                        @if($restaurant->cover_image)
                        <div style="margin-bottom:12px;position:relative;">
                            <img src="{{ Storage::url($restaurant->cover_image) }}"
                                 style="width:100%;height:160px;object-fit:cover;border-radius:10px;border:1px solid #2a2a2a;"
                                 id="coverPreview">
                            <div style="position:absolute;top:8px;right:8px;display:flex;gap:6px;">
                                <button type="button" onclick="document.getElementById('coverInput').click()"
                                        style="padding:5px 10px;background:rgba(0,0,0,.7);color:#fff;border:none;border-radius:6px;font-size:12px;cursor:pointer;">
                                    Change
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="img-zone" onclick="document.getElementById('coverInput').click()" id="coverZone">
                            <div id="coverPreviewWrap" style="display:none;margin-bottom:10px;">
                                <img id="coverPreview" style="max-height:160px;border-radius:8px;width:100%;object-fit:cover;">
                            </div>
                            <div id="coverPlaceholder">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:32px;height:32px;color:#444;margin:0 auto 8px;display:block">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p style="color:#555;font-size:13px;">Click to upload cover image</p>
                                <p style="color:#444;font-size:11px;margin-top:4px;">Recommended: 1200×400px — max 4MB</p>
                            </div>
                        </div>
                        @endif

                        <input type="file" id="coverInput" name="cover_image" accept="image/*"
                               style="display:none" onchange="previewCover(this)">

                        @if($restaurant->cover_image)
                        <div style="margin-top:8px;display:flex;gap:8px;">
                            <form method="POST" action="{{ route('dashboard.profile.cover.delete') }}" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-sm">🗑 Remove Cover</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Logo + Basic Info --}}
                <div class="section-card">
                    <div class="section-head">🏠 Basic Information</div>
                    <div class="section-body">

                        <div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:20px;flex-wrap:wrap;">
                            {{-- Logo --}}
                            <div style="text-align:center;">
                                <div style="width:80px;height:80px;border-radius:14px;overflow:hidden;background:#111;border:1px solid #2a2a2a;margin-bottom:8px;">
                                    @if($restaurant->logo)
                                        <img src="{{ Storage::url($restaurant->logo) }}"
                                             style="width:100%;height:100%;object-fit:cover;"
                                             id="logoPreview">
                                    @else
                                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#e8a23a,#e8502a);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff;" id="logoInitial">
                                            {{ strtoupper(substr($restaurant->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <button type="button" onclick="document.getElementById('logoInput').click()"
                                        style="padding:5px 10px;background:#1a1a1a;border:1px solid #2a2a2a;border-radius:6px;color:#888;font-size:11px;cursor:pointer;display:block;margin-bottom:4px;">
                                    Change Logo
                                </button>
                                @if($restaurant->logo)
                                <form method="POST" action="{{ route('dashboard.profile.logo.delete') }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding:4px 10px;background:#2d0a0a;color:#fca5a5;border:1px solid #7f1d1d;border-radius:6px;font-size:11px;cursor:pointer;">Remove</button>
                                </form>
                                @endif
                                <input type="file" id="logoInput" name="logo" accept="image/*"
                                       style="display:none" onchange="previewLogo(this)">
                            </div>

                            {{-- Basic fields --}}
                            <div style="flex:1;min-width:200px;">
                                <div style="margin-bottom:12px;">
                                    <label class="f-label">Restaurant Name *</label>
                                    <input type="text" name="name"
                                           value="{{ old('name', $restaurant->name) }}"
                                           class="f-input">
                                </div>
                                <div>
                                    <label class="f-label">About / Description</label>
                                    <textarea name="about" rows="3" class="f-input"
                                              placeholder="Tell customers about your restaurant...">{{ old('about', $restaurant->about) }}</textarea>
                                    <p class="f-hint">Shown on the public menu page</p>
                                </div>
                            </div>
                        </div>

                        <div class="three-col" style="margin-bottom:14px;">
                            <div>
                                <label class="f-label">Phone</label>
                                <input type="text" name="phone"
                                       value="{{ old('phone', $restaurant->phone) }}"
                                       class="f-input" placeholder="e.g. 03001234567">
                            </div>
                            <div>
                                <label class="f-label">Email</label>
                                <input type="email" name="email"
                                       value="{{ old('email', $restaurant->email) }}"
                                       class="f-input" placeholder="info@restaurant.com">
                            </div>
                            <div>
                                <label class="f-label">Address</label>
                                <input type="text" name="address"
                                       value="{{ old('address', $restaurant->address) }}"
                                       class="f-input" placeholder="City, Area">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="text-align:right;">
                    <button type="submit" class="btn-save">💾 Save Restaurant Profile</button>
                </div>

                </form>
                @else
                <div style="background:#1a1a1a;border:1px solid #222;border-radius:14px;padding:40px;text-align:center;color:#555;">
                    <p>No restaurant assigned to your account yet.</p>
                </div>
                @endif
            </div>

            {{-- ═══ OPENING HOURS ═══ --}}
            <div id="tab-hours" class="profile-tab">
                @if($restaurant)
                <form method="POST" action="{{ route('dashboard.profile.restaurant') }}"
                      enctype="multipart/form-data">
                @csrf

                {{-- Hidden fields to preserve other restaurant data --}}
                <input type="hidden" name="name"    value="{{ $restaurant->name }}">
                <input type="hidden" name="phone"   value="{{ $restaurant->phone }}">
                <input type="hidden" name="email"   value="{{ $restaurant->email }}">
                <input type="hidden" name="address" value="{{ $restaurant->address }}">
                <input type="hidden" name="about"   value="{{ $restaurant->about }}">

                <div class="section-card">
                    <div class="section-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;color:#888">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Opening Hours
                    </div>
                    <div class="section-body">

                        <p style="font-size:12px;color:#666;margin-bottom:16px;">
                            Set your opening hours. These are displayed on your public menu page.
                        </p>

                        @foreach($days as $day)
                        @php
                            $h    = $hours[$day] ?? ['open' => false, 'from' => '09:00', 'to' => '22:00'];
                            $open = $h['open'] ?? false;
                        @endphp
                        <div class="hours-row" id="row-{{ $day }}">
                            <span class="day-name">{{ ucfirst($day) }}</span>

                            <label class="toggle-switch day-toggle">
                                <input type="checkbox" name="hours_{{ $day }}_open" value="1"
                                       {{ $open ? 'checked' : '' }}
                                       onchange="toggleDay('{{ $day }}', this.checked)">
                                <span class="toggle-slider"></span>
                            </label>

                            <div id="time-{{ $day }}" style="{{ !$open ? 'opacity:.3;pointer-events:none;' : '' }}">
                                <input type="time" name="hours_{{ $day }}_from"
                                       value="{{ $h['from'] ?? '09:00' }}"
                                       class="time-input">
                            </div>
                            <div id="time2-{{ $day }}" style="{{ !$open ? 'opacity:.3;pointer-events:none;' : '' }}">
                                <input type="time" name="hours_{{ $day }}_to"
                                       value="{{ $h['to'] ?? '22:00' }}"
                                       class="time-input">
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>

                <div style="text-align:right;">
                    <button type="submit" class="btn-save">💾 Save Opening Hours</button>
                </div>

                </form>
                @endif
            </div>

            {{-- ═══ SOCIAL LINKS ═══ --}}
            <div id="tab-social" class="profile-tab">
                @if($restaurant)
                <form method="POST" action="{{ route('dashboard.profile.restaurant') }}"
                      enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="name"    value="{{ $restaurant->name }}">
                <input type="hidden" name="phone"   value="{{ $restaurant->phone }}">
                <input type="hidden" name="email"   value="{{ $restaurant->email }}">
                <input type="hidden" name="address" value="{{ $restaurant->address }}">
                <input type="hidden" name="about"   value="{{ $restaurant->about }}">

                <div class="section-card">
                    <div class="section-head">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;color:#888">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Social Media Links
                    </div>
                    <div class="section-body" style="display:flex;flex-direction:column;gap:14px;">

                        {{-- WhatsApp --}}
                        <div>
                            <label class="f-label">📱 WhatsApp Number</label>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span style="background:#052e16;border:1px solid #166534;color:#4ade80;padding:10px 12px;border-radius:8px;font-size:13px;font-weight:600;white-space:nowrap;">+92</span>
                                <input type="text" name="whatsapp"
                                       value="{{ old('whatsapp', ltrim($restaurant->whatsapp ?? '', '+92')) }}"
                                       class="f-input"
                                       placeholder="3001234567">
                            </div>
                            <p class="f-hint">Customers can click to chat with you directly</p>
                        </div>

                        {{-- Instagram --}}
                        <div>
                            <label class="f-label">📸 Instagram</label>
                            <div class="social-wrap">
                                <span class="social-prefix">@</span>
                                <input type="text" name="instagram"
                                       value="{{ old('instagram', ltrim($restaurant->instagram ?? '', '@')) }}"
                                       class="f-input"
                                       placeholder="yourrestaurant" style="padding-left:28px;">
                            </div>
                        </div>

                        {{-- Facebook --}}
                        <div>
                            <label class="f-label">👍 Facebook Page</label>
                            <div class="social-wrap">
                                <span class="social-prefix" style="font-size:11px;">facebook.com/</span>
                                <input type="text" name="facebook"
                                       value="{{ old('facebook', $restaurant->facebook) }}"
                                       class="f-input"
                                       placeholder="yourpagename" style="padding-left:88px;">
                            </div>
                        </div>

                        {{-- Preview --}}
                        @if($restaurant->whatsapp || $restaurant->instagram || $restaurant->facebook)
                        <div style="background:#111;border:1px solid #222;border-radius:10px;padding:14px;">
                            <p style="font-size:11px;color:#666;margin-bottom:10px;text-transform:uppercase;letter-spacing:.05em;">Preview on public menu</p>
                            <div style="display:flex;gap:10px;flex-wrap:wrap;">
                                @if($restaurant->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $restaurant->whatsapp) }}"
                                   target="_blank"
                                   style="display:flex;align-items:center;gap:6px;padding:7px 14px;background:#052e16;border:1px solid #166534;border-radius:8px;color:#4ade80;font-size:13px;text-decoration:none;">
                                    📱 WhatsApp
                                </a>
                                @endif
                                @if($restaurant->instagram)
                                <a href="https://instagram.com/{{ ltrim($restaurant->instagram, '@') }}"
                                   target="_blank"
                                   style="display:flex;align-items:center;gap:6px;padding:7px 14px;background:#1a0a2e;border:1px solid #4c1d95;border-radius:8px;color:#c084fc;font-size:13px;text-decoration:none;">
                                    📸 Instagram
                                </a>
                                @endif
                                @if($restaurant->facebook)
                                <a href="https://facebook.com/{{ $restaurant->facebook }}"
                                   target="_blank"
                                   style="display:flex;align-items:center;gap:6px;padding:7px 14px;background:#0f1729;border:1px solid #1e3a5f;border-radius:8px;color:#60a5fa;font-size:13px;text-decoration:none;">
                                    👍 Facebook
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <div style="text-align:right;">
                    <button type="submit" class="btn-save">💾 Save Social Links</button>
                </div>

                </form>
                @endif
            </div>

        </div>{{-- end tab content --}}
    </div>{{-- end grid --}}
</div>

<script>
// Tab switching
function switchTab(name, btn) {
    document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    btn.classList.add('active');
}

// Open on correct tab if redirected back with error
@if(session('tab'))
    document.addEventListener('DOMContentLoaded', () => {
        const btn = document.querySelector(`[onclick="switchTab('{{ session('tab') }}', this)"]`);
        if (btn) switchTab('{{ session('tab') }}', btn);
    });
@endif

// Avatar preview
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const initial = document.getElementById('avatarInitial');
            let img = document.getElementById('avatarPreview');
            if (!img) {
                img = document.createElement('img');
                img.id = 'avatarPreview';
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                if (initial) initial.replaceWith(img);
                else document.querySelector('.avatar-ring').appendChild(img);
            }
            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Logo preview
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const initial = document.getElementById('logoInitial');
            let img = document.getElementById('logoPreview');
            if (!img) {
                img = document.createElement('img');
                img.id = 'logoPreview';
                img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
                if (initial) initial.replaceWith(img);
            }
            img.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Cover preview
function previewCover(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('coverPreviewWrap').style.display = 'block';
            document.getElementById('coverPreview').src = e.target.result;
            document.getElementById('coverPlaceholder').style.display = 'none';
            document.getElementById('coverZone').style.borderColor = '#3b82f6';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Toggle opening hours day
function toggleDay(day, open) {
    const t1 = document.getElementById('time-' + day);
    const t2 = document.getElementById('time2-' + day);
    [t1, t2].forEach(el => {
        if (el) {
            el.style.opacity = open ? '1' : '.3';
            el.style.pointerEvents = open ? '' : 'none';
        }
    });
}

// Password strength
function strengthCheck(pw) {
    let score = 0;
    if (pw.length >= 8)          score++;
    if (/[A-Z]/.test(pw))         score++;
    if (/[0-9]/.test(pw))         score++;
    if (/[^A-Za-z0-9]/.test(pw)) score++;
    const map = [
        {w:'0%',   bg:'transparent', t:''},
        {w:'25%',  bg:'#ef4444',     t:'Weak'},
        {w:'50%',  bg:'#f97316',     t:'Fair'},
        {w:'75%',  bg:'#facc15',     t:'Good'},
        {w:'100%', bg:'#22c55e',     t:'Strong'},
    ];
    document.getElementById('pwBar').style.width      = map[score].w;
    document.getElementById('pwBar').style.background = map[score].bg;
    document.getElementById('pwLabel').textContent    = map[score].t;
    document.getElementById('pwLabel').style.color    = map[score].bg;
}
</script>

@endsection