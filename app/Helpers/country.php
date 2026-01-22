<?php

if (! function_exists('countryName')) {
    function countryName($codeOrName)
    {
        $map = [
            'AE' => 'United Arab Emirates',
            'EG' => 'Egypt',
            'US' => 'United States',
            'SA' => 'Saudi Arabia',
            'JO' => 'Jordan',
            'LB' => 'Lebanon',
            'SY' => 'Syria',
            'IQ' => 'Iraq',
            'MA' => 'Morocco',
            'DZ' => 'Algeria',
            'TN' => 'Tunisia',
            'SD' => 'Sudan',
            'LY' => 'Libya',
            'YE' => 'Yemen',
            'OM' => 'Oman',
            'QA' => 'Qatar',
            'KW' => 'Kuwait',
            'BH' => 'Bahrain',
            'PS' => 'Palestine',
            'TR' => 'Turkey',
            'IR' => 'Iran',
            'FR' => 'France',
            'DE' => 'Germany',
            'GB' => 'United Kingdom',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'CA' => 'Canada',
            'RU' => 'Russia',
            'CN' => 'China',
            'IN' => 'India',
            'BR' => 'Brazil',
            'ZA' => 'South Africa',
            // أضف المزيد حسب الحاجة
        ];
        // إذا كانت القيمة اسم دولة بالفعل، أرجعها كما هي
        if (in_array($codeOrName, $map)) {
            return $codeOrName;
        }

        // إذا كانت كود دولة، أرجع الاسم
        return $map[$codeOrName] ?? $codeOrName;
    }
}
