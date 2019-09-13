<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PriceReport extends Mailable
{
    use Queueable, SerializesModels;

    public $queries;

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
}
