<?php

namespace App\Helpers;

class TerbilangHelper
{
    private static $angka = [
        '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
        'sepuluh', 'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
        'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'
    ];

    private static $satuan = [
        '', 'ribu', 'juta', 'miliar', 'triliun'
    ];

    /**
     * Convert number to Indonesian words (terbilang)
     *
     * @param int|float $angka
     * @return string
     */
    public static function convert($angka)
    {
        if ($angka == 0) {
            return 'nol';
        }

        if ($angka < 0) {
            return 'minus ' . self::convert(abs($angka));
        }

        // Handle decimal numbers
        if (is_float($angka) || strpos($angka, '.') !== false) {
            $parts = explode('.', $angka);
            $integerPart = (int) $parts[0];
            $decimalPart = isset($parts[1]) ? $parts[1] : '';
            
            $result = self::convert($integerPart);
            
            if (!empty($decimalPart)) {
                $result .= ' koma ';
                for ($i = 0; $i < strlen($decimalPart); $i++) {
                    $digit = $decimalPart[$i];
                    $result .= self::$angka[$digit] . ' ';
                }
            }
            
            return trim($result);
        }

        $angka = (int) $angka;
        
        if ($angka < 20) {
            return self::$angka[$angka];
        }

        if ($angka < 100) {
            return self::puluhan($angka);
        }

        if ($angka < 1000) {
            return self::ratusan($angka);
        }

        return self::ribuan($angka);
    }

    /**
     * Convert tens (10-99)
     */
    private static function puluhan($angka)
    {
        $puluh = floor($angka / 10);
        $satuan = $angka % 10;

        if ($satuan == 0) {
            return self::$angka[$puluh] . ' puluh';
        }

        return self::$angka[$puluh] . ' puluh ' . self::$angka[$satuan];
    }

    /**
     * Convert hundreds (100-999)
     */
    private static function ratusan($angka)
    {
        $ratus = floor($angka / 100);
        $sisa = $angka % 100;

        $result = '';

        if ($ratus == 1) {
            $result = 'seratus';
        } else {
            $result = self::$angka[$ratus] . ' ratus';
        }

        if ($sisa > 0) {
            if ($sisa < 20) {
                $result .= ' ' . self::$angka[$sisa];
            } else {
                $result .= ' ' . self::puluhan($sisa);
            }
        }

        return $result;
    }

    /**
     * Convert thousands and above
     */
    private static function ribuan($angka)
    {
        $groups = [];
        $groupIndex = 0;

        while ($angka > 0) {
            $group = $angka % 1000;
            if ($group > 0) {
                $groupText = '';

                if ($group < 20) {
                    $groupText = self::$angka[$group];
                } elseif ($group < 100) {
                    $groupText = self::puluhan($group);
                } else {
                    $groupText = self::ratusan($group);
                }

                // Special case for "satu ribu" -> "seribu"
                if ($groupIndex == 1 && $group == 1) {
                    $groupText = 'se';
                }

                if ($groupIndex > 0) {
                    $groupText .= ' ' . self::$satuan[$groupIndex];
                }

                array_unshift($groups, $groupText);
            }

            $angka = floor($angka / 1000);
            $groupIndex++;
        }

        return implode(' ', $groups);
    }

    /**
     * Convert currency amount to words
     *
     * @param int|float $amount
     * @param string $currency
     * @return string
     */
    public static function currency($amount, $currency = 'rupiah')
    {
        if ($amount == 0) {
            return 'nol ' . $currency;
        }

        // Handle decimal for currency
        if (is_float($amount) || strpos($amount, '.') !== false) {
            $parts = explode('.', number_format($amount, 2, '.', ''));
            $integerPart = (int) $parts[0];
            $decimalPart = isset($parts[1]) ? (int) $parts[1] : 0;
            
            $result = self::convert($integerPart) . ' ' . $currency;
            
            if ($decimalPart > 0) {
                $result .= ' ' . self::convert($decimalPart) . ' sen';
            }
            
            return $result;
        }

        return self::convert($amount) . ' ' . $currency;
    }

    /**
     * Format for official documents
     *
     * @param int|float $amount
     * @return string
     */
    public static function official($amount)
    {
        $terbilang = self::currency($amount, 'rupiah');
        return ucfirst($terbilang);
    }
}