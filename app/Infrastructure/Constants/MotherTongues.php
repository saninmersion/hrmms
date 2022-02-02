<?php

namespace App\Infrastructure\Constants;

use Illuminate\Support\Str;

/**
 * Class MotherTongues
 * @package App\Infrastructure\Constants
 */
final class MotherTongues
{
    public const LANGUAGE_NONE          = 'none';
    public const LANGUAGE_MAITHILI      = 'maithili';
    public const LANGUAGE_BHOJPURI      = 'bhojpuri';
    public const LANGUAGE_THARU         = 'tharu';
    public const LANGUAGE_TAMANG        = 'tamang';
    public const LANGUAGE_NEWARI        = 'newari';
    public const LANGUAGE_BAJJIKA       = 'bajjika';
    public const LANGUAGE_MAGAR         = 'magar';
    public const LANGUAGE_DOTELLI       = 'dotelli';
    public const LANGUAGE_URDU          = 'urdu';
    public const LANGUAGE_AWADHI        = 'awadhi';
    public const LANGUAGE_LIMBU         = 'limbu';
    public const LANGUAGE_GURUNG        = 'gurung';
    public const LANGUAGE_BAITDALEY     = 'baitdaley';
    public const LANGUAGE_RAI           = 'rai';
    public const LANGUAGE_ACHHAMI       = 'achhami';
    public const LANGUAGE_BANTAWA       = 'bantawa';
    public const LANGUAGE_RAJBANSHI     = 'rajbanshi';
    public const LANGUAGE_SHERPA        = 'sherpa';
    public const LANGUAGE_HINDI         = 'hindi';
    public const LANGUAGE_CHAMLING      = 'chamling';
    public const LANGUAGE_BAJHANGI      = 'bajhangi';
    public const LANGUAGE_SANTHALI      = 'santhali';
    public const LANGUAGE_CHEPANG       = 'chepang';
    public const LANGUAGE_DANUWAR       = 'danuwar';
    public const LANGUAGE_SUNUWAR       = 'sunuwar';
    public const LANGUAGE_MAGAHI        = 'magahi';
    public const LANGUAGE_URAUN_URAU    = 'uraun_urau';
    public const LANGUAGE_KULUNG        = 'kulung';
    public const LANGUAGE_KHAM          = 'kham';
    public const LANGUAGE_RAJASTHANI    = 'rajasthani';
    public const LANGUAGE_MAJHI         = 'majhi';
    public const LANGUAGE_THAMI         = 'thami';
    public const LANGUAGE_BHUJEL        = 'bhujel';
    public const LANGUAGE_BANGLA        = 'bangla';
    public const LANGUAGE_THULUNG       = 'thulung';
    public const LANGUAGE_YAKKHA        = 'yakkha';
    public const LANGUAGE_DHIMAL        = 'dhimal';
    public const LANGUAGE_TAJPURIA      = 'tajpuria';
    public const LANGUAGE_ANGIKA        = 'angika';
    public const LANGUAGE_SAMPANG       = 'sampang';
    public const LANGUAGE_KHALING       = 'khaling';
    public const LANGUAGE_YAMBULE       = 'yambule';
    public const LANGUAGE_KUMAL         = 'kumal';
    public const LANGUAGE_DARAI         = 'darai';
    public const LANGUAGE_BAHING        = 'bahing';
    public const LANGUAGE_BAJURELI      = 'bajureli';
    public const LANGUAGE_HYOLMO_YOLMO  = 'hyolmo_yolmo';
    public const LANGUAGE_NACHIRING     = 'nachiring';
    public const LANGUAGE_YAMPHU_YAMPHE = 'yamphu_yamphe';
    public const LANGUAGE_BOTE          = 'bote';
    public const LANGUAGE_GHALE         = 'ghale';
    public const LANGUAGE_DUMY          = 'dumy ';
    public const LANGUAGE_LAPCHA        = 'lapcha';
    public const LANGUAGE_PUMA          = 'puma';
    public const LANGUAGE_DUNGMALI      = 'dungmali';
    public const LANGUAGE_DARCHULELI    = 'darchuleli';
    public const LANGUAGE_AATHPAHARIYA  = 'aathpahariya';
    public const LANGUAGE_TIRED         = 'tired';
    public const LANGUAGE_JIREL         = 'jirel';
    public const LANGUAGE_MEWAHANG      = 'mewahang';
    public const LANGUAGE_SIGN_LANGUAGE = 'sign_language';
    public const LANGUAGE_TIBETAN       = 'tibetan';
    public const LANGUAGE_MECHE         = 'meche';
    public const LANGUAGE_CHHANTYAL     = 'chhantyal';
    public const LANGUAGE_RAAJI         = 'raaji';
    public const LANGUAGE_LOHORUNG      = 'lohorung';
    public const LANGUAGE_CHHINTANG     = 'chhintang';
    public const LANGUAGE_GANGAI        = 'gangai';
    public const LANGUAGE_WATCH         = 'watch';
    public const LANGUAGE_DAILEKHI      = 'dailekhi';
    public const LANGUAGE_LHOPA         = 'lhopa';
    public const LANGUAGE_DURA          = 'dura';
    public const LANGUAGE_KOCHEY        = 'kochey';
    public const LANGUAGE_CHILLING      = 'chilling';
    public const LANGUAGE_ENGLISH       = 'english';
    public const LANGUAGE_JERO_JERUNG   = 'jero_jerung';
    public const LANGUAGE_KHAS          = 'khas';
    public const LANGUAGE_SANSKRIT      = 'sanskrit';
    public const LANGUAGE_DOLPALI       = 'dolpali';
    public const LANGUAGE_HAYU_BHAYU    = 'hayu_bhayu';
    public const LANGUAGE_TILUNG        = 'tilung';
    public const LANGUAGE_KOYI          = 'koyi';
    public const LANGUAGE_KISHAN        = 'kishan';
    public const LANGUAGE_WALING_WALUNG = 'waling_walung';
    public const LANGUAGE_MUSALMAN      = 'musalman';
    public const LANGUAGE_HARYANVI      = 'haryanvi';
    public const LANGUAGE_JUMLI         = 'jumli';
    public const LANGUAGE_PUNJABI       = 'punjabi';
    public const LANGUAGE_LHOMI         = 'lhomi';
    public const LANGUAGE_BELHARE       = 'belhare';
    public const LANGUAGE_ORIYA         = 'oriya';
    public const LANGUAGE_SONAHA        = 'sonaha';
    public const LANGUAGE_SINDHI        = 'sindhi';
    public const LANGUAGE_DADELDHURI    = 'dadeldhuri';
    public const LANGUAGE_VYANSI        = 'vyansi ';
    public const LANGUAGE_ASSAMI        = 'assami';
    public const LANGUAGE_KHAMCHI_RAUTE = 'khamchi_raute';
    public const LANGUAGE_SAM           = 'sam';
    public const LANGUAGE_MANANGEY      = 'manangey';
    public const LANGUAGE_DHULELI       = 'dhuleli';
    public const LANGUAGE_PHANGDUWALI   = 'phangduwali';
    public const LANGUAGE_SUREL         = 'surel';
    public const LANGUAGE_MALPANDE      = 'malpande';
    public const LANGUAGE_CHINESE       = 'chinese';
    public const LANGUAGE_KHARIA        = 'kharia';
    public const LANGUAGE_KURMALI       = 'kurmali';
    public const LANGUAGE_BARAM         = 'baram';
    public const LANGUAGE_LINGKHIM      = 'lingkhim';
    public const LANGUAGE_SADHNI        = 'sadhni';
    public const LANGUAGE_KAGATEY       = 'kagatey';
    public const LANGUAGE_JONKHA        = 'jonkha';
    public const LANGUAGE_BANKARIA      = 'bankaria';
    public const LANGUAGE_KAIKE         = 'kaike';
    public const LANGUAGE_GARHWALI      = 'garhwali';
    public const LANGUAGE_FRENCH        = 'french';
    public const LANGUAGE_MIZO          = 'mizo';
    public const LANGUAGE_KUKI          = 'kuki';
    public const LANGUAGE_KUSHUNDA      = 'kushunda';
    public const LANGUAGE_RUSSIAN       = 'russian';
    public const LANGUAGE_SPANISH       = 'spanish';
    public const LANGUAGE_NAGAMIZ       = 'nagamiz';
    public const LANGUAGE_ARABIC        = 'arabic';
    public const LANGUAGE_OTHER         = 'other';

    /**
     * MotherTongues constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     * @SuppressWarnings("unused")
     */
    public static function motherTongues(): array
    {
        $all = self::all();

        return collect($all)->filter(
        // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
            function ($val, $constants) {
                return Str::startsWith($constants, 'LANGUAGE_');
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
