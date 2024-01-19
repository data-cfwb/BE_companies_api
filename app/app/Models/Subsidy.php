<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subsidy extends Model
{

    protected $table = 'subsidies';
   // define the variables and their types
   protected $casts = [
    'AmountInEuros' => 'float',
    'Year' => 'integer'
    ];
}
