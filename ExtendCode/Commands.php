<?php

========================app/Console/Kernel.php================================
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}

========================app/Console/Commands/Kernel.php========================
--Switch all the baidu map coords to amap

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CoordConvert extends Command
{
    use \App\Traits\CURL;

    protected $name = 'patch:coord-convert';
    protected $description = 'Switch all the baidu map coords to amap.';

    public function fire()
    {
        set_time_limit(0);

        do {
            $coords = \DB::select('
                SELECT `shop_id`, CONCAT(`lng`,",",`lat`) AS `coords`
                FROM `jh_shop`
                WHERE `coord_convert` = 0
                AND `lng` <> ""
                AND `lat` <> ""
                LIMIT 32
            ');

            if (!$coords) {
                exit('No convert jobs found in records.'.PHP_EOL);
            }

            $this->patch($coords);
        } while ($coords);
    }

    public function patch($coords)
    {
        $coordStr = implode('|', array_column(collect($coords)
        ->map(function ($x) {
            return (array) $x;
        })
        ->toArray(), 'coords'));

        $api = 'http://restapi.amap.com/'
        .'v3/assistant/coordinate/convert?key='.env('AMAP_KEY')
        .'&locations='.$coordStr
        .'&coordsys=baidu'
        .'&output=json';

        $apiRes = $this->requestJsonApi($api, 'GET');

        if ((0 !== $apiRes['err']) ||
            (
                isset($apiRes['res']['status']) &&
                $apiRes['res']['status']=='0'
            ) || (
                !isset($apiRes['res']['locations']) ||
                !$apiRes['res']['locations']
            )
        ) {
            exit(
                'A call to Amap was failed, please try again later.'
                .PHP_EOL
                .'('
                .$apiRes['res']['info']
                .')'
            );
        }

        $locations = explode(';', $apiRes['res']['locations']);

        if (!$locations || (count($coords) != count($locations))) {
            exit('Exception occurred: counts unmatched');
        }

        foreach ($coords as $key => &$coord) {
            $_coord  = explode(',', $locations[$key]);
            $updated = \DB::table('jh_shop')
            ->where('shop_id', $coord->shop_id)
            ->update([
              'lng' => $_coord[0],
              'lat' => $_coord[1],
              'coord_convert' => 1,
            ]);

            usleep(500000);

            echo 'Coords of shop #',
            $coord->shop_id,
            ' converted ',
            ($updated ? 'successfully' : 'failed'),
            PHP_EOL;
        }

        echo 'Done!', PHP_EOL;
    }
}