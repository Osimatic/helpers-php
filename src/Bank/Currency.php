<?php

namespace Osimatic\Helpers\Bank;

use Symfony\Component\Intl\Currencies;

class Currency
{
	/**
	 * @param string $countryCode
	 * @return string
	 */
	public static function getCurrencyOfCountry(string $countryCode): string
	{
		return (new \NumberFormatter(
			\Osimatic\Helpers\Location\Country::getLocaleByCountryCode($countryCode),
			\NumberFormatter::CURRENCY
		))->getTextAttribute(\NumberFormatter::CURRENCY_CODE);
	}

	/**
	 * @param string $countryCode
	 * @return int
	 */
	public static function getNumericCodeOfCountry(string $countryCode): int
	{
		return self::getNumericCode(self::getCurrencyOfCountry($countryCode));
	}

	/**
	 * @param string $currencyCode
	 * @return int
	 */
	public static function getNumericCode(string $currencyCode): int
	{
		return Currencies::getNumericCode($currencyCode);
	}

	/**
	 * @param float $number
	 * @param string $currency
	 * @param int $decimals
	 * @return string
	 */
	public static function format(float $number, string $currency, int $decimals=2): string
	{
		$fmt = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::CURRENCY);
		$fmt->setTextAttribute(\NumberFormatter::CURRENCY_CODE, $currency);
		$fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, $decimals);
		return $fmt->formatCurrency($number, $currency);
	}

	/**
	 * @param float $number
	 * @param string $currency
	 * @param int $decimals
	 * @return string
	 */
	public static function formatWithCode(float $number, string $currency, int $decimals=2): string
	{
		$fmt = new \NumberFormatter(\Locale::getDefault(), \NumberFormatter::DECIMAL);
		$fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, $decimals);
		return $fmt->format($number).' '.$currency;
	}

}
