<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Filament\Models\Contracts\FilamentUser;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'restaurant_id',
        'invite_token',        // ADD THIS
        'invite_accepted_at',  // ADD THIS
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'  => 'datetime',
            'password'           => 'hashed',
            'invite_accepted_at' => 'datetime', // ADD THIS
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('super_admin');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    // ADD THIS
    public function hasAcceptedInvite(): bool
    {
        return !is_null($this->invite_accepted_at);
    }
}