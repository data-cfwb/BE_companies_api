<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    use HasFactory;

    protected $table = 'enterprise';

    // define the variables and their types
    protected $casts = [
        'EnterpriseNumber' => 'string',
        'Status' => 'string',
        'JuridicalSituation' => 'string',
        'TypeOfEnterprise' => 'string',
        'JuridicalForm' => 'string',
        'JuridicalFormCAC' => 'string',
        'StartDate' => 'date',
    ];

    public function StatusLabel()
    {
        return $this->hasMany(Code::class, 'Code', 'Status')
        ->where('Category', 'Status')
        ->orderBy('Code');
    }

    public function TypeOfEnterpriseLabel()
    {
        return $this->hasMany(Code::class, 'Code', 'TypeOfEnterprise')
        ->where('Category', 'TypeOfEnterprise')
        ->select(['Language','Description']);
    }

    public function JuridicalSituationLabel()
    {
        return $this->hasMany(Code::class, 'Code', 'JuridicalSituation')
        ->where('Category', 'JuridicalSituation')
        ->select(['Language','Description']);
    }

    public function JuridicalFormLabel()
    {
        return $this->hasMany(Code::class, 'Code', 'JuridicalForm')
        ->where('Category', 'JuridicalForm')
        ->select(['Language','Description']);
    }

    public function JuridicalFormCACLabel()
    {
        return $this->hasMany(Code::class, 'Code', 'JuridicalForm')
        ->where('Category', 'JuridicalForm')
        ->select(['Language','Description']);
    }
    
    public function denominations()
    {
        return $this->hasMany(Denomination::class, 'EntityNumber', 'EnterpriseNumber');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'EntityNumber', 'EnterpriseNumber');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'EntityNumber', 'EnterpriseNumber');
    }

    public function establishments()
    {
        return $this->hasMany(Establishment::class, 'EnterpriseNumber', 'EnterpriseNumber')->orderBy('StartDate', 'desc');
    }

    public function subsidies()
    {
        return $this->hasMany(Subsidie::class, 'EnterpriseNumber', 'EnterpriseNumber')->orderBy('Year', 'desc');
    }

    public function subsidies_group_by_year()
    {
        return $this->hasMany(Subsidie::class, 'EnterpriseNumber', 'EnterpriseNumber')
        ->selectRaw('format(round(sum("AmountInEuros"), 2),2) as total, Year as year')
        ->groupBy('year')
        ->orderBy('year', 'desc');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class, 'EnterpriseNumber', 'EnterpriseNumber');
    }

    // activity
    public function activities()
    {
        return $this->hasMany(Activity::class, 'EntityNumber', 'EnterpriseNumber')
        ->orderBy('Classification');
    }

    public function getSubsidiesMapByYearAttribute()
    {
        return $this->subsidies->groupBy('Year');
    }

    public function getSubsidiesGroupByYearForChartAttribute()
    {
        $labels = $this->subsidies_group_by_year->map(function ($item, $key) {
            return $item->year;
        });

        $values = $this->subsidies_group_by_year->map(function ($item, $key) {
            return $item->total;
        });

        return [
            'labels' => $labels,
            'values' => $values
        ];
    }


    ## EnterpriseNumber without the dot
    public function getEnterpriseNumberDotLessAttribute()
    {
        return str_replace('.', '', $this->EnterpriseNumber);
    }

    ## EnterpriseNumber starting with 'BE'
    public function getEnterpriseNumberBEAttribute()
    {
        return 'BE ' . $this->EnterpriseNumber;
    }
    
    # external links
    public function getExternalLinksAttribute()
    {
        return [
            'notaire' => [
                'service_name' => 'Statuts et pouvoirs de représentation',
                'href' =>  'https://statuts.notaire.be/stapor_v1/enterprise/' . $this->EnterpriseNumberDotLess . '/statutes',
            ],
            'nbb' => [
                'service_name' => 'Central Balance Sheet Office',
                'href' =>  'https://consult.cbso.nbb.be/consult-enterprise/' . $this->EnterpriseNumberDotLess,
            ],
            'ejustice' => [
                'service_name' => 'ejustice',
                'href' =>  'http://www.ejustice.just.fgov.be/cgi_tsv/tsv_rech.pl?language=fr&btw=' . $this->EnterpriseNumberDotLess . '&liste=Liste',
            ],
            'bce' => [
                'service_name' => 'Banque-Carrefour des Entreprises',
                'href' =>  'https://kbopub.economie.fgov.be/kbopub/toonondernemingps.html?lang=fr&ondernemingsnummer=' . $this->EnterpriseNumberDotLess,
            ],
            'map' => [
                'service_name' => 'Address',
                'href' => ($this->addresses->first() ? 'https://www.openstreetmap.org/search?query=' . urlencode($this->addresses->first()->short) : 'no address found'),
            ],
            'cadastre_2019' => [
                'service_name' => 'Cadastre des subventions 2019',
                'href' => 'https://www.odwb.be/explore/dataset/fwb-cadastre-des-subventions-2019/table/?disjunctive.ministre&disjunctive.competence&disjunctive.administration&disjunctive.code_postal&disjunctive.forme_juridique&sort=-liquidation&q=' . urlencode($this->denominations->first()->Denomination),
            ],
            'cadastre_2020' => [
                'service_name' => 'Cadastre des subventions 2020',
                'href' => 'https://www.odwb.be/explore/dataset/fwb-cadastre-subventions-2020/table/?disjunctive.ministre&disjunctive.competence&disjunctive.administration&disjunctive.code_postal&disjunctive.forme_juridique&sort=-liquidation&q=' . urlencode($this->denominations->first()->Denomination),
            ],
            'cadastre_2021' => [
                'service_name' => 'Cadastre des subventions 2021',
                'href' => 'https://www.odwb.be/explore/dataset/fwb-cadastre-des-subventions-2021/table/?disjunctive.ministre&disjunctive.competence&disjunctive.administration&disjunctive.code_postal&disjunctive.forme_juridique&sort=-liquidation&q=' . urlencode($this->denominations->first()->Denomination),
            ],
            'cadastre_2022' => [
                'service_name' => 'Cadastre des subventions 2022',
                'href' => 'https://www.odwb.be/explore/dataset/fwb-cadastre-des-subventions-2022/table/?disjunctive.ministre&disjunctive.competence&disjunctive.administration&disjunctive.code_postal&disjunctive.forme_juridique&sort=-liquidation&q=' . urlencode($this->denominations->first()->Denomination),
            ],

        ];
    }
}
