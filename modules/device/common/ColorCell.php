<?php

declare(strict_types=1);

namespace app\modules\device\common;

use app\modules\device\models\Device;
use Carbon\Carbon;
use yii\base\BaseObject;

class ColorCell extends BaseObject
{
    public const DEFAULT_COUNT_YEAR = 4;

    /**
     * Дата текущей поверки
     */
    private ?Carbon $dateCurrentVerification = null;

    /**
     * Дата следующей поверки
     */
    private ?Carbon $dateNextVerification = null;

    /**
     * Текущая дата
     */
    private ?Carbon $dateCurrent = null;

    /**
     * Период поверки
     */
    public ?int $verificationPeriod = null;

    /**
     * Эталонное СИ
     */
    public bool $deviceStandard = false;

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->setDateCurrent();
    }

    /**
     * @param Carbon $date
     * @param int $period
     * @return string|bool
     */
    public static function getNextVerificationDate(Carbon $date, int $period): string|bool
    {
        return match ($period) {
            Device::PERIOD_ONE => $date->addMonths(3)->addDays(-1)->toDateString(),
            Device::PERIOD_TWO => $date->addMonths(6)->addDays(-1)->toDateString(),
            Device::PERIOD_TREE => $date->addYears(1)->addDays(-1)->toDateString(),
            Device::PERIOD_FOUR => $date->addYears(2)->addDays(-1)->toDateString(),
            Device::PERIOD_FIVE => $date->addYears(3)->addDays(-1)->toDateString(),
            Device::PERIOD_SIX => $date->addYears(4)->addDays(-1)->toDateString(),
            Device::PERIOD_SEVEN => $date->addYears(5)->addDays(-1)->toDateString(),
            Device::PERIOD_EIGHT => $date->addYears(6)->addDays(-1)->toDateString(),
            default => false,
        };
    }

    /**
     * @param string|null $dateCurrentVerification
     * @return void
     */
    public function setDateCurrentVerification(?string $dateCurrentVerification): void
    {
        $this->dateCurrentVerification = $dateCurrentVerification === null ?
            null : Carbon::parse(date('Y-m-d', strtotime($dateCurrentVerification)));
    }

    /**
     * @return Carbon|null
     */
    public function getDateCurrentVerification(): ?Carbon
    {
        return $this->dateCurrentVerification;
    }

    /**
     * @return Carbon|null
     */
    public function getDateNextVerification(): ?Carbon
    {
        return $this->dateNextVerification;
    }

    /**
     * @param string|null $dateNextVerification
     */
    public function setDateNextVerification(?string $dateNextVerification): void
    {
        $this->dateNextVerification = Carbon::parse(date('Y-m-d', strtotime($dateNextVerification)));
    }

    /**
     * @return Carbon|null
     */
    public function getDateCurrent(): ?Carbon
    {
        return $this->dateCurrent;
    }

    /**
     * @return void
     */
    public function setDateCurrent(): void
    {
        $this->dateCurrent = Carbon::now();
    }

    /**
     * @return array|string[]
     */
    public function getColorVerification(): array
    {
        if ($this->getDateCurrentVerification() === null)
            return ['style' => "background-color: #d2d6de!important;"];
        if ($this->getDeltaYear() >= self::DEFAULT_COUNT_YEAR) {
            $verificationPeriod = [
                Device::PERIOD_SIX => '4 года',
                Device::PERIOD_SEVEN => '5 лет',
                Device::PERIOD_EIGHT => '6 лет'
            ];

            return (array_key_exists($this->verificationPeriod, $verificationPeriod)) ?
                ['class' => "table-success"] :
                ['class' => "table-secondary"];
        }

        return $this->checkPeriod();
    }

    /**
     * @return int
     */
    public function getDeltaYear(): int
    {
        return $this->getDateCurrent()->diffInYears($this->getDateCurrentVerification());
    }

    /**
     * @return int
     */
    public function getDeltaMonth(): int
    {
        return $this->getDateCurrent()->diffInMonths($this->getDateCurrentVerification());
    }

    /**
     * @return int
     */
    public function getDeltaDay(): int
    {
        return $this->getDateCurrent()->diffInDays($this->getDateCurrentVerification());
    }

    /**
     * @return array|string[]
     */
    public function checkPeriod(): array
    {
        switch ($this->verificationPeriod) {
            case Device::PERIOD_ONE://'3 мес.'
                if ($this->getDeltaMonth() > 3) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
            case Device::PERIOD_TWO://'6 мес'
                if ($this->getDeltaMonth() > 6) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
            case Device::PERIOD_TREE://'1 год'
                if ($this->getDeltaMonth() > 12) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
            case Device::PERIOD_FOUR://'2 года'
                if ($this->getDeltaMonth() > 24) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
            case Device::PERIOD_FIVE://'3 года'
                if ($this->getDeltaMonth() > 36) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
            case Device::PERIOD_SIX://'4 года'
                if ($this->getDeltaMonth() > 48) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
            case Device::PERIOD_SEVEN://'5 лет'
                if ($this->getDeltaMonth() > 60) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
            case Device::PERIOD_EIGHT://'6 лет'
                if ($this->getDeltaMonth() > 72) {
                    return $this->deviceStandard ?
                        ['class' => "table-warning"] :
                        ['class' => "table-danger"];
                } elseif ($this->getDeltaMonth() === 0) {
                    return ['class' => "table-info"];
                } else {
                    return ['class' => "table-success"];
                }
        }
        return [];
    }
}
