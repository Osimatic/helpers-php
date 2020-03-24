<?php

namespace Osimatic\Helpers;

class Country
{

	/**
	 * https://stackoverflow.com/questions/3191664/list-of-all-locales-and-their-short-codes
	 * https://stackoverflow.com/questions/10175658/is-there-a-simple-way-to-get-the-language-code-from-a-country-code-in-php
	 *
	 * @param $countryCode
	 * @return string
	 */
	public static function getLocaleByCountryCode($countryCode): ?string
	{
		$countryCode = strtoupper($countryCode);
		$countryCode = $countryCode==='UK'?'GB':$countryCode;

		$locales = [
			'af_ZA',
			'am_ET',
			'ar_AE',
			'ar_BH',
			'ar_DZ',
			'ar_EG',
			'ar_IQ',
			'ar_JO',
			'ar_KW',
			'ar_LB',
			'ar_LY',
			'ar_MA',
			'ar_OM',
			'ar_QA',
			'ar_SA',
			'ar_SY',
			'ar_TN',
			'ar_YE',
			'az_Cyrl_AZ',
			'az_Latn_AZ',
			'be_BY',
			'bg_BG',
			'bn_BD',
			'bs_Cyrl_BA',
			'bs_Latn_BA',
			'cs_CZ',
			'da_DK',
			'de_AT',
			'de_CH',
			'de_DE',
			'de_LI',
			'de_LU',
			'dv_MV',
			'el_GR',
			'en_AU',
			'en_BZ',
			'en_CA',
			'en_GB',
			'en_IE',
			'en_JM',
			'en_MY',
			'en_NZ',
			'en_SG',
			'en_TT',
			'en_US',
			'en_ZA',
			'en_ZW',
			'es_AR',
			'es_BO',
			'es_CL',
			'es_CO',
			'es_CR',
			'es_DO',
			'es_EC',
			'es_ES',
			'es_GT',
			'es_HN',
			'es_MX',
			'es_NI',
			'es_PA',
			'es_PE',
			'es_PR',
			'es_PY',
			'es_SV',
			'es_US',
			'es_UY',
			'es_VE',
			'et_EE',
			'fa_IR',
			'fi_FI',
			'fil_PH',
			'fo_FO',
			'fr_BE',
			'fr_CA',
			'fr_CH',
			'fr_FR',
			'fr_LU',
			'fr_MC',
			'he_IL',
			'hi_IN',
			'hr_BA',
			'hr_HR',
			'hu_HU',
			'hy_AM',
			'id_ID',
			'ig_NG',
			'is_IS',
			'it_CH',
			'it_IT',
			'ja_JP',
			'ka_GE',
			'kk_KZ',
			'kl_GL',
			'km_KH',
			'ko_KR',
			'ky_KG',
			'lb_LU',
			'lo_LA',
			'lt_LT',
			'lv_LV',
			'mi_NZ',
			'mk_MK',
			'mn_MN',
			'ms_BN',
			'ms_MY',
			'mt_MT',
			'nb_NO',
			'ne_NP',
			'nl_BE',
			'nl_NL',
			'pl_PL',
			'prs_AF',
			'ps_AF',
			'pt_BR',
			'pt_PT',
			'ro_RO',
			'ru_RU',
			'rw_RW',
			'sv_SE',
			'si_LK',
			'sk_SK',
			'sl_SI',
			'sq_AL',
			'sr_Cyrl_BA',
			'sr_Cyrl_CS',
			'sr_Cyrl_ME',
			'sr_Cyrl_RS',
			'sr_Latn_BA',
			'sr_Latn_CS',
			'sr_Latn_ME',
			'sr_Latn_RS',
			'sw_KE',
			'tg_Cyrl_TJ',
			'th_TH',
			'tk_TM',
			'tr_TR',
			'uk_UA',
			'ur_PK',
			'uz_Cyrl_UZ',
			'uz_Latn_UZ',
			'vi_VN',
			'wo_SN',
			'yo_NG',
			'zh_CN',
			'zh_HK',
			'zh_MO',
			'zh_SG',
			'zh_TW'
		];

		shuffle($locales);

		foreach ($locales as $lc) {
			if ($countryCode === \Locale::getRegion($lc)) {
				return $lc;
			}
		}

		return null;
	}

	/**
	 * @param $countryCode
	 * @return string|null
	 */
	public static function getLanguageByCountryCode($countryCode): ?string
	{
		$locale = self::getLocaleByCountryCode($countryCode);
		if (!empty($locale)) {
			return ucfirst(\Locale::getDisplayLanguage($locale, \Locale::getDefault()));
		}
		return null;
	}

}