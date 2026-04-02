<?php

enum AccountProvider: string {
    case MANDIRI     = 'Mandiri';
    case BCA         = 'BCA';
    case BNI         = 'BNI';
    case BRI         = 'BRI';
    case CIMB        = 'CIMB Niaga';
    case DANAMON     = 'Danamon';
    case PERMATA     = 'Permata';
    case OCBC        = 'OCBC NISP';
    case JAGO        = 'Bank Jago';
    case NEO         = 'Bank Neo';
    case SEABANK     = 'SeaBank';

    case GOPAY       = 'GoPay';
    case OVO         = 'OVO';
    case DANA        = 'DANA';
    case SHOPEEPAY   = 'ShopeePay';
    case LINKAJA     = 'LinkAja';

    case LAINNYA     = 'Lainnya';

    public static function forType(string $type): array 
    {
        return match($type){
            'tabungan' => [
                self::MANDIRI, self::BCA, self::BNI, self::BRI,
                self::CIMB, self::DANAMON, self::PERMATA,
                self::OCBC, self::JAGO, self::NEO, self::SEABANK,
                self::LAINNYA,
            ],
            'ewallet' => [
                self::GOPAY, self::OVO, self::DANA,
                self::SHOPEEPAY, self::LINKAJA, self::LAINNYA,
            ],
            'tunai' => [],
            default => [],
        };    
    }
}