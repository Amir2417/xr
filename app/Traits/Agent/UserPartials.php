<?php

namespace App\Traits\Agent;

use App\Models\AgentQrCode;

trait UserPartials{
	protected function user(){
		return userGuard()['user'];
	}
}
