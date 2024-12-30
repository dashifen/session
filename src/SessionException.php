<?php

namespace Dashifen\Session;

use Dashifen\Exception\Exception;

class SessionException extends Exception {
	public const int CANNOT_START = 1;
}
