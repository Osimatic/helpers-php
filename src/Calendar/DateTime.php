<?php

namespace Osimatic\Helpers\Calendar;

class DateTime
{
	/**
	 * @return \DateTime
	 */
	public static function getCurrentDateTime(): \DateTime
	{
		try {
			return new \DateTime('now');
		} catch (\Exception $e) {}
		return null;
	}

	/**
	 * @param \DateTime $dateTime
	 * @param int $dateFormatter
	 * @param int $timeFormatter
	 * @param string|null $locale
	 * @return string
	 */
	public static function format(\DateTime $dateTime, int $dateFormatter, int $timeFormatter, ?string $locale=null): string
	{
		return \IntlDateFormatter::create($locale, $dateFormatter, $timeFormatter)->format($dateTime->getTimestamp());
	}

	/**
	 * @param \DateTime $dateTime
	 * @param string|null $locale
	 * @return string
	 */
	public static function formatDateTime(\DateTime $dateTime, ?string $locale=null): string
	{
		return \IntlDateFormatter::create($locale, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT)->format($dateTime->getTimestamp());
	}

	/**
	 * @param \DateTime $dateTime
	 * @param string|null $locale
	 * @param int $dateFormatter
	 * @return string
	 */
	public static function formatDate(\DateTime $dateTime, ?string $locale=null, int $dateFormatter=\IntlDateFormatter::SHORT): string
	{
		return \IntlDateFormatter::create($locale, $dateFormatter, \IntlDateFormatter::NONE)->format($dateTime->getTimestamp());
	}

	/**
	 * @param \DateTime $dateTime
	 * @param string|null $locale
	 * @return string
	 */
	public static function formatDateInLong(\DateTime $dateTime, ?string $locale=null): string
	{
		return self::formatDate($dateTime, $locale, \IntlDateFormatter::LONG);
	}

	/**
	 * @param \DateTime $dateTime
	 * @param string|null $locale
	 * @param int $timeFormatter
	 * @return string
	 */
	public static function formatTime(\DateTime $dateTime, ?string $locale=null, int $timeFormatter=\IntlDateFormatter::SHORT): string
	{
		return \IntlDateFormatter::create($locale, \IntlDateFormatter::NONE, $timeFormatter)->format($dateTime->getTimestamp());
	}


	/**
	 * @param \DateTime $dateTime
	 * @param string $dateFormatter
	 * @param string $timeFormatter
	 * @param string|null $locale
	 * @return string
	 */
	public static function formatFromTwig(?\DateTime $dateTime, string $dateFormatter='short', string $timeFormatter='short', ?string $locale=null): ?string
	{
		if (null === $dateTime) {
			return null;
		}

		return self::format($dateTime, self::getDateTimeFormatterFromTwig($dateFormatter), self::getDateTimeFormatterFromTwig($timeFormatter), $locale);
	}

	/**
	 * @param \DateTime $dateTime
	 * @param string $dateFormatter
	 * @param string|null $locale
	 * @return string
	 */
	public static function formatDateFromTwig(?\DateTime $dateTime, string $dateFormatter='short', ?string $locale=null): ?string
	{
		if (null === $dateTime) {
			return null;
		}

		return self::format($dateTime, self::getDateTimeFormatterFromTwig($dateFormatter), \IntlDateFormatter::NONE, $locale);
	}

	/**
	 * @param \DateTime $dateTime
	 * @param string $timeFormatter
	 * @param string|null $locale
	 * @return string
	 */
	public static function formatTimeFromTwig(?\DateTime $dateTime, string $timeFormatter='short', ?string $locale=null): ?string
	{
		if (null === $dateTime) {
			return null;
		}

		return self::format($dateTime, \IntlDateFormatter::NONE, self::getDateTimeFormatterFromTwig($timeFormatter), $locale);
	}

	/**
	 * @param string $formatter
	 * @return int
	 */
	private static function getDateTimeFormatterFromTwig(string $formatter): int
	{
		switch ($formatter) {
			case 'none': return \IntlDateFormatter::NONE;
			case 'full': return \IntlDateFormatter::FULL;
			case 'long': return \IntlDateFormatter::LONG;
			case 'medium': return \IntlDateFormatter::MEDIUM;
		}
		return \IntlDateFormatter::SHORT;
	}



	/**
	 * @param string $str
	 * @return null|\DateTime
	 */
	public static function parse(string $str): ?\DateTime
	{
		try {
			return new \DateTime($str);
		}
		catch (\Exception $e) { }
		return null;
	}

	/**
	 * @param string $str
	 * @return null|\DateTime
	 */
	public static function parseDate(string $str): ?\DateTime
	{
		if (empty($str)) {
			return null;
		}

		// Format YYYY-mm-ddTHH:ii:ss
		if (strlen($str) === strlen('YYYY-mm-ddTHH:ii:ss') && null !== ($dateTime = self::parseFromSqlDateTime($str))) {
			return $dateTime;
		}

		if (false !== SqlDate::check($sqlDate = SqlDate::parse($str))) {
			return self::parseFromSqlDateTime($sqlDate.' 00:00:00');
		}

		return null;
	}

	/**
	 * @param string $sqlDateTime
	 * @return \DateTime|null
	 */
	public static function parseFromSqlDateTime(string $sqlDateTime): ?\DateTime
	{
		try {
			return new \DateTime($sqlDateTime);
		} catch (\Exception $e) {}
		return null;
	}

	// ========== Comparaison ==========

	/**
	 * @param \DateTime $dateTime1
	 * @param \DateTime $dateTime2
	 * @return bool
	 */
	public static function isDateAfter(\DateTime $dateTime1, \DateTime $dateTime2): bool
	{
		return $dateTime1->format('Y-m-d') > $dateTime2->format('Y-m-d');
	}

	/**
	 * @param \DateTime $dateTime1
	 * @param \DateTime $dateTime2
	 * @return bool
	 */
	public static function isDateBefore(\DateTime $dateTime1, \DateTime $dateTime2): bool
	{
		return $dateTime1->format('Y-m-d') < $dateTime2->format('Y-m-d');
	}

	/**
	 * @param \DateTime $dateTime
	 * @return bool
	 */
	public static function isInThePast(\DateTime $dateTime): bool
	{
		return $dateTime < self::getCurrentDateTime();
	}

	/**
	 * @param \DateTime $dateTime
	 * @return bool
	 */
	public static function isInTheFuture(\DateTime $dateTime): bool
	{
		return $dateTime > self::getCurrentDateTime();
	}



	// ========== Jour ==========

	// Jours dans une semaine

	/**
	 * Jour ouvré avec jour férié ou non
	 * @param \DateTime $dateTime
	 * @param bool $withPublicHoliday
	 * @return bool
	 */
	public static function isWorkingDay(\DateTime $dateTime, bool $withPublicHoliday=true): bool
	{
		if (self::isWeekend($dateTime)) {
			return false;
		}
		if ($withPublicHoliday && self::isPublicHoliday($dateTime)) {
			return false;
		}
		return true;
	}

	/**
	 * Jour ouvrable avec jour férié ou non
	 * @param \DateTime $dateTime
	 * @param bool $withPublicHoliday
	 * @return bool
	 */
	public static function isBusinessDay(\DateTime $dateTime, bool $withPublicHoliday=true): bool
	{
		$dayOfWeek = (int) $dateTime->format('N');
		if ($dayOfWeek === 7) {
			return false;
		}
		if ($withPublicHoliday && self::isPublicHoliday($dateTime)) {
			return false;
		}
		return true;
	}

	/**
	 * @param \DateTime $dateTime
	 * @return bool
	 */
	public static function isWeekend(\DateTime $dateTime): bool
	{
		$dayOfWeek = (int) $dateTime->format('N');
		return ($dayOfWeek === 6 || $dayOfWeek === 7);
	}

	/**
	 * @param \DateTime $dateTime
	 * @param string $country
	 * @param array $options
	 * @return bool
	 */
	public static function isPublicHoliday(\DateTime $dateTime, string $country='FR', array $options=[]): bool
	{
		// todo
		return false;
	}

	/**
	 * @param \DateTime $dateTime
	 * @param int $nbDays
	 * @return \DateTime
	 */
	public static function moveBackOfNbDays(\DateTime $dateTime, int $nbDays): \DateTime
	{
		try {
			$dateTime = new \DateTime($dateTime->format('Y-m-d H:i:s'));
		} catch (\Exception $e) {
		}
		return $dateTime->modify('-'.$nbDays.' day');
	}

	/**
	 * @param \DateTime $dateTime
	 * @param int $nbDays
	 * @return \DateTime
	 */
	public static function moveForwardOfNbDays(\DateTime $dateTime, int $nbDays): \DateTime
	{
		try {
			$dateTime = new \DateTime($dateTime->format('Y-m-d H:i:s'));
		} catch (\Exception $e) {
		}
		return $dateTime->modify('+'.$nbDays.' day');
	}


	// ========== Semaine ==========

	/**
	 * @param \DateTime $dateTime
	 * @return array
	 */
	public static function getWeekNumber(\DateTime $dateTime): array
	{
		$weekNumber = $dateTime->format('W');
		$year = $dateTime->format('Y');
		// si weekNumber = 1 et que mois de sqlDate = 12, mettre year++
		if (((int)$weekNumber) === 1 && ((int)$dateTime->format('m')) === 12) {
			$year++;
		}
		return [$year, $weekNumber];
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getFirstDayOfCurrentWeek(): ?\DateTime
	{
		return self::parseFromSqlDateTime(SqlDate::getFirstDayOfWeek(date('Y'), date('m')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getLastDayOfCurrentWeek(): ?\DateTime
	{
		return self::parseFromSqlDateTime(SqlDate::getLastDayOfWeek(date('Y'), date('m')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getFirstDayOfPreviousWeek(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('first day of previous week')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getLastDayOfPreviousWeek(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('last day of previous week')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getFirstDayOfNextWeek(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('first day of next week')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getLastDayOfNextWeek(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('last day of next week')).' 00:00:00');
	}

	/**
	 * @param int $year
	 * @param int $week
	 * @return string
	 */
	public static function getFirstDayOfWeek(int $year, int $week): string
	{
		return self::parseFromSqlDateTime(SqlDate::getFirstDayOfWeek($year, $week).' 00:00:00');
	}

	/**
	 * @param int $year
	 * @param int $week
	 * @return string
	 */
	public static function getLastDayOfWeek(int $year, int $week): string
	{
		return self::parseFromSqlDateTime(SqlDate::getLastDayOfWeek($year, $week).' 00:00:00');
	}

	/**
	 * @param \DateTime $dateTime
	 * @param int $weekDay
	 * @return \DateTime
	 */
	public static function getNextWeekDay(\DateTime $dateTime, int $weekDay): \DateTime
	{
		$timestampCurrent = $dateTime->getTimestamp();
		while (((int) date('N', $timestampCurrent)) !== $weekDay) {
			$timestampCurrent += 86400;
		}
		return new \DateTime(date('Y-m-d H:i:s', $timestampCurrent));
	}

	// ========== Mois ==========

	/**
	 * @param \DateTime $dateTime
	 * @param int $nbMonths
	 * @return \DateTime
	 */
	public static function moveBackOfNbMonths(\DateTime $dateTime, int $nbMonths): \DateTime
	{
		try {
			$dateTime = new \DateTime($dateTime->format('Y-m-d H:i:s'));
		} catch (\Exception $e) {
		}
		return $dateTime->modify('-'.$nbMonths.' month');
	}

	/**
	 * @param \DateTime $dateTime
	 * @param int $nbMonths
	 * @return \DateTime
	 */
	public static function moveForwardOfNbMonths(\DateTime $dateTime, int $nbMonths): \DateTime
	{
		try {
			$dateTime = new \DateTime($dateTime->format('Y-m-d H:i:s'));
		} catch (\Exception $e) {
		}
		return $dateTime->modify('+'.$nbMonths.' month');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getFirstDayOfCurrentMonth(): ?\DateTime
	{
		return self::parseFromSqlDateTime(SqlDate::getFirstDayOfMonth(date('Y'), date('m')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getLastDayOfCurrentMonth(): ?\DateTime
	{
		return self::parseFromSqlDateTime(SqlDate::getLastDayOfMonth(date('Y'), date('m')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getFirstDayOfPreviousMonth(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('first day of previous month')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getLastDayOfPreviousMonth(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('last day of previous month')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getFirstDayOfNextMonth(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('first day of next month')).' 00:00:00');
	}

	/**
	 * @return \DateTime|null
	 */
	public static function getLastDayOfNextMonth(): ?\DateTime
	{
		return self::parseFromSqlDateTime(date('Y-m-d', strtotime('last day of next month')).' 00:00:00');
	}

	/**
	 * @param int $year
	 * @param int $month
	 * @return \DateTime|null
	 */
	public static function getFirstDayOfMonth(int $year, int $month): ?\DateTime
	{
		return self::parseFromSqlDateTime(SqlDate::getFirstDayOfMonth($year, $month).' 00:00:00');
	}

	/**
	 * @param int $year
	 * @param int $month
	 * @return \DateTime|null
	 */
	public static function getLastDayOfMonth(int $year, int $month): ?\DateTime
	{
		return self::parseFromSqlDateTime(SqlDate::getLastDayOfMonth($year, $month).' 00:00:00');
	}

}