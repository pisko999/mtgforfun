<?php

use App\Models\Edition;
use App\Repositories\EditionRepository;
use App\Services\MKMService;
use Illuminate\Database\Seeder;

class mkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $mkm = new MKMService();
        $editionRepository = new EditionRepository(new Edition());

        $answer = $mkm->getExpansions();

        foreach ($answer->expansion as $expansion) {
            $local = $editionRepository->getByName($expansion->enName);
            if ($local != null) {
                $local->idExpansionMKM = $expansion->idExpansion;
                $local->save();
            } else {
                if ($expansion->abbreviation[0] == "X")
                    $expansion->abbreviation[0] = "p";
                $local = $editionRepository->getBySign(strtolower($expansion->abbreviation)); //naopak odebrat jedno pismeno
                if ($local != null) {
                    $local->idExpansionMKM = $expansion->idExpansion;
                    $local->save();
                } else {
                    echo $expansion->idExpansion . " " . $expansion->abbreviation . " " . $expansion->enName . "</br>";

                }

            }
        }
    }
}
