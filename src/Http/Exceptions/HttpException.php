<?php

namespace Hyvor\Helper\Http\Exceptions;

use Exception;

/**
 * This exception should be thrown in case of an error in an HTTP request.
 * In most cases, this will then be converted to JSON and sent to the client.
 */
class HttpException extends Exception
{}