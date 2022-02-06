<?php

namespace App\Service;

use DateTime;
use Exception;

class BansMapper
{
    public function map(array $bans): array
    {
        return array_map(function ($ban) {
            $ban['value'] = ($this::getDateByTimestamp($ban['value'])
                ?: $this::getDateFromString($ban['value'])
                ?: new DateTime())->format('d.m.Y H:i');

            return $ban;
        }, $bans);
    }

    public static function getDateByTimestamp($value): ?DateTime
    {
        try {
            return new DateTime('@'.substr($value, 0, -3));
        } catch (Exception $e) {
        }

        return null;
    }

    public static function getDateFromString($value): ?DateTime
    {
        try {
            return new DateTime($value);
        } catch (Exception $e) {
        }

        return null;
    }
}