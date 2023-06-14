<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Booking;

class DailyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // return 0;
        $this->info('daily:Run successfully!');
        $book = Booking::where('created_at', '>=', Carbon::now()->subMinute(1))->get();
        foreach($book as $row)
        {
            if($row->accepted != 1)
            {
                Booking::where('id',$row->id)->update(['rejected' => 1, 'accepted' => 0,'status' => 'Cancelled']);
                $this->info('daily:update Cummand Run successfully!');
            }
            // else
            // {
            //     Booking::where('id',$row->id)->update(['rejected' => 1, 'accepted' => 0,'status' => 'Cancelled']);
            // }
        }
    }
}
