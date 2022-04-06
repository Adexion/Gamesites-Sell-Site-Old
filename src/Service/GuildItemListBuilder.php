<?php

namespace App\Service;

class GuildItemListBuilder
{
    private static int $total = 27;

    /*
     * cols order:
     * 2
     * 1
     * 3
     */

    public function buildList(array $items): array
    {
        if (empty($items)) {
            return $items;
        }

        $count = count($items);
        $cols = ceil($count / 9);

        for ($i = 1; $i <= $cols; $i++) {
            $x = [];
            for ($j = 0; $j < min($count, 9); $j++) {
                $x[] = current($items);
            }

            $tmp[] = $x;
            $count -= 9;
        }

        $res = array_reverse($tmp ?? []);

        $first = count($res) - 1;
        $result[$first] = $this->addEmptyCells($res[$first]);
        for ($i = 0; $i < 3; $i++) {
            if ($i === $first) {
                continue;
            }

            $result[$i] = $this->addEmptyCells($res[$i] ?? []);
        }

        ksort($result);
        return $result;
    }

    private function addEmptyCells(array $res): array
    {
        if (count($res) === 9) {
            return $res;
        }

        $cellsL = ceil((9 - count($res)) / 2);
        $cellsR = floor((9 - count($res)) / 2);

        return array_merge($this->genEmpty($cellsL), $res, $this->genEmpty($cellsR));
    }

    private function genEmpty(int $count): array
    {
        for ($i = 0; $i < $count; $i++) {
            $tmp[] = null;
        }

        return $tmp ?? [];
    }
}