<?php

namespace App\Enum;

use MyCLabs\Enum\Enum;

class PaymentStatusResponseEnum extends Enum
{
    const CREATED = 'This payment is not accepted. If it is not right pleas contact with your administrator.';
    const UNACCEPTED = 'This payment is not accepted. If it is not right pleas contact with your administrator.';
    const CANCELED = 'This payment is not accepted. If it is not right pleas contact with your administrator.';
    const FAILURE = 'This payment is not accepted. If it is not right pleas contact with your administrator.';

    const PENDING = 'This payment is still pending. Pleas not log out from server.';

    const TIME_OUT = 'This payment can not checked correctly. Pleas contact with administrator.';
    const NOT_ON_SERVER = 'You are not connected to serwer! Contact with administration and give him this payment ID';
    const REALIZED = 'Payment realized successfully.';
    const NOT_EXISTED = 'Given payment does not exist.';
}