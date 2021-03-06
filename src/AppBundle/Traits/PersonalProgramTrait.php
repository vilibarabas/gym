<?php

namespace AppBundle\Traits;

use AppBundle\Entity\PersonalProgram;

trait PersonalProgramTrait {
    private $choices = [
        'lifeStyle' => [
            'title' => 'Cum va descrieti activitatea fizica',
            'data' => [
                "APROAPE ZERO" => 1,
                "SCURTE PLIMBARI" => 2,
                "ANTRENAMENT 1-2 /SAPTAMANA" => 3,
                "ANTRENAMENT 3-5 /SAPTAMANA" => 4,
                "ANTRENAMENT 5-7 /SAPTAMANA" => 5
            ]
        ],
        'workStyle' => [
            'title' => 'Descrie o zi obisnuita din viata ta',
            'data' => [
                "LA BIROU" => 1,
                "LA BIROU DAR SI IESIRI REGULATE IN ORAS" => 2,
                "SUNT TOT TIMPUL IN MISCARE" => 3,
                "ACTIVITATE FIZICA" => 4,
                "ACASA" => 5,
            ]
        ],
        'sleepStyle' => [
            'title' => 'Cum va descrieti calitatea somnului',
            'data' => [
                "10 Ore de somn" => 1,
                "7-9 Ore de somn" => 2,
                "6 ore de somn" => 3,
                "Sub 6 Ore de somn" => 4,
            ]
        ],
        'eatStyle' => [
            'title' => 'Cate mese aveti intro zi',
            'data' => [
                "UNA" => 1,
                "DOUA" => 2,
                "TREI" => 3,
                "PATRU" => 4,
                "DIFERIT IN FIECARE ZI" => 4,
            ]
        ],
    ];

    private function getPersonalProgramByUserId($id) {
        $program = $this->getDoctrine()
            ->getRepository(PersonalProgram::class)
            ->findByUserRefId($id);

        return isset($program[0]) ? $program[0] : null;
    }

    private function getPersonalPrograms() {
        $program = $this->getDoctrine()
            ->getRepository(PersonalProgram::class)
            ->findAll();
        
        return $program;
    }

    private function getLifestyleSelectedValue($style, $view = false) {
        $lifeStyle = $style->getLifeStyleSelect();
        if($lifeStyle = json_decode($lifeStyle, true)) {
            $key = array_keys($lifeStyle)[0];
            $value = $lifeStyle[$key];

            if(isset($this->choices[$key])) {
                $data = array_flip($this->choices[$key]['data']);

                if(isset($data[$value])) {

                    $data = [
                        'title'=> $this->choices[$key]['title'],
                        'data' => $data[$value],
                    ];

                    if($view) {
                        return $data;
                    }

                    return json_encode($data);
                }
            }
        }

        return null;
    }
}