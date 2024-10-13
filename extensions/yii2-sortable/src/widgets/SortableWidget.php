<?php

declare(strict_types=1);

namespace kilyanov\sortable\widgets;

use Closure;
use kilyanov\sortable\assets\SortableAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Sortable;

class SortableWidget extends Sortable
{
    /**
     * @var string|Closure
     */
    public string|Closure $attributeContent = '';

    /**
     * @var array|string[]
     */
    public array $icons = [
        'moving' => '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.4.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M278.6 9.4c-12.5-12.5-32.8-12.5-45.3 0l-64 64c-9.2 9.2-11.9 22.9-6.9 34.9s16.6 19.8 29.6 19.8h32v96H128V192c0-12.9-7.8-24.6-19.8-29.6s-25.7-2.2-34.9 6.9l-64 64c-12.5 12.5-12.5 32.8 0 45.3l64 64c9.2 9.2 22.9 11.9 34.9 6.9s19.8-16.6 19.8-29.6V288h96v96H192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l64 64c12.5 12.5 32.8 12.5 45.3 0l64-64c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8H288V288h96v32c0 12.9 7.8 24.6 19.8 29.6s25.7 2.2 34.9-6.9l64-64c12.5-12.5 12.5-32.8 0-45.3l-64-64c-9.2-9.2-22.9-11.9-34.9-6.9s-19.8 16.6-19.8 29.6v32H288V128h32c12.9 0 24.6-7.8 29.6-19.8s2.2-25.7-6.9-34.9l-64-64z"/></svg>',
    ];

    /**
     * @var array
     */
    public $options = [
        'class' => 'ui-sortable list-group',
    ];

    /**
     * @var array
     */
    public $clientOptions = [
        'axis' => 'y',
        'handle' => '.handle',
    ];

    /**
     * @var array
     */
    public array $additionalControls = [];

    /**
     * @return void
     */
    public function init(): void
    {
        parent::init();
        $this->items = $this->normalizeItems(ArrayHelper::toArray($this->items));
    }

    /**
     * @param array $item
     * @return string|null
     */
    public function renderItem(array $item): string|null
    {
        $handle = Html::tag('div', self::icon('moving'), ['class' => 'handle']);
        $additionalControls = '';
        if ($this->additionalControls) {
            foreach ($this->additionalControls as $key => $control) {
                $additionalControls .= ($control instanceof Closure ? call_user_func($control, $item) :
                        Html::a($control, Url::to([$key]))
                    ) . PHP_EOL;
            }
        }

        $control = Html::tag('div', $additionalControls, ['class' => 'control']);

        $content = $this->attributeContent instanceof Closure ? call_user_func(
            $this->attributeContent,
            $item
        ) : $item[$this->attributeContent];

        return Html::tag('div', $handle . $content . $control, ['class' => 'list-group-item']);
    }

    /**
     * @param string $name
     * @return string
     */
    public function icon(string $name): string
    {
        return $this->icons[$name];
    }

    /**
     * @param array $items
     *
     * @return array
     */
    protected function normalizeItems(array $items): array
    {
        foreach ($items as &$row) {
            $row['content'] = $this->renderItem($row);
            $row['options'] = [
                'data-id' => $row['id'],
            ];
        }

        return $items;
    }

    /**
     * @param $name
     * @param $id
     * @return void
     */
    protected function registerWidget($name, $id = null): void
    {
        parent::registerWidget($name, $id);
        SortableAsset::register($this->getView());
    }
}
