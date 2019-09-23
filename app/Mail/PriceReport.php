<?php

	namespace App\Mail;

	use Illuminate\Bus\Queueable;
	use Illuminate\Mail\Mailable;
	use Illuminate\Queue\SerializesModels;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Support\Facades\DB;

	class PriceReport extends Mailable
	{
		use Queueable, SerializesModels;

		public $queries;
		public $graphUrl;

		/**
		 * PriceReport constructor.
		 *
		 * @param $queries
		 */
		public function __construct($queries)
		{
			$this->subject("Cicaétel árak");
			$this->from(env("MAIL_USERNAME"), "Cicakaja ellenőrző");
			$this->queries = $queries;
			$this->graphUrl = $this->getGraphURL();
		}


		/**
		 * Build the message.
		 *
		 * @return $this
		 */
		public function build()
		{
			return $this->view('mail');
		}


		private function getGraphURL() {


			$days = DB::table("queries")
				->select(DB::raw("MIN(price_per_packet) as minprice, DATE(created_at) as cr"))
				->whereRaw("created_at > NOW() - INTERVAL 8 DAY")
				->groupBy("cr")
				->orderBy("cr", "asc")
				->get();

			$labels = [];
			$prices = [];
			$onlabel = [];

			foreach ($days as $day) {
				$labels[] = $day->cr;
				$prices[] = $day->minprice;
				$onlabel[] = number_format($day->minprice, "0", ",", " ")." Ft";
			}

			return
				'https://image-charts.com/chart?cht=bvg&chd=t:'.implode(",", $prices).'&chs=623x300&chxt=x,y&chxl=0:|'.implode("|", $labels).'&chf=b0,lg,90,2990a0,1,34b2c5,0.2&chl='.implode("|", $onlabel).'&chxs=1N**Ft&chtt=Whiskas ár (napi minimum/csomag)&chma=0,0&chxr=1,0,2000,500&chds=0,2000';
				}
	}
