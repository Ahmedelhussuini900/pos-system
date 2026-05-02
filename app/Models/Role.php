<?php

namespace App\Models;

use App\Models\Ability;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

  class Role extends Model
  {
      use HasFactory;

      protected $guarded = [];

      public function users()
      {
          return $this->hasMany(User::class);
      }

      public function abilities()
      {
          return $this->belongsToMany(Ability::class, 'role_ability');
      }
  }
