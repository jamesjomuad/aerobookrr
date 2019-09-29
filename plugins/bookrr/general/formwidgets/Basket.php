<?php namespace Bookrr\General\FormWidgets;

use Backend\Classes\FormWidgetBase;
use ApplicationException;
use Lang;

/**
 * Basket Form Widget
 */
class Basket extends FormWidgetBase
{
    use \Backend\Traits\FormModelWidget;

    protected $defaultAlias = 'aero_basket';

    //
    // Configurable properties
    //

    public $keyFrom = 'id';

    public $nameFrom = 'name';

    public $descriptionFrom;

    public $title = 'backend::lang.recordfinder.find_record';

    public $prompt = 'Click the %s button to find a record';

    public $recordsPerPage = 10;

    public $scope;

    public $conditions;

    public $searchMode;

    public $searchScope;

    public $useRelation = true;

    public $modelClass;


    //
    // Object properties
    //

    public $relationModel;

    protected $listWidget;

    protected $searchWidget;


    public function init()
    {
        $this->fillFromConfig([
            'title',
            'prompt',
            'keyFrom',
            'nameFrom',
            'descriptionFrom',
            'scope',
            'conditions',
            'searchMode',
            'searchScope',
            'recordsPerPage',
            'useRelation',
            'modelClass',
        ]);

        if (!$this->useRelation && !class_exists($this->modelClass)) {
            throw new ApplicationException(Lang::get('backend::lang.recordfinder.invalid_model_class', ['modelClass' => $this->modelClass]));
        }

        /*
            List Widget & Search Widget
        */
        if (post('basket_flag')) {
            $this->listWidget = $this->makeListWidget();
            $this->listWidget->bindToController();

            $this->searchWidget = $this->makeSearchWidget();
            $this->searchWidget->bindToController();

            $this->listWidget->setSearchTerm($this->searchWidget->getActiveTerm());

            $this->searchWidget->bindEvent('search.submit', function () {
                $this->listWidget->setSearchTerm($this->searchWidget->getActiveTerm());
                return $this->listWidget->onRefresh();
            });
        }
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('container');
    }

    public function prepareVars()
    {
        $this->relationModel = $this->getLoadValue();

        if ($this->formField->disabled) {
            $this->previewMode = true;
        }

        $this->vars['value'] = $this->getLoadValue();
        $this->vars['field'] = $this->formField;
        $this->vars['descriptionValue'] = $this->getDescriptionValue();
        $this->vars['listWidget'] = $this->listWidget;
        $this->vars['searchWidget'] = $this->searchWidget;
        $this->vars['title'] = $this->title;
        $this->vars['prompt'] = str_replace('%s', '<i class="icon-th-list"></i>', e(trans($this->prompt)));
        $this->vars['items'] = $this->relationModel;
        
    }

    /**
     * @inheritDoc
     */
    protected function loadAssets()
    {
        $this->addJs('js/recordfinder.js');
        $this->addJs('js/basket.js', 'basket');
        $this->addCss('css/basket.css', 'basket');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }

    public function getLoadValue()
    {
        $value = null;

        if ($this->useRelation) {
            list($model, $attribute) = $this->resolveModelAttribute($this->valueFrom);
            if ($model !== null) {
                $value = $model->{$attribute};
            }
        } else {
            $value = $this->modelClass::find(parent::getLoadValue());
        }

        return $value;
    }

    public function getDescriptionValue()
    {
        if (!$this->relationModel || !$this->descriptionFrom) {
            return null;
        }

        return $this->relationModel->{$this->descriptionFrom};
    }

    public function onFindItem()
    {
        $this->prepareVars();

        /*
         * Purge the search term stored in session
         */
        if ($this->searchWidget) {
            $this->listWidget->setSearchTerm(null);
            $this->searchWidget->setActiveTerm(null);
        }

        return $this->makePartial('recordfinder_form');
    }

    protected function makeListWidget()
    {
        $config = $this->makeConfig($this->getConfig('list'));

        if ($this->useRelation) {
            $config->model = $this->getRelationModel();
        } else {
            $config->model = new $this->modelClass;
        }

        $config->alias = $this->alias . 'List';
        $config->showSetup = false;
        $config->showCheckboxes = false;
        $config->recordsPerPage = $this->recordsPerPage;
        $config->recordOnClick = sprintf("$('#%s').recordFinder('updateRecord', this, ':" . $this->keyFrom . "')", $this->getId());
        $widget = $this->makeWidget('Backend\Widgets\Lists', $config);

        $widget->setSearchOptions([
            'mode' => $this->searchMode,
            'scope' => $this->searchScope,
        ]);

        if ($sqlConditions = $this->conditions) {
            $widget->bindEvent('list.extendQueryBefore', function ($query) use ($sqlConditions) {
                $query->whereRaw($sqlConditions);
            });
        }
        elseif ($scopeMethod = $this->scope) {
            $widget->bindEvent('list.extendQueryBefore', function ($query) use ($scopeMethod) {
                $query->$scopeMethod($this->model);
            });
        }
        else {
            if ($this->useRelation) {
                $widget->bindEvent('list.extendQueryBefore', function ($query) {
                    $this->getRelationObject()->addDefinedConstraintsToQuery($query);
                });
            }
        }

        return $widget;
    }

    protected function makeSearchWidget()
    {
        $config = $this->makeConfig();
        $config->alias = $this->alias . 'Search';
        $config->growable = false;
        $config->prompt = 'backend::lang.list.search_prompt';
        $widget = $this->makeWidget('Backend\Widgets\Search', $config);
        $widget->cssClasses[] = 'recordfinder-search';
        return $widget;
    }

    public function onRefresh()
    {
        

        $value = post('_'.$this->getFieldName());
        
        if ($this->useRelation) {
            list($model, $attribute) = $this->resolveModelAttribute($this->valueFrom);
            $model->{$attribute} = $value;
        } else {
            $this->formField->value = $value;
        }

        $this->prepareVars();

        $this->vars['item'] = $this->getLoadValue();

        return [
            // '#'.$this->getId('container')   => $this->makePartial('recordfinder'),
            '^#'.$this->getId('items') => $this->makePartial('item')
        ];
    }

    public function getThumb($model)
    {
        if(!$model->images()->get()->isEmpty())
        {
            return $this->vars['thumb'] = $model->images()->firstOrFail()->getThumb(300, 200, 'crop');
        }
        else
        {
            return $this->vars['thumb'] = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAALUlEQVR42u3OQQEAAAQEMJec5sTw2RIsO9X1KAICAgICAgICAgICAgICAt+BA8W+P+F350g0AAAAAElFTkSuQmCC';
        }
    }
}
