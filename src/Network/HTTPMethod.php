<?php

namespace Osimatic\Network;

enum HTTPMethod: string
{
	case GET = 'GET';
    case POST = 'POST';
    case PATCH = 'PATCH';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';

}