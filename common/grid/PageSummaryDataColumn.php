<?php

declare(strict_types=1);

namespace app\common\grid;

use Closure;
use Exception;
use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class PageSummaryDataColumn extends DataColumn
{
    /**
     * @var string
     */
    public string $cfgAttrUrl = 'id';

    /**
     * @var string|null|Closure
     */
    public null|string|Closure $url = null;

    /**
     * @var string
     */
    public string $role = '';

    /**
     * @var array
     */
    public array $formatShow = ['decimal', 2];

    /**
     * @var string
     */
    public string $cssClass = 'btn btn-outline-secondary';

    /**
     * @var float
     */
    private float $total = 0.00;

    /**
     * @throws Exception
     */
    public function getDataCellValue($model, $key, $index): null|string|int|float
    {
        $value = null;
        if ($this->value !== null) {
            if (is_string($this->value)) {
                $value = ArrayHelper::getValue($model, $this->value);
            } elseif ($this->value instanceof Closure) {
                $value = call_user_func($this->value, $model, $key, $index, $this);
            }
            else {
                $value = $this->value;
            }
        } elseif ($this->attribute !== null) {
            $value = ArrayHelper::getValue($model, $this->attribute);
        }

        $this->total += (float)$value;

        $options = !empty($this->role) ? [
            'class' => $this->cssClass,
            'role' => $this->role
        ] : [
            'target' => '_blank',
            'data-pjax' => 0,
            'class' => $this->cssClass,
        ];
        if ($this->url !== null) {
            if (is_string($this->url)) {
                $url = $this->url;
            } else {
                $url = call_user_func($this->url, $model, $key, $index, $this);
            }
        }
        else {
            $url = null;
        }

        return !empty($url) ?
            Html::a(
                !empty($value) ? $value : 'не задано',
                [$url, $this->cfgAttrUrl => $model->id],
                $options
            ) : $value;
    }

    /**
     * @return float
     */
    public function getTotal(): float
    {
        return $this->total;
    }

    /**
     * @param float $total
     */
    public function setTotal(float $total): void
    {
        $this->total += $total;
    }

    /**
     * @return string|float|null
     */
    protected function renderFooterCellContent(): string|float|null
    {
        return $this->grid->formatter->format(
            $this->total,
            !empty($this->url) ?
                $this->formatShow : $this->format
        );
    }

}
