<?php  

namespace App\Models;                                                                                                                               
use App\Models\Order;
use App\Models\Role;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

  #[Fillable(['name', 'email', 'password', 'role_id', 'pin_code'])]
  #[Hidden(['password', 'remember_token', 'pin_code'])]
  class User extends Authenticatable
  {
      /** @use HasFactory<UserFactory> */
      use HasFactory, Notifiable, HasApiTokens;

      protected function casts(): array
      {
          return [
              'email_verified_at' => 'datetime',
              'password' => 'hashed',
          ];
      }

      public function role()
      {
          return $this->belongsTo(Role::class);
      }

      public function orders()
      {
          return $this->hasMany(Order::class);
      }

      public function hasAbility(string $ability): bool
      {
          return $this->role->abilities()->where('name', $ability)->exists();
      }
  }
