<?php

	namespace App\Console\Commands;

	use App\Mail\PriceReport;
	use App\Query;
	use Illuminate\Console\Command;
	use Illuminate\Support\Facades\Config;
	use Illuminate\Support\Facades\DB;
	use Illuminate\Support\Facades\Mail;
	use Mockery\Exception;
	use Symfony\Component\CssSelector\CssSelectorConverter;

	class gather extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'gather';

		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Gathers information from shops.';

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
		 * @return mixed
		 */
		public function handle()
		{
			$this->line("Gathering new data");

			$stores = DB::table("stores")->orderBy("store_name", "asc")->get();
			$this->fetchPrices($stores);

			$queries = DB::table("queries")
				->whereRaw("created_at > NOW() - INTERVAL 5 HOUR")
				->select(["source_short", "source_url", "price", "price_per_packet"])
				->orderBy("price_per_packet", "asc")
				->get();

			Mail::to(Config::get("catfood.mailto"))
				->cc(Config::get("catfood.mailcc"))
				->send(new PriceReport($queries));
		}

		/**
		 * @param string $url
		 *
		 * @return string
		 */
		private function getSite(string $url):string {
			$this->line("Getting ".$url);
			$ch = curl_init();
			$timeout = 10;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:69.0) Gecko/20100101 Firefox/69.0');
			$data = curl_exec($ch);
			curl_close($ch);
			return $data;
		}

		private function htmlSelect(string $html, string $selector): ?string {
			libxml_use_internal_errors(true);
			$dom = new \DOMDocument();
			$dom->recover = true;
			$dom->loadHTML($html);
			libxml_use_internal_errors(false);
			$x = new \DOMXPath($dom);
			$converter = new CssSelectorConverter();
			$xpath = $converter->toXPath( $selector);
			$ret = $x->query($xpath);
			if (isset($ret[0]->textContent)) {
                return $ret[0]->textContent;
            }
			else {
			    return null;
            }
		}

		/**
		 * @param $stores
		 */
		private function fetchPrices($stores) : void
		{
			foreach ($stores as $store) {
				$this->line("Fetching " . $store->store_name);
				$price = 0;
				try {
					$html = $this->getSite($store->url);
					$price = intval(preg_replace('/[^0-9]/', '', $this->htmlSelect($html, $store->xpath)));
                    if (!$price) {
                        continue;
                    }
				}
				catch (Exception $e) {
					$this->error("Could not fetch store. " . $e);
				}
				if ($price == 0) continue;
				$this->line("Found price: " . $price);

				$query = new Query();
				$query->source_url = $store->url;
				$query->source_short = $store->store_name;
				$query->price = $price;
				$query->price_per_packet = round($price/$store->packet);
				$saved = $query->save();
				if (!$saved) {

				}
			}
		}

	}
