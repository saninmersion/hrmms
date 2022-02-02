<?php

namespace App\Infrastructure\Constants;

use Illuminate\Support\Str;

/**
 * Class Ethnicities
 * @package App\Infrastructure\Constants
 */
final class Ethnicities
{
    public const ETHNICITY_CHHETRI                = 'chhetri';
    public const ETHNICITY_BRAHMIN_HILLY          = 'brahmin_hilly';
    public const ETHNICITY_MAGAR                  = 'magar';
    public const ETHNICITY_THARU                  = 'tharu';
    public const ETHNICITY_TAMANG                 = 'tamang';
    public const ETHNICITY_NEWAR                  = 'newar';
    public const ETHNICITY_KAAMI                  = 'kaami';
    public const ETHNICITY_MUSALMAN               = 'musalman';
    public const ETHNICITY_YADAV                  = 'yadav';
    public const ETHNICITY_RAI                    = 'rai';
    public const ETHNICITY_GURUNG                 = 'gurung';
    public const ETHNICITY_DAMAI_DHOLI            = 'damai/dholi';
    public const ETHNICITY_THAKURI                = 'thakuri';
    public const ETHNICITY_LIMBU                  = 'limbu';
    public const ETHNICITY_SARKI                  = 'sarki';
    public const ETHNICITY_TELI                   = 'teli';
    public const ETHNICITY_CHAMAR_HARIJAN_RAM     = 'chamar/harijan/ram';
    public const ETHNICITY_KOIRI_KUSHWAHA         = 'koiri/kushwaha';
    public const ETHNICITY_MUSAHAR                = 'musahar';
    public const ETHNICITY_KURMI                  = 'kurmi';
    public const ETHNICITY_SANYASI_DASNAMI        = 'sanyasi/dasnami';
    public const ETHNICITY_DHANUK                 = 'dhanuk';
    public const ETHNICITY_DUSADH_PASWAN_PASI     = 'dusadh/paswan/pasi';
    public const ETHNICITY_MALLAHA                = 'mallaha';
    public const ETHNICITY_KEWAT                  = 'kewat';
    public const ETHNICITY_KATHBANIYA             = 'kathbaniya';
    public const ETHNICITY_BRAHMIN_TERAI          = 'brahmin_terai';
    public const ETHNICITY_KALAWAR                = 'kalawar';
    public const ETHNICITY_KANU                   = 'kanu';
    public const ETHNICITY_KUMAL                  = 'kumal';
    public const ETHNICITY_GHARTI_BHUJEL          = 'gharti/bhujel';
    public const ETHNICITY_HAZAM_THAKUR           = 'hazam/thakur';
    public const ETHNICITY_RAJBANSHI              = 'rajbanshi';
    public const ETHNICITY_SHERPA                 = 'sherpa';
    public const ETHNICITY_DHOBI                  = 'dhobi';
    public const ETHNICITY_TATMA_TATWA            = 'tatma/tatwa';
    public const ETHNICITY_LOHAR                  = 'lohar';
    public const ETHNICITY_KHATWE                 = 'khatwe';
    public const ETHNICITY_SUNDI                  = 'sundi';
    public const ETHNICITY_DANUWAR                = 'danuwar';
    public const ETHNICITY_HALUWAI                = 'haluwai';
    public const ETHNICITY_MAAJHI                 = 'maajhi';
    public const ETHNICITY_BARAI                  = 'barai';
    public const ETHNICITY_BIN                    = 'bin';
    public const ETHNICITY_NUNIA                  = 'nunia';
    public const ETHNICITY_CHEPANG_PRAJA          = 'chepang/praja';
    public const ETHNICITY_SONAR                  = 'sonar';
    public const ETHNICITY_KUMHAR                 = 'kumhar';
    public const ETHNICITY_SUNUWAR                = 'sunuwar';
    public const ETHNICITY_BATAR_CHIEF            = 'batar/chief';
    public const ETHNICITY_KAHAR                  = 'kahar';
    public const ETHNICITY_SATAR_SANTHAL          = 'satar/santhal';
    public const ETHNICITY_MARWADI                = 'marwadi';
    public const ETHNICITY_KAYASTHA               = 'kayastha';
    public const ETHNICITY_RAJPUT                 = 'rajput';
    public const ETHNICITY_BADI                   = 'badi';
    public const ETHNICITY_JHANGAD_DHAGAR         = 'jhangad/dhagar';
    public const ETHNICITY_GANGAI                 = 'gangai';
    public const ETHNICITY_LODH                   = 'lodh';
    public const ETHNICITY_CARPENTER              = 'carpenter';
    public const ETHNICITY_THAMI                  = 'thami';
    public const ETHNICITY_KULUNG                 = 'kulung';
    public const ETHNICITY_BANGALI                = 'BANGALI ';
    public const ETHNICITY_GADERI_BHEDIYAR        = 'gaderi/bhediyar';
    public const ETHNICITY_DHIMAL                 = 'dhimal';
    public const ETHNICITY_YAKKHA                 = 'yakkha';
    public const ETHNICITY_GHALE                  = 'ghale';
    public const ETHNICITY_TAJPURIA               = 'tajpuria';
    public const ETHNICITY_KHAWAS                 = 'khawas';
    public const ETHNICITY_DARAI                  = 'darai';
    public const ETHNICITY_MAALI                  = 'maali';
    public const ETHNICITY_DHUNIYA                = 'dhuniya';
    public const ETHNICITY_PAHARI                 = 'pahari';
    public const ETHNICITY_RAJDHOV                = 'rajdhov';
    public const ETHNICITY_BHOTE                  = 'bhote';
    public const ETHNICITY_DOME                   = 'dome';
    public const ETHNICITY_THAKALI                = 'thakali';
    public const ETHNICITY_KORI                   = 'kori';
    public const ETHNICITY_CHHANTYAL_CHHANTEL     = 'chhantyal/chhantel';
    public const ETHNICITY_HOLMO                  = 'holmo';
    public const ETHNICITY_BOTE                   = 'bote';
    public const ETHNICITY_RAJBHAR                = 'rajbhar';
    public const ETHNICITY_BARAMO_BARAM_BARAMU    = 'baramo/baram/baramu';
    public const ETHNICITY_PUNJABI_SIKH           = 'punjabi/sikh';
    public const ETHNICITY_NACHIRING              = 'nachiring';
    public const ETHNICITY_YAMPHU                 = 'yamphu';
    public const ETHNICITY_GAINEY                 = 'GAINEY ';
    public const ETHNICITY_CHAMLING               = 'chamling';
    public const ETHNICITY_AATHPAHARIYA           = 'aathpahariya';
    public const ETHNICITY_JIREL                  = 'jirel';
    public const ETHNICITY_DURA                   = 'dura';
    public const ETHNICITY_SARWARIYA              = 'sarwariya';
    public const ETHNICITY_MECHE                  = 'meche';
    public const ETHNICITY_BANTAWA                = 'bantawa';
    public const ETHNICITY_RAAJI                  = 'raaji';
    public const ETHNICITY_DOLPO                  = 'dolpo';
    public const ETHNICITY_HALKHOR                = 'halkhor';
    public const ETHNICITY_VYASI_SOUKA            = 'vyasi/souka';
    public const ETHNICITY_AMAT                   = 'amat';
    public const ETHNICITY_THULUNG                = 'thulung';
    public const ETHNICITY_LEPCHA                 = 'lepcha';
    public const ETHNICITY_PATTHARKATTA_KUSHWADIA = 'pattharkatta/kushwadia';
    public const ETHNICITY_MEWAHANG               = 'mewahang';
    public const ETHNICITY_BAHING                 = 'bahing';
    public const ETHNICITY_NATUWA                 = 'natuwa';
    public const ETHNICITY_HAYU                   = 'hayu';
    public const ETHNICITY_DHANKAR_DHARIKAR       = 'dhankar/dharikar';
    public const ETHNICITY_LHOPA                  = 'lhopa';
    public const ETHNICITY_MUNDA                  = 'munda';
    public const ETHNICITY_DEV                    = 'dev';
    public const ETHNICITY_DHANDI                 = 'dhandi';
    public const ETHNICITY_KAMAR                  = 'kamar';
    public const ETHNICITY_KISAN                  = 'kisan';
    public const ETHNICITY_SAMPANG                = 'sampang';
    public const ETHNICITY_KOCHEY                 = 'kochey';
    public const ETHNICITY_LHOMI                  = 'lhomi';
    public const ETHNICITY_KHALING                = 'khaling';
    public const ETHNICITY_TOPKEGOLA              = 'topkegola';
    public const ETHNICITY_CHIDIMAR               = 'chidimar';
    public const ETHNICITY_WALUNG                 = 'walung';
    public const ETHNICITY_LOHARUNG               = 'loharung';
    public const ETHNICITY_KALAR                  = 'kalar';
    public const ETHNICITY_RAUTE                  = 'raute';
    public const ETHNICITY_NURANG                 = 'nurang';
    public const ETHNICITY_KUSHUNDA               = 'kushunda';
    public const ETHNICITY_RANA_THARU             = 'rana_tharu';
    public const ETHNICITY_BHUMIHAR               = 'bhumihar';
    public const ETHNICITY_FOREIGN                = 'foreign';
    public const ETHNICITY_OTHER                  = 'other';

    /**
     * Ethnicities constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function ethnicities(): array
    {
        $all = self::all();

        return collect($all)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'ETHNICITY_');
            }
        )->values()->toArray();
    }

    /**
     * @return array
     */
    protected static function all(): array
    {
        try {
            $reflectionClass = new \ReflectionClass(__CLASS__);
        } catch (\ReflectionException $exception) {
            return [];
        }

        return $reflectionClass->getConstants();
    }
}
