<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\PlantelesModel;
use Illuminate\Http\Request;
use App\Models\User;

use Spatie\Permission\Models\Role;

class AuxscriptsController extends Controller
{
    public function asignar_rol()
    {
        $tutores = array(
            array('Álamos', 'miguel.barrona@bachilleresdesonora.edu.mx'),
            array('Álvaro Obregón Salido', 'elisa.moralesv@bachilleresdesonora.edu.mx'),
            array('BAHÍA DE KINO', 'paulo.garciar@bachilleresdesonora.edu.mx'),
            array('Caborca', 'marcela.figueroar@bachilleresdesonora.edu.mx'),
            array('California', 'luz.morenon@bachilleresdesonora.edu.mx'),
            array('California', 'cindy.escobarb@bachilleresdesonora.edu.mx'),
            array('Empalme', 'juanita.dyker@bachilleresdesonora.edu.mx'),
            array('Etchojoa', 'maria.ochoaj@bachilleresdesonora.edu.mx'),
            array('Eusebio Francisco Kino', 'edith.celayaa@bachilleresdesonora.edu.mx'),
            array('Faustino Félix Serna', 'daniel.villaa@bachilleresdesonora.edu.mx'),
            array('Lic. Alberto Flores Urbina', 'gina.romeror@bachilleresdesonora.edu.mx'),
            array('Lic. Alberto Flores Urbina', 'midori.obam@bachilleresdesonora.edu.mx'),
            array('Nacozari', 'alejandra.quijada@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'pedro.balderramap@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'maria.rodriguezd@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'arturo.veleza@bachilleresdesonora.edu.mx'),
            array('Hermosillo VII', 'olivia.lopeza@bachilleresdesonora.edu.mx'),
            array('Nogales', 'esteban.arevalod@bachilleresdesonora.edu.mx'),
            array('Nogales', 'erika.balderramab@bachilleresdesonora.edu.mx'),
            array('Nogales II', 'ana.valenzuelae@bachilleresdesonora.edu.mx'),
            array('Obregón II', 'brenda.sandovala@bachilleresdesonora.edu.mx'),
            array('Obregón II', 'maria.torresr@bachilleresdesonora.edu.mx'),
            array('Obregón III', 'erika.nunezc@bachilleresdesonora.edu.mx'),
            array('Plutarco Elías Calles', 'maria.villac@bachilleresdesonora.edu.mx '),
            array('Plutarco Elías Calles', 'norma.lauterioc@bachilleresdesonora.edu.mx'),
            array('Pueblo Yaqui', 'janeth.lopezc@bachilleresdesonora.edu.mx'),
            array('Puerto Peñasco', 'julio.morenov@bachilleresdesonora.edu.mx'),
            array('Quetchehueca', 'analy.cardenase@bachilleresdesonora.edu.mx'),
            array('José María Maytorena Tapia', 'lizbeth.carrillov@bachilleresdesonora.edu.mx'),
            array('José María Maytorena Tapia', 'francisco.armentar@bachilleresdesonora.edu.mx'),
            array('Villa de Seris', 'carmen.revillam@bachilleresdesonora.edu.mx'),
            array('Nuevo Hermosillo', 'karla.hernandezp@bachilleresdesonora.edu.mx'),
            array('San Ignacio Río Muerto', 'ana.felixo@bachilleresdesonora.edu.mx'),
            array('PROFR. ERNESTO LÓPEZ RIESGO', 'claudia.mirandaa@bachilleresdesonora.edu.mx'),
            array('JESÚS GUILLERMO CAREAGA CRUZ', 'alfredo.guzmanc@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'teresa.vallejor@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'raul.avitiac@bachilleresdesonora.edu.mx'),
            array('Sonoyta', 'martha.betancourts@bachilleresdesonora.edu.mx'),
            array('Álamos', 'belen.valenzuelag@bachilleresdesonora.edu.mx'),
            array('Álamos', 'eleazar.rossg@bachilleresdesonora.edu.mx'),
            array('Álvaro Obregón Salido', 'judith.changv@bachilleresdesonora.edu.mx'),
            array('Álvaro Obregón Salido', 'asbel.quijadar@bachilleresdesonora.edu.mx'),
            array('Bahía de Kino', 'guadalupe.yocupiciom@bachilleresdesonora.edu.mx'),
            array('Bahía de Kino', 'margarita.obesol@bachilleresdesonora.edu.mx'),
            array('Bahía de Kino', 'damaris.renteria@bachilleresdesonora.edu.mx'),
            array('Caborca', 'maria.gutierrezga@bachilleresdesonora.edu.mx'),
            array('Caborca', 'jesus.celayal@bachilleresdesonora.edu.mx'),
            array('Caborca', 'dania.egurrolac@bachilleresdesonora.edu.mx'),
            array('Caborca', 'rosa.chinchillasn@bachilleresdesonora.edu.mx'),
            array('California', 'diana.chavezr@bachilleresdesonora.edu.mx'),
            array('California', 'maría.contrerasp@bachilleresdesonora.edu.mx'),
            array('Empalme', 'sonia.valler@bachilleresdesonora.edu.mx'),
            array('Empalme', 'francisco.renteriam@bachilleresdesonora.edu.mx'),
            array('Etchojoa', 'erika.salcidol@bachilleresdesonora.edu.mx'),
            array('Etchojoa', 'margarita.islasb@bachilleresdesonora.edu.mx'),
            array('Eusebio Francisco Kino', 'monica.villarreale@bachilleresdesonora.edu.mx'),
            array('Eusebio Francisco Kino', 'ana.billyirigoyenm@bachilleresdesonora.edu.mx'),
            array('Faustino Félix Serna', 'maria.hagelsiebd@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'Ilse.zaratev@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'susett.roblesa@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'myriam.moraless@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'jesus.carrillom@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'claudia.hernandezd@bachilleresdesonora.edu.mx'),
            array('Hermosillo VII', 'edrei.s@bachilleresdesonora.edu.mx'),
            array('Hermosillo VII', 'ingrid.gamav@bachilleresdesonora.edu.mx'),
            array('Jesús Guillermo Careaga Cruz', 'josefina.reynosom@bachilleresdesonora.edu.mx'),
            array('Jesús Guillermo Careaga Cruz', 'henna.osunar@bachilleresdesonora.edu.mx'),
            array('Jesús Guillermo Careaga Cruz', 'frobles@bachilleresdesonora.edu.mx'),
            array('Jesús Guillermo Careaga Cruz', 'gabriela.fierrol@bachilleresdesonora.edu.mx'),
            array('José María Maytorena Tapia', 'carolina.gutierrezg@bachilleresdesonora.edu.mx'),
            array('José María Maytorena Tapia', 'maria.davisg@bachilleresdesonora.edu.mx'),
            array('Lic. Alberto Flores Urbina', 'ilse.castelom@bachilleresdesonora.edu.mx'),
            array('Lic. Alberto Flores Urbina', 'ana.leyvab@bachilleresdesonora.edu.mx'),
            array('Lic. Alberto Flores Urbina', 'josefa.hurtadoc@bachilleresdesonora.edu.mx'),
            array('Lic. Alberto Flores Urbina', 'martha.clarkv@bachilleresdesonora.edu.mx'),
            array('Nacozari', 'nylza.barriosc@bachilleresdesonora.edu.mx'),
            array('Nacozari', 'iris.perezp@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'claudia.gandarar@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'arely.alvarez@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'erika.salcidol@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'lourdes.mendivilc@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'karina.diazb@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'claudia.balderramac@bachilleresdesonora.edu.mx'),
            array('Nogales', 'maria.martinezcar@bachilleresdesonora.edu.mx'),
            array('Nogales', 'beatriz.rosasy@bachilleresdesonara.edu.mx'),
            array('Nogales', 'candy.cobosd@bachilleresdesonora.edu.mx'),
            array('Nogales', 'oralia.woolfolkp@bachilleresdesonora.edu.mx'),
            array('Nogales II', 'ana.valenzuelae@bachilleresdesonora.edu.mx'),
            array('Nogales II', 'iris.romeror@bachilleresdesonora.edu.mx'),
            array('Nuevo Hermosillo', 'carlota.martinezm@bachilleresdesonora.edu.mx'),
            array('Nuevo Hermosillo', 'zugey.carbajalf@bachilleresdesonora.edu.mx'),
            array('Nuevo Hermosillo', 'elia.sierrasl@bachilleresdesonora.edu.mx'),
            array('Nuevo Hermosillo', 'maria.bernalg@bachilleresdesonora.edu.mx'),
            array('Obregón II', 'monica.valenzuelah@bachilleresdesonora.edu.mx'),
            array('Obregón II', 'karla.licea@bachilleresdesonora.edu.mx'),
            array('Obregón III', 'karla.licea@bachilleresdesonora.edu.mx'),
            array('Obregón II', 'patricia.espinozas@bachilleresdesonora.edu.mx'),
            array('Obregón II', 'jesus.gonzalez@bachilleresdesonora.edu.mx'),
            array('Obregón III', 'lilia.saldivars@bachilleresdesonora.edu.mx'),
            array('Obregón III', 'maria.felixc@bachilleresdesonora.edu.mx'),
            array('Obregón III', 'maria.aganza@bachilleresdesonora.edu.mx'),
            array('Plutarco Elías Calles', 'veronica.molinam@bachilleresdesonora.edu.mx'),
            array('Plutarco Elías Calles', 'irma.retamoza@bachilleresdesonora.edu.mx'),
            array('Plutarco Elías Calles', 'juana.dealejandrog@bachilleresdesonora.edu.mx'),
            array('Profr. Ernesto López Riesgo', 'franceli.salcidov@bachilleresdesonora.edu.mx'),
            array('Profr. Ernesto López Riesgo', 'carolina.aguirrec@bachilleresdesonora.edu.mx'),
            array('Profr. Ernesto López Riesgo', 'veronica.grabac@bachilleresdesonora.edu.mx'),
            array('Profr. Ernesto López Riesgo', 'ingrid.kingg@bachilleresdesonora.edu.mx '),
            array('Profr. Ernesto López Riesgo', 'fatima.martinez@bachilleresdesonora.edu.mx'),
            array('Pueblo Yaqui', 'claudia.padillab@bachilleresdesonora.edu.mx'),
            array('Pueblo Yaqui', 'alejandro.monroye@bachilleresdesonora.edu.mx'),
            array('Puerto Peñasco', 'julia.gastelumo@bachilleresdesonora.edu.mx'),
            array('Puerto Peñasco', 'mara.luzaniag@bachilleresdesonora.edu.mx'),
            array('Puerto Peñasco', 'yolanda.sallardl@bachilleresdesonora.edu.mx'),
            array('Quetchehueca', 'leslie.ruizd@bachilleresdesonora.edu.mx'),
            array('San Ignacio Río Muerto', 'maricruz.rinconh@bachilleresdesonora.edu.mx'),
            array('San Ignacio Río Muerto', 'maria.garcias@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'mariana.lopezl@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'ana.jimenezl@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'dafne.moralesm@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'alexia.nunezg@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'jose.moraf@bachilleresdesonora.edu.mx'),
            array('San Luis Río Colorado', 'hernan.grijalvah@bachilleresdesonora.edu.mx'),
            array('Sonoyta', 'flora.vegat@bachilleresdesonora.edu.mx'),
            array('Sonoyta', 'diana.espinozar@bachilleresdesonora.edu.mx'),
            array('Sonoyta', 'rosa.sanchezm@bachilleresdesonora.edu.mx'),
            array('Villa de Seris', 'maria.morenof@bachilleresdesonora.edu.mx'),
            array('Villa de Seris', 'maria.fontesg@bachilleresdesonora.edu.mx'),
            array('Villa de Seris', 'gabriela.riverar@bachilleresdesonora.edu.mx'),
            array('Villa de Seris', 'guadalupe.medinam@bachilleresdesonora.edu.mx'),
            array('Villa de Seris', 'ramses.penunurit@bachilleresdesonora.edu.mx'),
            array('Bahía de Kino', 'graciela.higueraa@bachilleresdesonora.edu.mx'),
            array('Bahía de Kino', 'ana.galazp@bachilleresdesonora.edu.mx'),
            array('California', 'Joana.araizaw@bachilleresdesonora.Edu.mx'),
            array('California', 'lucia.perazad@bachilleresdesonora.edu.mx'),
            array('Empalme', 'maria.pilladov@bachilleresdesonora.edu.mx'),
            array('Faustino Félix Serna', 'betsaida.borbonv@bachilleresdesonora.edu.mx'),
            array('Hermosillo V', 'jesus.sandovals@bachilleresdesonora.edu.mx'),
            array('Jesús Guillermo Careaga Cruz', 'luisa.leond@bachilleresdesonora.edu.mx'),
            array('Jesús Guillermo Careaga Cruz', 'maria.moroyoquif@bachilleresdesonora.edu.mx'),
            array('Jesús Guillermo Careaga Cruz', 'luz.bracamontez@bachilleresdesonora.edu.mx '),
            array('Jesús Guillermo Careaga Cruz', 'manuel.cordovaa@bachilleresdesonora.edu.mx'),
            array('José María Maytorena Tapia', 'luz.rojol@bachilleresdesonora.edu.mx'),
            array('Lic. Alberto Flores Urbina', 'danae.moralesc@bachilleresdesonora.edu.mx'),
            array('Nacozari', 'irene.penad@bachilleresdesonora.edu.mx'),
            array('Nogales', 'ana.esquerg@bachilleresdesonora.edu.mx'),
            array('Obregón II', 'danae.moralesc@bachilleresdesonora.edu.mx'),
            array('Obregón III', 'maria.felixc@bachilleresdesonora.edu.mx'),
            array('Profr. Ernesto López Riesgo', 'marcela.marquezp@bachilleresdesonora.edu.mx'),
            array('Profr. Ernesto López Riesgo', 'maria.gonzalezt@bachilleresdesonora.edu.mx'),
            array('Pueblo Yaqui', 'patricia.millerv@bachilleresdesonora.edu.mx'),
            array('San Ignacio Río Muerto', 'ana.salasq@bachilleresdesonora.edu.mx'),
            array('Villa de Seris', 'claudia.hoyosd@bachilleresdesonora.edu.mx'),
            array('Navojoa', 'maestraadavegaee@gmail.com'),
            array('Nuevo Hermosillo', 'dulceyhe@gmail.com'),
            array('San Luis Río Colorado', 'psic.mayraralejandra@gmail.com')


        );


        foreach ($tutores as $aux) {

            $buscar_plantel = PlantelesModel::where('nombre', 'like', '%' . $aux[0] . '%')->first();

            if ($buscar_plantel) {
                $list = [];

                $rol_elegido = Role::where('name', '=', $buscar_plantel->abreviatura)->first();

                //dd($buscar_rol);

                array_push($list, $rol_elegido->name);

                //dd(json_encode($list));

                $user = User::where('email', $aux[1])->first();
                

                
                /*
                if($user->email == "jesus.sandovals@bachilleresdesonora.edu.mx"){
                }*/
                if ($user) {
                    foreach ($user->getRoleNames() as $rolNombre){
                        array_push($list, $rolNombre);
                    }
                    //if(Auth()->user()->hasPermissionTo('user-editar'))
                    $user->save();

                    $user->syncRoles($list);
                } else {
                    echo "usuario no encontrado: " . $aux[1];
                    echo "<br>";
                }

            } else {
                echo "Plantel no encontrado: " . $aux[0];
                echo "<br>";
            }

        }

        echo "Ya termine de ejecutar";
    }
}
