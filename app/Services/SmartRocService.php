<?php

namespace App\Services;

use App\Models\Data_training;
use App\Repositories\SubCriteriaRepository;
use Illuminate\Http\Request;

class SmartRocService
{

    private Data_training $model;
    private SubCriteriaRepository $repo;

    function __construct(Data_training $model, SubCriteriaRepository $repo)
    {
        $this->model = $model;
        $this->repo = $repo;
    }

    public function train($data_uji)
    {
        $features = [
            'penghasilan',
            'pekerjaan',
            'perkawinan',
            'calon_penghuni',
            'status_penempatan'
        ];



        $sub = $this->repo->grouped()->toArray();

        $total_data = $this->model->count();
        $total_layak = $this->model->where('kelayakan', 1)->count();
        $total_tidak_layak = $this->model->where('kelayakan', 0)->count();

        $probability_layak = $total_layak / $total_data;
        $probability_tidak_layak = $total_tidak_layak / $total_data;

        $temp = [];
        foreach ($features as $index => $feature) {
            $k = $index + 1;
            $alpha = 1;
            foreach ($sub["{$k}"] as $subc) {
                $vl = $this->model->where($feature, $data_uji[$feature])->where('kelayakan', '=', 1)->get(['id', $feature, 'kelayakan'])->count();
                $vtl = $this->model->where($feature, $data_uji[$feature])->where('kelayakan', '=', 0)->get(['id', $feature, 'kelayakan'])->count();
                $temp["layak"][$feature] = ($vl + $alpha) / ($alpha + $total_layak);
                $temp["tidak_layak"][$feature] = ($vtl + $alpha) / ($alpha + $total_tidak_layak);
            }
        }

        $mpcl = 1;
        $mpctl = 1;
        foreach ($temp["layak"] as $rec) {
            $mpcl *= $rec;
        }

        foreach ($temp["tidak_layak"] as $rec) {
            $mpctl *= $rec;
        }

        $layak = $mpcl * $probability_layak;
        $tidak_layak = $mpctl * $probability_tidak_layak;
        $keputusan = ($layak > $tidak_layak) ? 1 : 0;
        $data_uji['kelayakan'] = $keputusan;
        $data_uji['prob_layak'] = $layak;
        $data_uji['prob_tidak_layak'] = $tidak_layak;
        $data_uji['status'] = 1;
        $data_uji['created_by'] = "guest";
        $data_uji['ticket'] = $this->generateUniqueTicket();

        $this->model->create($data_uji);

        $result = [
            "data_input" => $data_uji,
            "ticket" => $data_uji['ticket'],
            "total_data" => $total_data,
            "total_layak" => $total_layak,
            "total_tidak_layak" => $total_tidak_layak,
            "probability_layak" => $probability_layak,
            "probability_tidak_layak" => $probability_tidak_layak,
            "temp" => $temp,
            "total_perkalian_kelayakan" => $mpcl,
            "total_perkalian_ketidak_layakan" => $mpctl,
            "prob_layak" => $layak,
            "prob_tidak_layak" => $tidak_layak,
            "keputusan" => ($keputusan == 1) ? "layak" : "tidak layak"
        ];
        return $result;
    }

    private function generateUniqueTicket(): string
    {
        do {
            $ticket = substr(bin2hex(random_bytes(8)), 0, 12);
        } while ($this->model->where('ticket', $ticket)->exists());

        return $ticket;
    }
}
