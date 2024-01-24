<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Enterprise;
use App\Models\Code;

class Denomination extends Model
{
    use HasFactory;

    protected $table = 'denomination';


    protected $casts = [
        'EntityNumber' => 'string',
        'Language' => 'string',
        'TypeOfDenomination' => 'string',
        'Denomination' => 'string',
    ];

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'EntityNumber', 'EnterpriseNumber');
    }

    public function language_label()
    {
        return $this->hasOne(Code::class, 'Code', 'Language')
        ->where('Category', 'Language')
        ->select(['Description']);
    }

    public function type_label()
    {
        return $this->hasOne(Code::class, 'Code', 'TypeOfDenomination')
        ->where('Category', 'TypeOfDenomination')
        ->select(['Description']);
    }

    public function getShortLanguageLabelAttribute()
    { 
        switch ($this->Language) {
            case '1':
                return 'fr';
                break;
            case '2':
                return 'nl';
                break;
            case '3':
                return 'de';
                break;
            case '4':
                return 'en';
                break;
            default:
                return 'unknown';
                break;
        }
    }

    // public function getTypLabelAttribute()
    // { 
    //     switch ($this->TypeOfDenomination) {
    //         case '1':
    //             return 'fr';
    //             break;
    //         case '2':
    //             return 'nl';
    //             break;
    //         case '3':
    //             return 'de';
    //             break;
    //         default:
    //             return 'unknown';
    //             break;
    //     }
    // }
}
